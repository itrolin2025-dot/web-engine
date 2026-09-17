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

<header
    class="bg-[{{ $background_color }}] border-b border-gray-100 py-4 px-6 md:px-12 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-10">
        <a href="#" class="text-2xl font-extrabold tracking-tight text-[{{ $title_color }}]">
            {{ $title }}
        </a>

        <nav class="hidden lg:flex items-center gap-7 text-[14px] font-medium text-[#333333]">

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
    </div>

    <div class="flex items-center gap-5 text-[#222222]">
        <!-- <button aria-label="Search" class="hover:opacity-75 transition">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8" />
                <path d="M21 21l-4.35-4.35" />
            </svg>
        </button> -->
        <!-- <button aria-label="Account" class="hover:opacity-75 transition">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
        </button> -->
        <!-- <button aria-label="Wishlist" class="relative hover:opacity-75 transition">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l8.78-8.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
            </svg>
            <span
                class="absolute -top-2 -right-2 bg-[#ff4d4f] text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">0</span>
        </button> -->
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
    </div>
</header>