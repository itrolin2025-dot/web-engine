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

    //ambil logo
    $logoFile = $navContent['image'] ?? null;
    $logo = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;

    //ambil nama brand
    $brand = $navContent['brand'] ?? ($website->title ?? 'My Brand');
    //ambil menu
    $menus = $navContent['menus'] ?? [];

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#000000';
    $button_color = $content['button_color'] ?? '#000000';

    $background_color = $content['background_color'] ?? '#ffffff';

    $hero_bg = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';
    $hero_img = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';
@endphp

<style>
    
    /* ==== Marquee animation ==== */
    @keyframes marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .marquee-track {
        display: inline-flex;
        white-space: nowrap;
        animation: marquee 28s linear infinite;
    }
</style>

<section class="w-full relative overflow-hidden py-14 md:py-20 px-6 md:px-20"
    style="background-color:{{ $background_color }}">
    <!-- Clouds -->
    <div class="absolute top-6 left-[8%] w-64 h-24 bg-white/70 rounded-full blur-2xl"></div>
    <div class="absolute bottom-10 right-[6%] w-80 h-28 bg-white/60 rounded-full blur-3xl"></div>
    <div class="absolute top-1/2 left-[45%] w-52 h-20 bg-white/50 rounded-full blur-2xl"></div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-10 items-center relative z-10">
        <!-- Foto lingkaran dengan ring gingham berbunga -->
        <div class="md:col-span-6 flex justify-center relative">
            <div class="w-60 h-60 md:w-80 md:h-80 overflow-hidden">
                <img src="{{ asset($hero_img) }}" alt="" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Teks hero -->
        <div class="md:col-span-6 text-center md:text-left flex flex-col items-center md:items-start">
            <h1 class="font-script text-6xl md:text-8xl font-bold text-[{{ $title_color }}] leading-[0.95] mb-5">
                {{ $title }}
            </h1>
            <p class="text-sm md:text-base text-[{{ $subtitle_color }}] max-w-md mb-8 leading-relaxed">
                {{ $subtitle }}
            </p>
            <div class="relative inline-block">
                <button class="bg-[{{ $button_color }}] hover:bg-[{{ $button_color }}] text-[{{ $button_text_color }}] font-sans-custom font-bold px-9 py-3.5 rounded-lg shadow-md uppercase tracking-[0.15em] text-xs transition-transform transform hover:scale-105">
                    {{ $button_text }}
                </button>
            </div>
        </div>
    </div>
</section>