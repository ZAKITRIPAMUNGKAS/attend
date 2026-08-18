<!DOCTYPE html>
<html lang="id" class="h-full bg-[#EEF5FC]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#259BE5">
    <title>{{ $title ?? 'Login — SmartAbsensi SMA IT Insan Kamil' }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
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
    </script>
</body>
</html>
