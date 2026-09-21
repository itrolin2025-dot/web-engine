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

<section id="shop" class="py-16 px-4 max-w-7xl mx-auto bg-red font-sans text-[#1a1a1a]">

    <!-- Title & Tab Filter Navigation -->
    <div class="text-center mb-8">
        <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-4">
            {{ $title }}
        </h2>

        <!-- Filter Tabs -->
        <div id="product-tabs" class="flex items-center justify-center gap-6 text-xs md:text-sm font-medium">
            <button data-filter="all" class="text-[#c83232] border-b-2 border-[#c83232] pb-1 transition">All Products</button>
            @if(isset($categories) && count($categories) > 0)
                @foreach ($categories as $category)
                    <button class="text-gray-600 hover:text-black pb-1 transition" data-filter="{{ is_array($category) ? ($category['id'] ?? $category['kode'] ?? '') : $category->id }}">{{ is_array($category) ? $category['name'] : $category->name }}</button>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Product Grid Container (Diisi oleh JS Loop) -->
    <div id="product-grid" class="flex flex-wrap justify-center gap-6">
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
            <div class="group flex flex-col w-full sm:w-[calc(50%-12px)] lg:w-[calc(25%-18px)]" data-category="{{ $pCatId }}">
                <!-- Image & Action Container -->
                <div class="relative rounded-xl overflow-hidden bg-[#f4f4f4] aspect-[4/5] flex items-center justify-center mb-3">
                    <!-- <span class="absolute top-3 left-3 bg-[#c83232] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-sm z-10"></span> -->
                    
                    <button aria-label="Add to Cart" 
                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-600 shadow-sm hover:text-red-500 hover:scale-110 transition z-10"
                        onclick="addToCart('{{ addslashes($pName) }}', {{ $numericPrice }}, '{{ $image }}')"
                        >
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l8.78-8.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </button>
                    
                    <img src="{{ $image }}" alt="{{ $pName }}" class="w-full h-full object-cover object-top transition duration-500 group-hover:scale-105" />

                    <!-- Hover 3 Action Buttons -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 opacity-0 group-hover:opacity-100 transform translate-y-3 group-hover:translate-y-0 transition-all duration-300 z-10">
                        <button class="w-9 h-9 rounded-full bg-white hover:bg-black hover:text-white text-gray-700 flex items-center justify-center shadow-md transition" title="Add to Cart">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        </button>
                        <!-- <button class="w-9 h-9 rounded-full bg-white hover:bg-black hover:text-white text-gray-700 flex items-center justify-center shadow-md transition" title="Quick View">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/></svg>
                        </button>
                        <button class="w-9 h-9 rounded-full bg-white hover:bg-black hover:text-white text-gray-700 flex items-center justify-center shadow-md transition" title="Compare">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </button> -->
                    </div>
                </div>

                <!-- Details -->
                <div class="text-center flex-grow flex flex-col justify-between">
                    <div>
                        <h3 class="text-xs font-semibold text-gray-800 hover:text-black line-clamp-1 mb-1 cursor-pointer">{{ $pName }}</h3>
                        <!-- <div class="flex items-center justify-center gap-0.5 text-[#ffb800] text-[10px] mb-1">
                            ${'★'.repeat(item.rating)}${'<span class="text-gray-300">★</span>'.repeat(5 - item.rating)}
                            <span class="text-gray-400 text-[10px] ml-1">(${item.reviews} review${item.reviews > 1 ? 's' : ''})</span>
                        </div> -->
                    </div>
                    <div class="flex items-center justify-center gap-2 text-xs">
                        <span class="font-bold text-[#1a1a1a]"> Rp {{ number_format($numericPrice, 0, ',', '.') }}</span>
                        <!-- ${item.oldPrice ? `<span class="text-gray-400 line-through">$${item.oldPrice.toFixed(2)}</span>` : ''} -->
                    </div>
                </div>
            </div>
        @endforeach
        @endif
    </div>

</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('#product-tabs [data-filter]');
        const cards = document.querySelectorAll('#product-grid > [data-category]');
        const activeClasses = ['text-[#c83232]', 'border-[#c83232]'];
        const inactiveClasses = ['text-gray-600', 'hover:text-black'];

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const filter = this.dataset.filter || 'all';

                // Swap active tab styling
                tabs.forEach(function (t) {
                    t.classList.remove(...activeClasses);
                    t.classList.add(...inactiveClasses);
                });
                this.classList.add(...activeClasses);
                this.classList.remove(...inactiveClasses);

                // Show/hide product cards
                cards.forEach(function (card) {
                    const show = filter === 'all' || card.dataset.category === filter;
                    card.style.display = show ? '' : 'none';
                });
            });
        });
    });
</script>