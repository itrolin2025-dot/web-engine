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

    $desc = $content['description'] ?? '';
    $desc_color = $content['description_color'] ?? '#000000';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#ffffffff';
    $button_color = $content['button_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#ffffff';

    $background = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';
    $image      = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';
@endphp

<section class="w-full bg-[{{ $background_color }}] py-20 px-6 md:px-20">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-12 items-center">

        <!-- Bio Kiri -->
        <div class="md:col-span-6 flex flex-col items-start">
            <h2 class="font-script text-6xl md:text-7xl font-bold mb-6 text-[{{ $title_color }}]">{{ $title }}</h2>
            <p class="text-sm md:text-base text-[{{ $desc_color }}] leading-relaxed mb-4">
                {{ $desc }}
            </p>
        </div>

        <!-- Image Kanan (Green Gingham Frame) -->
        <div class="md:col-span-6 flex justify-center">
            <div class="green-gingham p-4 md:p-6 rounded-[28px] shadow-2xl relative w-full max-w-lg">
                <div class="relative rounded-[28px] overflow-hidden shadow-lg">
                    <img src="{{ asset($image) }}" alt="About Clo" class="w-full h-80 md:h-[420px] object-cover">
                </div>
            </div>
        </div>

    </div>
</section>