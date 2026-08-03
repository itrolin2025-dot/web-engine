@php
    $navContent = isset($layout->content) ? json_decode($layout->content, true) : [];
    //ambil logo
    $logoFile = $navContent['logo'] ?? null;
    $logo = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;

    //ambil nama brand
    $brand = $navContent['brand'] ?? ($website->title ?? '');
    //ambil menu
    $menus = $navContent['menus'] ?? [];
    //ambil tombol cta
    $cta_text = $navContent['cta_text'] ?? 'Contact Us';
    $cta_url = $navContent['cta_url'] ?? '#';
    $cta_color = $navContent['cta_color'] ?? '#000000ff';
@endphp

<nav class="border-b border-stone-200 bg-white sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
        <!-- Logo -->
        <a href="#" class="flex items-center text-stone-800 hover:opacity-80 transition-opacity" aria-label="Home">
            @if($logo)
                <img src="{{ asset($logo) }}" alt="{{ $brand }}" class="h-20 w-auto object-contain">
            @else
                <span class="text-xl font-bold tracking-tight" style="color: {{ $cta_color }}">{{ $brand }}</span>
            @endif
        </a>

        <div class="hidden md:flex space-x-8 items-center">
            @foreach($menus as $menu)
                @php
                    $menuUrl = $menu['url'] ?? '#';
                    if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                        $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                    }
                @endphp
                @if(!empty($menu['children']))
                    {{-- Menu dengan Dropdown --}}
                    <div class="relative group">
                        <button
                            class="flex items-center gap-1 text-sm font-medium tracking-wide text-stone-600 hover:text-stone-900 transition-colors pb-4 -mb-4">
                            {{ $menu['label'] }}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" class="w-3 h-3 transition-transform group-hover:rotate-180">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div
                            class="absolute left-0 top-full mt-0 w-48 bg-white border border-stone-100 shadow-lg rounded-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-50 overflow-hidden">
                            <div class="py-2">
                                @foreach($menu['children'] as $child)
                                    @php
                                        $childUrl = $child['url'] ?? '#';
                                        if (!empty($childUrl) && $childUrl !== '#' && !str_starts_with($childUrl, 'http') && !str_starts_with($childUrl, '/')) {
                                            $childUrl = '/' . ($website->domain ?? '') . '/' . ltrim($childUrl, '/');
                                        }
                                    @endphp
                                    <a href="{{ $childUrl }}"
                                        class="block px-5 py-2.5 text-sm text-stone-600 hover:bg-stone-50 hover:text-stone-900 transition-colors">
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Menu biasa --}}
                    @php
                        $routeName = !empty($menu['url']) ? 'pages' : 'template';
                        $routeParams = ($routeName === 'template')
                            ? ['any' => $website->domain ?? '']
                            : ['client' => $website->domain ?? '', 'pages' => $menu['url']];
                    @endphp
                    <a href="{{ route($routeName, $routeParams) }}"
                        class="text-sm font-medium tracking-wide text-stone-600 hover:text-stone-900 transition-colors">
                        {{ $menu['label'] }}
                    </a>
                @endif
            @endforeach
        </div>

        <div>
            <a href="{{ $cta_url }}"
                class="inline-block border px-5 py-1.5 rounded-full text-sm font-medium tracking-wide transition-colors"
                style="border-color: {{ $cta_color }}; color: {{ $cta_color }};">
                {{ $cta_text }}
            </a>
        </div>
    </div>
</nav>