@php
    $user = auth()->user();
    $currentRoute = request()->route()->getName();
@endphp
<!DOCTYPE html>
<html lang="id" class="min-h-screen bg-[#EBF2F7] sm:bg-[#DDE8F0]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#259BE5">
    <title>{{ $title ?? 'Admin Panel — SmartAbsensi' }}</title>
    
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
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

    <!-- Mobile Device Simulator Container for Admin (Mobile-First Frame) -->
    <div class="w-full max-w-md mx-auto bg-[#F4F8FC] min-h-screen flex flex-col relative sm:shadow-[0_20px_60px_-15px_rgba(30,80,130,0.15)] sm:my-6 sm:rounded-[36px] sm:border-8 sm:border-white">
        
        <!-- Curved Sky Header -->
        <header class="relative bg-gradient-to-b from-[#2998EC] to-[#38A8F8] text-white pt-4 pb-10 px-6 rounded-b-[36px] shadow-sm overflow-hidden">
            <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/10 blur-xl pointer-events-none"></div>

            <!-- Brand Row -->
            <div class="flex items-center justify-between relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-2xl bg-white p-1.5 shadow-md shadow-sky-900/10 flex items-center justify-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo SMA IT" class="w-full h-full object-contain" />
                    </div>
                    <div>
                        <h1 class="text-base font-black tracking-tight text-white flex items-center gap-1.5 leading-tight">
                            SmartAbsensi
                            <span class="text-[9px] bg-white/25 text-white px-2 py-0.5 rounded-full font-bold uppercase tracking-wider">
                                ADMIN
                            </span>
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
                    <span class="font-medium text-white">{{ $user->name }}</span>
                </div>
                <span class="bg-white/20 backdrop-blur-md text-white font-bold text-[10px] px-2.5 py-0.5 rounded-full border border-white/25">
                    Super Admin
                </span>
            </div>
        </header>

        <!-- Main Content Area with safe scrolling padding pb-36 -->
        <main class="flex-1 px-5 -mt-5 pb-36 relative space-y-5">
            {{ $slot }}
        </main>

        <!-- Mobile-First Floating Bottom Navigation Bar -->
        <nav class="fixed bottom-0 left-0 right-0 z-30 pointer-events-none">
            <div class="w-full max-w-md mx-auto px-4 pb-3 pointer-events-auto">
                <div class="bg-white/95 backdrop-blur-xl border border-sky-100/80 rounded-3xl shadow-[0_12px_35px_-5px_rgba(20,60,110,0.12)] flex items-center justify-between px-2 h-18 relative">
                    
                    <!-- 1. Overview -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ $currentRoute === 'admin.dashboard' ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i data-lucide="layout-dashboard" class="w-5 h-5 {{ $currentRoute === 'admin.dashboard' ? 'stroke-[2.5]' : '' }}"></i>
                        </div>
                        <span class="text-[9px] font-bold mt-0.5 {{ $currentRoute === 'admin.dashboard' ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Overview
                        </span>
                    </a>

                    <!-- 2. Siswa -->
                    <a href="{{ route('admin.students') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ str_starts_with($currentRoute, 'admin.students') ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i data-lucide="users" class="w-5 h-5 {{ str_starts_with($currentRoute, 'admin.students') ? 'stroke-[2.5]' : '' }}"></i>
                        </div>
                        <span class="text-[9px] font-bold mt-0.5 {{ str_starts_with($currentRoute, 'admin.students') ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Siswa
                        </span>
                    </a>

                    <!-- 3. Guru -->
                    <a href="{{ route('admin.teachers') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ str_starts_with($currentRoute, 'admin.teachers') ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i data-lucide="graduation-cap" class="w-5 h-5 {{ str_starts_with($currentRoute, 'admin.teachers') ? 'stroke-[2.5]' : '' }}"></i>
                        </div>
                        <span class="text-[9px] font-bold mt-0.5 {{ str_starts_with($currentRoute, 'admin.teachers') ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Guru
                        </span>
                    </a>

                    <!-- 4. Rekap -->
                    <a href="{{ route('admin.attendance') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ str_starts_with($currentRoute, 'admin.attendance') ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i data-lucide="clipboard-check" class="w-5 h-5 {{ str_starts_with($currentRoute, 'admin.attendance') ? 'stroke-[2.5]' : '' }}"></i>
                        </div>
                        <span class="text-[9px] font-bold mt-0.5 {{ str_starts_with($currentRoute, 'admin.attendance') ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Rekap
                        </span>
                    </a>

                    <!-- 5. Jam -->
                    <a href="{{ route('admin.settings') }}" 
                       class="flex flex-col items-center justify-center flex-1 py-1 transition group">
                        <div class="p-1.5 rounded-2xl transition {{ str_starts_with($currentRoute, 'admin.settings') ? 'bg-sky-100 text-[#1E88E5]' : 'text-slate-400 group-hover:text-slate-600' }}">
                            <i data-lucide="clock" class="w-5 h-5 {{ str_starts_with($currentRoute, 'admin.settings') ? 'stroke-[2.5]' : '' }}"></i>
                        </div>
                        <span class="text-[9px] font-bold mt-0.5 {{ str_starts_with($currentRoute, 'admin.settings') ? 'text-[#1E88E5]' : 'text-slate-400' }}">
                            Jam
                        </span>
                    </a>

                </div>
            </div>
        </nav>

    </div>

    @livewireScripts
    <script>
        function refreshIcons() {
            if (window.lucide) {
                lucide.createIcons();
            }
        }
        document.addEventListener('DOMContentLoaded', refreshIcons);
        document.addEventListener('livewire:navigated', refreshIcons);
        document.addEventListener('livewire:initialized', () => {
            refreshIcons();
            Livewire.hook('morph.updated', () => {
                refreshIcons();
            });
            Livewire.hook('commit', () => {
                setTimeout(refreshIcons, 10);
            });
        });
        window.addEventListener('icons-updated', () => {
            setTimeout(refreshIcons, 50);
        });
    </script>
    @stack('scripts')
</body>
</html>
