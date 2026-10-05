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

<svg class="block w-full relative z-10 -mt-9 -mb-px" viewBox="0 0 1440 64" preserveAspectRatio="none" aria-hidden="true">
    <path fill="{{ $background_color }}" d="M0,40 C80,24 160,54 240,42 C320,30 400,56 480,42 C560,28 640,54 720,42 C800,30 880,56 960,42 C1040,28 1120,54 1200,42 C1280,30 1360,54 1440,42 L1440,64 L0,64 Z"/>
</svg>

<!-- 2. FOUNDER STORY (Yellow) -->
<section class="relative w-full bg-[{{ $background_color }}] px-4 pt-8 pb-10 md:px-16 z-20">
    <div class="max-w-7xl mx-auto flex flex-col gap-8 md:grid md:grid-cols-12 md:gap-8 md:items-start">

        <!-- Founder statement (mobile: baris pertama) -->
        <div class="order-1 md:order-2 md:col-span-5 md:pt-8 text-center flex flex-col items-center justify-center">
            <h2 class="font-script text-4xl sm:text-5xl md:text-6xl text-[{{ $title_color }}] mb-5 md:mb-7">{{ $title }}</h2>
            <p class="font-groovy text-[{{ $desc_color }}] text-[1.15rem] sm:text-[1.35rem] md:text-[2.1rem] leading-[1.4] [text-shadow:0_1px_0_rgba(160,120,40,0.25)]">
                {{ $desc }}
            </p>
        </div>

        <!-- Polaroids + Female Owned stamp (mobile: baris kedua, dua gambar bersebelahan) -->
        <div class="order-2 w-full flex items-start justify-center gap-4 sm:gap-6 md:contents">
        <div class="md:col-span-4 md:order-1 w-full max-w-[240px] sm:max-w-[280px] md:max-w-none relative md:h-[380px]">
            <!-- Stamp -->
            <!-- <div class="absolute -top-4 left-0 z-30 w-28 h-28 md:w-32 md:h-32">
                <svg viewBox="0 0 120 120" class="w-full h-full spin-slow">
                    <defs>
                        <path id="circleText" d="M60,60 m-46,0 a46,46 0 1,1 92,0 a46,46 0 1,1 -92,0"/>
                    </defs>
                    <text font-family="Montserrat, sans-serif" font-size="13.5" font-weight="800" fill="#E8A94E" letter-spacing="3.5">
                        <textPath href="#circleText">FEMALE OWNED • FEMALE OWNED •</textPath>
                    </text>
                    <g transform="translate(60,62)" fill="#E8A94E">
                        <ellipse cx="0" cy="-13" rx="5" ry="9"/>
                        <ellipse cx="0" cy="13" rx="5" ry="9"/>
                        <ellipse cx="-13" cy="0" rx="9" ry="5"/>
                        <ellipse cx="13" cy="0" rx="9" ry="5"/>
                        <ellipse cx="-9" cy="-9" rx="7" ry="5" transform="rotate(-45 -9 -9)"/>
                        <ellipse cx="9" cy="9" rx="7" ry="5" transform="rotate(-45 9 9)"/>
                        <ellipse cx="9" cy="-9" rx="7" ry="5" transform="rotate(45 9 -9)"/>
                        <ellipse cx="-9" cy="9" rx="7" ry="5" transform="rotate(45 -9 9)"/>
                        <circle cx="0" cy="0" r="5.5" fill="#D97B29"/>
                    </g>
                </svg>
            </div> -->
            <!-- Polaroid B (bottom left, overlapping) -->
            <div class="w-full md:w-auto md:absolute md:left-0 md:top-0 -rotate-3 rounded-[3px] z-20 overflow-hidden">
                <img src="{{ asset($image) }}" alt="{{ $title }}" class="w-full h-[200px] sm:h-[240px] md:h-[350px] object-cover">
            </div>
        </div>

        <!-- Arch photo (overlaps into promise section) -->
        <div class="md:col-span-3 md:order-3 w-full max-w-[240px] sm:max-w-[280px] md:max-w-none relative z-10 flex justify-center md:justify-end md:-mt-24 md:-mb-44">
            <div class="w-full md:w-auto rotate-3 z-20 overflow-hidden md:absolute md:left-0 md:top-20">
                <img src="{{ asset($image) }}" alt="{{ $title }}" class="w-full h-[200px] sm:h-[240px] md:h-[350px] object-cover">
            </div>
        </div>
        </div>
    </div>
</section>