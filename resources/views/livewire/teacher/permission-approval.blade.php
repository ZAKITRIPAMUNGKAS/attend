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

    <!-- Header & Filter Tabs -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-black text-slate-850">Persetujuan Izin & Sakit Siswa</h2>
            <p class="text-[11px] text-slate-400">Verifikasi surat izin dan dokter</p>
        </div>

        <div class="flex bg-[#F4F8FC] p-1 rounded-2xl border border-sky-100 text-xs">
            <button type="button" 
                    wire:click="$set('filterStatus', 'menunggu')"
                    class="px-3 py-1 rounded-xl font-bold transition cursor-pointer {{ $filterStatus === 'menunggu' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500' }}">
                Menunggu
            </button>
            <button type="button" 
                    wire:click="$set('filterStatus', 'all')"
                    class="px-3 py-1 rounded-xl font-bold transition cursor-pointer {{ $filterStatus === 'all' ? 'bg-[#1E88E5] text-white shadow-xs' : 'text-slate-500' }}">
                Semua
            </button>
        </div>
    </div>

    <!-- Permission Requests List -->
    <div class="space-y-3.5">
        @forelse($requests as $idx => $req)
            <div class="soft-card p-5 space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-sky-50 text-[#1E88E5] font-black text-xs flex items-center justify-center border border-sky-100 shrink-0">
                            {{ $idx + 1 }}
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-850">{{ $req->student->name ?? 'Siswa' }}</h4>
                            <p class="text-[11px] text-slate-400 font-semibold">
                                {{ $req->student->schoolClass->name ?? '-' }} • NISN: {{ $req->student->nisn }}
                            </p>
                        </div>
                    </div>

                    <span class="text-[10px] font-extrabold uppercase px-2.5 py-0.5 rounded-full {{ $req->type === 'sakit' ? 'bg-purple-100 text-purple-800' : 'bg-sky-100 text-sky-800' }}">
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
                        <a href="{{ Storage::url($req->attachment) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-[#1E88E5] font-bold hover:underline">
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
                                wire:click="reject({{ $req->id }})"
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
            <div class="soft-card p-8 text-center text-xs text-slate-400">
                <i data-lucide="inbox" class="w-10 h-10 text-slate-300 mx-auto mb-2"></i>
                Tidak ada pengajuan izin pada kategori ini.
            </div>
        @endforelse
    </div>

</div>
