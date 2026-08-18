<div class="space-y-5">

    <!-- Print-only CSS -->
    <style>
        @media print {
            nav, header, .no-print, button {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            #student-badge {
                max-width: 320px !important;
                margin: 20px auto !important;
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                page-break-inside: avoid !important;
            }
        }
    </style>

    <!-- Alert message -->
    @if($message)
        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-sm no-print">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ $message }}</span>
            </div>
            <button wire:click="$set('message', '')" class="text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- ID Card (Clean Modern Soft Card) -->
    <div id="student-badge" class="soft-card overflow-hidden bg-white border border-sky-100 rounded-[32px] shadow-sm">
        <!-- Card Top Bar with Logo & Branding -->
        <div class="bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] pt-6 pb-12 px-5 text-white text-center relative">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white p-1.5 shadow-md shadow-sky-900/15 mb-2">
                <img src="{{ asset('logo.png') }}" alt="Logo SMA IT" class="w-full h-full object-contain" />
            </div>
            <h3 class="text-sm font-black tracking-wide uppercase">SMA ISLAM TERPADU</h3>
            <p class="text-[10px] text-sky-100 font-bold tracking-wider">KARTU DIGITAL IDENTITAS SISWA</p>
            <div class="absolute top-4 right-4 text-[9px] bg-white/20 backdrop-blur-md px-2.5 py-0.5 rounded-full font-bold border border-white/25">
                {{ $student->academicYear->name ?? '2026/2027' }}
            </div>
        </div>

        <!-- Student Info & High Definition QR Code -->
        <div class="px-6 pb-6 pt-0 flex flex-col items-center text-center relative">
            <!-- Student Avatar Squircle with Border -->
            <div class="w-16 h-16 rounded-2xl {{ $student->gender === 'P' ? 'bg-gradient-to-tr from-rose-500 to-pink-400 shadow-rose-500/25' : 'bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] shadow-sky-500/25' }} text-white flex items-center justify-center text-lg font-black shadow-lg border-4 border-white -mt-8 mb-2.5 relative z-10 shrink-0">
                {{ strtoupper(substr($student->name, 0, 2)) }}
            </div>

            <h4 class="text-base font-black text-slate-850 tracking-tight leading-snug">{{ $student->name }}</h4>
            <p class="text-xs font-bold text-[#1E88E5] mt-0.5">{{ $student->schoolClass->name ?? '-' }}</p>

            <div class="flex flex-wrap items-center justify-center gap-2 mt-2.5 text-xs">
                <span class="bg-sky-50 text-sky-800 border border-sky-100 px-2.5 py-1 rounded-xl font-mono text-[11px] font-bold">
                    NISN: {{ $student->nisn }}
                </span>
                @if($student->nis)
                    <span class="bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-1 rounded-xl font-mono text-[11px] font-bold">
                        NIS: {{ $student->nis }}
                    </span>
                @endif
            </div>

            <!-- Soft QR Box -->
            <div class="mt-5 p-4 bg-[#F4F8FC] rounded-3xl border border-sky-100 flex flex-col items-center shadow-inner w-full max-w-[280px]">
                <div class="p-3 bg-white rounded-2xl shadow-xs border border-slate-100">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(185)->margin(0)->generate($student->qr_token) !!}
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span class="text-[11px] font-bold text-slate-600">QR Code Aktif & Siap Discan</span>
                </div>
            </div>

            <p class="text-[11px] text-slate-400 mt-4 leading-relaxed max-w-xs">
                QR ini menyimpan token terenkripsi. Tunjukkan ke kamera perangkat Guru saat absensi.
            </p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-2.5 no-print">
        <!-- Cetak ID Card [LOCKED - Coming Soon] -->
        <div class="w-full py-3.5 px-4 bg-slate-100 text-slate-400 text-xs font-bold rounded-2xl flex items-center justify-between gap-2 border border-slate-200 cursor-not-allowed select-none">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                <span>Cetak / Simpan Kartu</span>
            </div>
            <span class="text-[9px] font-extrabold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200 shrink-0">Coming Soon</span>
        </div>

        <button type="button" 
                wire:click="regenerateToken"
                wire:confirm="Apakah Anda yakin ingin memperbarui QR Code? Token QR lama tidak akan dapat digunakan lagi."
                class="w-full py-3.5 px-4 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-bold rounded-2xl flex items-center justify-center gap-2 transition active:scale-98 cursor-pointer">
            <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
            <span>Perbarui QR (Jika Bocor)</span>
        </button>
    </div>

</div>
