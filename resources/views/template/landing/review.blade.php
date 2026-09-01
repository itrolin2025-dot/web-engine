<!-- REVIEWS -->
<style>
    /* ===== REVIEWS ===== */
    .reviews {
        background: var(--light-gray);
        padding: 5rem 1rem;
    }

    .reviews h2 {
        text-align: center;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        margin-bottom: 3rem;
    }

    .reviews-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .review-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .review-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .review-meta h4 {
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 600;
    }

    .stars {
        color: #FBBF24;
        font-size: 0.9rem;
        letter-spacing: 2px;
    }

    .review-text {
        color: var(--gray);
        font-size: 0.9rem;
        line-height: 1.7;
    }

    .verified {
        color: #10B981;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.5rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .reviews-grid {
            grid-template-columns: 1fr;
        }
        .reviews-center .review-card {
            width: 100%;
            max-width: 100%;
        }
    }
    
    /* Centered Layout */
    .reviews-center {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }
    .reviews-center .review-card {
        width: calc(50% - 1rem);
        max-width: 400px;
    }
    
    /* Slider Layout */
    .reviews-slider-wrapper {
        width: 100%;
        overflow: hidden;
    }
    .reviews-slider {
        display: flex;
        gap: 2rem;
        width: max-content;
        animation: scrollReviews 20s linear infinite;
    }
    .reviews-slider:hover {
        animation-play-state: paused;
    }
    .reviews-slider .review-card {
        width: 350px;
        flex-shrink: 0;
    }
    
    @keyframes scrollReviews {
        0% { transform: translateX(0); }
        100% { transform: translateX(calc(-50% - 1rem)); }
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

    $repeater = $content['repeater'] ?? $content['tagline'];
    if (is_array($repeater)) {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';

    // Count reviews for layout logic
    $count = count($repeater);
    if ($count >= 4) {
        // Duplicate array for infinite scroll
        $displayRepeater = array_merge($repeater, $repeater);
        $wrapperClass = 'reviews-slider';
    } elseif ($count <= 2) {
        $displayRepeater = $repeater;
        $wrapperClass = 'reviews-center';
    } else {
        $displayRepeater = $repeater;
        $wrapperClass = 'reviews-grid';
    }
@endphp

<section class="reviews">
    <h2>Customer Reviews</h2>
    <div class="{{ $count >= 4 ? 'reviews-slider-wrapper' : '' }}">
        <div class="{{ $wrapperClass }}">
            @foreach ($displayRepeater as $review)
                @php
                    $avatar = !empty($review['image'])
                        ? asset('images/website/' . $domain . '/' . $review['image'])
                        : asset('images/default/broken.png');
                @endphp
                <div class="review-card">
                    <div class="review-header">
                        <img src="{{ $avatar }}" alt="Avatar" class="review-avatar">
                        <div class="review-meta">
                            <h4></h4>
                            <div class="stars">
                                @php $starCount = (int) ($review['star'] ?? 5); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $starCount)
                                        <span style="color: #FBBF24;">&#9733;</span>
                                    @else
                                        <span style="color: #D1D5DB;">&#9734;</span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>
                    <p class="review-text">"{{ $review['label'] }}"</p><br>
                    <!-- <div class="verified">&#10003; Verified Purchase</div> -->
                </div>
            @endforeach
        </div>
    </div>
</section>