<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poster QR Presensi — {{ $title }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #115d96;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 24px 0 32px;
        }

        /* ── Toolbar (Screen only) ─────────────────────────────── */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 210mm;
            max-width: 95vw;
            margin-bottom: 20px;
        }

        .toolbar a {
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: color .2s;
        }
        .toolbar a:hover { color: #fff; }

        .btn-print {
            background: rgba(255,255,255,0.2);
            border: 1.5px solid rgba(255,255,255,0.4);
            color: #fff;
            font-family: inherit;
            font-size: 13px;
            font-weight: 800;
            padding: 9px 22px;
            border-radius: 50px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(8px);
            transition: background .2s;
        }
        .btn-print:hover { background: rgba(255,255,255,0.3); }

        /* ── A4 Poster Sheet (Exact Monobank Aesthetic) ───────── */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        .poster {
            width: 210mm;
            height: 297mm;
            background: radial-gradient(circle at 50% 32%, #1e87c8 0%, #146caa 45%, #0d4e82 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            padding: 56px 40px 48px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,0.35);
        }

        /* ── Top Header ────────────────────────────────────────── */
        .poster-header {
            text-align: center;
            z-index: 2;
        }

        .poster-title {
            font-size: 33px;
            font-weight: 900;
            color: #ffffff;
            line-height: 1.2;
            letter-spacing: -0.3px;
            text-shadow: 0 2px 10px rgba(0, 30, 60, 0.2);
        }

        .poster-subtitle {
            font-size: 16px;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
            margin-top: 8px;
            letter-spacing: 0.2px;
        }

        /* ── Mascot & QR Card Stage ────────────────────────────── */
        .mascot-stage {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 20px 0;
            z-index: 2;
        }

        /* Mascot Top (Education Owl with Toga Cap & Glasses) */
        .mascot-top {
            position: absolute;
            top: -80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
            filter: drop-shadow(0 -5px 8px rgba(0,0,0,0.2));
        }

        /* Mascot Left Wing/Paw (gripping top-left corner) */
        .mascot-paw-left {
            position: absolute;
            top: 24px;
            left: -22px;
            z-index: 10;
            filter: drop-shadow(2px 4px 6px rgba(0,0,0,0.2));
        }

        /* Mascot Right Wing/Paw (gripping bottom-right corner) */
        .mascot-paw-right {
            position: absolute;
            bottom: 24px;
            right: -22px;
            z-index: 10;
            filter: drop-shadow(-2px 4px 6px rgba(0,0,0,0.2));
        }

        /* The Main Rounded QR Card */
        .qr-card {
            position: relative;
            background: #ffffff;
            border: 5px solid #000000;
            border-radius: 54px;
            padding: 34px;
            box-shadow: 0 35px 70px rgba(3, 30, 60, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .qr-card-inner {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-card-inner svg {
            display: block;
            width: 300px !important;
            height: 300px !important;
        }

        /* Center Icon Badge inside QR (Monobank center circle with School Logo) */
        .qr-center-badge {
            position: absolute;
            width: 64px;
            height: 64px;
            background: #ffffff;
            border: 4px solid #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(0,0,0,0.18);
            z-index: 5;
            overflow: hidden;
            padding: 5px;
        }

        .qr-center-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* ── Bottom Branding (Monobank Style) ──────────────────── */
        .poster-footer {
            text-align: center;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .school-logo-emblem {
            width: 52px;
            height: 52px;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.35);
            border-radius: 16px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(6px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-bottom: 2px;
        }

        .school-logo-emblem img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-title {
            font-size: 32px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .brand-sub {
            font-size: 13px;
            font-weight: 700;
            color: rgba(255,255,255,0.85);
            letter-spacing: 0.5px;
        }

        .brand-info {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255,255,255,0.7);
            margin-top: 6px;
            line-height: 1.4;
        }

        /* ── Print Media Optimization ──────────────────────────── */
        @media print {
            body {
                background: #0d4e82 !important;
                padding: 0 !important;
                min-height: unset;
            }
            .toolbar { display: none !important; }
            .poster {
                width: 210mm !important;
                height: 297mm !important;
                margin: 0 !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>

    <!-- Action Bar (hidden on print) -->
    <div class="toolbar">
        <a href="javascript:history.back()">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="m15 18-6-6 6-6"/>
            </svg>
            Kembali
        </a>
        <button class="btn-print" onclick="window.print()">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <polyline points="6 9 6 2 18 2 18 9"/>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                <rect width="12" height="8" x="6" y="14"/>
            </svg>
            Cetak A4
        </button>
    </div>

    <!-- ═══ POSTER A4 ═══ -->
    <div class="poster">

        <!-- Top Title & Subtitle -->
        <div class="poster-header">
            <h1 class="poster-title">{{ $title }}</h1>
            <p class="poster-subtitle">{{ $subtitle }}</p>
        </div>

        <!-- Center Mascot & QR Card Stage -->
        <div class="mascot-stage">
            
            <!-- Education Mascot: Wisdom Owl with Toga & Glasses (Peeking from behind) -->
            <svg class="mascot-top" viewBox="0 0 260 110" width="260" height="110">
                <!-- Owl Head Base -->
                <ellipse cx="130" cy="108" rx="72" ry="42" fill="#ffffff" stroke="#000000" stroke-width="5" stroke-linejoin="round"/>
                
                <!-- Ear Tuft Left -->
                <path d="M 68 85 L 48 50 L 80 68 Z" fill="#ffffff" stroke="#000000" stroke-width="4.5" stroke-linejoin="round" stroke-linecap="round"/>
                <path d="M 64 78 L 52 56 L 74 68 Z" fill="#0f172a"/>
                
                <!-- Ear Tuft Right -->
                <path d="M 192 85 L 212 50 L 180 68 Z" fill="#ffffff" stroke="#000000" stroke-width="4.5" stroke-linejoin="round" stroke-linecap="round"/>
                <path d="M 196 78 L 208 56 L 186 68 Z" fill="#0f172a"/>

                <!-- Cute Round Glasses Frame -->
                <!-- Left Eye -->
                <circle cx="102" cy="86" r="20" fill="#ffffff" stroke="#000000" stroke-width="4.5"/>
                <circle cx="102" cy="86" r="10" fill="#0d4e82"/>
                <circle cx="106" cy="82" r="4" fill="#ffffff"/>
                <circle cx="100" cy="88" r="1.5" fill="#ffffff"/>

                <!-- Right Eye -->
                <circle cx="158" cy="86" r="20" fill="#ffffff" stroke="#000000" stroke-width="4.5"/>
                <circle cx="158" cy="86" r="10" fill="#0d4e82"/>
                <circle cx="162" cy="82" r="4" fill="#ffffff"/>
                <circle cx="156" cy="88" r="1.5" fill="#ffffff"/>

                <!-- Glasses Bridge -->
                <path d="M 122 84 C 126 80, 134 80, 138 84" fill="none" stroke="#000000" stroke-width="4" stroke-linecap="round"/>

                <!-- Beak -->
                <polygon points="124,93 136,93 130,104" fill="#f59e0b" stroke="#000000" stroke-width="3.5" stroke-linejoin="round"/>

                <!-- Graduation Toga Cap (Mortarboard) -->
                <!-- Skullcap Base -->
                <path d="M 98 48 C 98 34, 162 34, 162 48 Z" fill="#1e293b" stroke="#000000" stroke-width="4.5" stroke-linejoin="round"/>
                
                <!-- Diamond Mortarboard Top -->
                <polygon points="130,8 215,30 130,50 45,30" fill="#0f172a" stroke="#000000" stroke-width="5.5" stroke-linejoin="round"/>
                <polygon points="130,12 206,30 130,46 54,30" fill="#1e293b"/>
                
                <!-- Button on Cap -->
                <circle cx="130" cy="30" r="5.5" fill="#fbbf24" stroke="#000000" stroke-width="2.5"/>
                
                <!-- Gold Tassel Hanging Down -->
                <path d="M 130 30 C 160 32, 192 44, 196 62" fill="none" stroke="#f59e0b" stroke-width="4" stroke-linecap="round"/>
                <!-- Tassel Brush -->
                <polygon points="191,62 201,62 198,78 194,78" fill="#fbbf24" stroke="#000000" stroke-width="2.5" stroke-linejoin="round"/>
            </svg>

            <!-- Mascot Left Wing Grip (holding top-left corner) -->
            <svg class="mascot-paw-left" viewBox="0 0 70 80" width="70" height="80">
                <path d="M 8 20 C 8 10, 20 4, 32 10 C 44 4, 56 8, 58 20 C 66 25, 64 42, 54 48 C 60 54, 56 68, 44 68 C 30 68, 18 56, 12 44 C 8 36, 8 28, 8 20 Z" 
                      fill="#ffffff" stroke="#000000" stroke-width="5" stroke-linejoin="round" stroke-linecap="round" />
                <path d="M 30 12 C 30 24, 32 32, 34 38" fill="none" stroke="#000000" stroke-width="4" stroke-linecap="round"/>
                <path d="M 48 20 C 46 28, 44 36, 42 44" fill="none" stroke="#000000" stroke-width="4" stroke-linecap="round"/>
            </svg>

            <!-- Mascot Right Wing Grip (holding bottom-right corner) -->
            <svg class="mascot-paw-right" viewBox="0 0 70 80" width="70" height="80">
                <path d="M 62 20 C 62 10, 50 4, 38 10 C 26 4, 14 8, 12 20 C 4 25, 6 42, 16 48 C 10 54, 14 68, 26 68 C 40 68, 52 56, 58 44 C 62 36, 62 28, 62 20 Z" 
                      fill="#ffffff" stroke="#000000" stroke-width="5" stroke-linejoin="round" stroke-linecap="round" />
                <path d="M 40 12 C 40 24, 38 32, 36 38" fill="none" stroke="#000000" stroke-width="4" stroke-linecap="round"/>
                <path d="M 22 20 C 24 28, 26 36, 28 44" fill="none" stroke="#000000" stroke-width="4" stroke-linecap="round"/>
            </svg>

            <!-- White Thick-Border Card -->
            <div class="qr-card">
                <div class="qr-card-inner">
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->errorCorrection('M')->margin(0)->generate($qrPayload) !!}
                    
                    <!-- Center Badge Icon with Official School Logo -->
                    <div class="qr-center-badge">
                        <img src="{{ asset('logo.png') }}" alt="Logo SMA IT">
                    </div>
                </div>
            </div>

        </div><!-- end .mascot-stage -->

        <!-- Bottom Monobank-style Footer with School Logo -->
        <div class="poster-footer">
            <div class="school-logo-emblem">
                <img src="{{ asset('logo.png') }}" alt="Logo SMA IT">
            </div>
            <div class="brand-title">smartabsensi</div>
            <div class="brand-sub">SMA ISLAM TERPADU</div>
            <div class="brand-info">
                SISTEM PRESENSI DIGITAL RESMI<br>
                Tahun Ajaran {{ $activeYear->name ?? '2026/2027' }} &bull; {{ $currentDate }}
            </div>
        </div>

    </div><!-- end .poster -->

    <script>
        window.addEventListener('load', () => {
            setTimeout(() => window.print(), 350);
        });
    </script>
</body>
</html>
