<x-app-layout>
    <div class="flex mb-4 items-center space-x-4 py-5 lg:py-6" style="margin-bottom: -3em;">
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
            <li>{{ $modul_type }}</li>
        </ul>
    </div>
    <div class="pt-4"></div>
    <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6 xl:grid-cols-4">
        <div class="card">
            <img src="images/800x600.png" class="h-48 w-full rounded-t-lg object-cover object-center" alt="images">
            <div class="flex grow flex-col p-4">
                <div class="flex">
                    <a href="#" class="text-xs text-info line-clamp-1">Frameworks</a>
                    <div class="mx-2 my-0.5 w-px bg-slate-200 dark:bg-navy-500"></div>

                    <span class="text-tiny+ text-slate-400 dark:text-navy-300">a hour ago</span>
                </div>

                <div class="pt-2 line-clamp-2">
                    <a href="#"
                        class="text-base font-medium text-slate-700 hover:text-primary focus:text-primary dark:text-navy-100 dark:hover:text-accent-light dark:focus:text-accent-light">Food:
                        A Simple Definition</a>
                </div>

                <p class="grow pt-2">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi
                    necessitatibus repellat voluptatibus?
                </p>

                <div class="mt-3 text-right">
                    <button
                        class="btn h-8 space-x-1.5 rounded-full bg-slate-150 px-3 text-xs+ font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                        <span> 32 </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="card">
            <img src="images/800x600.png" class="h-48 w-full rounded-t-lg object-cover object-center" alt="images">
            <div class="flex grow flex-col p-4">
                <div class="flex">
                    <a href="#" class="text-xs text-info line-clamp-1">Frameworks</a>
                    <div class="mx-2 my-0.5 w-px bg-slate-200 dark:bg-navy-500"></div>

                    <span class="text-tiny+ text-slate-400 dark:text-navy-300">12 min ago</span>
                </div>

                <div class="pt-2 line-clamp-2">
                    <a href="#"
                        class="text-base font-medium text-slate-700 hover:text-primary focus:text-primary dark:text-navy-100 dark:hover:text-accent-light dark:focus:text-accent-light">Tailwind
                        CSS Card Example</a>
                </div>

                <p class="grow pt-2">
                    Lorem ipsum dolor sit amet, consectetur. Lorem ipsum dolor on
                    the sit.
                </p>

                <div class="mt-3 text-right">
                    <button
                        class="btn h-8 space-x-1.5 rounded-full bg-slate-150 px-3 text-xs+ font-medium text-slate-800 hover:bg-slate-200 focus:bg-slate-200 active:bg-slate-200/80 dark:bg-navy-500 dark:text-navy-50 dark:hover:bg-navy-450 dark:focus:bg-navy-450 dark:active:bg-navy-450/90">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                        <span> 65 </span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status') === 'profile-updated')
                <div
                    class="alert flex items-center justify-between space-x-2 rounded-lg border border-success bg-success/10 p-4 text-success dark:border-success dark:bg-success/5 mt-4 animate-fade">
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                        <p class="font-medium">Profile updated successfully!</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>