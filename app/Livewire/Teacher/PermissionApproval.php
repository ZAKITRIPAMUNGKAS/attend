<?php

namespace App\Livewire\Teacher;

use App\Models\Attendance;
use App\Models\PermissionRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.teacher')]
class PermissionApproval extends Component
{
    public string $filterStatus = 'menunggu'; // 'menunggu' | 'disetujui' | 'ditolak' | 'all'
    public ?int $selectedRequestId = null;
    public string $rejectionReason = '';
    public string $flashMessage = '';

    public function approve(int $requestId)
    {
        $req = PermissionRequest::with('student')->findOrFail($requestId);
        
        $req->update([
            'status' => 'disetujui',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);

        // Simpan / perbarui ke tabel absensi
        Attendance::updateOrCreate(
            [
                'student_id' => $req->student_id,
                'date' => $req->date,
            ],
            [
                'status' => $req->type, // 'izin' atau 'sakit'
                'method' => 'permission',
                'notes' => "Disetujui dari pengajuan izin: " . $req->reason,
                'scanned_by' => Auth::id(),
            ]
        );

        $this->flashMessage = "Izin {$req->student->name} berhasil disetujui.";
    }

    public function reject(int $requestId)
    {
        $this->validate([
            'rejectionReason' => 'required|min:3',
        ], [
            'rejectionReason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $req = PermissionRequest::with('student')->findOrFail($requestId);
        $req->update([
            'status' => 'ditolak',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
            'rejection_reason' => $this->rejectionReason,
        ]);

        $this->selectedRequestId = null;
        $this->rejectionReason = '';
        $this->flashMessage = "Pengajuan izin {$req->student->name} ditolak.";
    }

    public function openRejectModal(int $requestId)
    {
        $this->selectedRequestId = $requestId;
        $this->rejectionReason = '';
    }

    public function render()
    {
        $teacher = Auth::user()->teacher;
        $classIds = $teacher ? $teacher->homeroomClasses->pluck('id')->toArray() : [];

        $query = PermissionRequest::with(['student.schoolClass', 'approver'])
            ->whereHas('student', function ($q) use ($classIds) {
                if (!empty($classIds)) {
                    $q->whereIn('class_id', $classIds);
                } else {
                    $q->whereRaw('1 = 0');
                }
            })
            ->orderBy('date', 'desc');

        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }

        $requests = $query->get();

        $pendingCount = PermissionRequest::where('status', 'menunggu')
            ->whereHas('student', function ($q) use ($classIds) {
                if (!empty($classIds)) {
                    $q->whereIn('class_id', $classIds);
                } else {
                    $q->whereRaw('1 = 0');
                }
            })
            ->count();

        return view('livewire.teacher.permission-approval', compact('requests', 'pendingCount'));
    }
}
