<?php

namespace App\Console\Commands;

use App\Services\AttendanceService;
use Illuminate\Console\Command;

class ProcessAutoAlpa extends Command
{
    protected $signature = 'attendance:auto-alpa {date? : Tanggal absensi format YYYY-MM-DD}';
    protected $description = 'Otomatis menetapkan status ALPA bagi siswa yang belum hadir pada saat absensi ditutup';

    public function handle(AttendanceService $attendanceService): int
    {
        $date = $this->argument('date');
        $this->info("Menjalankan proses auto ALPA untuk tanggal: " . ($date ?: date('Y-m-d')) . "...");

        $count = $attendanceService->runAutoAlpa($date);

        $this->info("Selesai. Sebanyak {$count} siswa berhasil ditandai ALPA.");

        return Command::SUCCESS;
    }
}
