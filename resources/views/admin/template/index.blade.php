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

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-2 lg:gap-6 xl:grid-cols-3">
            @foreach($templates as $template)
            <div class="card flex flex-col overflow-hidden">
                <div class="relative h-56 w-full bg-slate-100 dark:bg-navy-800">
                    <img loading="lazy"
                         src="{{ $template->preview ? asset($template->preview) : asset('images/default/broken.png') }}"
                         onerror="this.onerror=null;this.src='{{ asset('images/default/broken.png') }}';this.className='absolute inset-0 m-auto h-24 w-24 object-contain opacity-60 dark:opacity-40'"
                         class="absolute inset-0 h-full w-full object-cover object-center{{ $template->preview ? '' : ' m-auto h-24 w-24 object-contain opacity-60 dark:opacity-40' }}"
                         alt="preview">
                </div>
                <div class="flex flex-col border-t border-slate-150 px-4 py-3 dark:border-navy-600">
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
    </div>
</x-app-layout>
