<?php

namespace App\Livewire\Teacher;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.teacher')]
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
        $this->phone = Auth::user()->teacher->phone ?? '';
    }

    public function updateProfile()
    {
        $teacher = Auth::user()->teacher;
        $teacher->update([
            'phone' => $this->phone,
        ]);
        Auth::user()->update(['phone' => $this->phone]);

        $this->successProfile = 'Informasi kontak berhasil diperbarui.';
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->errorPassword = 'Password saat ini salah.';
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation', 'errorPassword']);
        $this->successPassword = 'Password berhasil diubah.';
    }

    public function render()
    {
        $teacher = Auth::user()->teacher()->with('homeroomClasses')->first();
        return view('livewire.teacher.profile', compact('teacher'));
    }
}
