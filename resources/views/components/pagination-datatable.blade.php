@if ($paginator->hasPages())
<ol class="pagination space-x-1.5">

    {{-- PREVIOUS --}}
    <li>
        @if ($paginator->onFirstPage())
            <span
                class="flex size-8 items-center justify-center rounded-full bg-slate-150 text-slate-400 dark:bg-navy-500 opacity-50 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="flex size-8 items-center justify-center rounded-full bg-slate-150 text-slate-500 transition-colors
                      hover:bg-slate-300 dark:bg-navy-500 dark:text-navy-200 dark:hover:bg-navy-450">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
        @endif
    </li>

    {{-- PAGE NUMBERS --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <li>
                <span class="flex h-8 min-w-[2rem] items-center justify-center text-slate-400">
                    {{ $element }}
                </span>
            </li>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                <li>
                    @if ($page == $paginator->currentPage())
                        <span
                            class="flex h-8 min-w-[2rem] items-center justify-center rounded-full bg-primary px-3 text-white
                                   dark:bg-accent">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="flex h-8 min-w-[2rem] items-center justify-center rounded-full bg-slate-150 px-3
                                  transition-colors hover:bg-slate-300
                                  dark:bg-navy-500 dark:hover:bg-navy-450">
                            {{ $page }}
                        </a>
                    @endif
                </li>
            @endforeach
        @endif
    @endforeach

    {{-- NEXT --}}
    <li>
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="flex size-8 items-center justify-center rounded-full bg-slate-150 text-slate-500 transition-colors
                      hover:bg-slate-300 dark:bg-navy-500 dark:text-navy-200 dark:hover:bg-navy-450">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span
                class="flex size-8 items-center justify-center rounded-full bg-slate-150 text-slate-400
                       dark:bg-navy-500 opacity-50 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </li>

</ol>
@endif
