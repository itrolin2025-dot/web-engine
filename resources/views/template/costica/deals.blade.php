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

<section class="w-full py-16 pl-4 md:pl-8 font-sans text-[#1a1a1a] overflow-hidden"
    style="background-color: {{ $background_color ?? '#ffffff' }}">
    <div class="max-w-7xl ml-auto mr-0 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center" style="margin-left: max(1rem, calc((100vw - 80rem) / 2)); max-width: none;">

        <!-- Left Info & Countdown Block (4 Columns) -->
        <div class="lg:col-span-4 flex flex-col items-start pr-0 lg:pr-4">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-gray-900 mb-4" 
                style="color: {{ $title_color ?? '#000000' }}">
                {{ $title }}
            </h2>

            <p class="text-xs md:text-sm text-gray-600 leading-relaxed mb-8 max-w-sm" 
                style="color: {{ $subtitle_color ?? '#000000' }}">
                {{ $subtitle }}
            </p>

            <!-- Countdown Timer Boxes -->
            <div class="grid grid-cols-4 gap-2 w-full max-w-xs mb-8">
                <div class="bg-white rounded-lg p-3 text-center shadow-sm">
                    <span id="deal-days" class="block text-lg font-bold" 
                        style="color: {{ $subtitle_color }}">00</span>
                    <span class="text-[10px] font-medium"
                        style="color: {{ $subtitle_color }}">Days</span>
                </div>
                <div class="bg-white rounded-lg p-3 text-center shadow-sm">
                    <span id="deal-hours" class="block text-lg font-bold" 
                        style="color: {{ $subtitle_color }}">07</span>
                    <span class="text-[10px] font-medium"
                    style="color: {{ $subtitle_color }}">Hours</span>
                </div>
                <div class="bg-white rounded-lg p-3 text-center shadow-sm">
                    <span id="deal-mins" class="block text-lg font-bold"
                        style="color: {{ $subtitle_color }}">51</span>
                    <span class="text-[10px] font-medium"
                    style="color: {{ $subtitle_color }}">Mins</span>
                </div>
                <div class="bg-white rounded-lg p-3 text-center shadow-sm">
                    <span id="deal-secs" class="block text-lg font-bold" style="color: {{ $subtitle_color }}">50</span>
                    <span class="text-[10px] font-medium"
                    style="color: {{ $subtitle_color }}">Secs</span>
                </div>
            </div>

            <!-- CTA Button -->
            <a href="#"
                class="inline-flex items-center gap-2 text-xs font-semibold px-6 py-3 rounded-md transition duration-300"
                style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                {{ $button_text }}
                <span>↗</span>
            </a>
        </div>

        <!-- Right Product Slider Block (8 Columns) -->
        <div class="lg:col-span-8 relative group">

            <!-- Horizontal Scrollable Container -->
            <div id="product-slider"
                class="flex gap-5 overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar pb-4 pt-2 px-1">
                
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
                
                        <div class="snap-start shrink-0 w-[230px] sm:w-[250px] bg-white rounded-lg flex flex-col justify-between shadow-sm relative group/card overflow-hidden">
                            <!-- Discount Badge -->
                            <span class="absolute top-3 left-3 bg-[#c80000] text-white text-[10px] font-bold px-2 py-0.5 rounded z-10">
                                50%
                            </span>
                            
                            <!-- Product Image -->
                            <div class="w-full aspect-square flex items-center justify-center overflow-hidden">
                                <img src="{{ $image }}" alt="{{ $pName }}" class="w-full h-full object-cover transition duration-300 group-hover/card:scale-105" />
                            </div>

                            <!-- Content Info -->
                            <div class="text-center flex-grow flex flex-col justify-end p-4">
                                <!-- <div class="flex justify-center text-gray-300 text-xs mb-2 gap-0.5">
                                    ★★★★★
                                </div> -->

                                <h3 class="text-xs md:text-sm font-semibold mb-2 line-clamp-1 mt-4"
                                    style="color:{{ $title_color }};">
                                    {{ $pName }}
                                </h3>

                                <!-- Pricing -->
                                <div class="flex items-center justify-center gap-2 text-xs mb-4">
                                    <span class="font-bold"
                                        style="color:{{ $subtitle_color }};">Rp {{ number_format($numericPrice, 0, ',', '.') }}</span>
                                    @if($numericPrice > 0)
                                        <span class="text-gray-400 line-through text-[11px]">Rp {{ number_format($numericPrice * 2, 0, ',', '.') }}</span>
                                    @endif
                                </div>

                                <!-- Add Button -->
                                <div class="mt-4">
                                    <button class="w-full hover:text-white text-white text-[10px] font-bold py-2.5 rounded uppercase tracking-wider transition duration-200"
                                        style="background:{{ $button_color }}; color:{{ $button_text_color }}; hover:{{ $button_color }}/60;">
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

            <!-- Navigation Arrow Buttons (Visible on Hover) -->
            <button onclick="scrollSlider(-280)"
                class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-3 w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center text-gray-700 hover:bg-black hover:text-white transition z-10 opacity-0 group-hover:opacity-100">
                ‹
            </button>
            <button onclick="scrollSlider(280)"
                class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-3 w-9 h-9 bg-white rounded-full shadow-md flex items-center justify-center text-gray-700 hover:bg-black hover:text-white transition z-10 opacity-0 group-hover:opacity-100">
                ›
            </button>

        </div>

    </div>
</section>

<!-- Custom Styles for Hiding Scrollbar -->
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

        // Function Scroll Manual Slider
        function scrollSlider(distance) {
            slider.scrollBy({ left: distance, behavior: 'smooth' });
        }

        // JS Countdown Real-time Timer
        const targetTime = new Date().getTime() + (7 * 3600 * 1000) + (51 * 60 * 1000) + (50 * 1000);
        setInterval(() => {
            const now = new Date().getTime();
            const diff = targetTime - now;
            if (diff > 0) {
                document.getElementById('deal-days').innerText = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
                document.getElementById('deal-hours').innerText = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                document.getElementById('deal-mins').innerText = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                document.getElementById('deal-secs').innerText = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
            }
        }, 1000);
    </script>