<?php

namespace App\Livewire\Admin;

use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Teachers extends Component
{
    use WithPagination;

    public string $search = '';

    // Modal Tambah / Edit Guru Form
    public bool $showModal = false;
    public ?int $teacherId = null;
    public string $name = '';
    public string $nip = '';
    public string $username = '';
    public string $email = '';
    public string $phone = '';
    public string $gender = 'L';
    public string $password = '';
    public ?int $homeroom_class_id = null;

    // Quick Modal Set Wali Kelas
    public bool $showQuickHomeroomModal = false;
    public ?int $quickTeacherId = null;
    public string $quickTeacherName = '';
    public ?int $quickClassId = null;

    public string $successMessage = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['teacherId', 'name', 'nip', 'username', 'email', 'phone', 'gender', 'password', 'homeroom_class_id']);
        $this->gender = 'L';
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $teacher = Teacher::with(['user', 'homeroomClasses'])->findOrFail($id);
        $this->teacherId = $teacher->id;
        $this->name = $teacher->name;
        $this->nip = $teacher->nip ?? '';
        $this->username = $teacher->user->username ?? '';
        $this->email = $teacher->user->email ?? '';
        $this->phone = $teacher->phone ?? '';
        $this->gender = $teacher->gender ?? 'L';
        $this->password = '';
        $this->homeroom_class_id = $teacher->homeroomClasses->first()?->id;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50',
            'email' => 'nullable|email',
            'homeroom_class_id' => 'nullable|exists:classes,id',
        ]);

        if ($this->teacherId) {
            $teacher = Teacher::findOrFail($this->teacherId);
            $teacher->update([
                'name' => $this->name,
                'nip' => $this->nip,
                'phone' => $this->phone,
                'gender' => $this->gender,
            ]);

            $userData = [
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email ?: ($this->username . '@guru.smait.sch.id'),
                'phone' => $this->phone,
            ];
            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
            }
            $teacher->user->update($userData);

            // Update Homeroom Assignment
            SchoolClass::where('homeroom_teacher_id', $teacher->id)->update(['homeroom_teacher_id' => null]);
            if ($this->homeroom_class_id) {
                SchoolClass::where('id', $this->homeroom_class_id)->update(['homeroom_teacher_id' => $teacher->id]);
            }

            $this->successMessage = "Data guru {$teacher->name} berhasil diperbarui.";
        } else {
            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email ?: ($this->username . '@guru.smait.sch.id'),
                'password' => Hash::make($this->password ?: 'password'),
                'role' => 'teacher',
                'phone' => $this->phone,
            ]);

            $teacher = Teacher::create([
                'user_id' => $user->id,
                'nip' => $this->nip,
                'name' => $this->name,
                'phone' => $this->phone,
                'gender' => $this->gender,
            ]);

            if ($this->homeroom_class_id) {
                SchoolClass::where('id', $this->homeroom_class_id)->update(['homeroom_teacher_id' => $teacher->id]);
            }

            $this->successMessage = "Guru {$teacher->name} berhasil ditambahkan.";
        }

        $this->showModal = false;
    }

    public function openQuickHomeroom(int $teacherId)
    {
        $teacher = Teacher::with('homeroomClasses')->findOrFail($teacherId);
        $this->quickTeacherId = $teacher->id;
        $this->quickTeacherName = $teacher->name;
        $this->quickClassId = $teacher->homeroomClasses->first()?->id;
        $this->showQuickHomeroomModal = true;
    }

    public function saveQuickHomeroom()
    {
        if ($this->quickTeacherId) {
            $teacher = Teacher::findOrFail($this->quickTeacherId);
            
            // Release previous class if any
            SchoolClass::where('homeroom_teacher_id', $teacher->id)->update(['homeroom_teacher_id' => null]);

            if ($this->quickClassId) {
                $class = SchoolClass::findOrFail($this->quickClassId);
                $class->update(['homeroom_teacher_id' => $teacher->id]);
                $this->successMessage = "Guru {$teacher->name} berhasil ditetapkan sebagai Wali Kelas {$class->name}.";
            } else {
                $this->successMessage = "Penugasan wali kelas untuk {$teacher->name} berhasil dikosongkan.";
            }
        }
        $this->showQuickHomeroomModal = false;
    }

    public function delete(int $id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        $name = $teacher->name;
        SchoolClass::where('homeroom_teacher_id', $teacher->id)->update(['homeroom_teacher_id' => null]);
        $teacher->user->delete(); // cascades to teacher
        $this->successMessage = "Guru {$name} berhasil dihapus.";
    }

    public function render()
    {
        $query = Teacher::with(['user', 'homeroomClasses'])->orderBy('name');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('nip', 'like', '%' . $this->search . '%');
            });
        }

        $teachers = $query->paginate(15);
        $classes = SchoolClass::orderBy('name')->get();

        return view('livewire.admin.teachers', compact('teachers', 'classes'));
    }
}
