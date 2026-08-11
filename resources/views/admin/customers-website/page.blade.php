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
                <li>Pages</li>
            </ul>
        </div>
    </div>

    {{-- Page Selection Layout --}}
    <div class="card p-4 sm:p-5">
        <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100 mb-4">Select Page Layout to Manage</h3>
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @php
                $pages = [
                    ['key' => 'homepage', 'name' => 'Homepage', 'icon' => 'fa-home'],
                    ['key' => 'about', 'name' => 'About', 'icon' => 'fa-address-card'],
                    ['key' => 'product', 'name' => 'Product', 'icon' => 'fa-box'],
                    ['key' => 'story', 'name' => 'Story', 'icon' => 'fa-book-open'],
                    ['key' => 'gallery', 'name' => 'Gallery', 'icon' => 'fa-images'],
                    ['key' => 'contact', 'name' => 'Contact', 'icon' => 'fa-envelope'],
                    ['key' => 'shop', 'name' => 'Shop', 'icon' => 'fa-shopping-bag'],
                    ['key' => 'article', 'name' => 'Article', 'icon' => 'fa-newspaper'],
                    ['key' => 'image', 'name' => 'Image', 'icon' => 'fa-image']
                ];
            @endphp
            @foreach($pages as $page)
                <a href="{{ route('admin.customers-website.layout', [$website->id, $page['key']]) }}" class="border border-slate-200 dark:border-navy-500 rounded-lg p-4 text-center flex flex-col items-center justify-between hover:border-primary dark:hover:border-accent cursor-pointer transition-colors hover:bg-slate-50 dark:hover:bg-navy-600 block">
                    <div class="bg-slate-100 dark:bg-navy-550 aspect-video w-full rounded-md flex items-center justify-center mb-3">
                        <i class="fa-solid {{ $page['icon'] }} text-3xl text-slate-400 dark:text-navy-300"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 dark:text-navy-100">{{ $page['name'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
