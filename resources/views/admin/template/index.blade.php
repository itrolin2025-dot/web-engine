{{-- Admin Template Index --}}
<x-app-layout>
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

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6 xl:grid-cols-4">
            @foreach($templates as $template)
            <div class="card flex flex-col">
                <img src="{{ $template->preview ? asset($template->preview) : 'https://via.placeholder.com/800x600?text=No+Image' }}" class="h-48 w-full rounded-t-lg object-cover object-center" alt="preview">
                <div class="flex grow flex-col p-4">
                    <div class="flex items-center justify-between">
                        <span class="badge rounded-full {{ $template->status ? 'bg-success/10 text-success' : 'bg-error/10 text-error' }} px-2.5 py-1 text-xs font-semibold">
                            {{ $template->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <div class="pt-2">
                        <h3 class="text-base font-medium text-slate-700 dark:text-navy-100">{{ $template->name }}</h3>
                    </div>

                    <p class="grow pt-1 text-xs text-slate-500 dark:text-navy-300">
                        Path: <span class="font-mono text-slate-700 dark:text-navy-100">{{ $template->path ?: '-' }}</span>
                    </p>

                    <div class="mt-4 flex justify-end space-x-2">
                        @if($canEdit)
                        <a href="{{ route('admin.template.edit', $template->id) }}" class="btn h-8 w-8 rounded-full bg-info/10 p-0 font-medium text-info hover:bg-info/20 focus:bg-info/20 active:bg-info/25">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        @endif
                        
                        @if($canDelete)
                        <form action="{{ route('admin.template.destroy', $template->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this template?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn h-8 w-8 rounded-full bg-error/10 p-0 font-medium text-error hover:bg-error/20 focus:bg-error/20 active:bg-error/25">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
