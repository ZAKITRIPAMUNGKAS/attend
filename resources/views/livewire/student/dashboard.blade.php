<div class="space-y-6">

    <!-- 1. Hero Banner Card (Inspired by 'Especially For You' Card in Reference UI) -->
    <div class="soft-card p-5 relative overflow-hidden bg-white">
        <div class="flex items-start justify-between">
            <div class="space-y-1.5 z-10 max-w-[65%]">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    Status Kehadiran Hari Ini
                </span>
                
                @if($todayAttendance)
                    @if($todayAttendance->status === 'hadir')
                        <h2 class="text-lg font-black text-slate-850 leading-snug pt-1">
                            Hadir Tepat Waktu
                        </h2>
                    @elseif($todayAttendance->status === 'terlambat')
                        <h2 class="text-lg font-black text-amber-700 leading-snug pt-1">
                            Terlambat (+{{ $todayAttendance->late_minutes }}m)
                        </h2>
                    @elseif($todayAttendance->status === 'izin')
                        <h2 class="text-lg font-black text-blue-700 leading-snug pt-1">
                            Izin Disetujui
                        </h2>
                    @elseif($todayAttendance->status === 'sakit')
                        <h2 class="text-lg font-black text-purple-700 leading-snug pt-1">
                            Sakit
                        </h2>
                    @else
                        <h2 class="text-lg font-black text-rose-700 leading-snug pt-1">
                            Alpa / Tidak Hadir
                        </h2>
                    @endif

                    <!-- Jam Masuk & Jam Pulang Badges -->
                    <div class="pt-1.5 space-y-1 text-xs">
                        <div class="flex items-center gap-1.5 text-slate-600">
                            <i data-lucide="sun" class="w-3.5 h-3.5 text-amber-500"></i>
                            <span>Masuk: <strong class="font-mono text-emerald-700">{{ $todayAttendance->check_in ? substr($todayAttendance->check_in, 0, 5) . ' WIB' : '-' }}</strong></span>
                        </div>
                        
                        <div class="flex items-center gap-1.5 text-slate-600">
                            <i data-lucide="moon" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span>Pulang: 
                                @if($todayAttendance->check_out)
                                    <strong class="font-mono text-blue-700 font-bold">{{ substr($todayAttendance->check_out, 0, 5) }} WIB</strong>
                                @else
                                    <span class="text-slate-400 font-medium italic">Belum scan pulang</span>
                                @endif
                            </span>
                        </div>
                    </div>

                @elseif($todayPermission)
                    <h2 class="text-lg font-black text-amber-700 leading-snug pt-1">
                        Menunggu Izin
                    </h2>
                    <p class="text-xs text-slate-500 font-medium">Sedang ditinjau oleh wali kelas</p>
                @else
                    <h2 class="text-lg font-black text-slate-850 leading-snug pt-1">
                        Belum Melakukan Absen
                    </h2>
                    <p class="text-xs text-slate-500 font-medium">
                        Batas on-time: <strong class="text-slate-800">{{ substr($settings->on_time_until, 0, 5) }}</strong>
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('student.qrcode') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white text-xs font-bold rounded-2xl shadow-md shadow-sky-500/20 active:scale-95 transition">
                            <span>Tampilkan QR</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Visual Icon Badge on Right -->
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-sky-50 to-blue-100/70 border border-sky-100 flex items-center justify-center relative shrink-0">
                @if($todayAttendance)
                    @if($todayAttendance->check_out)
                        <div class="w-14 h-14 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i data-lucide="log-out" class="w-8 h-8 stroke-[2.5]"></i>
                        </div>
                    @elseif($todayAttendance->status === 'hadir')
                        <div class="w-14 h-14 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <i data-lucide="check-circle" class="w-8 h-8 stroke-[2.5]"></i>
                        </div>
                    @elseif($todayAttendance->status === 'terlambat')
                        <div class="w-14 h-14 rounded-2xl bg-amber-500 text-white flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <i data-lucide="clock-alert" class="w-8 h-8 stroke-[2.5]"></i>
                        </div>
                    @else
                        <div class="w-14 h-14 rounded-2xl bg-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i data-lucide="file-check" class="w-8 h-8 stroke-[2.5]"></i>
                        </div>
                    @endif
                @else
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] text-white flex items-center justify-center shadow-lg shadow-sky-500/30 animate-pulse">
                        <i data-lucide="qr-code" class="w-8 h-8 stroke-[2]"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. Clean Topics Grid (Inspired by 'Topics' 4-Card in Reference UI) -->
    <div class="space-y-3">
        <h3 class="text-sm font-black text-slate-850 tracking-tight">Menu Utama</h3>

        <div class="grid grid-cols-2 gap-3.5">
            <!-- 1. QR Code (Vibrant Rose Circle) -->
            <a href="{{ route('student.qrcode') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-rose-500 to-pink-400 text-white flex items-center justify-center shadow-md shadow-rose-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="qr-code" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-800">Kartu QR</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Identitas Absen</p>
            </a>

            <!-- 2. Pengajuan Izin (Vibrant Cyan Circle) -->
            <a href="{{ route('student.permission') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 to-teal-400 text-white flex items-center justify-center shadow-md shadow-cyan-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="file-plus-2" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-800">Ajukan Izin</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Sakit & Acara</p>
            </a>

            <!-- 3. Riwayat Kehadiran (Vibrant Blue Circle) -->
            <a href="{{ route('student.history') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white flex items-center justify-center shadow-md shadow-blue-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="calendar-check-2" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-800">Riwayat</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Log Masuk & Pulang</p>
            </a>

            <!-- 4. Profil Murid (Vibrant Amber/Orange Circle) -->
            <a href="{{ route('student.profile') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center shadow-md shadow-amber-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="user-check" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-800">Akun Murid</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Biodata & Pass</p>
            </a>
        </div>
    </div>

    <!-- 3. Clean Monthly Stats Card -->
    <div class="soft-card p-5 space-y-3.5">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Ringkasan Bulan Ini</h3>
                <h4 class="text-sm font-black text-slate-850">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</h4>
            </div>
            <div class="text-right">
                <span class="text-lg font-black text-[#1E88E5]">{{ $stats['percentage'] }}%</span>
                <p class="text-[9px] font-bold text-slate-400 uppercase">Tingkat Kehadiran</p>
            </div>
        </div>

        <div class="grid grid-cols-5 gap-2 text-center pt-1">
            <!-- Hadir -->
            <div class="p-2.5 rounded-2xl bg-emerald-50/90 border border-emerald-100">
                <span class="text-[10px] font-bold text-emerald-700 block">Hadir</span>
                <span class="text-base font-black text-emerald-900 block mt-0.5">{{ $stats['hadir'] }}</span>
            </div>

            <!-- Telat -->
            <div class="p-2.5 rounded-2xl bg-amber-50/90 border border-amber-100">
                <span class="text-[10px] font-bold text-amber-700 block">Telat</span>
                <span class="text-base font-black text-amber-900 block mt-0.5">{{ $stats['terlambat'] }}</span>
            </div>

            <!-- Izin -->
            <div class="p-2.5 rounded-2xl bg-sky-50/90 border border-sky-100">
                <span class="text-[10px] font-bold text-sky-700 block">Izin</span>
                <span class="text-base font-black text-sky-900 block mt-0.5">{{ $stats['izin'] }}</span>
            </div>

            <!-- Sakit -->
            <div class="p-2.5 rounded-2xl bg-purple-50/90 border border-purple-100">
                <span class="text-[10px] font-bold text-purple-700 block">Sakit</span>
                <span class="text-base font-black text-purple-900 block mt-0.5">{{ $stats['sakit'] }}</span>
            </div>

            <!-- Alpa -->
            <div class="p-2.5 rounded-2xl bg-rose-50/90 border border-rose-100">
                <span class="text-[10px] font-bold text-rose-700 block">Alpa</span>
                <span class="text-base font-black text-rose-900 block mt-0.5">{{ $stats['alpa'] }}</span>
            </div>
        </div>
    </div>

</div>
