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

    $categories = $content['categories'] ?? [];
    $products = $content['products'] ?? [];

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';

@endphp

<section id="products-section" class="max-w-7xl mx-auto px-6 py-16">
    <div class="flex justify-between items-end mb-10">
        <h2 class="text-2xl md:text-3xl font-serif-heading font-bold uppercase tracking-wider">PRODUCTS</h2>
        <a href="#" class="text-xs font-semibold tracking-widest uppercase border-b border-black pb-0.5">See All
            Products</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

        @if(isset($products) && count($products) > 0)
            @foreach($products->take(6) as $product)
                @php
                    $pId = is_array($product) ? ($product['id'] ?? '') : $product->id;
                    $pName = is_array($product) ? ($product['name'] ?? '') : $product->name;
                    $pPrice = is_array($product) ? ($product['price'] ?? 0) : $product->price;

                    // Handle image logic
                    $rawImages = is_array($product) ? ($product['images'] ?? $product['image'] ?? null) : $product->images;
                    $firstImg = null;

                    if (is_string($rawImages) && !empty(trim($rawImages))) {
                        $decoded = json_decode($rawImages, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $firstImg = count($decoded) > 0 ? $decoded[0] : null;
                        } else {
                            $firstImg = $rawImages === '[]' ? null : $rawImages;
                        }
                    } elseif (is_array($rawImages) && count($rawImages) > 0) {
                        $firstImg = $rawImages[0];
                    }

                    if ($firstImg) {
                        $imgSrc = str_contains($firstImg, '/') || str_contains($firstImg, '\\')
                            ? asset('storage/' . $firstImg)
                            : asset('images/website/' . $domain . '/' . $firstImg);
                    } else {
                        $imgSrc = asset('images/default/broken.png');
                    }

                    $numericPrice = (float) $pPrice;
                @endphp

                <div class="border border-stone-100 rounded-lg p-3 text-center flex flex-col justify-between">
                    <div>
                        <div class="bg-yellow-100 aspect-square rounded-lg overflow-hidden mb-3">
                            <img src="{{ $imgSrc }}"
                                class="w-full h-full object-cover">
                        </div>
                        <span
                            class="text-[9px] text-stone-400 font-semibold uppercase tracking-widest">{{ $pCatName }}</span>
                        <h4 class="font-bold text-sm mb-1">{{ $pName }}</h4>
                            <p class="text-xs font-semibold text-stone-600 mb-3">Rp {{ number_format($pPrice, 0, ',', '.') }}</p>
                    </div>
                    <button
                        onclick="addToCart('{{ addslashes($pName) }}', {{ $numericPrice }}, '{{ $imgSrc }}')"
                        class="w-full border border-black text-black py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">Add
                        To Cart</button>
                </div>
            @endforeach
        @endif
    </div>
</section>