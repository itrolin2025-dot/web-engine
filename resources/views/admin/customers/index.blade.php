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
            <a href="{{ route('admin.' . $modul . '.create') }}"
                class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
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

        @include('admin.' . $modul_path . '.partials.table')
    </div>
</x-app-layout>
