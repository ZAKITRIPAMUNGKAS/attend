<?php

namespace App\Livewire\Auth;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.auth')]
class Login extends Component
{
    public string $identifier = ''; // NISN / NIS / Username / Email / NIP
    public string $password = '';
    public bool $remember = true;
    public string $errorMessage = '';

    public function login()
    {
        $this->validate([
            'identifier' => 'required|string',
            'password' => 'required|string',
        ], [
            'identifier.required' => 'NISN / NIS / Username / Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = Str::transliterate(Str::lower($this->identifier) . '|' . request()->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $this->errorMessage = "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.";
            return;
        }

        // 1. Cari user di tabel users (username, email, phone)
        $user = User::where('username', $this->identifier)
            ->orWhere('email', $this->identifier)
            ->orWhere('phone', $this->identifier)
            ->first();

        // 2. Jika belum ditemukan, cari via tabel students (NIS lokal atau NISN)
        if (!$user) {
            $student = Student::where('nis', $this->identifier)
                ->orWhere('nisn', $this->identifier)
                ->first();
            if ($student && $student->user) {
                $user = $student->user;
            }
        }

        // 3. Jika belum ditemukan, cari via tabel teachers (NIP)
        if (!$user) {
            $teacher = Teacher::where('nip', $this->identifier)->first();
            if ($teacher && $teacher->user) {
                $user = $teacher->user;
            }
        }

        // Autentikasi
        if ($user && Auth::attempt(['id' => $user->id, 'password' => $this->password], $this->remember)) {
            RateLimiter::clear($throttleKey);
            session()->regenerate();

            // Redirect berdasarkan role
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'teacher' => redirect()->route('teacher.dashboard'),
                'student' => redirect()->route('student.dashboard'),
                default => redirect()->to('/'),
            };
        }

        RateLimiter::hit($throttleKey, 60);
        $this->errorMessage = 'Identitas atau password yang Anda masukkan salah.';
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
