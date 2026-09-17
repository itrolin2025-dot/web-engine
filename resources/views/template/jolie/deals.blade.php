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

    $products = $products ?? collect();

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#000000';
    $button_color = $content['button_color'] ?? '#000000';
    $background_color = $content['background_color'] ?? '#ffffff';


    $background_image = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';

@endphp

<section class="relative w-full overflow-hidden bg-[ {{ $background_color }} ] py-20 lg:py-28 font-sans text-[#2d3748]">

    <!-- Background Split Images ( Left & Right Models ) -->
    <div class="absolute inset-0 grid grid-cols-1 md:grid-cols-1 w-full h-full">
        <!-- Left Image -->
        <div class="relative w-full h-full hidden md:block">
            <img src="{{ $background_image }}"
                alt="Model Left" class="w-full h-full object-cover object-center" />
        </div>
        <!-- Right Image -->
        <!-- <div class="relative w-full h-full hidden md:block">
            <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=1200&q=80"
                alt="Model Right" class="w-full h-full object-cover object-top" />
        </div> -->
        <!-- Mobile Background (Single Image) -->
        <div class="relative w-full h-full block md:hidden">
            <img src="{{ $background_image }}"
                alt="Model Mobile" class="w-full h-full object-cover object-top opacity-30" />
        </div>
    </div>

    <!-- Center Card Container ( Glassmorphism Overlay ) -->
    <div class="relative z-10 max-w-xl mx-auto px-6">
        <div
            class="bg-white/90 backdrop-blur-md rounded-2xl p-8 md:p-12 text-center shadow-lg border border-white/40">

            <!-- Subtitle -->
            <p class="text-[10px] md:text-xs font-bold tracking-[0.2em] text-[{{ $tag_color }}] uppercase mb-3">
                {{ $tag }}
            </p>

            <!-- Main Heading -->
            <h2 class="text-3xl md:text-4xl font-extrabold text-[{{ $title_color }}] mb-3">
                {{ $title }}
            </h2>

            <!-- Description -->
            <p class="text-xs md:text-sm text-[{{ $subtitle_color }}] mb-6 leading-relaxed max-w-md mx-auto">
                {{ $subtitle }}
            </p>

            <!-- Countdown Timer -->
            <!-- <div class="flex items-center justify-center gap-3 text-center mb-8">
                <div class="flex items-baseline gap-1">
                    <span id="days" class="text-lg md:text-xl font-bold text-black">00</span>
                    <span class="text-[10px] md:text-xs text-gray-500 font-medium">Days</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span id="hours" class="text-lg md:text-xl font-bold text-black">09</span>
                    <span class="text-[10px] md:text-xs text-gray-500 font-medium">Hrs</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span id="minutes" class="text-lg md:text-xl font-bold text-black">32</span>
                    <span class="text-[10px] md:text-xs text-gray-500 font-medium">Mins</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <span id="seconds" class="text-lg md:text-xl font-bold text-black">32</span>
                    <span class="text-[10px] md:text-xs text-gray-500 font-medium">Secs</span>
                </div>
            </div> -->

            <!-- CTA Button -->
            <a href="#"
                class="inline-block text-white text-xs md:text-sm font-semibold px-8 py-3.5 rounded-full shadow-md hover:scale-105 transition duration-300" style="background-color: {{ $button_color }}; color: {{ $button_text_color }}">
                {{ $button_text }}
            </a>

        </div>
    </div>

</section>