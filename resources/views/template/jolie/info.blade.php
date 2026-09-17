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

    $repeater = $content['repeater'] ?? $content['tagline'];
    if (is_array($repeater)) {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }
@endphp

<div class="bg-[{{ $background_color }}] text-[{{ $title_color }}] text-[12px] py-2 px-6 md:px-12 flex justify-between items-center">
    <div>
        {{ $title }}
        <a href="#" class="underline font-medium hover:text-[{{ $button_text_color }}]">{{ $button_text }}</a>
    </div>
    <div class="hidden md:flex items-center gap-5 text-[{{ $title_color }}]">
        @foreach ($repeater as $item)
            <a href="#" class="hover:text-white transition">{{ $item['label'] }}</a>
        @endforeach
        <!-- <div class="flex items-center gap-1 cursor-pointer hover:text-white transition">
            <span class="text-[14px]">🇺🇸</span>
            <span>English</span>
            <svg width="10" height="6" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M1 1l4 4 4-4" />
            </svg>
        </div> -->
        <!-- <div class="flex items-center gap-1 cursor-pointer hover:text-white transition">
            <span>USD $</span>
            <svg width="10" height="6" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M1 1l4 4 4-4" />
            </svg>
        </div> -->
    </div>
</div>