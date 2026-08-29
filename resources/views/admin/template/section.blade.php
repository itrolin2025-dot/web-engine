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
                        href="{{ route('admin.template') }}">Template</a>
                    <i class="fa-solid fa-angle-right text-xs"></i>
                </li>
                <li>Section</li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="alert flex items-center space-x-2 rounded-lg border border-success bg-success/10 p-4 text-success dark:border-success dark:bg-success/5 mb-4">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert flex items-center space-x-2 rounded-lg border border-warning bg-warning/10 p-4 text-warning mb-4">
            <i class="fa-solid fa-triangle-exclamation text-lg"></i>
            <p class="font-medium">{{ session('warning') }}</p>
        </div>
    @endif

    {{-- 2 Column Layout --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

        {{-- COLUMN LEFT: Template Info --}}
        <div class="lg:col-span-1 space-y-4">
            <div class="card p-4 sm:p-5">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Template Info</h3>
                    <a href="{{ route('admin.template.edit', $template->id) }}"
                        class="btn h-7 rounded-full bg-slate-150 px-3 text-xs+ font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
                        <i class="fa-solid fa-pen mr-1"></i> Edit
                    </a>
                </div>

                <div class="mb-4 relative group cursor-pointer overflow-hidden rounded-lg border border-slate-200 dark:border-navy-500 bg-slate-100 dark:bg-navy-800 p-2 flex items-center justify-center"
                    onclick="openImageZoom('{{ $template->preview ? asset($template->preview) : 'https://via.placeholder.com/600x400?text=No+Image' }}')">
                    <img src="{{ $template->preview ? asset($template->preview) : 'https://via.placeholder.com/600x400?text=No+Image' }}"
                        class="max-w-full h-auto max-h-96 object-contain rounded-md transition-transform duration-300 group-hover:scale-105"
                        alt="Template Preview">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-md">
                        <span class="text-white text-xs font-semibold px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-sm">
                            <i class="fa-solid fa-magnifying-glass-plus mr-1"></i> Zoom Image
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-slate-400 dark:text-navy-300">Name</p>
                        <p class="font-medium text-slate-700 dark:text-navy-100">{{ $template->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 dark:text-navy-300">Path</p>
                        <p class="font-mono text-sm text-slate-600 dark:text-navy-200">{{ $template->path ?: '-' }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-400 dark:text-navy-300">Status</p>
                        <span class="badge rounded-full {{ $template->status ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }} px-2.5 py-1 text-xs font-semibold">
                            {{ $template->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-slate-400 dark:text-navy-300">Total Sections</p>
                        <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light px-2.5 py-1 text-xs font-semibold">
                            {{ $sections->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- COLUMN RIGHT: Add Form + Sections Accordion --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Add New Section Card --}}
            <div class="card" x-data="{ showForm: false }">
                <button type="button" @click="showForm = !showForm"
                    class="flex w-full items-center justify-between px-4 py-3 sm:px-5 text-left">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-plus text-primary dark:text-accent-light"></i>
                        <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Add New Section</h3>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                        :class="showForm ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="showForm"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="border-t border-slate-150 dark:border-navy-600 px-4 pb-5 pt-4 sm:px-5">

                    <form id="add-section-form" action="{{ route('admin.template.section.store', $template->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Name <span class="text-error">*</span></span>
                                <input name="name" value="{{ old('name') }}" placeholder="Section name"
                                    class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                    type="text" required>
                                @error('name') <span class="text-xs text-error">{{ $message }}</span> @enderror
                            </label>

                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Slug</span>
                                <input name="slug" value="{{ old('slug') }}" placeholder="auto-generated if empty"
                                    class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                    type="text">
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Position</span>
                                <input name="position" value="{{ old('position', 0) }}" placeholder="0"
                                    class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                    type="number" min="0">
                            </label>
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Preview Image</span>
                                <input name="preview"
                                    class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                    type="file" accept="image/*">
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

                        {{-- Optional Initial Content Item --}}
                        <div class="mt-4 border-t border-slate-200 dark:border-navy-500 pt-4">
                            <h4 class="text-xs font-semibold text-slate-700 dark:text-navy-100 uppercase tracking-wide mb-2">
                                Initial Content Item (Optional)
                            </h4>

                            <div class="space-y-2 mt-3">
                                @foreach($contentPresets as $preset)
                                    <div class="rounded-lg border border-slate-200 dark:border-navy-500 bg-white dark:bg-navy-700 p-3 transition-colors">
                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-12 items-center">

                                            {{-- Checkbox --}}
                                            <div class="sm:col-span-1 flex items-center justify-center">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input name="section_contents[{{ $preset['key'] }}][enabled]" type="checkbox" value="1"
                                                        class="form-checkbox is-basic h-5 w-5 rounded border-slate-300 text-primary focus:border-primary dark:border-navy-450 dark:checked:bg-accent dark:checked:border-accent">
                                                </label>
                                            </div>

                                            {{-- Key (readonly) --}}
                                            <div class="sm:col-span-4">
                                                <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Key</label>
                                                <input name="section_contents[{{ $preset['key'] }}][key]" value="{{ $preset['key'] }}"
                                                    class="form-input w-full rounded-lg border border-slate-300 bg-slate-50 px-2.5 py-1.5 text-xs dark:border-navy-450 dark:bg-navy-800 text-slate-500 dark:text-navy-300"
                                                    type="text" readonly>
                                            </div>

                                            {{-- Type (readonly) --}}
                                            <div class="sm:col-span-2">
                                                <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Type</label>
                                                <input name="section_contents[{{ $preset['key'] }}][type]" value="{{ $preset['type'] }}"
                                                    class="form-input w-full rounded-lg border border-slate-300 bg-slate-50 px-2.5 py-1.5 text-xs dark:border-navy-450 dark:bg-navy-800 text-slate-500 dark:text-navy-300"
                                                    type="text" readonly>
                                            </div>

                                            {{-- Value --}}
                                            <div class="sm:col-span-5">
                                                <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Value</label>
                                                <input name="section_contents[{{ $preset['key'] }}][value]" value="{{ $preset['default_value'] }}"
                                                    class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                    type="text" placeholder="Enter value...">
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                                <i class="fa-solid fa-plus mr-1.5"></i> Add Section
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Sections Accordion List --}}
            <div class="card overflow-hidden">
                <div class="flex items-center justify-between px-4 py-3 sm:px-5 border-b border-slate-150 dark:border-navy-600">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-layer-group text-sm text-slate-400 dark:text-navy-300"></i>
                        <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Sections List</h3>
                    </div>
                    <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light px-2.5 py-0.5 text-xs font-semibold">
                        {{ $sections->count() }}
                    </span>
                </div>

                @if($sections->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400 dark:text-navy-300">
                        <i class="fa-solid fa-layer-group text-4xl mb-3 opacity-40"></i>
                        <p class="text-sm">No sections yet.</p>
                        <p class="text-xs mt-1 opacity-70">Click "Add New Section" above to create one.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-150 dark:divide-navy-600">
                        @foreach($sections as $index => $section)
                        <div id="section-row-{{ $section->id }}" x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }">

                            {{-- Accordion Header --}}
                            <button type="button"
                                class="flex w-full items-center justify-between px-4 py-4 sm:px-5 text-left hover:bg-slate-50 dark:hover:bg-navy-600 transition-colors"
                                @click="open = !open">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full font-semibold text-xs
                                        {{ $section->status ? 'bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                        {{ $section->position ?? $loop->iteration }}
                                    </div>
                                    <div class="min-w-0">
                                        <p id="section-title-{{ $section->id }}" class="font-medium text-slate-700 dark:text-navy-100 truncate">{{ $section->name }}</p>
                                        <p id="section-slug-{{ $section->id }}" class="text-xs text-slate-400 dark:text-navy-300 font-mono truncate">{{ $section->slug }}</p>
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center space-x-2 ml-3">
                                    <span class="badge rounded-full px-2.5 py-0.5 text-xs font-medium
                                        {{ $section->contents->count() > 0 ? 'bg-info/10 text-info' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                        {{ $section->contents->count() }} items
                                    </span>
                                    <span id="section-status-{{ $section->id }}" class="badge rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ $section->status ? 'bg-success/10 text-success' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                        {{ $section->status ? 'Active' : 'Inactive' }}
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

                                <form action="{{ route('admin.template.section.update', [$template->id, $section->id]) }}"
                                    method="POST" enctype="multipart/form-data" class="section-update-form space-y-4" data-section-id="{{ $section->id }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-1">
                                        <label class="block">
                                            <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Preview Image</span>
                                             @if($section->preview)
                                                <div class="h-20 w-auto inline-flex items-center justify-center mb-1.5 overflow-hidden rounded-lg border border-slate-200 dark:border-navy-500 bg-slate-100 dark:bg-navy-800 p-1 cursor-pointer relative group"
                                                    onclick="openImageZoom('{{ asset($section->preview) }}')">
                                                    <img class="max-h-full max-w-full h-auto w-auto object-contain rounded" src="{{ asset($section->preview) }}" alt="Preview">
                                                    <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded">
                                                        <i class="fa-solid fa-magnifying-glass-plus text-white text-xs"></i>
                                                    </div>
                                                </div>
                                            @endif
                                            <input name="preview"
                                                class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-white px-3.5 py-1 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                                                type="file" accept="image/*">
                                        </label>
                                    </div>

                                    <br>

                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <label class="block">
                                            <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Name <span class="text-error">*</span></span>
                                            <input name="name" value="{{ old('name', $section->name) }}"
                                                class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                                                type="text" required>
                                        </label>
                                        <label class="block">
                                            <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Slug</span>
                                            <input name="slug" value="{{ old('slug', $section->slug) }}"
                                                class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                                                type="text">
                                        </label>
                                    </div>

                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <label class="block">
                                            <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Position</span>
                                            <input name="position" value="{{ old('position', $section->position) }}"
                                                class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent"
                                                type="number" min="0">
                                        </label>
                                        <div class="flex items-end pb-1">
                                            <label class="inline-flex items-center space-x-2 cursor-pointer">
                                                <input name="status" type="checkbox" value="1" {{ $section->status ? 'checked' : '' }}
                                                    class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                                                <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Active</span>
                                            </label>
                                        </div>
                                    </div>

                                    <br>

                                    {{-- Section Contents List --}}
                                    <div class="mt-4 border-t border-slate-200 dark:border-navy-500 pt-4 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <i class="fa-solid fa-list-check text-xs text-slate-500 dark:text-navy-300"></i>
                                                <h4 class="text-xs font-semibold text-slate-700 dark:text-navy-100 uppercase tracking-wide">
                                                    Section Contents
                                                </h4>
                                            </div>
                                        </div>

                                        @php
                                            // Get all contents including soft-deleted for this section
                                            $allContents = \App\Models\TemplatesSectionContent::withTrashed()
                                                ->where('templates_sections_id', $section->id)
                                                ->get()
                                                ->keyBy('key');
                                        @endphp

                                        <div class="space-y-2">
                                            @foreach($contentPresets as $dIndex => $dummyItem)
                                                @php
                                                    $existing = $allContents->get($dummyItem['key']);
                                                    $isActive = $existing && is_null($existing->deleted_at);
                                                    $currentValue = $existing ? $existing->value : $dummyItem['default_value'];
                                                    $currentType = $existing ? $existing->type : $dummyItem['type'];
                                                @endphp
                                                <div class="rounded-lg border {{ $isActive ? 'border-primary/30 bg-primary/5 dark:border-accent/30 dark:bg-accent/5' : 'border-slate-200 dark:border-navy-500 bg-white dark:bg-navy-700' }} p-3 transition-colors">
                                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-12 items-center">

                                                        {{-- Checkbox --}}
                                                        <div class="sm:col-span-1 flex items-center justify-center">
                                                            <label class="inline-flex items-center cursor-pointer">
                                                                <input name="section_contents[{{ $dummyItem['key'] }}][enabled]" type="checkbox" value="1" {{ $isActive ? 'checked' : '' }}
                                                                    class="form-checkbox is-basic h-5 w-5 rounded border-slate-300 text-primary focus:border-primary dark:border-navy-450 dark:checked:bg-accent dark:checked:border-accent">
                                                            </label>
                                                        </div>

                                                        {{-- Key (readonly) --}}
                                                        <div class="sm:col-span-4">
                                                            <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Key</label>
                                                            <input name="section_contents[{{ $dummyItem['key'] }}][key]" value="{{ $dummyItem['key'] }}"
                                                                class="form-input w-full rounded-lg border border-slate-300 bg-slate-50 px-2.5 py-1.5 text-xs dark:border-navy-450 dark:bg-navy-800 text-slate-500 dark:text-navy-300"
                                                                type="text" readonly>
                                                        </div>

                                                        {{-- Type (readonly) --}}
                                                        <div class="sm:col-span-2">
                                                            <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Type</label>
                                                            <input name="section_contents[{{ $dummyItem['key'] }}][type]" value="{{ $currentType }}"
                                                                class="form-input w-full rounded-lg border border-slate-300 bg-slate-50 px-2.5 py-1.5 text-xs dark:border-navy-450 dark:bg-navy-800 text-slate-500 dark:text-navy-300"
                                                                type="text" readonly>
                                                        </div>

                                                        {{-- Value --}}
                                                        <div class="sm:col-span-5">
                                                            <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Value</label>
                                                            <input name="section_contents[{{ $dummyItem['key'] }}][value]" value="{{ $currentValue }}"
                                                                class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                                                type="text" placeholder="Enter value...">
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-slate-200 dark:border-navy-500">
                                        <button type="submit"
                                            class="btn h-8 rounded-full bg-primary px-4 text-xs font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                                            <i class="fa-solid fa-check mr-1.5"></i> Save Changes
                                        </button>
                                    </div>
                                </form>



                                <div class="flex justify-end -mt-8">
                                    <form action="{{ route('admin.template.section.destroy', [$template->id, $section->id]) }}"
                                        method="POST" class="section-delete-form" data-section-id="{{ $section->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn h-8 rounded-full bg-error/10 px-4 text-xs font-medium text-error hover:bg-error/20">
                                            <i class="fa-solid fa-trash mr-1.5"></i> Delete Section
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
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = '{{ csrf_token() }}';

            function showToast(message, type = 'success') {
                const existing = document.getElementById('ajax-toast-notification');
                if (existing) existing.remove();

                const toast = document.createElement('div');
                toast.id = 'ajax-toast-notification';
                toast.className = `alert flex items-center space-x-2 rounded-lg border p-4 shadow-xl fixed top-5 right-5 z-50 transition-all duration-300 transform translate-y-0 ${
                    type === 'success' ? 'border-success bg-white text-success dark:bg-navy-700' : 'border-error bg-white text-error dark:bg-navy-700'
                }`;
                toast.innerHTML = `
                    <i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-triangle-exclamation'} text-lg"></i>
                    <p class="font-medium text-sm">${message}</p>
                `;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(-10px)';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            async function handleFormSubmit(form, onSuccess) {
                const submitBtn = form.querySelector('button[type="submit"]');
                const origText = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1.5"></i> Saving...';
                }

                try {
                    const formData = new FormData(form);
                    // Ensure _method spoofing is handled if form has method="POST" and input name="_method"
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
                        let errMsg = data.message || 'Error occurred. Please check input.';
                        if (data.errors) {
                            const firstKey = Object.keys(data.errors)[0];
                            if (firstKey && data.errors[firstKey][0]) {
                                errMsg = data.errors[firstKey][0];
                            }
                        }
                        showToast(errMsg, 'error');
                    }
                } catch (err) {
                    console.error(err);
                    showToast('Network error or server failed to respond.', 'error');
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = origText;
                    }
                }
            }

            // Add Section Form Handler
            const addForm = document.getElementById('add-section-form');
            if (addForm) {
                addForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    handleFormSubmit(this, function () {
                        setTimeout(() => window.location.reload(), 500);
                    });
                });
            }

            // Update Section & Contents Form Handler
            document.querySelectorAll('.section-update-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const sectionId = this.dataset.sectionId;
                    handleFormSubmit(this, function (data) {
                        setTimeout(() => window.location.reload(), 500);
                    });
                });
            });

            // Delete Content Item Form Handler
            document.querySelectorAll('.content-delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (!confirm('Delete this content item?')) return;
                    const contentId = this.dataset.contentId;
                    handleFormSubmit(this, function () {
                        const row = document.getElementById(`content-item-row-${contentId}`);
                        if (row) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'scale(0.95)';
                            setTimeout(() => row.remove(), 300);
                        }
                    });
                });
            });

            // Delete Section Form Handler
            document.querySelectorAll('.section-delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (!confirm('Are you sure you want to delete this section?')) return;
                    const sectionId = this.dataset.sectionId;
                    handleFormSubmit(this, function () {
                        const sectionRow = document.getElementById(`section-row-${sectionId}`);
                        if (sectionRow) {
                            sectionRow.style.transition = 'all 0.3s ease';
                            sectionRow.style.opacity = '0';
                            sectionRow.style.transform = 'scale(0.98)';
                            setTimeout(() => sectionRow.remove(), 300);
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
