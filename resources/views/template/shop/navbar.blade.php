@php
    $navContent = isset($layout->content) ? json_decode($layout->content, true) : [];

    // Ambil logo
    $logoFile = $navContent['logo'] ?? null;
    $logo = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;

    // Ambil nama brand
    $brand = $navContent['brand'] ?? ($website->title ?? '');

    // Ambil menu
    $menus = $navContent['menus'] ?? [];

    // Ambil tombol CTA
    $cta_text  = $navContent['cta_text']  ?? '';
    $cta_url   = $navContent['cta_url']   ?? '#';
    $cta_color = $navContent['cta_color'] ?? '#000000';

    // Helper: build URL dari menu item
    $buildUrl = function ($url) use ($website) {
        if (empty($url) || $url === '#') return '#';
        if (str_starts_with($url, 'http') || str_starts_with($url, '/')) return $url;
        return '/' . ($website->domain ?? '') . '/' . ltrim($url, '/');
    };
@endphp

<nav class="bg-white border-b border-stone-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

        {{-- ===== LOGO ===== --}}
        <a href="{{ route('template', ['client' => $website->domain ?? '']) }}"
           class="flex items-center gap-2 shrink-0">
            @if($logo)
                <img src="{{ asset($logo) }}" alt="{{ $brand }}" class="h-12 w-auto object-contain">
            @else
                <span class="text-xl font-bold tracking-tight" style="color: {{ $cta_color }}">
                    {{ $brand }}
                </span>
            @endif
        </a>

        {{-- ===== NAVIGATION LINKS (Desktop) ===== --}}
        <ul class="hidden md:flex items-center gap-8 text-xs uppercase tracking-widest font-semibold text-stone-600 list-none m-0 p-0">

            @foreach($menus as $menu)
                @php
                    $menuUrl = $buildUrl($menu['url'] ?? '#');
                    $hasChildren = !empty($menu['children']);
                @endphp

                @if($hasChildren)
                    {{-- Dropdown Item --}}
                    <li class="relative group">
                        <button type="button"
                                class="flex items-center gap-1 uppercase font-semibold text-stone-600 hover:text-black hover:font-bold transition-colors cursor-pointer py-2">
                            {{ $menu['label'] }}
                            <svg class="w-3 h-3 transition-transform duration-200 group-hover:rotate-180"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Panel --}}
                        <div class="absolute left-0 top-full mt-1 w-52 bg-white border border-stone-100 rounded-xl shadow-xl
                                    opacity-0 invisible translate-y-1
                                    group-hover:opacity-100 group-hover:visible group-hover:translate-y-0
                                    transition-all duration-200 ease-out z-50">
                            <ul class="py-2 list-none m-0 p-0">
                                @foreach($menu['children'] as $child)
                                    @php $childUrl = $buildUrl($child['url'] ?? '#'); @endphp
                                    <li>
                                        <a href="{{ $childUrl }}"
                                           class="block px-5 py-2.5 text-xs text-stone-600 hover:text-black hover:bg-stone-50 transition-colors">
                                            {{ $child['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>

                @else
                    {{-- Menu biasa --}}
                    <li>
                        <a href="{{ $menuUrl }}"
                           class="hover:text-black transition-colors py-2 block">
                            {{ $menu['label'] }}
                        </a>
                    </li>
                @endif
            @endforeach

            {{-- CTA Button --}}
            @if(!empty($cta_text) && !empty($cta_url) && $cta_url !== '#')
                <li>
                    <a href="{{ $buildUrl($cta_url) }}"
                       class="px-5 py-2 rounded-full text-white text-xs font-semibold uppercase tracking-widest
                              hover:opacity-85 transition-opacity"
                       style="background-color: {{ $cta_color }}">
                        {{ $cta_text }}
                    </a>
                </li>
            @endif

        </ul>

        {{-- ===== NAVBAR ICONS (Search & Cart) ===== --}}
        <div class="flex items-center gap-5 text-lg text-stone-700">

            {{-- Search --}}
            <button type="button"
                    class="hover:text-black transition-colors"
                    aria-label="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>

            {{-- Cart --}}
            <button type="button"
                    onclick="toggleCartDrawer()"
                    class="relative hover:text-black transition-colors"
                    aria-label="Cart">
                <i class="fa-solid fa-bag-shopping"></i>
                <span id="cart-badge"
                      class="absolute -top-2 -right-2 bg-pink-500 text-white text-[10px]
                             w-4 h-4 rounded-full flex items-center justify-center font-bold hidden">
                    0
                </span>
            </button>

            {{-- Mobile menu toggle --}}
            <button type="button" id="mobile-menu-btn"
                    class="md:hidden hover:text-black transition-colors"
                    aria-label="Menu">
                <i class="fa-solid fa-bars"></i>
            </button>

        </div>
    </div>

    {{-- ===== MOBILE MENU ===== --}}
    <div id="mobile-menu"
         class="md:hidden hidden border-t border-stone-100 bg-white px-6 pb-4">
        <ul class="flex flex-col gap-1 mt-3 list-none p-0">
            @foreach($menus as $menu)
                @php
                    $menuUrl = $buildUrl($menu['url'] ?? '#');
                    $hasChildren = !empty($menu['children']);
                @endphp

                @if($hasChildren)
                    <li>
                        <details class="group/mob">
                            <summary class="flex items-center justify-between py-2.5 text-xs uppercase tracking-widest
                                           font-semibold text-stone-600 cursor-pointer hover:text-black transition-colors list-none">
                                {{ $menu['label'] }}
                                <svg class="w-3 h-3 transition-transform duration-200 group-open/mob:rotate-180"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </summary>
                            <ul class="pl-4 mt-1 flex flex-col gap-1 list-none p-0 border-l-2 border-stone-100 ml-1">
                                @foreach($menu['children'] as $child)
                                    @php $childUrl = $buildUrl($child['url'] ?? '#'); @endphp
                                    <li>
                                        <a href="{{ $childUrl }}"
                                           class="block py-2 text-xs text-stone-500 hover:text-black transition-colors">
                                            {{ $child['label'] }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    </li>
                @else
                    <li>
                        <a href="{{ $menuUrl }}"
                           class="block py-2.5 text-xs uppercase tracking-widest font-semibold
                                  text-stone-600 hover:text-black transition-colors">
                            {{ $menu['label'] }}
                        </a>
                    </li>
                @endif
            @endforeach

            @if(!empty($cta_text) && !empty($cta_url) && $cta_url !== '#')
                <li class="mt-2">
                    <a href="{{ $buildUrl($cta_url) }}"
                       class="inline-block w-full text-center px-5 py-2.5 rounded-full text-white
                              text-xs font-semibold uppercase tracking-widest hover:opacity-85 transition-opacity"
                       style="background-color: {{ $cta_color }}">
                        {{ $cta_text }}
                    </a>
                </li>
            @endif
        </ul>
    </div>
</nav>

<script>
    // Mobile menu toggle
    const mobileBtn  = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileBtn && mobileMenu) {
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>