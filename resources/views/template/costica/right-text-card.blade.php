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

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    $image = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : 'images/default/broken.png';
@endphp

<section class="w-full bg-white font-sans text-[#1a1a1a]">
    <div class="w-full grid grid-cols-1 lg:grid-cols-2">
        <!-- Image Left -->
        <div class="relative min-h-[350px] md:min-h-[450px] lg:min-h-[500px]">
            <img src="{{ $image }}" alt="{{ $title }}" class="absolute inset-0 w-full h-full object-cover object-center" />
        </div>

        <!-- Text Right -->
        <div
            class="bg-[{{ $background_color }}] flex flex-col items-center justify-center text-center p-8 md:p-16 lg:p-20">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-[{{ $title_color }}] tracking-tight mb-4">
                {{ $title }}
            </h2>
            <p class="text-xs md:text-sm text-[{{ $subtitle_color }}] leading-relaxed max-w-md mb-8">
                {{ $subtitle }}
            </p>
            <a href="#"
                class="inline-flex items-center gap-2 bg-[{{ $button_color }}] text-[{{ $button_text_color }}] text-xs md:text-sm font-semibold px-8 py-3.5 rounded-md transition duration-300">
                {{ $button_text }}
                <span class="text-sm">↗</span>
            </a>
        </div>
    </div>
</section>