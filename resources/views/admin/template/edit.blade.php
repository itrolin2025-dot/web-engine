<x-app-layout>
    <style>
        /* Feedback visual pemilihan tag */
        .tag-card { transition: border-color .15s ease, background-color .15s ease, box-shadow .15s ease; }
        .tag-card .tag-check { display: none; }
        .tag-card.tag-on,
        .tag-card:has(input[type="checkbox"]:checked) {
            border-color: #6366f1;
            background-color: rgba(99, 102, 241, 0.10);
            box-shadow: 0 0 0 1px #6366f1;
        }
        .tag-card.tag-on .tag-name,
        .tag-card:has(input[type="checkbox"]:checked) .tag-name {
            color: #4f46e5;
            font-weight: 600;
        }
        .tag-card.tag-on .tag-check,
        .tag-card:has(input[type="checkbox"]:checked) .tag-check {
            display: inline-flex;
        }
        .dark .tag-card.tag-on,
        .dark .tag-card:has(input[type="checkbox"]:checked) {
            border-color: #818cf8;
            background-color: rgba(129, 140, 248, 0.12);
            box-shadow: 0 0 0 1px #818cf8;
        }
        .dark .tag-card.tag-on .tag-name,
        .dark .tag-card:has(input[type="checkbox"]:checked) .tag-name {
            color: #a5b4fc;
        }
    </style>
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
                <li>Edit</li>
            </ul>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="card p-4 sm:p-5">
            <h3 class="text-base font-medium text-slate-700 dark:text-navy-100 mb-4">Edit Template</h3>

            <form action="{{ route('admin.template.update', $template->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Name -->
                <label class="block">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Name</span>
                    <input name="name" value="{{ old('name', $template->name) }}" placeholder="Enter template name" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text" required>
                    @error('name')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Path -->
                <label class="block">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Path</span>
                    <input name="path" value="{{ old('path', $template->path) }}" placeholder="e.g. template/landing" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text">
                    @error('path')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Tags (type: template) -->
                <div class="block">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Tags</span>
                    <span class="text-xs text-slate-400">— pilih satu atau lebih (type: template)</span>

                    @php
                        $selectedTagIds = old('tags', $template->tags->pluck('id')->all());
                    @endphp

                    {{-- Existing template tags --}}
                    <div id="tagList" class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                        @forelse($tags as $tag)
                            <label class="tag-card flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-sm cursor-pointer hover:border-slate-400 dark:border-navy-450 dark:hover:border-navy-400">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTagIds) ? 'checked' : '' }}
                                    class="form-checkbox is-basic rounded border-slate-400/70 bg-slate-100 checked:border-primary checked:bg-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:border-accent dark:checked:bg-accent">
                                <span class="tag-name text-slate-700 dark:text-navy-100">{{ $tag->nama }}</span>
                                <span class="ml-auto text-[10px] font-mono text-slate-400">{{ $tag->code }}</span>
                                <span class="tag-check size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white dark:bg-accent">
                                    <i class="fa-solid fa-check text-[9px]"></i>
                                </span>
                            </label>
                        @empty
                            <p class="text-xs text-slate-400">Belum ada tag. Tambahkan tag baru di bawah.</p>
                        @endforelse
                    </div>
                    @error('tags.*')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror

                    {{-- Add new tag inline --}}
                    <div id="newTagList" class="mt-2 space-y-2"></div>
                    <button type="button" id="addTagBtn" class="mt-2 inline-flex items-center gap-1.5 text-xs font-medium text-primary hover:underline dark:text-accent-light">
                        <i class="fa-solid fa-plus"></i> Add New Tag
                    </button>
                </div>

                <!-- Preview Image -->
                <label class="block">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Preview (Upload Image)</span>
                    @if($template->preview)
                        <div class="my-2">
                            <img src="{{ asset($template->preview) }}" class="h-20 w-20 rounded-lg object-cover border border-slate-200 dark:border-navy-500" alt="Current preview">
                        </div>
                    @endif
                    <input name="preview" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-primary hover:file:bg-primary/20 dark:border-navy-450 dark:file:bg-accent/10 dark:file:text-accent-light" type="file" accept="image/*">
                    @error('preview')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Status -->
                <div class="flex items-center justify-between pt-2">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Status</span>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input name="status" type="checkbox" value="1" {{ old('status', $template->status) ? 'checked' : '' }} class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                        <span class="text-xs font-medium text-slate-600 dark:text-navy-200">Active</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end space-x-2 pt-4">
                    <a href="{{ route('admin.template') }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                        Cancel
                    </a>
                    <button type="submit" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Inline Add New Tag (type otomatis 'template', code otomatis) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Sinkronisasi tampilan kartu tag dengan state checkbox
            document.querySelectorAll('.tag-card input[type="checkbox"]').forEach(function (cb) {
                var sync = function () { cb.closest('.tag-card').classList.toggle('tag-on', cb.checked); };
                cb.addEventListener('change', sync);
                sync();
            });

            var addBtn = document.getElementById('addTagBtn');
            if (!addBtn) return;
            var list = document.getElementById('newTagList');

            addBtn.addEventListener('click', function () {
                var row = document.createElement('div');
                row.className = 'flex items-center gap-2';
                row.innerHTML =
                    '<input type="text" name="new_tag_names[]" placeholder="Nama tag baru (type: template)" ' +
                    'class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 text-sm placeholder:text-slate-400/70 ' +
                    'hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">' +
                    '<button type="button" class="btn h-9 w-9 shrink-0 rounded-full bg-error/10 p-0 font-medium text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25" title="Remove">' +
                    '<i class="fa-solid fa-xmark text-xs"></i></button>';

                row.querySelector('button').addEventListener('click', function () { row.remove(); });
                list.appendChild(row);
                row.querySelector('input').focus();
            });
        });
    </script>
</x-app-layout>
