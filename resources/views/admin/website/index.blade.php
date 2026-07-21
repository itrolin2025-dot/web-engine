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

    <div x-data="{activeTab:'tabHome'}" class="tabs flex flex-col">
        <div
            class="is-scrollbar-hidden overflow-x-auto rounded-lg bg-slate-200 text-slate-600 dark:bg-navy-800 dark:text-navy-200">
            <div class="tabs-list flex px-1.5 py-1">
                <button @click="activeTab = 'tabHome'"
                    :class="activeTab === 'tabHome' ? 'bg-white shadow dark:bg-navy-500 dark:text-navy-100' : 'hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'"
                    class="btn shrink-0 space-x-2 px-3 py-1.5 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span> Home </span>
                </button>
                <button @click="activeTab = 'tabProfile'"
                    :class="activeTab === 'tabProfile' ? 'bg-white shadow dark:bg-navy-500 dark:text-navy-100' : 'hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'"
                    class="btn shrink-0 space-x-2 px-3 py-1.5 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profile</span>
                </button>
                <button @click="activeTab = 'tabMessages'"
                    :class="activeTab === 'tabMessages' ? 'bg-white shadow dark:bg-navy-500 dark:text-navy-100' : 'hover:text-slate-800 focus:text-slate-800 dark:hover:text-navy-100 dark:focus:text-navy-100'"
                    class="btn shrink-0 space-x-2 px-3 py-1.5 font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Messages</span>
                </button>
            </div>
        </div>
        <div class="tab-content pt-4">
            <div x-show="activeTab === 'tabHome'" x-transition:enter="transition-all duration-500 easy-in-out"
                x-transition:enter-start="opacity-0 [transform:translate3d(1rem,0,0)]"
                x-transition:enter-end="opacity-100 [transform:translate3d(0,0,0)]">
                <div>
                    <p>
                        Etiam nec ante eget lacus vulputate egestas non iaculis tellus.
                        Suspendisse tempus ex in tortor venenatis malesuada. Aenean
                        consequat dui vitae nibh lobortis condimentum. Duis vel risus est.
                    </p>
                    <div class="flex space-x-2 pt-3">
                        <a href="#"
                            class="tag rounded-full border border-primary text-primary dark:border-accent-light dark:text-accent-light">
                            Tag 1
                        </a>
                        <a href="#"
                            class="tag rounded-full border border-primary text-primary dark:border-accent-light dark:text-accent-light">
                            Tag 2
                        </a>
                    </div>

                    <p class="pt-3 text-xs text-slate-400 dark:text-navy-300">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore
                        dolore non atque?
                    </p>
                </div>
            </div>
            <div x-show="activeTab === 'tabProfile'" x-transition:enter="transition-all duration-500 easy-in-out"
                x-transition:enter-start="opacity-0 [transform:translate3d(1rem,0,0)]"
                x-transition:enter-end="opacity-100 [transform:translate3d(0,0,0)]">
                <div>
                    <p>
                        Cras iaculis ipsum quis lectus faucibus, in mattis nulla molestie.
                        Vestibulum vel tristique libero. Morbi vulputate odio at viverra
                        sodales. Curabitur accumsan justo eu libero porta ultrices vitae eu
                        leo.
                    </p>
                    <div class="flex space-x-2 pt-3">
                        <a href="#"
                            class="tag rounded-full border border-primary text-primary dark:border-accent-light dark:text-accent-light">
                            Tag 1
                        </a>
                        <a href="#"
                            class="tag rounded-full border border-primary text-primary dark:border-accent-light dark:text-accent-light">
                            Tag 2
                        </a>
                    </div>

                    <p class="pt-3 text-xs text-slate-400 dark:text-navy-300">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore
                        dolore non atque?
                    </p>
                </div>
            </div>
            <div x-show="activeTab === 'tabMessages'" x-transition:enter="transition-all duration-500 easy-in-out"
                x-transition:enter-start="opacity-0 [transform:translate3d(1rem,0,0)]"
                x-transition:enter-end="opacity-100 [transform:translate3d(0,0,0)]">
                <div>
                    <p>
                        Pellentesque pulvinar, sapien eget fermentum sodales, felis lacus
                        viverra magna, id pulvinar odio metus non enim. Ut id augue
                        interdum, ultrices felis eu, tincidunt libero.
                    </p>
                    <div class="flex space-x-2 pt-3">
                        <a href="#"
                            class="tag rounded-full border border-primary text-primary dark:border-accent-light dark:text-accent-light">
                            Tag 1
                        </a>
                        <a href="#"
                            class="tag rounded-full border border-primary text-primary dark:border-accent-light dark:text-accent-light">
                            Tag 2
                        </a>
                    </div>

                    <p class="pt-3 text-xs text-slate-400 dark:text-navy-300">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempore
                        dolore non atque?
                    </p>
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