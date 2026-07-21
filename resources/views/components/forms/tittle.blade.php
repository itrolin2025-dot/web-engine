<div class="flex items-center space-x-4 py-5 lg:py-6">
    <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">{{ $modul_name }}</h2>
    <div class="hidden h-full py-1 sm:flex">
        <div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>
    </div>
    <ul class="hidden flex-wrap items-center space-x-2 sm:flex">
        <li class="flex items-center space-x-2">
            <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                href="{{ route('admin.dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-angle-right"></i>
        </li>
        <li class="flex items-center space-x-2">
            <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                href="{{ route('admin.' . $modul) }}">{{ $modul_name }}</a>
            <i class="fa-solid fa-angle-right"></i>
        </li>
        <li>{{ $modul_type }}</li>
    </ul>
</div>