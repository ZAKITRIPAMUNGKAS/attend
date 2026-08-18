<?php

namespace App\Livewire\Student;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.student')]
class Profile extends Component
{
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';
    public string $phone = '';

    public string $successPassword = '';
    public string $errorPassword = '';
    public string $successProfile = '';

    public function mount()
    {
        $this->phone = Auth::user()->student->phone ?? '';
    }

    public function updateProfile()
    {
        $student = Auth::user()->student;
        $student->update([
            'phone' => $this->phone,
        ]);
        Auth::user()->update(['phone' => $this->phone]);

        $this->successProfile = 'Kontak berhasil diperbarui.';
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'new_password.required' => 'Password baru wajib diisi.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->errorPassword = 'Password saat ini salah.';
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
            'is_default_password' => false,
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation', 'errorPassword']);
        $this->successPassword = 'Password berhasil diubah. Keamanan akun Anda kini lebih terjaga.';
    }

    public function render()
    {
        $student = Auth::user()->student()->with(['schoolClass', 'academicYear'])->first();
        return view('livewire.student.profile', compact('student'));
    }
}
