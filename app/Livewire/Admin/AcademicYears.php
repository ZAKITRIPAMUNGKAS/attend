<?php

namespace App\Livewire\Admin;

use App\Models\AcademicYear;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AcademicYears extends Component
{
    public bool $showModal = false;
    public ?int $academicYearId = null;
    public string $name = '';
    public string $semester = 'ganjil';
    public string $start_date = '';
    public string $end_date = '';
    public bool $is_active = true;

    public string $successMessage = '';

    public function create()
    {
        $this->reset(['academicYearId', 'name', 'semester', 'start_date', 'end_date', 'is_active']);
        $this->semester = 'ganjil';
        $this->is_active = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:50',
            'semester' => 'required|in:ganjil,genap',
        ]);

        if ($this->is_active) {
            AcademicYear::query()->update(['is_active' => false]);
        }

        if ($this->academicYearId) {
            $year = AcademicYear::findOrFail($this->academicYearId);
            $year->update([
                'name' => $this->name,
                'semester' => $this->semester,
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'is_active' => $this->is_active,
            ]);
            $this->successMessage = "Tahun ajaran berhasil diperbarui.";
        } else {
            AcademicYear::create([
                'name' => $this->name,
                'semester' => $this->semester,
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'is_active' => $this->is_active,
            ]);
            $this->successMessage = "Tahun ajaran baru berhasil ditambahkan.";
        }

        $this->showModal = false;
    }

    public function setActive(int $id)
    {
        AcademicYear::query()->update(['is_active' => false]);
        AcademicYear::where('id', $id)->update(['is_active' => true]);
        $this->successMessage = "Tahun ajaran aktif berhasil dialihkan.";
    }

    public function render()
    {
        $years = AcademicYear::withCount(['classes', 'students'])->orderBy('id', 'desc')->get();
        return view('livewire.admin.academic-years', compact('years'));
    }
}
