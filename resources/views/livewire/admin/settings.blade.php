<div class="space-y-6 max-w-2xl">

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

    <div class="soft-card p-6 sm:p-8 space-y-6">
        <div class="flex items-center gap-3.5 border-b border-slate-100 pb-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-400 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/20">
                <i data-lucide="clock" class="w-6 h-6 stroke-[2.5]"></i>
            </div>
            <div>
                <h2 class="text-base font-black text-slate-850 tracking-tight">Pengaturan Jam Masuk & Pulang</h2>
                <p class="text-xs text-slate-400">Atur jadwal absensi masuk pagi dan sesi kepulangan murid.</p>
            </div>
        </div>

        <form wire:submit="save" class="space-y-5">
            
            <!-- SECTION 1: JADWAL ABSENSI MASUK -->
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-700">1. Sesi Absensi Masuk (Pagi)</h3>
                </div>

                <!-- Jam Buka Scan Masuk -->
                <div class="p-4 bg-[#F4F8FC] rounded-2xl border border-sky-100 space-y-1">
                    <label class="block text-xs font-bold text-slate-750">
                        Jam Buka Absensi Masuk
                    </label>
                    <input type="time" 
                           wire:model="check_in_start" 
                           required 
                           class="w-full px-4 py-2.5 bg-white border border-sky-100 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                    <p class="text-[11px] text-slate-400">Kamera scanner guru dibuka mulai jam ini.</p>
                </div>

                <!-- Batas On-Time -->
                <div class="p-4 bg-[#F4F8FC] rounded-2xl border border-sky-100 space-y-1">
                    <label class="block text-xs font-bold text-slate-750">
                        Batas Tepat Waktu (On-Time Until)
                    </label>
                    <input type="time" 
                           wire:model="on_time_until" 
                           required 
                           class="w-full px-4 py-2.5 bg-white border border-sky-100 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                    <p class="text-[11px] text-slate-400">Scan sebelum waktu ini dicatat berstatus <strong class="text-emerald-600">HADIR</strong> (0 Menit Terlambat).</p>
                </div>

                <!-- Batas Terlambat -->
                <div class="p-4 bg-[#F4F8FC] rounded-2xl border border-sky-100 space-y-1">
                    <label class="block text-xs font-bold text-slate-750">
                        Batas Maksimal Terlambat (Late Until)
                    </label>
                    <input type="time" 
                           wire:model="late_until" 
                           required 
                           class="w-full px-4 py-2.5 bg-white border border-sky-100 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                    <p class="text-[11px] text-slate-400">Scan setelah on-time hingga batas ini dicatat <strong class="text-amber-600">TERLAMBAT</strong>.</p>
                </div>

                <!-- Jam Auto-Alpa Cutoff -->
                <div class="p-4 bg-[#F4F8FC] rounded-2xl border border-sky-100 space-y-1">
                    <label class="block text-xs font-bold text-slate-750">
                        Jam Penutupan Absensi Masuk (Auto-Alpa Cutoff)
                    </label>
                    <input type="time" 
                       wire:model="auto_absent_at" 
                       required 
                       class="w-full px-4 py-2.5 bg-white border border-sky-100 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                    <p class="text-[11px] text-slate-400">Murid yang belum hadir otomatis ditandai <strong class="text-rose-600">ALPA</strong>.</p>
                </div>
            </div>

            <!-- SECTION 2: JADWAL ABSENSI PULANG -->
            <div class="space-y-3 pt-3 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-700">2. Sesi Absensi Pulang (Check-Out)</h3>
                    </div>
                    
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model.live="allow_checkout" class="w-4 h-4 rounded text-sky-600 focus:ring-sky-500" />
                        <span class="text-xs font-bold text-slate-700">Aktifkan Absen Pulang</span>
                    </label>
                </div>

                @if($allow_checkout)
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Jam Buka Pulang -->
                        <div class="p-4 bg-sky-50/70 rounded-2xl border border-sky-200 space-y-1">
                            <label class="block text-xs font-bold text-slate-750">
                                Jam Buka Absen Pulang
                            </label>
                            <input type="time" 
                                   wire:model="check_out_start" 
                                   required 
                                   class="w-full px-3.5 py-2.5 bg-white border border-sky-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                            <p class="text-[10px] text-slate-400">Scan mulai jam ini otomatis mencatat Jam Pulang.</p>
                        </div>

                        <!-- Batas Akhir Pulang -->
                        <div class="p-4 bg-sky-50/70 rounded-2xl border border-sky-200 space-y-1">
                            <label class="block text-xs font-bold text-slate-750">
                                Batas Akhir Absen Pulang
                            </label>
                            <input type="time" 
                                   wire:model="check_out_end" 
                                   required 
                                   class="w-full px-3.5 py-2.5 bg-white border border-sky-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                            <p class="text-[10px] text-slate-400">Sesi scan kepulangan ditutup pada jam ini.</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="pt-4 border-t border-slate-100">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full py-3.5 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white font-bold text-xs rounded-2xl shadow-lg shadow-sky-500/25 transition active:scale-98 cursor-pointer disabled:opacity-75">
                    <span wire:loading.remove>Simpan Semua Pengaturan Jam</span>
                    <span wire:loading>Menyimpan Pengaturan...</span>
                </button>
            </div>
        </form>
    </div>

</div>
