<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\PermissionRequest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Set fixed test time during valid attendance hours (06:45 AM today)
        Carbon::setTestNow(Carbon::today()->setHour(6)->setMinute(45)->setSecond(0));
        $this->seed();
        Attendance::whereDate('date', Carbon::today()->toDateString())->delete();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_login_page_renders_successfully()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SmartPresensi');
    }

    public function test_student_dashboard_accessible_when_authenticated_as_student()
    {
        $studentUser = User::where('role', 'student')->first();
        $response = $this->actingAs($studentUser)->get('/siswa/dashboard');
        $response->assertStatus(200);
        $response->assertSee('SmartPresensi');
    }

    public function test_teacher_dashboard_accessible_when_authenticated_as_teacher()
    {
        $teacherUser = User::where('role', 'teacher')->first();
        $response = $this->actingAs($teacherUser)->get('/guru/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Mulai Scan');
    }

    public function test_teacher_permission_approval_accessible_when_authenticated()
    {
        $teacherUser = User::where('role', 'teacher')->first();
        $response = $this->actingAs($teacherUser)->get('/guru/izin-siswa');
        $response->assertStatus(200);
        $response->assertSee('Persetujuan Izin');
    }

    public function test_admin_dashboard_accessible_when_authenticated_as_admin()
    {
        $adminUser = User::where('role', 'admin')->first();
        $response = $this->actingAs($adminUser)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Executive Overview');
    }

    public function test_qr_share_page_accessible_by_admin_and_teacher()
    {
        $adminUser = User::where('role', 'admin')->first();
        $response = $this->actingAs($adminUser)->get('/admin/share-qr');
        $response->assertStatus(200);
        $response->assertSee('Bagikan QR Presensi');

        $teacherUser = User::where('role', 'teacher')->first();
        $responseTeacher = $this->actingAs($teacherUser)->get('/guru/share-qr');
        $responseTeacher->assertStatus(200);
        $responseTeacher->assertSee('Bagikan QR Presensi');
        $responseTeacher->assertSee('QR Khusus Kelas');
    }

    public function test_student_can_scan_general_qr_code_for_attendance()
    {
        $service = new AttendanceService();
        $unattendedStudent = Student::whereDoesntHave('attendances', function ($q) {
            $q->whereDate('date', Carbon::today()->toDateString());
        })->where('status', 'aktif')->first();

        $this->assertNotNull($unattendedStudent);

        $result = $service->recordSelfAttendance($unattendedStudent, 'SMAIT_GENERAL_ATTENDANCE_QR');

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('attendance', [
            'student_id' => $unattendedStudent->id,
            'status' => 'hadir',
        ]);
    }

    public function test_student_can_scan_their_own_class_qr_code()
    {
        $service = new AttendanceService();
        $unattendedStudent = Student::whereDoesntHave('attendances', function ($q) {
            $q->whereDate('date', Carbon::today()->toDateString());
        })->where('status', 'aktif')->first();

        $this->assertNotNull($unattendedStudent);

        // Scan valid class QR for student's own class
        $classPayload = 'SMAIT_CLASS_QR_' . $unattendedStudent->class_id;
        $result = $service->recordSelfAttendance($unattendedStudent, $classPayload);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('attendance', [
            'student_id' => $unattendedStudent->id,
            'status' => 'hadir',
        ]);
    }

    public function test_student_cannot_scan_different_class_qr_code()
    {
        $service = new AttendanceService();
        $unattendedStudent = Student::whereDoesntHave('attendances', function ($q) {
            $q->whereDate('date', Carbon::today()->toDateString());
        })->where('status', 'aktif')->first();

        $this->assertNotNull($unattendedStudent);

        // Scan QR of a different class
        $otherClass = SchoolClass::where('id', '!=', $unattendedStudent->class_id)->first();
        $wrongClassPayload = 'SMAIT_CLASS_QR_' . $otherClass->id;
        $result = $service->recordSelfAttendance($unattendedStudent, $wrongClassPayload);

        $this->assertFalse($result['success']);
        $this->assertEquals('WRONG_CLASS', $result['code']);
    }

    public function test_attendance_service_records_scan_properly()
    {
        $service = new AttendanceService();
        $teacherUser = User::where('role', 'teacher')->first();
        
        $unattendedStudent = Student::whereDoesntHave('attendances', function ($q) {
            $q->whereDate('date', Carbon::today()->toDateString());
        })->where('status', 'aktif')->first();

        $this->assertNotNull($unattendedStudent);

        $result = $service->recordAttendance($unattendedStudent->qr_token, $teacherUser);

        $this->assertTrue($result['success']);
        $this->assertDatabaseHas('attendance', [
            'student_id' => $unattendedStudent->id,
        ]);
    }

    public function test_attendance_service_records_checkout_properly()
    {
        $service = new AttendanceService();
        $teacherUser = User::where('role', 'teacher')->first();
        $student = Student::first();

        // Simulate afternoon check-out time (15:30 PM)
        Carbon::setTestNow(Carbon::create(2026, 8, 18, 15, 30, 0));

        $result = $service->recordAttendance($student->qr_token, $teacherUser, 'check_out');

        $this->assertTrue($result['success']);
        $this->assertEquals('check_out', $result['type']);
        $this->assertDatabaseHas('attendance', [
            'student_id' => $student->id,
            'check_out' => '15:30:00',
        ]);

        Carbon::setTestNow(); // Reset test time
    }

    public function test_attendance_service_prevents_duplicate_scan_in_same_day()
    {
        $service = new AttendanceService();
        $teacherUser = User::where('role', 'teacher')->first();

        // Cari siswa yang belum absen atau buat absensi pertama
        $student = Student::whereDoesntHave('attendances')->first() ?? Student::first();
        
        // Scan pertama (Check-In)
        $firstResult = $service->recordAttendance($student->qr_token, $teacherUser, 'check_in');

        // Scan kedua kali di hari yang sama harus gagal (DUPLICATE)
        $result = $service->recordAttendance($student->qr_token, $teacherUser, 'check_in');

        $this->assertFalse($result['success']);
        $this->assertEquals('DUPLICATE', $result['code']);
    }

    public function test_auto_alpa_marks_unrecorded_students()
    {
        $service = new AttendanceService();
        $testDate = '2026-08-10';

        $count = $service->runAutoAlpa($testDate);

        $this->assertGreaterThan(0, $count);
        $this->assertDatabaseHas('attendance', [
            'status' => 'alpa',
            'method' => 'system',
        ]);
    }

    public function test_pdf_and_excel_exports_are_accessible_for_admin()
    {
        $adminUser = User::where('role', 'admin')->first();

        $pdfResponse = $this->actingAs($adminUser)->get('/export/pdf?date=' . Carbon::today()->toDateString());
        $pdfResponse->assertStatus(200);

        $excelResponse = $this->actingAs($adminUser)->get('/export/excel?mode=daily');
        $excelResponse->assertStatus(200);

        $printQrResponse = $this->actingAs($adminUser)->get('/export/print-qr');
        $printQrResponse->assertStatus(200);

        $printPosterResponse = $this->actingAs($adminUser)->get('/export/print-poster-qr');
        $printPosterResponse->assertStatus(200);

        $studentsCsvResponse = $this->actingAs($adminUser)->get('/export/students');
        $studentsCsvResponse->assertStatus(200);

        $studentsPdfResponse = $this->actingAs($adminUser)->get('/export/students-pdf');
        $studentsPdfResponse->assertStatus(200);
    }
}
