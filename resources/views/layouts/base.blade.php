<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#047857">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ $title ?? config('app.name', 'SmartAbsensi') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons CDN for easy feather-light iconography -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        /* Mobile safe-area padding */
        .pb-safe {
            padding-bottom: env(safe-area-inset-bottom, 1.5rem);
        }
        .pt-safe {
            padding-top: env(safe-area-inset-top, 1rem);
        }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-slate-100 flex flex-col selection:bg-emerald-500 selection:text-white">

    {{ $slot }}

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
        document.addEventListener('livewire:navigated', () => {
            if (window.lucide) {
                lucide.createIcons();
            }
        });
        window.addEventListener('icons-updated', () => {
            setTimeout(() => {
                if (window.lucide) lucide.createIcons();
            }, 50);
        });
    </script>
    @stack('scripts')
</body>
</html>
