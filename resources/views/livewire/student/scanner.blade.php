<div class="space-y-4" x-data="studentScannerComponent()">

    <!-- Header Card -->
    <div class="soft-card p-4 space-y-3 bg-white">
        <div>
            <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                Presensi Mandiri Murid
            </span>
            <h2 class="text-base font-black text-slate-850 tracking-tight mt-1">Scan QR Presensi Sekolah</h2>
            <p class="text-[11px] text-slate-400">Arahkan kamera ke QR General (Lobi/Gerbang) atau QR Kelas Anda.</p>
        </div>

        <!-- Today Student Attendance Status Pill -->
        @if($todayAttendance)
            <div class="p-3 rounded-2xl bg-[#F4F8FC] border border-sky-100 flex items-center justify-between text-xs">
                <div>
                    <span class="text-[10px] text-slate-400 block font-semibold">Status Hari Ini:</span>
                    <span class="font-extrabold uppercase text-xs {{ $todayAttendance->status === 'hadir' ? 'text-emerald-700' : 'text-amber-700' }}">
                        {{ $todayAttendance->status }} (Masuk: {{ substr($todayAttendance->check_in, 0, 5) }})
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400 block font-semibold">Absen Pulang:</span>
                    <span class="font-bold font-mono text-xs {{ $todayAttendance->check_out ? 'text-blue-700' : 'text-slate-400' }}">
                        {{ $todayAttendance->check_out ? substr($todayAttendance->check_out, 0, 5) . ' WIB' : 'Belum Pulang' }}
                    </span>
                </div>
            </div>
        @endif
    </div>

    <!-- Scan Result Card Popup / Feedback -->
    @if($lastResult)
        <div class="soft-card p-4 border transition-all animate-bounce-short {{ $lastResult['success'] ? (($lastResult['type'] ?? '') === 'check_out' ? 'border-blue-300 bg-blue-50/90 text-blue-950' : ($lastResult['status'] === 'hadir' ? 'border-emerald-300 bg-emerald-50/90 text-emerald-950' : 'border-amber-300 bg-amber-50/90 text-amber-950')) : 'border-rose-300 bg-rose-50/90 text-rose-950' }}">
            <div class="flex items-start justify-between">
                <div class="flex items-start gap-3">
                    <div class="w-11 h-11 rounded-2xl flex items-center justify-center shrink-0 text-white shadow-md {{ $lastResult['success'] ? (($lastResult['type'] ?? '') === 'check_out' ? 'bg-blue-600' : ($lastResult['status'] === 'hadir' ? 'bg-emerald-500' : 'bg-amber-500')) : 'bg-rose-500' }}">
                        @if($lastResult['success'])
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-black tracking-tight">
                            @if($lastResult['success'])
                                {{ ($lastResult['type'] ?? '') === 'check_out' ? 'ABSENSI PULANG BERHASIL' : ($lastResult['status'] === 'hadir' ? 'ABSENSI MASUK: HADIR' : 'ABSENSI MASUK: TERLAMBAT') }}
                            @else
                                GAGAL ABSEN
                            @endif
                        </h4>
                        <p class="text-xs font-bold mt-0.5 {{ $lastResult['success'] ? 'text-slate-800' : 'text-rose-800' }}">
                            {{ $lastResult['message'] }}
                        </p>
                    </div>
                </div>
                <button type="button" wire:click="$set('lastResult', null)" class="text-slate-400 hover:text-slate-700 font-bold text-base">&times;</button>
            </div>
        </div>
    @endif

    <!-- Live Scanner Viewport -->
    <div class="soft-card p-4 space-y-3 bg-white">
        <!-- Scanner Window with Target Frame -->
        <div class="relative w-full aspect-square bg-slate-950 rounded-3xl overflow-hidden shadow-inner flex items-center justify-center border-2 border-slate-800">
            
            <div id="reader" class="w-full h-full object-cover"></div>

            <!-- Visual Overlay Reticle -->
            <div class="absolute inset-0 pointer-events-none flex items-center justify-center p-8">
                <div class="w-56 h-56 border-2 border-sky-400/80 rounded-3xl relative shadow-[0_0_0_9999px_rgba(15,23,42,0.45)]">
                    <!-- Corner Markers -->
                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-[#1E88E5] rounded-tl-xl"></div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-[#1E88E5] rounded-tr-xl"></div>
                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-[#1E88E5] rounded-bl-xl"></div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-[#1E88E5] rounded-br-xl"></div>

                    <!-- Scanning Laser Animation Line -->
                    <div class="absolute left-2 right-2 h-0.5 bg-gradient-to-r from-sky-400 via-white to-sky-400 shadow-[0_0_8px_#38bdf8] animate-scanner-laser"></div>
                </div>
            </div>

            <!-- Camera Switching / Status overlay -->
            <div class="absolute top-3 left-3 right-3 flex items-center justify-between text-[11px] text-white z-10">
                <span class="bg-black/60 backdrop-blur-md px-3 py-1 rounded-full font-semibold flex items-center gap-1.5 border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span x-text="isScanning ? 'Kamera Aktif' : 'Menyiapkan Kamera...'"></span>
                </span>

                <button type="button" 
                        @click="switchCamera()" 
                        class="p-2 rounded-full bg-black/60 backdrop-blur-md text-white hover:bg-black/80 transition active:scale-90 border border-white/10 cursor-pointer"
                        title="Ganti Kamera">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 19H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h5"/><path d="M13 5h7a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-5"/><circle cx="12" cy="12" r="3"/><path d="m18 22-3-3 3-3"/><path d="m6 2 3 3-3 3"/></svg>
                </button>
            </div>
        </div>

        <p class="text-center text-[11px] text-slate-400">
            Arahkan kamera ke QR Presensi di lobi/kelas untuk absensi otomatis.
        </p>
    </div>

    <!-- Audio Element for Beep Feedback -->
    <audio id="beep-audio" preload="auto">
        <source src="data:audio/wav;base64,UklGRl9vT19XQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YU..." type="audio/wav">
    </audio>

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function studentScannerComponent() {
            return {
                html5QrCode: null,
                isScanning: false,
                currentFacingMode: "environment",
                lastScannedCode: null,
                lastScanTimestamp: 0,

                init() {
                    this.$nextTick(() => {
                        this.startScanner();
                    });

                    window.addEventListener('scan-success', (e) => {
                        this.playAudioFeedback();
                        if (navigator.vibrate) navigator.vibrate([100, 50, 100]);
                    });
                },

                playAudioFeedback() {
                    try {
                        const ctx = new (window.AudioContext || window.webkitAudioContext)();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.type = "sine";
                        osc.frequency.setValueAtTime(880, ctx.currentTime);
                        gain.gain.setValueAtTime(0.3, ctx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.18);
                        osc.start(ctx.currentTime);
                        osc.stop(ctx.currentTime + 0.18);
                    } catch(e) {}
                },

                startScanner() {
                    const readerElement = document.getElementById("reader");
                    if (!readerElement) return;

                    this.html5QrCode = new Html5Qrcode("reader");
                    const config = {
                        fps: 15,
                        qrbox: { width: 230, height: 230 },
                        aspectRatio: 1.0
                    };

                    this.html5QrCode.start(
                        { facingMode: this.currentFacingMode },
                        config,
                        (decodedText) => {
                            const now = Date.now();
                            if (decodedText === this.lastScannedCode && (now - this.lastScanTimestamp) < 3000) {
                                return;
                            }
                            this.lastScannedCode = decodedText;
                            this.lastScanTimestamp = now;

                            @this.call('handleScan', decodedText);
                        },
                        (errorMessage) => {}
                    ).then(() => {
                        this.isScanning = true;
                    }).catch(err => {
                        this.isScanning = false;
                    });
                },

                switchCamera() {
                    if (this.html5QrCode && this.isScanning) {
                        this.html5QrCode.stop().then(() => {
                            this.currentFacingMode = this.currentFacingMode === "environment" ? "user" : "environment";
                            this.startScanner();
                        });
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes scannerLaser {
            0% { top: 10%; opacity: 0; }
            15% { opacity: 1; }
            85% { opacity: 1; }
            100% { top: 88%; opacity: 0; }
        }
        .animate-scanner-laser {
            animation: scannerLaser 2s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        }
    </style>
    @endpush

</div>
