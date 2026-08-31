<x-app-layout>
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

    {{-- 2 Column Layout --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- COLUMN LEFT: Website Info --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="card p-4 sm:p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Website Info</h3>
                    <a href="{{ route('admin.customers-website.edit', $website->id) }}"
                        class="btn h-7 rounded-full bg-slate-150 px-3 text-xs+ font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
                        <i class="fa-solid fa-pen mr-1"></i> Edit
                    </a>
                </div>

                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-400 dark:text-navy-300">Title</p>
                        <p class="font-medium text-slate-700 dark:text-navy-100">{{ $website->title }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 dark:text-navy-300">Customer</p>
                        <p class="font-medium text-slate-700 dark:text-navy-100">{{ $website->customer->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 dark:text-navy-300">Template</p>
                        <p class="font-mono text-sm text-slate-600 dark:text-navy-200">{{ $website->template->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 dark:text-navy-300">Domain</p>
                        <p class="font-mono text-sm text-slate-600 dark:text-navy-200">{{ $website->domain ?: '-' }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-400 dark:text-navy-300">Page Type</p>
                        <span class="badge rounded-full bg-info/10 text-info px-2.5 py-1 text-xs font-semibold capitalize">
                            {{ $page_type }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-400 dark:text-navy-300">Total Layouts</p>
                        <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light px-2.5 py-1 text-xs font-semibold">
                            {{ $layouts->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMN RIGHT: Add Layout Form + Layouts List --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Add New Layout Card --}}
            <div class="card" x-data="{ showForm: false }">
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
                    class="border-t border-slate-150 dark:border-navy-600 px-4 pb-5 pt-4 sm:px-5">

                    <form id="add-layout-form" action="{{ route('admin.customers-website.layout.store', [$website->id, $page_type]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-1">
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">
                                    Section <span class="text-error">*</span>
                                </span>

                                <!-- Select Dropdown -->
                                <select name="templates_section_id" id="sectionSelect" x-model="selectedSectionId" onchange="updateSectionPreview(this)" class="form-select mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" required>
                                    <option value="">Select Section</option>
                                    @foreach($sections as $sec)
                                        <option value="{{ $sec->id }}" data-image="{{ $sec->preview ? asset($sec->preview) : '' }}">
                                            [{{ $sec->template?->name ?? 'No Template' }}] ({{ $sec->slug }}) - {{ $sec->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('templates_section_id') <span class="text-xs text-error">{{ $message }}</span> @enderror

                                <!-- Baris Kedua: Preview Image -->
                                <div id="previewContainer" class="mt-3 hidden">
                                    <span class="text-xs text-slate-500 dark:text-navy-300 mb-1 block">Preview Section:</span>
                                    <div class="w-full rounded-lg overflow-hidden border border-slate-200 dark:border-navy-500 bg-slate-100 dark:bg-navy-800 flex items-center justify-center p-2">
                                        <img id="sectionPreviewImage" src="" alt="Section Preview" class="max-w-full h-auto max-h-[600px] object-contain rounded transition-all duration-300">
                                    </div>
                                </div>
                            </label>
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

                        <label class="block">
                            <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Content (JSON / Custom Raw)</span>
                            <textarea name="content" id="contentAdd" rows="3" placeholder="Layout content..."
                                class="form-textarea mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
                        </label>

                        <!-- Dynamic Section Content Fields loaded via AJAX -->
                        <div id="dynamicFieldsContainerAdd" class="hidden space-y-3 rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-navy-500 dark:bg-navy-800">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-navy-200 border-b pb-1 border-slate-200 dark:border-navy-600 flex items-center">
                                <i class="fa-solid fa-sliders mr-1.5 text-primary dark:text-accent-light"></i> Section Dynamic Content Fields
                            </h4>
                            <div id="dynamicFieldsListAdd" class="space-y-3"></div>
                        </div>

                        <label class="block">
                            <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Position</span>
                            <input name="position" value="0" placeholder="0"
                                class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                type="number" min="0">
                        </label>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                                <i class="fa-solid fa-plus mr-1.5"></i> Add Layout
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Layouts Accordion List --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 sm:px-5 border-b border-slate-150 dark:border-navy-600">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-table-cells-large text-sm text-slate-400 dark:text-navy-300"></i>
                        <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Layout Items</h3>
                    </div>
                    <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light px-2.5 py-0.5 text-xs font-semibold">
                        {{ $layouts->count() }}
                    </span>
                </div>

                @if($layouts->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400 dark:text-navy-300">
                        <i class="fa-solid fa-table-cells-large text-4xl mb-3 opacity-40"></i>
                        <p class="text-sm">No layout items yet for {{ $page_type }}.</p>
                        <p class="text-xs mt-1 opacity-70">Click "Add New Layout Item" above to create one.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-150 dark:divide-navy-600">
                        @foreach($layouts as $index => $layout)
                        <div id="layout-row-{{ $layout->id }}" x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }">

                            {{-- Accordion Header --}}
                            <button type="button"
                                class="flex w-full items-center justify-between px-4 py-4 sm:px-5 text-left hover:bg-slate-50 dark:hover:bg-navy-600 transition-colors"
                                @click="open = !open">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full font-semibold text-xs
                                        {{ $layout->status ? 'bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                        {{ $layout->position }}
                                    </div>
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
                                    <span id="layout-status-{{ $layout->id }}" class="badge rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ $layout->status ? 'bg-success/10 text-success' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                        {{ $layout->status ? 'Active' : 'Inactive' }}
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                                        :class="open ? 'rotate-180' : ''"></i>
                                </div>
                            </button>

                            {{-- Accordion Body --}}
                            <div x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="bg-slate-50 dark:bg-navy-600 border-t border-slate-150 dark:border-navy-500 px-4 pb-4 pt-4 sm:px-5">

                                <form action="{{ route('admin.customers-website.layout.update', [$website->id, $page_type, $layout->id]) }}"
                                    method="POST" enctype="multipart/form-data" class="layout-update-form space-y-4" data-layout-id="{{ $layout->id }}"
                                    x-data="{ selectedSectionId: '{{ $layout->templates_section_id }}' }">
                                    @csrf
                                    @method('PUT')

                                    <label class="block">
                                        <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Section <span class="text-error">*</span></span>
                                        <select name="templates_section_id"
                                            id="sectionSelectUpdate-{{ $layout->id }}"
                                            x-model="selectedSectionId"
                                            onchange="updateSectionPreviewEdit(this, {{ $layout->id }})"
                                            class="form-select mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" required>
                                            <option value="">Select Section</option>
                                            @foreach($sections as $sec)
                                                <option value="{{ $sec->id }}"
                                                    data-image="{{ $sec->preview ? asset($sec->preview) : '' }}"
                                                    {{ $layout->templates_section_id == $sec->id ? 'selected' : '' }}>
                                                    [{{ $sec->template?->name ?? 'No Template' }}] ({{ $sec->slug }}) - {{ $sec->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        {{-- Preview current section --}}
                                        @php
                                            $currentSection = $sections->firstWhere('id', $layout->templates_section_id);
                                        @endphp
                                        <div id="previewContainerUpdate-{{ $layout->id }}" class="mt-3 {{ $currentSection && $currentSection->preview ? '' : 'hidden' }}">
                                            <span class="text-xs text-slate-500 dark:text-navy-300 mb-1 block">Preview Section:</span>
                                            <div class="w-full rounded-lg overflow-hidden border border-slate-200 dark:border-navy-500 bg-slate-100 dark:bg-navy-800 flex items-center justify-center p-2">
                                                <img id="sectionPreviewImageUpdate-{{ $layout->id }}"
                                                    src="{{ $currentSection && $currentSection->preview ? asset($currentSection->preview) : '' }}"
                                                    alt="Section Preview"
                                                    class="max-w-full h-auto max-h-[600px] object-contain rounded transition-all duration-300">
                                            </div>
                                        </div>
                                    </label>

                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <label class="block">
                                            <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Position</span>
                                            <input name="position" value="{{ old('position', $layout->position) }}"
                                                class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                                                type="number" min="0">
                                        </label>
                                        <div class="flex items-end pb-1">
                                            <label class="inline-flex items-center space-x-2 cursor-pointer">
                                                <input name="status" type="checkbox" value="1" {{ $layout->status ? 'checked' : '' }}
                                                    class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                                                <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Active</span>
                                            </label>
                                        </div>
                                    </div>

                                    <label class="block">
                                        <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Content (JSON / Custom Raw)</span>
                                        <textarea name="content" id="contentUpdate-{{ $layout->id }}" rows="6" placeholder="Layout content..."
                                            class="form-textarea mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('content', $layout->content) }}</textarea>
                                    </label>

                                    <!-- Dynamic Section Content Fields loaded via AJAX -->
                                    <div id="dynamicFieldsContainerUpdate-{{ $layout->id }}" class="hidden space-y-3 rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-navy-500 dark:bg-navy-800">
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-navy-200 border-b pb-1 border-slate-200 dark:border-navy-600 flex items-center">
                                            <i class="fa-solid fa-sliders mr-1.5 text-primary dark:text-accent-light"></i> Section Dynamic Content Fields
                                        </h4>
                                        <div id="dynamicFieldsListUpdate-{{ $layout->id }}" class="space-y-3"></div>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-navy-500">
                                        <button type="submit"
                                            class="btn h-8 rounded-full bg-primary px-4 text-xs font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                                            <i class="fa-solid fa-check mr-1.5"></i> Save Changes
                                        </button>
                                    </div>
                                </form>

                                <div class="flex justify-end -mt-8">
                                    <form action="{{ route('admin.customers-website.layout.destroy', [$website->id, $page_type, $layout->id]) }}"
                                        method="POST" class="layout-delete-form" data-layout-id="{{ $layout->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn h-8 rounded-full bg-error/10 px-4 text-xs font-medium text-error hover:bg-error/20">
                                            <i class="fa-solid fa-trash mr-1.5"></i> Delete
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
        {{-- END COLUMN RIGHT --}}

    </div>

    <script>
        const websiteDomain = '{{ $website->domain ?? "" }}';

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

                        data.contents.forEach(item => {
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

                            const typeLower = (item.type || '').toLowerCase();

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
                            } else if (typeLower === 'repeater' || typeof val === 'object') {
                                // 1. Inisialisasi Data Repeater
                                let repeaterItems = [];
                                if (Array.isArray(val)) {
                                    repeaterItems = val;
                                } else if (typeof val === 'string') {
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
                                repeaterWrapper.className = 'space-y-2 rounded-lg border border-slate-200 bg-slate-100/60 p-2.5 dark:border-navy-500 dark:bg-navy-900/40';

                                const itemsContainer = document.createElement('div');
                                itemsContainer.className = 'space-y-2';
                                repeaterWrapper.appendChild(itemsContainer);

                                // Helper: Sync Data ke Hidden Input & Dynamic JSON Textarea
                                const syncRepeaterValue = () => {
                                    const currentData = [];
                                    itemsContainer.querySelectorAll('.repeater-item-row').forEach(row => {
                                        const lbl = row.querySelector('.repeater-label-input').value;
                                        const clr = row.querySelector('.repeater-color-text-input').value;
                                        const srt = row.querySelector('.repeater-sort-input').value;
                                        const imgVal = row.querySelector('.repeater-image-value').value;

                                        currentData.push({
                                            label: lbl,
                                            color: clr,
                                            sort: srt,
                                            image: imgVal
                                        });
                                    });

                                    hiddenInput.value = JSON.stringify(currentData);
                                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                                    hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                                };

                                // Helper: Render Row Item
                                const renderRepeaterRow = (itemData = { label: '', color: '#575757', sort: '1', image: '' }) => {
                                    const row = document.createElement('div');
                                    row.className = 'repeater-item-row flex flex-col space-y-2 rounded-md border border-slate-200 bg-white p-2.5 shadow-sm dark:border-navy-500 dark:bg-navy-700 sm:flex-row sm:items-center sm:space-x-2 sm:space-y-0';

                                    // Hidden input menyimpan string path/base64 gambar per item
                                    const imageValInput = document.createElement('input');
                                    imageValInput.type = 'hidden';
                                    imageValInput.className = 'repeater-image-value';
                                    imageValInput.value = itemData.image || '';

                                    // 1. Upload & Preview Image Element
                                    const imgContainer = document.createElement('div');
                                    imgContainer.className = 'flex items-center space-x-2 shrink-0';

                                    const imgPreview = document.createElement('img');
                                    let initialImgSrc = itemData.image || '';
                                    if (initialImgSrc && !initialImgSrc.startsWith('http') && !initialImgSrc.startsWith('data:') && !initialImgSrc.startsWith('/')) {
                                        initialImgSrc = websiteDomain ? `{{ asset('images/website') }}/${websiteDomain}/${initialImgSrc}` : `{{ asset('storage') }}/${initialImgSrc}`;
                                    }
                                    imgPreview.src = initialImgSrc;
                                    imgPreview.className = `h-9 w-9 object-cover rounded border border-slate-200 dark:border-navy-450 ${itemData.image ? '' : 'hidden'}`;

                                    const fileInput = document.createElement('input');
                                    fileInput.type = 'file';
                                    fileInput.accept = 'image/*';
                                    fileInput.className = 'hidden';

                                    const uploadBtn = document.createElement('button');
                                    uploadBtn.type = 'button';
                                    uploadBtn.className = 'btn h-8 rounded bg-slate-150 px-2.5 text-xs font-medium text-slate-700 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-100 dark:hover:bg-navy-450 shrink-0';
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

                                    // 2. Input Label
                                    const labelInput = document.createElement('input');
                                    labelInput.type = 'text';
                                    labelInput.placeholder = 'Tag / Label';
                                    labelInput.value = itemData.label || '';
                                    labelInput.className = 'repeater-label-input form-input w-full rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs dark:border-navy-450';

                                    // 3. Color Picker Wrapper
                                    const colorPickerDiv = document.createElement('div');
                                    colorPickerDiv.className = 'flex items-center space-x-1 shrink-0';

                                    const colorPicker = document.createElement('input');
                                    colorPicker.type = 'color';
                                    colorPicker.value = (itemData.color && itemData.color.startsWith('#')) ? itemData.color : '#575757';
                                    colorPicker.className = 'h-7 w-8 cursor-pointer rounded border border-slate-300 bg-transparent p-0.5 dark:border-navy-450';

                                    const colorTextInput = document.createElement('input');
                                    colorTextInput.type = 'text';
                                    colorTextInput.placeholder = '#000000';
                                    colorTextInput.value = itemData.color || '#575757';
                                    colorTextInput.className = 'repeater-color-text-input form-input w-20 rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs font-mono dark:border-navy-450';

                                    colorPicker.addEventListener('input', () => {
                                        colorTextInput.value = colorPicker.value;
                                        syncRepeaterValue();
                                    });
                                    colorTextInput.addEventListener('input', () => {
                                        if (colorTextInput.value.startsWith('#') && (colorTextInput.value.length === 4 || colorTextInput.value.length === 7)) {
                                            colorPicker.value = colorTextInput.value;
                                        }
                                        syncRepeaterValue();
                                    });

                                    colorPickerDiv.appendChild(colorPicker);
                                    colorPickerDiv.appendChild(colorTextInput);

                                    // 4. Input Sort
                                    const sortInput = document.createElement('input');
                                    sortInput.type = 'number';
                                    sortInput.placeholder = 'Sort';
                                    sortInput.value = itemData.sort !== undefined ? itemData.sort : (itemsContainer.children.length + 1).toString();
                                    sortInput.className = 'repeater-sort-input form-input w-16 rounded-md border border-slate-300 bg-transparent px-2 py-1 text-xs dark:border-navy-450';

                                    labelInput.addEventListener('input', syncRepeaterValue);
                                    sortInput.addEventListener('input', syncRepeaterValue);

                                    // 5. Tombol Hapus Baris
                                    const removeBtn = document.createElement('button');
                                    removeBtn.type = 'button';
                                    removeBtn.className = 'btn h-7 w-7 rounded-md p-0 text-error hover:bg-error/10 shrink-0 self-end sm:self-center';
                                    removeBtn.innerHTML = '<i class="fa-solid fa-trash-can text-xs"></i>';
                                    removeBtn.addEventListener('click', () => {
                                        row.remove();
                                        syncRepeaterValue();
                                    });

                                    // Masukkan elemen ke row
                                    row.appendChild(imgContainer);
                                    row.appendChild(labelInput);
                                    row.appendChild(colorPickerDiv);
                                    row.appendChild(sortInput);
                                    row.appendChild(removeBtn);

                                    itemsContainer.appendChild(row);
                                };

                                // Render data awal
                                if (repeaterItems.length > 0) {
                                    repeaterItems.forEach(item => renderRepeaterRow(item));
                                } else {
                                    renderRepeaterRow();
                                }

                                // Tombol Add Item
                                const addMoreBtn = document.createElement('button');
                                addMoreBtn.type = 'button';
                                addMoreBtn.className = 'btn mt-2 h-7 rounded-lg bg-primary/10 px-3 text-xs font-medium text-primary hover:bg-primary/20 dark:bg-accent/10 dark:text-accent-light dark:hover:bg-accent/20';
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
                                label.appendChild(input);
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

        // Global updateSectionPreview for Create (defined before DOMContentLoaded for inline onchange attribute)
        function updateSectionPreview(selectElement) {
            if (!selectElement) return;
            const selectedOption = selectElement.options ? selectElement.options[selectElement.selectedIndex] : null;
            const imageUrl = selectedOption ? selectedOption.getAttribute('data-image') : '';

            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('sectionPreviewImage');

            if (selectElement.value && imageUrl) {
                if (previewImage) previewImage.src = imageUrl;
                if (previewContainer) previewContainer.classList.remove('hidden');
            } else {
                if (previewContainer) previewContainer.classList.add('hidden');
            }

            // Load dynamic fields
            const containerEl = document.getElementById('dynamicFieldsContainerAdd');
            const listEl = document.getElementById('dynamicFieldsListAdd');
            const contentTextarea = document.getElementById('contentAdd');
            if (containerEl && listEl && contentTextarea && typeof window.loadSectionContents === 'function') {
                window.loadSectionContents(selectElement.value, containerEl, listEl, contentTextarea, contentTextarea.value);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = '{{ csrf_token() }}';

        window.showToast = function(message, type = 'success') {
            const existing = document.getElementById('ajax-toast-notification');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.id = 'ajax-toast-notification';
            toast.style.zIndex = '99999';
            toast.className = `flex items-center space-x-3 rounded-xl border px-4 py-3 shadow-2xl fixed top-6 right-6 transition-all duration-300 transform translate-y-0 ${
                type === 'success' 
                    ? 'border-emerald-500/30 bg-emerald-50 text-emerald-800 dark:bg-navy-700 dark:text-emerald-300 dark:border-emerald-500/40' 
                    : 'border-rose-500/30 bg-rose-50 text-rose-800 dark:bg-navy-700 dark:text-rose-300 dark:border-rose-500/40'
            }`;
            toast.innerHTML = `
                <div class="flex h-8 w-8 items-center justify-center rounded-lg ${type === 'success' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'}">
                    <i class="fa-solid ${type === 'success' ? 'fa-check' : 'fa-xmark'} text-sm"></i>
                </div>
                <div>
                    <h5 class="text-xs font-bold uppercase tracking-wider ${type === 'success' ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300'}">${type === 'success' ? 'Berhasil' : 'Gagal'}</h5>
                    <p class="text-xs font-medium">${message}</p>
                </div>
            `;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-15px)';
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

            const sectionSelectAdd = document.getElementById('sectionSelect');
            if (sectionSelectAdd) {
                sectionSelectAdd.addEventListener('change', function () {
                    updateSectionPreview(this);
                });
            }

            // Global updateSectionPreviewEdit for Edit
            window.updateSectionPreviewEdit = function (selectElement, layoutId) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const imageUrl = selectedOption ? selectedOption.getAttribute('data-image') : '';

                const previewContainer = document.getElementById(`previewContainerUpdate-${layoutId}`);
                const previewImage = document.getElementById(`sectionPreviewImageUpdate-${layoutId}`);

                if (selectElement.value && imageUrl) {
                    previewImage.src = imageUrl;
                    previewContainer.classList.remove('hidden');
                } else {
                    previewContainer.classList.add('hidden');
                }

                // Load dynamic fields for edit (use embedded server data for reliable content matching)
                const containerEl = document.getElementById(`dynamicFieldsContainerUpdate-${layoutId}`);
                const listEl = document.getElementById(`dynamicFieldsListUpdate-${layoutId}`);
                const contentTextarea = document.getElementById(`contentUpdate-${layoutId}`);
                const existingJson = JSON.stringify((window.__layoutContentData && window.__layoutContentData[layoutId]) || {});
                window.loadSectionContents(selectElement.value, containerEl, listEl, contentTextarea, existingJson);
            };

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
                        const row = document.getElementById(`layout-row-${layoutId}`);
                        if (row) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'scale(0.98)';
                            setTimeout(() => row.remove(), 300);
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
