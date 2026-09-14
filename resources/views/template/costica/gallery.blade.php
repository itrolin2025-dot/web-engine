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

    $repeater = $content['repeater'] ?? $content['tagline'];
    if (is_array($repeater)) {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }
    $tagline_color = $content['tagline_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';
@endphp

<div class="w-full overflow-hidden relative py-8"
    style="background-color:{{ $background_color }};">
    <div class="flex animate-marquee whitespace-nowrap gap-4">

        <!-- Original Set of Images -->
        @for ($i = 0; $i < 6; $i++)
            <div class="flex gap-4 shrink-0">
                @foreach ($repeater as $img)
                    <div class="w-40 h-40 sm:w-48 sm:h-48 md:w-56 md:h-56 rounded-xl overflow-hidden shrink-0">
                        <img src="{{ asset('images/website/' . $domain . '/' . $img['image']) }}"
                            alt="Beauty Care"
                            class="w-full h-full object-cover" />
                    </div>
                @endforeach
            </div>
        @endfor

    </div>
</div>

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
        animation: marquee 25s linear infinite;
    }

    .animate-marquee:hover {
        animation-play-state: paused;
    }
</style>
