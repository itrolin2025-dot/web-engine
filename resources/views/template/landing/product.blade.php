<!-- COLLECTIONS -->
<style>
    /* ===== COLLECTIONS ===== */
    .collections {
        padding: 5rem 1rem;
        text-align: center;
        background: white;
        margin-bottom: -5rem;
    }

    .collections h2 {
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        margin-bottom: 2rem;
    }

    .collection-tabs {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .tab-btn {
        padding: 0.75rem 2rem;
        border: 2px solid var(--light-gray);
        background: white;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--gray);
    }

    .tab-btn.active,
    .tab-btn:hover {
        border-color: var(--coral);
        color: var(--coral-dark);
        background: var(--cream);
    }

    /* ===== PRODUCTS ===== */
    .products {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem 5rem;
    }

    .product-card {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
        padding: 2rem;
    }

    .product-card.hidden {
        display: none !important;
    }

    .product-card:nth-child(even) {
        direction: rtl;
    }

    .product-card:nth-child(even)>* {
        direction: ltr;
    }

    .product-card:nth-child(even) .product-info {
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .product-image {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .product-image .shape {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        height: 80%;
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        z-index: 0;
    }

    .product-card:nth-child(1) .shape {
        background: var(--peach);
    }

    .product-card:nth-child(2) .shape {
        background: var(--sky);
    }

    .product-card:nth-child(3) .shape {
        background: var(--pink);
    }

    .product-card:nth-child(4) .shape {
        background: var(--lavender);
    }

    .product-card:nth-child(5) .shape {
        background: var(--mint);
    }

    .product-image img {
        width: 70%;
        height: auto;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.1));
    }

    .product-info h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .product-tag {
        color: var(--coral-dark);
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 1rem;
        display: block;
    }

    .product-notes {
        color: var(--gray);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        line-height: 1.8;
    }

    .product-notes strong {
        color: var(--dark);
        display: block;
        margin-bottom: 0.25rem;
    }

    .btn-outline {
        border: 2px solid var(--coral);
        color: var(--coral-dark);
        padding: 0.75rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-block;
        background: transparent;
    }

    .btn-outline:hover {
        background: var(--coral);
        color: white;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .product-card {
            grid-template-columns: 1fr;
            gap: 2rem;
            text-align: center;
        }

        .product-card:nth-child(even) {
            direction: ltr;
        }

        .product-card:nth-child(even) .product-info {
            text-align: center;
            align-items: center;
        }
    }

    @media (max-width: 576px) {
        .features {
            justify-content: center;
        }

        .collection-tabs {
            gap: 0.5rem;
        }

        .tab-btn {
            padding: 0.6rem 1.2rem;
            font-size: 0.8rem;
        }

        .product-card {
            padding: 1rem;
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

    $title = $content['title'] ?? $content['title_en'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $desc = $content['desc_en'] ?? $content['desc'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $categories = $content['categories'] ?? [];
    $products = $content['products'] ?? [];

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';

@endphp

<section class="collections" id="products">
    <h2>{{ $title }}</h2>
    <div class="collection-tabs">
        <button class="tab-btn active" data-filter="all">All Collection</button>
        @foreach ($categories as $category)
            <button class="tab-btn" data-filter="{{ $category['kode'] }}">{{ $category['name'] }}</button>
        @endforeach

    </div>
</section>

<!-- PRODUCTS -->
<section class="products">
    @foreach ($products as $product)
        @php
            $image = !empty($product['image'])
                ? asset('images/website/' . $domain . '/' . $product['image'])
                : asset('images/default/broken.png');
        @endphp
        <div class="product-card" data-category="{{ $product['kode'] }}">
            <div class="product-image"
                style="width: 100%; min-height: 400px; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9; border-radius: 12px; overflow: hidden;">
                <img src="{{ $image }}" alt="{{ $product['name'] }}"
                    style="max-width: 100%; max-height: 450px; object-fit: contain; display: block;"
                    onerror="this.onerror=null; this.src='{{ asset('images/default/broken.png') }}';">
            </div>
            <div class="product-info">
                <span class="product-tag">{{ $product['categories'] }}</span>
                <h3>{{ $product['name'] }}</h3>
                <div class="product-notes">
                    {{ $product['desc'] }}
                    <br><br>
                    <strong>Top Notes:</strong> Fruity, Fresh, Green<br>
                    <strong>Middle Notes:</strong> Floral, Fruity, Ambery<br>
                    <strong>Base Notes:</strong> Woody, Fruity, Ambery
                </div>
                <a href="#" class="btn-outline">Buy Now</a>
            </div>
        </div>

    @endforeach
</section>