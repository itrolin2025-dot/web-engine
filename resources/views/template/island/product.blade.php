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

<svg class="block w-full relative z-10 -mt-9 -mb-px" viewBox="0 0 1440 64" preserveAspectRatio="none" aria-hidden="true">
    <path fill="#FFFFFF" d="M0,40 C80,24 160,54 240,42 C320,30 400,56 480,42 C560,28 640,54 720,42 C800,30 880,56 960,42 C1040,28 1120,54 1200,42 C1280,30 1360,54 1440,42 L1440,64 L0,64 Z"/>
</svg>

<!-- 5. FEATURED PRODUCTS (White) -->
<section class="relative w-full bg-white pt-8 pb-20 px-6 overflow-hidden">
    <!-- Squiggles on edges -->
    <svg class="absolute top-16 -left-8 w-20 h-56 rotate-[160deg] opacity-80" aria-hidden="true"><use href="#squiggle"/></svg>
    <svg class="absolute bottom-24 -right-8 w-20 h-56 rotate-[10deg] opacity-80" aria-hidden="true"><use href="#squiggle"/></svg>

    <h2 class="font-groovy text-2xl md:text-4xl tracking-wide text-[{{ $title_color}} ] text-center uppercase mb-14 relative z-10">{{ $title }}</h2>

    <div class="max-w-4xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-10 gap-y-12 relative z-10">
        <!-- P1 -->
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
                <a href="#" class="group block">
                    <div class="w-full h-52 md:h-60 overflow-hidden">
                        <img src="{{ $image }}" alt="{{ $pName }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <p class="text-[10px] md:text-[11px] font-bold tracking-[0.2em] text-[#8A8175] uppercase text-center mt-4 group-hover:text-[#E2A7B8] transition-colors">{{ $pName }}</p>
                </a>
            @endforeach
        @endif
    </div>
</section>