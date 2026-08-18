<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
class History extends Component
{
    public string $currentMonth = '';
    public string $selectedDate = '';

    public function mount()
    {
        $this->currentMonth = Carbon::today()->format('Y-m');
        $this->selectedDate = Carbon::today()->toDateString();
    }

    public function prevMonth()
    {
        $this->currentMonth = Carbon::createFromFormat('Y-m', $this->currentMonth)
            ->subMonth()
            ->format('Y-m');
    }

    public function nextMonth()
    {
        $this->currentMonth = Carbon::createFromFormat('Y-m', $this->currentMonth)
            ->addMonth()
            ->format('Y-m');
    }

    public function selectDate(string $date)
    {
        $this->selectedDate = $date;
    }

    public function render()
    {
        $student = Auth::user()->student;

        $carbonMonth = Carbon::createFromFormat('Y-m', $this->currentMonth);
        $startDate = $carbonMonth->copy()->startOfMonth();
        $endDate = $carbonMonth->copy()->endOfMonth();

        // Ambil semua data absensi siswa di bulan ini
        $attendances = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

        // Rekapitulasi bulanan
        $summary = [
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'alpa' => $attendances->where('status', 'alpa')->count(),
            'total_late_minutes' => $attendances->where('status', 'terlambat')->sum('late_minutes'),
        ];
        $totalPresent = $summary['hadir'] + $summary['terlambat'];
        $totalRecorded = $attendances->count();
        $summary['percentage'] = $totalRecorded > 0 ? round(($totalPresent / $totalRecorded) * 100, 1) : 100;

        // Bangun Grid Kalender (Senin s.d. Minggu)
        // 1 = Monday, 7 = Sunday
        $startDayOfWeek = $startDate->dayOfWeekIso; // 1 (Mon) to 7 (Sun)
        $daysInMonth = $startDate->daysInMonth;

        $calendarDays = [];

        // 1. Padding kosong sebelum tanggal 1
        for ($i = 1; $i < $startDayOfWeek; $i++) {
            $calendarDays[] = [
                'day' => null,
                'date' => null,
                'attendance' => null,
                'is_today' => false,
                'is_weekend' => false,
                'is_selected' => false,
            ];
        }

        // 2. Tanggal 1 s.d. akhir bulan
        $todayStr = Carbon::today()->toDateString();
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateObj = $startDate->copy()->day($day);
            $dateStr = $dateObj->toDateString();
            $att = $attendances->get($dateStr);
            $isWeekend = $dateObj->isWeekend();

            $calendarDays[] = [
                'day' => $day,
                'date' => $dateStr,
                'attendance' => $att,
                'status' => $att ? $att->status : null,
                'is_today' => $dateStr === $todayStr,
                'is_weekend' => $isWeekend,
                'is_selected' => $dateStr === $this->selectedDate,
            ];
        }

        // Data absensi pada tanggal yang sedang dipilih
        $selectedAttendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', $this->selectedDate)
            ->first();

        return view('livewire.student.history', [
            'student' => $student,
            'monthTitle' => $carbonMonth->translatedFormat('F Y'),
            'calendarDays' => $calendarDays,
            'summary' => $summary,
            'selectedAttendance' => $selectedAttendance,
            'selectedCarbon' => Carbon::parse($this->selectedDate),
        ]);
    }
}
