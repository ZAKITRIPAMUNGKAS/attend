<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\PermissionRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
class Dashboard extends Component
{
    public function render()
    {
        $user = Auth::user();
        $student = $user->student()->with('schoolClass')->first();
        $today = Carbon::today()->toDateString();
        $startOfMonth = Carbon::today()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::today()->endOfMonth()->toDateString();

        // Absensi hari ini
        $todayAttendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->first();

        // Pengajuan izin hari ini jika ada
        $todayPermission = PermissionRequest::where('student_id', $student->id)
            ->whereDate('date', $today)
            ->latest()
            ->first();

        // Rekap kehadiran bulan berjalan
        $monthlyAttendances = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $stats = [
            'hadir' => $monthlyAttendances->where('status', 'hadir')->count(),
            'terlambat' => $monthlyAttendances->where('status', 'terlambat')->count(),
            'izin' => $monthlyAttendances->where('status', 'izin')->count(),
            'sakit' => $monthlyAttendances->where('status', 'sakit')->count(),
            'alpa' => $monthlyAttendances->where('status', 'alpa')->count(),
            'total_late_minutes' => $monthlyAttendances->where('status', 'terlambat')->sum('late_minutes'),
        ];

        $totalPresent = $stats['hadir'] + $stats['terlambat'];
        $totalDays = $monthlyAttendances->count();
        $stats['percentage'] = $totalDays > 0 ? round(($totalPresent / $totalDays) * 100, 1) : 100;

        $settings = AttendanceSetting::current();

        return view('livewire.student.dashboard', compact('student', 'todayAttendance', 'todayPermission', 'stats', 'settings'));
    }
}
