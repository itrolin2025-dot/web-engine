<x-app-layout>
    <style>
        .section-card.sortable-ghost {
            opacity: 0.4;
            background-color: rgba(79, 70, 229, 0.1);
        }
        .section-card.sortable-chosen {
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5);
        }
        .section-card.sortable-drag {
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

    {{-- Row 1: Template Info Full Width --}}
    <div class="mb-6 mt-2">
        <div class="card p-4 sm:p-5">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Template Info</h3>
                <a href="{{ route('admin.template.edit', $template->id) }}"
                    class="btn h-7 rounded-full bg-slate-150 px-3 text-xs+ font-medium text-slate-800 hover:bg-slate-200 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450">
                    <i class="fa-solid fa-pen mr-1"></i> Edit
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Name</p>
                    <p class="font-medium text-slate-700 dark:text-navy-100 mt-0.5">{{ $template->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Path</p>
                    <p class="font-mono text-sm text-slate-600 dark:text-navy-200 mt-0.5">{{ $template->path ?: '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Status</p>
                    <div class="mt-1">
                        <span class="badge rounded-full {{ $template->status ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }} px-2.5 py-1 text-xs font-semibold">
                            {{ $template->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Total Sections</p>
                    <div class="mt-1">
                        <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light px-2.5 py-1 text-xs font-semibold">
                            {{ $sections->count() }}
                        </span>
                    </div>
                </div>
                @if($template->preview)
                <div>
                    <p class="text-xs text-slate-400 dark:text-navy-300">Preview</p>
                    <div class="mt-1">
                        <img src="{{ asset($template->preview) }}" class="h-12 w-auto rounded border border-slate-200 dark:border-navy-500" alt="Preview">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="py-2"></div>
    {{-- Row 2: Add New Section (6 cols) + Section Lists (6 cols) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-start">

        {{-- COLUMN LEFT: Add Section Form --}}
        <div x-data="{ showForm: true }">
            <div class="card">
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
                    class="px-4 pb-5 pt-4 sm:px-5">

                    <form id="add-section-form" action="{{ route('admin.template.section.store', $template->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <label class="block">
                                <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Name <span class="text-error">*</span></span>
                                <input name="name" value="{{ old('name') }}" placeholder="Section name"
                                    class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                    type="text" required>
                                @error('name') <span class="text-xs text-error">{{ $message }}</span> @enderror
                            </label>
                            <label class="block">
                                <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Slug</span>
                                <input name="slug" value="{{ old('slug') }}" placeholder="auto-generated"
                                    class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                    type="text">
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <input type="hidden" name="position" value="0">
                            <label class="block">
                                <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Preview Image</span>
                                <input name="preview"
                                    class="form-input mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                    type="file" accept="image/*">
                            </label>
                        </div>

                        <div class="flex items-end pb-1">
                            <label class="inline-flex items-center space-x-2 cursor-pointer">
                                <input name="status" type="checkbox" value="1" checked
                                    class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                                <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Active</span>
                            </label>
                        </div>

                        {{-- Optional Initial Content Item --}}
                        <div class="mt-4 border-t border-slate-200 dark:border-navy-500 pt-4">
                            <h4 class="text-xs font-semibold text-slate-700 dark:text-navy-100 uppercase tracking-wide mb-2">
                                Initial Content Item (Optional)
                            </h4>
                            <div class="grid grid-cols-2 gap-2 mt-3">
                                @foreach($contentPresets as $preset)
                                    <label class="flex items-center gap-2 rounded-lg border border-slate-200 dark:border-navy-500 bg-white dark:bg-navy-700 px-3 py-2 transition-colors cursor-pointer hover:bg-slate-50 dark:hover:bg-navy-600">
                                        <input name="section_contents[{{ $preset['key'] }}][enabled]" type="checkbox" value="1"
                                            class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary dark:border-navy-450 dark:bg-navy-800 dark:checked:bg-accent dark:checked:border-accent cursor-pointer">
                                        <span class="text-xs text-slate-600 dark:text-navy-200 truncate">{{ $preset['key'] }}</span>
                                        <input type="hidden" name="section_contents[{{ $preset['key'] }}][key]" value="{{ $preset['key'] }}">
                                        <input type="hidden" name="section_contents[{{ $preset['key'] }}][type]" value="{{ $preset['type'] }}">
                                        <input type="hidden" name="section_contents[{{ $preset['key'] }}][value]" value="{{ $preset['default_value'] }}">
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                class="btn bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus">
                                <i class="fa-solid fa-check mr-1.5"></i> Save Section
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- COLUMN RIGHT: Section Lists --}}
        <div class="min-w-0">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-layer-group text-sm text-slate-400 dark:text-navy-300"></i>
                    <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100">Section Lists</h3>
                </div>
                <span class="badge rounded-full bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light px-2.5 py-0.5 text-xs font-semibold">
                    {{ $sections->count() }}
                </span>
            </div>

            @if($sections->isEmpty())
                <div class="card flex flex-col items-center justify-center py-12 text-slate-400 dark:text-navy-300">
                    <i class="fa-solid fa-layer-group text-4xl mb-3 opacity-40"></i>
                    <p class="text-sm">No sections yet.</p>
                    <p class="text-xs mt-1 opacity-70">Click "Add New Section" to create one.</p>
                </div>
            @else
                {{-- Drag Hint --}}
                <div class="h-2"></div>
                <p class="text-xs text-slate-400 dark:text-navy-300 flex items-center gap-1">
                    <i class="fa-solid fa-grip-vertical"></i> Drag cards to reorder sections
                </p>
                <div class="h-3"></div>

                {{-- Sortable Container --}}
                <div id="section-sortable" class="space-y-3"
                    x-data="{ expandedCardId: null, toggleCard(cardId) { this.expandedCardId = this.expandedCardId === cardId ? null : cardId; } }">
                    @foreach($sections as $section)
                    <div id="section-row-{{ $section->id }}" data-id="{{ $section->id }}" class="section-card card p-4 cursor-move h-full">
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 min-w-0">
                                {{-- Drag Handle --}}
                                <div class="drag-handle flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-slate-100 dark:bg-navy-600 text-slate-400 dark:text-navy-300 hover:bg-slate-200 dark:hover:bg-navy-500 transition-colors">
                                    <i class="fa-solid fa-grip-vertical text-xs"></i>
                                </div>
                                {{-- Position Badge --}}
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full font-semibold text-xs
                                    {{ $section->status ? 'bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                    {{ $section->position ?? $loop->iteration }}
                                </div>
                                {{-- Section Info --}}
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-700 dark:text-navy-100 truncate">{{ $section->name }}</p>
                                    <p class="text-xs text-slate-400 dark:text-navy-300 font-mono truncate">{{ $section->slug }}</p>
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center space-x-2 ml-3">
                                <span class="badge rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $section->contents->count() > 0 ? 'bg-info/10 text-info' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                    {{ $section->contents->count() }} items
                                </span>
                                <span class="badge rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $section->status ? 'bg-success/10 text-success' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300' }}">
                                    {{ $section->status ? 'Active' : 'Inactive' }}
                                </span>
                                <button type="button" @click.prevent="toggleCard('section-{{ $section->id }}')"
                                    class="btn h-8 w-8 rounded-lg p-0 hover:bg-slate-100 dark:hover:bg-navy-600">
                                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform duration-300"
                                        :class="expandedCardId === 'section-{{ $section->id }}' ? 'rotate-180' : ''"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Card Body: Edit Form --}}
                        <div x-show="expandedCardId === 'section-{{ $section->id }}'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="mt-4 pt-4 border-t border-slate-200 dark:border-gray-800 bg-slate-50 dark:bg-navy-800 rounded-b-xl -mx-4 px-4 pb-4 sm:-mx-5 sm:px-5">

                            <form action="{{ route('admin.template.section.update', [$template->id, $section->id]) }}"
                                method="POST" enctype="multipart/form-data" class="section-update-form space-y-4" data-section-id="{{ $section->id }}">
                                @csrf
                                @method('PUT')

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
                                    <input type="hidden" name="position" value="{{ $section->position }}">
                                    <div class="flex items-end pb-1">
                                        <label class="inline-flex items-center space-x-2 cursor-pointer">
                                            <input name="status" type="checkbox" value="1" {{ $section->status ? 'checked' : '' }}
                                                class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                                            <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Active</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Section Contents --}}
                                <div class="mt-4 border-t border-slate-200 dark:border-navy-500 pt-4 space-y-3">
                                    <div class="flex items-center space-x-2">
                                        <i class="fa-solid fa-list-check text-xs text-slate-500 dark:text-navy-300"></i>
                                        <h4 class="text-xs font-semibold text-slate-700 dark:text-navy-100 uppercase tracking-wide">Section Contents</h4>
                                    </div>

                                    @php
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
                                                    <div class="sm:col-span-1 flex items-center justify-center">
                                                        <label class="inline-flex items-center cursor-pointer">
                                                            <input name="section_contents[{{ $dummyItem['key'] }}][enabled]" type="checkbox" value="1" {{ $isActive ? 'checked' : '' }}
                                                                class="form-checkbox is-basic h-5 w-5 rounded border-slate-300 text-primary focus:border-primary dark:border-navy-450 dark:checked:bg-accent dark:checked:border-accent">
                                                        </label>
                                                    </div>
                                                    <div class="sm:col-span-4">
                                                        <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Key</label>
                                                        <input name="section_contents[{{ $dummyItem['key'] }}][key]" value="{{ $dummyItem['key'] }}"
                                                            class="form-input w-full rounded-lg border border-slate-300 bg-slate-50 px-2.5 py-1.5 text-xs dark:border-navy-450 dark:bg-navy-800 text-slate-500 dark:text-navy-300"
                                                            type="text" readonly>
                                                    </div>
                                                    <div class="sm:col-span-2">
                                                        <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Type</label>
                                                        <input name="section_contents[{{ $dummyItem['key'] }}][type]" value="{{ $currentType }}"
                                                            class="form-input w-full rounded-lg border border-slate-300 bg-slate-50 px-2.5 py-1.5 text-xs dark:border-navy-450 dark:bg-navy-800 text-slate-500 dark:text-navy-300"
                                                            type="text" readonly>
                                                    </div>
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
                                    <form action="{{ route('admin.template.section.destroy', [$template->id, $section->id]) }}"
                                        method="POST" class="section-delete-form" data-section-id="{{ $section->id }}">
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
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = '{{ csrf_token() }}';

            // Toast
            window.showToast = function(message, type = 'success') {
                const existing = document.getElementById('ajax-toast-notification');
                if (existing) existing.remove();
                const toast = document.createElement('div');
                toast.id = 'ajax-toast-notification';
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
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        body: formData
                    });
                    const data = await response.json();
                    if (response.ok && data.success) {
                        showToast(data.message || 'Done.', 'success');
                        if (onSuccess) onSuccess(data);
                    } else {
                        let errMsg = data.message || 'Error occurred.';
                        if (data.errors) {
                            const firstKey = Object.keys(data.errors)[0];
                            if (firstKey && data.errors[firstKey][0]) errMsg = data.errors[firstKey][0];
                        }
                        showToast(errMsg, 'error');
                    }
                } catch (err) {
                    showToast('Network error.', 'error');
                } finally {
                    if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = origText; }
                }
            }

            // Add Form
            const addForm = document.getElementById('add-section-form');
            if (addForm) {
                addForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    handleFormSubmit(this, () => setTimeout(() => window.location.reload(), 500));
                });
            }

            // Update Forms
            document.querySelectorAll('.section-update-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    handleFormSubmit(this, () => setTimeout(() => window.location.reload(), 500));
                });
            });

            // Delete Forms
            document.querySelectorAll('.section-delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (!confirm('Are you sure you want to delete this section?')) return;
                    const sectionId = this.dataset.sectionId;
                    handleFormSubmit(this, () => {
                        const row = document.getElementById(`section-row-${sectionId}`);
                        if (row) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'scale(0.98)';
                            setTimeout(() => row.remove(), 300);
                        }
                    });
                });
            });

            // SortableJS
            const sortableContainer = document.getElementById('section-sortable');
            if (sortableContainer && typeof Sortable !== 'undefined') {
                Sortable.create(sortableContainer, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'opacity-50',
                    chosenClass: 'ring-2 ring-primary dark:ring-accent',
                    onEnd: function(evt) {
                        const order = Array.from(sortableContainer.children).map(el => el.dataset.id);
                        fetch('{{ route("admin.template.section.reorder", $template->id) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order: order })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const cards = sortableContainer.querySelectorAll('.section-card');
                                cards.forEach((card, index) => {
                                    const badge = card.querySelector('.rounded-full.font-semibold');
                                    if (badge) badge.textContent = index;
                                });
                                showToast('Section order updated!', 'success');
                            }
                        })
                        .catch(err => showToast('Failed to update order', 'error'));
                    }
                });
            }
        });
    </script>
</x-app-layout>
