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
    $subtitle = $content['subtitle'] ?? $content['subtitle_en'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#ffffff';

    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $desc = $content['desc_en'] ?? $content['desc'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $categories = $categories ?? collect();
    $products = $products ?? collect();

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';

@endphp

<section class="py-16 px-4 sm:px-6 lg:px-12 overflow-hidden text-gray-800"
    style="background-color: {{ $background_color }};">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-start gap-8 lg:gap-12">

        <!-- LEFT SIDE: TITLE, DESCRIPTION, CTA & NAVIGATION -->
        <div class="w-full lg:w-1/4 flex flex-col justify-between space-y-6 lg:sticky lg:top-8">
            <div class="space-y-3">
                <h2 class="text-3xl sm:text-4xl font-serif text-gray-900 tracking-tight">
                    {{ $title }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-500 leading-relaxed font-light">
                    {{ $subtitle }}
                </p>
            </div>

            <!-- CTA Button -->
            <div>
                <a href="#"
                    class="inline-flex items-center gap-2 px-6 py-3 text-white text-xs font-semibold tracking-widest uppercase rounded-full hover:bg-[#a0685b] transition duration-200"
                    style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                    <span>{{ $button_text }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <!-- Arrow Navigation Buttons -->
            <div class="flex items-center space-x-3 pt-2">
                <button
                    class="w-9 h-9 rounded-full bg-black/5 text-gray-600 flex items-center justify-center hover:bg-black/10 transition"
                    aria-label="Previous">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button
                    class="w-9 h-9 rounded-full bg-black/5 text-gray-600 flex items-center justify-center hover:bg-black/10 transition"
                    aria-label="Next">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- RIGHT SIDE: SCROLLABLE PRODUCT CAROUSEL GRID -->
        <div class="w-full lg:w-3/4 overflow-x-auto pb-6 scrollbar-none flex gap-6">
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

                        $numericPrice = (float) $pPrice;
                    @endphp
                    
                    <!-- PRODUCT CARD 1 -->
                    <div class="flex-shrink-0 w-[260px] sm:w-[280px] flex flex-col justify-between space-y-4">
                        <!-- Image Box -->
                        <div
                            class="relative bg-[#eeebe3] rounded-2xl aspect-square flex items-center justify-center p-6 group">
                            <button
                                class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-600 hover:text-black shadow-xs"
                                aria-label="Wishlist">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                            <img src="{{ $image }}" alt="{{ $pName }}"
                        style="max-width: 100%; max-height: 450px; object-fit: contain; display: block;"
                        onerror="this.onerror=null; this.src='{{ asset('images/default/broken.png') }}';">
                        </div>
                        <!-- Details -->
                        <div class="space-y-1">
                            <span
                                class="text-[11px] font-semibold tracking-wider text-[#b57a6c] uppercase block">{{ $pCatName }}</span>
                            <h3 class="text-xs text-gray-700 font-normal line-clamp-1">{{ $pName }}</h3>
                            <p class="text-xs font-medium text-gray-800 pt-2">Rp {{ number_format($numericPrice, 0, ',', '.') }}</p>
                        </div>
                        <!-- Add to Cart Button -->
                        <button
                            onclick="addToCart('{{ addslashes($pName) }}', {{ $numericPrice }}, '{{ $image }}')"
                            class="w-full py-2.5 border border-gray-400 rounded-full text-[11px] font-semibold text-gray-700 tracking-wider hover:bg-gray-900 hover:text-white hover:border-gray-900 transition duration-200 uppercase">
                            ADD TO CART
                        </button>
                    </div>
            @endforeach
            @endif
        </div>
    </div>
</section>