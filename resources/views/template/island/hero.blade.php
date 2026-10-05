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

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#ffffffff';
    $button_color = $content['button_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#ffffff';

    $hero_bg = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';
    $hero_img = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';
@endphp

<svg class="hidden absolute w-0 h-0" aria-hidden="true">
    <defs>
        <!-- Rainbow squiggle stripes -->
        <symbol id="squiggle" viewBox="0 0 100 220">
            <g fill="none" stroke-linecap="round">
                <path d="M12,-10 C52,30 -28,70 12,110 C52,150 -28,190 12,230" stroke="#EFD9A8" stroke-width="11"/>
                <path d="M36,-10 C76,30 -4,70 36,110 C76,150 -4,190 36,230" stroke="#C3A6C8" stroke-width="11"/>
                <path d="M60,-10 C100,30 20,70 60,110 C100,150 20,190 60,230" stroke="#AFCBA8" stroke-width="11"/>
            </g>
        </symbol>
    </defs>
</svg>

<section class="relative w-full bg-[#DDBCD3] overflow-hidden">
    <!-- Squiggle top-left -->
    <svg class="absolute -top-6 -left-8 w-24 md:w-32 h-64 md:h-80 rotate-[30deg] z-20 opacity-90" aria-hidden="true"><use href="#squiggle"/></svg>
    <!-- Squiggle right edge -->
    <svg class="absolute top-1/3 -right-6 w-20 md:w-28 h-72 md:h-96 rotate-[100deg] z-20 opacity-90" aria-hidden="true"><use href="#squiggle"/></svg>

    <!-- Hamburger -->
    <!-- <button class="absolute top-5 right-6 z-30 text-white/95 hover:text-white transition-colors" aria-label="Menu">
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button> -->

    <!-- Two dogs -->
    <div class="relative z-10 flex items-stretch justify-center">
        <div class="w-full h-[380px] md:h-[540px]">
            <img src="{{ asset($hero_img) }}" alt="French Bulldog" class="w-full h-full object-cover">
        </div>
    </div>

    <!-- Groovy sticker logo -->
    <!-- <div class="absolute inset-x-0 top-[34%] z-20 flex justify-center pointer-events-none">
        <svg viewBox="0 0 340 210" class="w-56 md:w-96 [filter:drop-shadow(0_10px_18px_rgba(90,40,70,0.25))]" aria-label="{{ $title }}">
            <defs>
                <path id="logoTop" d="M30,112 Q170,42 310,112"/>
                <path id="logoBot" d="M92,184 Q170,150 248,184"/>
            </defs>
            <text font-family="Shrikhand, cursive" font-size="55" fill="#F2A7C3" transform="translate(5,8)">
                <textPath href="#logoTop" startOffset="50%" text-anchor="middle">{{ $title }}</textPath>
            </text>
        </svg>
    </div> -->
</section>
