<?php

namespace App\Livewire\Admin;

use App\Models\AcademicYear;
use App\Models\AttendanceSetting;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QrShare extends Component
{
    public string $mode = 'general'; // 'general' or 'class'
    public ?int $selectedClassId = null;
    public string $scanType = 'auto'; // 'auto', 'check_in', 'check_out'

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->role === 'teacher') {
            $teacher = $user->teacher;
            $myClass = $teacher ? $teacher->homeroomClasses()->first() : null;
            $this->mode = 'class';
            $this->selectedClassId = $myClass?->id;
        } else {
            $firstClass = SchoolClass::first();
            $this->selectedClassId = $firstClass?->id;
        }
    }

    public function setMode(string $mode)
    {
        $user = Auth::user();
        if ($user && $user->role === 'teacher') {
            $this->mode = 'class';
            return;
        }
        $this->mode = $mode;
    }

    public function selectClass(int $classId)
    {
        $user = Auth::user();
        if ($user && $user->role === 'teacher') {
            $teacher = $user->teacher;
            $myClass = $teacher ? $teacher->homeroomClasses()->first() : null;
            $this->selectedClassId = $myClass?->id;
            return;
        }
        $this->selectedClassId = $classId;
    }

    public function render()
    {
        $user = Auth::user();
        $isTeacher = $user && $user->role === 'teacher';
        $teacher = $isTeacher ? $user->teacher : null;

        if ($isTeacher) {
            $myClass = $teacher ? $teacher->homeroomClasses()->first() : null;
            $this->mode = 'class';
            $this->selectedClassId = $myClass?->id;
            $classes = $myClass ? collect([$myClass]) : collect();
            $selectedClass = $myClass;
        } else {
            $classes = SchoolClass::with('homeroomTeacher')->orderBy('name')->get();
            $selectedClass = $this->selectedClassId ? SchoolClass::find($this->selectedClassId) : null;
        }

        $settings = AttendanceSetting::current();
        $activeYear = AcademicYear::active() ?? AcademicYear::first();

        // Tentukan payload string QR
        if ($this->mode === 'general' && !$isTeacher) {
            $qrPayload = 'SMAIT_GENERAL_ATTENDANCE_QR';
            $title = 'QR Presensi General Sekolah';
            $subtitle = 'Dapat di-scan oleh seluruh murid aktif SMA IT Insan Kamil';
        } else {
            if ($selectedClass) {
                $qrPayload = 'SMAIT_CLASS_QR_' . $selectedClass->id;
                $title = 'QR Presensi Rombel Kelas ' . $selectedClass->name;
                $subtitle = 'Khusus murid terdaftar di Kelas ' . $selectedClass->name . ' (Murid rombel lain tidak dapat scan)';
            } else {
                $qrPayload = 'NO_CLASS_ASSIGNED';
                $title = 'Belum Ditugaskan Kelas';
                $subtitle = 'Anda belum ditugaskan sebagai wali kelas rombongan belajar.';
            }
        }

        // Cek sesi waktu saat ini
        $now = Carbon::now();
        $checkOutStart = $settings->check_out_start ? Carbon::createFromTimeString($settings->check_out_start) : null;
        $isCheckout = $settings->allow_checkout && $checkOutStart && $now->gte($checkOutStart);

        $currentTime = $now->format('H:i:s') . ' WIB';
        $currentDate = $now->translatedFormat('l, d F Y');

        $view = view('livewire.admin.qr-share', compact('isTeacher', 'teacher', 'classes', 'selectedClass', 'settings', 'activeYear', 'qrPayload', 'title', 'subtitle', 'isCheckout', 'currentTime', 'currentDate'));

        return $isTeacher ? $view->layout('layouts.teacher') : $view->layout('layouts.admin');
    }
}
