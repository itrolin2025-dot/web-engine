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

<section class="relative w-full min-h-screen flex flex-col bg-[#e5b453] overflow-hidden" style="position:relative;width:100%;min-height:100vh;display:flex;flex-direction:column;background-color:#e5b453;overflow:hidden;">
    <!-- Squiggle top-left -->
    <svg class="absolute -top-6 -left-8 w-24 md:w-32 h-64 md:h-80 rotate-[30deg] z-20 opacity-90" aria-hidden="true"><use href="#squiggle"/></svg>
    <!-- Squiggle right edge -->
    <svg class="absolute top-1/3 -right-6 w-20 md:w-28 h-72 md:h-96 rotate-[100deg] z-20 opacity-90" aria-hidden="true"><use href="#squiggle"/></svg>

    <!-- Hamburger -->
    <!-- <button class="absolute top-5 right-6 z-30 text-white/95 hover:text-white transition-colors" aria-label="Menu">
        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button> -->

    <!-- Hero image as background -->
    <div class="absolute inset-0 w-full h-full min-h-screen bg-cover bg-center hero-bg"
         style="position:absolute;inset:0;width:100%;height:100%;min-height:100vh;background-size:cover;background-position:center;background-image:url('{{ asset($hero_bg) }}');background-color:{{ $background_color }};"></div>

    <!-- Centered text overlay -->
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 z-30 pointer-events-none"
         style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:1rem;z-index:30;pointer-events:none;">
        <span class="inline-block font-semibold tracking-widest uppercase"
              style="display:inline-block;font-size:0.875rem;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.4rem;color:{{ $tag_color }};">{{ $tag }}</span>
        <h1 class="font-bold leading-tight"
            style="font-size:5.25rem;line-height:1.2;margin-bottom:0.4rem;color:{{ $title_color }};">{{ $title }}</h1>
        @if($hero_img)
            <img src="{{ asset($hero_img) }}" alt="hero image"
                 style="display:block;margin:0 auto;margin-bottom:0.4rem;max-width:30vh;max-height:30vh;width:auto;height:auto;object-fit:contain;">
        @endif
        <p class="max-w-xl mx-auto mb-2 leading-relaxed"
           style="font-size:1.125rem;line-height:1.6;max-width:36rem;margin:0 auto;margin-bottom:1.5rem;color:{{ $subtitle_color }};">{{ $subtitle }}</p>
        @if($button_text)
            <a href="#" class="inline-block mt-2 px-6 py-3 text-center font-medium transition"
               style="display:inline-block; max-height:40vh; margin-top:0.5rem;padding:1rem 1.5rem;text-align:center;font-weight:500;text-decoration:none;transition:background-color 0.2s ease, opacity 0.2s ease;background-color:{{ $button_color }};color:{{ $button_text_color }};">
                {{ $button_text }}
            </a>
        @endif
    </div>
</section>
