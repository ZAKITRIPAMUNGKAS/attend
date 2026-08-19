<div class="space-y-4">

    <!-- Flash message notification -->
    @if($successMessage)
        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-850 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')" class="text-emerald-700 font-bold text-sm">&times;</button>
        </div>
    @endif

    <!-- ========================================================= -->
    <!-- VIEW 1: PILIHAN DIREKTORI KELAS (DEFAULT)                  -->
    <!-- ========================================================= -->
    @if(!$selectedClassId && !$viewAll)
        <div class="soft-card p-4 space-y-3 bg-white">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                        Direktori Kelas & Roster
                    </span>
                    <h2 class="text-base font-black text-slate-850 tracking-tight mt-1">Pilih Rombongan Belajar</h2>
                    <p class="text-[11px] text-slate-400">Pilih kelas untuk melihat & mengelola daftar murid.</p>
                </div>

                <button type="button" 
                        wire:click="create" 
                        class="py-2 px-3 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 flex items-center gap-1.5 transition active:scale-95 cursor-pointer shrink-0">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    <span>+ Murid</span>
                </button>
            </div>

            <!-- Quick Global Action & Export -->
            <div class="flex items-center gap-2 pt-1">
                <button type="button" 
                        wire:click="showAllStudents" 
                        class="flex-1 py-2 px-2.5 bg-[#F4F8FC] hover:bg-sky-50 text-[#1E88E5] border border-sky-100 font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span>Semua Murid</span>
                </button>

                <a href="{{ route('export.students-pdf') }}" 
                   target="_blank" 
                   class="py-2 px-3 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                    <span>PDF</span>
                </a>

                <a href="{{ route('export.students') }}" 
                   target="_blank" 
                   class="py-2 px-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></svg>
                    <span>Excel</span>
                </a>
            </div>
        </div>

        <!-- Class Cards Grid -->
        <div class="space-y-3">
            <h3 class="text-xs font-black uppercase tracking-wider text-slate-400">Daftar Kelas Aktif</h3>

            <div class="space-y-2.5">
                @forelse($classes as $c)
                    <div class="soft-card p-4 space-y-3 transition hover:shadow-md">
                        <!-- Top Info: Name, Grade & Student Count -->
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-black text-sm flex items-center justify-center shrink-0 shadow-blue-500/20 shadow-sm">
                                    {{ $c->grade }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-black text-slate-850">{{ $c->name }}</h4>
                                    <p class="text-[10px] text-slate-400">Tingkat {{ $c->grade }} • {{ $c->academicYear->name ?? '2026/2027' }}</p>
                                </div>
                            </div>

                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $c->students_count }} Murid
                            </span>
                        </div>

                        <!-- Teacher Info -->
                        <div class="flex items-center gap-2 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                            <span class="font-medium text-[11px] truncate">
                                Wali: <strong>{{ $c->homeroomTeacher->name ?? 'Belum Ditentukan' }}</strong>
                            </span>
                        </div>

                        <!-- Class Card Actions -->
                        <div class="flex items-center gap-2 pt-1 border-t border-slate-100">
                            <button type="button" 
                                    wire:click="selectClass({{ $c->id }})" 
                                    class="flex-1 py-2 px-3 bg-[#1E88E5] hover:bg-[#1976D2] text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-1.5 transition cursor-pointer">
                                <span>Buka Rombel ({{ $c->students_count }})</span>
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                            </button>

                            <a href="{{ route('export.students-pdf', ['class_id' => $c->id]) }}" 
                               target="_blank" 
                               title="Export PDF Rombel {{ $c->name }}" 
                               class="p-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                            </a>

                            <a href="{{ route('export.students', ['class_id' => $c->id]) }}" 
                               target="_blank" 
                               title="Export Excel Rombel {{ $c->name }}" 
                               class="p-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="soft-card p-6 text-center text-slate-400 bg-white">
                        <p class="text-xs">Belum ada data kelas yang terdaftar.</p>
                    </div>
                @endforelse
            </div>
        </div>

    <!-- ========================================================= -->
    <!-- VIEW 2: DATA MURID KELAS TERPILIH / GLOBAL               -->
    <!-- ========================================================= -->
    @else
        <!-- Back Navigation & Class Header Card -->
        <div class="soft-card p-4 space-y-3.5 bg-white">
            <div class="flex items-center justify-between gap-2">
                <button type="button" 
                        wire:click="backToClasses" 
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#F4F8FC] hover:bg-sky-50 text-[#1E88E5] border border-sky-100 rounded-xl text-xs font-bold transition cursor-pointer">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                    <span>Pilihan Kelas</span>
                </button>
            </div>

            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    {{ $currentClass ? 'Rombel ' . $currentClass->name : 'Semua Murid Sekolah' }}
                </span>
                <h2 class="text-base font-black text-slate-850 tracking-tight mt-1">
                    {{ $currentClass ? 'Data Murid ' . $currentClass->name : 'Daftar Seluruh Murid' }}
                </h2>
                <p class="text-[11px] text-slate-500 font-medium">
                    Total {{ $students ? $students->total() : 0 }} Murid terdaftar.
                </p>
            </div>

            <!-- Action Buttons for this class -->
            <div class="grid grid-cols-4 gap-1.5 pt-1">
                <button type="button" 
                        wire:click="create" 
                        class="py-2.5 px-2 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 flex items-center justify-center gap-1 transition active:scale-95 cursor-pointer">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    <span>+ Murid</span>
                </button>

                <a href="{{ route('export.students-pdf', ['class_id' => $selectedClassId]) }}" 
                   target="_blank" 
                   class="py-2.5 px-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-1 transition">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/></svg>
                    <span>PDF</span>
                </a>

                <a href="{{ route('export.students', ['class_id' => $selectedClassId]) }}" 
                   target="_blank" 
                   class="py-2.5 px-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs flex items-center justify-center gap-1 transition">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M8 13h2"/><path d="M14 13h2"/><path d="M8 17h2"/><path d="M14 17h2"/></svg>
                    <span>Excel</span>
                </a>

                <!-- Cetak QR Kartu [LOCKED] -->
                <div class="py-2.5 px-2 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl border border-slate-200 flex flex-col items-center justify-center gap-0.5 cursor-not-allowed select-none relative">
                    <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>QR</span>
                    <span class="text-[8px] font-extrabold uppercase tracking-wider text-amber-700">Soon</span>
                </div>
            </div>

            <!-- Search Inside this Class -->
            <div class="relative pt-1">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 pt-1">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari nama atau NISN murid..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500" />
            </div>
        </div>

        <!-- Clean Compact Student Cards List -->
        <div class="space-y-2.5">
            @forelse($students as $idx => $s)
                <div class="soft-card p-3.5 space-y-2.5 transition hover:shadow-md">
                    <!-- Top Row: Clickable for Detail View -->
                    <div wire:click="showDetail({{ $s->id }})" class="flex items-start justify-between gap-3 cursor-pointer group">
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Student Icon Squircle -->
                            <div class="w-10 h-10 rounded-2xl {{ $s->gender === 'P' ? 'bg-gradient-to-tr from-rose-500 to-pink-400 shadow-rose-500/20' : 'bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] shadow-sky-500/20' }} text-white flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-xs font-black text-slate-850 truncate group-hover:text-[#1E88E5] transition flex items-center gap-1.5">
                                    <span>{{ $s->name }}</span>
                                    <svg class="w-3 h-3 text-slate-400 group-hover:text-[#1E88E5] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6"/></svg>
                                </h4>
                                <p class="text-[10px] text-slate-400 font-mono">
                                    NISN: <strong class="text-slate-700">{{ $s->nisn }}</strong> • DOB: {{ $s->birth_date ? \Carbon\Carbon::parse($s->birth_date)->format('d/m/Y') : '-' }}
                                </p>
                            </div>
                        </div>

                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-sky-50 text-[#1E88E5] border border-sky-100 shrink-0">
                            {{ $s->schoolClass->name ?? '-' }}
                        </span>
                    </div>

                    <!-- Bottom Actions Row -->
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100/90 gap-2">
                        <!-- Status Pill with Glowing Dot -->
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold {{ $s->status === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/70' : 'bg-slate-100 text-slate-500 border border-slate-200' }} shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full {{ $s->status === 'aktif' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                            <span class="uppercase tracking-wide">{{ $s->status }}</span>
                        </span>

                        <!-- Clean Aligned Action Button Group -->
                        <div class="flex items-center gap-1.5 shrink-0">
                            <!-- Detail Profile Button -->
                            <button type="button" 
                                    wire:click="showDetail({{ $s->id }})" 
                                    title="Lihat Detail Profil Murid"
                                    class="h-7 px-2.5 rounded-xl bg-sky-50 hover:bg-sky-100 text-[#1E88E5] border border-sky-200/80 font-black text-[10px] flex items-center gap-1 transition active:scale-95 cursor-pointer shadow-2xs">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <span>Detail</span>
                            </button>

                            <!-- Reset Password DOB -->
                            <button type="button" 
                                    wire:click="resetPassword({{ $s->id }})" 
                                    wire:confirm="Reset password akun murid ini ke format tanggal lahir (DDMMYYYY)?"
                                    title="Reset Password ke Tanggal Lahir (DOB)"
                                    class="w-7 h-7 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200/80 flex items-center justify-center transition active:scale-95 cursor-pointer shadow-2xs">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="m21 2-2 2m-1.5 1.5L14 9l-1.5-1.5L11 9l-1.5-1.5L8 9l-4.5 4.5a3.5 3.5 0 1 0 5 5L13 14l1.5-1.5L13 11l1.5-1.5L16 11l1.5-1.5L19 11l2-2Z"/></svg>
                            </button>

                            <!-- Regenerate QR Token -->
                            <button type="button" 
                                    wire:click="regenerateQr({{ $s->id }})" 
                                    wire:confirm="Perbarui token kode QR murid ini?"
                                    title="Perbarui / Regenerate Token QR"
                                    class="w-7 h-7 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 border border-indigo-200/80 flex items-center justify-center transition active:scale-95 cursor-pointer shadow-2xs">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
                            </button>

                            <!-- Edit Biodata Lengkap -->
                            <button type="button" 
                                    wire:click="edit({{ $s->id }})" 
                                    title="Edit Biodata Murid"
                                    class="w-7 h-7 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200 flex items-center justify-center transition active:scale-95 cursor-pointer shadow-2xs">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                            </button>

                            <!-- Hapus Murid -->
                            <button type="button" 
                                    wire:click="delete({{ $s->id }})" 
                                    wire:confirm="Hapus data murid ini?"
                                    title="Hapus Murid"
                                    class="w-7 h-7 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200/80 flex items-center justify-center transition active:scale-95 cursor-pointer shadow-2xs">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="soft-card p-8 text-center text-xs text-slate-400">
                    Belum ada murid yang terdaftar pada kelas ini.
                </div>
            @endforelse

            <div class="pt-2">
                {{ $students->links() }}
            </div>
        </div>
    @endif

    <!-- ========================================================= -->
    <!-- MODAL: DETAIL LENGKAP MURID                               -->
    <!-- ========================================================= -->
    @if($showDetailModal && $detailedStudent)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                
                <!-- Modal Sticky Header -->
                <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                        Profil Murid
                    </span>
                    <button type="button" wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer text-lg leading-none p-1">&times;</button>
                </div>

                <!-- Modal Scrollable Content Body -->
                <div class="p-4 space-y-3.5 overflow-y-auto flex-1 overscroll-contain">
                    <!-- Hero Profile Card -->
                    <div class="flex items-center gap-3.5 p-3.5 rounded-2xl bg-[#F4F8FC] border border-sky-100">
                        <div class="w-13 h-13 rounded-2xl {{ $detailedStudent->gender === 'P' ? 'bg-gradient-to-tr from-rose-500 to-pink-400 shadow-rose-500/20' : 'bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] shadow-sky-500/20' }} text-white flex items-center justify-center shrink-0 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-black text-slate-850 truncate leading-tight">{{ $detailedStudent->name }}</h3>
                            <p class="text-[11px] font-mono text-slate-500 mt-0.5">NISN: <strong class="text-slate-800">{{ $detailedStudent->nisn }}</strong></p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase {{ $detailedStudent->status === 'aktif' ? 'bg-emerald-100 text-emerald-850' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $detailedStudent->status }}
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold bg-sky-100 text-[#1E88E5]">
                                    {{ $detailedStudent->schoolClass->name ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Info Grid -->
                    <div class="space-y-1.5">
                        <h4 class="text-[10px] font-black uppercase tracking-wider text-slate-400">Informasi Akademik</h4>
                        <div class="p-3 rounded-2xl bg-white border border-slate-200/80 space-y-2 text-xs">
                            <div class="flex justify-between items-center border-b border-slate-100 pb-1.5">
                                <span class="text-slate-400">Rombongan Belajar</span>
                                <span class="font-bold text-slate-800">{{ $detailedStudent->schoolClass->name ?? '-' }} (Tingkat {{ $detailedStudent->schoolClass->grade ?? '-' }})</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-100 pb-1.5">
                                <span class="text-slate-400">Wali Kelas</span>
                                <span class="font-bold text-slate-800">{{ $detailedStudent->schoolClass->homeroomTeacher->name ?? 'Belum Ditugaskan' }}</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-100 pb-1.5">
                                <span class="text-slate-400">Tahun Ajaran</span>
                                <span class="font-bold text-slate-800">{{ $detailedStudent->academicYear->name ?? '2026/2027' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">NIS (Lokal)</span>
                                <span class="font-mono font-bold text-slate-800">{{ $detailedStudent->nis ?: '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Info Grid -->
                    <div class="space-y-1.5">
                        <h4 class="text-[10px] font-black uppercase tracking-wider text-slate-400">Biodata Pribadi</h4>
                        <div class="p-3 rounded-2xl bg-white border border-slate-200/80 space-y-2 text-xs">
                            <div class="flex justify-between items-center border-b border-slate-100 pb-1.5">
                                <span class="text-slate-400">Jenis Kelamin</span>
                                <span class="font-bold text-slate-800">{{ $detailedStudent->gender === 'P' ? 'Perempuan' : 'Laki-laki' }}</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-100 pb-1.5">
                                <span class="text-slate-400">Tempat, Tanggal Lahir</span>
                                <span class="font-bold text-slate-800">
                                    {{ $detailedStudent->birth_place ? $detailedStudent->birth_place . ', ' : '' }}{{ $detailedStudent->birth_date ? \Carbon\Carbon::parse($detailedStudent->birth_date)->translatedFormat('d F Y') : '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center border-b border-slate-100 pb-1.5">
                                <span class="text-slate-400">Default Password</span>
                                <span class="font-mono font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">
                                    {{ $detailedStudent->birth_date ? \Carbon\Carbon::parse($detailedStudent->birth_date)->format('dmY') : '-' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">Telepon Murid</span>
                                <span class="font-mono font-bold text-slate-800">{{ $detailedStudent->phone ?: '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Parent & WA Info Grid -->
                    <div class="space-y-1.5">
                        <h4 class="text-[10px] font-black uppercase tracking-wider text-slate-400">Orang Tua / Wali Murid</h4>
                        <div class="p-3 rounded-2xl bg-white border border-slate-200/80 space-y-2 text-xs">
                            <div class="flex justify-between items-center border-b border-slate-100 pb-1.5">
                                <span class="text-slate-400">Nama Wali</span>
                                <span class="font-bold text-slate-800">{{ $detailedStudent->parent_name ?: '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400">WhatsApp Wali</span>
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-slate-800">{{ $detailedStudent->parent_phone ?: '-' }}</span>
                                    @if($detailedStudent->parent_phone)
                                        @php
                                            $phoneClean = preg_replace('/[^0-9]/', '', $detailedStudent->parent_phone);
                                            if (str_starts_with($phoneClean, '0')) {
                                                $phoneClean = '62' . substr($phoneClean, 1);
                                            }
                                        @endphp
                                        <a href="https://wa.me/{{ $phoneClean }}" target="_blank" class="px-2 py-0.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[10px] rounded-md border border-emerald-200 flex items-center gap-1">
                                            <span>Chat WA</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Token Info -->
                    <div class="space-y-1.5">
                        <h4 class="text-[10px] font-black uppercase tracking-wider text-slate-400">Kode Token QR</h4>
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs flex items-center justify-between gap-2">
                            <span class="font-mono text-[10px] text-slate-600 truncate">{{ $detailedStudent->qr_token }}</span>
                            <a href="{{ route('export.print-qr', ['class_id' => $detailedStudent->class_id]) }}" target="_blank" class="px-2.5 py-1 bg-white text-slate-700 hover:bg-sky-50 hover:text-[#1E88E5] font-bold text-[10px] rounded-lg border border-slate-200 transition shrink-0">
                                Cetak Kartu
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Modal Sticky Footer Actions -->
                <div class="p-4 border-t border-slate-100 flex gap-2 shrink-0 bg-white">
                    <button type="button" 
                            wire:click="edit({{ $detailedStudent->id }}); $set('showDetailModal', false);" 
                            class="flex-1 py-2.5 bg-[#1E88E5] hover:bg-[#1976D2] text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 cursor-pointer flex items-center justify-center gap-1.5 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                        <span>Edit Biodata</span>
                    </button>
                    <button type="button" 
                            wire:click="$set('showDetailModal', false)" 
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl cursor-pointer active:scale-95 transition">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    @endif

    <!-- ========================================================= -->
    <!-- MODAL: TAMBAH / EDIT BIODATA MURID                        -->
    <!-- ========================================================= -->
    @if($showModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
                    <h3 class="text-sm font-black text-slate-850">
                        {{ $studentId ? 'Edit Data Murid' : 'Tambah Murid Baru' }}
                    </h3>
                    <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer text-base p-1">&times;</button>
                </div>

                <form wire:submit="save" class="flex flex-col flex-1 overflow-hidden">
                    <div class="p-4 space-y-3 overflow-y-auto flex-1 overscroll-contain">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                            @error('name') <span class="text-[10px] text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NISN</label>
                                <input type="text" wire:model="nisn" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 font-mono" />
                                @error('nisn') <span class="text-[10px] text-rose-600">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIS (Lokal)</label>
                                <input type="text" wire:model="nis" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 font-mono" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Kelas</label>
                                <select wire:model="class_id" required class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id') <span class="text-[10px] text-rose-600">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Gender</label>
                                <select wire:model="gender" required class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tanggal Lahir (Default Password)</label>
                            <input type="date" wire:model="birth_date" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                            @error('birth_date') <span class="text-[10px] text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Orang Tua</label>
                                <input type="text" wire:model="parent_name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">WA Orang Tua</label>
                                <input type="text" wire:model="parent_phone" placeholder="08xxx" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border-t border-slate-100 flex gap-2 shrink-0 bg-white">
                        <button type="submit" class="flex-1 py-2.5 bg-[#1E88E5] hover:bg-[#1976D2] text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 cursor-pointer active:scale-95 transition">
                            Simpan Data
                        </button>
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl cursor-pointer active:scale-95 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
