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

    $title = $content['title'] ?? $content['title_en'] ?? 'Article';
    $subtitle = $content['subtitle'] ?? $content['subtitle_en'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#f7f5ee';

    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $desc = $content['desc_en'] ?? $content['desc'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $categories = $categories ?? collect();
    $products = $products ?? collect();

    $article_categories = $article_categories ?? collect();
    $articles = $articles ?? collect();

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';
@endphp

<section class="bg-white py-10 px-6 lg:px-12 text-black">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- 1. CATEGORIES TITLE & PILLS -->
        <div class="space-y-4">
            <h2 class="text-3xl font-bold tracking-tight" style="color: {{ $title_color }};">{{ $title }}</h2>
            <h3 class="text-3xl font-bold tracking-tight" style="color: {{ $subtitle_color }};">{{ $subtitle }}</h3>

            <!-- <div class="flex flex-wrap items-center gap-2">
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Clothing</button>
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Music</button>
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Accessories</button>
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Collab</button>
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Home
                    and Lifestyle</button>
            </div> -->
        </div>

        <!-- 2. SORT & RESULTS BAR -->
        <div class="flex items-center justify-end space-x-4 pt-2 text-xs text-gray-600">
            <div class="flex items-center space-x-1 cursor-pointer">
                <span>Sort by </span>
                <span class="font-semibold text-black">Relevance</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <span class="text-gray-400">1723 product(s)</span>
        </div>

        <!-- 3. MAIN CATALOG CONTENT (STICKY SIDEBAR + PRODUCT GRID) -->
        <div class="flex flex-col lg:flex-row gap-8 items-start relative">

            <!-- LEFT SIDEBAR FILTER (STICKY) -->
            <aside
                class="w-full lg:w-1/5 sticky top-24 self-start space-y-6 max-h-[calc(100vh-6rem)] overflow-y-auto pr-2 scrollbar-none">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <span class="font-bold text-sm">Filters</span>
                    <span
                        class="w-5 h-5 rounded-full border border-gray-400 text-[10px] font-semibold flex items-center justify-center">0</span>
                </div>

                <div class="space-y-3 border-b border-gray-100 pb-4">
                    <span class="text-xs font-semibold block">Categories</span>
                    @if(isset($categories) && count($categories) > 0)
                        @foreach ($categories as $category)
                            <label
                                class="flex items-center space-x-2 bg-gray-50 p-2.5 rounded-md cursor-pointer hover:bg-gray-100 transition">
                                <input type="checkbox" class="w-4 h-4 rounded-xs border-gray-300 text-black focus:ring-0">
                                <span class="text-xs text-gray-700">{{ is_array($category) ? $category['name'] : $category->name }}</span>
                            </label>
                        @endforeach
                    @endif
                </div>

                <!-- Sale Checkbox -->
                <div class="space-y-3 border-b border-gray-100 pb-4">
                    <span class="text-xs font-semibold block">Sale</span>
                    <label
                        class="flex items-center space-x-2 bg-gray-50 p-2.5 rounded-md cursor-pointer hover:bg-gray-100 transition">
                        <input type="checkbox" class="w-4 h-4 rounded-xs border-gray-300 text-black focus:ring-0">
                        <span class="text-xs text-gray-700">On Sale Only</span>
                    </label>
                </div>

            </aside>

            <!-- RIGHT PRODUCT GRID (2 BARIS / 8 PRODUCTS) -->
            <main class="w-full lg:w-4/5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-8">

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

                            <!-- BARIS 1 - CARD 1 -->
                            <div class="group flex flex-col justify-between space-y-3 cursor-pointer">
                                <div
                                    class="relative bg-[#f6f6f6] rounded-xl aspect-square flex items-center justify-center p-6 overflow-hidden">
                                    @if($pCatName)
                                        <span
                                        class="absolute top-3 left-3 bg-black text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider z-10">{{ $pCatName }}</span>
                                    @endif
                                    
                                    <img src="{{ $image }}"
                                        alt="{{ $pName }}"
                                        class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                                    <!-- Hover Add to Cart Button -->
                                    <button
                                        onclick="addToCart('{{ addslashes($pName) }}', {{ $numericPrice }}, '{{ $image }}')"
                                        class="absolute bottom-3 left-3 right-3 bg-black text-white py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                        Add To Cart
                                    </button>
                                </div>
                                <div class="space-y-0.5">
                                    <h3 class="text-xs font-medium text-gray-800 line-clamp-1">{{ $pName }}</h3>
                                    <p class="text-xs text-gray-500">Rp {{ number_format($numericPrice, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif

                </div>
            </main>

        </div>

    </div>
</section>