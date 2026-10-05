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

    $title = $content['title_en'] ?? $content['title'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#ffffff';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $desc = $content['desc_en'] ?? $content['desc'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $repeater = $content['repeater'] ?? $content['tagline'] ?? [];
    if (!is_array($repeater)) {
        $repeater = [];
    } else {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }
    $tagline_color = $content['tagline_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';
@endphp

<section class="w-full bg-[{{ $background_color }}] py-12 px-6 text-center">
    <p class="text-[10px] md:text-xs font-sans-custom font-bold tracking-[0.3em] text-[{{ $title_color }}] uppercase mb-8">{{ $title }}</p>
    <div class="max-w-5xl mx-auto flex flex-wrap items-center justify-center gap-8 md:gap-14 text-[#5F7036]">
       @foreach ($repeater as $img)
            <div class="w-40 h-40 sm:w-48 sm:h-48 md:w-56 md:h-56 rounded-xl overflow-hidden shrink-0">
                <img src="{{ asset('images/website/' . $domain . '/' . $img['image']) }}"
                    alt="Beauty Care"
                    class="w-full h-full object-cover" />
            </div>
        @endforeach
    </div>
</section>