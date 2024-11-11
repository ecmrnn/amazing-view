<div
    class="items-center justify-between gap-2 sm:flex"
    wire:loading.class="blur-[2px]"
    wire:target="loadMore"
>
    <div class="items-center justify-between w-full sm:flex-1 sm:flex">
        @if ($recordCount === 'full')
            <div @class(['mr-3' => $paginator->hasPages()])>
                <div @class([
                    'mr-2' => $paginator->hasPages(),
                    'leading-5 text-center text-pg-primary-700 text-md dark:text-pg-primary-300 sm:text-right',
                ])>
                    {{ trans('livewire-powergrid::datatable.pagination.showing') }}
                    <span class="font-semibold firstItem">{{ $paginator->firstItem() }}</span>
                    {{ trans('livewire-powergrid::datatable.pagination.to') }}
                    <span class="font-semibold lastItem">{{ $paginator->lastItem() }}</span>
                    {{ trans('livewire-powergrid::datatable.pagination.of') }}
                    <span class="font-semibold total">{{ $paginator->total() }}</span>
                    {{ trans('livewire-powergrid::datatable.pagination.results') }}
                </div>
            </div>
        @elseif($recordCount === 'short')
            <div @class(['mr-3' => $paginator->hasPages()])>
                <p @class([
                    'mr-2' => $paginator->hasPages(),
                    'leading-5 text-center text-pg-primary-700 text-md dark:text-pg-primary-300 sm:text-right',
                ])>
                    <span class="font-semibold firstItem"> {{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-semibold lastItem"> {{ $paginator->lastItem() }}</span>
                    |
                    <span class="font-semibold total"> {{ $paginator->total() }}</span>
                </p>
            </div>
        @elseif($recordCount === 'min')
            <div @class(['mr-3' => $paginator->hasPages()])>
                <p @class([
                    'mr-2' => $paginator->hasPages(),
                    'leading-5 text-center text-pg-primary-700 text-md dark:text-pg-primary-300 sm:text-right',
                ])>
                    <span class="font-semibold firstItem"> {{ $paginator->firstItem() }}</span>
                    -
                    <span class="font-semibold lastItem"> {{ $paginator->lastItem() }}</span>
                </p>
            </div>
        @endif

        @if ($paginator->hasPages() && !in_array($recordCount, ['min', 'short']))
            <nav
                role="navigation"
                aria-label="Pagination Navigation"
                class="items-center justify-between sm:flex"
            >
                <div class="flex justify-center mt-2 md:flex-none md:justify-end sm:mt-0">

                    @if (!$paginator->onFirstPage())
                        <a
                            class="relative inline-flex items-center text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border cursor-pointer text-pg-primary-500 dark:text-pg-primary-300 dark:bg-pg-primary-600 border-pg-primary-300 dark:border-transparent rounded-l-md hover:text-pg-primary-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-pg-primary-100 active:text-pg-primary-500"
                            wire:navigate
                            wire:click="gotoPage(1, '{{ $paginator->getPageName() }}')"
                        >
                            <x-icon-button>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-left"><path d="m11 17-5-5 5-5"/><path d="m18 17-5-5 5-5"/></svg>
                            </x-icon-button>
                        </a>

                        <a
                            class="relative inline-flex items-center text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border cursor-pointer text-pg-primary-500 dark:text-pg-primary-300 dark:bg-pg-primary-600 border-pg-primary-300 dark:border-transparent hover:text-pg-primary-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-pg-primary-100 active:text-pg-primary-500"
                            wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            wire:navigate
                            rel="next"
                        >
                           <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left"><path d="m15 18-6-6 6-6"/></svg>
                           </x-icon-button>
                        </a>
                    @endif

                    @foreach ($elements as $element)
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="relative z-10 inline-flex items-center px-3 py-2 -ml-px text-sm font-semibold cursor-default select-none">{{ $page }}</span>
                                @elseif (
                                    $page === $paginator->currentPage() + 1 ||
                                        $page === $paginator->currentPage() + 2 ||
                                        $page === $paginator->currentPage() - 1 ||
                                        $page === $paginator->currentPage() - 2)
                                    <a
                                        class="relative inline-flex items-center px-3 py-2 -ml-px text-sm font-semibold leading-5 transition duration-150 ease-in-out cursor-pointer select-none text-zinc-800/25"
                                        wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                    >{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    @if ($paginator->hasMorePages())
                        <a
                            @class([
                                'block' => $paginator->lastPage() - $paginator->currentPage() >= 2,
                                'hidden' => $paginator->lastPage() - $paginator->currentPage() < 2,
                                'select-none cursor-pointer relative inline-flex items-center text-sm font-medium text-pg-primary-500 dark:text-pg-primary-300 bg-white dark:bg-pg-primary-600 border border-pg-primary-300 dark:border-transparent leading-5 hover:text-pg-primary-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-pg-primary-100 active:text-pg-primary-500 transition ease-in-out duration-150'
                            ])
                            wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            wire:navigate
                            rel="next"
                        >
                        <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right"><path d="m9 18 6-6-6-6"/></svg>
                        </x-icon-button>
                        </a>
                        <a
                            class="relative inline-flex items-center text-sm font-medium leading-5 transition duration-150 ease-in-out bg-white border cursor-pointer select-none text-pg-primary-500 dark:text-pg-primary-300 dark:bg-pg-primary-600 border-pg-primary-300 dark:border-transparent rounded-r-md hover:text-pg-primary-400 focus:z-10 focus:outline-none focus:shadow-outline-blue active:bg-pg-primary-100 active:text-pg-primary-500"
                            wire:click="gotoPage({{ $paginator->lastPage() }}, '{{ $paginator->getPageName() }}')"
                            wire:navigate
                        >
                        <x-icon-button>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevrons-right"><path d="m6 17 5-5-5-5"/><path d="m13 17 5-5-5-5"/></svg>
                        </x-icon-button>
                        </a>
                    @endif
                </div>
            </nav>
        @endif

        <div>
            @if ($paginator->hasPages() && in_array($recordCount, ['min', 'short']))
                <nav
                    role="navigation"
                    aria-label="Pagination Navigation"
                    class="items-center justify-between sm:flex"
                >
                    <div class="flex justify-center gap-2 md:flex-none md:justify-end sm:mt-0">
                        <span>
                            {{-- Previous Page Link Disabled --}}
                            @if ($paginator->onFirstPage())
                                <button
                                    disabled
                                    class="inline-flex items-center justify-center px-4 py-2 font-semibold transition-all duration-200 ease-in-out border rounded-md outline-none focus:ring-offset-white focus:shadow-outline group gap-x-2 hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-md text-pg-primary-500 bg-pg-primary-50 ring-0 ring-inset ring-pg-primary-300 hover:bg-pg-primary-100 dark:bg-pg-primary-800 dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-900 dark:text-pg-primary-300 focus-visible:outline-offset-0"
                                >
                                    @lang('Previous')
                                </button>
                            @else
                                @if (method_exists($paginator, 'getCursorName'))
                                    <button
                                        wire:click="setPage('{{ $paginator->previousCursor()->encode() }}','{{ $paginator->getCursorName() }}')"
                                        wire:loading.attr="disabled"
                                        class="p-2 m-1 text-center text-white rounded cursor-pointer select-none bg-pg-primary-600 border-pg-primary-400 border-1 hover:bg-pg-primary-600 hover:border-pg-primary-800 dark:text-pg-primary-300"
                                    >
                                        <svg
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-5 h-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5"
                                            />
                                        </svg>

                                    </button>
                                @else
                                    <button
                                        wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center justify-center px-4 py-2 font-semibold transition-all duration-200 ease-in-out border rounded-md outline-none select-none focus:ring-offset-white focus:shadow-outline group gap-x-2 hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-md text-pg-primary-500 bg-pg-primary-50 ring-0 ring-inset ring-pg-primary-300 hover:bg-pg-primary-100 dark:bg-pg-primary-800 dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-900 dark:text-pg-primary-300 focus-visible:outline-offset-0"
                                    >
                                        @lang('Previous')
                                    </button>
                                @endif
                            @endif
                        </span>

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                @if (method_exists($paginator, 'getCursorName'))
                                    <button
                                        wire:click="setPage('{{ $paginator->nextCursor()->encode() }}','{{ $paginator->getCursorName() }}')"
                                        wire:loading.attr="disabled"
                                        class="p-2 m-1 text-center text-white rounded cursor-pointer select-none bg-pg-primary-600 border-pg-primary-400 border-1 hover:bg-pg-primary-600 hover:border-pg-primary-800 dark:text-pg-primary-300"
                                    >
                                        <svg
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-5 h-5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="m18.75 4.5-7.5 7.5 7.5 7.5m-6-15L5.25 12l7.5 7.5"
                                            />
                                        </svg>

                                    </button>
                                @else
                                    <button
                                        wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center justify-center px-4 py-2 font-semibold transition-all duration-200 ease-in-out border rounded-md outline-none select-none focus:ring-offset-white focus:shadow-outline group gap-x-2 hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-md text-pg-primary-500 bg-pg-primary-50 ring-0 ring-inset ring-pg-primary-300 hover:bg-pg-primary-100 dark:bg-pg-primary-800 dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-900 dark:text-pg-primary-300 focus-visible:outline-offset-0"
                                    >
                                        @lang('Next')
                                    </button>
                                @endif
                            @else
                                <button
                                    disabled
                                    class="inline-flex items-center justify-center px-4 py-2 font-semibold transition-all duration-200 ease-in-out border rounded-md outline-none focus:ring-offset-white focus:shadow-outline group gap-x-2 hover:shadow-sm focus:border-transparent focus:ring-2 disabled:cursor-not-allowed disabled:opacity-80 text-md text-pg-primary-500 bg-pg-primary-50 ring-0 ring-inset ring-pg-primary-300 hover:bg-pg-primary-100 dark:bg-pg-primary-800 dark:ring-pg-primary-600 dark:border-pg-primary-600 dark:hover:bg-pg-primary-900 dark:text-pg-primary-300 focus-visible:outline-offset-0"
                                >
                                    @lang('Next')
                                </button>
                            @endif
                        </span>
                    </div>
                </nav>
            @endif
        </div>
    </div>
</div>
