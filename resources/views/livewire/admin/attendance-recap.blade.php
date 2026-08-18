<div class="space-y-6">

    @if($successMessage)
        <div class="p-4 rounded-3xl bg-emerald-50 border border-emerald-200 text-emerald-850 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
                <span>{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')" class="text-emerald-700 font-bold hover:text-black">&times;</button>
        </div>
    @endif

    <!-- Top Filter Soft Card -->
    <div class="soft-card p-5 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    Laporan Presensi
                </span>
                <h2 class="text-lg font-black text-slate-850 tracking-tight mt-1">Rekapitulasi Kehadiran</h2>
                <p class="text-xs text-slate-400">Pilih mode laporan dan unduh berkas presensi.</p>
            </div>

            <!-- Export Action Buttons -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('export.excel', ['type' => $viewMode, 'class_id' => $selectedClassId, 'date' => $selectedDate, 'month' => $selectedMonth, 'start_date' => $selectedWeek]) }}" 
                   target="_blank" 
                   class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-2xl shadow-md shadow-emerald-600/20 flex items-center gap-1.5 transition active:scale-95">
                    <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                    <span>Excel</span>
                </a>

                <a href="{{ route('export.pdf', ['type' => $viewMode, 'class_id' => $selectedClassId, 'date' => $selectedDate, 'month' => $selectedMonth, 'start_date' => $selectedWeek]) }}" 
                   target="_blank" 
                   class="px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-2xl shadow-md shadow-rose-600/20 flex items-center gap-1.5 transition active:scale-95">
                    <i data-lucide="file-text" class="w-4 h-4"></i>
                    <span>PDF</span>
                </a>
            </div>
        </div>

        <!-- Period Mode Switcher -->
        <div class="flex bg-[#F4F8FC] p-1 rounded-2xl border border-sky-100 text-xs font-bold">
            <button type="button" 
                    wire:click="$set('viewMode', 'daily')" 
                    class="flex-1 py-2 rounded-xl transition cursor-pointer {{ $viewMode === 'daily' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500' }}">
                Harian
            </button>
            <button type="button" 
                    wire:click="$set('viewMode', 'weekly')" 
                    class="flex-1 py-2 rounded-xl transition cursor-pointer {{ $viewMode === 'weekly' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500' }}">
                Mingguan
            </button>
            <button type="button" 
                    wire:click="$set('viewMode', 'monthly')" 
                    class="flex-1 py-2 rounded-xl transition cursor-pointer {{ $viewMode === 'monthly' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500' }}">
                Bulanan
            </button>
        </div>

        <!-- Dynamic Filter Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
            <div>
                <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Filter Rombel Kelas</label>
                <select wire:model.live="selectedClassId" class="w-full px-3.5 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} (Tingkat {{ $c->grade }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                @if($viewMode === 'daily')
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Pilih Tanggal</label>
                    <input type="date" wire:model.live="selectedDate" class="w-full px-3.5 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                @elseif($viewMode === 'weekly')
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-[11px] font-bold text-slate-400 uppercase">Minggu</label>
                        <div class="flex items-center gap-1">
                            <button type="button" wire:click="prevWeek" class="text-[10px] px-2 py-0.5 bg-slate-100 rounded-lg hover:bg-slate-200 font-bold">&larr; Prev</button>
                            <button type="button" wire:click="nextWeek" class="text-[10px] px-2 py-0.5 bg-slate-100 rounded-lg hover:bg-slate-200 font-bold">Next &rarr;</button>
                        </div>
                    </div>
                    <input type="date" wire:model.live="selectedWeek" class="w-full px-3.5 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                @else
                    <label class="block text-[11px] font-bold text-slate-400 uppercase mb-1">Pilih Bulan</label>
                    <input type="month" wire:model.live="selectedMonth" class="w-full px-3.5 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                @endif
            </div>
        </div>
    </div>

    <!-- Data Display Section -->
    @if($viewMode === 'daily')
        <div class="soft-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-sky-50/50 text-[10px] uppercase font-black text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center">No</th>
                            <th class="p-3.5">Nama Murid</th>
                            <th class="p-3.5">Kelas</th>
                            <th class="p-3.5">Masuk</th>
                            <th class="p-3.5">Pulang</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($students as $idx => $s)
                            @php $att = $dailyAttendances->get($s->id); @endphp
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-3.5 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="p-3.5 font-bold text-slate-850 whitespace-nowrap">{{ $s->name }}</td>
                                <td class="p-3.5 whitespace-nowrap text-slate-700">{{ $s->schoolClass->name ?? '-' }}</td>
                                <td class="p-3.5 font-mono text-emerald-800 font-bold whitespace-nowrap">
                                    {{ $att && $att->check_in ? substr($att->check_in, 0, 5) : '-' }}
                                </td>
                                <td class="p-3.5 font-mono text-blue-800 font-bold whitespace-nowrap">
                                    {{ $att && $att->check_out ? substr($att->check_out, 0, 5) : '-' }}
                                </td>
                                <td class="p-3.5 whitespace-nowrap">
                                    @if($att)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase border {{ $att->status_badge }}">
                                            {{ $att->status_label }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold text-slate-400 bg-slate-100">
                                            Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <button type="button" 
                                            wire:click="openEdit({{ $s->id }}, {{ $att ? $att->id : 'null' }})" 
                                            class="px-2.5 py-1 bg-sky-50 hover:bg-sky-100 text-[#1E88E5] font-bold text-[10px] rounded-xl transition cursor-pointer">
                                        Koreksi
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400 text-xs">Belum ada murid pada filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($viewMode === 'weekly')
        <div class="soft-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-sky-50/50 text-[10px] uppercase font-black text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="p-3 w-8 text-center">No</th>
                            <th class="p-3">Nama Murid</th>
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
                        @forelse($weeklyMatrix as $idx => $row)
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
                                <td colspan="8" class="p-8 text-center text-slate-400 text-xs">Belum ada data pada minggu ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($viewMode === 'monthly')
        <div class="soft-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-sky-50/50 text-[10px] uppercase font-black text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="p-3.5 w-10 text-center">No</th>
                            <th class="p-3.5">Nama Murid</th>
                            <th class="p-3.5 text-center">H</th>
                            <th class="p-3.5 text-center">T</th>
                            <th class="p-3.5 text-center">I</th>
                            <th class="p-3.5 text-center">S</th>
                            <th class="p-3.5 text-center">A</th>
                            <th class="p-3.5 text-center">Pulang</th>
                            <th class="p-3.5 text-center">% Hadir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($monthlyMatrix as $idx => $row)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-3.5 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                <td class="p-3.5 font-bold text-slate-850 whitespace-nowrap">{{ $row['student']->name }}</td>
                                <td class="p-3.5 text-center font-bold text-emerald-700">{{ $row['hadir'] }}</td>
                                <td class="p-3.5 text-center font-bold text-amber-700">{{ $row['terlambat'] }}</td>
                                <td class="p-3.5 text-center font-bold text-sky-700">{{ $row['izin'] }}</td>
                                <td class="p-3.5 text-center font-bold text-purple-700">{{ $row['sakit'] }}</td>
                                <td class="p-3.5 text-center font-bold text-rose-700">{{ $row['alpa'] }}</td>
                                <td class="p-3.5 text-center font-bold text-blue-700">{{ $row['pulang'] }}</td>
                                <td class="p-3.5 text-center font-black text-[#1E88E5]">{{ $row['percentage'] }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-slate-400 text-xs">Belum ada data presensi pada bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Modal Koreksi Status Absensi (with Check In & Check Out) -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="text-sm font-black text-slate-850">Koreksi Presensi Murid</h3>
                        <p class="text-xs text-[#1E88E5] font-bold">{{ $editingStudentName }}</p>
                    </div>
                    <button type="button" wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer">&times;</button>
                </div>

                <form wire:submit="saveCorrection" class="space-y-3.5">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status Kehadiran</label>
                        <select wire:model="editStatus" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800">
                            <option value="hadir">Hadir (Tepat Waktu)</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpa">Alpa</option>
                        </select>
                    </div>

                    @if(in_array($editStatus, ['hadir', 'terlambat']))
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Jam Masuk</label>
                                <input type="time" wire:model="editCheckIn" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-mono font-bold text-slate-800" />
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Jam Pulang</label>
                                <input type="time" wire:model="editCheckOut" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-mono font-bold text-slate-800" />
                            </div>
                        </div>
                    @endif

                    @if($editStatus === 'terlambat')
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Menit Keterlambatan</label>
                            <input type="number" wire:model="editLateMinutes" min="1" max="300" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-800" />
                        </div>
                    @endif

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Catatan Koreksi</label>
                        <textarea wire:model="editNotes" placeholder="Alasan koreksi..." rows="2" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800"></textarea>
                    </div>

                    <div class="flex gap-2 pt-3 border-t border-slate-100">
                        <button type="submit" class="flex-1 py-3 bg-[#1E88E5] hover:bg-[#1976D2] text-white font-bold text-xs rounded-2xl shadow-md shadow-sky-500/20 cursor-pointer">
                            Simpan Koreksi
                        </button>
                        <button type="button" wire:click="$set('showEditModal', false)" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-2xl cursor-pointer">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
