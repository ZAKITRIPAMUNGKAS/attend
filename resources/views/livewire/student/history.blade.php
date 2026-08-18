<div class="space-y-5">

    <!-- 1. Calendar Header & Month Navigation (Clean Soft Card) -->
    <div class="soft-card p-5 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    Kalender Kehadiran
                </span>
                <h2 class="text-base font-black text-slate-850 tracking-tight mt-1 capitalize">
                    {{ $monthTitle }}
                </h2>
            </div>

            <!-- Month Stepper Buttons -->
            <div class="flex items-center gap-1.5 bg-[#F4F8FC] p-1 rounded-2xl border border-sky-100">
                <button type="button" 
                        wire:click="prevMonth" 
                        title="Bulan Sebelumnya"
                        class="w-8 h-8 rounded-xl bg-white hover:bg-sky-50 text-slate-700 flex items-center justify-center shadow-xs transition active:scale-90 cursor-pointer">
                    <i data-lucide="chevron-left" class="w-4 h-4 text-slate-600"></i>
                </button>
                <button type="button" 
                        wire:click="nextMonth" 
                        title="Bulan Berikutnya"
                        class="w-8 h-8 rounded-xl bg-white hover:bg-sky-50 text-slate-700 flex items-center justify-center shadow-xs transition active:scale-90 cursor-pointer">
                    <i data-lucide="chevron-right" class="w-4 h-4 text-slate-600"></i>
                </button>
            </div>
        </div>

        <!-- Days of Week Bar (Senin - Ahad) -->
        <div class="grid grid-cols-7 gap-1 text-center border-b border-slate-100 pb-2">
            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Ahd'] as $dName)
                <span class="text-[11px] font-extrabold {{ in_array($dName, ['Sab', 'Ahd']) ? 'text-rose-400' : 'text-slate-400' }}">
                    {{ $dName }}
                </span>
            @endforeach
        </div>

        <!-- Interactive Calendar Days Grid -->
        <div class="grid grid-cols-7 gap-1.5 text-center">
            @foreach($calendarDays as $cell)
                @if($cell['day'] === null)
                    <!-- Empty Placeholder Cell before 1st day -->
                    <div class="h-11 rounded-2xl"></div>
                @else
                    @php
                        $st = $cell['status'];
                        $isSel = $cell['is_selected'];
                        $isToday = $cell['is_today'];
                        $isWknd = $cell['is_weekend'];
                    @endphp
                    <button type="button" 
                            wire:click="selectDate('{{ $cell['date'] }}')" 
                            class="h-11 rounded-2xl flex flex-col items-center justify-center relative transition-all duration-200 cursor-pointer 
                            {{ $isSel ? 'ring-2 ring-[#1E88E5] ring-offset-2 bg-sky-50 scale-105 shadow-sm' : 'hover:bg-slate-50' }}
                            {{ $isToday && !$isSel ? 'border border-[#1E88E5]/50 bg-sky-50/40 font-black' : '' }}
                            {{ $isWknd && !$st ? 'text-slate-300' : 'text-slate-750' }}">
                        
                        <span class="text-xs font-bold leading-none {{ $isToday ? 'text-[#1E88E5]' : '' }}">
                            {{ $cell['day'] }}
                        </span>

                        <!-- Indicator Pill / Dot below Date -->
                        @if($st === 'hadir')
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mt-1"></span>
                        @elseif($st === 'terlambat')
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mt-1"></span>
                        @elseif($st === 'izin')
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500 mt-1"></span>
                        @elseif($st === 'sakit')
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500 mt-1"></span>
                        @elseif($st === 'alpa')
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mt-1"></span>
                        @else
                            <span class="w-1.5 h-1.5 mt-1"></span>
                        @endif
                    </button>
                @endif
            @endforeach
        </div>

        <!-- Legend / Keterangan Warna -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-around text-[10px] font-bold text-slate-500 flex-wrap gap-2">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hadir
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span> Telat
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-sky-500"></span> Izin
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-purple-500"></span> Sakit
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span> Alpa
            </span>
        </div>
    </div>

    <!-- 2. Selected Date Detail Card (Instant Pop-up Info with Jam Masuk & Jam Pulang) -->
    <div class="soft-card p-5 space-y-3">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Detail Tanggal Terpilih</span>
                <h3 class="text-sm font-black text-slate-850">
                    {{ $selectedCarbon->translatedFormat('l, d F Y') }}
                </h3>
            </div>
            @if($selectedCarbon->isToday())
                <span class="text-[10px] bg-sky-100 text-[#1E88E5] font-extrabold px-2.5 py-0.5 rounded-full">
                    Hari Ini
                </span>
            @endif
        </div>

        @if($selectedAttendance)
            <div class="p-4 rounded-2xl flex items-center gap-4 {{ $selectedAttendance->status_badge }}">
                <div class="w-11 h-11 rounded-xl bg-white/80 backdrop-blur-sm flex items-center justify-center shrink-0 shadow-xs">
                    @if($selectedAttendance->status === 'hadir')
                        <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                    @elseif($selectedAttendance->status === 'terlambat')
                        <i data-lucide="clock-alert" class="w-6 h-6 text-amber-600"></i>
                    @elseif($selectedAttendance->status === 'izin')
                        <i data-lucide="file-check" class="w-6 h-6 text-blue-600"></i>
                    @elseif($selectedAttendance->status === 'sakit')
                        <i data-lucide="heart-pulse" class="w-6 h-6 text-purple-600"></i>
                    @else
                        <i data-lucide="x-circle" class="w-6 h-6 text-rose-600"></i>
                    @endif
                </div>

                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-black uppercase">{{ $selectedAttendance->status }}</span>
                        @if($selectedAttendance->late_minutes > 0)
                            <span class="text-[10px] bg-amber-200 text-amber-950 font-bold px-2 py-0.5 rounded-full">
                                Telat {{ $selectedAttendance->late_minutes }} Menit
                            </span>
                        @endif
                    </div>

                    <div class="text-xs font-semibold opacity-95 space-y-0.5">
                        <div>
                            Masuk: <strong class="font-mono">{{ $selectedAttendance->check_in ? substr($selectedAttendance->check_in, 0, 5) . ' WIB' : '-' }}</strong>
                        </div>
                        <div>
                            Pulang: 
                            @if($selectedAttendance->check_out)
                                <strong class="font-mono">{{ substr($selectedAttendance->check_out, 0, 5) }} WIB</strong>
                            @else
                                <span class="opacity-75 italic text-[11px]">Belum scan pulang</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="p-4 rounded-2xl bg-[#F4F8FC] border border-sky-100 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-sky-100 text-[#1E88E5] flex items-center justify-center shrink-0 font-bold">
                    <i data-lucide="info" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-700">
                        {{ $selectedCarbon->isWeekend() ? 'Hari Libur Akhir Pekan' : 'Tidak Ada Catatan Kehadiran' }}
                    </h4>
                    <p class="text-[11px] text-slate-400 mt-0.5">
                        {{ $selectedCarbon->isWeekend() ? 'Tidak ada kegiatan belajar mengajar pada hari ini.' : 'Murid belum / tidak memiliki log absensi pada tanggal ini.' }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- 3. Monthly Metrics Summary Pills -->
    <div class="soft-card p-5 space-y-3">
        <div class="flex items-center justify-between">
            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Total Akumulasi Bulan Ini</h4>
            <span class="text-sm font-black text-[#1E88E5]">{{ $summary['percentage'] }}% Hadir</span>
        </div>

        <div class="grid grid-cols-5 gap-2 text-center">
            <div class="p-2 rounded-2xl bg-emerald-50 border border-emerald-100">
                <span class="text-[10px] font-bold text-emerald-700 block">Hadir</span>
                <span class="text-sm font-black text-emerald-900">{{ $summary['hadir'] }}</span>
            </div>
            <div class="p-2 rounded-2xl bg-amber-50 border border-amber-100">
                <span class="text-[10px] font-bold text-amber-700 block">Telat</span>
                <span class="text-sm font-black text-amber-900">{{ $summary['terlambat'] }}</span>
            </div>
            <div class="p-2 rounded-2xl bg-sky-50 border border-sky-100">
                <span class="text-[10px] font-bold text-sky-700 block">Izin</span>
                <span class="text-sm font-black text-sky-900">{{ $summary['izin'] }}</span>
            </div>
            <div class="p-2 rounded-2xl bg-purple-50 border border-purple-100">
                <span class="text-[10px] font-bold text-purple-700 block">Sakit</span>
                <span class="text-sm font-black text-purple-900">{{ $summary['sakit'] }}</span>
            </div>
            <div class="p-2 rounded-2xl bg-rose-50 border border-rose-100">
                <span class="text-[10px] font-bold text-rose-700 block">Alpa</span>
                <span class="text-sm font-black text-rose-900">{{ $summary['alpa'] }}</span>
            </div>
        </div>
    </div>

</div>
