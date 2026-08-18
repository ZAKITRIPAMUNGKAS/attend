<?php

namespace App\Livewire\Admin;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Teacher;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Classes extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public ?int $classId = null;
    public string $name = '';
    public string $grade = 'X';
    public ?int $homeroom_teacher_id = null;

    public string $successMessage = '';

    public function create()
    {
        $this->reset(['classId', 'name', 'grade', 'homeroom_teacher_id']);
        $this->grade = 'X';
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $class = SchoolClass::findOrFail($id);
        $this->classId = $class->id;
        $this->name = $class->name;
        $this->grade = $class->grade;
        $this->homeroom_teacher_id = $class->homeroom_teacher_id;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:50',
            'grade' => 'required|string|max:10',
            'homeroom_teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $activeYear = AcademicYear::active() ?? AcademicYear::first();

        if ($this->classId) {
            $class = SchoolClass::findOrFail($this->classId);
            $class->update([
                'name' => $this->name,
                'grade' => $this->grade,
                'homeroom_teacher_id' => $this->homeroom_teacher_id,
            ]);
            $this->successMessage = "Kelas {$class->name} berhasil diperbarui.";
        } else {
            $class = SchoolClass::create([
                'academic_year_id' => $activeYear?->id,
                'name' => $this->name,
                'grade' => $this->grade,
                'homeroom_teacher_id' => $this->homeroom_teacher_id,
            ]);
            $this->successMessage = "Kelas {$class->name} berhasil ditambahkan.";
        }

        $this->showModal = false;
    }

    public function delete(int $id)
    {
        $class = SchoolClass::findOrFail($id);
        $name = $class->name;
        $class->delete();
        $this->successMessage = "Kelas {$name} berhasil dihapus.";
    }

    public function render()
    {
        $classes = SchoolClass::with(['homeroomTeacher', 'academicYear'])
            ->withCount('students')
            ->orderBy('name')
            ->paginate(15);

        $teachers = Teacher::orderBy('name')->get();

        return view('livewire.admin.classes', compact('classes', 'teachers'));
    }
}
