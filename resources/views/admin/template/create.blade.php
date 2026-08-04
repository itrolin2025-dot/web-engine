<x-app-layout>
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
                <li>Create</li>
            </ul>
        </div>
    </div>

    <div class="max-w-2xl">
        <div class="card p-4 sm:p-5">
            <h3 class="text-base font-medium text-slate-700 dark:text-navy-100 mb-4">Add New Template</h3>

            <form action="{{ route('admin.template.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Name -->
                <label class="block">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Name</span>
                    <input name="name" value="{{ old('name') }}" placeholder="Enter template name" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text" required>
                    @error('name')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Path -->
                <label class="block">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Path</span>
                    <input name="path" value="{{ old('path') }}" placeholder="e.g. template/landing" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text">
                    @error('path')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Preview Image -->
                <label class="block">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Preview (Upload Image)</span>
                    
                    <div class="my-2" x-show="imageUrl">
                        <img :src="imageUrl" class="h-28 w-40 rounded-lg object-cover border border-slate-200 dark:border-navy-500 shadow-sm" alt="Image Preview">
                    </div>

                    <input name="preview" @change="fileChoice" class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-1.5 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-primary/10 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-primary hover:file:bg-primary/20 dark:border-navy-450 dark:file:bg-accent/10 dark:file:text-accent-light" type="file" accept="image/*">
                    @error('preview')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <!-- Status -->
                <div class="flex items-center justify-between pt-2">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Status</span>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input name="status" type="checkbox" value="1" checked class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                        <span class="text-xs font-medium text-slate-600 dark:text-navy-200">Active</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end space-x-2 pt-4">
                    <a href="{{ route('admin.template') }}" class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90">
                        Cancel
                    </a>
                    <button type="submit" class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
