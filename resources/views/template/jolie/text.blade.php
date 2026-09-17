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

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';
@endphp

<section class="w-full bg-[{{ $background_color }}] font-sans text-[#1a1a1a] overflow-hidden">

    <!-- Bottom Running Text (Ticker / Marquee Bar) -->
    <div class="w-full bg-[{{ $background_color }}] border-t border-b border-gray-100 py-4 overflow-hidden relative">
        <div class="flex whitespace-nowrap animate-marquee">

            @for ($i = 0; $i < 20; $i++)
                <div class="flex items-center gap-12 text-[11px] md:text-xs font-semibold tracking-wider uppercase px-6"
                    aria-hidden="true">
                    <span class="text-[{{ $title_color }}]">{{ $title }}</span>
                    <span class="text-[{{ $subtitle_color }}]">{{ $subtitle }}</span>
                </div>
            @endfor

        </div>
    </div>

</section>


<style>
    @keyframes marquee {
        0% {
            transform: translateX(0%);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .animate-marquee {
        display: flex;
        width: 200%;
        animation: marquee 25s linear infinite;
    }

    .animate-marquee:hover {
        animation-play-state: paused;
        /* Berhenti sejenak saat kursor di-hover */
    }
</style>