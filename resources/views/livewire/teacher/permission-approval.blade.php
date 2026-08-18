<div class="space-y-5">

    <!-- Flash Message -->
    @if($flashMessage)
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <span>{{ $flashMessage }}</span>
            </div>
            <button type="button" wire:click="$set('flashMessage', '')" class="text-emerald-700 hover:text-emerald-900 font-black text-base cursor-pointer px-1">&times;</button>
        </div>
    @endif

    <!-- Top Hero Card with Clean Horizontal Filters -->
    <div class="soft-card p-5 space-y-4 bg-white">
        <div class="flex items-start justify-between">
            <div class="space-y-1 max-w-[75%]">
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    Verifikasi Kehadiran
                </span>
                <h2 class="text-base font-black text-slate-850 tracking-tight leading-snug">
                    Persetujuan Izin & Sakit
                </h2>
                <p class="text-xs text-slate-400 font-medium leading-relaxed">
                    Verifikasi surat izin & dokter dari murid rombel Anda
                </p>
            </div>
            
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#1E88E5] to-[#42A5F5] text-white flex items-center justify-center shadow-md shadow-sky-500/25 shrink-0">
                <svg class="w-6 h-6 stroke-[2]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M9 11l3 3L22 4"/>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                </svg>
            </div>
        </div>

        <!-- 4 Bulletproof Horizontal Filter Tabs -->
        <div class="flex items-center gap-1 p-1 bg-[#F4F8FC] rounded-2xl border border-sky-100 w-full text-center select-none">
            <!-- 1. Menunggu -->
            <button type="button" 
                    wire:click="$set('filterStatus', 'menunggu')"
                    class="flex-1 py-2 px-1.5 rounded-xl text-[11px] font-extrabold transition-all duration-200 flex items-center justify-center gap-1 cursor-pointer {{ $filterStatus === 'menunggu' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                <span>Menunggu</span>
                @if($pendingCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[9px] font-black leading-none {{ $filterStatus === 'menunggu' ? 'bg-white text-[#1E88E5]' : 'bg-rose-500 text-white' }}">
                        {{ $pendingCount }}
                    </span>
                @endif
            </button>

            <!-- 2. Disetujui -->
            <button type="button" 
                    wire:click="$set('filterStatus', 'disetujui')"
                    class="flex-1 py-2 px-1.5 rounded-xl text-[11px] font-extrabold transition-all duration-200 flex items-center justify-center cursor-pointer {{ $filterStatus === 'disetujui' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                Disetujui
            </button>

            <!-- 3. Ditolak -->
            <button type="button" 
                    wire:click="$set('filterStatus', 'ditolak')"
                    class="flex-1 py-2 px-1.5 rounded-xl text-[11px] font-extrabold transition-all duration-200 flex items-center justify-center cursor-pointer {{ $filterStatus === 'ditolak' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                Ditolak
            </button>

            <!-- 4. Semua -->
            <button type="button" 
                    wire:click="$set('filterStatus', 'all')"
                    class="flex-1 py-2 px-1.5 rounded-xl text-[11px] font-extrabold transition-all duration-200 flex items-center justify-center cursor-pointer {{ $filterStatus === 'all' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                Semua
            </button>
        </div>
    </div>

    <!-- Permission Requests List -->
    <div class="space-y-3.5">
        @forelse($requests as $idx => $req)
            <div class="soft-card p-5 space-y-3 bg-white">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-sky-50 text-[#1E88E5] font-black text-xs flex items-center justify-center border border-sky-100 shrink-0">
                            {{ $idx + 1 }}
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-850 leading-tight">{{ $req->student->name ?? 'Murid' }}</h4>
                            <p class="text-[11px] text-slate-400 font-semibold mt-0.5">
                                {{ $req->student->schoolClass->name ?? '-' }} • NISN: {{ $req->student->nisn }}
                            </p>
                        </div>
                    </div>

                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full {{ $req->type === 'sakit' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-sky-100 text-sky-800 border border-sky-200' }}">
                        {{ $req->type }}
                    </span>
                </div>

                <div class="bg-[#F4F8FC] p-3.5 rounded-2xl border border-sky-100 space-y-1.5 text-xs">
                    <div class="flex items-center justify-between text-slate-500 text-[11px]">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/>
                                <line x1="16" x2="16" y1="2" y2="6"/>
                                <line x1="8" x2="8" y1="2" y2="6"/>
                                <line x1="3" x2="21" y1="10" y2="10"/>
                            </svg>
                            Tanggal Izin:
                        </span>
                        <strong class="text-slate-850 font-bold">{{ \Carbon\Carbon::parse($req->date)->translatedFormat('l, d F Y') }}</strong>
                    </div>
                    <p class="text-slate-700 pt-1 leading-relaxed border-t border-sky-100/70">
                        <strong class="text-slate-900 font-bold">Alasan:</strong> {{ $req->reason }}
                    </p>
                </div>

                <!-- Attachment Link if available -->
                @if($req->attachment)
                    <div class="pt-1">
                        <a href="{{ Storage::url($req->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-xs text-[#1E88E5] font-bold rounded-xl border border-sky-100 transition">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                            <span>Lihat Lampiran Bukti Surat</span>
                        </a>
                    </div>
                @endif

                <!-- Action Controls if Menunggu -->
                @if($req->status === 'menunggu')
                    <div class="flex gap-2 pt-2 border-t border-slate-100">
                        <button type="button" 
                                wire:click="approve({{ $req->id }})"
                                class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-2xl shadow-sm shadow-emerald-500/20 flex items-center justify-center gap-1.5 transition active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            <span>Setujui</span>
                        </button>
                        <button type="button" 
                                wire:click="openRejectModal({{ $req->id }})"
                                class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-2xl border border-rose-200 flex items-center justify-center gap-1.5 transition active:scale-95 cursor-pointer">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                            <span>Tolak</span>
                        </button>
                    </div>
                @else
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                        <span>Status: <strong class="uppercase font-bold {{ $req->status === 'disetujui' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $req->status }}</strong></span>
                        @if($req->approver)
                            <span>Oleh: {{ $req->approver->name }}</span>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <!-- Clean Modern Empty State -->
            <div class="soft-card p-10 bg-white text-center flex flex-col items-center justify-center space-y-3">
                <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-sky-50 to-blue-50 text-[#1E88E5] border border-sky-100 flex items-center justify-center shadow-xs">
                    <svg class="w-8 h-8 text-[#1E88E5] stroke-[1.8]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M22 12h-6l-2 3h-4l-2-3H2"/>
                        <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <h4 class="text-sm font-black text-slate-800">Tidak Ada Pengajuan</h4>
                    <p class="text-xs text-slate-400 max-w-xs leading-relaxed">
                        Tidak ada surat izin atau sakit murid dengan status <strong class="text-slate-700 capitalize">{{ $filterStatus }}</strong>.
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Modal Alasan Penolakan Izin -->
    @if($selectedRequestId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-5 space-y-3.5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h3 class="text-sm font-black text-slate-850">Tolak Pengajuan Izin</h3>
                    <button type="button" wire:click="$set('selectedRequestId', null)" class="text-slate-400 hover:text-slate-700 cursor-pointer text-lg leading-none">&times;</button>
                </div>

                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase">Alasan Penolakan</label>
                    <textarea wire:model="rejectionReason" 
                              rows="3" 
                              placeholder="Masukkan alasan penolakan izin..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
                    @error('rejectionReason') <span class="text-[10px] text-rose-600 font-bold block">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-2 pt-1">
                    <button type="button" 
                            wire:click="reject({{ $selectedRequestId }})" 
                            class="flex-1 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-md shadow-rose-500/20 cursor-pointer">
                        Konfirmasi Tolak
                    </button>
                    <button type="button" 
                            wire:click="$set('selectedRequestId', null)" 
                            class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
