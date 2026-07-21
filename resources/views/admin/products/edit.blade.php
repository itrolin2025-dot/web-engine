<x-app-layout>
    <div class="flex items-center space-x-4 py-5 lg:py-6" style="margin-bottom: -3em;">
        <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">{{ $modul_name }}</h2>
        <div class="hidden h-full py-1 sm:flex">
            <div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>
        </div>
        <ul class="hidden flex-wrap items-center space-x-2 sm:flex">
        <li class="flex items-center space-x-2">
            <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent" href="{{ route('dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-angle-right"></i>
        </li>
        <li>{{ $modul_type }}</li>
        </ul>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @if (session('status') === 'profile-updated')
             <div class="alert flex items-center justify-between space-x-2 rounded-lg border border-success bg-success/10 p-4 text-success dark:border-success dark:bg-success/5 mt-4 animate-fade">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <p class="font-medium">Profile updated successfully!</p>
                </div>
            </div>
        @endif

            @include('profile.partials.update-profile-information-form')

        </div>
    </div>
</x-app-layout>
