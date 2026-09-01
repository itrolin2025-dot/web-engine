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

    $categories = $categories ?? collect();
    $products = $products ?? collect();

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#ffffff';
    $button_color = $content['button_color'] ?? '#000000';
    $button_border_color = $content['button_border_color'] ?? '#000000';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';

@endphp

<section class="collections" id="products">
    <h2>{{ $title }}</h2>
    <div class="collection-tabs">
        <button class="tab-btn active" data-filter="all">All Collection</button>
        @if(isset($categories) && count($categories) > 0)
            @foreach ($categories as $category)
                <button class="tab-btn" data-filter="{{ is_array($category) ? ($category['id'] ?? $category['kode'] ?? '') : $category->id }}">{{ is_array($category) ? $category['name'] : $category->name }}</button>
            @endforeach
        @endif
    </div>
</section>

<!-- PRODUCTS -->
<section class="products">
    @if(isset($products) && count($products) > 0)
        @foreach ($products as $product)
            @php
                $pId = is_array($product) ? ($product['id'] ?? '') : $product->id;
                $pName = is_array($product) ? ($product['name'] ?? '') : $product->name;
                $pPrice = is_array($product) ? ($product['price'] ?? 0) : $product->price;
                $pDesc = is_array($product) ? ($product['description'] ?? $product['desc'] ?? '') : $product->description;
                $pCatId = is_array($product) ? ($product['category_products_id'] ?? $product['kode'] ?? '') : $product->category_products_id;
                
                // Get category name
                $pCatName = '';
                if (isset($categories)) {
                    foreach ($categories as $cat) {
                        $cId = is_array($cat) ? ($cat['id'] ?? $cat['kode'] ?? '') : $cat->id;
                        if ($cId == $pCatId) {
                            $pCatName = is_array($cat) ? $cat['name'] : $cat->name;
                            break;
                        }
                    }
                }

                // Handle image logic
                $rawImages = is_array($product) ? ($product['images'] ?? $product['image'] ?? null) : $product->images;
                if (is_string($rawImages)) {
                    $decoded = json_decode($rawImages, true);
                    $firstImg = is_array($decoded) && count($decoded) > 0 ? $decoded[0] : $rawImages;
                } elseif (is_array($rawImages) && count($rawImages) > 0) {
                    $firstImg = $rawImages[0];
                } else {
                    $firstImg = null;
                }

                if ($firstImg) {
                    $image = str_contains($firstImg, '/') || str_contains($firstImg, '\\')
                        ? asset('storage/' . $firstImg)
                        : asset('images/website/' . $domain . '/' . $firstImg);
                } else {
                    $image = asset('images/default/broken.png');
                }

                $button_text = $content['button_text'] ?? '';
                $button_text_color = $content['button_text_color'] ?? '#000000';
                $button_color = $content['button_color'] ?? '#ffffff';

                $numericPrice = (float) $pPrice;
            @endphp
            <div class="product-card" data-category="{{ $pCatId }}">
                <div class="product-image"
                    style="width: 100%; min-height: 400px; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9; border-radius: 12px; overflow: hidden;">
                    <img src="{{ $image }}" alt="{{ $pName }}"
                        style="max-width: 100%; max-height: 450px; object-fit: contain; display: block;"
                        onerror="this.onerror=null; this.src='{{ asset('images/default/broken.png') }}';">
                </div>
                <div class="product-info">
                    @if($pCatName)
                        <span class="product-tag">{{ $pCatName }}</span>
                    @endif
                    <h3>{{ $pName }}</h3>
                    <div class="product-notes">
                        {{ $pDesc }}
                        <br><br>
                        <strong>Price:</strong> Rp {{ number_format($numericPrice, 0, ',', '.') }}
                    </div>
                    <a
                        onclick="addToCart('{{ addslashes($pName) }}', {{ $numericPrice }}, '{{ $image }}')"
                        class="btn-outline" style="cursor: pointer; color: {{ $button_text_color }}; background-color: {{ $button_color }}; border-color: {{ $button_border_color }};">
                        {{ $button_text }}</a>
                </div>
            </div>
        @endforeach
    @endif
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('#products .tab-btn');
        const productCards = document.querySelectorAll('.products .product-card');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                tabBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                const filterValue = btn.getAttribute('data-filter');

                productCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    if (filterValue === 'all' || cardCategory === filterValue) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });
            });
        });
    });
</script>