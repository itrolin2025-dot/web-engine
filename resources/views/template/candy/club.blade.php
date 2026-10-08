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

    $tag = $content['tag_en'] ?? $content['tag'] ?? 'MONTHLY CANDY SUBSCRIPTION';
    $tag_color = $content['tag_color'] ?? '#721C24';

    $title = $content['title_en'] ?? $content['title'] ?? 'Join the Nectar Candy Club';
    $title_color = $content['title_color'] ?? '#3D2314';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '$25/month';
    $subtitle_color = $content['subtitle_color'] ?? '#3D2314';

    $description = $content['description_en'] ?? $content['description'] ?? 'Candy concierge-guided tastings, a mix-your-own candy bag for every guest, and a candy cart that doubles as a conversation piece.';
    $description_color = $content['description_color'] ?? '#5B4636';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? 'JOIN THE CLUB NOW';
    $button_color = $content['button_color'] ?? '#C84B31';
    $button_text_color = $content['button_text_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#B8B4FF';


    $has_background = !empty($content['background']) && $content['background'] !== 'your image';
    $background = $has_background ? 'images/website/' . $domain . '/' . $content['background'] : null;

@endphp

<style>
    .candy-font-serif-custom {
        font-family: 'Instrument Serif', 'Playfair Display', serif;
    }

    .candy-font-sans-custom {
        font-family: 'DM Sans', 'Inter', sans-serif;
    }

    /* Scalloped Box with exact SVG Wavy Edges on all 4 sides */
    .candy-scalloped-box {
        background-color: #FAF6F0;
        position: relative;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.07);
    }

    .candy-wavy-edge-top,
    .candy-wavy-edge-bottom {
        position: absolute;
        left: 0;
        width: 100%;
        height: 18px;
        overflow: hidden;
        line-height: 0;
        pointer-events: none;
        z-index: 10;
    }

    .candy-wavy-edge-top {
        top: -17px;
    }

    .candy-wavy-edge-bottom {
        bottom: -17px;
    }

    .candy-wavy-edge-left,
    .candy-wavy-edge-right {
        position: absolute;
        top: 0;
        height: 100%;
        width: 18px;
        overflow: hidden;
        line-height: 0;
        pointer-events: none;
        z-index: 10;
    }

    .candy-wavy-edge-left {
        left: -17px;
    }

    .candy-wavy-edge-right {
        right: -17px;
    }

    .candy-wavy-edge-top svg,
    .candy-wavy-edge-bottom svg,
    .candy-wavy-edge-left svg,
    .candy-wavy-edge-right svg {
        display: block;
        width: 100%;
        height: 100%;
    }
</style>

@php
    $waveHorizontal = 'M0,0 C30,20 60,20 90,0 C120,20 150,20 180,0 C210,20 240,20 270,0 C300,20 330,20 360,0 C390,20 420,20 450,0 C480,20 510,20 540,0 C570,20 600,20 630,0 C660,20 690,20 720,0 C750,20 780,20 810,0 C840,20 870,20 900,0 C930,20 960,20 990,0 C1020,20 1050,20 1080,0 C1110,20 1140,20 1170,0 C1200,20 1230,20 1260,0 L1200,0 L0,0 Z';
    $waveVerticalLeft = 'M24,0 C4,25 4,50 24,75 C4,100 4,125 24,150 C4,175 4,200 24,225 C4,250 4,275 24,300 C4,325 4,350 24,375 C4,400 4,425 24,450 C4,475 4,500 24,525 C4,550 4,575 24,600 C4,625 4,650 24,675 C4,700 4,725 24,750 C4,775 4,800 24,800 L24,800 L24,0 Z';
    $waveVerticalRight = 'M0,0 C20,25 20,50 0,75 C20,100 20,125 0,150 C20,175 20,200 0,225 C20,250 20,275 0,300 C20,325 20,350 0,375 C20,400 20,425 0,450 C20,475 20,500 0,525 C20,550 20,575 0,600 C20,625 20,650 0,675 C20,700 20,725 0,750 C20,775 20,800 0,800 L0,800 L0,0 Z';
@endphp

<section id="club" class="w-full flex items-center justify-center p-6 md:p-12 selection:bg-pink-200"
    style="background-color: {{ $background_color }}; @if($has_background) background-image: url('{{ asset($background) }}'); background-size: cover; background-position: center; background-repeat: no-repeat; @endif;">

    <!-- Card Tengah dengan Efek Wavy/Scalloped di Seluruh Tepinya -->
    <div class="candy-scalloped-box w-full max-w-4xl mx-auto p-8 md:p-16 text-center relative flex flex-col items-center justify-center my-6">

        <!-- Top Wavy Edge -->
        <div class="candy-wavy-edge-top">
            <svg viewBox="0 0 1200 24" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" class="rotate-180">
                <path d="{{ $waveHorizontal }}" fill="#FAF6F0"></path>
            </svg>
        </div>

        <!-- Bottom Wavy Edge -->
        <div class="candy-wavy-edge-bottom">
            <svg viewBox="0 0 1200 24" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="{{ $waveHorizontal }}" fill="#FAF6F0"></path>
            </svg>
        </div>

        <!-- Left Wavy Edge -->
        <div class="candy-wavy-edge-left">
            <svg viewBox="0 0 24 800" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="{{ $waveVerticalLeft }}" fill="#FAF6F0"></path>
            </svg>
        </div>

        <!-- Right Wavy Edge -->
        <div class="candy-wavy-edge-right">
            <svg viewBox="0 0 24 800" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="{{ $waveVerticalRight }}" fill="#FAF6F0"></path>
            </svg>
        </div>

        <!-- Judul Utama -->
        <h2 class="text-5xl md:text-7xl candy-font-serif-custom tracking-tight leading-none mb-4 relative z-20"
            style="color: {{ $title_color }};">
            {{ $title }}
        </h2>

        <!-- Harga -->
        <p class="text-2xl md:text-3xl candy-font-serif-custom italic mb-6 relative z-20"
            style="color: {{ $subtitle_color }};">
            {{ $subtitle }}
        </p>

        <!-- Deskripsi -->
        <p class="text-sm md:text-base max-w-xl mx-auto leading-relaxed mb-8 relative z-20"
            style="color: {{ $description_color }};">
            {{ $description }}
        </p>

        <!-- Tombol Aksi -->
        <button
            class="font-bold px-8 py-3.5 rounded-full shadow-lg transition-transform transform hover:scale-105 uppercase tracking-wider text-sm relative z-20"
            style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
            {{ $button_text }}
        </button>

        <!-- Stiker Hati Kanan Atas -->
        <div
            class="absolute -top-6 -right-4 md:-top-8 md:-right-6 text-white w-20 h-20 md:w-24 md:h-24 rounded-full flex flex-col items-center justify-center p-2 transform rotate-12 shadow-md border-2 border-white z-30"
            style="background-color: {{ $button_color }};">
            <span class="text-[10px] md:text-xs candy-font-serif-custom italic text-center leading-tight">{{ $tag }}</span>
        </div>

        <!-- Stiker Permen Kiri Tengah -->
        <div
            class="absolute top-1/3 -left-4 md:-left-6 transform -rotate-12 bg-[#FFB6C1] p-3 rounded-xl shadow-md border-2 border-white hidden sm:block z-30">
            <span class="text-2xl">🍬</span>
        </div>

        <!-- Stiker Ceri Kanan Tengah -->
        <div
            class="absolute top-1/2 -right-4 md:-right-6 transform rotate-12 bg-white p-3 rounded-full shadow-md border-2 border-pink-200 hidden sm:block z-30">
            <span class="text-2xl">🍒</span>
        </div>

    </div>
</section>

<script>
    (function () {
        var clubBtn = document.querySelector('#club button');
        if (clubBtn) {
            clubBtn.addEventListener('click', function () {
                if (typeof candyJoinClubAction === 'function') {
                    candyJoinClubAction();
                }
            });
        }
    })();
</script>
