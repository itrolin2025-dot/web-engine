@php
    $rawContent = $layout->content ?? '';

    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
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

    $background = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';
    $image = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';

    $repeater = $content['repeater'] ?? $content['tagline'];
    if (is_array($repeater)) {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }
@endphp

<section class="py-12 px-4 max-w-7xl mx-auto font-sans text-[#2d3748]">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        @foreach($repeater as $i => $item)
            @php
                $bannerBg = !empty($item['image']) ? asset('images/website/' . $domain . '/' . $item['image']) : asset($background);
                $bannerTag = $item['label'] ?? '';
                $bannerTagColor = $item['color'] ?? '#ffffff';
                $bannerTitle = $item['title'] ?? '';
                $bannerTitleColor = $item['title_color'] ?? '#ffffff';
                $bannerSubtitle = $item['subtitle'] ?? '' ;
                $bannerSubtitleColor = $item['subtitle_color'] ?? '#ffffff';
                $bannerBtnText = $item['button_text'] ?? ''  ;
                $bannerBtnColor = $item['button_color'] ?? '#000000';
                $bannerBtnTextColor = $item['button_text_color'] ?? '#ffffff';
            @endphp
            <div
                class="relative overflow-hidden rounded-xl bg-[#f2f2f2] min-h-[380px] md:min-h-[420px] flex items-center p-8 md:p-12 group">
                <!-- Background Image -->
                <img src="{{ $bannerBg }}"
                    alt="{{ $bannerTitle }}"
                    class="absolute inset-0 w-full h-full object-cover object-center transition duration-500 group-hover:scale-105" />

                <!-- Content Overlay -->
                <div class="relative z-10 max-w-[240px] sm:max-w-[280px]">
                    <p class="text-xs md:text-sm font-medium text-[{{ $bannerTagColor }}] mb-2">
                        {{ $bannerTag }}
                    </p>
                    <h3 class="text-2xl md:text-3xl font-bold text-[{{ $bannerTitleColor }}] leading-tight mb-3">
                        {{ $bannerTitle }}
                    </h3>
                    <p class="text-xs md:text-sm text-[{{ $bannerSubtitleColor }}] mb-6 leading-relaxed">
                        {{ $bannerSubtitle }}
                    </p>
                    <a href="#"
                        class="inline-block bg-[{{ $bannerBtnColor }}] text-[{{ $bannerBtnTextColor }}] font-semibold text-xs md:text-sm px-7 py-3 rounded-full shadow-sm hover:bg-black hover:text-white transition duration-300">
                        {{ $bannerBtnText }}
                    </a>
                </div>
            </div>
        @endforeach

    </div>
</section>