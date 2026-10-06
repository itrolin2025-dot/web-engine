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

    $tag          = $content['tag_en'] ?? $content['tag'] ?? '';
    $tag_color    = $content['tag_color'] ?? '#ffffff';

    $title        = $content['title_en'] ?? $content['title'] ?? '';
    $title_color  = $content['title_color'] ?? '#ffffff';

    $subtitle       = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $desc       = $content['description'] ?? '';
    $desc_color = $content['description_color'] ?? '#000000';

    $button_text       = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#ffffff';
    $button_color      = $content['button_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#ffffff';
    $image            = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';
    $background        = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : '';
@endphp

{{-- Wave connector (top) --}}
<svg class="block w-full relative z-10 -mt-16 -mb-px pointer-events-none" viewBox="0 0 1440 64" preserveAspectRatio="none" aria-hidden="true">
    <path fill="{{ $background_color }}" d="M0,40 C80,24 160,54 240,42 C320,30 400,56 480,42 C560,28 640,54 720,42 C800,30 880,56 960,42 C1040,28 1120,54 1200,42 C1280,30 1360,54 1440,42 L1440,64 L0,64 Z"/>
</svg>

<section class="relative w-full bg-[{{ $background_color }}] z-20" style="overflow: visible;">

    {{-- Main content: vertically & horizontally centered, minimum full viewport height --}}
    <div class="md:min-h-screen flex items-center justify-center px-4 py-8 md:py-16 md:px-12">

        {{-- 3-column grid: [image | text | image] --}}
        <div class="w-full max-w-8xl grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 items-center">

            {{-- Column 1: Left image (tilted left) — hidden on mobile --}}
            <div class="hidden md:flex items-center justify-center">
                @if($background)
                    <img src="{{ asset($background) }}"
                         alt="{{ $title }}"
                         class="w-full max-w-[340px] lg:max-w-[400px] max-h-[520px] h-auto object-contain"
                         style="transform: rotate(-6deg);">
                @endif
            </div>

            {{-- Column 2: Center text — always visible --}}
            <div class="flex flex-col items-center justify-center text-center gap-6 px-2">

                @if($tag)
                    <span class="inline-block text-xs font-bold tracking-[0.2em] uppercase px-4 py-1 rounded-full"
                          style="color: {{ $tag_color }}; border: 1.5px solid {{ $tag_color }};">
                        {{ $tag }}
                    </span>
                @endif

                @if($title)
                    <h2 class="font-script text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight"
                        style="color: {{ $title_color }};">
                        {{ $title }}
                    </h2>
                @endif

                @if($desc)
                    <p class="font-groovy text-base sm:text-lg md:text-xl lg:text-xl leading-snug"
                       style="color: {{ $desc_color }}; text-shadow: 0 1px 0 rgba(160,120,40,0.2);">
                        {{ $desc }}
                    </p>
                @endif

                @if($subtitle)
                    <p class="text-sm sm:text-base leading-relaxed opacity-80"
                       style="color: {{ $subtitle_color }};">
                        {{ $subtitle }}
                    </p>
                @endif

                @if($button_text)
                    <a href="#"
                       class="inline-block mt-2 px-7 py-3 rounded-full text-sm font-semibold transition-opacity hover:opacity-80"
                       style="background-color: {{ $button_color }}; color: {{ $button_text_color }}; text-decoration: none;">
                        {{ $button_text }}
                    </a>
                @endif

            </div>

            {{-- Column 3: Right image (tilted right) — hidden on mobile --}}
            <div class="hidden md:flex items-center justify-center">
                @if($image)
                    <img src="{{ asset($image) }}"
                         alt="{{ $title }}"
                         class="w-full max-w-[340px] lg:max-w-[400px] max-h-[520px] h-auto object-contain"
                         style="transform: rotate(6deg);">
                @endif
            </div>

            {{-- Mobile: single image below text --}}
            @if($image)
                <div class="flex md:hidden items-center justify-center">
                    <img src="{{ asset($image) }}"
                         alt="{{ $title }}"
                         class="w-full max-w-[180px] h-auto object-contain">
                </div>
            @endif

        </div>
    </div>

    {{-- Wave connector (bottom) — overlaps next section --}}

</section>

<svg class="block w-full relative z-30 h-8 md:h-16 -mt-px -mb-8 md:-mb-16 pointer-events-none"
     style="transform: rotate(180deg); transform-origin: 50% 50%;"
     viewBox="0 0 1440 64" preserveAspectRatio="none" aria-hidden="true">
    <path fill="{{ $background_color }}" d="M0,40 C80,24 160,54 240,42 C320,30 400,56 480,42 C560,28 640,54 720,42 C800,30 880,56 960,42 C1040,28 1120,54 1200,42 C1280,30 1360,54 1440,42 L1440,64 L0,64 Z"/>
</svg>
