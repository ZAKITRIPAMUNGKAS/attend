<?php

use App\Http\Controllers\ReportExportController;
use App\Livewire\Admin\AcademicYears;
use App\Livewire\Admin\AttendanceRecap;
use App\Livewire\Admin\Classes;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\QrShare;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Students;
use App\Livewire\Admin\Teachers;
use App\Livewire\Auth\Login;
use App\Livewire\Student\Dashboard as StudentDashboard;
use App\Livewire\Student\History as StudentHistory;
use App\Livewire\Student\Permission as StudentPermission;
use App\Livewire\Student\Profile as StudentProfile;
use App\Livewire\Student\QrCodeView;
use App\Livewire\Student\Scanner as StudentScanner;
use App\Livewire\Teacher\ClassRecap as TeacherClassRecap;
use App\Livewire\Teacher\Dashboard as TeacherDashboard;
use App\Livewire\Teacher\PermissionApproval;
use App\Livewire\Teacher\Profile as TeacherProfile;
use App\Livewire\Teacher\Scanner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard based on role or login
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// Authentication
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout')->middleware('auth');

// 1. Student Portal (Mobile-First)
Route::prefix('siswa')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('student.dashboard');
    Route::get('/scan', StudentScanner::class)->name('student.scan');
    Route::get('/qr-code', QrCodeView::class)->name('student.qrcode');
    Route::get('/riwayat', StudentHistory::class)->name('student.history');
    Route::get('/izin', StudentPermission::class)->name('student.permission');
    Route::get('/profil', StudentProfile::class)->name('student.profile');
});

// 2. Teacher Portal (Mobile-First)
Route::prefix('guru')->middleware(['auth', 'role:teacher,admin'])->group(function () {
    Route::get('/dashboard', TeacherDashboard::class)->name('teacher.dashboard');
    Route::get('/scanner', Scanner::class)->name('teacher.scanner');
    Route::get('/share-qr', QrShare::class)->name('teacher.qr-share');
    Route::get('/izin-siswa', PermissionApproval::class)->name('teacher.permissions');
    Route::get('/rekap-kelas', TeacherClassRecap::class)->name('teacher.recap');
    Route::get('/profil', TeacherProfile::class)->name('teacher.profile');
});

// 3. Admin Portal (Mobile-First Shell)
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('/share-qr', QrShare::class)->name('admin.qr-share');
    Route::get('/siswa', Students::class)->name('admin.students');
    Route::get('/guru', Teachers::class)->name('admin.teachers');
    Route::get('/kelas', Classes::class)->name('admin.classes');
    Route::get('/tahun-ajaran', AcademicYears::class)->name('admin.academics');
    Route::get('/rekap-absensi', AttendanceRecap::class)->name('admin.attendance');
    Route::get('/pengaturan', Settings::class)->name('admin.settings');
});

// Export Routes (Accessible for Admin and Teacher)
Route::middleware(['auth', 'role:admin,teacher'])->group(function () {
    Route::get('/export/excel', [ReportExportController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [ReportExportController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/export/print-qr', [ReportExportController::class, 'printQrCards'])->name('export.print-qr');
    Route::get('/export/print-poster-qr', [ReportExportController::class, 'printPosterQr'])->name('export.print-poster-qr');
    Route::get('/export/students', [ReportExportController::class, 'exportStudents'])->name('export.students');
});
