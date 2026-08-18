<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\PermissionRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;

class AttendanceService
{
    /**
     * Catat absensi mandiri siswa ketika siswa scan QR General atau QR Kelas dari HP
     */
    public function recordSelfAttendance(Student $student, string $scannedQrPayload, string $scanType = 'auto'): array
    {
        $payload = trim($scannedQrPayload);

        // 1. Cek apakah QR General Sekolah
        if ($payload === 'SMAIT_GENERAL_ATTENDANCE_QR' || str_contains($payload, 'SMAIT_GENERAL_ATTENDANCE_QR')) {
            return $this->recordAttendance($student->qr_token, $student->user, $scanType);
        }

        // 2. Cek apakah QR Khusus Kelas
        if (str_starts_with($payload, 'SMAIT_CLASS_QR_') || str_contains($payload, 'SMAIT_CLASS_QR_')) {
            preg_match('/SMAIT_CLASS_QR_(\d+)/', $payload, $matches);
            $classId = isset($matches[1]) ? (int) $matches[1] : null;

            if ($classId && $student->class_id != $classId) {
                $targetClass = SchoolClass::find($classId);
                $targetClassName = $targetClass ? $targetClass->name : "ID {$classId}";
                return [
                    'success' => false,
                    'code' => 'WRONG_CLASS',
                    'message' => "QR Presensi ini khusus untuk Kelas {$targetClassName}. Anda terdaftar di Kelas {$student->schoolClass->name}.",
                ];
            }

            return $this->recordAttendance($student->qr_token, $student->user, $scanType);
        }

        return [
            'success' => false,
            'code' => 'INVALID_QR',
            'message' => 'Kode QR yang Anda scan tidak valid untuk presensi sekolah.',
        ];
    }

    /**
     * Catat absensi siswa melalui Scan QR Token atau NISN (Masuk / Pulang)
     */
    public function recordAttendance(string $tokenOrIdentifier, ?User $scanner = null, string $scanType = 'auto'): array
    {
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');

        // 1. Cari data siswa berdasarkan qr_token atau nisn
        $student = Student::with(['schoolClass', 'user'])
            ->where('qr_token', $tokenOrIdentifier)
            ->orWhere('nisn', $tokenOrIdentifier)
            ->orWhere('nis', $tokenOrIdentifier)
            ->first();

        if (!$student) {
            return [
                'success' => false,
                'code' => 'NOT_FOUND',
                'message' => 'Data siswa atau Token QR tidak ditemukan dalam sistem.',
            ];
        }

        // 2. Cek status aktif siswa
        if ($student->status !== 'aktif') {
            return [
                'success' => false,
                'code' => 'INACTIVE_STUDENT',
                'message' => "Siswa {$student->name} berstatus {$student->status}, tidak dapat melakukan absensi.",
            ];
        }

        // 3. Ambil setting jam absensi
        $settings = AttendanceSetting::current();
        $checkInStart = Carbon::createFromTimeString($settings->check_in_start);
        $onTimeLimit = Carbon::createFromTimeString($settings->on_time_until);
        $lateLimit = Carbon::createFromTimeString($settings->late_until);
        
        $checkOutStart = $settings->check_out_start ? Carbon::createFromTimeString($settings->check_out_start) : null;
        $checkOutEnd = $settings->check_out_end ? Carbon::createFromTimeString($settings->check_out_end) : null;

        // Tentukan apakah aksi saat ini adalah Check-Out (Absen Pulang)
        $isCheckoutTime = false;
        if ($scanType === 'check_out') {
            $isCheckoutTime = true;
        } elseif ($scanType === 'auto' && $settings->allow_checkout && $checkOutStart) {
            $isCheckoutTime = $now->gte($checkOutStart);
        }

        // 4. Cari data absensi hari ini
        $existing = Attendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        // --- SKENARIO 1: ABSENSI PULANG (CHECK-OUT) ---
        if ($isCheckoutTime) {
            if ($existing) {
                if ($existing->check_out) {
                    return [
                        'success' => false,
                        'code' => 'DUPLICATE_CHECKOUT',
                        'student' => $student,
                        'attendance' => $existing,
                        'message' => "Siswa {$student->name} sudah melakukan absensi pulang pada pukul " . substr($existing->check_out, 0, 5) . " WIB.",
                    ];
                }

                // Update jam pulang
                $existing->update([
                    'check_out' => $currentTime,
                    'scanned_by' => $scanner?->id ?? $existing->scanned_by,
                ]);

                return [
                    'success' => true,
                    'type' => 'check_out',
                    'code' => 'CHECKOUT_RECORDED',
                    'student' => $student,
                    'attendance' => $existing,
                    'status' => $existing->status,
                    'check_in' => $existing->check_in,
                    'check_out' => $currentTime,
                    'late_minutes' => $existing->late_minutes,
                    'message' => "Absensi Pulang Berhasil: {$student->name} (Pukul {$currentTime})",
                ];
            } else {
                // Belum pernah absen masuk di pagi hari, langsung absen saat jam pulang
                $attendance = Attendance::create([
                    'student_id' => $student->id,
                    'date' => $today,
                    'check_in' => $currentTime,
                    'check_out' => $currentTime,
                    'status' => 'terlambat',
                    'late_minutes' => (int) ceil($now->diffInMinutes($onTimeLimit)),
                    'method' => 'qr',
                    'scanned_by' => $scanner?->id,
                ]);

                return [
                    'success' => true,
                    'type' => 'check_out',
                    'code' => 'CHECKOUT_RECORDED',
                    'student' => $student,
                    'attendance' => $attendance,
                    'status' => 'terlambat',
                    'check_in' => $currentTime,
                    'check_out' => $currentTime,
                    'late_minutes' => $attendance->late_minutes,
                    'message' => "Absensi Pulang Berhasil: {$student->name} (Pukul {$currentTime})",
                ];
            }
        }

        // --- SKENARIO 2: ABSENSI MASUK (CHECK-IN) ---
        if ($existing) {
            return [
                'success' => false,
                'code' => 'DUPLICATE',
                'student' => $student,
                'attendance' => $existing,
                'message' => "Siswa {$student->name} sudah melakukan absensi masuk hari ini pada pukul " . substr($existing->check_in, 0, 5) . " WIB (Status: " . strtoupper($existing->status) . ").",
            ];
        }

        if ($now->lt($checkInStart)) {
            // Sebelum jam mulai dibuka
            return [
                'success' => false,
                'code' => 'TOO_EARLY',
                'message' => "Absensi belum dibuka. Jam buka absensi: {$settings->check_in_start}.",
            ];
        } elseif ($now->lte($onTimeLimit)) {
            // Tepat Waktu
            $status = 'hadir';
            $lateMinutes = 0;
        } elseif ($now->lte($lateLimit)) {
            // Terlambat
            $status = 'terlambat';
            $lateMinutes = (int) ceil($now->diffInMinutes($onTimeLimit));
        } else {
            // Lewat batas akhir absensi
            $status = 'terlambat';
            $lateMinutes = (int) ceil($now->diffInMinutes($onTimeLimit));
        }

        // 5. Simpan absensi masuk
        $attendance = Attendance::create([
            'student_id' => $student->id,
            'date' => $today,
            'check_in' => $currentTime,
            'status' => $status,
            'late_minutes' => $lateMinutes,
            'method' => 'qr',
            'scanned_by' => $scanner?->id,
        ]);

        return [
            'success' => true,
            'type' => 'check_in',
            'code' => 'RECORDED',
            'student' => $student,
            'attendance' => $attendance,
            'status' => $status,
            'check_in' => $currentTime,
            'check_out' => null,
            'late_minutes' => $lateMinutes,
            'message' => $status === 'hadir'
                ? "Absensi Masuk Berhasil: {$student->name} (Hadir Tepat Waktu)"
                : "Absensi Masuk Berhasil: {$student->name} (Terlambat {$lateMinutes} Menit)",
        ];
    }

    /**
     * Statistik ringkasan sekolah untuk hari tertentu
     */
    public function getSchoolStats(?string $date = null): array
    {
        $date = $date ?: Carbon::today()->toDateString();
        $activeYear = AcademicYear::active();

        $studentsQuery = Student::where('status', 'aktif');
        if ($activeYear) {
            $studentsQuery->where('academic_year_id', $activeYear->id);
        }
        $totalStudents = $studentsQuery->count();

        $attendances = Attendance::whereDate('date', $date)->get();

        $hadir = $attendances->where('status', 'hadir')->count();
        $terlambat = $attendances->where('status', 'terlambat')->count();
        $izin = $attendances->where('status', 'izin')->count();
        $sakit = $attendances->where('status', 'sakit')->count();
        $alpa = $attendances->where('status', 'alpa')->count();
        $checkedOut = $attendances->whereNotNull('check_out')->count();

        $totalAbsen = $hadir + $terlambat + $izin + $sakit + $alpa;
        $belumAbsen = max(0, $totalStudents - $totalAbsen);

        return [
            'date' => $date,
            'total_students' => $totalStudents,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpa' => $alpa,
            'belum_absen' => $belumAbsen,
            'sudah_pulang' => $checkedOut,
            'persentase_kehadiran' => $totalStudents > 0 ? round((($hadir + $terlambat) / $totalStudents) * 100, 1) : 0,
            'percentage' => $totalStudents > 0 ? round((($hadir + $terlambat) / $totalStudents) * 100, 1) : 0,
        ];
    }

    /**
     * Statistik kelas untuk guru / wali kelas
     */
    public function getClassStats(int $classId, ?string $date = null): array
    {
        $date = $date ?: Carbon::today()->toDateString();
        $schoolClass = SchoolClass::withCount(['students' => function ($q) {
            $q->where('status', 'aktif');
        }])->findOrFail($classId);

        $students = Student::where('class_id', $classId)
            ->where('status', 'aktif')
            ->with(['attendances' => function ($q) use ($date) {
                $q->whereDate('date', $date);
            }])
            ->get();

        $total = $students->count();
        $hadir = 0;
        $terlambat = 0;
        $izin = 0;
        $sakit = 0;
        $alpa = 0;
        $checkedOut = 0;

        foreach ($students as $s) {
            $att = $s->attendances->first();
            if ($att) {
                if ($att->status === 'hadir') $hadir++;
                elseif ($att->status === 'terlambat') $terlambat++;
                elseif ($att->status === 'izin') $izin++;
                elseif ($att->status === 'sakit') $sakit++;
                elseif ($att->status === 'alpa') $alpa++;

                if ($att->check_out) $checkedOut++;
            }
        }

        $recorded = $hadir + $terlambat + $izin + $sakit + $alpa;
        $belum = max(0, $total - $recorded);

        return [
            'class' => $schoolClass,
            'total_students' => $total,
            'hadir' => $hadir,
            'terlambat' => $terlambat,
            'izin' => $izin,
            'sakit' => $sakit,
            'alpa' => $alpa,
            'belum_absen' => $belum,
            'sudah_pulang' => $checkedOut,
            'students' => $students,
        ];
    }

    /**
     * Jalankan proses otomatis penandaan ALPA bagi siswa yang belum hadir
     */
    public function runAutoAlpa(?string $date = null): int
    {
        $date = $date ?: Carbon::today()->toDateString();
        $activeYear = AcademicYear::active();

        $studentsQuery = Student::where('status', 'aktif')
            ->whereDoesntHave('attendances', function ($q) use ($date) {
                $q->whereDate('date', $date);
            });

        if ($activeYear) {
            $studentsQuery->where('academic_year_id', $activeYear->id);
        }

        $unattendedStudents = $studentsQuery->get();
        $count = 0;

        foreach ($unattendedStudents as $student) {
            Attendance::create([
                'student_id' => $student->id,
                'date' => $date,
                'status' => 'alpa',
                'method' => 'system',
                'notes' => 'Otomatis Alpa oleh sistem (Auto-Alpa Cutoff)',
            ]);
            $count++;
        }

        return $count;
    }
}
