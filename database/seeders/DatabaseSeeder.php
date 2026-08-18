<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\PermissionRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tahun Ajaran Aktif
        $academicYear = AcademicYear::create([
            'name' => '2026/2027',
            'semester' => 'ganjil',
            'start_date' => '2026-07-15',
            'end_date' => '2026-12-20',
            'is_active' => true,
        ]);

        // 2. Pengaturan Absensi
        AttendanceSetting::create([
            'academic_year_id' => $academicYear->id,
            'check_in_start' => '06:00:00',
            'on_time_until' => '07:00:00',
            'late_until' => '10:00:00',
            'auto_absent_at' => '10:00:00',
            'allow_checkout' => true,
            'check_out_start' => '15:00:00',
            'check_out_end' => '17:00:00',
        ]);

        // 3. User Admin
        $adminUser = User::create([
            'name' => 'Administrator SMA IT Insan Kamil',
            'username' => 'admin',
            'email' => 'admin@smait.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_default_password' => false,
            'phone' => '081234567890',
        ]);

        // 4. Data Guru & Wali Kelas
        $guruData = [
            [
                'name' => 'Ustadz Abdullah S.Pd',
                'username' => 'guru_abdullah',
                'email' => 'abdullah@smait.sch.id',
                'nip' => '198501012010011001',
                'phone' => '081298765431',
                'gender' => 'L',
            ],
            [
                'name' => 'Ustadzah Siti Maryam M.Pd',
                'username' => 'guru_maryam',
                'email' => 'maryam@smait.sch.id',
                'nip' => '198805122012012002',
                'phone' => '081298765432',
                'gender' => 'P',
            ],
            [
                'name' => 'Ustadz Ahmad Fauzi S.Si',
                'username' => 'guru_fauzi',
                'email' => 'fauzi@smait.sch.id',
                'nip' => '199003202015011003',
                'phone' => '081298765433',
                'gender' => 'L',
            ],
            [
                'name' => 'Ustadzah Nurul Hidayah S.Pd',
                'username' => 'guru_nurul',
                'email' => 'nurul@smait.sch.id',
                'nip' => '199207142018012004',
                'phone' => '081298765434',
                'gender' => 'P',
            ],
        ];

        $teachers = [];
        foreach ($guruData as $g) {
            $userGuru = User::create([
                'name' => $g['name'],
                'username' => $g['username'],
                'email' => $g['email'],
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'is_default_password' => false,
                'phone' => $g['phone'],
            ]);

            $teachers[] = Teacher::create([
                'user_id' => $userGuru->id,
                'nip' => $g['nip'],
                'name' => $g['name'],
                'phone' => $g['phone'],
                'gender' => $g['gender'],
            ]);
        }

        // 5. Data Rombel Kelas Real (X Akhwat, X Ikhwan, XI Akhwat, XI Ikhwan)
        $classXAkhwat = SchoolClass::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'X Akhwat',
            'grade' => 'X',
            'homeroom_teacher_id' => $teachers[1]->id, // Ustdz. Maryam
        ]);

        $classXIkhwan = SchoolClass::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'X Ikhwan',
            'grade' => 'X',
            'homeroom_teacher_id' => $teachers[0]->id, // Ustd. Abdullah
        ]);

        $classXIAkhwat = SchoolClass::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'XI Akhwat',
            'grade' => 'XI',
            'homeroom_teacher_id' => $teachers[3]->id, // Ustdz. Nurul
        ]);

        $classXIIkhwan = SchoolClass::create([
            'academic_year_id' => $academicYear->id,
            'name' => 'XI Ikhwan',
            'grade' => 'XI',
            'homeroom_teacher_id' => $teachers[2]->id, // Ustd. Ahmad Fauzi
        ]);

        // 6. Data Riil Siswa SMAIT Insan Kamil Karanganyar (53 Siswa)
        $realStudents = [
            // === KELAS X AKHWAT (17 Siswa) ===
            ['name' => 'Astiningrum Ika', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010001', 'nis' => '26271001'],
            ['name' => 'Dhia Husna Salsabila', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010002', 'nis' => '26271002'],
            ['name' => "Fatimah Khoirunnisa'", 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010003', 'nis' => '26271003'],
            ['name' => 'Fatiya Rodhwa Salsabila', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010004', 'nis' => '26271004'],
            ['name' => 'Fauzizah Nur Aini', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010005', 'nis' => '26271005'],
            ['name' => 'Gustiah Alfiah Almas', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010006', 'nis' => '26271006'],
            ['name' => 'Hana Arifah Mazayadina', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010007', 'nis' => '26271007'],
            ['name' => 'Hasna Nabila Rosida', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010008', 'nis' => '26271008'],
            ['name' => 'Imelda Cahya Rizqiwati', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010009', 'nis' => '26271009'],
            ['name' => 'Khoirunnisa Salsabila', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010010', 'nis' => '26271010'],
            ['name' => 'Nabill Labibah', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010011', 'nis' => '26271011'],
            ['name' => 'Nida Khafiyyah', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010012', 'nis' => '26271012'],
            ['name' => 'Prita Cece Rahmadani', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010013', 'nis' => '26271013'],
            ['name' => 'Quilla Dzahabiyya', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010014', 'nis' => '26271014'],
            ['name' => 'Sasya Fathin Musyaffa', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010015', 'nis' => '26271015'],
            ['name' => 'Tsabita Hasna Adi Dzakya', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010016', 'nis' => '26271016'],
            ['name' => 'Zidna Ilma Azizah', 'class' => $classXAkhwat, 'gender' => 'P', 'nisn' => '0091010017', 'nis' => '26271017'],

            // === KELAS X IKHWAN (17 Siswa) ===
            ['name' => 'Abdullah Zhafar Siddiq', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020001', 'nis' => '26271018'],
            ['name' => 'Alfian Ashraf Setiawan', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020002', 'nis' => '26271019'],
            ['name' => 'Bayu Kristanto', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020003', 'nis' => '26271020'],
            ['name' => 'Elgy Risky Saputra', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020004', 'nis' => '26271021'],
            ['name' => 'Fadhil Abdullah Faqih', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020005', 'nis' => '26271022'],
            ['name' => 'Faiz Satya Putra', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020006', 'nis' => '26271023'],
            ['name' => 'Jauhari rafif falah', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020007', 'nis' => '26271024'],
            ['name' => 'Miftahul Ahnaf Nasrullah', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020008', 'nis' => '26271025'],
            ['name' => 'Muhammad Kamal Fauzi', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020009', 'nis' => '26271026'],
            ['name' => 'Muhammad Rizky Tulus Abdilla', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020010', 'nis' => '26271027'],
            ['name' => 'Muhammad Rizqi Syarifuddin', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020011', 'nis' => '26271028'],
            ['name' => 'Musa Abdurrohman', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020012', 'nis' => '26271029'],
            ['name' => 'Nidaan Khafiyya Renan', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020013', 'nis' => '26271030'],
            ['name' => 'Rafi Naufal Tsaqif', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020014', 'nis' => '26271031'],
            ['name' => 'Raihan Anwar Fatih Ilham', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020015', 'nis' => '26271032'],
            ['name' => 'Raihan Habibi', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020016', 'nis' => '26271033'],
            ['name' => 'Zulfan Akhmad Al Latif', 'class' => $classXIkhwan, 'gender' => 'L', 'nisn' => '0091020017', 'nis' => '26271034'],

            // === KELAS XI AKHWAT (12 Siswa) ===
            ['name' => 'Alfinza Faustina Rozzaqu', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010001', 'nis' => '25261001'],
            ['name' => 'Atina Sabilarrosyad', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010002', 'nis' => '25261002'],
            ['name' => 'Auliyatusyfa Maulina', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010003', 'nis' => '25261003'],
            ['name' => "Farras Mukhita Robbi'i", 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010004', 'nis' => '25261004'],
            ['name' => 'Hanin Khoirotun Hisan', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010005', 'nis' => '25261005'],
            ['name' => 'Iyadzi Nisa Shadiqaat', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010006', 'nis' => '25261006'],
            ['name' => 'Muna Hapsari Masyhuroh', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010007', 'nis' => '25261007'],
            ['name' => 'Nafisah Aqila Husna', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010008', 'nis' => '25261008'],
            ['name' => 'Rifa Irdina Zafirah', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010009', 'nis' => '25261009'],
            ['name' => 'Sadina Putri Pratista Apsarini', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010010', 'nis' => '25261010'],
            ['name' => 'Stevani Gabryela', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010011', 'nis' => '25261011'],
            ['name' => 'Khalishah Nara Syafrina', 'class' => $classXIAkhwat, 'gender' => 'P', 'nisn' => '0081010012', 'nis' => '25261012'],

            // === KELAS XI IKHWAN (7 Siswa) ===
            ['name' => 'Afif Hisyam Al Haidar', 'class' => $classXIIkhwan, 'gender' => 'L', 'nisn' => '0081020001', 'nis' => '25261013'],
            ['name' => 'Azzam Ghozy Robbam', 'class' => $classXIIkhwan, 'gender' => 'L', 'nisn' => '0081020002', 'nis' => '25261014'],
            ['name' => 'Dzulfiqar Muhammad', 'class' => $classXIIkhwan, 'gender' => 'L', 'nisn' => '0081020003', 'nis' => '25261015'],
            ['name' => 'Falih Akhyar Robbani', 'class' => $classXIIkhwan, 'gender' => 'L', 'nisn' => '0081020004', 'nis' => '25261016'],
            ['name' => 'Muhammad Alfatih Murod R', 'class' => $classXIIkhwan, 'gender' => 'L', 'nisn' => '0081020005', 'nis' => '25261017'],
            ['name' => 'Muhammad Arkam Azzam', 'class' => $classXIIkhwan, 'gender' => 'L', 'nisn' => '0081020006', 'nis' => '25261018'],
            ['name' => 'Rafid Iltizam Aminuddin', 'class' => $classXIIkhwan, 'gender' => 'L', 'nisn' => '0081020007', 'nis' => '25261019'],
        ];

        $createdStudents = [];
        $index = 1;

        foreach ($realStudents as $item) {
            // Default Tanggal Lahir (Format: YYYY-MM-DD -> Password default DDMMYYYY)
            // Misal: Lahir 2010 (Kelas X) & 2009 (Kelas XI)
            $birthYear = ($item['class']->grade === 'X') ? 2010 : 2009;
            $birthMonth = str_pad(($index % 12) + 1, 2, '0', STR_PAD_LEFT);
            $birthDay = str_pad(($index % 28) + 1, 2, '0', STR_PAD_LEFT);
            $birthDate = "{$birthYear}-{$birthMonth}-{$birthDay}";
            $defaultPassword = "{$birthDay}{$birthMonth}{$birthYear}";

            // Username = NISN
            $userStudent = User::create([
                'name' => $item['name'],
                'username' => $item['nisn'],
                'email' => strtolower(Str::slug($item['name'], '.')) . '@siswa.smait.sch.id',
                'password' => Hash::make($defaultPassword),
                'role' => 'student',
                'is_default_password' => true,
                'phone' => '08' . str_pad((string)rand(100000000, 999999999), 10, '0', STR_PAD_LEFT),
            ]);

            $student = Student::create([
                'user_id' => $userStudent->id,
                'class_id' => $item['class']->id,
                'academic_year_id' => $academicYear->id,
                'nis' => $item['nis'],
                'nisn' => $item['nisn'],
                'name' => $item['name'],
                'gender' => $item['gender'],
                'birth_place' => 'Karanganyar',
                'birth_date' => $birthDate,
                'phone' => $userStudent->phone,
                'parent_name' => 'Wali ' . $item['name'],
                'parent_phone' => '08' . str_pad((string)rand(100000000, 999999999), 10, '0', STR_PAD_LEFT),
                'qr_token' => 'SMAIT_QR_' . strtoupper(Str::random(16)) . '_' . $item['nisn'],
                'status' => 'aktif',
            ]);

            $createdStudents[] = $student;
            $index++;
        }

        // 7. Riwayat Kehadiran 1 Minggu ke Belakang (Hapus hari ini, isi 5 hari kerja sebelumnya)
        $this->call(AttendanceHistorySeeder::class);
    }
}
