<!DOCTYPE html>
<html lang="id" class="h-full bg-[#EEF5FC]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#259BE5">
    <title>{{ $title ?? 'Login — SmartPresensi SMA IT Insan Kamil' }}</title>
    
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #EEF5FC; }
        * { -webkit-tap-highlight-color: transparent; }
    </style>
</head>
<body class="h-full antialiased text-slate-850 flex items-center justify-center p-4 min-h-screen">

    <div class="w-full max-w-md my-auto">
        <!-- PWA Install Banner (appears when installable on mobile) -->
        <div id="pwa-install-banner" class="hidden mb-4">
            <div class="bg-white/95 backdrop-blur-md p-3.5 rounded-2xl shadow-xl border border-sky-200 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('icons/icon-72x72.png') }}" class="w-9 h-9 rounded-xl shadow-xs shrink-0" alt="Logo">
                    <div>
                        <p class="text-xs font-black text-slate-800">Pasang Aplikasi SmartPresensi</p>
                        <p class="text-[10px] text-slate-500 font-medium">Akses langsung dari layar utama HP Anda</p>
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

        {{ $slot }}

        <!-- Developer Credit Footer -->
        <div class="mt-5 text-center">
            <a href="https://tepegrafi.id" 
               target="_blank" 
               rel="noopener noreferrer"
               class="inline-flex items-center gap-1.5 text-[11px] text-slate-400 hover:text-[#1E88E5] font-medium transition-colors duration-200 group">
                <svg class="w-3 h-3 text-slate-300 group-hover:text-[#1E88E5] transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                <span>Developed by <strong class="text-slate-500 group-hover:text-[#1E88E5] transition-colors font-bold">gemala.dev</strong></span>
            </a>
        </div>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) lucide.createIcons();
        });
        document.addEventListener('livewire:navigated', () => {
            if (window.lucide) lucide.createIcons();
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
</body>
</html>
