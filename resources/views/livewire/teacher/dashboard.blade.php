<div class="space-y-6">

    <!-- 1. Hero Class Selector & Summary Card (Matching Reference UI Banner) -->
    <div class="soft-card p-5 relative overflow-hidden bg-white">
        <div class="flex items-start justify-between">
            <div class="space-y-2 z-10 max-w-[65%]">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    Kelas Yang Dipantau
                </span>
                
                @if($stats)
                    <h2 class="text-lg font-black text-slate-850 leading-snug">
                        {{ $stats['class']->name }}
                    </h2>
                    <p class="text-xs text-slate-500 font-medium">
                        Total Murid: <strong class="text-slate-800">{{ $stats['total_students'] }} Orang</strong> • Sudah Pulang: <strong class="text-blue-600 font-bold">{{ $stats['sudah_pulang'] }}</strong>
                    </p>
                @else
                    <h2 class="text-lg font-black text-slate-850 leading-snug">
                        Pilih Kelas
                    </h2>
                @endif

                <!-- Class Dropdown Selector -->
                <div class="pt-1">
                    <select wire:model.live="selectedClassId" 
                            class="w-full px-3 py-2 bg-[#F4F8FC] border border-sky-100 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500 cursor-pointer">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }} (Tingkat {{ $c->grade }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Visual Icon Right Badge -->
            <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-sky-50 to-blue-100/70 border border-sky-100 flex items-center justify-center relative shrink-0">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] text-white flex items-center justify-center shadow-lg shadow-sky-500/30">
                    <i data-lucide="graduation-cap" class="w-8 h-8 stroke-[2]"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Clean Topics Grid 2x2 for Teacher (Matching Reference UI) -->
    <div class="space-y-3">
        <h3 class="text-sm font-black text-slate-850 tracking-tight">Menu Utama Guru</h3>

        <div class="grid grid-cols-2 gap-3.5">
            <!-- Mulai Scan (Vibrant Rose Circle) -->
            <a href="{{ route('teacher.scanner') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-rose-500 to-pink-400 text-white flex items-center justify-center shadow-md shadow-rose-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="scan" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-850">Mulai Scan</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Masuk & Pulang</p>
            </a>

            <!-- Approval Izin (Vibrant Cyan Circle) -->
            <a href="{{ route('teacher.permissions') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group relative">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 to-teal-400 text-white flex items-center justify-center shadow-md shadow-cyan-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="clipboard-check" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-850">Approval Izin</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Review Surat</p>
            </a>

            <!-- Rekap Kelas (Vibrant Blue Circle) -->
            <a href="{{ route('teacher.recap') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white flex items-center justify-center shadow-md shadow-blue-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="bar-chart-3" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-850">Rekap Kelas</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Matriks & Log</p>
            </a>

            <!-- Profil / Akun (Vibrant Amber Circle) -->
            <a href="{{ route('teacher.profile') }}" class="soft-card-interactive p-4 flex flex-col items-center text-center group">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center shadow-md shadow-amber-500/25 mb-2.5 transition group-hover:scale-105">
                    <i data-lucide="user-check" class="w-7 h-7 stroke-[2.5]"></i>
                </div>
                <h4 class="text-xs font-bold text-slate-850">Akun Guru</h4>
                <p class="text-[10px] text-slate-400 mt-0.5">Profil & Kontak</p>
            </a>
        </div>

        <!-- 5th Action: Layar & Share QR Kelas/General -->
        <a href="{{ route('teacher.qr-share') }}" class="soft-card-interactive p-4 flex items-center gap-3 bg-gradient-to-r from-sky-50 to-blue-50/60 border border-sky-200">
            <div class="w-10 h-10 rounded-2xl bg-[#1E88E5] text-white flex items-center justify-center shrink-0 shadow-md shadow-sky-500/20">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
            </div>
            <div class="text-left flex-1">
                <h4 class="text-xs font-black text-slate-850 flex items-center gap-1.5">
                    <span>Tampilkan QR Presensi</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-[#1E88E5] text-white">Proyektor / Kelas</span>
                </h4>
                <p class="text-[10px] text-slate-500">Tampilkan QR di layar proyektor kelas atau bagikan QR sekolah</p>
            </div>
        </a>
    </div>

    <!-- 3. Class Attendance Stats Summary Grid -->
    @if($stats)
        <div class="soft-card p-5 space-y-3.5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Ringkasan Hari Ini</h3>
                    <h4 class="text-sm font-black text-slate-850">{{ $stats['class']->name }}</h4>
                </div>
                <div class="text-right">
                    <span class="text-xs font-extrabold text-[#1E88E5]">
                        {{ $stats['hadir'] + $stats['terlambat'] }} / {{ $stats['total_students'] }} Hadir
                    </span>
                    <p class="text-[10px] text-blue-600 font-bold">{{ $stats['sudah_pulang'] }} Pulang</p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-3 gap-2.5 text-center">
                <!-- Hadir -->
                <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-100">
                    <span class="text-[10px] text-emerald-700 font-bold block">Hadir</span>
                    <span class="text-xl font-black text-emerald-900 block mt-0.5">{{ $stats['hadir'] }}</span>
                </div>

                <!-- Terlambat -->
                <div class="p-3 rounded-2xl bg-amber-50 border border-amber-100">
                    <span class="text-[10px] text-amber-700 font-bold block">Terlambat</span>
                    <span class="text-xl font-black text-amber-900 block mt-0.5">{{ $stats['terlambat'] }}</span>
                </div>

                <!-- Belum Absen -->
                <div class="p-3 rounded-2xl bg-slate-100 border border-slate-200">
                    <span class="text-[10px] text-slate-600 font-bold block">Belum Absen</span>
                    <span class="text-xl font-black text-slate-850 block mt-0.5">{{ $stats['belum_absen'] }}</span>
                </div>

                <!-- Izin -->
                <div class="p-3 rounded-2xl bg-sky-50 border border-sky-100">
                    <span class="text-[10px] text-sky-700 font-bold block">Izin</span>
                    <span class="text-xl font-black text-sky-900 block mt-0.5">{{ $stats['izin'] }}</span>
                </div>

                <!-- Sakit -->
                <div class="p-3 rounded-2xl bg-purple-50 border border-purple-100">
                    <span class="text-[10px] text-purple-700 font-bold block">Sakit</span>
                    <span class="text-xl font-black text-purple-900 block mt-0.5">{{ $stats['sakit'] }}</span>
                </div>

                <!-- Alpa -->
                <div class="p-3 rounded-2xl bg-rose-50 border border-rose-100">
                    <span class="text-[10px] text-rose-700 font-bold block">Alpa</span>
                    <span class="text-xl font-black text-rose-900 block mt-0.5">{{ $stats['alpa'] }}</span>
                </div>
            </div>
        </div>

        <!-- 4. Chapter-List Style Student Presence List (Inspired by Reference UI) -->
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black text-slate-850 tracking-tight">Daftar Kehadiran Murid</h3>
                <span class="text-xs font-bold text-slate-400">{{ count($stats['students']) }} Murid</span>
            </div>

            <div class="space-y-2">
                @foreach($stats['students'] as $idx => $s)
                    @php $att = $s->attendances->first(); @endphp
                    <div class="soft-card p-3.5 flex items-center justify-between transition">
                        <div class="flex items-center gap-3">
                            <!-- Numbered Pill Badge -->
                            <div class="w-9 h-9 rounded-2xl bg-sky-50 text-[#1E88E5] font-black text-xs flex items-center justify-center border border-sky-100 shrink-0">
                                {{ $idx + 1 }}
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-slate-850">{{ $s->name }}</h4>
                                <p class="text-[10px] text-slate-500 font-mono">
                                    @if($att)
                                        In: <strong class="text-slate-850">{{ $att->check_in ? substr($att->check_in, 0, 5) : '-' }}</strong>
                                        @if($att->check_out)
                                            • Out: <strong class="text-blue-700 font-bold">{{ substr($att->check_out, 0, 5) }}</strong>
                                        @endif
                                    @else
                                        NISN: {{ $s->nisn }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div>
                            @if($att)
                                @if($att->check_out)
                                    <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                        Pulang
                                    </span>
                                @else
                                    <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border {{ $att->status_badge }}">
                                        {{ $att->status_label }}
                                    </span>
                                @endif
                            @else
                                <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200">
                                    Belum Absen
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
