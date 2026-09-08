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

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#ffffffff';
    $button_color = $content['button_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#ffffff';

    $image = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';
    
@endphp

<section class="w-full bg-white py-16 md:py-24 px-4 md:px-8 font-sans text-[#1a1a1a]">
    <div class="max-w-7xl mx-auto">

        <!-- Header Title -->
        <div class="text-center mb-10">
            <span class="text-[11px] md:text-xs font-bold tracking-[0.2em] text-[{{ $tag_color }}] uppercase block mb-2">
                {{ $tag ?? '' }}
            </span>
            <h2 class="text-3xl md:text-4xl font-bold text-[{{ $title_color }}] tracking-tight">
                {{ $title ?? '' }}
            </h2>
        </div>

        <!-- Main Grid Container (Left Banner + 3 Rows of Products) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

            <!-- Left Column: Image Banner (3 Rows Height Equivalent) -->
            <div class="lg:col-span-4 relative rounded-xl overflow-hidden min-h-[380px] lg:min-h-full">
                <img src="{{ $image }}"
                    alt="Beauty Makeup Focus" class="absolute inset-0 w-full h-full object-cover object-center" />
            </div>

            <!-- Right Column: Product Cards Grid (3 Rows x 2 Columns = 6 Products) -->
            <div id="trending-products-grid" class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Products dynamically rendered by JavaScript -->
            </div>

        </div>

    </div>
</section>

<script>
    // Data Produk (6 Items untuk 3 Baris x 2 Kolom)
    const trendingProducts = [
        {
            name: "Radiant Silk Cleanser",
            price: "$68.00",
            oldPrice: "$70.00",
            discount: "-2%",
            image: "https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=300&q=80"
        },
        {
            name: "Timeless Beauty Essence",
            price: "$37.00",
            oldPrice: "$40.00",
            discount: "-7%",
            image: "https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=300&q=80"
        },
        {
            name: "Velvet Smooth Moisturizer",
            price: "$68.00",
            oldPrice: "$71.00",
            discount: "-4%",
            image: "https://images.unsplash.com/photo-1586495777744-4413f21062fa?auto=format&fit=crop&w=300&q=80"
        },
        {
            name: "Ultimate Glow Foundation",
            price: "$57.00",
            oldPrice: "$62.00",
            discount: "-8%",
            image: "https://images.unsplash.com/photo-1608248597260-652163582684?auto=format&fit=crop&w=300&q=80"
        },
        {
            name: "Hydrating Rose Toner",
            price: "$45.00",
            oldPrice: "$50.00",
            discount: "-10%",
            image: "https://images.unsplash.com/photo-1571781926291-c477ebfd024b?auto=format&fit=crop&w=300&q=80"
        },
        {
            name: "Nourishing Night Cream",
            price: "$52.00",
            oldPrice: "$58.00",
            discount: "-10%",
            image: "https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?auto=format&fit=crop&w=300&q=80"
        }
    ];

    // Render Produk
    const productsContainer = document.getElementById('trending-products-grid');
    productsContainer.innerHTML = trendingProducts.map(item => `
        <div class="bg-[#fcfcfc] border border-gray-100 rounded-xl p-4 flex items-center gap-4 relative shadow-sm hover:shadow-md transition duration-300 group cursor-pointer">
            
            <!-- Discount Badge -->
            <span class="absolute top-3 left-3 bg-[#c80000] text-white text-[10px] font-bold px-1.5 py-0.5 rounded">
                ${item.discount}
            </span>

            <!-- Small Product Image -->
            <div class="w-20 h-20 shrink-0 flex items-center justify-center p-1 bg-white rounded-lg">
                <img src="${item.image}" alt="${item.name}" class="h-full object-contain group-hover:scale-105 transition duration-300" />
            </div>

            <!-- Product Details -->
            <div class="flex flex-col justify-center min-w-0 flex-1">
                <!-- Stars -->
                <div class="text-gray-300 text-[11px] mb-1">
                    ★★★★★
                </div>

                <!-- Product Name -->
                <h3 class="text-xs md:text-sm font-semibold text-gray-900 truncate mb-1 group-hover:text-[#c80000] transition">
                    ${item.name}
                </h3>

                <!-- Price -->
                <div class="flex items-center gap-2 text-xs">
                    <span class="font-bold text-gray-900">${item.price}</span>
                    <span class="text-gray-400 line-through text-[11px]">${item.oldPrice}</span>
                </div>
            </div>

        </div>
    `).join('');
</script>