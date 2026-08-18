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
        $myClass = $teacher ? $teacher->homeroomClasses()->first() : null;
        if ($myClass) {
            $this->selectedClassId = $myClass->id;
        }
    }

    public function render(AttendanceService $attendanceService)
    {
        $teacher = Auth::user()->teacher;
        $myClass = $teacher ? $teacher->homeroomClasses()->first() : null;

        $stats = null;
        if ($myClass) {
            $this->selectedClassId = $myClass->id;
            $stats = $attendanceService->getClassStats($myClass->id);
        }

        $today = Carbon::today()->toDateString();

        return view('livewire.teacher.dashboard', compact('teacher', 'myClass', 'stats', 'today'));
    }
}
