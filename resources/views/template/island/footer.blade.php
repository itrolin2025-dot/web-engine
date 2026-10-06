@php
    $rawContent = $layout->content ?? '';

    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
        // Strip control characters that break json_decode
        $cleanJson = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $rawContent);
        $content = json_decode($cleanJson, true) ?? json_decode($rawContent, true) ?? [];
    } else {
        $content = [];
    }

    $domain = $website->domain ?? '';

    $title = $content['title_en'] ?? $content['title'] ?? '';
    $title_color = $content['title_color'] ?? '#7B5E3B';
    $button_color = $content['button_color'] ?? '#7B5E3B';
    $button_text_color = $content['button_text_color'] ?? '#7B5E3B';

    $background_color = $content['background_color'] ?? '#F2D9A4';

    $img = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';

    $socials = [
        ['label' => 'Facebook', 'href' => '#', 'icon' => 'facebook'],
        ['label' => 'Instagram', 'href' => '#', 'icon' => 'instagram'],
        ['label' => 'TikTok', 'href' => '#', 'icon' => 'tiktok'],
        ['label' => 'WhatsApp', 'href' => '#', 'icon' => 'whatsapp'],
    ];

    $iconPaths = [
        'facebook' => '<path d="M13.5 21v-7h2.4l.4-3h-2.8V9.1c0-.9.3-1.5 1.6-1.5h1.3V4.9c-.3 0-1.1-.1-2-.1-2 0-3.4 1.2-3.4 3.5V11H8.5v3H11v7h2.5z"/>',
        'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"/>',
        'tiktok' => '<path d="M16.6 5.8a4.8 4.8 0 0 1-3.1-1.7v7.2a2.9 2.9 0 1 1-2.9-2.9c.3 0 .6 0 .8.1v2.7a1 1 0 1 0 1.4.9V3.9h2.6c.2 1.6 1.4 2.9 3.1 3.2z"/>',
        'whatsapp' => '<path d="M20.5 3.5A11.9 11.9 0 0 0 12.1 0C5.6 0 .3 5.3 .3 11.8c0 2.1.6 4.1 1.6 5.9L0 24l6.5-1.7a12 12 0 0 0 5.5 1.4h.1c6.5 0 11.8-5.3 11.8-11.8a12 12 0 0 0-3.4-8.4zm-8.4 18.3c-.9 0-1.8-.3-2.5-.7l-.2-.1-1.9.5.5-1.9-.1-.2a9.7 9.7 0 0 1-1.5-5.2c0-5.3 4.4-9.6 9.7-9.6a9.7 9.7 0 0 1 9.7 9.7c0 5.3-4.4 9.5-9.7 9.5zm5.4-7.2c-.3-.1-1.7-.8-2-.9s-.5-.1-.7.2-.8.9-1 1.1-.4.2-.7.1a8 8 0 0 1-2.3-1.4 8.7 8.7 0 0 1-1.6-2c-.2-.3 0-.5.1-.7l.5-.6.3-.5a2 2 0 0 0-.1-.5l-.9-2.2c-.2-.6-.5-.5-.7-.5h-.6a1.2 1.2 0 0 0-.9.4 3.6 3.6 0 0 0-1.1 2.8 6.2 6.2 0 0 0 1.3 3.3 14.2 14.2 0 0 0 5.5 4.8c2 .7 2.7.8 3.9.7a3.2 3.2 0 0 0 2.1-1.5 2.6 2.6 0 0 0 .2-1.5c-.1-.1-.3-.2-.6-.3z"/>',
    ];
@endphp

<footer class="w-full bg-[#{{ $background_color }}] pt-24 pb-10 px-6 text-center relative">
    <!-- Mascot image, breaks out above the footer top edge -->
    @if ($img !== '')
        <img src="{{ asset($img) }}" alt="{{ $title }}"
            class="absolute left-1/2 -translate-x-1/2 -top-20 sm:-top-20 md:-top-20 z-10 w-28 h-28 sm:w-32 sm:h-32 md:w-40 md:h-40 object-contain" />
    @endif

    <!-- Social media links -->
    <div class="relative z-10 flex items-center items-center justify-center gap-3 mb-8">
        @foreach ($socials as $social)
            <a href="{{ $social['href'] }}" aria-label="{{ $social['label'] }}"
                class="w-10 h-10 inline-flex items-center justify-center rounded-full bg-white/70 text-[#7B5E3B] hover:bg-white hover:text-[#5A3E2B] transition-colors shadow-sm">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $iconPaths[$social['icon']] !!}</svg>
            </a>
        @endforeach
    </div>

    <!-- Primary nav -->
    <nav class="relative z-10 flex flex-wrap items-center justify-center gap-x-8 gap-y-3 mb-8 text-[11px] md:text-xs font-extrabold tracking-[0.18em] text-[#7B3E3B] uppercase">
        @foreach($footerPresets['footer_menu'] ?? [] as $menu)
            @php
                $menuUrl = $menu['url'] ?? '#';
                if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                    $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                }
            @endphp
            <a href="{{ $menuUrl }}" class="hover:text-[#5A3E2B] transition-colors">{{ $menu['label'] }}</a>
        @endforeach
    </nav>

    <!-- Secondary nav -->
    <nav class="relative z-10 flex flex-wrap items-center justify-center gap-x-8 gap-y-2 mb-6 text-[9px] md:text-[10px] font-bold tracking-[0.15em] text-[{{ $button_text_color }}] uppercase">
        @foreach($footerPresets['footer_secondary_menu'] ?? [] as $menu)
            @php
                $menuUrl = $menu['url'] ?? '#';
                if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                    $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                }
            @endphp
            <a href="{{ $menuUrl }}" class="hover:text-[#5A3E2B] transition-colors">{{ $menu['label'] }}</a>
        @endforeach
        <!-- <a href="#" class="hover:text-[#5A3E2B] transition-colors">Become an Ambassador</a> -->
        <a href="#" class="hover:text-[#5A3E2B] transition-colors">Shipping &amp; Return Policy</a>
        <a href="#" class="hover:text-[#5A3E2B] transition-colors">Terms &amp; Conditions</a>
        <a href="#" class="hover:text-[#5A3E2B] transition-colors">Privacy Policy</a>
    </nav>

    <p class="relative z-10 text-[10px] md:text-[11px] text-[#8A6D3B]">©2026 All Rights Reserved by <a href="https://alisabethdesigns.com" target="_blank" class="underline hover:text-[#5A3E2B]">Rolin</a></p>
</footer>