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
                <li class="text-slate-500 dark:text-navy-300">Pages</li>
            </ul>
        </div>
    </div>

    {{-- Main Container Card --}}
    <div class="card p-4 sm:p-6 lg:p-8 space-y-8">
        
        {{-- SECTION 1: WEBSITE PAGE LAYOUT --}}
        <div>
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center space-x-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary/10 text-primary dark:bg-accent/10 dark:text-accent-light">
                        <i class="fa-solid fa-layer-group text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-navy-100">Website Page Layout</h3>
                        <p class="text-xs text-slate-400 dark:text-navy-300">Kelola tata letak untuk halaman utama website pelanggan</p>
                    </div>
                </div>
                <span class="badge rounded-full bg-slate-100 text-slate-600 dark:bg-navy-600 dark:text-navy-200 text-xs px-3 py-1 font-medium hidden sm:inline-block">
                    10 Pages
                </span>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-2 mt-4 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @php
                    $pages = [
                        ['key' => 'homepage', 'name' => 'Homepage', 'icon' => 'fa-home', 'desc' => 'Halaman Utama'],
                        ['key' => 'about', 'name' => 'About Us', 'icon' => 'fa-address-card', 'desc' => 'Profil & Tentang'],
                        ['key' => 'product', 'name' => 'Products', 'icon' => 'fa-box', 'desc' => 'Katalog Produk'],
                        ['key' => 'product_detail', 'name' => 'Product Detail', 'icon' => 'fa-box-open', 'desc' => 'Rincian Produk'],
                        ['key' => 'story', 'name' => 'Brand Story', 'icon' => 'fa-book-open', 'desc' => 'Kisah & Artikel'],
                        ['key' => 'gallery', 'name' => 'Gallery', 'icon' => 'fa-images', 'desc' => 'Koleksi Foto'],
                        ['key' => 'contact', 'name' => 'Contact Us', 'icon' => 'fa-envelope', 'desc' => 'Kontak & Lokasi'],
                        ['key' => 'shop', 'name' => 'Shop', 'icon' => 'fa-shopping-bag', 'desc' => 'Toko Online'],
                        ['key' => 'article', 'name' => 'Articles', 'icon' => 'fa-newspaper', 'desc' => 'Berita & Blog'],
                        ['key' => 'image', 'name' => 'Image Assets', 'icon' => 'fa-image', 'desc' => 'Galeri Gambar']
                    ];
                @endphp

                @foreach($pages as $page)
                    <a href="{{ route('admin.customers-website.layout', [$website->id, $page['key']]) }}" 
                       class="group relative flex flex-col justify-between rounded-xl border border-slate-200/80 bg-white p-4 transition-all duration-300 hover:-translate-y-1 hover:border-primary hover:shadow-lg hover:shadow-primary/5 dark:border-navy-600 dark:bg-navy-700 dark:hover:border-accent dark:hover:shadow-accent/5">
                        
                        <!-- Thumbnail/Icon Wrapper -->
                        <div class="relative aspect-square w-full rounded-lg bg-slate-100 dark:bg-navy-600 flex items-center justify-center overflow-hidden mb-3 group-hover:bg-primary/5 dark:group-hover:bg-accent/10 transition-colors">
                            <i class="fa-solid {{ $page['icon'] }} text-3xl text-slate-400 group-hover:scale-110 group-hover:text-primary dark:text-navy-300 dark:group-hover:text-accent transition-all duration-300"></i>
                        </div>

                        <!-- Info Area -->
                        <div class="flex flex-col py-4 flex-grow justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-700 group-hover:text-primary dark:text-navy-100 dark:group-hover:text-accent-light transition-colors line-clamp-1">
                                    {{ $page['name'] }}
                                </h4>
                                <p class="text-[11px] text-slate-400 dark:text-navy-300 mt-0.5 line-clamp-1">
                                    {{ $page['desc'] }}
                                </p>
                            </div>
                            
                            <!-- Arrow Link -->
                            <div class="mt-3 flex items-center justify-end text-xs font-medium text-slate-400 group-hover:text-primary dark:group-hover:text-accent transition-colors">
                                <span class="text-[11px] mr-1 opacity-0 group-hover:opacity-100 transition-opacity">Manage</span>
                                <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-0.5 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- SEPARATOR WITH DIVIDER ICON --}}
        <div class="relative py-2">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-dashed border-slate-200 dark:border-navy-500"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="bg-white dark:bg-navy-700 px-4 text-xs font-semibold tracking-wider text-slate-400 dark:text-navy-300 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i> Special Features
                </span>
            </div>
        </div>

        {{-- SECTION 2: SPECIAL PAGE LAYOUT --}}
        <div>
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center space-x-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/10 text-amber-500 dark:bg-amber-500/20">
                        <i class="fa-solid fa-star text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-navy-100">Special Page Layout</h3>
                        <p class="text-xs text-slate-400 dark:text-navy-300">Halaman modul khusus untuk fungsionalitas lanjutan</p>
                    </div>
                </div>
                <span class="badge rounded-full bg-amber-500/10 text-amber-600 dark:bg-amber-500/20 dark:text-amber-300 text-xs px-3 py-1 font-medium hidden sm:inline-block">
                    2 Modules
                </span>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-2 mt-4 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @php
                    $specialPages = [
                        ['key' => 'Tree Page', 'name' => 'Tree Page', 'icon' => 'fa-arrows-split-up-and-left', 'desc' => 'Struktur Hirarki'],
                        ['key' => 'POS', 'name' => 'Point of Sales (POS)', 'icon' => 'fa-calculator', 'desc' => 'Kasir & Transaksi']
                    ];
                @endphp

                @foreach($specialPages as $page)
                    <a href="{{ route('admin.customers-website.layout', [$website->id, $page['key']]) }}" 
                       class="group relative flex flex-col justify-between rounded-xl border border-amber-200/60 bg-amber-50/20 p-4 transition-all duration-300 hover:-translate-y-1 hover:border-amber-500 hover:shadow-lg hover:shadow-amber-500/5 dark:border-navy-600 dark:bg-navy-700/50 dark:hover:border-amber-400">
                        
                        <!-- Thumbnail/Icon Wrapper -->
                        <div class="relative aspect-square w-full rounded-lg bg-slate-100 dark:bg-navy-600 flex items-center justify-center overflow-hidden mb-3 group-hover:bg-primary/5 dark:group-hover:bg-accent/10 transition-colors">
                            <i class="fa-solid {{ $page['icon'] }} text-3xl text-slate-400 group-hover:scale-110 group-hover:text-primary dark:text-navy-300 dark:group-hover:text-accent transition-all duration-300"></i>
                        </div>

                        <!-- Info Area -->
                        <div class="flex py-4 flex-col flex-grow justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-700 group-hover:text-amber-600 dark:text-navy-100 dark:group-hover:text-amber-400 transition-colors line-clamp-1">
                                    {{ $page['name'] }}
                                </h4>
                                <p class="text-[11px] text-slate-400 dark:text-navy-300 mt-0.5 line-clamp-1">
                                    {{ $page['desc'] }}
                                </p>
                            </div>

                            <!-- Arrow Link -->
                            <div class="mt-3 flex items-center justify-end text-xs font-medium text-amber-500/80 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">
                                <span class="text-[11px] mr-1 opacity-0 group-hover:opacity-100 transition-opacity">Configure</span>
                                <i class="fa-solid fa-arrow-right text-[10px] transform group-hover:translate-x-0.5 transition-transform"></i>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
</x-app-layout>