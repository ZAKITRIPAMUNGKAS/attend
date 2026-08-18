<div class="space-y-5">

    <!-- Flash Notification -->
    @if($actionMessage)
        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-850 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ $actionMessage }}</span>
            </div>
            <button wire:click="$set('actionMessage', '')" class="text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- 1. Executive Top Hero Banner Card (Matching Reference UI) -->
    <div class="soft-card p-5 bg-white relative overflow-hidden">
        <div class="flex items-start justify-between">
            <div class="space-y-1.5 z-10 max-w-[65%]">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    Executive Overview
                </span>
                
                <h2 class="text-base font-black text-slate-850 leading-snug pt-1">
                    SmartAbsensi Overview
                </h2>
                <p class="text-xs text-slate-500 font-medium">
                    Tahun: <strong class="text-slate-800">{{ $activeYear->name ?? '2026/2027' }}</strong> • On-Time: <strong class="font-mono text-[#1E88E5]">{{ substr($settings->on_time_until, 0, 5) }}</strong>
                </p>

                <div class="pt-2">
                    <button type="button" 
                            wire:click="triggerAutoAlpa"
                            wire:confirm="Jalankan proses Auto-Alpa sekarang? Semua murid yang belum hadir hari ini akan ditandai Alpa."
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white text-[11px] font-bold rounded-2xl shadow-md shadow-sky-500/20 active:scale-95 transition cursor-pointer">
                        <i data-lucide="clock-alert" class="w-3.5 h-3.5"></i>
                        <span>Tutup Absensi (Auto-Alpa)</span>
                    </button>
                </div>
            </div>

            <!-- Right Icon Badge -->
            <div class="w-18 h-18 rounded-2xl bg-gradient-to-tr from-sky-50 to-blue-100/70 border border-sky-100 flex items-center justify-center relative shrink-0">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] text-white flex items-center justify-center shadow-lg shadow-sky-500/30">
                    <i data-lucide="shield-check" class="w-6 h-6 stroke-[2.5]"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Master Data Metric Cards Grid (2x2 Topics Grid) -->
    <div class="space-y-3">
        <h3 class="text-sm font-black text-slate-850 tracking-tight">Master Data Sekolah</h3>

        <div class="grid grid-cols-2 gap-3.5">
            <!-- 1. Murid (Rose Circle) -->
            <a href="{{ route('admin.students') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-rose-500 to-pink-400 text-white flex items-center justify-center shadow-md shadow-rose-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="users" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-850">Data Murid</h4>
                <p class="text-[10px] text-rose-500 font-extrabold mt-0.5">{{ $totalStudents }} Murid</p>
            </a>

            <!-- 2. Guru (Cyan Circle) -->
            <a href="{{ route('admin.teachers') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 to-teal-400 text-white flex items-center justify-center shadow-md shadow-cyan-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="graduation-cap" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-850">Data Guru</h4>
                <p class="text-[10px] text-cyan-600 font-extrabold mt-0.5">{{ $totalTeachers }} Guru</p>
            </a>

            <!-- 3. Kelas (Blue Circle) -->
            <a href="{{ route('admin.classes') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white flex items-center justify-center shadow-md shadow-blue-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="school" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-850">Data Kelas</h4>
                <p class="text-[10px] text-blue-600 font-extrabold mt-0.5">{{ $totalClasses }} Kelas</p>
            </a>

            <!-- 4. Tahun Ajaran (Amber Circle) -->
            <a href="{{ route('admin.academics') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center shadow-md shadow-amber-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="calendar" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-850">Tahun Ajaran</h4>
                <p class="text-[10px] text-amber-600 font-extrabold mt-0.5">{{ $activeYear->name ?? 'Atur' }}</p>
            </a>
        </div>
    </div>

    <!-- 3. Realtime Attendance Today Summary Card -->
    <div class="soft-card p-5 space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Kehadiran Hari Ini</h3>
                <h4 class="text-sm font-black text-slate-850">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</h4>
            </div>
            <div class="text-right">
                <span class="text-lg font-black text-[#1E88E5]">{{ $stats['percentage'] }}%</span>
                <p class="text-[9px] font-bold text-slate-400 uppercase">Tingkat Hadir</p>
            </div>
        </div>

        <!-- Grid 3x2 Status Kehadiran -->
        <div class="grid grid-cols-3 gap-2.5 text-center">
            <!-- Hadir -->
            <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-100">
                <span class="text-[10px] text-emerald-700 font-bold block">Hadir</span>
                <span class="text-lg font-black text-emerald-900 block mt-0.5">{{ $stats['hadir'] }}</span>
            </div>

            <!-- Telat -->
            <div class="p-3 rounded-2xl bg-amber-50 border border-amber-100">
                <span class="text-[10px] text-amber-700 font-bold block">Telat</span>
                <span class="text-lg font-black text-amber-900 block mt-0.5">{{ $stats['terlambat'] }}</span>
            </div>

            <!-- Belum Absen -->
            <div class="p-3 rounded-2xl bg-slate-100 border border-slate-200">
                <span class="text-[10px] text-slate-600 font-bold block">Belum</span>
                <span class="text-lg font-black text-slate-850 block mt-0.5">{{ $stats['belum_absen'] }}</span>
            </div>

            <!-- Izin -->
            <div class="p-3 rounded-2xl bg-sky-50 border border-sky-100">
                <span class="text-[10px] text-sky-700 font-bold block">Izin</span>
                <span class="text-lg font-black text-sky-900 block mt-0.5">{{ $stats['izin'] }}</span>
            </div>

            <!-- Sakit -->
            <div class="p-3 rounded-2xl bg-purple-50 border border-purple-100">
                <span class="text-[10px] text-purple-700 font-bold block">Sakit</span>
                <span class="text-lg font-black text-purple-900 block mt-0.5">{{ $stats['sakit'] }}</span>
            </div>

            <!-- Alpa -->
            <div class="p-3 rounded-2xl bg-rose-50 border border-rose-100">
                <span class="text-[10px] text-rose-700 font-bold block">Alpa</span>
                <span class="text-lg font-black text-rose-900 block mt-0.5">{{ $stats['alpa'] }}</span>
            </div>
        </div>
    </div>

    <!-- 4. Quick Action Menu Grid -->
    <div class="space-y-3">
        <h3 class="text-sm font-black text-slate-850">Aksi Cepat Presensi</h3>

        <div class="grid grid-cols-2 gap-3">
            <!-- Share & Display QR -->
            <a href="{{ route('admin.qr-share') }}" class="soft-card-interactive p-4 flex items-center gap-3 bg-gradient-to-r from-sky-50 to-blue-50/60 border border-sky-200 col-span-2">
                <div class="w-10 h-10 rounded-2xl bg-[#1E88E5] text-white flex items-center justify-center shrink-0 shadow-md shadow-sky-500/20">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
                </div>
                <div class="text-left flex-1">
                    <h4 class="text-xs font-black text-slate-850 flex items-center gap-1.5">
                        <span>Layar & Bagikan QR</span>
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#1E88E5] text-white">Proyektor / Kelas</span>
                    </h4>
                    <p class="text-[10px] text-slate-500">Tampilkan QR General Sekolah atau QR Khusus Kelas</p>
                </div>
            </a>

            <a href="{{ route('admin.attendance') }}" class="soft-card-interactive p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></svg>
                </div>
                <div class="text-left">
                    <h4 class="text-xs font-bold text-slate-850">Rekap & Ekspor</h4>
                    <p class="text-[10px] text-slate-400">Excel & PDF</p>
                </div>
            </a>

            <a href="{{ route('admin.settings') }}" class="soft-card-interactive p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/></svg>
                </div>
                <div class="text-left">
                    <h4 class="text-xs font-bold text-slate-850">Jam Absensi</h4>
                    <p class="text-[10px] text-slate-400">Atur Batas</p>
                </div>
            </a>
        </div>
    </div>

</div>
