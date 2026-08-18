<?php

namespace App\Livewire\Admin;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public string $selectedDate = '';
    public string $actionMessage = '';

    public function mount()
    {
        $this->selectedDate = Carbon::today()->toDateString();
    }

    public function triggerAutoAlpa(AttendanceService $attendanceService)
    {
        $count = $attendanceService->runAutoAlpa($this->selectedDate);
        $this->actionMessage = "Auto-Alpa selesai diproses. {$count} siswa berhasil ditandai Alpa.";
    }

    public function render(AttendanceService $attendanceService)
    {
        $totalStudents = Student::where('status', 'aktif')->count();
        $totalTeachers = Teacher::count();
        $totalClasses = SchoolClass::count();
        $activeYear = AcademicYear::active();

        $stats = $attendanceService->getSchoolStats($this->selectedDate);
        $settings = \App\Models\AttendanceSetting::current();

        return view('livewire.admin.dashboard', compact('totalStudents', 'totalTeachers', 'totalClasses', 'activeYear', 'stats', 'settings'));
    }
}
