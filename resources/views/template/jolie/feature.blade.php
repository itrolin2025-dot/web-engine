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

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $desc = $content['desc_en'] ?? $content['desc'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $repeater = $content['repeater'] ?? $content['tagline'] ?? '';
    if (is_array($repeater)) {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }
    $tagline_color = $content['tagline_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';
@endphp

<section class="w-full bg-[#f6f6f6] font-sans text-[#1a1a1a] overflow-hidden">
    <div class="border-b border-gray-100 py-12 px-4">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center">

            @foreach ($repeater as $item)
                <div class="flex flex-col items-center">
                    <div class="mb-3">
                        <img src="{{ asset('images/website/' . $domain . '/' . $item['image']) }}" alt="{{ $item['title'] ?? 'Feature' }}"
                            class="w-10 h-10 rounded-full object-cover border-2 border-black shadow-sm" />
                    </div>
                    <h4 class="text-sm md:text-base font-bold text-[{{ $item['title_color'] ?? 'gray-500' }}] mb-1">{{ $item['title'] ?? '' }}</h4>
                    <p class="text-xs text-[{{ $item['subtitle_color'] ?? 'gray-500' }}] leading-relaxed">{{ $item['subtitle'] ?? '' }}</p>
                </div>
            @endforeach

        </div>
    </div>
</section>