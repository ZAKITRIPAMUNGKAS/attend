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
        $teacher = $this->teacherId ? Teacher::with('user')->findOrFail($this->teacherId) : null;
        $userId = $teacher?->user_id;

        $this->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('users', 'username')->ignore($userId),
            ],
            'email' => [
                'nullable',
                'email',
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($userId),
            ],
            'homeroom_class_id' => 'nullable|exists:classes,id',
            'password' => 'nullable|string|min:4',
        ]);

        if ($this->teacherId && $teacher) {
            $teacher->update([
                'name' => $this->name,
                'nip' => $this->nip ?: null,
                'phone' => $this->phone ?: null,
                'gender' => $this->gender ?: 'L',
            ]);

            $userData = [
                'name' => $this->name,
                'username' => $this->username,
                'email' => !empty($this->email) ? $this->email : ($teacher->user?->email ?: null),
                'phone' => $this->phone ?: null,
            ];
            if (!empty($this->password)) {
                $userData['password'] = Hash::make($this->password);
                $userData['is_default_password'] = false;
            }

            if ($teacher->user) {
                $teacher->user->update($userData);
            } else {
                $user = User::create(array_merge($userData, [
                    'password' => !empty($this->password) ? Hash::make($this->password) : Hash::make('password'),
                    'role' => 'teacher',
                ]));
                $teacher->update(['user_id' => $user->id]);
            }

            // Update Homeroom Assignment
            SchoolClass::where('homeroom_teacher_id', $teacher->id)->update(['homeroom_teacher_id' => null]);
            if (!empty($this->homeroom_class_id)) {
                SchoolClass::where('id', $this->homeroom_class_id)->update(['homeroom_teacher_id' => $teacher->id]);
            }

            $this->successMessage = "Data guru {$teacher->name} berhasil diperbarui.";
        } else {
            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => !empty($this->email) ? $this->email : null,
                'password' => Hash::make(!empty($this->password) ? $this->password : 'password'),
                'role' => 'teacher',
                'phone' => $this->phone ?: null,
            ]);

            $newTeacher = Teacher::create([
                'user_id' => $user->id,
                'nip' => $this->nip ?: null,
                'name' => $this->name,
                'phone' => $this->phone ?: null,
                'gender' => $this->gender ?: 'L',
            ]);

            if (!empty($this->homeroom_class_id)) {
                SchoolClass::where('id', $this->homeroom_class_id)->update(['homeroom_teacher_id' => $newTeacher->id]);
            }

            $this->successMessage = "Guru {$newTeacher->name} berhasil ditambahkan.";
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
