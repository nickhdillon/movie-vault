{{-- blade-formatter-disable --}}

@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex items-center justify-between flex-1 sm:hidden">
                <span class="flex items-center space-x-1">
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center p-2 text-sm font-medium leading-5 bg-white border rounded-md cursor-default text-slate-500 border-slate-200 dark:bg-slate-800 dark:border-slate-600 dark:focus:border-indigo-700 dark:active:bg-slate-700 dark:active:text-slate-300">
                            <flux:icon.chevron-double-left class="w-4 h-4" />
                        </span>

                        <span class="relative inline-flex items-center p-2 text-sm font-medium leading-5 bg-white border rounded-md cursor-default text-slate-500 border-slate-200 dark:bg-slate-800 dark:border-slate-600 dark:focus:border-indigo-700 dark:active:bg-slate-700 dark:active:text-slate-300">
                            <flux:icon.chevron-left class="w-4 h-4" />
                        </span>
                    @else
                        <button type="button" wire:click="setPage(1)" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="setPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center p-2 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border rounded-md text-slate-700 border-slate-200 hover:text-indigo-500 dark:text-slate-300 hover:bg-indigo-50 dark:border-slate-600 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer">
                            <flux:icon.chevron-double-left class="w-4 h-4" />
                        </button>
                    
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center p-2 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border rounded-md text-slate-700 border-slate-200 hover:text-indigo-500 dark:text-slate-300 hover:bg-indigo-50 dark:border-slate-600 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer">
                            <flux:icon.chevron-left class="w-4 h-4" />
                        </button>
                    @endif
                </span>

                <div>
                    <p class="text-sm leading-5 text-slate-700 dark:text-slate-400">
                        <span class="font-semibold text-indigo-500">{{ $paginator->currentPage() }}</span>
                        <span>/</span>
                        <span class="font-semibold">{{ $paginator->lastPage() }}</span>
                    </p>
                </div>

                <span class="flex items-center space-x-1">
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center p-2 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border rounded-md text-slate-700 border-slate-200 hover:text-indigo-500 dark:text-slate-300 hover:bg-indigo-50 dark:border-slate-600 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer">
                            <flux:icon.chevron-right class="w-4 h-4" />
                        </button>

                        <button type="button" wire:click="gotoPage({{ $paginator->lastPage() }})" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="setPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center p-2 text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border rounded-md text-slate-700 border-slate-200 hover:text-indigo-500 dark:text-slate-300 hover:bg-indigo-50 dark:border-slate-600 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer">
                            <flux:icon.chevron-double-right class="w-4 h-4" />
                        </button>
                    @else
                        <span class="relative inline-flex items-center p-2 text-sm font-medium leading-5 bg-white border rounded-md cursor-default text-slate-500 border-slate-200 dark:bg-slate-800 dark:border-slate-600 dark:focus:border-indigo-700 dark:active:bg-slate-700 dark:active:text-slate-300">
                            <flux:icon.chevron-right class="w-4 h-4" />
                        </span>

                        <span class="relative inline-flex items-center p-2 text-sm font-medium leading-5 bg-white border rounded-md cursor-default text-slate-500 border-slate-200 dark:bg-slate-800 dark:border-slate-600 dark:focus:border-indigo-700 dark:active:bg-slate-700 dark:active:text-slate-300">
                            <flux:icon.chevron-double-right class="w-4 h-4" />
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm leading-5 text-slate-700 dark:text-slate-400">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-semibold text-indigo-500">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-semibold text-indigo-500">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-semibold text-indigo-500">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rounded-md shadow-sm rtl:flex-row-reverse">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium leading-5 bg-white border cursor-default text-slate-500 border-slate-300 rounded-l-md dark:bg-slate-800 dark:border-slate-600" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @else
                                <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border text-slate-500 border-slate-300 rounded-l-md hover:text-indigo-500 hover:bg-indigo-50 dark:border-slate-600 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer" aria-label="{{ __('pagination.previous') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 bg-white border cursor-default text-slate-700 border-slate-300 dark:bg-slate-800 dark:border-slate-600">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-indigo-500 border cursor-default bg-indigo-50 border-slate-300 dark:bg-slate-800 dark:border-slate-600">{{ $page }}</span>
                                            </span>
                                        @else
                                            <button type="button" wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border hover:bg-indigo-50 text-slate-700 border-slate-300 hover:text-indigo-600 dark:bg-slate-800 dark:border-slate-600 dark:hover:bg-slate-700 dark:text-slate-400 dark:hover:text-indigo-500 cursor-pointer" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border text-slate-500 border-slate-300 rounded-r-md hover:text-indigo-500 hover:bg-indigo-50 dark:border-slate-600 dark:hover:bg-slate-700 dark:bg-slate-800 cursor-pointer" aria-label="{{ __('pagination.next') }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium leading-5 bg-white border cursor-default text-slate-500 border-slate-300 rounded-r-md dark:bg-slate-800 dark:border-slate-600" aria-hidden="true">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
