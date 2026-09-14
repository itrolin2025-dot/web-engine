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

    $background = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';
    $image      = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';
@endphp

<section class="w-full bg-white py-16 md:py-24 px-4 md:px-8 font-sans text-[#1a1a1a]">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">

        <div class="lg:col-span-6 relative pb-16 lg:pb-24">
            <div class="w-[75%] sm:w-[70%] h-[320px] sm:h-[420px] rounded-xl overflow-hidden shadow-sm">
                <img src="{{ $background }}"
                    alt="Beauty Application" class="w-full h-full object-cover object-center" />
            </div>

            <div
                class="absolute bottom-0 right-0 sm:right-6 w-[60%] sm:w-[55%] h-[240px] sm:h-[300px] rounded-xl overflow-hidden shadow-xl border-4 border-white">
                <img src="{{ $image }}"
                    alt="Mecca Cosmetica Products" class="w-full h-full object-cover object-center" />
            </div>
        </div>

        <div class="lg:col-span-6 lg:pl-8 flex flex-col items-start justify-center">
            <span class="text-[11px] md:text-xs font-bold tracking-[0.2em] block mb-3" style="color: {{ $tag_color ?? '#000000ff' }}">
                {{ $tag }}
            </span>

            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight mb-6 leading-tight" style="color: {{ $title_color ?? '#000000ff' }}">
                {!! !empty($title) ? nl2br(e($title)) : '' !!}
            </h2>

            <p class="text-xs md:text-sm leading-relaxed max-w-lg mb-8" style="color: {{ $subtitle_color ?? '#000000ff' }}">
                {!! !empty($subtitle) ? nl2br(e($subtitle)) : '' !!}
            </p>

            <a href="#"
                class="inline-flex items-center gap-2 bg-[{{ $button_color }}] hover:bg-[#ebd8b6] text-[{{ $button_text_color }}] text-xs md:text-sm font-semibold px-7 py-3.5 rounded-md transition duration-300">
                About Us
                <span class="text-sm">↗</span>
            </a>
        </div>

    </div>
</section>