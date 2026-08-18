<div class="space-y-5" x-data="scannerComponent()">

    <!-- Scan Mode Selector Switcher (Masuk / Pulang) -->
    <div class="bg-white p-1.5 rounded-2xl border border-sky-100/90 shadow-sm flex items-center justify-between text-xs font-bold">
        <button type="button" 
                wire:click="$set('scanMode', 'auto')" 
                class="flex-1 py-2 rounded-xl transition cursor-pointer flex items-center justify-center gap-1.5 {{ $scanMode === 'auto' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500 hover:bg-slate-50' }}">
            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
            <span>Auto Sesi</span>
        </button>
        <button type="button" 
                wire:click="$set('scanMode', 'check_in')" 
                class="flex-1 py-2 rounded-xl transition cursor-pointer flex items-center justify-center gap-1.5 {{ $scanMode === 'check_in' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-500 hover:bg-slate-50' }}">
            <i data-lucide="sun" class="w-3.5 h-3.5"></i>
            <span>Masuk Pagi</span>
        </button>
        <button type="button" 
                wire:click="$set('scanMode', 'check_out')" 
                class="flex-1 py-2 rounded-xl transition cursor-pointer flex items-center justify-center gap-1.5 {{ $scanMode === 'check_out' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-500 hover:bg-slate-50' }}">
            <i data-lucide="moon" class="w-3.5 h-3.5"></i>
            <span>Pulang Sore</span>
        </button>
    </div>

    <!-- Scan Result Card Popup / Feedback -->
    @if($lastResult)
        <div class="soft-card p-4 border transition-all animate-bounce-short {{ $lastResult['success'] ? ($lastResult['type'] === 'check_out' ? 'border-blue-300 bg-blue-50/80 text-blue-950' : ($lastResult['status'] === 'hadir' ? 'border-emerald-300 bg-emerald-50/70 text-emerald-950' : 'border-amber-300 bg-amber-50/70 text-amber-950')) : 'border-rose-300 bg-rose-50/70 text-rose-950' }}">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-3">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 text-white shadow-md {{ $lastResult['success'] ? ($lastResult['type'] === 'check_out' ? 'bg-blue-600' : ($lastResult['status'] === 'hadir' ? 'bg-emerald-500' : 'bg-amber-500')) : 'bg-rose-500' }}">
                        @if($lastResult['success'])
                            <i data-lucide="check" class="w-7 h-7 stroke-[3]"></i>
                        @else
                            <i data-lucide="alert-triangle" class="w-7 h-7"></i>
                        @endif
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="text-sm font-black tracking-tight">
                                @if($lastResult['success'])
                                    {{ $lastResult['type'] === 'check_out' ? 'ABSENSI PULANG BERHASIL' : ($lastResult['status'] === 'hadir' ? 'ABSENSI MASUK: HADIR' : 'ABSENSI MASUK: TERLAMBAT') }}
                                @else
                                    PERINGATAN
                                @endif
                            </h4>
                            @if($lastResult['success'] && $lastResult['late_minutes'] > 0 && $lastResult['type'] !== 'check_out')
                                <span class="text-[10px] bg-amber-200 text-amber-900 font-bold px-2 py-0.5 rounded-full">
                                    +{{ $lastResult['late_minutes'] }} Menit
                                </span>
                            @endif
                        </div>

                        @if($lastResult['student'])
                            <p class="text-sm font-bold mt-0.5 text-slate-850">
                                {{ $lastResult['student']['name'] }}
                            </p>
                            <p class="text-xs text-slate-600">
                                Kelas: <strong class="text-slate-850">{{ $lastResult['student']['class'] }}</strong>
                                @if($lastResult['check_in'])
                                    • Masuk: <strong class="font-mono text-slate-850">{{ substr($lastResult['check_in'], 0, 5) }}</strong>
                                @endif
                                @if($lastResult['check_out'])
                                    • Pulang: <strong class="font-mono text-blue-700 font-black">{{ substr($lastResult['check_out'], 0, 5) }}</strong>
                                @endif
                            </p>
                        @endif

                        <p class="text-xs mt-1 font-medium opacity-90">
                            {{ $lastResult['message'] }}
                        </p>
                    </div>
                </div>

                <button wire:click="clearResult" class="p-1 text-slate-400 hover:text-slate-700 cursor-pointer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Camera Viewport Box (Soft Card Container) -->
    <div class="soft-card p-4 space-y-3 bg-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-850">
                    Scanner {{ $scanMode === 'check_out' ? 'Pulang' : ($scanMode === 'check_in' ? 'Masuk' : 'Aktif') }}
                </h3>
            </div>
            <span class="text-[10px] font-bold text-slate-500 bg-[#F4F8FC] px-2.5 py-1 rounded-xl border border-sky-100">
                @if($settings->allow_checkout)
                    Pulang: {{ substr($settings->check_out_start, 0, 5) }} – {{ substr($settings->check_out_end, 0, 5) }}
                @else
                    Batas Hadir: {{ substr($settings->on_time_until, 0, 5) }}
                @endif
            </span>
        </div>

        <!-- Interactive Camera Scanner Container -->
        <div class="relative w-full aspect-square rounded-3xl overflow-hidden bg-slate-950 flex flex-col items-center justify-center shadow-inner">
            
            <div id="reader" class="w-full h-full object-cover"></div>

            <!-- Visual Target Overlay Frame -->
            <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center p-8 z-20">
                <div class="w-56 h-56 border-2 border-sky-400/80 rounded-3xl relative shadow-[0_0_0_9999px_rgba(0,0,0,0.5)]">
                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-sky-400 rounded-tl-xl"></div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-sky-400 rounded-tr-xl"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-sky-400 rounded-bl-xl"></div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-sky-400 rounded-br-xl"></div>
                    <div class="w-full h-0.5 bg-gradient-to-r from-transparent via-sky-400 to-transparent shadow-[0_0_12px_#38bdf8] animate-pulse"></div>
                </div>
            </div>

            <!-- Camera Placeholder Button -->
            <div x-show="!cameraStarted" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-30 bg-slate-950">
                <div class="w-16 h-16 rounded-3xl bg-sky-500/20 border border-sky-500/30 flex items-center justify-center text-sky-400 mb-3">
                    <i data-lucide="camera" class="w-8 h-8"></i>
                </div>
                <h4 class="text-sm font-bold text-white">Nyalakan Kamera Scanner</h4>
                <p class="text-xs text-slate-400 mt-1 max-w-xs">Arahkan kamera ke QR Code pada kartu murid.</p>
                <button type="button" 
                        @click="startScanner()" 
                        class="mt-4 px-6 py-2.5 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white text-xs font-bold rounded-2xl shadow-lg shadow-sky-500/30 transition active:scale-95 cursor-pointer">
                    Nyalakan Kamera
                </button>
            </div>
        </div>

        <!-- Controls Bar below Camera -->
        <div class="flex items-center justify-between text-xs text-slate-400 pt-1">
            <button type="button" 
                    x-show="cameraStarted"
                    @click="switchCamera()" 
                    class="p-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-700 text-xs flex items-center gap-1.5 font-bold transition cursor-pointer">
                <i data-lucide="switch-camera" class="w-4 h-4 text-[#1E88E5]"></i>
                <span>Ganti Kamera</span>
            </button>
        </div>
    </div>

    <!-- Manual Fallback Input Soft Card -->
    <div class="soft-card p-4 space-y-2">
        <h4 class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Input Manual (Jika Gelap / Rusak)</h4>
        <form wire:submit="submitManual" class="flex gap-2">
            <input type="text" 
                   wire:model="manualInput" 
                   placeholder="Ketik NISN atau Token QR Murid..." 
                   class="flex-1 px-4 py-2.5 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
            <button type="submit" class="px-5 py-2.5 bg-[#1E88E5] hover:bg-[#1976D2] text-white text-xs font-bold rounded-2xl shadow-md shadow-sky-500/20 transition active:scale-95 cursor-pointer">
                Proses
            </button>
        </form>
    </div>

    <!-- Recent Scans Stream -->
    <div class="space-y-3">
        <h3 class="text-sm font-black text-slate-850 tracking-tight">Presensi Terkini Hari Ini</h3>

        <div class="space-y-2">
            @forelse($recentScans as $idx => $att)
                <div class="soft-card p-3.5 flex items-center justify-between transition">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-2xl bg-sky-50 text-[#1E88E5] font-black text-xs flex items-center justify-center border border-sky-100 shrink-0">
                            {{ $idx + 1 }}
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-slate-850">{{ $att->student->name ?? 'Murid' }}</h4>
                            <p class="text-[10px] text-slate-400">
                                {{ $att->student->schoolClass->name ?? '-' }} • 
                                <span class="font-mono text-slate-700">In: {{ substr($att->check_in, 0, 5) }}</span>
                                @if($att->check_out)
                                    • <span class="font-mono text-blue-600 font-bold">Out: {{ substr($att->check_out, 0, 5) }}</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        @if($att->check_out)
                            <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                Pulang
                            </span>
                        @else
                            <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full border {{ $att->status_badge }}">
                                {{ $att->status_label }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="soft-card p-6 text-center text-xs text-slate-400">
                    Belum ada data scan absensi hari ini.
                </div>
            @endforelse
        </div>
    </div>

</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
function scannerComponent() {
    return {
        cameraStarted: false,
        html5QrCode: null,
        currentFacingMode: "environment",
        lastScanTime: 0,

        init() {
            window.addEventListener('scan-processed', (event) => {
                const isSuccess = event.detail[0]?.success ?? event.detail?.success;
                this.playAudioFeedback(isSuccess);
            });
        },

        startScanner() {
            const config = { 
                fps: 15, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            this.html5QrCode = new Html5Qrcode("reader");
            this.html5QrCode.start(
                { facingMode: this.currentFacingMode },
                config,
                (decodedText, decodedResult) => {
                    const now = Date.now();
                    if (now - this.lastScanTime > 2500) {
                        this.lastScanTime = now;
                        @this.scanToken(decodedText);
                    }
                },
                (errorMessage) => {}
            ).then(() => {
                this.cameraStarted = true;
            }).catch(err => {
                console.error("Camera start error:", err);
                alert("Gagal mengakses kamera. Pastikan izin kamera aktif pada browser.");
            });
        },

        switchCamera() {
            if (!this.html5QrCode) return;
            this.currentFacingMode = this.currentFacingMode === "environment" ? "user" : "environment";
            this.html5QrCode.stop().then(() => {
                this.startScanner();
            });
        },

        playAudioFeedback(isSuccess) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);

                if (isSuccess) {
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    osc.frequency.setValueAtTime(1174, ctx.currentTime + 0.1);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.3);
                    if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
                } else {
                    osc.frequency.setValueAtTime(220, ctx.currentTime);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.4);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.4);
                    if (navigator.vibrate) navigator.vibrate(300);
                }
            } catch(e) {}
        }
    }
}
</script>
@endpush
