<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
class QrCodeView extends Component
{
    public string $message = '';

    public function regenerateToken()
    {
        $student = Auth::user()->student;
        $student->regenerateQrToken();
        $this->message = 'QR Code Anda berhasil diperbarui!';
    }

    public function render()
    {
        $student = Auth::user()->student()->with(['schoolClass', 'academicYear'])->first();
        return view('livewire.student.qr-code', compact('student'));
    }
}
