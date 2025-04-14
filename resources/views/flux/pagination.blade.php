@props([
    'paginator' => null,
    'simple' => null
])

@if ($simple)
    <div {{ $attributes->class('pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center') }} data-flux-pagination>
        @if ($paginator->total() > 0)
            <div class="text-slate-500 dark:text-slate-400 text-xs font-medium whitespace-nowrap">
                {!! __('Showing') !!} {{ $paginator->firstItem() }} {!! __('to') !!} {{ $paginator->lastItem() }} {!! __('of') !!} {{ $paginator->total() }} {!! __('results') !!}
            </div>
        @else
            <div></div>
        @endif

        @if ($paginator->hasPages())
            <div class="flex items-center bg-white border border-slate-200 rounded-[8px] p-[1px] dark:bg-slate-800 dark:border-slate-700">
                @if ($paginator->onFirstPage())
                    <div class="flex justify-center items-center size-6 rounded-[6px] text-slate-300 dark:text-white">
                        <flux:icon.chevron-left variant="micro" />
                    </div>
                @else
                    @if(method_exists($paginator,'getCursorName'))
                        <button type="button" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->previousCursor()->encode() }}" wire:click="setPage('{{$paginator->previousCursor()->encode()}}','{{ $paginator->getCursorName() }}')" class="flex justify-center items-center size-6 rounded-[6px] text-slate-400 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-800 dark:hover:text-white">
                            <flux:icon.chevron-left variant="micro" />
                        </button>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" class="flex justify-center items-center size-6 rounded-[6px] text-slate-400 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-800 dark:hover:text-white">
                            <flux:icon.chevron-left variant="micro" />
                        </button>
                    @endif
                @endif

                @if ($paginator->hasMorePages())
                    @if(method_exists($paginator,'getCursorName'))
                        <button type="button" wire:key="cursor-{{ $paginator->getCursorName() }}-{{ $paginator->nextCursor()->encode() }}" wire:click="setPage('{{$paginator->nextCursor()->encode()}}','{{ $paginator->getCursorName() }}')" class="flex justify-center items-center size-6 rounded-[6px] text-slate-400 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-800 dark:hover:text-white">
                            <flux:icon.chevron-right variant="micro" />
                        </button>
                    @else
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" class="flex justify-center items-center size-6 rounded-[6px] text-slate-400 dark:text-white hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-800 dark:hover:text-white">
                            <flux:icon.chevron-right variant="micro" />
                        </button>
                    @endif
                @else
                    <div class="flex justify-center items-center size-6 rounded-[6px] text-slate-300 dark:text-white">
                        <flux:icon.chevron-right variant="micro" />
                    </div>
                @endif
            </div>
        @endif
    </div>
@else
    <div {{ $attributes->class('pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-between items-center max-sm:flex-col max-sm:gap-3 max-sm:items-end') }} data-flux-pagination>
        @if ($paginator->total() > 0)
            <div class="text-slate-500 dark:text-slate-400 text-xs font-medium whitespace-nowrap">
                {!! __('Showing') !!} {{ $paginator->firstItem() }} {!! __('to') !!} {{ $paginator->lastItem() }} {!! __('of') !!} {{ $paginator->total() }} {!! __('results') !!}
            </div>
        @else
            <div></div>
        @endif

        @if ($paginator->hasPages())
            <div class="flex items-center bg-white border border-slate-200 rounded-[8px] p-[1px] dark:bg-slate-800 dark:border-slate-700 shadow-sm">
                @if ($paginator->onFirstPage())
                    <div aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="flex justify-center items-center size-6 rounded-[6px] text-slate-300 dark:text-slate-400">
                        <flux:icon.chevron-left variant="micro" />
                    </div>
                @else
                    <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" aria-label="{{ __('pagination.previous') }}" class="flex justify-center items-center size-6 rounded-[6px] text-slate-400 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-800 dark:hover:text-white">
                        <flux:icon.chevron-left variant="micro" />
                    </button>
                @endif

                @foreach (\Livewire\invade($paginator)->elements() as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <div
                            aria-disabled="true"
                            class="cursor-default flex justify-center items-center text-xs size-6 rounded-[6px] font-medium dark:text-slate-400 text-slate-400"
                        >{{ $element }}</div>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <div
                                    wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}"
                                    aria-current="page"
                                    class="cursor-default flex justify-center items-center text-xs h-6 px-2 rounded-[6px] font-medium dark:text-white text-slate-800"
                                >{{ $page }}</div>
                            @else
                                <button
                                    wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}"
                                    wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    type="button"
                                    class="text-xs h-6 px-2 rounded-[6px] text-slate-400 font-medium dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-800 dark:hover:text-white"
                                >{{ $page }}</button>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" aria-label="{{ __('pagination.next') }}" class="flex justify-center items-center size-6 rounded-[6px] text-slate-400 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-800 dark:hover:text-white">
                        <flux:icon.chevron-right variant="micro" />
                    </button>
                @else
                    <div aria-label="{{ __('pagination.next') }}" class="flex justify-center items-center size-6 rounded-[6px] text-slate-300 dark:text-slate-400">
                        <flux:icon.chevron-right variant="micro" />
                    </div>
                @endif
            </div>
        @endif
    </div>
@endif


