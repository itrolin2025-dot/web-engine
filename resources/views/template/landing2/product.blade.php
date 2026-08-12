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

<section class="border-y border-stone-200 bg-white">
    <div class="py-10" style="overflow: hidden;">
        <div id="category-track"
            class="flex flex-nowrap items-center text-sm font-medium text-stone-500 uppercase tracking-widest cursor-grab"
            style="overflow-x: auto; scroll-behavior: smooth; -ms-overflow-style: none; scrollbar-width: none;">

            @foreach ($categories as $category)
                @php
                    $image = !empty($category['image'])
                        ? asset('images/website/' . $domain . '/' . $category['image'])
                        : asset('images/default/broken.png');
                @endphp
                <button href="#"
                    data-filter="{{ $category['kode'] }}"
                    class="category-item flex flex-col items-center gap-3 hover:text-stone-900 transition-colors group flex-shrink-0">
                    <div
                        class="category-img rounded-full overflow-hidden bg-stone-100 group-hover:ring-2 group-hover:ring-stone-400 transition-all">
                        <img src="{{ $image }}" alt="Skincare" class="w-full h-full object-cover">
                    </div>
                    <span>{{ $category['name'] }}</span>
                </button>
            @endforeach


        </div>
    </div>

    <style>
        /* Mobile: 3 items visible */
        .category-item {
            min-width: calc(100vw / 3);
            padding: 0.5rem 0;
        }

        .category-img {
            width: 72px;
            height: 72px;
        }

        /* Tablet: 4 items visible */
        @media (min-width: 768px) {
            .category-item {
                min-width: calc(100vw / 4);
            }

            .category-img {
                width: 96px;
                height: 96px;
            }
        }

        /* Desktop: exactly 5 items visible */
        @media (min-width: 1024px) {
            .category-item {
                min-width: calc(100vw / 5);
            }

            .category-img {
                width: 140px;
                height: 140px;
            }

            #category-track {
                padding: 0 2rem;
            }
        }

        #category-track::-webkit-scrollbar {
            display: none;
        }

        #category-track.is-dragging {
            cursor: grabbing;
            user-select: none;
        }
    </style>
    <script>
        (function () {
            const track = document.getElementById('category-track');
            let isDown = false, startX, scrollLeft;
            track.addEventListener('mousedown', (e) => { isDown = true; track.classList.add('is-dragging'); startX = e.pageX - track.offsetLeft; scrollLeft = track.scrollLeft; });
            track.addEventListener('mouseleave', () => { isDown = false; track.classList.remove('is-dragging'); });
            track.addEventListener('mouseup', () => { isDown = false; track.classList.remove('is-dragging'); });
            track.addEventListener('mousemove', (e) => { if (!isDown) return; e.preventDefault(); track.scrollLeft = scrollLeft - (e.pageX - track.offsetLeft - startX); });
        })();
    </script>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
    <div class="mb-12">
        <span
            class="text-xs font-bold uppercase tracking-widest text-[#61984B] block mb-2 text-center">{{ $title }}</span>
        <h2 class="text-3xl font-serif-brand text-stone-800 text-center">{{ $subtitle }}</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Card 1 -->
        @foreach ($products as $product)
            @php
                $image = !empty($product['image'])
                    ? asset('images/website/' . $domain . '/' . $product['image'])
                    : asset('images/default/broken.png');
                
                $numericPrice = preg_replace('/[^0-9]/', '', $product['price'] ?? '0');

            @endphp
            <div
                data-category="{{ $product['kode'] }}"
                class="group flex flex-col justify-between bg-white rounded-2xl shadow-sm border border-stone-100 h-full relative hover:shadow-md transition-shadow overflow-hidden">
                <div>
                    <div class="aspect-[1/1] w-full bg-stone-100 overflow-hidden relative">
                        <img src="{{ $image }}" alt="Daily Moisturizer"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>
                    <div class="px-4 sm:px-5 pt-5">
                        <h3 class="text-lg font-semibold text-stone-800">{{ $product['name'] }}</h3>
                        <p class="text-sm text-stone-500 mt-1 mb-2 line-clamp-2">{{ $product['desc'] }}</p>
                    </div>
                </div>
                <div class="p-4 sm:p-5 flex items-end justify-between">
                    <p class="text-base font-bold text-stone-900">Rp {{ number_format($product['price'], 0, ',', '.') }}</p>
                    <button
                        onclick="addToCart('{{ addslashes($product['name']) }}', {{ $numericPrice }}, '{{ $image }}')"
                        class="w-10 h-10 rounded-full bg-stone-900 text-white flex items-center justify-center hover:bg-stone-700 transition-colors shadow-md"
                        aria-label="Add to cart">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</section>