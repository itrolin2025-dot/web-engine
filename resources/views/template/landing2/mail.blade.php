@php
    $rawContent = $layout->content ?? '';

    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
        // Strip non-standard whitespace/control characters (like raw tabs \t) that break json_decode
        $cleanJson = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $rawContent);
        // Remove trailing commas before closing brackets/braces (e.g. , ] or , })
        $cleanJson = preg_replace('/,\s*([\]}])/', '$1', $cleanJson);
        $content = json_decode($cleanJson, true) ?? json_decode($rawContent, true) ?? [];
    } else {
        $content = [];
    }

    $domain = $website->domain ?? '';

    $title = $content['title_en'] ?? $content['title'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $desc = $content['desc_en'] ?? $content['desc'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';

@endphp

<section>
    @php
        $image = !empty($content['image'])
            ? asset('images/website/' . $domain . '/' . $content['image'])
            : asset('images/default/broken.png');
    @endphp
    <div class="max-w-7xl mx-auto bg-[#FBFBFA] rounded-[32px] p-6 md:p-12 lg:p-16 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-16 items-center">

            <div class="lg:col-span-5 w-full">
                <div class="aspect-[1.5/1] w-full rounded-[24px] overflow-hidden shadow-sm bg-stone-200">
                    <img src="{{ $image }}" alt="Cosmetics Presentation" class="w-full h-full object-cover">
                </div>
            </div>

            <div class="lg:col-span-7 space-y-6 flex flex-col justify-center">

                <div class="space-y-3">
                    <h2 class="text-4xl lg:text-5xl font-serif-brand text-[#2C2A29] tracking-normal font-bold">
                        {{ $title }}
                    </h2>
                    <p class="text-stone-500 text-sm md:text-base leading-relaxed max-w-xl font-normal">
                        {{ $subtitle }}
                    </p>
                </div>

                <form class="w-full max-w-xl">
                    <div
                        class="flex items-center border border-[#4A5D4E] rounded-full p-1.5 pl-5 bg-white shadow-sm focus-within:ring-1 focus-within:ring-[#4A5D4E] transition-all">

                        <div class="text-[#4A5D4E] mr-3 flex items-center justify-center text-lg">
                            <i class="fa-regular fa-envelope"></i>
                        </div>

                        <input type="email" placeholder="{{ $desc }}" required
                            class="w-full bg-transparent outline-none text-sm text-stone-700 placeholder-[#7A8A7A]">

                        <button type="submit"
                            class="bg-[#141414] text-white px-8 py-3 rounded-full text-sm font-medium tracking-wide hover:bg-stone-800 transition-colors whitespace-nowrap">
                            {{ $button_text }}
                        </button>

                    </div>
                </form>

            </div>

        </div>
    </div>
</section>