<?php

namespace App\Livewire\Teacher;

use App\Models\SchoolClass;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.teacher')]
class Dashboard extends Component
{
    public ?int $selectedClassId = null;

    public function mount()
    {
        $teacher = Auth::user()->teacher;
        $firstClass = SchoolClass::where('homeroom_teacher_id', $teacher->id)->first() ?? SchoolClass::first();
        if ($firstClass) {
            $this->selectedClassId = $firstClass->id;
        }
    }

    public function render(AttendanceService $attendanceService)
    {
        $teacher = Auth::user()->teacher;
        $classes = SchoolClass::all();

        $stats = null;
        if ($this->selectedClassId) {
            $stats = $attendanceService->getClassStats($this->selectedClassId);
        }

        $today = Carbon::today()->toDateString();

        return view('livewire.teacher.dashboard', compact('teacher', 'classes', 'stats', 'today'));
    }
}
