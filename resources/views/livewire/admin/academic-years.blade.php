<div class="space-y-4">

    @if($successMessage)
        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-850 text-xs font-semibold flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')" class="text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <div class="soft-card p-4 flex items-center justify-between bg-white">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                Master Data
            </span>
            <h2 class="text-base font-black text-slate-850 tracking-tight mt-1">Tahun Ajaran</h2>
            <p class="text-[11px] text-slate-400">Atur periode & semester aktif.</p>
        </div>

        <button type="button" 
                wire:click="create" 
                class="py-2.5 px-3.5 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white font-bold text-xs rounded-2xl shadow-md shadow-sky-500/20 flex items-center gap-1.5 transition active:scale-95 cursor-pointer">
            <i data-lucide="plus" class="w-3.5 h-3.5"></i>
            <span>+ Tahun</span>
        </button>
    </div>

    <!-- Chapter-List Mobile Card Rows for Academic Years -->
    <div class="space-y-2.5">
        @forelse($years as $idx => $y)
            <div class="soft-card p-3.5 space-y-2.5 transition">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 text-white font-black text-xs flex items-center justify-center shrink-0 shadow-xs">
                            {{ $idx + 1 }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-black text-slate-850 truncate">{{ $y->name }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold capitalize">
                                Semester {{ $y->semester }}
                            </p>
                        </div>
                    </div>

                    @if($y->is_active)
                        <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase bg-emerald-100 text-emerald-850 border border-emerald-200 shrink-0">
                            SEDANG AKTIF
                        </span>
                    @else
                        <button type="button" 
                                wire:click="setActive({{ $y->id }})" 
                                class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-slate-100 text-slate-600 hover:bg-sky-50 hover:text-[#1E88E5] border border-slate-200 transition cursor-pointer shrink-0">
                            Aktifkan
                        </button>
                    @endif
                </div>

                <!-- Middle: Date Period -->
                <div class="p-2 rounded-xl bg-[#F4F8FC] border border-sky-100/70 text-[11px] text-slate-600 flex items-center justify-between">
                    <span class="text-[10px] text-slate-400 font-semibold">Periode Semester:</span>
                    <span class="font-mono font-bold text-slate-800 text-xs">
                        {{ $y->start_date ? \Carbon\Carbon::parse($y->start_date)->format('d/m/Y') : '-' }} – {{ $y->end_date ? \Carbon\Carbon::parse($y->end_date)->format('d/m/Y') : '-' }}
                    </span>
                </div>

                <!-- Bottom Row Actions -->
                <div class="flex items-center justify-end gap-1.5 pt-1 border-t border-slate-100">
                    <button type="button" 
                            wire:click="edit({{ $y->id }})" 
                            class="p-1.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[10px] flex items-center gap-1 transition cursor-pointer">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                        <span>Edit</span>
                    </button>
                    @if(!$y->is_active)
                        <button type="button" 
                                wire:click="delete({{ $y->id }})" 
                                wire:confirm="Hapus tahun ajaran ini?" 
                                class="p-1.5 px-3 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-[10px] flex items-center gap-1 transition cursor-pointer">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            <span>Hapus</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="soft-card p-8 text-center text-xs text-slate-400">
                Belum ada data tahun ajaran.
            </div>
        @endforelse
    </div>

    <!-- Modal Form Tambah / Edit Tahun Ajaran -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-5 space-y-3.5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="text-sm font-black text-slate-850">{{ $academicYearId ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' }}</h3>
                    <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-700 cursor-pointer text-base">&times;</button>
                </div>

                <form wire:submit="save" class="space-y-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama Tahun Ajaran</label>
                        <input type="text" wire:model="name" placeholder="Contoh: 2026/2027" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                        @error('name') <span class="text-[10px] text-rose-600">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Semester</label>
                        <select wire:model="semester" required class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                            <option value="ganjil">Semester Ganjil</option>
                            <option value="genap">Semester Genap</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2.5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tgl Mulai</label>
                            <input type="date" wire:model="start_date" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Tgl Selesai</label>
                            <input type="date" wire:model="end_date" class="w-full px-2.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800" />
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input type="checkbox" id="is_active" wire:model="is_active" class="w-4 h-4 rounded text-sky-600 focus:ring-sky-500" />
                        <label for="is_active" class="text-xs font-bold text-slate-700">Tahun Ajaran Aktif</label>
                    </div>

                    <div class="flex gap-2 pt-2 border-t border-slate-100">
                        <button type="submit" class="flex-1 py-2.5 bg-[#1E88E5] hover:bg-[#1976D2] text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 cursor-pointer">
                            Simpan Data
                        </button>
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl cursor-pointer">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
