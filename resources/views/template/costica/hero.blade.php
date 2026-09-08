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
@endphp

<section class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-2 pt-20 lg:pt-0">

    <!-- Left Column: Content -->
    <div class="flex flex-col justify-center items-start px-8 sm:px-16 lg:px-24 py-16"
        style="background-color:{{ $background_color }}">

        <span class="text-xs font-semibold tracking-[0.2em] uppercase mb-4" style="color: {{ $tag_color }}">
            {{ $tag }}
        </span>

        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.15] mb-6" style="color: {{ $title_color }}">
            {!! !empty($title) ? nl2br(e($title)) : '' !!}
        </h1>

        <p class="text-sm sm:text-base max-w-md leading-relaxed mb-8" style="color: {{ $subtitle_color }}">
            {!! !empty($subtitle) ? nl2br(e($subtitle)) : '' !!}
        </p>

        <a href="#"
            class="inline-flex items-center gap-2 text-sm font-semibold px-8 py-3.5 rounded-md transition duration-300 shadow-sm"
            style="color:{{ $button_text_color }}; background-color:{{ $button_color }}">
            {{ $button_text }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3">
                </path>
            </svg>
        </a>

    </div>

    <!-- Right Column: Product Hero Image -->
    <div
        class="relative min-h-[450px] lg:min-h-screen bg-[#e8e2d9] flex items-center justify-center overflow-hidden" style="background-image: url('{{ asset($hero_img) }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    </div>

</section>