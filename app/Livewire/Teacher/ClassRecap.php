<?php

namespace App\Livewire\Teacher;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.teacher')]
class ClassRecap extends Component
{
    public ?int $selectedClassId = null;
    public string $viewMode = 'weekly'; // 'daily' | 'weekly'
    public string $selectedDate = '';
    public string $selectedWeek = '';

    public function mount()
    {
        $teacher = Auth::user()->teacher;
        $myClass = $teacher ? $teacher->homeroomClasses()->first() : null;
        if ($myClass) {
            $this->selectedClassId = $myClass->id;
        }

        $this->selectedDate = Carbon::today()->toDateString();
        $this->selectedWeek = Carbon::today()->startOfWeek()->format('Y-m-d');
    }

    public function prevWeek()
    {
        $this->selectedWeek = Carbon::parse($this->selectedWeek)->subWeek()->startOfWeek()->format('Y-m-d');
    }

    public function nextWeek()
    {
        $this->selectedWeek = Carbon::parse($this->selectedWeek)->addWeek()->startOfWeek()->format('Y-m-d');
    }

    public function render()
    {
        $teacher = Auth::user()->teacher;
        $schoolClass = $teacher ? $teacher->homeroomClasses()->first() : null;
        $this->selectedClassId = $schoolClass?->id;

        $students = $schoolClass ? Student::where('class_id', $schoolClass->id)->where('status', 'aktif')->orderBy('name')->get() : collect();

        // Calculate Weekly Matrix (Senin s.d. Jumat)
        $startOfWeek = Carbon::parse($this->selectedWeek)->startOfWeek(); // Monday
        $weekDays = [];
        for ($i = 0; $i < 5; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $weekDays[] = [
                'date' => $day->toDateString(),
                'day_name' => $day->translatedFormat('D'),
                'day_num' => $day->format('d/m'),
            ];
        }

        $weekDates = array_column($weekDays, 'date');
        $weeklyAttendances = Attendance::whereIn('student_id', $students->pluck('id'))
            ->whereIn('date', $weekDates)
            ->get();

        // Build structured matrix for each student
        $matrix = [];
        foreach ($students as $s) {
            $studentAtts = $weeklyAttendances->where('student_id', $s->id);
            $days = [];
            foreach ($weekDates as $d) {
                $att = $studentAtts->firstWhere('date', $d);
                $days[$d] = [
                    'date' => $d,
                    'status' => $att ? $att->status : null,
                    'check_in' => $att ? $att->check_in : null,
                    'late_minutes' => $att ? $att->late_minutes : 0,
                ];
            }

            $matrix[] = [
                'student' => $s,
                'days' => $days,
                'totals' => [
                    'hadir' => $studentAtts->where('status', 'hadir')->count(),
                    'terlambat' => $studentAtts->where('status', 'terlambat')->count(),
                    'izin' => $studentAtts->where('status', 'izin')->count(),
                    'sakit' => $studentAtts->where('status', 'sakit')->count(),
                    'alpa' => $studentAtts->where('status', 'alpa')->count(),
                ],
            ];
        }

        // Daily attendances
        $dailyAttendances = Attendance::whereIn('student_id', $students->pluck('id'))
            ->whereDate('date', $this->selectedDate)
            ->get()
            ->keyBy('student_id');

        return view('livewire.teacher.class-recap', [
            'classes' => $classes,
            'schoolClass' => $schoolClass,
            'students' => $students,
            'weekDays' => $weekDays,
            'matrix' => $matrix,
            'dailyAttendances' => $dailyAttendances,
        ]);
    }
}
