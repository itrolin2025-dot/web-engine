<!-- ABOUT -->
<style>
    /* ===== ABOUT SECTION ===== */
    .about {
        padding: 6rem 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .about-image {
        background: linear-gradient(135deg, var(--sky) 0%, var(--lavender) 100%);
        border-radius: 24px;
        overflow: hidden;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .about-image img {
        width: 80%;
        height: 80%;
        object-fit: contain;
    }

    .about-content h2 {
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        margin-bottom: 1.5rem;
        color: var(--dark);
    }

    .about-content p {
        color: var(--gray);
        margin-bottom: 2rem;
        line-height: 1.8;
    }

    .features {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--cream);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .feature-text {
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .about-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
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

    $tagline = $content['tagline'] ?? [];
    if (is_array($tagline)) {
        $tagline = collect($tagline)->sortBy('sort')->values()->all();
    }
    $tagline_color = $content['tagline_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';
@endphp

<section class="about">
    <div class="about-grid">
        <div class="about-image" style="
                    width: 100%;
                    min-height: 500px;
                    background-image: url('{{ asset($about_image) }}');
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: contain;
                ">
            <!-- <img src="{{ asset('images/website/elska/Artboard 18 1.png') }}" alt="Signature Fragrance" style="width: 100%;"
                    text-anchor='middle' font-family='sans-serif'> -->
        </div>
        <div class="about-content">
            <h2 style="color: {{ $title_color }};">{{ $title }}</h2>
            <p style="color: {{ $desc_color }};">{{ $desc }}</p>
            <div class="features">
                @foreach ($tagline as $tag)
                    <div class="feature-item">
                        <div class="feature-icon">&#10003;</div>
                        <span class="feature-text" style="color: {{ $tag['color'] }};">{{ $tag['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>