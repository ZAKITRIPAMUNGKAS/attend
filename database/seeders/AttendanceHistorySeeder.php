<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceHistorySeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today();
        $adminUser = User::where('role', 'admin')->first();
        $students  = Student::where('status', 'aktif')->get();

        // ── 1. Hapus absensi hari ini ──────────────────────────────────────────
        Attendance::whereDate('date', $today->toDateString())->delete();
        $this->command->info("🗑  Absensi hari ini ({$today->toDateString()}) dihapus.");

        // ── 2. Kumpulkan hari kerja 7 hari ke belakang (Senin–Jumat, skip Sabtu/Minggu) ──
        $workDays = [];
        $cursor   = $today->copy()->subDay(); // mulai dari kemarin
        while (count($workDays) < 5) {
            // dayOfWeek: 0=Minggu, 1=Senin ... 5=Jumat, 6=Sabtu
            if ($cursor->dayOfWeek >= 1 && $cursor->dayOfWeek <= 5) {
                $workDays[] = $cursor->toDateString();
            }
            $cursor->subDay();
        }

        $this->command->info('📅 Mengisi kehadiran untuk ' . count($workDays) . ' hari kerja: ' . implode(', ', $workDays));

        // ── 3. Profil siswa: tentukan kecenderungan per siswa (seed stabil) ──────
        // Supaya data terasa "nyata", setiap siswa punya pola kehadiran berbeda
        $totalStudents = $students->count(); // 53

        foreach ($workDays as $dateStr) {
            $dayIndex = array_search($dateStr, $workDays); // 0-4

            foreach ($students as $i => $student) {
                // Seed acak tapi stabil per student per hari
                $seed = crc32($student->nisn . $dateStr);
                mt_srand($seed);
                $r = mt_rand(0, 99);

                // Distribusi realistis berdasarkan posisi siswa:
                // 80% hadir tepat waktu, 10% terlambat, 5% izin, 3% sakit, 2% alpa
                if ($r < 80) {
                    // Hadir tepat waktu: 06:10 – 06:59
                    $h   = 6;
                    $m   = mt_rand(10, 59);
                    $s   = mt_rand(0, 59);
                    $ci  = sprintf('%02d:%02d:%02d', $h, $m, $s);
                    // 70% langsung pulang sore (check-out tersedia)
                    $co  = mt_rand(0, 9) < 7
                        ? sprintf('%02d:%02d:%02d', 15, mt_rand(0, 30), mt_rand(0, 59))
                        : null;

                    Attendance::updateOrCreate(
                        ['student_id' => $student->id, 'date' => $dateStr],
                        [
                            'check_in'     => $ci,
                            'check_out'    => $co,
                            'status'       => 'hadir',
                            'late_minutes' => 0,
                            'method'       => 'qr',
                            'scanned_by'   => $adminUser?->id,
                            'notes'        => null,
                        ]
                    );
                } elseif ($r < 90) {
                    // Terlambat: masuk 07:01 – 07:45
                    $lateMin = mt_rand(1, 45);
                    $h       = 7;
                    $m       = $lateMin;
                    $s       = mt_rand(0, 59);
                    $ci      = sprintf('%02d:%02d:%02d', $h, $m, $s);

                    Attendance::updateOrCreate(
                        ['student_id' => $student->id, 'date' => $dateStr],
                        [
                            'check_in'     => $ci,
                            'check_out'    => null,
                            'status'       => 'terlambat',
                            'late_minutes' => $lateMin,
                            'method'       => 'qr',
                            'scanned_by'   => $adminUser?->id,
                            'notes'        => null,
                        ]
                    );
                } elseif ($r < 95) {
                    // Izin
                    Attendance::updateOrCreate(
                        ['student_id' => $student->id, 'date' => $dateStr],
                        [
                            'check_in'     => null,
                            'check_out'    => null,
                            'status'       => 'izin',
                            'late_minutes' => 0,
                            'method'       => 'permission',
                            'scanned_by'   => null,
                            'notes'        => collect([
                                'Izin keperluan keluarga mendesak',
                                'Izin acara keluarga',
                                'Izin mengurus administrasi',
                            ])->random(),
                        ]
                    );
                } elseif ($r < 98) {
                    // Sakit
                    Attendance::updateOrCreate(
                        ['student_id' => $student->id, 'date' => $dateStr],
                        [
                            'check_in'     => null,
                            'check_out'    => null,
                            'status'       => 'sakit',
                            'late_minutes' => 0,
                            'method'       => 'permission',
                            'scanned_by'   => null,
                            'notes'        => collect([
                                'Sakit demam dan flu',
                                'Sakit istirahat atas saran dokter',
                                'Sakit maag kambuh',
                            ])->random(),
                        ]
                    );
                } else {
                    // Alpa (tidak hadir tanpa keterangan) — biarkan tidak ada record
                    // Atau buat record alpa
                    Attendance::updateOrCreate(
                        ['student_id' => $student->id, 'date' => $dateStr],
                        [
                            'check_in'     => null,
                            'check_out'    => null,
                            'status'       => 'alpa',
                            'late_minutes' => 0,
                            'method'       => 'system',
                            'scanned_by'   => null,
                            'notes'        => 'Tidak hadir tanpa keterangan',
                        ]
                    );
                }
            }

            $this->command->info("  ✅ {$dateStr} — {$students->count()} siswa diproses.");
        }

        $total = Attendance::whereIn('date', $workDays)->count();
        $this->command->info("🎉 Selesai! Total {$total} record absensi berhasil dibuat untuk 5 hari kerja.");
    }
}
