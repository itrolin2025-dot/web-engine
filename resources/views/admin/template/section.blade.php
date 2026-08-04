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

                <div class="mb-4">
                    <img src="{{ $template->preview ? asset($template->preview) : 'https://via.placeholder.com/400x260?text=No+Image' }}"
                        class="w-full rounded-lg object-cover border border-slate-200 dark:border-navy-500 shadow-sm"
                        style="max-height: 180px;"
                        alt="Template Preview">
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

                    <form action="{{ route('admin.template.section.store', $template->id) }}" method="POST" class="space-y-4">
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
                            <div class="flex items-end pb-1">
                                <label class="inline-flex items-center space-x-2 cursor-pointer">
                                    <input name="status" type="checkbox" value="1" checked
                                        class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                                    <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Active</span>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end">
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
                        <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }}, editing: false }">

                            {{-- Accordion Header --}}
                            <button type="button"
                                class="flex w-full items-center justify-between px-4 py-4 sm:px-5 text-left hover:bg-slate-50 dark:hover:bg-navy-600 transition-colors"
                                @click="open = !open; if (!open) editing = false">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full font-semibold text-xs
                                        {{ $section->status ? 'bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                        {{ $section->position ?? $loop->iteration }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-700 dark:text-navy-100 truncate">{{ $section->name }}</p>
                                        <p class="text-xs text-slate-400 dark:text-navy-300 font-mono truncate">{{ $section->slug }}</p>
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center space-x-2 ml-3">
                                    <span class="badge rounded-full px-2 py-0.5 text-xs font-medium
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
                                class="bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300 px-4 pb-4 pt-3 sm:px-5">

                                {{-- View Mode --}}
                                <div x-show="!editing">
                                    <!-- <div class="grid grid-cols-3 gap-3 mb-4">
                                        <div class="rounded-lg bg-white dark:bg-navy-700 p-3 border border-slate-150 dark:border-navy-600">
                                            <p class="text-xs text-slate-400 dark:text-navy-300 mb-0.5">Position</p>
                                            <p class="text-sm font-semibold text-slate-700 dark:text-navy-100">{{ $section->position }}</p>
                                        </div>
                                        <div class="rounded-lg bg-white dark:bg-navy-700 p-3 border border-slate-150 dark:border-navy-600">
                                            <p class="text-xs text-slate-400 dark:text-navy-300 mb-0.5">Created</p>
                                            <p class="text-sm font-semibold text-slate-700 dark:text-navy-100">
                                                {{ $section->created_at ? $section->created_at->format('d M Y') : '-' }}
                                            </p>
                                        </div>
                                        <div class="rounded-lg bg-white dark:bg-navy-700 p-3 border border-slate-150 dark:border-navy-600">
                                            <p class="text-xs text-slate-400 dark:text-navy-300 mb-0.5">Status</p>
                                            <p class="text-sm font-semibold {{ $section->status ? 'text-success' : 'text-error' }}">
                                                {{ $section->status ? 'Active' : 'Inactive' }}
                                            </p>
                                        </div>
                                    </div> -->
                                    <div class="flex items-center space-x-2">
                                        <!-- <button type="button" @click.stop="editing = true"
                                            class="btn h-8 rounded-full bg-info/10 px-4 text-xs font-medium text-info hover:bg-info/20">
                                            <i class="fa-solid fa-pen mr-1.5"></i> Edit
                                        </button> -->
                                    </div>
                                </div>

                                {{-- Edit Mode --}}
                                <div x-show="editing">
                                    <!-- <p class="text-xs font-semibold text-slate-500 dark:text-navy-300 uppercase tracking-wide mb-3">Edit Section</p> -->
                                    <form action="{{ route('admin.template.section.update', [$template->id, $section->id]) }}"
                                        method="POST" class="space-y-3">
                                        @csrf
                                        @method('PUT')

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

                                        <div class="flex items-center space-x-2 pt-1" style="padding-top:20px;padding-bottom:20px; jutistify-content:right;">
                                            
                                            <button type="submit"
                                                class="btn h-8 rounded-full bg-primary px-4 text-xs font-medium text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                                                <i class="fa-solid fa-check mr-1.5"></i> Save Changes
                                            </button>
                                            <form action="{{ route('admin.template.section.destroy', [$template->id, $section->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this section?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn h-8 rounded-full bg-error/10 px-4 text-xs font-medium text-error hover:bg-error/20">
                                                    <i class="fa-solid fa-trash mr-1.5"></i> Delete
                                                </button>
                                            </form>
                                        </div>
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
</x-app-layout>
