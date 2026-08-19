@php
    $user = auth()->user();
    $teacher = $user->teacher;
    $currentRoute = request()->route()->getName();
    $classIds = $teacher ? $teacher->homeroomClasses->pluck('id')->toArray() : [];
    $pendingPermCount = \App\Models\PermissionRequest::where('status', 'menunggu')
        ->whereHas('student', function ($q) use ($classIds) {
            if (!empty($classIds)) {
                $q->whereIn('class_id', $classIds);
            } else {
                $q->whereRaw('1 = 0');
            }
        })
        ->count();
@endphp
<!DOCTYPE html>
<html lang="id" class="min-h-screen bg-[#EBF2F7] sm:bg-[#DDE8F0]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#259BE5">
    <title>{{ $title ?? 'Portal Guru — SmartPresensi' }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SmartPresensi">
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
        }
    </style>
</head>
<body class="min-h-screen antialiased text-slate-800 selection:bg-sky-200 selection:text-sky-900 pb-12 sm:pb-16">

    <!-- PWA Install Banner Floating on Mobile -->
    <div id="pwa-install-banner" class="hidden fixed top-3 left-3 right-3 z-50 max-w-xl mx-auto">
        <div class="bg-white/95 backdrop-blur-md p-3.5 rounded-2xl shadow-2xl border border-sky-200 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('icons/icon-72x72.png') }}" class="w-9 h-9 rounded-xl shadow-xs shrink-0" alt="Logo">
                <div>
                    <p class="text-xs font-black text-slate-800">Install SmartPresensi</p>
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

    <div class="w-full max-w-xl mx-auto bg-[#F4F8FC] min-h-screen flex flex-col relative sm:shadow-[0_20px_60px_-15px_rgba(30,80,130,0.15)] sm:my-6 sm:rounded-[36px] sm:border-8 sm:border-white">
        
        <!-- Curved Header with Logo -->
        <header class="relative bg-gradient-to-b from-[#2998EC] to-[#38A8F8] text-white pt-4 pb-10 px-6 rounded-b-[36px] shadow-sm overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10 blur-xl pointer-events-none"></div>

            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white p-1.5 shadow-md shadow-sky-900/10 flex items-center justify-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo SMA IT" class="w-full h-full object-contain" />
                    </div>
                    <div>
                        <h1 class="text-base font-extrabold tracking-tight text-white flex items-center gap-1.5 leading-tight">
                            SmartPresensi
                            <span class="text-[9px] bg-white/25 text-white px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">
                                Guru
                            </span>
                        </h1>
                        <p class="text-[11px] text-sky-100 font-medium leading-none mt-0.5">
                            {{ $teacher->name ?? $user->name }}
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
        </header>

        <!-- Main Content -->
        <main class="flex-1 px-5 -mt-5 pb-36 relative space-y-5">
            {{ $slot }}
        </main>

        <!-- Teacher Bottom Navigation -->
        <nav class="fixed bottom-0 left-0 right-0 z-30 pointer-events-none">
            <div class="w-full max-w-xl mx-auto px-4 pb-3 pointer-events-auto">
                <div class="bg-white/95 backdrop-blur-xl border border-sky-100/80 rounded-3xl shadow-[0_12px_35px_-5px_rgba(20,60,110,0.12)] flex items-center justify-between px-3 h-18 relative">
                    
                    <!-- 1. Beranda -->
                    <a href="{{ route('teacher.dashboard') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ $currentRoute === 'teacher.dashboard' ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5 {{ $currentRoute === 'teacher.dashboard' ? 'stroke-[2.5]' : '' }}"></i>
                        </div>
                        <span class="text-[10px] font-bold mt-0.5 {{ $currentRoute === 'teacher.dashboard' ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Beranda
                        </span>
                    </a>

                    <!-- 2. Izin Murid -->
                    <a href="{{ route('teacher.permissions') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group relative">
                        <div class="p-1.5 rounded-2xl transition relative {{ $currentRoute === 'teacher.permissions' ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i data-lucide="clipboard-check" class="w-5 h-5 {{ $currentRoute === 'teacher.permissions' ? 'stroke-[2.5]' : '' }}"></i>
                            @if($pendingPermCount > 0)
                                <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-bold">
                                    {{ $pendingPermCount }}
                                </span>
                            @endif
                        </div>
                        <span class="text-[10px] font-bold mt-0.5 {{ $currentRoute === 'teacher.permissions' ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Izin
                        </span>
                    </a>

                    <!-- 3. Center Scanner Action Button -->
                    <div class="flex-1 flex flex-col items-center justify-center -mt-6">
                        <a href="{{ route('teacher.scanner') }}" 
                           class="w-14 h-14 rounded-full bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] text-white flex items-center justify-center shadow-[0_10px_25px_rgba(30,136,229,0.45)] border-4 border-[#F4F8FC] active:scale-90 transition-transform duration-200 hover:-translate-y-0.5">
                            <i data-lucide="scan" class="w-6 h-6 stroke-[2.5]"></i>
                        </a>
                        <span class="text-[10px] font-bold text-slate-400 mt-1">Scan QR</span>
                    </div>

                    <!-- 4. Rekap -->
                    <a href="{{ route('teacher.recap') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ $currentRoute === 'teacher.recap' ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i data-lucide="bar-chart-3" class="w-5 h-5 {{ $currentRoute === 'teacher.recap' ? 'stroke-[2.5]' : '' }}"></i>
                        </div>
                        <span class="text-[10px] font-bold mt-0.5 {{ $currentRoute === 'teacher.recap' ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Rekap
                        </span>
                    </a>

                    <!-- 5. Profil -->
                    <a href="{{ route('teacher.profile') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ $currentRoute === 'teacher.profile' ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i data-lucide="user-check" class="w-5 h-5 {{ $currentRoute === 'teacher.profile' ? 'stroke-[2.5]' : '' }}"></i>
                        </div>
                        <span class="text-[10px] font-bold mt-0.5 {{ $currentRoute === 'teacher.profile' ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Profil
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
