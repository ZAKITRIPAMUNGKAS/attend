<div class="space-y-5">

    <!-- Alert Notifications -->
    @if($successMessage)
        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-850 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ $successMessage }}</span>
            </div>
            <button wire:click="$set('successMessage', '')" class="text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    @if($errorMessage)
        <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-850 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                <span>{{ $errorMessage }}</span>
            </div>
            <button wire:click="$set('errorMessage', '')" class="text-rose-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- Form Pengajuan Soft Card -->
    <div class="soft-card p-5 space-y-4">
        <div class="flex items-center gap-2.5 pb-2 border-b border-slate-100">
            <div class="w-8 h-8 rounded-xl bg-sky-100 text-[#1E88E5] flex items-center justify-center font-bold">
                <i data-lucide="file-plus-2" class="w-4 h-4"></i>
            </div>
            <div>
                <h3 class="text-xs font-black text-slate-850 uppercase tracking-wider">Form Pengajuan Izin</h3>
                <p class="text-[11px] text-slate-400">Isi keterangan dan lampirkan bukti surat</p>
            </div>
        </div>

        <form wire:submit="submit" class="space-y-3.5">
            <!-- Jenis Izin Tabs -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Jenis Pengajuan</label>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" 
                            wire:click="$set('type', 'izin')" 
                            class="py-2.5 px-3 rounded-2xl text-xs font-bold border transition flex items-center justify-center gap-2 cursor-pointer {{ $type === 'izin' ? 'bg-sky-50 border-sky-400 text-[#1E88E5] shadow-sm' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                        <span>Izin Acara</span>
                    </button>

                    <button type="button" 
                            wire:click="$set('type', 'sakit')" 
                            class="py-2.5 px-3 rounded-2xl text-xs font-bold border transition flex items-center justify-center gap-2 cursor-pointer {{ $type === 'sakit' ? 'bg-purple-50 border-purple-400 text-purple-700 shadow-sm' : 'bg-slate-50 border-slate-200 text-slate-600' }}">
                        <i data-lucide="heart-pulse" class="w-4 h-4"></i>
                        <span>Sakit</span>
                    </button>
                </div>
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Tanggal</label>
                <input type="date" 
                       wire:model="date" 
                       required 
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                @error('date') <span class="text-[10px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Alasan -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Alasan Detail</label>
                <textarea wire:model="reason" 
                          rows="3" 
                          placeholder="Jelaskan alasan izin / sakit..." 
                          required
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500"></textarea>
                @error('reason') <span class="text-[10px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Upload Bukti -->
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                    Lampiran Bukti (Foto Surat / PDF)
                </label>
                <input type="file" 
                       wire:model="attachment" 
                       accept="image/*,application/pdf"
                       class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-[#1E88E5] hover:file:bg-sky-100 cursor-pointer" />
                @error('attachment') <span class="text-[10px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Submit -->
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full py-3.5 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white font-bold text-xs rounded-2xl shadow-md shadow-sky-500/25 flex items-center justify-center gap-2 transition active:scale-98 cursor-pointer disabled:opacity-75">
                <span wire:loading.remove wire:target="submit">Kirim Pengajuan</span>
                <span wire:loading wire:target="submit">Mengirimkan...</span>
                <i data-lucide="send" class="w-3.5 h-3.5" wire:loading.remove wire:target="submit"></i>
            </button>
        </form>
    </div>

    <!-- Riwayat Pengajuan Izin -->
    <div class="space-y-3">
        <h3 class="text-sm font-black text-slate-850">Riwayat Pengajuan</h3>

        @forelse($requests as $req)
            <div class="soft-card p-4 space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full {{ $req->type === 'sakit' ? 'bg-purple-100 text-purple-800' : 'bg-sky-100 text-sky-800' }}">
                        {{ $req->type }}
                    </span>

                    @if($req->status === 'menunggu')
                        <span class="text-[10px] bg-amber-100 text-amber-800 font-bold px-2.5 py-0.5 rounded-full">
                            Menunggu Review
                        </span>
                    @elseif($req->status === 'disetujui')
                        <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2.5 py-0.5 rounded-full">
                            Disetujui
                        </span>
                    @else
                        <span class="text-[10px] bg-rose-100 text-rose-800 font-bold px-2.5 py-0.5 rounded-full">
                            Ditolak
                        </span>
                    @endif
                </div>

                <div>
                    <span class="text-xs font-bold text-slate-800">
                        {{ \Carbon\Carbon::parse($req->date)->translatedFormat('l, d F Y') }}
                    </span>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $req->reason }}</p>
                </div>
            </div>
        @empty
            <div class="soft-card p-6 text-center text-xs text-slate-400">
                Belum ada riwayat pengajuan izin.
            </div>
        @endforelse
    </div>

</div>
