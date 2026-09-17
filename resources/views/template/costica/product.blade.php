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

    $tag = $content['tag_en'] ?? $content['tag'] ?? '';
    $tag_color = $content['tag_color'] ?? '#ffffff';

    $title = $content['title_en'] ?? $content['title'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';
    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';
    
    //ambil logo
    $logoFile = $navContent['image'] ?? null;
    $logo = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;

    //ambil nama brand
    $brand = $navContent['brand'] ?? ($website->title ?? 'My Brand');
    //ambil menu
    $menus = $navContent['menus'] ?? [];

    $products = $products ?? collect();

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#000000';
    $button_color = $content['button_color'] ?? '#000000';
    $background_color = $content['background_color'] ?? '#ffffff';

@endphp

<section class="w-full py-16 md:py-16 pl-4 md:pl-4 font-sans text-[#1a1a1a] overflow-hidden" 
    style="background-color: {{ $background_color ?? '#ffffff' }};">
    <div class="max-w-7xl ml-auto mr-0" style="margin-left: max(1rem, calc((100vw - 80rem) / 2)); max-width: none;">

        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 pr-4 md:pr-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-2" 
                    style="color: {{ $title_color ?? '#000000ff' }}">
                    {{ $title }}
                </h2>
                <p class="text-xs md:text-sm text-gray-500" 
                    style="color: {{ $subtitle_color ?? '#000000ff' }}">
                    {{ $subtitle }}
                </p>
            </div>

            <div class="flex items-center gap-2 self-end sm:self-auto">
                <button onclick="scrollFeatured(-300)" aria-label="Previous"
                    class="w-9 h-9 rounded-full flex items-center justify-center transition duration-200"
                    style="background:{{ $button_color }}; color:{{ $button_text_color }};">
                    ❮
                </button>
                <button onclick="scrollFeatured(300)" aria-label="Next"
                    class="w-9 h-9 rounded-full flex items-center justify-center transition duration-200"
                    style="background:{{ $button_color }}; color:{{ $button_text_color }};">
                    ❯
                </button>
            </div>
        </div>

        <div id="featured-slider"
            class="flex gap-6 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar py-2 {{ count($products ?? []) < 5 ? 'pr-4 md:pr-8 lg:pr-[calc(100vw-80rem)]' : '' }}">
            
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
                        $firstImg = null;
                        
                        if (is_string($rawImages) && !empty(trim($rawImages))) {
                            $decoded = json_decode($rawImages, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                $firstImg = count($decoded) > 0 ? $decoded[0] : null;
                            } else {
                                // Jika bukan JSON (misal raw path string) atau kosong tapi bukan array
                                $firstImg = $rawImages === '[]' ? null : $rawImages;
                            }
                        } elseif (is_array($rawImages) && count($rawImages) > 0) {
                            $firstImg = $rawImages[0];
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
            
                    <div class="snap-start shrink-0 w-[240px] sm:w-[270px] bg-white rounded-lg flex flex-col justify-between shadow-sm relative group/card overflow-hidden {{ $loop->first ? 'ml-auto' : '' }}">
            
                        <!-- <span class="absolute top-3 left-3 bg-[#c80000] text-white text-[10px] font-bold px-2 py-0.5 rounded z-10">
                            50%
                        </span> -->

                        <!-- Product Image -->
                        <div class="w-full aspect-square flex items-center justify-center overflow-hidden">
                            <img src="{{ $image }}" alt="{{ $pName }}" class="w-full h-full object-cover transition duration-300 group-hover/card:scale-105" />
                        </div>

                        <!-- Content Info -->
                        <div class="text-center flex-grow flex flex-col justify-end p-4">
                            <h3 class="text-xs md:text-sm font-semibold mb-2 line-clamp-1 mt-4" style="color:{{ $title_color }};">
                                {{ $pName }}
                            </h3>

                            <!-- Pricing -->
                            <div class="flex items-center justify-center gap-2 text-xs mb-4">
                                <span class="font-bold" style="color:{{ $subtitle_color }};">Rp {{ number_format($numericPrice, 0, ',', '.') }}</span>
                            </div>

                            <!-- Add Button -->
                            <div class="mt-4">
                                <button class="w-full hover:text-white text-white text-[10px] font-bold py-2.5 rounded uppercase tracking-wider transition duration-200"
                                    onclick="addToCart('{{ addslashes($pName) }}', {{ $numericPrice }}, '{{ $image }}')"
                                    style="background:{{ $button_color }}; color:{{ $button_text_color }};">
                                    Add to Cart
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach
            @else
                <div class="w-full text-center">
                    <p class="text-gray-500">No products found</p>
                </div>
            @endif
        </div>

    </div>
</section>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>

    // Scroll Control Function
    function scrollFeatured(distance) {
        container.scrollBy({ left: distance, behavior: 'smooth' });
    }
</script>