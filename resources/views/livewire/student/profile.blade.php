<div class="space-y-5">

    <!-- Profile Header Card -->
    <div class="soft-card p-6 text-center flex flex-col items-center">
        <div class="w-20 h-20 rounded-3xl bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] text-white flex items-center justify-center text-2xl font-black shadow-lg shadow-sky-500/25 mb-3 border-4 border-white">
            {{ strtoupper(substr($student->name, 0, 2)) }}
        </div>
        <h2 class="text-base font-black text-slate-850">{{ $student->name }}</h2>
        <p class="text-xs text-[#1E88E5] font-bold mt-0.5">{{ $student->schoolClass->name ?? '-' }}</p>
        <span class="mt-2 text-[10px] uppercase font-extrabold tracking-wider px-3 py-0.5 rounded-full bg-sky-50 text-[#1E88E5] border border-sky-200">
            Murid Aktif
        </span>
    </div>

    <!-- Biodata Info -->
    <div class="soft-card p-5 space-y-3">
        <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Biodata Murid</h3>
        
        <div class="space-y-2.5 text-xs">
            <div class="flex justify-between py-1.5 border-b border-slate-100">
                <span class="text-slate-400 font-medium">NISN</span>
                <span class="font-mono font-bold text-slate-800">{{ $student->nisn }}</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-100">
                <span class="text-slate-400 font-medium">Jenis Kelamin</span>
                <span class="font-bold text-slate-800">{{ $student->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
            </div>
            <div class="flex justify-between py-1.5 border-b border-slate-100">
                <span class="text-slate-400 font-medium">Tanggal Lahir</span>
                <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($student->birth_date)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex justify-between py-1.5">
                <span class="text-slate-400 font-medium">Orang Tua / Wali</span>
                <span class="font-bold text-slate-800">{{ $student->parent_name ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="soft-card p-5 space-y-3">
        <h3 class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Nomor WhatsApp Murid</h3>
        
        @if($successProfile)
            <div class="p-3 rounded-2xl bg-emerald-50 text-emerald-850 text-xs font-semibold">
                {{ $successProfile }}
            </div>
        @endif

        <form wire:submit="updateProfile" class="space-y-3">
            <input type="text" 
                   wire:model="phone" 
                   placeholder="08xxxxxxxxxx"
                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
            <button type="submit" class="w-full py-2.5 bg-slate-850 hover:bg-slate-900 text-white font-bold text-xs rounded-2xl transition active:scale-98 cursor-pointer">
                Simpan Kontak
            </button>
        </form>
    </div>

    <!-- Password Change Form -->
    <div id="ganti-password" class="soft-card p-5 space-y-3">
        <div class="flex items-center gap-2">
            <i data-lucide="shield-check" class="w-4 h-4 text-[#1E88E5]"></i>
            <h3 class="text-[11px] font-extrabold text-slate-850 uppercase tracking-wider">Ubah Password Akun</h3>
        </div>

        @if($successPassword)
            <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-850 text-xs font-semibold">
                {{ $successPassword }}
            </div>
        @endif

        @if($errorPassword)
            <div class="p-3 rounded-2xl bg-rose-50 border border-rose-200 text-rose-850 text-xs font-semibold">
                {{ $errorPassword }}
            </div>
        @endif

        <form wire:submit="updatePassword" class="space-y-3">
            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Password Lama</label>
                <input type="password" 
                       wire:model="current_password" 
                       placeholder="Password saat ini" 
                       required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                @error('current_password') <span class="text-[10px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Password Baru</label>
                <input type="password" 
                       wire:model="new_password" 
                       placeholder="Minimal 6 karakter" 
                       required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
                @error('new_password') <span class="text-[10px] text-rose-600 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-600 mb-1">Ulangi Password Baru</label>
                <input type="password" 
                       wire:model="new_password_confirmation" 
                       placeholder="Konfirmasi password" 
                       required
                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-sky-500" />
            </div>

            <button type="submit" class="w-full py-3 bg-[#1E88E5] hover:bg-[#1976D2] text-white font-bold text-xs rounded-2xl shadow-md shadow-sky-500/20 transition active:scale-98 cursor-pointer">
                Perbarui Password
            </button>
        </form>
    </div>

</div>
