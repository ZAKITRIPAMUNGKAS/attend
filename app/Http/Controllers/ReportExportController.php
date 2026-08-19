<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportController extends Controller
{
    /**
     * Export Master Data Siswa & Orang Tua / Wali ke format Excel / CSV
     */
    public function exportStudents(Request $request): StreamedResponse
    {
        $classId = $request->query('class_id');
        $filename = "Data_Siswa_Dan_Wali_SMAIT_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($classId) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fputs($handle, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($handle, [
                'No',
                'NISN',
                'NIS',
                'Nama Lengkap Siswa',
                'Jenis Kelamin',
                'Kelas',
                'Tingkat',
                'Tempat Lahir',
                'Tanggal Lahir (DD/MM/YYYY)',
                'Default Password',
                'Nama Orang Tua / Wali',
                'WhatsApp Orang Tua',
                'Telepon Siswa',
                'Status Akun',
            ]);

            $studentsQuery = Student::where('status', 'aktif')->with(['schoolClass', 'user']);
            if ($classId) {
                $studentsQuery->where('class_id', $classId);
            }
            $students = $studentsQuery->orderBy('class_id')->orderBy('name')->get();

            foreach ($students as $idx => $s) {
                $dobFormatted = $s->birth_date ? Carbon::parse($s->birth_date)->format('d/m/Y') : '-';
                $dobPassword = $s->birth_date ? Carbon::parse($s->birth_date)->format('dmY') : '-';

                fputcsv($handle, [
                    $idx + 1,
                    "'" . $s->nisn, // Single quote to prevent scientific notation in Excel
                    $s->nis ? "'" . $s->nis : '-',
                    $s->name,
                    $s->gender === 'L' ? 'Laki-laki' : 'Perempuan',
                    $s->schoolClass->name ?? '-',
                    $s->schoolClass->grade ?? '-',
                    $s->birth_place ?? '-',
                    $dobFormatted,
                    $dobPassword,
                    $s->parent_name ?? '-',
                    $s->parent_phone ? "'" . $s->parent_phone : '-',
                    $s->phone ? "'" . $s->phone : '-',
                    strtoupper($s->status),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export Master Data Siswa ke PDF Resmi (Lengkap Kop Surat, Metadata & Lembar Pengesahan)
     */
    public function exportStudentsPdf(Request $request)
    {
        $classId = $request->query('class_id');
        $academicYear = AcademicYear::active() ?? AcademicYear::first();

        $selectedClass = $classId ? SchoolClass::with('homeroomTeacher')->find($classId) : null;
        $homeroomTeacher = $selectedClass?->homeroomTeacher;

        $studentsQuery = Student::where('status', 'aktif')->with(['schoolClass', 'user']);
        if ($classId) {
            $studentsQuery->where('class_id', $classId);
        }
        $students = $studentsQuery->orderBy('class_id')->orderBy('name')->get();

        $totalStudents = $students->count();
        $countMale = $students->where('gender', 'L')->count();
        $countFemale = $students->where('gender', 'P')->count();

        // Base64 Logo for fast & fail-safe DomPDF rendering
        $logoPath = public_path('logo.png');
        $logoBase64 = file_exists($logoPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
            : '';

        $schoolName = 'SMA IT Insan Kamil';

        $pdf = Pdf::loadView('exports.pdf-students', compact(
            'students',
            'academicYear',
            'selectedClass',
            'homeroomTeacher',
            'totalStudents',
            'countMale',
            'countFemale',
            'logoBase64',
            'schoolName'
        ))->setPaper('a4', 'landscape');

        $classNameSlug = $selectedClass ? '_' . str_replace(' ', '_', $selectedClass->name) : '_Semua_Kelas';
        $filename = "Data_Induk_Siswa_SMAIT{$classNameSlug}_" . date('Ymd_His') . ".pdf";

        return $pdf->stream($filename);
    }

    /**
     * Export Rekap Absensi ke format Excel / CSV
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $mode = $request->query('type', $request->query('mode', 'daily'));
        $date = $request->query('date', Carbon::today()->toDateString());
        $month = $request->query('month', Carbon::today()->format('Y-m'));
        $classId = $request->query('class_id');

        $filename = "Laporan_Absensi_SMAIT_{$mode}_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($mode, $date, $month, $classId) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel support
            fputs($handle, "\xEF\xBB\xBF");

            $studentsQuery = Student::where('status', 'aktif')->with('schoolClass');
            if ($classId) {
                $studentsQuery->where('class_id', $classId);
            }
            $students = $studentsQuery->orderBy('name')->get();

            if ($mode === 'daily') {
                fputcsv($handle, ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status', 'Keterlambatan (Menit)', 'Metode']);

                $attendances = Attendance::whereDate('date', $date)->get()->keyBy('student_id');

                foreach ($students as $idx => $s) {
                    $att = $attendances->get($s->id);
                    fputcsv($handle, [
                        $idx + 1,
                        "'" . $s->nisn,
                        $s->name,
                        $s->schoolClass->name ?? '-',
                        $date,
                        $att && $att->check_in ? $att->check_in : '-',
                        $att && $att->check_out ? $att->check_out : '-',
                        $att ? strtoupper($att->status) : 'BELUM ABSEN',
                        $att && $att->late_minutes ? $att->late_minutes : 0,
                        $att ? $att->method : '-',
                    ]);
                }
            } elseif ($mode === 'monthly') {
                fputcsv($handle, ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Bulan', 'Hadir', 'Terlambat', 'Izin', 'Sakit', 'Alpa', 'Sudah Pulang', '% Kehadiran']);

                $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth()->toDateString();
                $endOfMonth = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->toDateString();
                $monthlyAttendances = Attendance::whereBetween('date', [$startOfMonth, $endOfMonth])->get();

                foreach ($students as $idx => $s) {
                    $sMonth = $monthlyAttendances->where('student_id', $s->id);
                    $h = $sMonth->where('status', 'hadir')->count();
                    $t = $sMonth->where('status', 'terlambat')->count();
                    $i = $sMonth->where('status', 'izin')->count();
                    $sk = $sMonth->where('status', 'sakit')->count();
                    $a = $sMonth->where('status', 'alpa')->count();
                    $p = $sMonth->whereNotNull('check_out')->count();
                    $total = $sMonth->count();
                    $pct = $total > 0 ? round((($h + $t) / $total) * 100, 1) : 100;

                    fputcsv($handle, [
                        $idx + 1,
                        "'" . $s->nisn,
                        $s->name,
                        $s->schoolClass->name ?? '-',
                        $month,
                        $h,
                        $t,
                        $i,
                        $sk,
                        $a,
                        $p,
                        $pct . '%',
                    ]);
                }
            } else {
                fputcsv($handle, ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Tanggal', 'Jam Masuk', 'Jam Pulang', 'Status']);
                $attendances = Attendance::whereDate('date', $date)->get()->keyBy('student_id');
                foreach ($students as $idx => $s) {
                    $att = $attendances->get($s->id);
                    fputcsv($handle, [
                        $idx + 1,
                        "'" . $s->nisn,
                        $s->name,
                        $s->schoolClass->name ?? '-',
                        $date,
                        $att && $att->check_in ? $att->check_in : '-',
                        $att && $att->check_out ? $att->check_out : '-',
                        $att ? strtoupper($att->status) : 'BELUM ABSEN',
                    ]);
                }
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export Rekap Absensi ke format PDF
     */
    public function exportPdf(Request $request)
    {
        $mode = $request->query('type', $request->query('mode', 'daily'));
        $date = $request->query('date', Carbon::today()->toDateString());
        $month = $request->query('month', Carbon::today()->format('Y-m'));
        $classId = $request->query('class_id');

        $studentsQuery = Student::where('status', 'aktif')->with('schoolClass');
        if ($classId) {
            $studentsQuery->where('class_id', $classId);
        }
        $students = $studentsQuery->orderBy('name')->get();
        $schoolClass = $classId ? SchoolClass::find($classId) : null;

        $dailyAttendances = Attendance::whereDate('date', $date)->get()->keyBy('student_id');

        $pdf = Pdf::loadView('exports.pdf-recap', compact('students', 'dailyAttendances', 'date', 'month', 'mode', 'schoolClass'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Laporan_Absensi_SMAIT_{$date}.pdf");
    }

    /**
     * Cetak Kartu QR Massal per Kelas / Seluruh Siswa
     */
    public function printQrCards(Request $request)
    {
        $classId = $request->query('class_id');

        $query = Student::where('status', 'aktif')->with(['schoolClass', 'academicYear']);
        if ($classId) {
            $query->where('class_id', $classId);
        }
        $students = $query->orderBy('name')->get();
        $schoolClass = $classId ? SchoolClass::find($classId) : null;

        return view('exports.print-qr-cards', compact('students', 'schoolClass'));
    }

    /**
     * Cetak Poster QR Presensi A4 (General / Kelas)
     */
    public function printPosterQr(Request $request)
    {
        $mode = $request->query('mode', 'general');
        $classId = $request->query('class_id');
        $activeYear = \App\Models\AcademicYear::active() ?? \App\Models\AcademicYear::first();
        $settings = \App\Models\AttendanceSetting::current();

        $selectedClass = ($mode === 'class' && $classId) ? \App\Models\SchoolClass::find($classId) : null;

        if ($mode === 'class' && $selectedClass) {
            $qrPayload = 'SMAIT_CLASS_QR_' . $selectedClass->id;
            $title = 'QR Presensi Murid';
            $subtitle = 'Kelas ' . $selectedClass->name;
        } else {
            $mode = 'general';
            $selectedClass = null;
            $qrPayload = 'SMAIT_GENERAL_ATTENDANCE_QR';
            $title = 'QR Presensi Murid';
            $subtitle = 'SMA IT Insan Kamil';
        }

        $now = \Carbon\Carbon::now();
        $checkOutStart = $settings->check_out_start ? \Carbon\Carbon::createFromTimeString($settings->check_out_start) : null;
        $isCheckout = $settings->allow_checkout && $checkOutStart && $now->gte($checkOutStart);

        $currentDate = $now->translatedFormat('d F Y');
        $currentTime = $now->format('H:i');

        return view('exports.print-poster-qr', compact('mode', 'selectedClass', 'activeYear', 'settings', 'qrPayload', 'title', 'subtitle', 'isCheckout', 'currentDate', 'currentTime'));
    }
}
