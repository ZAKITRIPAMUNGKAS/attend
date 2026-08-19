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

    <!-- Header Card -->
    <div class="soft-card p-4 space-y-3.5 bg-white">
        <div class="flex items-start justify-between gap-3">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    Master Data Guru
                </span>
                <h2 class="text-base font-black text-slate-850 tracking-tight mt-1">Data Guru & Wali Kelas</h2>
                <p class="text-[11px] text-slate-400">Total {{ $teachers->total() }} guru terdaftar.</p>
            </div>

            <button type="button" 
                    wire:click="create" 
                    class="py-2.5 px-3.5 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white font-bold text-xs rounded-2xl shadow-md shadow-sky-500/20 flex items-center gap-1.5 transition active:scale-95 cursor-pointer shrink-0">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                <span>+ Guru</span>
            </button>
        </div>

        <!-- Search Input -->
        <div class="relative pt-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 pt-1">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            </div>
            <input type="text" 
                   wire:model.live.debounce.300ms="search" 
                   placeholder="Cari nama guru atau NIP..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500" />
        </div>
    </div>

    <!-- Chapter-List Mobile Card Rows for Teachers -->
    <div class="space-y-2.5">
        @forelse($teachers as $idx => $t)
            <div class="soft-card p-3.5 space-y-2.5 transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Direct SVG Graduation Cap for Teacher -->
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-cyan-600 to-teal-500 text-white flex items-center justify-center shrink-0 shadow-cyan-500/20 shadow-sm">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-black text-slate-850 truncate">{{ $t->name }}</h4>
                            <p class="text-[10px] text-slate-400 font-mono">
                                NIP: <strong class="text-slate-700">{{ $t->nip ?: '-' }}</strong> • {{ $t->gender === 'P' ? 'Ustadzah' : 'Ustadz' }}
                            </p>
                        </div>
                    </div>

                    @if($t->homeroomClasses->isNotEmpty())
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-teal-50 text-teal-700 border border-teal-200 shrink-0 flex items-center gap-1">
                            <span>Wali {{ $t->homeroomClasses->pluck('name')->join(', ') }}</span>
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded-lg text-[9px] font-bold bg-slate-100 text-slate-500 shrink-0">
                            Bukan Wali
                        </span>
                    @endif
                </div>

                <!-- Middle: Contact & Assignment Details -->
                <div class="p-2.5 rounded-xl bg-[#F4F8FC] border border-sky-100/70 text-[11px] text-slate-600 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 block font-semibold">Username Login:</span>
                        <span class="font-mono font-bold text-slate-800 text-xs">{{ $t->user->username ?? '-' }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400 block font-semibold">Tugas Wali Kelas:</span>
                        <span class="font-bold text-[#1E88E5] text-xs">
                            {{ $t->homeroomClasses->isNotEmpty() ? $t->homeroomClasses->pluck('name')->join(', ') : 'Belum Ditugaskan' }}
                        </span>
                    </div>
                </div>

                <!-- Bottom Row Actions -->
                <div class="flex items-center justify-between pt-1 border-t border-slate-100">
                    <!-- Quick Set Wali Kelas Button -->
                    <button type="button" 
                            wire:click="openQuickHomeroom({{ $t->id }})" 
                            class="px-2.5 py-1 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-800 border border-teal-200 font-bold text-[10px] flex items-center gap-1 transition cursor-pointer">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        <span>Set Wali Kelas</span>
                    </button>

                    <div class="flex items-center gap-1.5">
                        <button type="button" 
                                wire:click="edit({{ $t->id }})" 
                                class="px-2 py-1 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-[10px] flex items-center gap-1 transition cursor-pointer">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                            <span>Edit</span>
                        </button>
                        <button type="button" 
                                wire:click="delete({{ $t->id }})" 
                                wire:confirm="Hapus data guru ini?" 
                                class="p-1 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 font-bold text-[10px] flex items-center gap-1 transition cursor-pointer">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="soft-card p-8 text-center text-xs text-slate-400">
                Tidak ada data guru yang cocok.
            </div>
        @endforelse

        <div class="pt-2">
            {{ $teachers->links() }}
        </div>
    </div>

    <!-- MODAL 1: QUICK SET WALI KELAS GURU -->
    @if($showQuickHomeroomModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
                    <div>
                        <h3 class="text-sm font-black text-slate-850">Penugasan Wali Kelas</h3>
                        <p class="text-xs text-teal-700 font-bold">{{ $quickTeacherName }}</p>
                    </div>
                    <button type="button" wire:click="$set('showQuickHomeroomModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer text-base p-1">&times;</button>
                </div>

                <form wire:submit="saveQuickHomeroom" class="flex flex-col flex-1 overflow-hidden">
                    <div class="p-4 space-y-3 overflow-y-auto flex-1 overscroll-contain">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Rombongan Belajar (Kelas)</label>
                            <select wire:model="quickClassId" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <option value="">-- Bukan / Hapus Wali Kelas --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">
                                        Kelas {{ $c->name }} (Tingkat {{ $c->grade }})
                                        @if($c->homeroom_teacher_id && $c->homeroom_teacher_id != $quickTeacherId)
                                            — [Saat ini: {{ $c->homeroomTeacher?->name }}]
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="p-4 border-t border-slate-100 flex gap-2 shrink-0 bg-white">
                        <button type="submit" class="flex-1 py-2.5 bg-teal-600 hover:bg-teal-700 text-white font-bold text-xs rounded-xl shadow-md shadow-teal-500/20 cursor-pointer active:scale-95 transition">
                            Simpan Penugasan
                        </button>
                        <button type="button" wire:click="$set('showQuickHomeroomModal', false)" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl cursor-pointer active:scale-95 transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- MODAL 2: FORM TAMBAH / EDIT DATA GURU -->
    @if($showModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
                    <h3 class="text-sm font-black text-slate-850">{{ $teacherId ? 'Edit Data Guru' : 'Tambah Guru Baru' }}</h3>
                    <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer text-base p-1">&times;</button>
                </div>

                <form wire:submit="save" class="flex flex-col flex-1 overflow-hidden">
                    <div class="p-4 space-y-3 overflow-y-auto flex-1 overscroll-contain">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Lengkap (dengan Gelar)</label>
                            <input type="text" wire:model="name" placeholder="Contoh: Ustadz Ahmad, S.Pd" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                            @error('name') <span class="text-[10px] text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIP (Opsional)</label>
                                <input type="text" wire:model="nip" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 font-mono" />
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Gender</label>
                                <select wire:model="gender" required class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                                    <option value="L">Laki-laki (Ustadz)</option>
                                    <option value="P">Perempuan (Ustadzah)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tugaskan Sebagai Wali Kelas (Opsional)</label>
                            <select wire:model="homeroom_class_id" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                                <option value="">-- Bukan Wali Kelas --</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">Kelas {{ $c->name }} (Tingkat {{ $c->grade }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Username Login</label>
                                <input type="text" wire:model="username" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 font-mono" />
                                @error('username') <span class="text-[10px] text-rose-600">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">No. WhatsApp</label>
                                <input type="text" wire:model="phone" placeholder="08xxx" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Email (Opsional)</label>
                            <input type="email" wire:model="email" placeholder="guru@smait.sch.id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Password {{ $teacherId ? '(Kosongkan jika tidak diubah)' : '' }}</label>
                            <input type="password" wire:model="password" autocomplete="new-password" placeholder="{{ $teacherId ? '••••••••' : 'Default: password' }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                        </div>
                    </div>

                    <div class="p-4 border-t border-slate-100 flex gap-2 shrink-0 bg-white">
                        <button type="submit" class="flex-1 py-2.5 bg-[#1E88E5] hover:bg-[#1976D2] text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 cursor-pointer active:scale-95 transition">
                            Simpan Data Guru
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
