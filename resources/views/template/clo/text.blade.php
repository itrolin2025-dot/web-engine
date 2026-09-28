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


<style>
    /* ==== Marquee animation ==== */
    @keyframes marquee {
        0% {
            transform: translateX(0%);
        }
        100% {
            transform: translateX(-50%);
        }
    }
    .marquee-track {
        display: inline-flex;
        white-space: nowrap;
        animation: marquee 60s linear infinite;
    }
    .marquee-track:hover {
        animation-play-state: paused;
    }
</style>

<div class="w-full overflow-hidden" style="background-color: {{ $background_color ?? '#C74A3C' }}; color: #ffffff;">
    <div class="marquee-track py-2.5 text-[11px] font-sans-custom font-bold tracking-[0.25em] uppercase">
        @for ($i = 0; $i < 16; $i++)
            <div class="flex items-center gap-6 text-[11px] md:text-xs font-semibold tracking-wider uppercase px-6 shrink-0"
                aria-hidden="true">
                <span style="color: {{ $title_color }};">{{ $title }}</span>
                @if(!empty($subtitle))
                    <span style="color: {{ $subtitle_color }};">{{ $subtitle }}</span>
                @endif
            </div>
        @endfor
    </div>
</div>