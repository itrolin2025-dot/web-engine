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

        @foreach ($products as $product)
            @php
                $image = !empty($product['image'])
                    ? asset('images/website/' . $domain . '/' . $product['image'])
                    : asset('images/default/broken.png');
                // Extract numeric price (remove non-digit except dots)
                $numericPrice = preg_replace('/[^0-9]/', '', $product['price'] ?? '0');
            @endphp

            <div class="border border-stone-100 rounded-lg p-3 text-center flex flex-col justify-between">
                <div>
                    <div class="bg-yellow-100 aspect-square rounded-lg overflow-hidden mb-3">
                        <img src="{{ $image }}"
                            class="w-full h-full object-cover">
                    </div>
                    <span
                        class="text-[9px] text-stone-400 font-semibold uppercase tracking-widest">{{ $product['name'] }}</span>
                    <h4 class="font-bold text-sm mb-1">{{ $product['name'] }}</h4>
                    <p class="text-xs font-semibold text-stone-600 mb-3">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                </div>
                <button
                    onclick="addToCart('{{ addslashes($product['name']) }}', {{ $numericPrice }}, '{{ $image }}')"
                    class="w-full border border-black text-black py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">Add
                    To Cart</button>
            </div>
        @endforeach
    </div>
</section>