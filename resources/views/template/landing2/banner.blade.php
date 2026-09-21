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

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#000000';
    $button_color = $content['button_color'] ?? '#ffffff';

    $background = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';
@endphp

<section id="contact" class="bg-white py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-1 gap-6">

        <div class="relative rounded-[24px] overflow-hidden bg-cover bg-center min-h-[380px] md:min-h-[440px] flex flex-col justify-between p-8 md:p-12 lg:p-16 shadow-sm"
            style="background-image: url('{{ asset($background) }}');">

            <div class="absolute inset-0 bg-[#b3c5b3] mix-blend-multiply opacity-85 z-0"></div>

            <div class="relative z-10 flex flex-col justify-between h-full space-y-6">

                <div class="space-y-4 md:space-y-6">

                    @if (!empty($tag))
                        <span class="text-xs font-bold uppercase tracking-[0.2em] block" style="color:{{ $tag_color }};">
                            {{ $tag }}
                        </span>
                    @endif

                    @if (!empty($title))
                        <h2
                            class="text-3xl sm:text-4xl lg:text-5xl font-serif-brand leading-[1.25] tracking-wide" style="color:{{ $title_color }};">
                            {{ $title }}
                        </h2>
                    @endif

                    @if (!empty($subtitle))
                        <p class="text-sm sm:text-base max-w-sm font-normal leading-relaxed pt-2" style="color:{{ $subtitle_color }};">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>

                <div class="pt-4">
                    @if (!empty($button_text))
                        <a href="#"
                            class="inline-flex  items-center text-xs font-bold uppercase tracking-[0.15em] hover:opacity-70 transition-opacity gap-2 group" style="color:{{ $button_text_color }};">
                            {{ $button_text }}
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

    </div>
</section>