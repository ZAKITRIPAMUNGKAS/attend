<?php

namespace App\Livewire\Student;

use App\Models\Attendance;
use App\Models\PermissionRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.student')]
class Permission extends Component
{
    use WithFileUploads;

    public string $type = 'izin'; // 'izin' | 'sakit'
    public string $date = '';
    public string $reason = '';
    public $attachment;

    public string $successMessage = '';
    public string $errorMessage = '';

    public function mount()
    {
        $this->date = Carbon::today()->toDateString();
    }

    public function submit()
    {
        $this->validate([
            'type' => 'required|in:izin,sakit',
            'date' => 'required|date',
            'reason' => 'required|string|min:5|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'reason.required' => 'Alasan izin wajib diisi.',
            'reason.min' => 'Alasan minimal 5 karakter.',
            'attachment.max' => 'Ukuran file lampiran maksimal 2MB.',
        ]);

        $student = Auth::user()->student;

        // Cek apakah sudah pernah mengajukan untuk tanggal ini
        $existing = PermissionRequest::where('student_id', $student->id)
            ->whereDate('date', $this->date)
            ->first();

        if ($existing) {
            $this->errorMessage = 'Anda sudah memiliki pengajuan izin pada tanggal ini.';
            return;
        }

        $path = null;
        if ($this->attachment) {
            $path = $this->attachment->store('permissions', 'public');
        }

        PermissionRequest::create([
            'student_id' => $student->id,
            'date' => $this->date,
            'type' => $this->type,
            'reason' => $this->reason,
            'attachment' => $path,
            'status' => 'menunggu',
        ]);

        $this->reset(['reason', 'attachment']);
        $this->date = Carbon::today()->toDateString();
        $this->successMessage = 'Pengajuan izin berhasil dikirim. Menunggu persetujuan wali kelas / guru piket.';
    }

    public function render()
    {
        $student = Auth::user()->student;
        $requests = PermissionRequest::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('livewire.student.permission', compact('requests', 'student'));
    }
}
