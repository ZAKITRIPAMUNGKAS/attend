<div class="bg-white rounded-[32px] p-6 sm:p-8 shadow-[0_20px_50px_-10px_rgba(20,70,120,0.1)] border border-sky-100/80 relative overflow-hidden" x-data="{ showPass: false }">
    
    <!-- Top Subtle Glow -->
    <div class="absolute -top-12 -right-12 w-36 h-36 bg-sky-200/30 rounded-full blur-2xl pointer-events-none"></div>

    <!-- Header Logo & Branding -->
    <div class="text-center mb-6 relative">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-sky-50 border border-sky-100 p-2 shadow-sm mb-3">
            <img src="{{ asset('logo.png') }}" alt="Logo SMA IT Insan Kamil" class="w-full h-full object-contain" />
        </div>
        <h2 class="text-2xl font-black text-slate-850 tracking-tight">SmartAbsensi</h2>
        <p class="text-[11px] font-extrabold text-[#1E88E5] mt-0.5 uppercase tracking-wider">SMA IT INSAN KAMIL</p>
        <p class="text-xs text-slate-400 mt-1">Sistem Absensi Murid & Manajemen Kehadiran</p>
    </div>

    <!-- Error Alert -->
    @if($errorMessage)
        <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2.5">
            <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-rose-600"></i>
            <span>{{ $errorMessage }}</span>
        </div>
    @endif

    <!-- Login Form -->
    <form wire:submit="login" class="space-y-4 relative text-left">
        <!-- Input Identitas -->
        <div class="text-left">
            <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5 text-left">
                NISN / NIS / Username / Email
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <input type="text" 
                       wire:model="identifier" 
                       placeholder="Contoh: 26271001 atau 0091010001" 
                       autocomplete="username"
                       required
                       class="w-full pl-10 pr-4 py-3 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-semibold text-left text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition" />
            </div>
            @error('identifier') <span class="text-[10px] text-rose-600 mt-1 block font-medium text-left">{{ $message }}</span> @enderror
        </div>

        <!-- Input Password -->
        <div class="text-left">
            <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5 text-left">
                Password
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </div>
                <input :type="showPass ? 'text' : 'password'" 
                       wire:model="password" 
                       placeholder="Password Akun" 
                       autocomplete="current-password"
                       required
                       class="w-full pl-10 pr-11 py-3 bg-[#F4F8FC] border border-sky-100 rounded-2xl text-xs font-semibold text-left text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:bg-white transition" />
                <button type="button" 
                       @click="showPass = !showPass" 
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition cursor-pointer">
                    <template x-if="!showPass">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </template>
                    <template x-if="showPass">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                    </template>
                </button>
            </div>
            @error('password') <span class="text-[10px] text-rose-600 mt-1 block font-medium text-left">{{ $message }}</span> @enderror
            <p class="text-[11px] text-slate-400 mt-1.5 text-left">
                <strong class="text-sky-700">Murid:</strong> Password awal = tanggal lahir <code class="bg-sky-50 text-sky-800 px-1 py-0.5 rounded font-mono font-bold">DDMMYYYY</code>
            </p>
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] hover:from-[#1976D2] hover:to-[#1E88E5] text-white font-bold text-xs rounded-2xl shadow-lg shadow-sky-500/25 transition transform active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75">
                <span wire:loading.remove>Masuk ke Portal</span>
                <span wire:loading class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    Memproses...
                </span>
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" wire:loading.remove><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </div>
    </form>

    <!-- Demo Accounts Quick Click Pills with Clean SVG Icons -->
    <div class="mt-6 pt-5 border-t border-slate-100">
        <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 text-center mb-2.5">Akun Contoh (Klik untuk Isi Otomatis):</p>
        <div class="grid grid-cols-3 gap-2 text-center">
            <!-- Admin -->
            <button type="button" 
                    wire:click="$set('identifier', 'admin'); $set('password', 'password');" 
                    class="py-2.5 px-2 rounded-2xl bg-slate-100/90 hover:bg-slate-200 text-slate-700 text-xs font-bold transition active:scale-95 cursor-pointer flex items-center justify-center gap-1.5 border border-slate-200/70 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <span>Admin</span>
            </button>

            <!-- Guru / Wali -->
            <button type="button" 
                    wire:click="$set('identifier', 'guru_abdullah'); $set('password', 'password');" 
                    class="py-2.5 px-2 rounded-2xl bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition active:scale-95 cursor-pointer flex items-center justify-center gap-1.5 border border-blue-200/70 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                <span>Guru</span>
            </button>

            <!-- Murid Real -->
            <button type="button" 
                    wire:click="$set('identifier', '26271001'); $set('password', '02022010');" 
                    class="py-2.5 px-2 rounded-2xl bg-sky-50 hover:bg-sky-100 text-[#1E88E5] text-xs font-bold transition active:scale-95 cursor-pointer flex items-center justify-center gap-1.5 border border-sky-200/70 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-[#1E88E5] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span>Murid</span>
            </button>
        </div>
    </div>

</div>
