<div class="space-y-4">

    @if($successMessage)
        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-850 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')" class="text-emerald-700 font-bold text-sm">&times;</button>
        </div>
    @endif

    <div class="soft-card p-4 flex items-center justify-between bg-white">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                Master Data Kelas
            </span>
            <h2 class="text-base font-black text-slate-850 tracking-tight mt-1">Rombongan Belajar</h2>
            <p class="text-[11px] text-slate-400">Total {{ $classes->total() }} rombel kelas aktif.</p>
        </div>

        <button type="button" 
                wire:click="create" 
                class="py-2.5 px-3.5 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white font-bold text-xs rounded-2xl shadow-md shadow-sky-500/20 flex items-center gap-1.5 transition active:scale-95 cursor-pointer">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            <span>+ Kelas</span>
        </button>
    </div>

    <!-- Chapter-List Mobile Card Rows for Classes -->
    <div class="space-y-2.5">
        @forelse($classes as $idx => $c)
            <div class="soft-card p-3.5 space-y-2.5 transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Clean Grade Roman Numeral Squircle Badge -->
                        <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-black text-sm flex items-center justify-center shrink-0 shadow-blue-500/20 shadow-sm">
                            {{ $c->grade }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm font-black text-slate-850 truncate">Kelas {{ $c->name }}</h4>
                            <p class="text-[10px] text-slate-400">
                                Tingkat <strong class="text-slate-700">{{ $c->grade }}</strong> • TA: {{ $c->academicYear->name ?? '2026/2027' }}
                            </p>
                        </div>
                    </div>

                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-100 shrink-0">
                        {{ $c->students_count }} Siswa
                    </span>
                </div>

                <!-- Middle: Wali Kelas -->
                <div class="p-2.5 rounded-xl bg-[#F4F8FC] border border-sky-100/70 text-[11px] text-slate-600 flex items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="text-[10px] text-slate-400 block font-semibold">Wali Kelas Pengampu:</span>
                        <span class="font-bold text-slate-850 truncate block">{{ $c->homeroomTeacher->name ?? 'Belum Ditugaskan' }}</span>
                    </div>
                </div>

                <!-- Bottom Row Actions -->
                <div class="flex items-center justify-end gap-1.5 pt-1 border-t border-slate-100">
                    <button type="button" 
                            wire:click="edit({{ $c->id }})" 
                            class="p-1.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[10px] flex items-center gap-1 transition cursor-pointer">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                        <span>Edit</span>
                    </button>
                    <button type="button" 
                            wire:click="delete({{ $c->id }})" 
                            wire:confirm="Hapus kelas ini?" 
                            class="p-1.5 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center gap-1 transition cursor-pointer">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        <span>Hapus</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="soft-card p-8 text-center text-xs text-slate-400">
                Belum ada data rombel kelas.
            </div>
        @endforelse

        <div class="pt-2">
            {{ $classes->links() }}
        </div>
    </div>

    <!-- Modal Form Tambah / Edit Kelas -->
    @if($showModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl flex flex-col max-h-[85vh] overflow-hidden">
                <div class="p-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
                    <h3 class="text-sm font-black text-slate-850">{{ $classId ? 'Edit Kelas' : 'Tambah Kelas' }}</h3>
                    <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer text-base p-1">&times;</button>
                </div>

                <form wire:submit="save" class="flex flex-col flex-1 overflow-hidden">
                    <div class="p-4 space-y-3 overflow-y-auto flex-1 overscroll-contain">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Rombel Kelas</label>
                            <input type="text" wire:model="name" placeholder="Contoh: X IPA 1" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                            @error('name') <span class="text-[10px] text-rose-600">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tingkat</label>
                            <select wire:model="grade" required class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                                <option value="X">X (Sepuluh)</option>
                                <option value="XI">XI (Sebelas)</option>
                                <option value="XII">XII (Duabelas)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Wali Kelas</label>
                            <select wire:model="homeroom_teacher_id" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                                <option value="">-- Belum Ditugaskan --</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="p-4 border-t border-slate-100 flex gap-2 shrink-0 bg-white">
                        <button type="submit" class="flex-1 py-2.5 bg-[#1E88E5] hover:bg-[#1976D2] text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 cursor-pointer active:scale-95 transition">
                            Simpan Kelas
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
