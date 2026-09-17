{{-- Admin Template Index --}}
<x-app-layout>
    <style>
        .dark .template-card .card-info {
            border-top-color: rgba(0, 0, 0, 0.35) !important;
        }
    </style>
    <div>
        <div class="flex mb-4 items-center justify-between py-5 lg:py-6">
            <div class="flex items-center space-x-4">
                <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">{{ $modul_name }}</h2>
                <div class="hidden h-full py-1 sm:flex">
                    <div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>
                </div>
                <ul class="hidden flex-wrap items-center space-x-2 sm:flex">
                    <li class="flex items-center space-x-2">
                        <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                            href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <i class="fa-solid fa-angle-right text-xs"></i>
                    </li>
                    <li>{{ $modul_type }}</li>
                </ul>
            </div>
            
            @if($canAdd)
            <a href="{{ route('admin.template.create') }}" class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <i class="fa-solid fa-plus text-base"></i>
                <span>Add</span>
            </a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert flex items-center justify-between space-x-2 rounded-lg border border-success bg-success/10 p-4 text-success dark:border-success dark:bg-success/5 mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert flex items-center justify-between space-x-2 rounded-lg border border-warning bg-warning/10 p-4 text-warning dark:border-warning dark:bg-warning/5 mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    <p class="font-medium">{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        {{-- Toolbar: Show items (left) + Search (right) --}}
        <div class="mt-4 flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" action="{{ route('admin.template') }}" class="flex shrink-0 items-center gap-2 text-xs text-slate-500 dark:text-navy-300">
                @if($search)
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif
                <span>Show</span>
                <select onchange="this.form.submit()" name="per_page"
                    class="form-select h-9 w-20 rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700">
                    @foreach([10, 25, 50] as $option)
                        <option value="{{ $option }}" {{ $perPage == $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
                <span>items</span>
            </form>

            <form method="GET" action="{{ route('admin.template') }}" id="searchForm" class="flex w-full items-center justify-end gap-2 sm:w-auto sm:ml-auto">
                @if($perPage != 10)
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                @endif
                <div class="relative w-full max-w-xs sm:w-64">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                    <input type="text" name="search" id="searchInput" value="{{ $search }}" placeholder="Search template..." autocomplete="off"
                        class="form-input w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700">
                </div>
            </form>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-2 lg:gap-6 xl:grid-cols-3">
            @foreach($templates as $template)
            <div class="card template-card flex flex-col overflow-hidden">
                <a href="{{ $template->preview ? asset($template->preview) : asset('images/default/broken.png') }}" class="template-preview relative block aspect-square w-full overflow-hidden bg-slate-100 dark:bg-navy-800" onmouseenter="this.querySelector('.preview-overlay').style.opacity='1';this.querySelector('.preview-overlay').style.backgroundColor='rgba(0,0,0,0.4)';this.querySelector('.preview-img').style.transform='scale(1.05)'" onmouseleave="this.querySelector('.preview-overlay').style.opacity='0';this.querySelector('.preview-overlay').style.backgroundColor='rgba(0,0,0,0)';this.querySelector('.preview-img').style.transform='scale(1)'">
                    <img loading="lazy"
                         src="{{ $template->preview ? asset($template->preview) : asset('images/default/broken.png') }}"
                         onerror="this.onerror=null;this.src='{{ asset('images/default/broken.png') }}';this.className='absolute inset-0 m-auto h-24 w-24 object-contain opacity-60 dark:opacity-40'"
                         class="preview-img absolute inset-0 h-full w-full object-cover object-top transition-transform duration-300{{ $template->preview ? '' : ' m-auto h-24 w-24 object-contain opacity-60 dark:opacity-40' }} pointer-events-none"
                         alt="preview">
                    <div class="preview-overlay absolute inset-0 z-10 flex items-center justify-center transition-opacity duration-300" style="opacity:0;background-color:transparent;">
                        <i class="fa-solid fa-magnifying-glass-plus text-2xl text-white"></i>
                    </div>
                </a>
                <div class="card-info flex flex-col border-t border-slate-150 px-4 py-3 dark:border-navy-600/60">
                    <div class="flex items-center justify-between gap-2">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-navy-100 truncate">{{ $template->name }}</h3>
                        <span class="badge shrink-0 rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $template->status ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }}">
                            {{ $template->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <p class="mt-1 truncate text-[11px] text-slate-400 dark:text-navy-300">
                        <i class="fa-solid fa-folder-open mr-1"></i>{{ $template->path ?: '-' }}
                    </p>
                    <div class="mt-2 flex items-center justify-end space-x-1.5">
                        @if($canEdit)
                        <a href="{{ route('admin.template.edit', $template->id) }}" class="btn h-7 w-7 rounded-full bg-info/10 p-0 font-medium text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25">
                            <i class="fa-solid fa-pen text-[11px]"></i>
                        </a>
                        <a href="{{ route('admin.template.section', $template->id) }}" class="btn h-7 w-7 rounded-full bg-info/10 p-0 font-medium text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25">
                            <i class="fa-solid fa-list text-[11px]"></i>
                        </a>
                        @endif
                        @if($canDelete)
                        <form action="{{ route('admin.template.destroy', $template->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this template?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn h-7 w-7 rounded-full bg-error/10 p-0 font-medium text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                                <i class="fa-solid fa-trash text-[11px]"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($templates->count())
            <div class="mt-5 flex flex-col items-center gap-3 sm:flex-row sm:justify-between">
                <p class="text-xs text-slate-500 dark:text-navy-300">
                    Showing
                    <span class="font-semibold text-slate-700 dark:text-navy-100">{{ $templates->firstItem() }}</span>
                    to
                    <span class="font-semibold text-slate-700 dark:text-navy-100">{{ $templates->lastItem() }}</span>
                    of
                    <span class="font-semibold text-slate-700 dark:text-navy-100">{{ $templates->total() }}</span>
                    entries
                </p>
                @if($templates->hasPages())
                    <nav class="flex items-center gap-2" aria-label="Pagination">
                        {{-- Previous --}}
                        @if($templates->onFirstPage())
                            <span class="flex h-8 items-center rounded-lg border border-slate-200 px-3 text-xs text-slate-400 dark:border-navy-500 dark:text-navy-500">
                                <i class="fa-solid fa-angle-left"></i>
                            </span>
                        @else
                            <a href="{{ $templates->previousPageUrl() }}"
                                class="flex h-8 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-600 transition-colors hover:bg-slate-50 dark:border-navy-500 dark:bg-navy-700 dark:text-navy-100 dark:hover:bg-navy-600">
                                <i class="fa-solid fa-angle-left"></i>
                            </a>
                        @endif

                        {{-- Page numbers --}}
                        @foreach($templates->getUrlRange(1, $templates->lastPage()) as $page => $url)
                            @if($page == $templates->currentPage())
                                <span aria-current="page"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary text-xs font-medium text-white dark:bg-accent">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-xs text-slate-600 transition-colors hover:bg-slate-50 dark:border-navy-500 dark:bg-navy-700 dark:text-navy-100 dark:hover:bg-navy-600">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if($templates->hasMorePages())
                            <a href="{{ $templates->nextPageUrl() }}"
                                class="flex h-8 items-center rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-600 transition-colors hover:bg-slate-50 dark:border-navy-500 dark:bg-navy-700 dark:text-navy-100 dark:hover:bg-navy-600">
                                <i class="fa-solid fa-angle-right"></i>
                            </a>
                        @else
                            <span class="flex h-8 items-center rounded-lg border border-slate-200 px-3 text-xs text-slate-400 dark:border-navy-500 dark:text-navy-500">
                                <i class="fa-solid fa-angle-right"></i>
                            </span>
                        @endif
                    </nav>
                @endif
            </div>
        @else
            <div class="mt-6 flex flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 py-10 text-center dark:border-navy-500">
                <i class="fa-solid fa-box-open mb-2 text-2xl text-slate-300 dark:text-navy-400"></i>
                <p class="text-sm font-medium text-slate-500 dark:text-navy-300">No templates found</p>
                @if($search)
                    <a href="{{ route('admin.template') }}" class="mt-1 text-xs text-primary hover:underline dark:text-accent-light">Clear search</a>
                @endif
            </div>
        @endif
    </div>
    </div>

    <!-- GLightbox CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/css/glightbox.min.css">
    <script src="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/js/glightbox.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            GLightbox({
                selector: '.template-preview',
                touchNavigation: true,
                loop: false,
                zoomable: true,
                openEffect: 'zoom',
                closeEffect: 'zoom'
            });

            // Debounced auto-search (3 seconds after typing stops)
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            let searchTimer = null;
            const initialSearch = "{{ $search }}";

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimer);

                    // No change from initial value -> do nothing
                    if (searchInput.value.trim() === initialSearch.trim()) return;

                    // Empty input -> search immediately (reset)
                    if (searchInput.value.trim() === '') {
                        searchForm.submit();
                        return;
                    }

                    searchTimer = setTimeout(() => {
                        searchForm.submit();
                    }, 3000);
                });
            }
        });
    </script>
</x-app-layout>
