<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu QR Siswa — SMA Islam Terpadu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&family=JetBrains+Mono:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        @page {
            size: A4 portrait;
            margin: 8mm;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .page-container {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .card-item {
                break-inside: avoid !important;
                page-break-inside: avoid !important;
                box-shadow: none !important;
            }
            .print-grid {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 6mm !important;
            }
        }
        .crop-guide {
            border: 1px dashed #cbd5e1;
        }
    </style>
</head>
<body class="bg-[#F1F5F9] p-6 text-slate-800 antialiased min-h-screen">

    <!-- Top Floating Toolbar (Hidden during print) -->
    <header class="no-print max-w-4xl mx-auto mb-6 p-4 bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-wrap items-center justify-between gap-4 sticky top-4 z-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-sky-50 border border-sky-100 p-1 flex items-center justify-center shrink-0">
                <img src="{{ asset('logo.png') }}" alt="Logo SMA IT" class="w-full h-full object-contain" />
            </div>
            <div>
                <h1 class="text-sm font-black text-slate-850">Cetak Kartu QR Identitas Siswa</h1>
                <p class="text-xs text-slate-500">
                    Total: <strong class="text-slate-800">{{ count($students) }} Siswa</strong> 
                    {{ $schoolClass ? "• Rombel {$schoolClass->name}" : '• Seluruh Rombel' }}
                    (A4: 6-8 Kartu per lembar)
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.history.back()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition cursor-pointer">
                &larr; Kembali
            </button>
            <button onclick="window.print()" class="px-5 py-2 bg-[#1E88E5] hover:bg-[#1976D2] text-white font-black text-xs rounded-xl shadow-md shadow-sky-500/20 flex items-center gap-1.5 transition cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                <span>Cetak / Simpan PDF (A4)</span>
            </button>
        </div>
    </header>

    <!-- Cards Grid (2 Columns, neatly proportional for A4) -->
    <main class="page-container max-w-4xl mx-auto">
        <div class="print-grid grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($students as $s)
                <div class="card-item bg-white rounded-2xl overflow-hidden shadow-xs border border-slate-300 relative flex flex-col justify-between" style="min-height: 255px; max-height: 275px;">
                    
                    <!-- Header Bar -->
                    <div class="bg-gradient-to-r from-[#1565C0] via-[#1E88E5] to-[#42A5F5] px-3.5 py-2.5 text-white flex items-center justify-between border-b border-sky-300/30">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-white p-0.5 shadow-xs flex items-center justify-center shrink-0">
                                <img src="{{ asset('logo.png') }}" alt="Logo SMA IT" class="w-full h-full object-contain" />
                            </div>
                            <div>
                                <h3 class="text-[11px] font-black tracking-wider uppercase leading-none">SMA ISLAM TERPADU</h3>
                                <p class="text-[8px] text-sky-100 font-semibold tracking-wider leading-tight mt-0.5">KARTU PRESENSI DIGITAL SISWA</p>
                            </div>
                        </div>
                        <span class="text-[8px] font-extrabold bg-white/20 px-2 py-0.5 rounded-md backdrop-blur-xs border border-white/20 uppercase tracking-wider">
                            {{ $s->academicYear->name ?? '2026/2027' }}
                        </span>
                    </div>

                    <!-- Card Body (Left Info + Right Crisp QR Code) -->
                    <div class="p-3.5 flex items-center justify-between gap-3 flex-1 bg-white">
                        
                        <!-- Left Details -->
                        <div class="flex-1 min-w-0 pr-1 space-y-1.5">
                            <div>
                                <span class="text-[8px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-100 inline-block">
                                    {{ $s->schoolClass->name ?? 'Siswa' }}
                                </span>
                                <h4 class="text-[13px] font-black text-slate-900 truncate mt-1 leading-tight" title="{{ $s->name }}">
                                    {{ $s->name }}
                                </h4>
                            </div>

                            <div class="space-y-0.5 text-[10px] text-slate-600 bg-slate-50 p-2 rounded-xl border border-slate-100">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 font-medium">NISN:</span>
                                    <span class="font-mono font-bold text-slate-800">{{ $s->nisn }}</span>
                                </div>
                                @if($s->nis)
                                    <div class="flex items-center justify-between">
                                        <span class="text-slate-400 font-medium">NIS:</span>
                                        <span class="font-mono font-semibold text-slate-700">{{ $s->nis }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-400 font-medium">Tingkat:</span>
                                    <span class="font-bold text-slate-800">Kelas {{ $s->schoolClass->grade ?? '-' }}</span>
                                </div>
                            </div>

                            <p class="text-[8px] text-slate-400 leading-tight">
                                Tunjukkan QR ke kamera scanner untuk absensi harian.
                            </p>
                        </div>

                        <!-- Right QR Code (High Contrast White Box) -->
                        <div class="shrink-0 flex flex-col items-center justify-center p-2 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="p-1 bg-white rounded-lg shadow-2xs">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(92)->margin(0)->generate($s->qr_token) !!}
                            </div>
                            <span class="text-[7.5px] font-mono font-bold text-slate-400 mt-1 uppercase tracking-wider">
                                SMARTABSENSI
                            </span>
                        </div>
                    </div>

                    <!-- Footer / Security Strip -->
                    <div class="bg-slate-50 border-t border-slate-200 px-3.5 py-1.5 flex items-center justify-between text-[8px] text-slate-400">
                        <span class="truncate">Wali: {{ $s->parent_name ? Str::limit($s->parent_name, 18) : '-' }}</span>
                        <span class="font-semibold text-slate-500">Wajib Dibawa Setiap Hari</span>
                    </div>

                </div>
            @empty
                <div class="col-span-2 p-12 bg-white rounded-2xl text-center text-slate-400 text-xs">
                    Tidak ada data siswa untuk dicetak.
                </div>
            @endforelse
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Auto open Print / Save as PDF dialog
            setTimeout(() => {
                window.print();
            }, 350);
        });
    </script>
</body>
</html>
