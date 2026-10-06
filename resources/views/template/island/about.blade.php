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

<section class="relative w-full min-h-screen grid grid-cols-1 md:grid-cols-2 z-10">
    <!-- Left: swirl pattern + copy (mobile: baris kedua) -->
    <div class="order-1 md:order-1 relative overflow-hidden flex flex-col items-center justify-center min-h-[480px] md:min-h-screen px-6 py-14 md:px-16 md:py-20"
         style="background-color: {{ $background_color }}; {{ $background ? 'background-image: url(' . asset($background) . '); background-size: cover; background-position: center;' : '' }}">
        <!-- Swirl background (mobile) -->
        <svg class="absolute inset-0 w-full h-full opacity-60 md:hidden" viewBox="0 0 600 640" preserveAspectRatio="xMidYMid meet" fill="none" aria-hidden="true">
            <path d="M60,150 C180,100 260,220 170,285 C90,340 170,430 290,400" stroke="#F3DFA6" stroke-width="26" stroke-linecap="round"/>
            <path d="M540,110 C430,140 450,240 545,280" stroke="#E2D0EE" stroke-width="24" stroke-linecap="round"/>
            <path d="M60,470 C170,435 240,510 190,565" stroke="#E2D0EE" stroke-width="22" stroke-linecap="round"/>
            <path d="M420,560 C455,495 535,510 560,575" stroke="#F3DFA6" stroke-width="24" stroke-linecap="round"/>
        </svg>
        <!-- Swirl background (desktop) -->
        <svg class="absolute inset-0 w-full h-full opacity-70 hidden md:block" viewBox="0 0 600 640" preserveAspectRatio="xMidYMid slice" fill="none" aria-hidden="true">
            <path d="M-60,140 C110,60 230,230 90,300 C-30,360 90,490 250,440" stroke="#F3DFA6" stroke-width="48" stroke-linecap="round"/>
            <path d="M660,70 C480,110 520,260 670,310" stroke="#E2D0EE" stroke-width="46" stroke-linecap="round"/>
            <path d="M-70,540 C100,490 200,600 130,680" stroke="#E2D0EE" stroke-width="40" stroke-linecap="round"/>
            <path d="M420,640 C470,540 600,560 640,660" stroke="#F3DFA6" stroke-width="42" stroke-linecap="round"/>
        </svg>

        <div class="relative z-10 w-full max-w-sm mx-auto flex flex-col items-center text-center gap-5">
            <h2 class="font-script text-4xl sm:text-5xl md:text-6xl font-bold leading-tight"
                style="color: {{ $title_color }};">
                {{ $title }}
            </h2>
            @if($subtitle)
                <p class="text-[11px] sm:text-xs font-bold tracking-[0.22em] uppercase leading-loose"
                   style="color: {{ $subtitle_color }};">
                    {{ $subtitle }}
                </p>
            @endif
            @if($desc)
                <p class="text-sm sm:text-base leading-relaxed"
                   style="color: {{ $desc_color }};">
                    {{ $desc }}
                </p>
            @endif
            @if($button_text)
                <button class="mt-2 bg-[{{ $button_color }}] hover:opacity-90 transition-opacity text-[{{ $button_text_color }}] text-[10px] md:text-[11px] font-extrabold tracking-[0.2em] uppercase rounded-[26px] px-10 py-5 shadow-md leading-relaxed">
                    {{ $button_text }}
                </button>
            @endif
        </div>
    </div>

    <!-- Right: full-bleed photo (mobile: baris pertama, di atas teks) -->
    <div class="order-2 md:order-2 relative">
        {{-- Mobile: natural aspect ratio, no crop --}}
        <img src="{{ asset($image) }}" alt="{{ $title }}"
             class="block md:hidden w-full h-auto object-contain">
        {{-- Desktop: full-bleed cover --}}
        <div class="hidden md:block absolute inset-0">
            <img src="{{ asset($image) }}" alt="{{ $title }}"
                 class="w-full h-full object-cover">
        </div>
    </div>
</section>