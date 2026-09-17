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

    $background_color = $content['background_color'] ?? '#fffffff';

    $title = $content['title_en'] ?? $content['title'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    $categories = $categories->take(4) ?? collect();
@endphp

<section class="py-16 px-4 max-w-7xl mx-auto bg-white">
    <!-- Section Header -->
    <div class="text-center mb-10">
        <h2 class="text-2xl md:text-3xl font-bold text-[#1a1a1a] tracking-tight mb-2">
            {{ $title }}
        </h2>
        <p class="text-xs md:text-sm text-gray-500 font-normal">
            {{ $subtitle }}
        </p>
    </div>

    <!-- Collection Grid Container -->
    <div class="flex flex-wrap justify-center gap-6">
        @if(isset($categories) && count($categories) > 0)
            @foreach ($categories as $category)
                @php
                    $category = (array) $category;
                    $image = !empty($category['image'])
                        ? asset('storage/' . $category['image'])
                        : asset('images/default/broken.png');
                @endphp
                <a href="#"
                    class="group relative rounded-2xl overflow-hidden bg-[#dce5ed] aspect-[3/4] w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)] max-w-[320px] flex flex-col justify-end items-center p-6 transition duration-300 transform hover:-translate-y-1">
                    <img src="{{ $image }}"
                        alt="{{ $category['name'] }}"
                        class="absolute inset-0 w-full h-full object-cover object-center transition duration-500 group-hover:scale-105" />
                    <div
                        class="relative z-10 w-full max-w-[200px] bg-white/95 backdrop-blur-sm text-[#1a1a1a] font-medium text-xs md:text-sm py-3 px-6 rounded-full text-center shadow-sm group-hover:bg-black group-hover:text-white transition duration-300">
                        {{ $category['name'] }}
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</section>