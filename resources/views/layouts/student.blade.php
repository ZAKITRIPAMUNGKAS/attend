@php
    $user = auth()->user();
    $student = $user->student;
    $currentRoute = request()->route()->getName();
@endphp
<!DOCTYPE html>
<html lang="id" class="min-h-screen bg-[#EBF2F7] sm:bg-[#DDE8F0]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#259BE5"> 
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SmartAbsensi">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #EEF5FC;
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
        }
        * { -webkit-tap-highlight-color: transparent; }
        .soft-card {
            background: #FFFFFF;
            border-radius: 24px;
            box-shadow: 0 10px 28px -6px rgba(15, 60, 100, 0.05);
            border: 1px solid rgba(220, 234, 245, 0.8);
        }
        .soft-card-interactive {
            background: #FFFFFF;
            border-radius: 24px;
            box-shadow: 0 10px 28px -6px rgba(15, 60, 100, 0.05);
            border: 1px solid rgba(220, 234, 245, 0.8);
            transition: all 0.25s ease;
        }
        .soft-card-interactive:active {
            transform: scale(0.97);
            box-shadow: 0 4px 12px -2px rgba(15, 60, 100, 0.04);
        }
    </style>
</head>
<body class="min-h-screen antialiased text-slate-800 selection:bg-sky-200 selection:text-sky-900 pb-12 sm:pb-16">

    <!-- PWA Install Banner Floating on Mobile -->
    <div id="pwa-install-banner" class="hidden fixed top-3 left-3 right-3 z-50 max-w-md mx-auto">
        <div class="bg-white/95 backdrop-blur-md p-3.5 rounded-2xl shadow-2xl border border-sky-200 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('icons/icon-72x72.png') }}" class="w-9 h-9 rounded-xl shadow-xs shrink-0" alt="Logo">
                <div>
                    <p class="text-xs font-black text-slate-800">Install SmartAbsensi</p>
                    <p class="text-[10px] text-slate-500 font-medium">Buka lebih cepat dari layar utama HP</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 shrink-0">
                <button id="pwa-install-btn" type="button" class="px-3 py-1.5 bg-[#1E88E5] hover:bg-[#1976D2] text-white text-xs font-extrabold rounded-xl shadow-xs transition active:scale-95 cursor-pointer">
                    Install
                </button>
                <button id="pwa-dismiss-btn" type="button" class="p-1 text-slate-400 hover:text-slate-600 cursor-pointer text-sm font-bold">&times;</button>
            </div>
        </div>
    </div>

    <!-- Mobile Device Simulator Container (clean app shell) -->
    <div class="w-full max-w-md mx-auto bg-[#F4F8FC] min-h-screen flex flex-col relative sm:shadow-[0_20px_60px_-15px_rgba(30,80,130,0.15)] sm:my-6 sm:rounded-[36px] sm:border-8 sm:border-white">
        
        <!-- Curved Sky Header (Inspired by Reference UI) -->
        <header class="relative bg-gradient-to-b from-[#2998EC] to-[#38A8F8] text-white pt-4 pb-12 px-6 rounded-b-[36px] shadow-sm overflow-hidden">
            <!-- Organic Soft Wave Shapes -->
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10 blur-xl pointer-events-none"></div>
            <div class="absolute top-1/2 left-0 w-32 h-32 rounded-full bg-sky-300/20 blur-lg pointer-events-none"></div>

            <!-- Top Row: Logo, School Name, & Logout -->
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white p-1.5 shadow-md shadow-sky-900/10 flex items-center justify-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo SMA IT" class="w-full h-full object-contain" />
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold tracking-tight text-white flex items-center gap-1.5 leading-tight">
                            SmartAbsensi
                        </h1>
                        <p class="text-[11px] text-sky-100 font-medium leading-none mt-0.5">
                            SMA IT INSAN KAMIL
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Keluar" class="w-9 h-9 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-md flex items-center justify-center text-white transition active:scale-90 cursor-pointer">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- User Tag Bar -->
            <div class="mt-4 flex items-center justify-between text-xs text-sky-100 relative z-10">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping"></span>
                    <span class="font-medium text-white">{{ $student->name ?? $user->name }}</span>
                </div>
                <span class="bg-white/20 backdrop-blur-md text-white font-bold text-[10px] px-2.5 py-0.5 rounded-full border border-white/25 uppercase">
                    {{ $student->schoolClass->name ?? 'Kelas Murid' }}
                </span>
            </div>

            <!-- Warning Banner: Default Password -->
            @if($user->is_default_password)
                <div class="mt-3 bg-amber-400 text-amber-950 p-2.5 rounded-2xl text-xs font-semibold flex items-center justify-between shadow-md relative z-10">
                    <div class="flex items-center gap-2">
                        <i data-lucide="shield-alert" class="w-4 h-4 shrink-0 text-amber-900"></i>
                        <span class="text-[11px]">Password masih default!</span>
                    </div>
                    <a href="{{ route('student.profile') }}#ganti-password" class="bg-amber-900 text-white px-2.5 py-1 rounded-xl text-[10px] hover:bg-black font-bold">
                        Ganti
                    </a>
                </div>
            @endif
        </header>

        <!-- Main Page Content Area (with pb-36 for safe scrolling above floating bottom nav) -->
        <main class="flex-1 px-5 -mt-6 pb-36 relative space-y-5">
            {{ $slot }}
        </main>

        <!-- Modern Clean Floating Bottom Navigation Bar (Docked gracefully at bottom) -->
        <nav class="fixed bottom-0 left-0 right-0 z-30 pointer-events-none">
            <div class="w-full max-w-md mx-auto px-4 pb-3 pointer-events-auto">
                <div class="bg-white/95 backdrop-blur-xl border border-sky-100/80 rounded-3xl shadow-[0_12px_35px_-5px_rgba(20,60,110,0.12)] flex items-center justify-between px-3 h-18 relative">
                    
                    <!-- 1. Beranda -->
                    <a href="{{ route('student.dashboard') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ $currentRoute === 'student.dashboard' ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <svg class="w-5 h-5 {{ $currentRoute === 'student.dashboard' ? 'stroke-[2.5]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        </div>
                        <span class="text-[9px] font-bold mt-0.5 {{ $currentRoute === 'student.dashboard' ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Beranda
                        </span>
                    </a>

                    <!-- 2. Riwayat -->
                    <a href="{{ route('student.history') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ $currentRoute === 'student.history' ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <svg class="w-5 h-5 {{ $currentRoute === 'student.history' ? 'stroke-[2.5]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                        </div>
                        <span class="text-[9px] font-bold mt-0.5 {{ $currentRoute === 'student.history' ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Riwayat
                        </span>
                    </a>

                    <!-- 3. Center Elevated Scan QR Action (Camera) -->
                    <div class="flex-1 flex flex-col items-center justify-center -mt-6">
                        <a href="{{ route('student.scan') }}" 
                           class="w-14 h-14 rounded-full bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] text-white flex items-center justify-center shadow-[0_10px_25px_rgba(30,136,229,0.45)] border-4 border-[#F4F8FC] active:scale-90 transition-transform duration-200 hover:-translate-y-0.5">
                            <svg class="w-6 h-6 stroke-[2.5]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                        </a>
                        <span class="text-[9px] font-extrabold text-[#1E88E5] mt-1">Scan QR</span>
                    </div>

                    <!-- 4. Kartu QR Saya -->
                    <a href="{{ route('student.qrcode') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ $currentRoute === 'student.qrcode' ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <svg class="w-5 h-5 {{ $currentRoute === 'student.qrcode' ? 'stroke-[2.5]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg>
                        </div>
                        <span class="text-[9px] font-bold mt-0.5 {{ $currentRoute === 'student.qrcode' ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Kartu QR
                        </span>
                    </a>

                    <!-- 5. Izin -->
                    <a href="{{ route('student.permission') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ $currentRoute === 'student.permission' ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <svg class="w-5 h-5 {{ $currentRoute === 'student.permission' ? 'stroke-[2.5]' : '' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </div>
                        <span class="text-[9px] font-bold mt-0.5 {{ $currentRoute === 'student.permission' ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Izin
                        </span>
                    </a>

                </div>
            </div>
        </nav>

    </div>

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
        document.addEventListener('livewire:navigated', () => {
            if (window.lucide) lucide.createIcons();
        });
        window.addEventListener('icons-updated', () => {
            setTimeout(() => { if (window.lucide) lucide.createIcons(); }, 50);
        });
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((reg) => console.log('PWA Service Worker registered:', reg.scope))
                    .catch((err) => console.warn('PWA registration failed:', err));
            });
        }
        let deferredPrompt = null;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const banner = document.getElementById('pwa-install-banner');
            if (banner && !sessionStorage.getItem('pwa-prompt-dismissed')) {
                banner.classList.remove('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const installBtn = document.getElementById('pwa-install-btn');
            const dismissBtn = document.getElementById('pwa-dismiss-btn');
            const banner = document.getElementById('pwa-install-banner');

            if (installBtn) {
                installBtn.addEventListener('click', async () => {
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        const { outcome } = await deferredPrompt.userChoice;
                        deferredPrompt = null;
                        if (banner) banner.classList.add('hidden');
                    }
                });
            }
            if (dismissBtn) {
                dismissBtn.addEventListener('click', () => {
                    if (banner) banner.classList.add('hidden');
                    sessionStorage.setItem('pwa-prompt-dismissed', '1');
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
