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

    // Jumlah kartu menentukan jumlah kolom. Kalau hanya 1-2 kartu, kolomnya
    // ikut dikecilkan supaya kartu tetap ter-center dan tidak ada ruang kosong
    // di sisi kanan (grid 3 kolom dengan 2 kartu terlihat jauh ke kiri).
    $cardCount = count($repeater);
    $cardCols = match (true) {
        $cardCount <= 1 => 'grid-cols-1',
        $cardCount === 2 => 'grid-cols-1 sm:grid-cols-2',
        $cardCount === 3 => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3',
        default        => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
    // Batas lebar kartu: 2 kartu tidak boleh selebar grid 3 kolom agar
    // tidak terlalu renggang di layar besar.
    $cardWidth = $cardCount === 2 ? 'max-w-3xl' : 'max-w-5xl';
    $tagline_color = $content['tagline_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';
@endphp

<section class="w-full relative" style="background-color:#C74A3C">
    <!-- Torn paper top edge (image overlay) -->
    <img src="/images/paper/torn-white-top.png" alt="" aria-hidden="true"
            class="absolute top-0 left-0 w-full pointer-events-none select-none">

    <div class="pink-grid-bg relative pt-28 pb-28 px-6">
        <!-- Dekorasi pensil -->
        <svg class="hidden md:block absolute top-8 right-[10%] w-56 rotate-[135deg] drop-shadow-lg z-30" viewBox="0 0 220 40" aria-hidden="true">
            <rect x="0" y="12" width="14" height="16" rx="4" fill="#E8A0A8"/>
            <rect x="12" y="11" width="8" height="18" fill="#9DB4CE"/>
            <rect x="20" y="10" width="150" height="20" fill="#5F7036"/>
            <rect x="20" y="17" width="150" height="6" fill="#4A5A28"/>
            <polygon points="170,10 202,20 170,30" fill="#E8C99B"/>
            <polygon points="197,17.5 207,20 197,22.5" fill="#3D2314"/>
        </svg>

        <div class="max-w-5xl mx-auto text-center mt-16 mb-8 relative z-10">
            <h2 class="font-script text-6xl md:text-7xl font-bold text-white">{{ $title }}</h2>
            <svg class="mx-auto mt-1 w-40" viewBox="0 0 160 12" fill="none" aria-hidden="true">
                <path d="M2,8 Q12,2 22,8 T42,8 T62,8 T82,8 T102,8 T122,8 T142,8 T158,6" stroke="white" stroke-width="3" stroke-linecap="round"/>
            </svg>
        </div>

        <!-- Feature cards -->
        <div class="{{ $cardWidth }} mx-auto pt-16 pb-16 grid {{ $cardCols }} gap-8 relative z-10 mb-12">
            
            @foreach ($repeater as $img)
                <div class="bg-white rounded-[22px] px-6 pt-8 pb-8 shadow-xl flex flex-col items-center text-center">
                    <div class="flower-mask w-44 h-44 mb-6 bg-[#EDE4D6]">
                        <img src="{{ asset('images/website/' . $domain . '/' . $img['image']) }}" 
                        alt="{{ $img['title'] }}" class="w-full h-full object-cover">
                    </div>
                    <div class="bg-[#F4A9B8] border-2 border-[#C13A3A] text-[#C13A3A] px-7 py-1.5 rounded-md font-sans-custom font-bold text-xs tracking-[0.2em] uppercase mb-6">
                        {{ $img['title'] }}
                    </div>
                    <ul class="text-[11px] font-sans-custom font-semibold text-[#9E3A2B] space-y-2.5 text-left tracking-[0.12em] uppercase">
                        {{ $img['subtitle'] }}
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
    <img src="/images/paper/torn-white-bottom.png" alt="" aria-hidden="true"
            class="absolute bottom-0 left-0 w-full pointer-events-none select-none">

    <div class="h-[120px] absolute bottom-0 left-0 w-full pointer-events-none select-none" aria-hidden="true"></div>

    <!-- Dekorasi penggaris biru -->
    <div class="hidden md:block absolute -bottom-6 left-4 lg:left-16 z-30 rotate-[-12deg]">
        <div class="w-[430px] h-14 bg-[#BFD6EC] border-2 border-white rounded-md shadow-lg relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-4" style="background: repeating-linear-gradient(90deg, #3E6491 0 2px, transparent 2px 26px);"></div>
            <div class="absolute inset-0 flex items-end justify-between px-5 pb-1.5 font-sans-custom font-bold text-[#3E6491] text-sm">
                <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span><span>6</span><span>7</span><span>8</span><span>9</span>
            </div>
        </div>
    </div>
</section>