<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
class Scanner extends Component
{
    public ?array $lastResult = null;
    public string $scanType = 'auto'; // 'auto', 'check_in', 'check_out'
    public string $flashMessage = '';

    public function handleScan(string $qrPayload, AttendanceService $attendanceService)
    {
        $student = Auth::user()->student;

        if (!$student) {
            $this->lastResult = [
                'success' => false,
                'message' => 'Akun Anda tidak terhubung dengan profil siswa aktif.',
            ];
            $this->dispatch('scan-error');
            return;
        }

        $result = $attendanceService->recordSelfAttendance($student, $qrPayload, $this->scanType);
        $this->lastResult = $result;

        if ($result['success']) {
            $this->dispatch('scan-success', [
                'type' => $result['type'] ?? 'check_in',
                'name' => $student->name,
                'status' => $result['status'] ?? 'hadir',
                'time' => $result['type'] === 'check_out' ? $result['check_out'] : $result['check_in'],
            ]);
        } else {
            $this->dispatch('scan-error', [
                'message' => $result['message'],
            ]);
        }
    }

    public function setScanType(string $type)
    {
        $this->scanType = $type;
    }

    public function render()
    {
        $student = Auth::user()->student()->with(['schoolClass', 'academicYear'])->first();
        $settings = AttendanceSetting::current();
        $today = Carbon::today()->toDateString();
        
        $todayAttendance = $student ? Attendance::where('student_id', $student->id)->whereDate('date', $today)->first() : null;

        return view('livewire.student.scanner', compact('student', 'settings', 'todayAttendance'));
    }
}
