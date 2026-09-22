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


    $image = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : 'images/default/broken.png';

@endphp

<section class="w-full bg-[{{ $background_color }}] font-sans text-[#1a1a1a] overflow-hidden">

    <!-- Top Hero Banner: 2 Columns Split Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 min-h-[480px] lg:min-h-[560px]">

        <!-- Left Image Column -->
        <div class="relative w-full h-full bg-[#eee]">
            <img src="{{ asset($image) }}"
                alt="Summer Collection Model" class="w-full h-full object-cover object-center" />
        </div>

        <!-- Right Content Column -->
        <div class="flex flex-col justify-center items-start p-8 md:p-16 lg:p-24 bg-[{{ $background_color }}]">
            <span class="text-xs md:text-sm font-medium mb-3" style="color: {{ $tag_color }}">
                {{ $tag }}
            </span>

            <h1
                class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight leading-[1.15] mb-4 max-w-md" style="color: {{ $title_color }}">
                {{ $title }}
            </h1>

            <p class="text-xs md:text-sm mb-8" style="color: {{ $subtitle_color }}">
                {{ $subtitle }}
            </p>

            <a href="#"
                class="inline-block text-white text-xs md:text-sm font-semibold px-8 py-3.5 rounded-full hover:bg-black hover:scale-105 transition duration-300" style="background-color: {{ $button_color }}; color: {{ $button_text_color }}">
                {{ $button_text }}
            </a>
        </div>

    </div>

</section>

