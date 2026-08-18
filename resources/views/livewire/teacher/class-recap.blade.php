<div class="space-y-5">

    <!-- Filters Soft Card -->
    <div class="soft-card p-5 space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    Laporan Presensi
                </span>
                <h2 class="text-sm font-black text-slate-850 tracking-tight mt-1">Rekap Presensi Siswa</h2>
            </div>

            <!-- View Mode Switcher (Harian / Mingguan) -->
            <div class="flex bg-[#F4F8FC] p-1 rounded-2xl border border-sky-100 text-xs">
                <button type="button" 
                        wire:click="$set('viewMode', 'weekly')"
                        class="px-3 py-1 rounded-xl font-bold transition cursor-pointer {{ $viewMode === 'weekly' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500' }}">
                    Mingguan
                </button>
                <button type="button" 
                        wire:click="$set('viewMode', 'daily')"
                        class="px-3 py-1 rounded-xl font-bold transition cursor-pointer {{ $viewMode === 'daily' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500' }}">
                    Harian
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Pilih Kelas</label>
                <select wire:model.live="selectedClassId" 
                        class="w-full px-3.5 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 cursor-pointer">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} (Tingkat {{ $c->grade }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                @if($viewMode === 'weekly')
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase">Minggu</label>
                        <div class="flex items-center gap-1">
                            <button type="button" wire:click="prevWeek" class="text-xs px-2 py-0.5 bg-slate-100 rounded-lg hover:bg-slate-200 font-bold">&larr; Prev</button>
                            <button type="button" wire:click="nextWeek" class="text-xs px-2 py-0.5 bg-slate-100 rounded-lg hover:bg-slate-200 font-bold">Next &rarr;</button>
                        </div>
                    </div>
                    <input type="date" 
                           wire:model.live="selectedWeek" 
                           class="w-full px-3.5 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                @else
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Pilih Tanggal</label>
                    <input type="date" 
                           wire:model.live="selectedDate" 
                           class="w-full px-3.5 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                @endif
            </div>
        </div>
    </div>

    <!-- Weekly Matrix Table View -->
    @if($viewMode === 'weekly')
        <div class="soft-card overflow-hidden">
            <div class="p-4 bg-slate-50/70 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="table-2" class="w-4 h-4 text-[#1E88E5]"></i>
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-850">
                        Matriks Senin – Jumat ({{ count($students) }} Siswa)
                    </h3>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-sky-50/50 text-[10px] uppercase font-black text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="p-3 w-8 text-center">No</th>
                            <th class="p-3">Nama Siswa</th>
                            @foreach($weekDays as $d)
                                <th class="p-3 text-center">
                                    <div>{{ $d['day_name'] }}</div>
                                    <div class="text-[9px] text-slate-400 font-mono">{{ $d['day_num'] }}</div>
                                </th>
                            @endforeach
                            <th class="p-3 text-center">H</th>
                            <th class="p-3 text-center">T</th>
                            <th class="p-3 text-center">I</th>
                            <th class="p-3 text-center">S</th>
                            <th class="p-3 text-center">A</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($matrix as $idx => $row)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-3 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="p-3 font-bold text-slate-850 whitespace-nowrap">{{ $row['student']->name }}</td>
                                @foreach($row['days'] as $dayInfo)
                                    <td class="p-3 text-center">
                                        @if($dayInfo['status'] === 'hadir')
                                            <span class="inline-block w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 font-black leading-6 text-[10px]">H</span>
                                        @elseif($dayInfo['status'] === 'terlambat')
                                            <span class="inline-block w-6 h-6 rounded-lg bg-amber-100 text-amber-800 font-black leading-6 text-[10px]" title="Telat {{ $dayInfo['late_minutes'] }}m">T</span>
                                        @elseif($dayInfo['status'] === 'izin')
                                            <span class="inline-block w-6 h-6 rounded-lg bg-sky-100 text-sky-800 font-black leading-6 text-[10px]">I</span>
                                        @elseif($dayInfo['status'] === 'sakit')
                                            <span class="inline-block w-6 h-6 rounded-lg bg-purple-100 text-purple-800 font-black leading-6 text-[10px]">S</span>
                                        @elseif($dayInfo['status'] === 'alpa')
                                            <span class="inline-block w-6 h-6 rounded-lg bg-rose-100 text-rose-800 font-black leading-6 text-[10px]">A</span>
                                        @else
                                            <span class="text-slate-300 font-mono">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="p-3 text-center font-bold text-emerald-700 bg-emerald-50/40">{{ $row['totals']['hadir'] }}</td>
                                <td class="p-3 text-center font-bold text-amber-700 bg-amber-50/40">{{ $row['totals']['terlambat'] }}</td>
                                <td class="p-3 text-center font-bold text-sky-700">{{ $row['totals']['izin'] }}</td>
                                <td class="p-3 text-center font-bold text-purple-700">{{ $row['totals']['sakit'] }}</td>
                                <td class="p-3 text-center font-bold text-rose-700 bg-rose-50/40">{{ $row['totals']['alpa'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-slate-400 text-xs">Belum ada data siswa di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Daily List View -->
        <div class="soft-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-sky-50/50 text-[10px] uppercase font-black text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-12 text-center">No</th>
                            <th class="p-3.5">Nama Siswa</th>
                            <th class="p-3.5">NISN</th>
                            <th class="p-3.5">Jam Masuk</th>
                            <th class="p-3.5">Keterlambatan</th>
                            <th class="p-3.5 text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $idx => $s)
                            @php $att = $dailyAttendances->get($s->id); @endphp
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-3.5 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="p-3.5 font-bold text-slate-850 whitespace-nowrap">{{ $s->name }}</td>
                                <td class="p-3.5 font-mono text-slate-600">{{ $s->nisn }}</td>
                                <td class="p-3.5 font-mono text-slate-800 font-bold">
                                    {{ $att && $att->check_in ? substr($att->check_in, 0, 8) . ' WIB' : '-' }}
                                </td>
                                <td class="p-3.5 font-bold text-slate-700">
                                    {{ $att && $att->late_minutes > 0 ? "+{$att->late_minutes} Menit" : '-' }}
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    @if($att)
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $att->status_badge }}">
                                            {{ $att->status_label }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold text-slate-400 bg-slate-100">
                                            Belum Absen
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400 text-xs">Belum ada siswa di kelas ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
