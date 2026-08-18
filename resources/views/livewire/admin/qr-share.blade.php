<div class="space-y-4">

    <!-- Top Header Card -->
    <div class="soft-card p-4 space-y-3 bg-white">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                Layar & Pembagian QR
            </span>
            <h2 class="text-base font-black text-slate-850 tracking-tight mt-1">Bagikan QR Presensi</h2>
            <p class="text-[11px] text-slate-400">Tampilkan di proyektor/layar lobi, atau cetak sebagai poster kelas.</p>
        </div>

        <!-- 2 Mode Tabs: QR General vs QR Per-Kelas -->
        <div class="grid grid-cols-2 gap-2 p-1 bg-[#F4F8FC] rounded-2xl border border-sky-100">
            <button type="button" 
                    wire:click="setMode('general')" 
                    class="py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer {{ $mode === 'general' ? 'bg-white text-[#1E88E5] shadow-xs border border-sky-100' : 'text-slate-500 hover:text-slate-800' }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                <span>QR General (Sekolah)</span>
            </button>

            <button type="button" 
                    wire:click="setMode('class')" 
                    class="py-2.5 px-3 rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer {{ $mode === 'class' ? 'bg-white text-[#1E88E5] shadow-xs border border-sky-100' : 'text-slate-500 hover:text-slate-800' }}">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10"/><path d="M6 10h10"/></svg>
                <span>QR Rombel Kelas</span>
            </button>
        </div>

        <!-- Class Selector if in Class Mode -->
        @if($mode === 'class')
            <div class="pt-1">
                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">Pilih Kelas yang Ingin Ditampilkan:</label>
                <select wire:model.live="selectedClassId" class="w-full px-3 py-2 bg-[#F4F8FC] border border-sky-100 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500 cursor-pointer">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">Kelas {{ $c->name }} (Tingkat {{ $c->grade }}) — Wali: {{ $c->homeroomTeacher?->name ?? 'Belum ada' }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <!-- Live QR Poster Display Container (Fullscreenable) -->
    <div id="qr-poster-card" class="soft-card overflow-hidden bg-white border border-sky-100/90 transition">
        <!-- Top Poster Brand Bar -->
        <div class="bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] p-4 text-white text-center relative">
            <div class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-white p-1.5 shadow-md shadow-sky-900/10 mb-1.5">
                <img src="{{ asset('logo.png') }}" alt="Logo SMA IT Insan Kamil" class="w-full h-full object-contain" />
            </div>
            <h3 class="text-sm font-black tracking-wide uppercase">SMA IT INSAN KAMIL</h3>
            <p class="text-[10px] text-sky-100 font-semibold tracking-wider uppercase">{{ $title }}</p>
            <div class="absolute top-3.5 right-3.5 text-[9px] bg-white/20 backdrop-blur-md px-2 py-0.5 rounded-full font-bold border border-white/25">
                {{ $activeYear->name ?? '2026/2027' }}
            </div>
        </div>

        <!-- Live Session & Clock Status -->
        <div class="p-5 flex flex-col items-center text-center">
            
            <!-- Realtime Clock Widget -->
            <div class="flex items-center gap-2 mb-2" x-data="{ time: '' }" x-init="setInterval(() => { const now = new Date(); time = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB'; }, 1000)">
                <span class="w-2 h-2 rounded-full {{ $isCheckout ? 'bg-indigo-500' : 'bg-emerald-500' }} animate-ping"></span>
                <span class="text-xs font-black font-mono text-slate-800" x-text="time || '{{ $currentTime }}'">{{ $currentTime }}</span>
                <span class="text-[10px] text-slate-400 font-semibold">• {{ $currentDate }}</span>
            </div>

            <!-- Active Session Badge -->
            <div class="mb-3">
                @if($isCheckout)
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">
                        🌙 SESI PULANG SORE (CHECK-OUT)
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-50 text-emerald-850 border border-emerald-200">
                        ☀️ SESI MASUK PAGI (CHECK-IN)
                    </span>
                @endif
            </div>

            <h4 class="text-base font-black text-slate-850 tracking-tight">{{ $title }}</h4>
            <p class="text-xs text-slate-500 mt-0.5 max-w-xs">{{ $subtitle }}</p>

            <!-- High Definition QR Code Render Box -->
            <div class="mt-4 p-4 bg-[#F4F8FC] rounded-3xl border border-sky-100/80 flex flex-col items-center shadow-inner">
                <div class="p-3.5 bg-white rounded-2xl shadow-sm border border-slate-100">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(210)->margin(1)->generate($qrPayload) !!}
                </div>
                <div class="mt-3 flex items-center gap-1.5 text-[10px] font-bold text-slate-600">
                    <svg class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Scan menggunakan Kamera HP Siswa</span>
                </div>
            </div>

            <!-- Instruction Footer -->
            <p class="text-[10px] text-slate-400 mt-3 leading-relaxed max-w-xs">
                Siswa cukup membuka menu <strong>Scan Presensi</strong> di HP masing-masing dan mengarahkan kamera ke kode QR ini.
            </p>
        </div>
    </div>

    <!-- Action Buttons Row -->
    <div class="grid grid-cols-2 gap-2.5">
        <!-- Fullscreen Proyektor Button -->
        <button type="button" 
                onclick="toggleFullscreen()" 
                class="py-3 px-3 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white font-bold text-xs rounded-2xl shadow-md shadow-sky-500/20 flex items-center justify-center gap-1.5 transition active:scale-95 cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
            <span>Layar Penuh (Proyektor)</span>
        </button>

        <!-- Cetak Poster QR [AKTIF] -->
        <a href="{{ route('export.print-poster-qr', ['mode' => $mode, 'class_id' => $mode === 'class' ? $selectedClassId : null]) }}" 
           target="_blank"
           class="py-3 px-3 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-2xl border border-sky-100 flex items-center justify-center gap-1.5 shadow-xs transition active:scale-95 cursor-pointer">
            <svg class="w-4 h-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
            <span>Cetak Poster QR (A4)</span>
        </a>
    </div>

    <!-- Locked Features Row -->
    <div class="space-y-2">
        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Fitur Akan Datang:</p>

        <!-- Direct Pesan ke Ortu [LOCKED] -->
        <div class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between gap-3 cursor-not-allowed select-none">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                </div>
                <div class="text-left">
                    <p class="text-xs font-bold text-slate-500">Notifikasi Langsung ke Orang Tua</p>
                    <p class="text-[10px] text-slate-400">Kirim pesan WhatsApp/SMS otomatis ke wali siswa</p>
                </div>
            </div>
            <span class="text-[9px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200 shrink-0">Coming Soon</span>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleFullscreen() {
            const elem = document.getElementById('qr-poster-card');
            if (!document.fullscreenElement) {
                elem.requestFullscreen().catch(err => {
                    alert(`Gagal membuka mode layar penuh: ${err.message}`);
                });
            } else {
                document.exitFullscreen();
            }
        }
    </script>
    @endpush

</div>
