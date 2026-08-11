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

                <div x-show="showForm"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="border-t border-slate-150 dark:border-navy-600 px-4 pb-5 pt-4 sm:px-5">

                    <form id="add-layout-form" action="{{ route('admin.customers-website.layout.store', [$website->id, $page_type]) }}" method="POST" class="space-y-4">
                        @csrf

                        <!-- <div class="grid grid-cols-1 gap-4 sm:grid-cols-2"> -->
                            <!-- <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Section <span class="text-error">*</span></span>
                                <select name="templates_section_id" class="form-select mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" required>
                                    <option value="">Select Section</option>
                                    @foreach($sections as $sec)
                                        <option value="{{ $sec->id }}">{{ $sec->name }} ({{ $sec->slug }})</option>
                                    @endforeach
                                </select>
                                @error('templates_section_id') <span class="text-xs text-error">{{ $message }}</span> @enderror
                            </label> -->
                            

                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">
                                    Section <span class="text-error">*</span>
                                </span>

                                <!-- Select Dropdown -->
                                <select name="templates_section_id" id="sectionSelect" onchange="updateSectionPreview(this)" class="form-select mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" required>
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

                            <!-- Script Sederhana untuk Mengubah Gambar Preview -->
                            <script>
                            function updateSectionPreview(selectElement) {
                                const selectedOption = selectElement.options[selectElement.selectedIndex];
                                const imageUrl = selectedOption.getAttribute('data-image');
                                
                                const previewContainer = document.getElementById('previewContainer');
                                const previewImage = document.getElementById('sectionPreviewImage');

                                if (selectElement.value && imageUrl) {
                                    previewImage.src = imageUrl;
                                    previewContainer.classList.remove('hidden');
                                } else {
                                    previewContainer.classList.add('hidden');
                                }
                            }
                            </script>
                    
                        <!-- </div> -->

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
                            <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Content</span>
                            <textarea name="content" rows="3" placeholder="Layout content..."
                                class="form-textarea mt-1 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"></textarea>
                        </label>

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
                                    method="POST" class="layout-update-form space-y-4" data-layout-id="{{ $layout->id }}">
                                    @csrf
                                    @method('PUT')

                                    <label class="block">
                                        <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Section <span class="text-error">*</span></span>
                                        <select name="templates_section_id"
                                            id="sectionSelectUpdate-{{ $layout->id }}"
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
                                        <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Content</span>
                                        <textarea name="content" rows="30" placeholder="Layout content..."
                                            class="form-textarea mt-1 w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('content', $layout->content) }}</textarea>
                                    </label>

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
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    });

                    const data = await response.json();
                    if (response.ok && data.success) {
                        showToast(data.message || 'Operation completed successfully.', 'success');
                        if (onSuccess) onSuccess(data);
                    } else {
                        showToast(data.message || 'Error occurred. Please check input.', 'error');
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

            // Add Layout Form
            const addForm = document.getElementById('add-layout-form');
            if (addForm) {
                addForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    handleFormSubmit(this, function () {
                        setTimeout(() => window.location.reload(), 500);
                    });
                });
            }

            // Update Layout Forms
            document.querySelectorAll('.layout-update-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const layoutId = this.dataset.layoutId;
                    handleFormSubmit(this, function (data) {
                        if (data.layout) {
                            const statusEl = document.getElementById(`layout-status-${layoutId}`);
                            if (statusEl) {
                                statusEl.textContent = data.layout.status ? 'Active' : 'Inactive';
                                statusEl.className = `badge rounded-full px-2 py-0.5 text-xs font-medium ${
                                    data.layout.status ? 'bg-success/10 text-success' : 'bg-slate-150 text-slate-500 dark:bg-navy-600 dark:text-navy-300'
                                }`;
                            }
                        }
                    });
                });
            });

            // Delete Layout Forms
            document.querySelectorAll('.layout-delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    if (!confirm('Are you sure you want to delete this layout item?')) return;
                    const layoutId = this.dataset.layoutId;
                    handleFormSubmit(this, function () {
                        const row = document.getElementById(`layout-row-${layoutId}`);
                        if (row) {
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'scale(0.98)';
                            setTimeout(() => row.remove(), 300);
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
