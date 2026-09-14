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

    $repeater = $content['repeater'] ?? $content['tagline'];
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

<section class="w-full bg-white py-16 md:py-24 px-4 md:px-8 font-sans text-[#1a1a1a]"
style="background-color:{{ $background_color }};">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12 items-start">

        <!-- Column 1: Left Main Heading -->
        <div class="flex flex-col justify-start">
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-[{{ $title_color }}] tracking-tight leading-tight mb-4">
                {{ $title }}
            </h2>
            <p class="text-xs md:text-sm text-[{{ $subtitle_color }}] leading-relaxed max-w-sm">
                {{ $subtitle }}    
            </p>
        </div>

        <!-- Column 2: Center Image & Description -->
        @foreach ($repeater as $img)
            <div class="flex flex-col">
                <!-- Image Wrapper -->
                <div class="w-full h-[320px] sm:h-[380px] md:h-[420px] rounded-xl overflow-hidden mb-6 shadow-sm">
                    <img src="{{ asset('images/website/' . $domain . '/' . $img['image']) }}"
                        alt="{{ $img['label'] ?? '' }}"
                        class="w-full h-full object-cover object-center hover:scale-105 transition duration-500" />
                </div>

                <!-- Description Block -->
                <h3 class="text-base md:text-lg font-bold text-[{{ $img['color'] ?? '' }}] mb-2">
                    {{ $img['label'] ?? '' }}
                </h3>
                <p class="text-xs md:text-sm text-[{{ $img['color'] ?? '' }}] leading-relaxed">
                    {{ $img['sublabel'] ?? $img['label'] }}
                </p>
            </div>
        @endforeach

    </div>
</section>