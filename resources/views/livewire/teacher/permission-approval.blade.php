<div class="space-y-5">

    <!-- Flash Message -->
    @if($flashMessage)
        <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-850 text-xs font-semibold flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span>{{ $flashMessage }}</span>
            </div>
            <button wire:click="$set('flashMessage', '')" class="text-emerald-700 font-bold">&times;</button>
        </div>
    @endif

    <!-- Top Hero Card with Clean Filters -->
    <div class="soft-card p-5 space-y-4 bg-white">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-sky-600 bg-sky-50 px-2.5 py-0.5 rounded-full border border-sky-100">
                    Verifikasi Kehadiran
                </span>
                <h2 class="text-base font-black text-slate-850 tracking-tight mt-1">Persetujuan Izin & Sakit</h2>
                <p class="text-[11px] text-slate-400">Verifikasi surat izin & dokter murid rombel Anda</p>
            </div>
            
            <div class="w-10 h-10 rounded-2xl bg-sky-50 text-[#1E88E5] flex items-center justify-center border border-sky-100 shrink-0">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
            </div>
        </div>

        <!-- 4 Filter Tabs -->
        <div class="grid grid-cols-4 gap-1.5 p-1 bg-[#F4F8FC] rounded-2xl border border-sky-100 text-xs">
            <button type="button" 
                    wire:click="$set('filterStatus', 'menunggu')"
                    class="py-2 px-1 rounded-xl text-[11px] font-bold transition flex items-center justify-center gap-1 cursor-pointer {{ $filterStatus === 'menunggu' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                <span>Menunggu</span>
                @if($pendingCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[9px] font-black {{ $filterStatus === 'menunggu' ? 'bg-white text-[#1E88E5]' : 'bg-rose-500 text-white' }}">
                        {{ $pendingCount }}
                    </span>
                @endif
            </button>
            <button type="button" 
                    wire:click="$set('filterStatus', 'disetujui')"
                    class="py-2 px-1 rounded-xl text-[11px] font-bold transition flex items-center justify-center cursor-pointer {{ $filterStatus === 'disetujui' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                Disetujui
            </button>
            <button type="button" 
                    wire:click="$set('filterStatus', 'ditolak')"
                    class="py-2 px-1 rounded-xl text-[11px] font-bold transition flex items-center justify-center cursor-pointer {{ $filterStatus === 'ditolak' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
                Ditolak
            </button>
            <button type="button" 
                    wire:click="$set('filterStatus', 'all')"
                    class="py-2 px-1 rounded-xl text-[11px] font-bold transition flex items-center justify-center cursor-pointer {{ $filterStatus === 'all' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500 hover:text-slate-800' }}">
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
                            <h4 class="text-sm font-black text-slate-850">{{ $req->student->name ?? 'Murid' }}</h4>
                            <p class="text-[11px] text-slate-400 font-semibold">
                                {{ $req->student->schoolClass->name ?? '-' }} • NISN: {{ $req->student->nisn }}
                            </p>
                        </div>
                    </div>

                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full {{ $req->type === 'sakit' ? 'bg-purple-100 text-purple-800 border border-purple-200' : 'bg-sky-100 text-sky-800 border border-sky-200' }}">
                        {{ $req->type }}
                    </span>
                </div>

                <div class="bg-[#F4F8FC] p-3 rounded-2xl border border-sky-100 space-y-1 text-xs">
                    <div class="flex justify-between text-slate-500 text-[11px]">
                        <span>Tanggal Izin:</span>
                        <strong class="text-slate-850">{{ \Carbon\Carbon::parse($req->date)->translatedFormat('l, d F Y') }}</strong>
                    </div>
                    <p class="text-slate-700 pt-1 leading-relaxed">
                        <strong class="text-slate-900">Alasan:</strong> {{ $req->reason }}
                    </p>
                </div>

                <!-- Attachment Link if available -->
                @if($req->attachment)
                    <div class="pt-1">
                        <a href="{{ Storage::url($req->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-xs text-[#1E88E5] font-bold rounded-xl border border-sky-100 transition">
                            <i data-lucide="paperclip" class="w-3.5 h-3.5"></i>
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
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>Setujui</span>
                        </button>
                        <button type="button" 
                                wire:click="openRejectModal({{ $req->id }})"
                                class="px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-2xl border border-rose-200 flex items-center justify-center gap-1.5 transition active:scale-95 cursor-pointer">
                            <i data-lucide="x" class="w-4 h-4"></i>
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
            <div class="soft-card p-10 bg-white text-center flex flex-col items-center justify-center space-y-2.5">
                <div class="w-14 h-14 rounded-3xl bg-sky-50 text-[#1E88E5] border border-sky-100 flex items-center justify-center">
                    <i data-lucide="inbox" class="w-7 h-7 stroke-[1.8]"></i>
                </div>
                <h4 class="text-sm font-black text-slate-800">Tidak Ada Pengajuan Izin</h4>
                <p class="text-xs text-slate-400 max-w-xs leading-relaxed">
                    Tidak ditemukan surat izin atau sakit murid pada kategori <span class="font-bold text-slate-600 uppercase">{{ $filterStatus }}</span>.
                </p>
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
                              class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500"></textarea>
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
