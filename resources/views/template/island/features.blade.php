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

    $itemCount = count($repeater);
    $itemCols = match (true) {
        $itemCount <= 1 => 'grid-cols-1',
        $itemCount === 2 => 'grid-cols-1 sm:grid-cols-2',
        $itemCount === 3 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3',
        default => 'grid-cols-2 md:grid-cols-4',
    };
    $itemWidth = match (true) {
        $itemCount === 1 => 'max-w-sm',
        $itemCount === 2 => 'max-w-2xl',
        default => 'max-w-6xl',
    };

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';
@endphp

{{-- Wave connector (top) --}}
<svg class="block w-full relative z-10 h-8 md:h-16 -mt-4 md:-mt-14 -mb-px pointer-events-none"
     viewBox="0 0 1440 64" preserveAspectRatio="none" aria-hidden="true">
    <path fill="{{ $background_color }}" d="M0,40 C80,24 160,54 240,42 C320,30 400,56 480,42 C560,28 640,54 720,42 C800,30 880,56 960,42 C1040,28 1120,54 1200,42 C1280,30 1360,54 1440,42 L1440,64 L0,64 Z"/>
</svg>

<section class="w-full bg-[{{ $background_color }}] pt-6 pb-20 px-6 text-center">
    <h2 class="font-script text-5xl md:text-6xl text-[{{ $title_color }}] mb-14">{{ $title }}</h2>

    <div class="{{ $itemWidth }} mx-auto grid {{ $itemCols }} gap-x-6 gap-y-12">
        <!-- Community -->
        @foreach ($repeater as $img)
            <div class="flex flex-col items-center">
                <img src="{{ asset('images/website/' . $domain . '/' . $img['image']) }}" alt="{{ $img['title'] }}" class="h-20 md:h-40 mb-5">
                <h3 class="font-groovy text-sm md:text-base tracking-wide text-[{{ $title_color }}] uppercase mb-3">{{ $img['title'] }}</h3>
                <p class="text-[11px] md:text-xs leading-relaxed text-[{{ $subtitle_color }}] max-w-[220px]">{{ $img['subtitle'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- Wave connector (bottom) --}}
<svg class="block w-full relative z-30 h-8 md:h-16 -mt-px -mb-8 md:-mb-16 pointer-events-none"
     style="transform: rotate(180deg); transform-origin: 50% 50%;"
     viewBox="0 0 1440 64" preserveAspectRatio="none" aria-hidden="true">
    <path fill="{{ $background_color }}" d="M0,40 C80,24 160,54 240,42 C320,30 400,56 480,42 C560,28 640,54 720,42 C800,30 880,56 960,42 C1040,28 1120,54 1200,42 C1280,30 1360,54 1440,42 L1440,64 L0,64 Z"/>
</svg>