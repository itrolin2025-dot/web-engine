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

    $desc = $content['description'] ?? '';
    $desc_color = $content['description_color'] ?? '#000000';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#ffffffff';
    $button_color = $content['button_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#ffffff';

    $background = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';
    $image      = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';
@endphp

<section class="relative w-full grid grid-cols-1 md:grid-cols-2 z-10">
    <!-- Left: swirl pattern + copy (mobile: baris kedua) -->
    <div class="order-2 md:order-1 relative bg-[{{ $background_color }}] px-6 md:px-16 pt-12 pb-14 md:pt-14 md:pb-16 overflow-hidden">
        <!-- Swirl background (mobile: thinned + fully framed so the wavy lines are not cut off) -->
        <svg class="absolute inset-0 w-full h-full opacity-60 md:hidden" viewBox="0 0 600 640" preserveAspectRatio="xMidYMid meet" fill="none" aria-hidden="true">
            <path d="M60,150 C180,100 260,220 170,285 C90,340 170,430 290,400" stroke="#F3DFA6" stroke-width="26" stroke-linecap="round"/>
            <path d="M540,110 C430,140 450,240 545,280" stroke="#E2D0EE" stroke-width="24" stroke-linecap="round"/>
            <path d="M60,470 C170,435 240,510 190,565" stroke="#E2D0EE" stroke-width="22" stroke-linecap="round"/>
            <path d="M420,560 C455,495 535,510 560,575" stroke="#F3DFA6" stroke-width="24" stroke-linecap="round"/>
        </svg>
        <!-- Swirl background (desktop, unchanged) -->
        <svg class="absolute inset-0 w-full h-full opacity-70 hidden md:block" viewBox="0 0 600 640" preserveAspectRatio="xMidYMid slice" fill="none" aria-hidden="true">
            <path d="M-60,140 C110,60 230,230 90,300 C-30,360 90,490 250,440" stroke="#F3DFA6" stroke-width="48" stroke-linecap="round"/>
            <path d="M660,70 C480,110 520,260 670,310" stroke="#E2D0EE" stroke-width="46" stroke-linecap="round"/>
            <path d="M-70,540 C100,490 200,600 130,680" stroke="#E2D0EE" stroke-width="40" stroke-linecap="round"/>
            <path d="M420,640 C470,540 600,560 640,660" stroke="#F3DFA6" stroke-width="42" stroke-linecap="round"/>
        </svg>

        <div class="relative z-10 max-w-md mx-auto md:mx-0 md:ml-10 text-center md:text-left">
            <h2 class="font-script text-4xl sm:text-5xl md:text-6xl text-[{{ $title_color }}] mb-5 md:mb-7">{{ $title }}</h2>
            <p class="text-[10px] sm:text-[11px] md:text-xs font-bold tracking-[0.2em] sm:tracking-[0.25em] text-[{{ $subtitle_color }}] uppercase leading-loose mb-5 md:mb-6">
                {{ $subtitle }}
            </p>
            <p class="text-[13px] sm:text-sm text-[{{ $desc_color }}] leading-relaxed mb-8 md:mb-9">
                {{ $desc }}
            </p>
            <button class="bg-[{{ $button_color }}] hover:bg-[#E9C77E] transition-colors text-[{{ $button_text_color }}] text-[10px] md:text-[11px] font-extrabold tracking-[0.2em] uppercase rounded-[26px] px-12 py-6 shadow-md leading-relaxed">
                {{ $button_text }}
            </button>
        </div>
    </div>

    <!-- Right: full-bleed photo (mobile: baris pertama, di atas teks) -->
    <div class="order-1 md:order-2 relative h-[240px] sm:h-[300px] md:h-auto md:min-h-[560px]">
        <img src="{{ asset($image) }}" alt="{{ $title }}" class="absolute inset-0 w-full h-full object-cover">
    </div>
</section>