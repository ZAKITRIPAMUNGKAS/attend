<?php

namespace App\Livewire\Admin;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Students extends Component
{
    use WithPagination;

    // View navigation: null = Class Directory View, int = Student Roster for that Class, 'all' = All Students
    public ?int $selectedClassId = null;
    public bool $viewAll = false;

    public string $search = '';
    public string $filterClass = '';
    
    // Modal Detail Siswa
    public bool $showDetailModal = false;
    public ?int $detailedStudentId = null;

    // Modal Form properties (Tambah/Edit Biodata Siswa)
    public bool $showModal = false;
    public ?int $studentId = null;
    public string $name = '';
    public string $nisn = '';
    public string $nis = '';
    public string $gender = 'L';
    public string $birth_place = '';
    public string $birth_date = '';
    public ?int $class_id = null;
    public string $phone = '';
    public string $parent_name = '';
    public string $parent_phone = '';
    public string $status = 'aktif';

    // Modal Rol / Ganti Wali Kelas
    public bool $showHomeroomModal = false;
    public ?int $homeroomClassId = null;
    public string $homeroomClassName = '';
    public ?int $newHomeroomTeacherId = null;

    // Modal Rol Wali Murid & Enroll / Pindah Kelas
    public bool $showParentModal = false;
    public ?int $parentStudentId = null;
    public string $parentStudentName = '';
    public string $editParentName = '';
    public string $editParentPhone = '';
    public ?int $editStudentClassId = null;

    public string $successMessage = '';

    public function selectClass(int $classId)
    {
        $this->selectedClassId = $classId;
        $this->viewAll = false;
        $this->filterClass = (string) $classId;
        $this->search = '';
        $this->resetPage();
    }

    public function showAllStudents()
    {
        $this->selectedClassId = null;
        $this->viewAll = true;
        $this->filterClass = '';
        $this->search = '';
        $this->resetPage();
    }

    public function backToClasses()
    {
        $this->selectedClassId = null;
        $this->viewAll = false;
        $this->filterClass = '';
        $this->search = '';
        $this->resetPage();
    }

    public function showDetail(int $id)
    {
        $this->detailedStudentId = $id;
        $this->showDetailModal = true;
    }

    public function create()
    {
        $this->reset(['studentId', 'name', 'nisn', 'nis', 'gender', 'birth_place', 'birth_date', 'class_id', 'phone', 'parent_name', 'parent_phone', 'status']);
        $this->gender = 'L';
        $this->status = 'aktif';
        $this->class_id = $this->selectedClassId ?? SchoolClass::first()?->id;
        $this->birth_date = '2010-01-01';
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $student = Student::findOrFail($id);
        $this->studentId = $student->id;
        $this->name = $student->name;
        $this->nisn = $student->nisn;
        $this->nis = $student->nis ?? '';
        $this->gender = $student->gender;
        $this->birth_place = $student->birth_place ?? '';
        $this->birth_date = $student->birth_date ? Carbon::parse($student->birth_date)->format('Y-m-d') : '';
        $this->class_id = $student->class_id;
        $this->phone = $student->phone ?? '';
        $this->parent_name = $student->parent_name ?? '';
        $this->parent_phone = $student->parent_phone ?? '';
        $this->status = $student->status;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:students,nisn,' . $this->studentId,
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'status' => 'required|in:aktif,nonaktif,lulus,mutasi',
        ]);

        $activeYear = AcademicYear::active() ?? AcademicYear::first();
        $dob = Carbon::parse($this->birth_date);
        $defaultPassword = $dob->format('dmY');

        if ($this->studentId) {
            $student = Student::findOrFail($this->studentId);
            $student->update([
                'name' => $this->name,
                'nisn' => $this->nisn,
                'nis' => $this->nis,
                'gender' => $this->gender,
                'birth_place' => $this->birth_place,
                'birth_date' => $this->birth_date,
                'class_id' => $this->class_id,
                'phone' => $this->phone,
                'parent_name' => $this->parent_name,
                'parent_phone' => $this->parent_phone,
                'status' => $this->status,
            ]);

            $student->user->update([
                'name' => $this->name,
                'username' => $this->nisn,
            ]);

            $this->successMessage = "Data siswa {$student->name} berhasil diperbarui.";
        } else {
            // Buat User Akun Siswa Baru
            $user = User::create([
                'name' => $this->name,
                'username' => $this->nisn,
                'email' => $this->nisn . '@siswa.smait.sch.id',
                'password' => Hash::make($defaultPassword),
                'role' => 'student',
                'is_default_password' => true,
                'phone' => $this->phone,
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'class_id' => $this->class_id,
                'academic_year_id' => $activeYear?->id,
                'nisn' => $this->nisn,
                'nis' => $this->nis,
                'name' => $this->name,
                'gender' => $this->gender,
                'birth_place' => $this->birth_place,
                'birth_date' => $this->birth_date,
                'phone' => $this->phone,
                'parent_name' => $this->parent_name,
                'parent_phone' => $this->parent_phone,
                'qr_token' => Str::random(40),
                'status' => $this->status,
            ]);

            $this->successMessage = "Siswa {$student->name} berhasil ditambahkan ke kelas {$student->schoolClass->name} (Password: {$defaultPassword}).";
        }

        $this->showModal = false;
    }

    // --- ROL WALI KELAS ---
    public function openAssignHomeroom(int $classId)
    {
        $class = SchoolClass::findOrFail($classId);
        $this->homeroomClassId = $class->id;
        $this->homeroomClassName = $class->name;
        $this->newHomeroomTeacherId = $class->homeroom_teacher_id;
        $this->showHomeroomModal = true;
    }

    public function saveHomeroomTeacher()
    {
        $this->validate([
            'newHomeroomTeacherId' => 'nullable|exists:teachers,id',
        ]);

        if ($this->homeroomClassId) {
            $class = SchoolClass::findOrFail($this->homeroomClassId);
            $class->update([
                'homeroom_teacher_id' => $this->newHomeroomTeacherId ?: null,
            ]);
            $teacherName = $class->homeroomTeacher?->name ?? 'Belum Ditugaskan';
            $this->successMessage = "Wali kelas {$class->name} berhasil diperbarui menjadi: {$teacherName}.";
        }
        $this->showHomeroomModal = false;
    }

    // --- ROL WALI MURID & ENROLL/PINDAH KELAS ---
    public function openEditParent(int $studentId)
    {
        $student = Student::findOrFail($studentId);
        $this->parentStudentId = $student->id;
        $this->parentStudentName = $student->name;
        $this->editParentName = $student->parent_name ?? '';
        $this->editParentPhone = $student->parent_phone ?? '';
        $this->editStudentClassId = $student->class_id;
        $this->showParentModal = true;
    }

    public function saveParentAndEnrollment()
    {
        if ($this->parentStudentId) {
            $student = Student::findOrFail($this->parentStudentId);
            $student->update([
                'parent_name' => $this->editParentName ?: null,
                'parent_phone' => $this->editParentPhone ?: null,
                'class_id' => $this->editStudentClassId,
            ]);

            $this->successMessage = "Data wali murid & penempatan kelas {$student->name} berhasil diperbarui.";
        }
        $this->showParentModal = false;
    }

    public function resetPassword(int $id)
    {
        $student = Student::with('user')->findOrFail($id);
        $dob = Carbon::parse($student->birth_date);
        $defaultPassword = $dob->format('dmY');

        $student->user->update([
            'password' => Hash::make($defaultPassword),
            'is_default_password' => true,
        ]);

        $this->successMessage = "Password siswa {$student->name} berhasil di-reset ke tanggal lahir ({$defaultPassword}).";
    }

    public function regenerateQr(int $id)
    {
        $student = Student::findOrFail($id);
        $student->regenerateQrToken();
        $this->successMessage = "QR Token siswa {$student->name} berhasil digenerate ulang.";
    }

    public function delete(int $id)
    {
        $student = Student::with('user')->findOrFail($id);
        $name = $student->name;
        $student->user->delete(); // cascades to student
        $this->successMessage = "Data siswa {$name} berhasil dihapus.";
    }

    public function render()
    {
        $classes = SchoolClass::withCount(['students' => function ($q) {
            $q->where('status', 'aktif');
        }])->with('homeroomTeacher')->get();

        $teachers = Teacher::orderBy('name')->get();
        $currentClass = $this->selectedClassId ? SchoolClass::find($this->selectedClassId) : null;

        $detailedStudent = null;
        if ($this->showDetailModal && $this->detailedStudentId) {
            $detailedStudent = Student::with(['schoolClass.homeroomTeacher', 'academicYear', 'user', 'attendances'])->find($this->detailedStudentId);
        }

        $students = null;
        if ($this->selectedClassId || $this->viewAll) {
            $query = Student::with(['schoolClass', 'user'])
                ->orderBy('name', 'asc');

            if ($this->selectedClassId) {
                $query->where('class_id', $this->selectedClassId);
            }

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('nisn', 'like', '%' . $this->search . '%')
                      ->orWhere('nis', 'like', '%' . $this->search . '%')
                      ->orWhere('parent_name', 'like', '%' . $this->search . '%');
                });
            }

            $students = $query->paginate(15);
        }

        return view('livewire.admin.students', compact('classes', 'teachers', 'students', 'currentClass', 'detailedStudent'));
    }
}
