@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3">
        <!-- Info text -->
        <p class="text-xs text-slate-500 font-medium text-center sm:text-left">
            Menampilkan <span class="font-black text-slate-850">{{ $paginator->firstItem() }}</span> &ndash; <span class="font-black text-slate-850">{{ $paginator->lastItem() }}</span> dari <span class="font-black text-slate-850">{{ $paginator->total() }}</span> siswa
        </p>

        <!-- Buttons group -->
        <div class="inline-flex items-center gap-1.5 p-1 bg-[#F4F8FC] border border-sky-100 rounded-2xl">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="w-8 h-8 rounded-xl bg-white/50 text-slate-300 text-xs flex items-center justify-center cursor-not-allowed">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                </span>
            @else
                <button type="button" wire:click="previousPage" wire:loading.attr="disabled" class="w-8 h-8 rounded-xl bg-white hover:bg-sky-50 text-slate-700 text-xs font-black flex items-center justify-center shadow-xs transition active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                </button>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-6 h-8 flex items-center justify-center text-xs font-bold text-slate-400 select-none">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-8 h-8 rounded-xl bg-gradient-to-r from-[#1E88E5] to-[#42A5F5] text-white font-black text-xs flex items-center justify-center shadow-md shadow-sky-500/20 select-none">
                                {{ $page }}
                            </span>
                        @else
                            <button type="button" wire:click="gotoPage({{ $page }})" class="w-8 h-8 rounded-xl bg-white hover:bg-sky-50 text-slate-700 text-xs font-bold flex items-center justify-center shadow-xs transition active:scale-95 cursor-pointer">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <button type="button" wire:click="nextPage" wire:loading.attr="disabled" class="w-8 h-8 rounded-xl bg-white hover:bg-sky-50 text-slate-700 text-xs font-black flex items-center justify-center shadow-xs transition active:scale-95 cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            @else
                <span class="w-8 h-8 rounded-xl bg-white/50 text-slate-300 text-xs flex items-center justify-center cursor-not-allowed">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m9 18 6-6-6-6"/></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
