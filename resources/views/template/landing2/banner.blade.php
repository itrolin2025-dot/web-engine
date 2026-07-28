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

    $banners = $content['banners'] ?? [];

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';

@endphp

<section class="bg-white py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">

        @foreach ($banners as $banner)
            @php
                $background = !empty($banner['background'])
                    ? asset('images/website/' . $domain . '/' . $banner['background'])
                    : asset('images/default/broken.png');
            @endphp

            <div class="relative rounded-[24px] overflow-hidden bg-cover bg-center min-h-[380px] md:min-h-[440px] flex flex-col justify-between p-8 md:p-12 lg:p-16 shadow-sm"
                style="background-image: url('{{ $background }}');">

                <div class="absolute inset-0 bg-[#b3c5b3] mix-blend-multiply opacity-85 z-0"></div>

                <div class="relative z-10 flex flex-col justify-between h-full space-y-6">

                    <div class="space-y-4 md:space-y-6">

                        @if (!empty($banner['tag']))
                            <span class="text-xs font-bold uppercase tracking-[0.2em] text-stone-800 block">
                                {{ $banner['tag'] }}
                            </span>
                        @endif

                        @if (!empty($banner['title']))
                            <h2
                                class="text-3xl sm:text-4xl lg:text-5xl font-serif-brand text-stone-900 leading-[1.25] tracking-wide">
                                {{ $banner['title'] }}
                            </h2>
                        @endif

                        @if (!empty($banner['desc']))
                            <p class="text-stone-700 text-sm sm:text-base max-w-sm font-normal leading-relaxed pt-2">
                                {{ $banner['desc'] }}
                            </p>
                        @endif
                    </div>

                    @if (!empty($banner['tagline']))
                        <div class="grid grid-cols-2 gap-6 pt-2">
                            <div class="flex flex-col items-center space-y-3">
                                <div
                                    class="text-stone-800 text-3xl h-10 flex items-center justify-center font-light select-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-8 h-8 opacity-80">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
                                    </svg>
                                </div>
                                <p
                                    class="text-[13px] md:text-sm font-medium tracking-wide text-stone-800 leading-tight text-center">
                                    Consistent Quality<br>Standards
                                </p>
                            </div>

                            <div class="flex flex-col items-center space-y-3">
                                <div class="text-stone-800 text-3xl h-10 flex items-center justify-center opacity-80">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" class="w-7 h-7">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 3v18m0-18c-3.5 0-6.75 3-6.75 7.5S8.5 21 12 21m0-18c3.5 0 6.75 3 6.75 7.5S15.5 21 12 21" />
                                    </svg>
                                </div>
                                <p
                                    class="text-[13px] md:text-sm font-medium tracking-wide text-stone-800 leading-tight text-center">
                                    Consistent Quality<br>Standards
                                </p>
                            </div>
                        </div>
                    @endif

                    <div class="pt-4">
                        @if (!empty($banner['button_text']))
                            <a href="#"
                                class="inline-flex items-center text-xs font-bold uppercase tracking-[0.15em] text-stone-950 hover:opacity-70 transition-opacity gap-2 group">
                                {{ $banner['button_text'] }}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor"
                                    class="w-3.5 h-3.5 transform group-hover:translate-x-1 transition-transform">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach

    </div>
</section>