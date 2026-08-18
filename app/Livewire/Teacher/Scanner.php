<?php

namespace App\Livewire\Teacher;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\Student;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.teacher')]
class Scanner extends Component
{
    public string $manualInput = '';
    public string $scanMode = 'auto'; // 'auto' | 'check_in' | 'check_out'
    public ?array $lastResult = null;
    public string $lastScannedToken = '';

    public function scanToken(string $token, AttendanceService $attendanceService)
    {
        $token = trim($token);
        if (empty($token)) {
            return;
        }

        $this->lastScannedToken = $token;
        $result = $attendanceService->recordAttendance($token, Auth::user(), $this->scanMode);
        
        $this->lastResult = [
            'success' => $result['success'],
            'type' => $result['type'] ?? 'check_in',
            'code' => $result['code'] ?? '',
            'message' => $result['message'],
            'status' => $result['status'] ?? null,
            'check_in' => $result['check_in'] ?? null,
            'check_out' => $result['check_out'] ?? null,
            'late_minutes' => $result['late_minutes'] ?? 0,
            'student' => isset($result['student']) ? [
                'name' => $result['student']->name,
                'nisn' => $result['student']->nisn,
                'class' => $result['student']->schoolClass->name ?? '-',
            ] : null,
            'timestamp' => Carbon::now()->format('H:i:s'),
        ];

        // Emit browser event for audio feedback
        $this->dispatch('scan-processed', [
            'success' => $result['success'],
            'code' => $result['code'] ?? '',
        ]);
    }

    public function submitManual(AttendanceService $attendanceService)
    {
        $this->validate([
            'manualInput' => 'required|string',
        ]);

        $this->scanToken($this->manualInput, $attendanceService);
        $this->manualInput = '';
    }

    public function clearResult()
    {
        $this->lastResult = null;
    }

    public function render()
    {
        $today = Carbon::today()->toDateString();
        $recentScans = Attendance::whereDate('date', $today)
            ->with(['student.schoolClass'])
            ->latest('updated_at')
            ->take(6)
            ->get();

        $settings = AttendanceSetting::current();

        return view('livewire.teacher.scanner', compact('recentScans', 'settings'));
    }
}
