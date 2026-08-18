<?php

namespace App\Livewire\Admin;

use App\Models\AcademicYear;
use App\Models\AttendanceSetting;
use App\Models\SchoolClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class QrShare extends Component
{
    public string $mode = 'general'; // 'general' or 'class'
    public ?int $selectedClassId = null;
    public string $scanType = 'auto'; // 'auto', 'check_in', 'check_out'

    public function mount()
    {
        $firstClass = SchoolClass::first();
        $this->selectedClassId = $firstClass?->id;
    }

    public function setMode(string $mode)
    {
        $this->mode = $mode;
    }

    public function selectClass(int $classId)
    {
        $this->selectedClassId = $classId;
    }

    public function render()
    {
        $classes = SchoolClass::with('homeroomTeacher')->orderBy('name')->get();
        $selectedClass = $this->selectedClassId ? SchoolClass::find($this->selectedClassId) : null;
        $settings = AttendanceSetting::current();
        $activeYear = AcademicYear::active() ?? AcademicYear::first();

        // Tentukan payload string QR
        if ($this->mode === 'general') {
            $qrPayload = 'SMAIT_GENERAL_ATTENDANCE_QR';
            $title = 'QR Presensi General Sekolah';
            $subtitle = 'Dapat di-scan oleh seluruh siswa aktif SMA IT';
        } else {
            $qrPayload = 'SMAIT_CLASS_QR_' . ($selectedClass ? $selectedClass->id : '0');
            $title = 'QR Presensi Rombel ' . ($selectedClass ? $selectedClass->name : '-');
            $subtitle = 'Khusus siswa terdaftar di Kelas ' . ($selectedClass ? $selectedClass->name : '-');
        }

        // Cek sesi waktu saat ini
        $now = Carbon::now();
        $checkOutStart = $settings->check_out_start ? Carbon::createFromTimeString($settings->check_out_start) : null;
        $isCheckout = $settings->allow_checkout && $checkOutStart && $now->gte($checkOutStart);

        $currentTime = $now->format('H:i:s') . ' WIB';
        $currentDate = $now->translatedFormat('l, d F Y');

        return view('livewire.admin.qr-share', compact('classes', 'selectedClass', 'settings', 'activeYear', 'qrPayload', 'title', 'subtitle', 'isCheckout', 'currentTime', 'currentDate'));
    }
}
