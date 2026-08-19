<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SyncMissingStudentsSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::active() ?? AcademicYear::first();
        if (!$academicYear) {
            $this->command->error('Tahun ajaran aktif tidak ditemukan.');
            return;
        }

        $classXAkhwat = SchoolClass::where('name', 'like', '%X Akhwat%')->first();
        $classXIIkhwan = SchoolClass::where('name', 'like', '%XI Ikhwan%')->first();

        if (!$classXAkhwat || !$classXIIkhwan) {
            $this->command->error('Kelas X Akhwat atau XI Ikhwan tidak ditemukan.');
            return;
        }

        $studentsToAdd = [
            [
                'name' => 'Nida Qonita',
                'class_id' => $classXAkhwat->id,
                'gender' => 'P',
                'nisn' => '0091010018',
                'nis' => '26271035',
                'birth_date' => '2010-06-18',
                'default_pass' => '18062010',
            ],
            [
                'name' => 'Hanif Afandi',
                'class_id' => $classXIIkhwan->id,
                'gender' => 'L',
                'nisn' => '0081020008',
                'nis' => '25261020',
                'birth_date' => '2009-08-08',
                'default_pass' => '08082009',
            ],
            [
                'name' => 'Ahnaf Al Fawwas',
                'class_id' => $classXIIkhwan->id,
                'gender' => 'L',
                'nisn' => '0081020009',
                'nis' => '25261021',
                'birth_date' => '2009-09-09',
                'default_pass' => '09092009',
            ],
        ];

        foreach ($studentsToAdd as $item) {
            $existing = Student::where('name', $item['name'])
                ->orWhere('nisn', $item['nisn'])
                ->first();

            if ($existing) {
                $this->command->info("Murid {$item['name']} sudah ada di database (NISN: {$existing->nisn}).");
                continue;
            }

            // Buat Akun User Siswa
            $user = User::create([
                'name' => $item['name'],
                'username' => $item['nisn'],
                'email' => strtolower(Str::slug($item['name'], '.')) . '@siswa.smait.sch.id',
                'password' => Hash::make($item['default_pass']),
                'role' => 'student',
                'is_default_password' => true,
                'phone' => '08' . str_pad((string)rand(100000000, 999999999), 10, '0', STR_PAD_LEFT),
            ]);

            // Buat Profil Siswa
            $student = Student::create([
                'user_id' => $user->id,
                'class_id' => $item['class_id'],
                'academic_year_id' => $academicYear->id,
                'nis' => $item['nis'],
                'nisn' => $item['nisn'],
                'name' => $item['name'],
                'gender' => $item['gender'],
                'birth_place' => 'Karanganyar',
                'birth_date' => $item['birth_date'],
                'phone' => $user->phone,
                'parent_name' => 'Wali ' . $item['name'],
                'parent_phone' => '08' . str_pad((string)rand(100000000, 999999999), 10, '0', STR_PAD_LEFT),
                'qr_token' => 'SMAIT_QR_' . strtoupper(Str::random(16)) . '_' . $item['nisn'],
                'status' => 'aktif',
            ]);

            $this->command->info("✅ Berhasil menambahkan murid: {$student->name} ke Kelas {$student->schoolClass->name} (NISN: {$student->nisn})");
        }
    }
}
