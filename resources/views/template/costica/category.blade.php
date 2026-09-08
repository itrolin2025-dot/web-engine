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

    $background_color = $content['background_color'] ?? '#fffffff';

    $title = $content['title_en'] ?? $content['title'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    $categories = $categories->take(5) ?? collect();
@endphp

<section class="w-full py-16 md:py-16 px-4 md:px-16 font-sans"
    style="background-color:{{ $background_color }};">
    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-10">

        <div class="w-full lg:w-1/3 text-left">
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-3"
                style="color: {{ $title_color ?? '#000000ff' }}">
                {{ $title ?? '' }}
            </h2>
            <p class="text-xs md:text-sm text-gray-500 leading-relaxed max-w-sm"
                style="color: {{ $subtitle_color ?? '#000000ff' }}">
                {{ $subtitle ?? '' }}
            </p>
        </div>

        <div class="w-full lg:w-2/3 overflow-x-auto no-scrollbar py-2">
            <div id="category-picks"
                class="flex items-center gap-6 md:gap-8 min-w-max lg:min-w-0 justify-start lg:justify-end">
                
                @if(isset($categories) && count($categories) > 0)
                    @foreach ($categories as $category)
                        @php
                            $category = (array) $category;
                            $image = !empty($category['image'])
                                ? asset('storage/' . $category['image'])
                                : asset('images/default/broken.png');
                        @endphp
                        <div class="flex flex-col items-center gap-3 cursor-pointer group shrink-0">
                            <div
                                class="w-28 h-28 sm:w-32 sm:h-32 rounded-full overflow-hidden  p-1 transition duration-300 transform group-hover:scale-105 shadow-sm">
                                <img src="{{ $image }}" alt="{{ $category['name'] }}" class="w-full h-full object-cover rounded-full" />
                            </div>
                            
                            <span class="text-xs sm:text-sm font-semibold group-hover:text-black transition"
                            style="color:{{ $button_text_color }}">
                                {{ $category['name'] }}
                            </span>
                        </div>
                    @endforeach
                @endif
            </div>
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

<!-- <script>
    const picksCategories = [
        {
            title: "Masks",
            image: "https://images.unsplash.com/photo-1512290900673-0498b8981507?auto=format&fit=crop&w=400&q=80",
            bgColor: "bg-[#e5cbb3]"
        },
        {
            title: "Facial Cream",
            image: "https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=400&q=80",
            bgColor: "bg-[#e2ded9]"
        },
        {
            title: "Oil Cleansers",
            image: "https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=400&q=80",
            bgColor: "bg-[#ecebe9]"
        },
        {
            title: "Body Care",
            image: "https://images.unsplash.com/photo-1608248597260-652163582684?auto=format&fit=crop&w=400&q=80",
            bgColor: "bg-[#eae3f0]"
        },
        {
            title: "Lipstick",
            image: "https://images.unsplash.com/photo-1586495777744-4413f21062fa?auto=format&fit=crop&w=400&q=80",
            bgColor: "bg-[#b0cde4]"
        }
    ];

    // Render Items
    const picksContainer = document.getElementById('category-picks');
    picksContainer.innerHTML = picksCategories.map(item => `
        <div class="flex flex-col items-center gap-3 cursor-pointer group shrink-0">
            <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full overflow-hidden ${item.bgColor} p-1 transition duration-300 transform group-hover:scale-105 shadow-sm">
                <img src="${item.image}" alt="${item.title}" class="w-full h-full object-cover rounded-full" />
            </div>
            
            <span class="text-xs sm:text-sm font-semibold text-gray-800 group-hover:text-black transition">
                ${item.title}
            </span>
        </div>
    `).join('');
</script> -->