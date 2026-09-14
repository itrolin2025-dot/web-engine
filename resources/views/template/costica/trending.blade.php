@php
    $rawContent = $layout->content ?? '';

    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
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

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#ffffffff';
    $button_color = $content['button_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#ffffff';

    $image = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';

    $products = $products ?? collect();
@endphp

<section class="w-full py-16 md:py-24 pl-4 md:pl-4 font-sans text-[#1a1a1a] overflow-hidden"
    style="background-color: {{ $background_color }};">
    <div class="max-w-7xl ml-auto mr-0" style="margin-left: max(1rem, calc((100vw - 80rem) / 2)); max-width: none;">

        <!-- Header Title -->
        <div class="text-center mb-10 pr-4 md:pr-8">
            <span class="text-[11px] md:text-xs font-bold tracking-[0.2em] uppercase block mb-2"
                style="color: {{ $tag_color }};">
                {{ $tag }}
            </span>
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight"
                style="color: {{ $title_color }};">
                {{ $title }}
            </h2>
            @if($subtitle)
                <p class="mt-3 text-sm md:text-base max-w-2xl mx-auto"
                    style="color: {{ $subtitle_color }};">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <!-- Main Grid: Left Banner + Product Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-0 items-stretch">

            <!-- Left Column: Image Banner - Full Height, Sticks to Left Edge -->
            @if($image)
                <div class="lg:col-span-4 relative overflow-hidden min-h-[300px] lg:min-h-[500px]">
                    <img src="{{ asset($image) }}"
                        alt="{{ $title }}" class="absolute inset-0 w-full h-full object-cover object-center" />
                </div>
            @endif

            <!-- Right Column: Product Cards Grid -->
            <div class="lg:col-span-{{ $image ? '8' : '12' }} grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4 md:p-8 items-stretch">
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

                        <a href="#" class="bg-[#fcfcfc] border border-gray-100 rounded-xl p-4 flex items-center gap-4 relative shadow-sm hover:shadow-md transition duration-300 group cursor-pointer h-full">
                            <!-- Product Image -->
                            <div class="w-20 h-20 shrink-0 flex items-center justify-center p-1 bg-white rounded-lg overflow-hidden">
                                <img src="{{ $imgSrc }}" alt="{{ $pName }}"
                                    class="h-full object-contain group-hover:scale-105 transition duration-300"
                                    loading="lazy" />
                            </div>

                            <!-- Product Details -->
                            <div class="flex flex-col justify-center min-w-0 flex-1">
                                <!-- Stars -->
                                <div class="text-gray-300 text-[11px] mb-1">
                                    ★★★★★
                                </div>

                                <!-- Product Name -->
                                <h3 class="text-xs md:text-sm font-semibold text-gray-900 truncate mb-1 group-hover:text-[#c80000] transition">
                                    {{ $pName }}
                                </h3>

                                <!-- Price -->
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="font-bold text-gray-900">Rp {{ number_format($numericPrice, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @else
                    <div class="col-span-full text-center py-10 text-gray-400">
                        <p>No products available</p>
                    </div>
                @endif
            </div>

        </div>

        <!-- View All Button -->
        @if($button_text)
            <div class="text-center mt-10 pr-4 md:pr-8">
                <a href="#" class="inline-block px-8 py-3 rounded-full text-sm font-semibold transition duration-300 hover:opacity-90"
                    style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                    {{ $button_text }}
                </a>
            </div>
        @endif

    </div>
</section>
