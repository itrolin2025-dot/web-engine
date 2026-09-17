@php
    $rawContent = $layout->content ?? '';

    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
        // Strip non-standard whitespace/control characters (like raw tabs \t) that break json_decode
        $cleanJson = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $rawContent);
        $content = json_decode($cleanJson, true) ?? json_decode($rawContent, true) ?? [];
    } else {
        $content = [];
    }

    $domain = $website->domain ?? '';

    $tag = $content['tag_en'] ?? $content['tag'] ?? '';
    $tag_color = $content['tag_color'] ?? '#ffffff';

    $title = $content['title_en'] ?? $content['title'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    //ambil logo
    $logoFile = $navContent['image'] ?? null;
    $logo = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;

    //ambil nama brand
    $brand = $navContent['brand'] ?? ($website->title ?? 'My Brand');
    //ambil menu
    $menus = $navContent['menus'] ?? [];

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#000000';
    $button_color = $content['button_color'] ?? '#000000';

    $background_color = $content['background_color'] ?? '#ffffff';

    $hero_bg = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';
    $hero_img = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';
@endphp



<style>
    /* Mobile: Solid background by default */
    #main-header {
        background-color: {{ $background_color }};
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); /* shadow-sm */
        padding-bottom:0.6rem;
    }
    
    /* Desktop: Transparent before scroll */
    @media (min-width: 768px) {
        #main-header:not(.scrolled) {
            background-color: transparent !important;
            box-shadow: none !important;
        }
    }
</style>

<header id="main-header"
    class="fixed top-0 left-0 w-full z-50 transition-all duration-300">
    <div class="w-full px-6 md:px-12 py-5 flex items-center justify-between">

        <!-- Left Navigation Links -->
        <nav class="hidden lg:flex items-center space-x-8 text-sm font-medium" style="color:{{ $button_text_color }};">
            @foreach($menus as $menu)
                @php
                    $menuUrl = $menu['url'] ?? '#';
                    if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                        $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                    }
                @endphp
                @if(!empty($menu['children']))
                    <div class="dropdown relative">
                        <a href="javascript:void(0)" class="dropdown-toggle flex items-center gap-1 hover:text-teal-700 transition">
                            {{ $menu['label'] }} &#9662;
                        </a>
                        <div class="dropdown-menu absolute left-0 top-full mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100 z-50 hidden">
                            @foreach($menu['children'] as $child)
                                @php
                                    $childUrl = $child['url'] ?? '#';
                                    if (!empty($childUrl) && $childUrl !== '#' && !str_starts_with($childUrl, 'http') && !str_starts_with($childUrl, '/')) {
                                        $childUrl = '/' . ($website->domain ?? '') . '/' . ltrim($childUrl, '/');
                                    }
                                @endphp
                                <a href="{{ $childUrl }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-teal-700 transition">
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    @php
                        $routeName = !empty($menu['url']) ? 'pages' : 'template';
                        $routeParams = ($routeName === 'template')
                            ? ['client' => $website->domain ?? '']
                            : ['client' => $website->domain ?? '', 'pages' => $menu['url']];
                    @endphp
                    <a href="{{ route($routeName, $routeParams) }}" class="hover:text-teal-700 transition">{{ $menu['label'] }}</a>
                @endif
            @endforeach
        </nav>

        <!-- Center Brand Logo -->
        <div class="text-center">
            <a href="#" class="text-2xl font-bold tracking-[0.25em] text-stone-700 uppercase">
                @if($logo)
            <img src="{{ asset($logo) }}" alt="{{ $title }}" class="h-20 w-auto object-contain">
        @else
            <span class="text-xl font-bold tracking-tight" style="color: {{ $title_color }}">{{ $title }}</span>
        @endif
            </a>
        </div>

        <!-- Right Action Icons -->
        <div class="flex items-center space-x-5 text-gray-700">
            <!-- Search Icon -->
            <button aria-label="Search" class="hover:text-black transition">
                <svg class="w-5 h-5" fill="none" stroke="{{ $button_text_color }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>

            <!-- Account Icon -->
            <button aria-label="Account" class="hover:text-black transition">
                <svg class="w-5 h-5" fill="none" stroke="{{ $button_text_color }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </button>

            <!-- Wishlist Icon with Badge -->
            <button aria-label="Wishlist" class="relative hover:text-black transition">
                <svg class="w-5 h-5" fill="none" stroke="{{ $button_text_color }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                    </path>
                </svg>
                <span
                    class="absolute -top-1.5 -right-2 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold" 
                    style="background-color:{{ $button_color }}">0</span>
            </button>

            <!-- Cart Icon with Badge -->
            <button aria-label="Cart" class="relative hover:text-black transition"
                onclick="toggleCartDrawer()">
                <svg class="w-5 h-5" fill="none" stroke="{{ $button_text_color }}" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                <span id="cart-badge"
                    class="absolute -top-1.5 -right-2 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold"
                    style="background-color:{{ $button_color }}">0</span>
            </button>
        </div>

    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Dropdown Toggle Logic on Click
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                
                const parent = this.closest('.dropdown');
                const menu = parent.querySelector('.dropdown-menu');
                
                // Close other dropdowns
                document.querySelectorAll('.dropdown').forEach(item => {
                    if (item !== parent) {
                        const otherMenu = item.querySelector('.dropdown-menu');
                        if (otherMenu) {
                            otherMenu.classList.add('hidden');
                        }
                    }
                });

                // Toggle current dropdown
                if (menu) {
                    menu.classList.toggle('hidden');
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('main-header');
            if (header) {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            }
        });
    });
</script>