<?php

namespace App\Livewire\Admin;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AttendanceRecap extends Component
{
    public string $viewMode = 'daily'; // 'daily' | 'weekly' | 'monthly'
    public string $selectedDate = '';
    public string $selectedMonth = '';
    public string $selectedWeek = '';
    public string $selectedClassId = '';
    public string $statusFilter = 'all';

    // Manual Correction modal
    public bool $showEditModal = false;
    public ?int $editingAttendanceId = null;
    public ?int $editingStudentId = null;
    public string $editingStudentName = '';
    public string $editStatus = 'hadir';
    public string $editCheckIn = '07:00';
    public string $editCheckOut = '';
    public int $editLateMinutes = 0;
    public string $editNotes = '';

    public string $successMessage = '';

    public function mount()
    {
        $this->selectedDate = Carbon::today()->toDateString();
        $this->selectedMonth = Carbon::today()->format('Y-m');
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

    public function openEdit(int $studentId, ?int $attendanceId = null)
    {
        $student = Student::findOrFail($studentId);
        $this->editingStudentId = $student->id;
        $this->editingStudentName = $student->name;
        $this->editingAttendanceId = $attendanceId;

        if ($attendanceId) {
            $att = Attendance::findOrFail($attendanceId);
            $this->editStatus = $att->status;
            $this->editCheckIn = $att->check_in ? substr($att->check_in, 0, 5) : '07:00';
            $this->editCheckOut = $att->check_out ? substr($att->check_out, 0, 5) : '';
            $this->editLateMinutes = $att->late_minutes;
            $this->editNotes = $att->notes ?? '';
        } else {
            $this->editStatus = 'hadir';
            $this->editCheckIn = '06:45';
            $this->editCheckOut = '';
            $this->editLateMinutes = 0;
            $this->editNotes = 'Koreksi manual oleh admin';
        }

        $this->showEditModal = true;
    }

    public function saveCorrection()
    {
        Attendance::updateOrCreate(
            [
                'student_id' => $this->editingStudentId,
                'date' => $this->selectedDate,
            ],
            [
                'status' => $this->editStatus,
                'check_in' => in_array($this->editStatus, ['hadir', 'terlambat']) ? ($this->editCheckIn . ':00') : null,
                'check_out' => $this->editCheckOut ? ($this->editCheckOut . ':00') : null,
                'late_minutes' => $this->editStatus === 'terlambat' ? $this->editLateMinutes : 0,
                'method' => 'manual',
                'notes' => $this->editNotes ?: 'Koreksi manual admin',
                'scanned_by' => Auth::id(),
            ]
        );

        $this->showEditModal = false;
        $this->successMessage = "Status absensi {$this->editingStudentName} berhasil diperbarui.";
    }

    public function render()
    {
        $classes = SchoolClass::all();

        // 1. Filter Students
        $studentsQuery = Student::where('status', 'aktif')->with('schoolClass');
        if ($this->selectedClassId) {
            $studentsQuery->where('class_id', $this->selectedClassId);
        }
        $students = $studentsQuery->orderBy('name')->get();

        // 2. Daily Attendances
        $dailyAttendances = Attendance::whereDate('date', $this->selectedDate)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        // 3. Weekly Matrix Calculation
        $startOfWeek = Carbon::parse($this->selectedWeek)->startOfWeek();
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

        $weeklyMatrix = [];
        foreach ($students as $s) {
            $sAtts = $weeklyAttendances->where('student_id', $s->id);
            $days = [];
            foreach ($weekDates as $d) {
                $att = $sAtts->firstWhere('date', $d);
                $days[$d] = [
                    'date' => $d,
                    'status' => $att ? $att->status : null,
                    'late_minutes' => $att ? $att->late_minutes : 0,
                    'check_out' => $att ? $att->check_out : null,
                ];
            }

            $weeklyMatrix[] = [
                'student' => $s,
                'days' => $days,
                'totals' => [
                    'hadir' => $sAtts->where('status', 'hadir')->count(),
                    'terlambat' => $sAtts->where('status', 'terlambat')->count(),
                    'izin' => $sAtts->where('status', 'izin')->count(),
                    'sakit' => $sAtts->where('status', 'sakit')->count(),
                    'alpa' => $sAtts->where('status', 'alpa')->count(),
                    'pulang' => $sAtts->whereNotNull('check_out')->count(),
                ],
            ];
        }

        // 4. Monthly Summary Calculation
        $startOfMonth = Carbon::createFromFormat('Y-m', $this->selectedMonth)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::createFromFormat('Y-m', $this->selectedMonth)->endOfMonth()->toDateString();
        $monthlyAttendances = Attendance::whereIn('student_id', $students->pluck('id'))
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $monthlyMatrix = [];
        foreach ($students as $s) {
            $sAtts = $monthlyAttendances->where('student_id', $s->id);
            $h = $sAtts->where('status', 'hadir')->count();
            $t = $sAtts->where('status', 'terlambat')->count();
            $i = $sAtts->where('status', 'izin')->count();
            $sk = $sAtts->where('status', 'sakit')->count();
            $a = $sAtts->where('status', 'alpa')->count();
            $p = $sAtts->whereNotNull('check_out')->count();
            $totalRecorded = $h + $t + $i + $sk + $a;
            $rate = $totalRecorded > 0 ? round((($h + $t) / $totalRecorded) * 100, 1) : 100;

            $monthlyMatrix[] = [
                'student' => $s,
                'hadir' => $h,
                'terlambat' => $t,
                'izin' => $i,
                'sakit' => $sk,
                'alpa' => $a,
                'pulang' => $p,
                'percentage' => $rate,
            ];
        }

        return view('livewire.admin.attendance-recap', [
            'classes' => $classes,
            'students' => $students,
            'dailyAttendances' => $dailyAttendances,
            'weekDays' => $weekDays,
            'weeklyMatrix' => $weeklyMatrix,
            'monthlyMatrix' => $monthlyMatrix,
        ]);
    }
}
