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
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $hero_img = !empty($content['hero_img']) ? 'images/website/' . $domain . '/' . $content['hero_img'] : '';
@endphp

<section class="relative w-full" style="
        min-height: 90vh;
        background-image: url('{{ asset($hero_bg) }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    ">

    <!-- <div class="absolute inset-0"></div> -->

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-40 flex items-center min-h-[90vh]">
        <div class="space-y-6 max-w-xl">
            @if(!empty($tag))
                <span class="text-xs font-bold uppercase tracking-widest block"
                    style="color: {{ $tag_color }};">{{ $tag }}</span>
            @endif
            <h1 class=" text-4xl sm:text-5xl lg:text-6xl font-serif-brand leading-tight"
                style=" color: {{ $title_color }};">
                {{ $title }}
            </h1>
            <p class="max-w-md text-base sm:text-lg leading-relaxed" style=" color: {{ $subtitle_color }};">
                {{ $subtitle }}
            </p>
            <div class="pt-4 flex flex-wrap gap-4">
                @if(!empty($button_text))
                    <a href="#"
                        class="inline-flex items-center gap-2 text-white px-8 py-3.5 rounded-full text-sm font-medium tracking-wide transition-colors shadow-sm"
                        style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                        {{ $button_text }}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </a>
                @endif
                <!-- <a href="#"
                        class="inline-block border border-[#61984B] text-[#61984B] px-8 py-3.5 rounded-full text-sm font-medium tracking-wide hover:border-white transition-colors">
                        Learn More
                    </a> -->
            </div>
        </div>
    </div>
</section>