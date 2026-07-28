<!-- CTA -->
<style>
    /* ===== CTA SECTION ===== */
    .cta {
        background-image: url('{{ asset('images/website/elska/footer.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        position: relative;
        overflow: hidden;
        padding: 4rem 8%;
    }

    .cta h2 {
        font-size: clamp(2rem, 4vw, 3rem);
        color: white;
        margin-bottom: 1rem;
        text-align: left;
        padding: 0;
        margin-top: 0;
        font-family: 'Inter', sans-serif;
    }

    .cta p {
        color: rgba(255, 255, 255, 0.9);
        max-width: 500px;
        text-align: left;
        padding: 0;
        margin-top: 1rem;
    }

    .cta-products {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 3rem 0;
        flex-wrap: wrap;
    }

    .cta-products img {
        width: 150px;
        height: 200px;
        object-fit: contain;
        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
    }

    @media (max-width: 576px) {
        .cta-products img {
            width: 100px;
            height: 140px;
        }
    }
</style>
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

@foreach ($banners as $banner)
    @php
        $background = !empty($banner['background'])
            ? asset('images/website/' . $domain . '/' . $banner['background'])
            : asset('images/default/broken.png');
        $btnText = $banner['button_text_en'] ?? $banner['button_text'] ?? $button_text ?? '';
        $btnColor = $banner['button_color'] ?? $button_color ?? '#ffffff';
        $btnTextColor = $banner['button_text_color'] ?? $button_text_color ?? '#FF9B7A';
        $bannerTitle = $banner['title_en'] ?? $banner['title'] ?? '';
        $bannerSub = $banner['subtitle_en'] ?? $banner['subtitle'] ?? $banner['desc_en'] ?? $banner['desc'] ?? '';
    @endphp
    <section class="cta" style="background-image: url('{{ $background }}');">
        @if(!empty($bannerTitle))
            <h2 class="line-clamp-2 max-w-[600px] leading-tight" style="color: {{ $banner['title_color'] ?? $title_color }};">
                {!! nl2br(e($bannerTitle)) !!}
            </h2>
        @endif

        @if(!empty($bannerSub))
            <p style="color: {{ $banner['subtitle_color'] ?? $subtitle_color }};">
                {{ $bannerSub }}
            </p>
        @endif

        @if(!empty($btnText))
            <div style="margin-top: 2.5rem;">
                <a href="{{ $banner['button_url'] ?? '#' }}" class="btn-primary"
                    style="background-color: {{ $btnColor }}; color: {{ $btnTextColor }};">
                    {{ $btnText }}
                </a>
            </div>
        @endif
    </section>
@endforeach