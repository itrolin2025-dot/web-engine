@php
    $navContent = isset($layout->content) ? json_decode($layout->content, true) : [];

    // Jika ada data dummy navbarPresets dari controller, gabungkan
    if(isset($navbarPresets)) {
        $navContent = array_merge($navbarPresets, $navContent);
    }

    //ambil logo
    $logoFile = $navContent['image'] ?? null;
    $logo = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;

    //ambil nama brand
    $brand = $navContent['brand'] ?? ($website->title ?? 'My Brand');
    //ambil menu
    $menus = $navContent['menus'] ?? [];
    //ambil tombol cta
    $button_text_color = $navContent['button_text_color'] ?? '#000000';
@endphp

<style>
    /* === Navbar === */
    .nav-link {
        position: relative;
        font-size: 0.9375rem;
        font-weight: 500;
        color: {{ $button_text_color }};
        transition: color 0.2s ease;
    }
    .nav-link:hover {
        opacity: 0.8;
    }
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: currentColor;
        transition: width 0.2s ease;
    }
    .nav-link:hover::after {
        width: 100%;
    }

    /* === Dropdown === */
    .dropdown-wrapper {
        position: relative;
    }
    .dropdown-trigger {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        font-size: 0.9375rem;
        font-weight: 500;
        color: {{ $button_text_color }};
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .dropdown-trigger:hover {
        opacity: 0.8;
    }
    .dropdown-trigger svg {
        width: 14px;
        height: 14px;
        transition: transform 0.2s ease;
    }
    .dropdown-wrapper:hover .dropdown-trigger svg {
        transform: rotate(180deg);
    }
    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%) translateY(8px);
        min-width: 180px;
        background: #fff;
        border: 1px solid #e7e5e4;
        border-radius: 0.75rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        padding: 0.5rem 0;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s ease;
        z-index: 100;
    }
    .dropdown-wrapper:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }
    .dropdown-menu a {
        display: block;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        color: {{ $button_text_color }};
        transition: all 0.15s ease;
    }
    .dropdown-menu a:hover {
        background-color: #f5f5f4;
        opacity: 0.8;
        padding-left: 1.25rem;
    }
</style>

<nav class="border-b border-stone-200 bg-white sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Logo -->
        <a href="#" class="flex items-center shrink-0" style="color: {{ $button_text_color }}" aria-label="Home">
            @if($logo)
                <img src="{{ asset($logo) }}" alt="{{ $brand }}" class="h-16 w-auto object-contain">
            @else
                <span class="text-xl font-bold tracking-tight" style="color: {{ $button_text_color }}">{{ $brand }}</span>
            @endif
        </a>

        <!-- Menu Desktop -->
        <ul class="hidden md:flex items-center gap-8" style="color: {{ $button_text_color }}">
            @foreach($menus as $menu)
                @php
                    $menuUrl = $menu['url'] ?? '#';
                    if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                        $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                    }
                @endphp
                @if(!empty($menu['children']))
                    <li class="dropdown-wrapper">
                        <span class="dropdown-trigger">
                            {{ $menu['label'] }}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </span>
                        <ul class="dropdown-menu" style="color: {{ $button_text_color }}">
                            @foreach($menu['children'] as $child)
                                @php
                                    $childUrl = $child['url'] ?? '#';
                                    if (!empty($childUrl) && $childUrl !== '#' && !str_starts_with($childUrl, 'http') && !str_starts_with($childUrl, '/')) {
                                        $childUrl = '/' . ($website->domain ?? '') . '/' . ltrim($childUrl, '/');
                                    }
                                @endphp
                                <li>
                                    <a href="{{ $childUrl }}">{{ $child['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    @php
                        $routeName = !empty($menu['url']) ? 'pages' : 'template';
                        $routeParams = ($routeName === 'template')
                            ? ['client' => $website->domain ?? '']
                            : ['client' => $website->domain ?? '', 'pages' => $menu['url']];
                    @endphp
                    <li>
                        <a href="{{ route($routeName, $routeParams) }}" class="nav-link">{{ $menu['label'] }}</a>
                    </li>
                @endif
            @endforeach
        </ul>

        <!-- Right Side: Cart (desktop) + Mobile icons -->
        <div class="flex items-center gap-2 md:gap-4">
            {{-- Cart button (always visible) --}}
            <button onclick="toggleCartDrawer()"
                class="relative p-2 rounded-full hover:bg-stone-100 transition-colors"
                style="color: {{ $button_text_color }}"
                aria-label="Cart">
                <i class="fa-solid fa-bag-shopping text-lg"></i>
                <span id="cart-badge"
                    class="absolute -top-0.5 -right-0.5 bg-pink-500 text-white text-[10px]
                            w-4 h-4 rounded-full flex items-center justify-center font-bold hidden">
                    0
                </span>
            </button>
            {{-- Burger (mobile only) --}}
            <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg hover:bg-stone-100 transition-colors" onclick="toggleMobileMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" style="color: {{ $button_text_color }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden border-t border-stone-100 bg-white">
        <ul class="px-4 py-4 space-y-1">
            @foreach($menus as $menu)
                @php
                    $menuUrl = $menu['url'] ?? '#';
                    if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                        $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                    }
                @endphp
                @if(!empty($menu['children']))
                    <li>
                        <button onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('svg').classList.toggle('rotate-180')"
                            class="w-full flex items-center justify-between py-2.5 px-3 text-sm font-medium hover:bg-stone-50 rounded-lg transition-colors" style="color: {{ $button_text_color }}">
                            {{ $menu['label'] }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <ul class="hidden pl-4 pb-2 space-y-0.5">
                            @foreach($menu['children'] as $child)
                                @php
                                    $childUrl = $child['url'] ?? '#';
                                    if (!empty($childUrl) && $childUrl !== '#' && !str_starts_with($childUrl, 'http') && !str_starts_with($childUrl, '/')) {
                                        $childUrl = '/' . ($website->domain ?? '') . '/' . ltrim($childUrl, '/');
                                    }
                                @endphp
                                <li>
                                    <a href="{{ $childUrl }}" class="block py-2 px-3 text-sm hover:bg-stone-50 rounded-lg transition-colors" style="color: {{ $button_text_color }}; opacity: 0.8;">
                                        {{ $child['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    @php
                        $routeName = !empty($menu['url']) ? 'pages' : 'template';
                        $routeParams = ($routeName === 'template')
                            ? ['client' => $website->domain ?? '']
                            : ['client' => $website->domain ?? '', 'pages' => $menu['url']];
                    @endphp
                    <li>
                        <a href="{{ route($routeName, $routeParams) }}" class="block py-2.5 px-3 text-sm font-medium hover:bg-stone-50 rounded-lg transition-colors" style="color: {{ $button_text_color }}">
                            {{ $menu['label'] }}
                        </a>
                    </li>
                @endif
            @endforeach

        </ul>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }
</script>
