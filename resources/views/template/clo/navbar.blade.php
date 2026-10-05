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

<header class="w-full bg-[#FBF7EC] relative z-40">
    <div class="px-6 md:px-12 py-4 md:py-5 flex items-center gap-4">

    <nav class="hidden md:flex items-center gap-8 text-xs font-sans-custom font-semibold tracking-[0.15em] text-[#4A3B2E] order-1">
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
    <!-- Logo (mobile: kiri, desktop: di samping cart button) -->
    <div class="order-first md:order-2 ml-0 md:ml-auto pr-0 md:pr-4 flex items-center min-w-0">
        @if($logo)
            <a href="#" aria-label="{{ $brand ?? $website->title }}">
                <img src="{{ asset($logo) }}" alt="{{ $brand ?? $website->title }}" class="h-11 md:h-14 w-auto object-contain max-w-[150px] sm:max-w-[200px] md:max-w-none">
            </a>
        @else
            <span class="font-script text-3xl md:text-5xl font-bold tracking-wide">
                <span class="text-[#D9534F]">{{ $brand ?? $website->title }}</span>
            </span>
        @endif
    </div>
    <!-- Cart + tombol burger (mobile) -->
    <div class="order-last ml-auto md:ml-0 flex items-center gap-5">
        <button aria-label="Cart" class="relative hover:opacity-75 transition"
            onclick="toggleCartDrawer()">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 0 1-8 0" />
            </svg>
            <span id="cart-badge"
                class="absolute -top-2 -right-2 bg-[#ff4d4f] text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">0</span>
        </button>

        <button type="button" id="clo-burger" aria-label="Buka menu" aria-expanded="false"
            aria-controls="clo-mobile-menu"
            class="md:hidden -mr-1 p-2 -ml-1 rounded-md text-[#4A3B2E] hover:bg-black/5 transition">
            <svg class="clo-burger-icon-open" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" viewBox="0 0 24 24">
                <line x1="3" y1="7" x2="21" y2="7" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="17" x2="21" y2="17" />
            </svg>
            <svg class="clo-burger-icon-close hidden" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" viewBox="0 0 24 24">
                <line x1="5" y1="5" x2="19" y2="19" />
                <line x1="19" y1="5" x2="5" y2="19" />
            </svg>
        </button>
    </div>
    </div>

    <!-- Burger menu (mobile) -->
    <div id="clo-mobile-menu" hidden
        class="md:hidden border-t border-[#4A3B2E]/10 bg-[#FBF7EC] px-6 py-4 font-sans-custom">
        <nav class="flex flex-col text-[#4A3B2E]">
            @foreach($menus as $menu)
                @php
                    $menuUrl = $menu['url'] ?? '#';
                    if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                        $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                    }
                @endphp
                @if(!empty($menu['children']))
                    <details class="clo-mobile-group border-b border-[#4A3B2E]/10 last:border-b-0">
                        <summary class="flex items-center justify-between py-3.5 cursor-pointer list-none text-xs font-semibold tracking-[0.15em] uppercase hover:text-teal-700 transition">
                            <span>{{ $menu['label'] }}</span>
                            <svg class="clo-mobile-caret transition-transform duration-200" width="14" height="14" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </summary>
                        <div class="pb-3 pl-4 flex flex-col gap-3">
                            @foreach($menu['children'] as $child)
                                @php
                                    $childUrl = $child['url'] ?? '#';
                                    if (!empty($childUrl) && $childUrl !== '#' && !str_starts_with($childUrl, 'http') && !str_starts_with($childUrl, '/')) {
                                        $childUrl = '/' . ($website->domain ?? '') . '/' . ltrim($childUrl, '/');
                                    }
                                @endphp
                                <a href="{{ $childUrl }}"
                                    class="text-sm font-normal normal-case tracking-normal text-[#4A3B2E]/80 hover:text-teal-700 transition">
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </details>
                @else
                    @php
                        $routeName = !empty($menu['url']) ? 'pages' : 'template';
                        $routeParams = ($routeName === 'template')
                            ? ['client' => $website->domain ?? '']
                            : ['client' => $website->domain ?? '', 'pages' => $menu['url']];
                    @endphp
                    <a href="{{ route($routeName, $routeParams) }}"
                        class="py-3.5 border-b border-[#4A3B2E]/10 last:border-b-0 text-xs font-semibold tracking-[0.15em] uppercase hover:text-teal-700 transition">
                        {{ $menu['label'] }}
                    </a>
                @endif
            @endforeach
        </nav>
    </div>
</header>

<style>
    /* Panah sub-menu rotate saat group dibuka */
    .clo-mobile-group[open] .clo-mobile-caret {
        transform: rotate(180deg);
    }

    /* Hilangkan marker default <summary> di Safari/Chrome */
    .clo-mobile-group>summary::-webkit-details-marker {
        display: none;
    }
</style>

<script>
    (function () {
        var burger = document.getElementById('clo-burger');
        var panel = document.getElementById('clo-mobile-menu');
        if (!burger || !panel) return;

        var iconOpen = burger.querySelector('.clo-burger-icon-open');
        var iconClose = burger.querySelector('.clo-burger-icon-close');

        function setOpen(open) {
            panel.hidden = !open;
            burger.setAttribute('aria-expanded', open ? 'true' : 'false');
            burger.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
            if (iconOpen) iconOpen.classList.toggle('hidden', open);
            if (iconClose) iconClose.classList.toggle('hidden', !open);
        }

        burger.addEventListener('click', function () {
            setOpen(panel.hidden);
        });

        // Tutup panel saat resize ke tampilan desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) setOpen(false);
        });

        // Tutup panel ketika klik di luar header
        document.addEventListener('click', function (e) {
            if (panel.hidden) return;
            if (burger.contains(e.target) || panel.contains(e.target)) return;
            setOpen(false);
        });

        // Tutup panel dengan tombol Escape
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !panel.hidden) {
                setOpen(false);
                burger.focus();
            }
        });

        // Tutup panel setelah memilih menu
        panel.addEventListener('click', function (e) {
            if (e.target.closest('a')) setOpen(false);
        });
    })();
</script>