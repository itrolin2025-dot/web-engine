<x-app-layout>
    <style>
        .layout-card.sortable-ghost {
            opacity: 0.4;
            background-color: rgba(79, 70, 229, 0.1);
        }
        .layout-card.sortable-chosen {
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5);
        }
        .layout-card.sortable-drag {
            opacity: 0.9;
            transform: rotate(1deg);
        }
    </style>

    {{-- Breadcrumb --}}
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
                <li class="flex items-center space-x-2">
                    <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                        href="{{ route('admin.customers-website') }}">Customers Website</a>
                    <i class="fa-solid fa-angle-right text-xs"></i>
                </li>
                <li class="flex items-center space-x-2">
                    <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                        href="{{ route('admin.customers-website.page', $website->id) }}">Pages</a>
                    <i class="fa-solid fa-angle-right text-xs"></i>
                </li>
                <li class="capitalize">{{ $page_type }} Layout</li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="alert flex items-center space-x-2 rounded-lg border border-success bg-success/10 p-4 text-success dark:border-success dark:bg-success/5 mb-4">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Row 1: Website Info Full Width --}}
    <div class="mb-6 mt-2">
        <div class="card p-4 sm:p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Website Info</h3>
                <a href="{{ route('admin.customers-website.edit', $website->id) }}"
                    class="btn h-7 rounded-full bg-slate-150 px-3 text-xs+ font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
                    <i class="fa-solid fa-pen mr-1"></i> Edit
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Title</p>
                    <p class="font-medium text-slate-700 dark:text-navy-100 mt-0.5">{{ $website->title }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Customer</p>
                    <p class="font-medium text-slate-700 dark:text-navy-100 mt-0.5">{{ $website->customer->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Template</p>
                    <p class="font-mono text-sm text-slate-600 dark:text-navy-200 mt-0.5">{{ $website->template->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Domain</p>
                    <p class="font-mono text-sm text-slate-600 dark:text-navy-200 mt-0.5">{{ $website->domain ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Page Type</p>
                    <div class="mt-1">
                        <span class="badge rounded-full bg-info/10 text-info px-2.5 py-1 text-xs font-semibold capitalize">
                            {{ $page_type }}
                        </span>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Total Layouts</p>
                    <div class="mt-1">
                        <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light px-2.5 py-1 text-xs font-semibold">
                            {{ $layouts->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>    {{-- Row 2: Add New Layout (6 cols) + Layout Items (6 cols) --}}
    <div class="mb-4 mt-4"></div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

        {{-- COLUMN LEFT: Add Layout Form --}}
        <div x-data="{ showForm: true }">
            <div class="card">
                <button type="button" @click="showForm = !showForm"
                    class="flex w-full items-center justify-between px-4 py-3 sm:px-5 text-left">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-plus text-primary dark:text-accent-light"></i>
                        <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Add New Layout Item</h3>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                        :class="showForm ? 'rotate-180' : ''"></i>
                </button>

                <div x-data="{ selectedSectionId: '' }"
                    x-show="showForm"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="px-4 pb-5 pt-4 sm:px-5">

                    <form id="add-layout-form" action="{{ route('admin.customers-website.layout.store', [$website->id, $page_type]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Filter Template</span>
                                <select id="filterTemplate" onchange="filterSections()" class="form-select mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                                    <option value="">All Templates</option>
                                    @foreach($sections->pluck('template.name', 'template_id')->unique() as $tid => $tname)
                                        <option value="{{ $tid }}">{{ $tname }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block">
                                <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Filter Slug</span>
                                <select id="filterSlug" onchange="filterSections()" class="form-select mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                                    <option value="">All Slugs</option>
                                    @foreach($sections->pluck('slug')->unique()->sort() as $slug)
                                        <option value="{{ $slug }}">{{ $slug }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">
                                    Section <span class="text-error">*</span>
                                </span>
                                <span id="sectionHint" class="text-xs text-slate-400 dark:text-navy-300 italic">
                                    Pilih filter di atas untuk menampilkan section
                                </span>
                            </div>
                            <input type="hidden" name="templates_section_id" id="sectionSelect" x-model="selectedSectionId" required>
                            @error('templates_section_id') <span class="text-xs text-error">{{ $message }}</span> @enderror

                            <div id="sectionCardGrid" class="mt-2 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-2 xl:grid-cols-3 gap-3 hidden">
                                @foreach($sections as $sec)
                                    <div class="section-card group relative cursor-pointer overflow-hidden rounded-xl border-2 border-slate-200 dark:border-navy-500 bg-white dark:bg-navy-700 transition-all duration-200 hover:border-primary dark:hover:border-accent"
                                        data-id="{{ $sec->id }}"
                                        data-template="{{ $sec->template_id }}"
                                        data-slug="{{ $sec->slug }}"
                                        data-image="{{ $sec->preview ? asset($sec->preview) : '' }}"
                                        onclick="selectSection(this)">
                                        {{-- Image Preview --}}
                                        <div class="relative h-28 w-full overflow-hidden bg-slate-100 dark:bg-navy-800">
                                            @if($sec->preview)
                                                <img src="{{ asset($sec->preview) }}" alt="{{ $sec->name }}"
                                                    onerror="this.src='{{ asset('images/default/broken.png') }}'"
                                                    class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            @else
                                                <img src="{{ asset('images/default/broken.png') }}" alt="No preview"
                                                    class="h-full w-full object-cover opacity-40">
                                            @endif
                                            {{-- Selected Checkmark --}}
                                            <div class="section-check absolute top-2 right-2 hidden h-6 w-6 items-center justify-center rounded-full bg-primary text-white shadow-lg">
                                                <i class="fa-solid fa-check text-xs"></i>
                                            </div>
                                        </div>
                                        {{-- Info --}}
                                        <div class="p-2.5">
                                            <p class="text-xs font-semibold text-slate-700 dark:text-navy-100 truncate">{{ $sec->name }}</p>
                                            <p class="mt-0.5 text-[10px] text-slate-400 dark:text-navy-300 truncate">
                                                <span class="font-mono">[{{ $sec->template?->name ?? '-' }}]</span> ({{ $sec->slug }})
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                    
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div class="flex items-end pb-1">
                                <label class="inline-flex items-center space-x-2 cursor-pointer">
                                    <input name="status" type="checkbox" value="1" checked
                                        class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                                    <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Active</span>
                                </label>
                            </div>
                        </div>

                        <label class="block hidden">
                            <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Content (JSON / Custom Raw)</span>
                            <textarea name="content" id="contentAdd" rows="3" placeholder="Layout content..."
                                class="form-textarea mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
                        </label>

                        <!-- Dynamic Section Content Fields loaded via AJAX -->
                        <div id="dynamicFieldsContainerAdd" class="hidden">
                            <div id="dynamicFieldsListAdd" class="space-y-4 p-3"></div>
                        </div>

                        <input type="hidden" name="position" value="0">

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                                <i class="fa-solid fa-plus mr-1.5"></i> Add Layout
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- END COLUMN LEFT: Add Layout Form --}}

        {{-- COLUMN RIGHT: Layout Items --}}
        <div class="min-w-0">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-table-cells-large text-sm text-slate-400 dark:text-navy-300"></i>
                    <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Layout Items</h3>
                </div>
                <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light px-2.5 py-0.5 text-xs font-semibold">
                    {{ $layouts->count() }}
                </span>
            </div>

            @if($layouts->isEmpty())
                <div class="card flex flex-col items-center justify-center py-12 text-slate-400 dark:text-navy-300">
                    <i class="fa-solid fa-table-cells-large text-4xl mb-3 opacity-40"></i>
                    <p class="text-sm">No layout items yet for {{ $page_type }}.</p>
                    <p class="text-xs mt-1 opacity-70">Click "Add New Layout Item" above to create one.</p>
                </div>
            @else
                {{-- Drag Hint --}}
                <div class="h-2"></div>
                <p class="text-xs text-slate-400 dark:text-navy-300 flex items-center gap-1">
                    <i class="fa-solid fa-grip-vertical"></i> Drag cards to reorder sections
                </p>
                <div class="h-3"></div>

                {{-- Sortable Container --}}
                <div id="layout-sortable" class="space-y-3" x-data="{ expandedCardId: null, toggleCard(cardId) { this.expandedCardId = this.expandedCardId === cardId ? null : cardId; } }">
                    @foreach($layouts as $layout)
                    <div id="layout-row-{{ $layout->id }}" data-id="{{ $layout->id }}" class="layout-card card p-4 cursor-move h-full">
                        {{-- Card Header: Drag Handle + Info + Actions --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 min-w-0">
                                {{-- Drag Handle --}}
                                <div class="drag-handle flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-navy-600 text-slate-400 dark:text-navy-300 hover:bg-slate-200 dark:hover:bg-navy-500 transition-colors">
                                    <i class="fa-solid fa-grip-vertical text-xs"></i>
                                </div>
                                {{-- Position Badge --}}
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full font-semibold text-xs
                                    {{ $layout->status ? 'bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                    {{ $layout->position }}
                                </div>
                                {{-- Section Info --}}
                                <div class="min-w-0">
                                    <p id="layout-title-{{ $layout->id }}" class="font-medium text-slate-700 dark:text-navy-100 truncate">
                                        {{ $layout->section->name ?? 'Section #'.$layout->templates_section_id }}
                                    </p>
                                    <p class="text-xs text-slate-400 dark:text-navy-300 font-mono truncate">
                                        {{ $layout->page_type ?? 'no page type' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center space-x-2 ml-3">
                                {{-- Status Badge --}}
                                <span id="layout-status-{{ $layout->id }}" class="badge rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $layout->status ? 'bg-success/10 text-success' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                    {{ $layout->status ? 'Active' : 'Inactive' }}
                                </span>
                                {{-- Toggle Button --}}
                                <button type="button" @click.prevent="toggleCard('layout-{{ $layout->id }}')"
                                    class="btn h-8 w-8 rounded-lg p-0 hover:bg-slate-100 dark:hover:bg-navy-600">
                                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                                        :class="expandedCardId === 'layout-{{ $layout->id }}' ? 'rotate-180' : ''"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Card Body: Edit Form (collapsible) --}}
                        <div x-show="expandedCardId === 'layout-{{ $layout->id }}'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="mt-4 pt-4 border-t border-slate-150 dark:border-gray-800">

                            <form action="{{ route('admin.customers-website.layout.update', [$website->id, $page_type, $layout->id]) }}"
                                method="POST" enctype="multipart/form-data" class="layout-update-form space-y-4" data-layout-id="{{ $layout->id }}"
                                x-data="{ selectedSectionId: '{{ $layout->templates_section_id }}' }">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="templates_section_id" id="sectionSelectUpdate-{{ $layout->id }}" value="{{ $layout->templates_section_id }}">
                                <input type="hidden" name="position" value="{{ $layout->position }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Section:</span>
                                        <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary dark:bg-accent/10 dark:text-accent-light">
                                            <i class="fa-solid fa-puzzle-piece mr-1"></i>
                                            {{ $layout->section->name ?? 'Section #'.$layout->templates_section_id }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 dark:text-navy-300 font-mono">
                                            [{{ $layout->section->template?->name ?? '-' }}] ({{ $layout->section->slug ?? '-' }})
                                        </span>
                                    </div>
                                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                                        <input name="status" type="checkbox" value="1" {{ $layout->status ? 'checked' : '' }}
                                            class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                                        <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Active</span>
                                    </label>
                                </div>

                                <label class="block hidden">
                                    <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Content (JSON / Custom Raw)</span>
                                    <textarea name="content" id="contentUpdate-{{ $layout->id }}" rows="6" placeholder="Layout content..."
                                        class="form-textarea mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('content', $layout->content) }}</textarea>
                                </label>

                                <!-- Dynamic Section Content Fields loaded via AJAX -->
                                <div id="dynamicFieldsContainerUpdate-{{ $layout->id }}" class="hidden">
                                    <div id="dynamicFieldsListUpdate-{{ $layout->id }}" class="space-y-4 p-3"></div>
                                </div>

                                <div class="flex items-center justify-between pt-4">
                                    {{-- form attribute links this button to the delete form OUTSIDE the update form (no nested forms) --}}
                                    <button type="submit" form="deleteLayoutForm-{{ $layout->id }}"
                                        class="btn bg-error/10 font-medium text-error hover:bg-error/20 dark:bg-error/10 dark:text-error dark:hover:bg-error/20">
                                        <i class="fa-solid fa-trash mr-1.5"></i> Delete
                                    </button>
                                    <button type="submit"
                                        class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                                        <i class="fa-solid fa-check mr-1.5"></i> Save Changes
                                    </button>
                                </div>
                            </form>

                            {{-- Delete form: sibling of update form (valid HTML) --}}
                            <form id="deleteLayoutForm-{{ $layout->id }}"
                                action="{{ route('admin.customers-website.layout.destroy', [$website->id, $page_type, $layout->id]) }}"
                                method="POST" class="layout-delete-form hidden" data-layout-id="{{ $layout->id }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
        {{-- END COLUMN RIGHT: Layout Items --}}

    </div>
    {{-- END Row 2 --}}

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const websiteDomain = '{{ $website->domain ?? "" }}';

        // Select section card
        function selectSection(card) {
            const id = card.dataset.id;
            const hidden = document.getElementById('sectionSelect');
            // Deselect all
            document.querySelectorAll('.section-card').forEach(c => {
                c.classList.remove('border-primary', 'dark:border-accent', 'bg-primary/5', 'dark:bg-accent/5');
                c.classList.add('border-slate-200', 'dark:border-navy-500');
                c.querySelector('.section-check').classList.add('hidden');
            });
            // Select clicked
            card.classList.add('border-primary', 'dark:border-accent', 'bg-primary/5', 'dark:bg-accent/5');
            card.classList.remove('border-slate-200', 'dark:border-navy-500');
            card.querySelector('.section-check').classList.remove('hidden');
            hidden.value = id;
            hidden.dispatchEvent(new Event('change'));
            // Load dynamic fields
            const containerEl = document.getElementById('dynamicFieldsContainerAdd');
            const listEl = document.getElementById('dynamicFieldsListAdd');
            const contentTextarea = document.getElementById('contentAdd');
            if (containerEl && listEl && contentTextarea && typeof window.loadSectionContents === 'function') {
                window.loadSectionContents(id, containerEl, listEl, contentTextarea, contentTextarea.value);
            }
        }

        // Filter section cards by template and slug
        function filterSections() {
            const templateFilter = document.getElementById('filterTemplate').value;
            const slugFilter = document.getElementById('filterSlug').value;
            const sectionSelect = document.getElementById('sectionSelect');
            const grid = document.getElementById('sectionCardGrid');
            const hint = document.getElementById('sectionHint');
            const cards = document.querySelectorAll('.section-card');

            // Show/hide grid based on whether a filter is selected
            const hasFilter = templateFilter !== '' || slugFilter !== '';
            if (grid) grid.classList.toggle('hidden', !hasFilter);
            if (hint) hint.classList.toggle('hidden', hasFilter);

            cards.forEach(card => {
                const matchTemplate = !templateFilter || card.dataset.template === templateFilter;
                const matchSlug = !slugFilter || card.dataset.slug === slugFilter;
                card.style.display = (matchTemplate && matchSlug) ? '' : 'none';
            });

            // Reset selection if selected card is now hidden
            if (sectionSelect.value) {
                const selectedCard = document.querySelector('.section-card[data-id="' + sectionSelect.value + '"]');
                if (selectedCard && selectedCard.style.display === 'none') {
                    sectionSelect.value = '';
                    selectedCard.classList.remove('border-primary', 'dark:border-accent', 'bg-primary/5', 'dark:bg-accent/5');
                    selectedCard.classList.add('border-slate-200', 'dark:border-navy-500');
                    selectedCard.querySelector('.section-check').classList.add('hidden');
                }
            }
        }

        // Function to render dynamic input fields based on templates_sections_content
        window.loadSectionContents = function (sectionId, containerEl, listEl, contentTextarea, existingJsonStr) {
            if (!sectionId) {
                if (containerEl) containerEl.classList.add('hidden');
                if (listEl) listEl.innerHTML = '';
                return;
            }

            let existingData = {};
            if (existingJsonStr) {
                try {
                    existingData = JSON.parse(existingJsonStr);
                } catch (e) {
                    existingData = {};
                }
            }

            fetch('{{ route("admin.customers-website.section-contents", ["sectionId" => "__SECTION_ID__"]) }}'.replace('__SECTION_ID__', sectionId))
                .then(res => res.json())
                .then(data => {
                    listEl.innerHTML = '';
                    if (data.success && data.contents && data.contents.length > 0) {
                        if (containerEl) containerEl.classList.remove('hidden');

                        // Build a map of all items for pairing
                        const contentsMap = {};
                        data.contents.forEach(c => { contentsMap[c.key] = c; });
                        const renderedKeys = new Set();

                        data.contents.forEach(item => {
                            // Skip color items that have a paired TEXT item (they'll be rendered with the text item)
                            const typeLower = (item.type || '').toLowerCase();
                            if (typeLower === 'color') {
                                const pairedKey = item.key.replace(/_color$/, '');
                                const pairedItem = contentsMap[pairedKey];
                                if (pairedItem && (pairedItem.type || '').toLowerCase() === 'text') {
                                    renderedKeys.add(item.key);
                                    return; // Will be rendered alongside the text field
                                }
                            }

                            const fieldWrapper = document.createElement('div');
                            fieldWrapper.className = 'block';

                            const label = document.createElement('label');
                            label.className = 'block space-y-1';

                            const keySpan = document.createElement('span');
                            keySpan.className = 'text-xs font-semibold capitalize text-slate-700 dark:text-navy-100';
                            keySpan.innerHTML = `${item.key.replace(/_/g, ' ')} <span class="text-xs text-slate-400 font-normal">(${item.type || 'text'})</span>`;

                            label.appendChild(keySpan);

                            const val = existingData[item.key] !== undefined ? existingData[item.key] : (item.value || '');
                            const inputName = `dynamic_content[${item.key}]`;
                            const fileInputName = `dynamic_files[${item.key}]`;

                            if (typeLower === 'image' || typeLower === 'file') {
                                const fileInput = document.createElement('input');
                                fileInput.type = 'file';
                                fileInput.name = fileInputName;
                                fileInput.className = 'form-input w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700';

                                const prevDiv = document.createElement('div');
                                prevDiv.className = 'mt-1 flex items-center space-x-2';
                                const img = document.createElement('img');
                                let imgSrc = val;
                                if (val && typeof val === 'string' && val.length > 0) {
                                    if (!val.startsWith('http') && !val.startsWith('/')) {
                                        if (websiteDomain && websiteDomain !== '') {
                                            imgSrc = `{{ asset('images/website') }}/${websiteDomain}/${val}`;
                                        } else {
                                            imgSrc = `{{ asset('storage') }}/${val}`;
                                        }
                                    }
                                    img.src = imgSrc;
                                    img.className = 'h-10 w-10 object-cover rounded border border-slate-200';
                                    const hiddenCurrent = document.createElement('input');
                                    hiddenCurrent.type = 'hidden';
                                    hiddenCurrent.name = inputName;
                                    hiddenCurrent.value = val;

                                    prevDiv.appendChild(img);
                                    prevDiv.appendChild(hiddenCurrent);
                                } else {
                                    img.className = 'h-10 w-10 object-cover rounded border border-slate-200 hidden';
                                    prevDiv.appendChild(img);
                                }

                                fileInput.addEventListener('change', function(e) {
                                    const selectedFile = e.target.files[0];
                                    if (selectedFile) {
                                        const reader = new FileReader();
                                        reader.onload = function(evt) {
                                            img.src = evt.target.result;
                                            img.classList.remove('hidden');
                                        };
                                        reader.readAsDataURL(selectedFile);
                                    }
                                });

                                label.appendChild(fileInput);
                                label.appendChild(prevDiv);
                            } else if (typeLower === 'action') {
                                const select = document.createElement('select');
                                select.name = inputName;
                                select.className = 'form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700';

                                const options = [
                                    { value: 'url', label: 'url' },
                                    { value: 'add_to_cart', label: 'add to cart' },
                                    { value: 'submit', label: 'submit' },
                                    { value: 'do_nothing', label: 'do nothing' }
                                ];

                                options.forEach(opt => {
                                    const option = document.createElement('option');
                                    option.value = opt.value;
                                    option.textContent = opt.label;
                                    if (val && (val.toString().toLowerCase() === opt.value || val.toString().toLowerCase() === opt.label)) {
                                        option.selected = true;
                                    }
                                    select.appendChild(option);
                                });

                                label.appendChild(select);
                            } else if (typeLower === 'color') {
                                const colorContainer = document.createElement('div');
                                colorContainer.className = 'flex items-center space-x-2';

                                const colorInput = document.createElement('input');
                                colorInput.type = 'color';
                                colorInput.value = val && val.startsWith('#') ? val : '#000000';
                                colorInput.className = 'h-8 w-10 cursor-pointer rounded border border-slate-300 bg-transparent p-0.5 dark:border-navy-450';

                                const textInput = document.createElement('input');
                                textInput.type = 'text';
                                textInput.name = inputName;
                                textInput.value = val || '#000000';
                                textInput.className = 'form-input w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs font-mono hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700';

                                colorInput.addEventListener('input', function() {
                                    textInput.value = colorInput.value;
                                    textInput.dispatchEvent(new Event('input', { bubbles: true }));
                                });

                                textInput.addEventListener('input', function() {
                                    if (textInput.value.startsWith('#') && (textInput.value.length === 4 || textInput.value.length === 7)) {
                                        colorInput.value = textInput.value;
                                    }
                                });

                                colorContainer.appendChild(colorInput);
                                colorContainer.appendChild(textInput);
                                label.appendChild(colorContainer);
                            } else if (typeLower === 'long_text' || typeLower === 'textarea' || typeLower === 'description') {
                                const textarea = document.createElement('textarea');
                                textarea.name = inputName;
                                textarea.rows = 3;
                                textarea.value = typeof val === 'object' ? JSON.stringify(val, null, 2) : val;
                                textarea.className = 'form-textarea w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700';
                                label.appendChild(textarea);
                            } else if (typeLower === 'repeater' || Array.isArray(val)) {
                                // 1. Inisialisasi Data Repeater
                                let repeaterItems = [];
                                if (Array.isArray(val)) {
                                    repeaterItems = val;
                                } else if (typeof val === 'string' && val.trim() !== '') {
                                    try {
                                        const parsed = JSON.parse(val);
                                        repeaterItems = Array.isArray(parsed) ? parsed : [];
                                    } catch (e) {
                                        repeaterItems = [];
                                    }
                                }

                                // 2. Hidden Input Utama untuk Simpan JSON Array Final
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type = 'hidden';
                                hiddenInput.name = inputName;
                                hiddenInput.value = JSON.stringify(repeaterItems);
                                label.appendChild(hiddenInput);

                                // 3. Container Utama Repeater
                                const repeaterWrapper = document.createElement('div');
                                repeaterWrapper.className = 'space-y-3';                                    const itemsContainer = document.createElement('div');
                                    itemsContainer.className = 'space-y-4';
                                    repeaterWrapper.appendChild(itemsContainer);

                                // Helper: Sync Data ke Hidden Input & Dynamic JSON Textarea
                                const syncRepeaterValue = () => {
                                    const currentData = [];
                                    itemsContainer.querySelectorAll('.repeater-item-row').forEach(row => {
                                        const lbl = row.querySelector('.repeater-label-input') ? row.querySelector('.repeater-label-input').value : '';
                                        const clr = row.querySelector('.repeater-color-text-input') ? row.querySelector('.repeater-color-text-input').value : '';
                                        const ttl = row.querySelector('.repeater-title-input') ? row.querySelector('.repeater-title-input').value : '';
                                        const ttlClr = row.querySelector('.repeater-title-color-input') ? row.querySelector('.repeater-title-color-input').value : '';
                                        const sub = row.querySelector('.repeater-subtitle-input') ? row.querySelector('.repeater-subtitle-input').value : '';
                                        const subClr = row.querySelector('.repeater-subtitle-color-input') ? row.querySelector('.repeater-subtitle-color-input').value : '';
                                        const btnTxt = row.querySelector('.repeater-button-text-input') ? row.querySelector('.repeater-button-text-input').value : '';
                                        const btnTxtClr = row.querySelector('.repeater-button-text-color-input') ? row.querySelector('.repeater-button-text-color-input').value : '';
                                        const btnClr = row.querySelector('.repeater-button-color-input') ? row.querySelector('.repeater-button-color-input').value : '';
                                        const btnUrl = row.querySelector('.repeater-button-url-input') ? row.querySelector('.repeater-button-url-input').value : '';
                                        const srt = row.querySelector('.repeater-sort-input') ? row.querySelector('.repeater-sort-input').value : '';
                                        const imgVal = row.querySelector('.repeater-image-value') ? row.querySelector('.repeater-image-value').value : '';

                                        currentData.push({
                                            label: lbl,
                                            color: clr,
                                            title: ttl,
                                            title_color: ttlClr,
                                            subtitle: sub,
                                            subtitle_color: subClr,
                                            button_text: btnTxt,
                                            button_text_color: btnTxtClr,
                                            button_color: btnClr,
                                            button_url: btnUrl,
                                            sort: srt,
                                            image: imgVal
                                        });
                                    });

                                    hiddenInput.value = JSON.stringify(currentData);
                                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                                };

                                // Helper: Wrap input with a label above it
                                const makeLabeledField = (labelText, inputEl) => {
                                    const wrapper = document.createElement('div');
                                    const span = document.createElement('span');
                                    span.className = 'mb-1 block text-xs font-semibold capitalize text-slate-700 dark:text-navy-100';
                                    span.textContent = labelText;
                                    wrapper.appendChild(span);
                                    wrapper.appendChild(inputEl);
                                    return wrapper;
                                };

                                // Helper: Create a labeled color field (picker + hex input in one row)
                                const makeColorField = (labelText, pickerEl, hexInputEl) => {
                                    const row = document.createElement('div');
                                    row.className = 'flex items-center gap-2';
                                    row.appendChild(pickerEl);
                                    row.appendChild(hexInputEl);
                                    return makeLabeledField(labelText, row);
                                };

                                // Helper: Render Row Item
                                const renderRepeaterRow = (itemData = { label: '', color: '#575757', title: '', title_color: '#000000', subtitle: '', subtitle_color: '#000000', button_text: '', button_text_color: '#ffffff', button_color: '#000000', button_url: '', sort: '1', image: '' }) => {
                                    const row = document.createElement('div');
                                    row.className = 'repeater-item-row rounded-lg border border-slate-200 bg-white dark:bg-navy-700 p-4 shadow-sm dark:border-navy-500';

                                    // Hidden input menyimpan string path/base64 gambar per item
                                    const imageValInput = document.createElement('input');
                                    imageValInput.type = 'hidden';
                                    imageValInput.className = 'repeater-image-value';
                                    imageValInput.value = itemData.image || '';

                                    // 1. Upload & Preview Image Element
                                    const imgContainer = document.createElement('div');
                                    imgContainer.className = 'flex flex-col h-32';

                                    const imgPreview = document.createElement('img');
                                    let initialImgSrc = itemData.image || '';
                                    if (initialImgSrc && !initialImgSrc.startsWith('http') && !initialImgSrc.startsWith('data:') && !initialImgSrc.startsWith('/')) {
                                        initialImgSrc = websiteDomain ? `{{ asset('images/website') }}/${websiteDomain}/${initialImgSrc}` : `{{ asset('storage') }}/${initialImgSrc}`;
                                    }
                                    imgPreview.src = initialImgSrc;
                                    imgPreview.className = `h-full w-full object-cover rounded border border-slate-200 dark:border-navy-450 ${itemData.image ? '' : 'hidden'}`;

                                    const fileInput = document.createElement('input');
                                    fileInput.type = 'file';
                                    fileInput.accept = 'image/*';
                                    fileInput.className = 'hidden';

                                    const uploadBtn = document.createElement('button');
                                    uploadBtn.type = 'button';
                                    uploadBtn.className = 'btn h-7 w-full rounded bg-slate-150 px-2 text-[10px] font-medium text-slate-700 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-100 dark:hover:bg-navy-450 mt-1.5';
                                    uploadBtn.innerHTML = '<i class="fa-solid fa-image mr-1"></i> Pic';
                                    uploadBtn.addEventListener('click', () => fileInput.click());

                                    fileInput.addEventListener('change', (e) => {
                                        const file = e.target.files[0];
                                        if (file) {
                                            if (file.size > 2 * 1024 * 1024) {
                                                window.showToast('Ukuran gambar maksimal 2MB. File yang dipilih: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB', 'error');
                                                fileInput.value = '';
                                                return;
                                            }
                                            const reader = new FileReader();
                                            reader.onload = (evt) => {
                                                const base64Data = evt.target.result;
                                                imgPreview.src = base64Data;
                                                imgPreview.classList.remove('hidden');
                                                imageValInput.value = base64Data;
                                                syncRepeaterValue();
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    });

                                    imgContainer.appendChild(imgPreview);
                                    imgContainer.appendChild(uploadBtn);
                                    imgContainer.appendChild(fileInput);
                                    imgContainer.appendChild(imageValInput);

                                    // Helper: Create paired picker + hex input for a color
                                    const makeColorPair = (defaultColor, pickerClass, hexClass) => {
                                        const picker = document.createElement('input');
                                        picker.type = 'color';
                                        picker.value = (defaultColor && defaultColor.startsWith('#')) ? defaultColor : '#000000';
                                        picker.className = pickerClass;

                                        const hex = document.createElement('input');
                                        hex.type = 'text';
                                        hex.placeholder = '#000000';
                                        hex.value = defaultColor || '#000000';
                                        hex.className = hexClass;

                                        picker.addEventListener('input', () => {
                                            hex.value = picker.value;
                                            syncRepeaterValue();
                                        });
                                        hex.addEventListener('input', () => {
                                            if (hex.value.startsWith('#') && (hex.value.length === 4 || hex.value.length === 7)) {
                                                picker.value = hex.value;
                                            }
                                            syncRepeaterValue();
                                        });

                                        return { picker, hex };
                                    };

                                    // 2. Input Label
                                    const labelInput = document.createElement('input');
                                    labelInput.type = 'text';
                                    labelInput.placeholder = 'Tag / Label';
                                    labelInput.value = itemData.label || '';
                                    labelInput.className = 'repeater-label-input form-input w-full rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs dark:border-navy-450';
                                    const labelField = makeLabeledField('Tag / Label', labelInput);

                                    // 3. Tag Color (picker + hex)
                                    const tagColor = makeColorPair(itemData.color || '#575757',
                                        'repeater-tag-color-picker h-7 w-10 shrink-0 cursor-pointer rounded-md border border-slate-300 bg-transparent p-0.5 dark:border-navy-450',
                                        'repeater-color-text-input form-input min-w-0 flex-1 rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs font-mono dark:border-navy-450');

                                    // 4. Input Sort (hidden, auto-managed)
                                    const sortInput = document.createElement('input');
                                    sortInput.type = 'hidden';
                                    sortInput.value = itemData.sort !== undefined ? itemData.sort : (itemsContainer.children.length + 1).toString();
                                    sortInput.className = 'repeater-sort-input';

                                    // 5. Input Title
                                    const titleInput = document.createElement('input');
                                    titleInput.type = 'text';
                                    titleInput.placeholder = 'Title';
                                    titleInput.value = itemData.title || '';
                                    titleInput.className = 'repeater-title-input form-input w-full rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs dark:border-navy-450';
                                    const titleField = makeLabeledField('Title', titleInput);

                                    // 6. Title Color (picker + hex)
                                    const titleColor = makeColorPair(itemData.title_color || '#000000',
                                        'repeater-title-color-picker h-7 w-10 shrink-0 cursor-pointer rounded-md border border-slate-300 bg-transparent p-0.5 dark:border-navy-450',
                                        'repeater-title-color-input form-input min-w-0 flex-1 rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs font-mono dark:border-navy-450');

                                    // 7. Input Subtitle
                                    const subtitleInput = document.createElement('input');
                                    subtitleInput.type = 'text';
                                    subtitleInput.placeholder = 'Subtitle / Description';
                                    subtitleInput.value = itemData.subtitle || '';
                                    subtitleInput.className = 'repeater-subtitle-input form-input w-full rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs dark:border-navy-450';
                                    const subtitleField = makeLabeledField('Subtitle', subtitleInput);

                                    // 8. Subtitle Color (picker + hex)
                                    const subtitleColor = makeColorPair(itemData.subtitle_color || '#000000',
                                        'repeater-subtitle-color-picker h-7 w-10 shrink-0 cursor-pointer rounded-md border border-slate-300 bg-transparent p-0.5 dark:border-navy-450',
                                        'repeater-subtitle-color-input form-input min-w-0 flex-1 rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs font-mono dark:border-navy-450');

                                    // 9. Input Button Text
                                    const buttonText = document.createElement('input');
                                    buttonText.type = 'text';
                                    buttonText.placeholder = 'Button Text';
                                    buttonText.value = itemData.button_text || '';
                                    buttonText.className = 'repeater-button-text-input form-input w-full rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs dark:border-navy-450';
                                    const buttonTextField = makeLabeledField('Button Text', buttonText);

                                    // 10. Button Text Color (picker + hex)
                                    const buttonTextColor = makeColorPair(itemData.button_text_color || '#ffffff',
                                        'repeater-button-text-color-picker h-7 w-10 shrink-0 cursor-pointer rounded-md border border-slate-300 bg-transparent p-0.5 dark:border-navy-450',
                                        'repeater-button-text-color-input form-input min-w-0 flex-1 rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs font-mono dark:border-navy-450');

                                    // 11. Button Color (picker + hex)
                                    const buttonColor = makeColorPair(itemData.button_color || '#000000',
                                        'repeater-button-color-picker h-7 w-10 shrink-0 cursor-pointer rounded-md border border-slate-300 bg-transparent p-0.5 dark:border-navy-450',
                                        'repeater-button-color-input form-input min-w-0 flex-1 rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs font-mono dark:border-navy-450');

                                    // 12. Input Button URL
                                    const buttonUrl = document.createElement('input');
                                    buttonUrl.type = 'text';
                                    buttonUrl.placeholder = 'https://...';
                                    buttonUrl.value = itemData.button_url || '';
                                    buttonUrl.className = 'repeater-button-url-input form-input w-full rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs dark:border-navy-450';
                                    const buttonUrlField = makeLabeledField('Button URL', buttonUrl);

                                    labelInput.addEventListener('input', syncRepeaterValue);
                                    titleInput.addEventListener('input', syncRepeaterValue);
                                    subtitleInput.addEventListener('input', syncRepeaterValue);
                                    buttonText.addEventListener('input', syncRepeaterValue);
                                    buttonUrl.addEventListener('input', syncRepeaterValue);
                                    sortInput.addEventListener('input', syncRepeaterValue);

                                    // 7. Tombol Hapus Baris
                                    const removeBtn = document.createElement('button');
                                    removeBtn.type = 'button';
                                    removeBtn.className = 'btn w-full h-7 rounded-md text-xs font-medium text-error hover:bg-error/10 bg-error/5 dark:bg-error/10 dark:hover:bg-error/20';
                                    removeBtn.innerHTML = '<i class="fa-solid fa-trash-can mr-1"></i> Delete Item';
                                    removeBtn.addEventListener('click', () => {
                                        row.remove();
                                        syncRepeaterValue();
                                    });

                                    // ─── 2-Column Layout ───
                                    // Left column: Image
                                    const leftCol = document.createElement('div');
                                    leftCol.className = 'flex-shrink-0 w-28';
                                    leftCol.appendChild(makeLabeledField('Image', imgContainer));

                                    // Helper to build a row: text field (2 cols) + color field (1 col)
                                    const makePairRow = (textField, colorField) => {
                                        const pairRow = document.createElement('div');
                                        pairRow.className = 'grid grid-cols-3 gap-2 items-end';
                                        const textCol = document.createElement('div');
                                        textCol.className = 'col-span-2';
                                        textCol.appendChild(textField);
                                        pairRow.appendChild(textCol);
                                        pairRow.appendChild(colorField);
                                        return pairRow;
                                    };

                                    // Right column: Label + Tag Color, Title + Title Color, Subtitle + Subtitle Color, Button fields
                                    const rightCol = document.createElement('div');
                                    rightCol.className = 'flex-1 space-y-2';

                                    // Row: Tag / Label (wide) + Tag Color
                                    rightCol.appendChild(makePairRow(labelField, makeColorField('Tag Color', tagColor.picker, tagColor.hex)));

                                    // Row: Title (wide) + Title Color
                                    rightCol.appendChild(makePairRow(titleField, makeColorField('Title Color', titleColor.picker, titleColor.hex)));

                                    // Row: Subtitle (wide) + Subtitle Color
                                    rightCol.appendChild(makePairRow(subtitleField, makeColorField('Subtitle Color', subtitleColor.picker, subtitleColor.hex)));

                                    // Row: Button Text (wide) + Button Text Color
                                    rightCol.appendChild(makePairRow(buttonTextField, makeColorField('Button Text Color', buttonTextColor.picker, buttonTextColor.hex)));

                                    // Row: Button URL (wide) + Button Color
                                    rightCol.appendChild(makePairRow(buttonUrlField, makeColorField('Button Color', buttonColor.picker, buttonColor.hex)));

                                    // Main grid: left + right
                                    const gridRow = document.createElement('div');
                                    gridRow.className = 'flex gap-3';
                                    gridRow.appendChild(leftCol);
                                    gridRow.appendChild(rightCol);

                                    // Delete button full width below
                                    const deleteRow = document.createElement('div');
                                    deleteRow.className = 'mt-3 pt-3 border-t border-slate-200 dark:border-navy-600';
                                    deleteRow.appendChild(removeBtn);

                                    // Masukkan elemen ke row
                                    row.appendChild(gridRow);
                                    row.appendChild(deleteRow);

                                    itemsContainer.appendChild(row);
                                };

                                // Render data awal
                                if (repeaterItems.length > 0) {
                                    repeaterItems.forEach(item => renderRepeaterRow({
                                        label: item.label || '',
                                        color: item.color || '#575757',
                                        title: item.title || '',
                                        title_color: item.title_color || '#000000',
                                        subtitle: item.subtitle || '',
                                        subtitle_color: item.subtitle_color || '#000000',
                                        button_text: item.button_text || '',
                                        button_text_color: item.button_text_color || '#ffffff',
                                        button_color: item.button_color || '#000000',
                                        button_url: item.button_url || '',
                                        sort: item.sort || '1',
                                        image: item.image || ''
                                    }));
                                } else {
                                    renderRepeaterRow();
                                }

                                // Tombol Add Item
                                const addMoreBtn = document.createElement('button');
                                addMoreBtn.type = 'button';
                                addMoreBtn.className = 'btn mt-3 block mx-auto h-7 rounded-lg bg-primary/10 px-3 text-xs font-medium text-primary hover:bg-primary/20 dark:bg-accent/10 dark:text-accent-light dark:hover:bg-accent/20';
                                addMoreBtn.innerHTML = '<i class="fa-solid fa-plus mr-1"></i> Add Item';
                                addMoreBtn.addEventListener('click', () => {
                                    renderRepeaterRow();
                                    syncRepeaterValue();
                                });

                                repeaterWrapper.appendChild(addMoreBtn);
                                label.appendChild(repeaterWrapper);

                                syncRepeaterValue();
                            } else {
                                const input = document.createElement('input');
                                input.type = 'text';
                                input.name = inputName;
                                input.value = val;
                                input.className = 'form-input w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700';

                                // Check if there's a paired color field (e.g., tag -> tag_color)
                                const colorKey = item.key + '_color';
                                const colorItem = contentsMap[colorKey];
                                if (colorItem && typeLower !== 'color') {
                                    const colorVal = existingData[colorKey] !== undefined ? existingData[colorKey] : (colorItem.value || '#000000');
                                    const colorInputName = `dynamic_content[${colorKey}]`;

                                    // Create grid row: text + color side by side
                                    const gridRow = document.createElement('div');
                                    gridRow.className = 'grid grid-cols-3 gap-2 items-end';

                                    // Text field (2 cols)
                                    const textCol = document.createElement('div');
                                    textCol.className = 'col-span-2';
                                    const textLabel = document.createElement('span');
                                    textLabel.className = 'text-xs font-semibold capitalize text-slate-700 dark:text-navy-100 mb-1 block';
                                    textLabel.textContent = item.key.replace(/_/g, ' ');
                                    input.className = 'form-input w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700';
                                    textCol.appendChild(textLabel);
                                    textCol.appendChild(input);

                                    // Color field (1 col) - picker + hex input (same as background_color/button_color)
                                    const colorCol = document.createElement('div');
                                    colorCol.className = 'col-span-1';
                                    const colorLabel = document.createElement('span');
                                    colorLabel.className = 'text-xs font-semibold capitalize text-slate-700 dark:text-navy-100 mb-1 block';
                                    colorLabel.textContent = 'color';

                                    const colorRow = document.createElement('div');
                                    colorRow.className = 'flex items-center space-x-1.5';

                                    const colorInput = document.createElement('input');
                                    colorInput.type = 'color';
                                    colorInput.value = colorVal && colorVal.startsWith('#') ? colorVal : '#000000';
                                    colorInput.className = 'h-8 w-9 shrink-0 cursor-pointer rounded-md border border-slate-300 bg-transparent p-0.5 dark:border-navy-450';

                                    const colorHexInput = document.createElement('input');
                                    colorHexInput.type = 'text';
                                    colorHexInput.name = colorInputName;
                                    colorHexInput.value = colorVal || '#000000';
                                    colorHexInput.className = 'form-input w-full min-w-0 rounded-md border border-slate-300 bg-white px-2 py-1 text-xs font-mono hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700';

                                    colorInput.addEventListener('input', function() {
                                        colorHexInput.value = colorInput.value;
                                        colorHexInput.dispatchEvent(new Event('input', { bubbles: true }));
                                    });

                                    colorHexInput.addEventListener('input', function() {
                                        if (colorHexInput.value.startsWith('#') && (colorHexInput.value.length === 4 || colorHexInput.value.length === 7)) {
                                            colorInput.value = colorHexInput.value;
                                        }
                                    });

                                    colorRow.appendChild(colorInput);
                                    colorRow.appendChild(colorHexInput);
                                    colorCol.appendChild(colorLabel);
                                    colorCol.appendChild(colorRow);

                                    gridRow.appendChild(textCol);
                                    gridRow.appendChild(colorCol);
                                    fieldWrapper.appendChild(gridRow);
                                    renderedKeys.add(colorKey);
                                    listEl.appendChild(fieldWrapper);
                                    return;
                                } else {
                                    // No paired color - render text field normally
                                    label.appendChild(input);
                                }
                            }

                            fieldWrapper.appendChild(label);
                            listEl.appendChild(fieldWrapper);
                        });

                        // Helper function to update contentTextarea in real-time
                        const updateTextareaFromDynamic = () => {
                            const obj = {};
                            listEl.querySelectorAll('input[type="text"], input[type="hidden"], input[type="color"], textarea, select').forEach(el => {
                                const match = el.name.match(/dynamic_content\[(.*?)\]/);
                                if (match && match[1]) {
                                    let rawVal = el.value;
                                    let parsedVal = rawVal;
                                    if (typeof rawVal === 'string' && (rawVal.trim().startsWith('[') || rawVal.trim().startsWith('{'))) {
                                        try {
                                            parsedVal = JSON.parse(rawVal);
                                        } catch (e) {
                                            parsedVal = rawVal;
                                        }
                                    }
                                    obj[match[1]] = parsedVal;
                                }
                            });
                            if (Object.keys(obj).length > 0) {
                                contentTextarea.value = JSON.stringify(obj, null, 2);
                            }
                        };

                        listEl.querySelectorAll('input[type="text"], input[type="hidden"], input[type="color"], textarea, select').forEach(el => {
                            el.addEventListener('input', updateTextareaFromDynamic);
                            el.addEventListener('change', updateTextareaFromDynamic);
                        });

                        // Store reference for pre-submit sync
                        if (!window.__dynamicUpdateFns) window.__dynamicUpdateFns = [];
                        window.__dynamicUpdateFns.push(updateTextareaFromDynamic);

                        // Initial sync if textarea was empty
                        if (!existingJsonStr || existingJsonStr.trim() === '') {
                            updateTextareaFromDynamic();
                        }
                    } else {
                        listEl.innerHTML = '<p class="text-xs text-slate-400 font-normal italic">Tidak ada template content tambahan untuk section ini.</p>';
                    }
                })
                .catch(err => console.error('Error fetching section contents:', err));
        };

        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = '{{ csrf_token() }}';

        window.showToast = function(message, type = 'success') {
            const existing = document.getElementById('ajax-toast-notification');
            if (existing) existing.remove();

            const isSuccess = type === 'success';
            const accent = isSuccess ? '#10b981' : '#f43f5e';

            const toast = document.createElement('div');
            toast.id = 'ajax-toast-notification';
            // Inline styles agar tidak bergantung pada Tailwind build (class utility bisa belum ter-generate)
            toast.style.cssText = `
                position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 99999;
                display: flex; align-items: center; gap: 0.75rem;
                background: #1e293b; color: #f1f5f9;
                border: 1px solid ${accent}55; border-radius: 0.75rem;
                padding: 0.75rem 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.45);
                font-family: inherit; max-width: 22rem;
                opacity: 0; transform: translateY(15px);
                transition: opacity 0.3s ease, transform 0.3s ease;
            `;
            toast.innerHTML = `
                <div style="flex-shrink:0; width:2rem; height:2rem; display:flex; align-items:center; justify-content:center; border-radius:0.5rem; background:${accent}; color:#fff;">
                    <i class="fa-solid ${isSuccess ? 'fa-check' : 'fa-xmark'}" style="font-size:0.8rem;"></i>
                </div>
                <div style="min-width:0;">
                    <h5 style="font-size:0.65rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:${accent}; margin:0 0 0.125rem;">${isSuccess ? 'Berhasil' : 'Gagal'}</h5>
                    <p style="font-size:0.75rem; font-weight:500; margin:0; word-break:break-word;">${message}</p>
                </div>
            `;
            document.body.appendChild(toast);

            // Trigger masuk (fade + slide up)
            requestAnimationFrame(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(15px)';
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        };

            async function handleFormSubmit(form, onSuccess) {
                const submitBtn = form.querySelector('button[type="submit"]');
                const origText = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Saving...';
                }

                try {
                    const formData = new FormData(form);
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput && methodInput.value) {
                        formData.set('_method', methodInput.value);
                    }

                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        showToast(data.message || 'Operation completed successfully.', 'success');
                        if (onSuccess) onSuccess(data);
                    } else {
                        console.error('Save failed response:', data);
                        showToast(data.message || 'Error occurred. Please check input.', 'error');
                    }
                } catch (err) {
                    console.error('Submit error:', err);
                    showToast('Network error or server failed to respond: ' + err.message, 'error');
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = origText;
                    }
                }
            }

            // Layout content data embedded from server (reliable, no DOM parsing issues)
            window.__layoutContentData = {
                @foreach($layouts as $layout)
                    '{{ $layout->id }}': @json(json_decode($layout->content, true) ?: new \stdClass),
                @endforeach
            };

            // Auto initialize Edit forms on page load
            document.querySelectorAll('[id^="sectionSelectUpdate-"]').forEach(selectEl => {
                const layoutId = selectEl.id.replace('sectionSelectUpdate-', '');
                const containerEl = document.getElementById(`dynamicFieldsContainerUpdate-${layoutId}`);
                const listEl = document.getElementById(`dynamicFieldsListUpdate-${layoutId}`);
                const contentTextarea = document.getElementById(`contentUpdate-${layoutId}`);
                if (selectEl.value && containerEl && listEl && contentTextarea) {
                    const existingJson = JSON.stringify(window.__layoutContentData[layoutId] || {});
                    window.loadSectionContents(selectEl.value, containerEl, listEl, contentTextarea, existingJson);
                }
            });

            // Global submit delegation to ensure no form submit is missed
            document.addEventListener('submit', function (e) {
                const targetForm = e.target;
                if (!targetForm) return;

                // Pre-submit sync: ensure all dynamic textareas are up-to-date
                if (window.__dynamicUpdateFns) {
                    window.__dynamicUpdateFns.forEach(fn => fn());
                }

                if (targetForm.id === 'add-layout-form') {
                    e.preventDefault();
                    handleFormSubmit(targetForm, function () {
                        setTimeout(() => window.location.reload(), 500);
                    });
                } else if (targetForm.classList.contains('layout-update-form')) {
                    e.preventDefault();
                    const layoutId = targetForm.dataset.layoutId;
                    handleFormSubmit(targetForm, function (data) {
                        if (data.layout) {
                            if (data.layout.content) {
                                try {
                                    window.__layoutContentData[layoutId] = JSON.parse(data.layout.content);
                                } catch (err) {}
                                const textareaEl = document.getElementById(`contentUpdate-${layoutId}`);
                                if (textareaEl) {
                                    textareaEl.value = data.layout.content;
                                }
                            }
                            const selectEl = document.getElementById(`sectionSelectUpdate-${layoutId}`);
                            const containerEl = document.getElementById(`dynamicFieldsContainerUpdate-${layoutId}`);
                            const listEl = document.getElementById(`dynamicFieldsListUpdate-${layoutId}`);
                            const contentTextarea = document.getElementById(`contentUpdate-${layoutId}`);
                            if (selectEl && selectEl.value && containerEl && listEl && contentTextarea) {
                                const updatedJson = JSON.stringify(window.__layoutContentData[layoutId] || {});
                                window.loadSectionContents(selectEl.value, containerEl, listEl, contentTextarea, updatedJson);
                            }
                            const statusEl = document.getElementById(`layout-status-${layoutId}`);
                            if (statusEl) {
                                statusEl.textContent = data.layout.status ? 'Active' : 'Inactive';
                                statusEl.className = `badge rounded-full px-2 py-0.5 text-xs font-medium ${
                                    data.layout.status ? 'bg-success/10 text-success' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300'
                                }`;
                            }
                        }
                    });
                } else if (targetForm.classList.contains('layout-delete-form')) {
                    e.preventDefault();
                    if (!confirm('Are you sure you want to delete this layout item?')) return;
                    const layoutId = targetForm.dataset.layoutId;
                    handleFormSubmit(targetForm, function () {
                        const row = document.querySelector(`.layout-card[data-id="${layoutId}"]`);
                        if (row) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'scale(0.98)';
                            setTimeout(() => row.remove(), 300);
                        }
                    });
                }
            });

            // Initialize SortableJS for drag & drop reorder
            const sortableContainer = document.getElementById('layout-sortable');
            if (sortableContainer && typeof Sortable !== 'undefined') {
                Sortable.create(sortableContainer, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function(evt) {
                        const order = Array.from(sortableContainer.querySelectorAll('.layout-card')).map(el => el.dataset.id).filter(id => id);
                        fetch('{{ route("admin.customers-website.layout.reorder", [$website->id, $page_type]) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order: order })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Update position badges
                                const cards = sortableContainer.querySelectorAll('.layout-card');
                                cards.forEach((card, index) => {
                                    const badge = card.querySelector('.rounded-full.font-semibold');
                                    if (badge) badge.textContent = index;
                                });
                                window.showToast('Layout order updated!', 'success');
                            } else {
                                console.error('Server validation or processing error:', data);
                                window.showToast(data.message || 'Failed to update layout order.', 'error');
                            }
                        })
                        .catch(err => {
                            console.error('Reorder error:', err);
                            window.showToast('Failed to update order', 'error');
                        });
                    }
                });
            }
        });
    </script>
</x-app-layout>
