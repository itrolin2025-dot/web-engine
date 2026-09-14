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

    //ambil logo
    $logoFile = $navContent['image'] ?? null;
    $logo = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;

    //ambil nama brand
    $brand = $navContent['brand'] ?? ($website->title ?? 'My Brand');
    //ambil menu
    $menus = $navContent['menus'] ?? [];

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#000000';
    $button_color = $content['button_color'] ?? '#000000';

    $background_color = $content['background_color'] ?? '#ffffff';

    $articles       = collect($articles ?? [])->take(4);
    $article_head = collect($articles ?? [])->sortByDesc('created_at')->first();
    $images = $article_head?->images? json_decode($article_head->images, true): [];
    $image_head = !empty($images[0])? asset('storage/' . $images[0]): asset('images/default/broken.png');

    $article_second = collect($articles ?? [])->sortByDesc('created_at')->skip(1)->first();
    $images = $article_second?->images ? json_decode($article_second->images, true) : [];
    $image_second = !empty($images[0]) ? asset('storage/' . $images[0]) : asset('images/default/broken.png');

@endphp

<section class="w-full bg-white py-16 md:py-12 px-4 md:px-8 font-sans text-[#1a1a1a]" 
style="background-color:{{ $background_color }};">
    <div class="max-w-7xl mx-auto">

        <!-- Section Header -->
        <div class="text-center mb-12">
            <span class="text-[11px] md:text-xs font-bold tracking-[0.2em] uppercase block mb-2" style="color:{{ $tag_color }}">
                {{ $tag }}
            </span>
            <h2 class="text-3xl md:text-4xl font-bold tracking-tight" style="color:{{ $title_color }}">
                {{ $title }}
            </h2>
        </div>

        <!-- Main Blog Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <!-- FEATURED ARTICLE: Takes 2 Columns on Large Screens -->
            <div
                class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 cursor-pointer"
                style="background-color:{{ $button_color }}">
                <!-- Left Image -->
                <div class="relative h-64 sm:h-auto min-h-[260px]">
                    <img src="{{ $image_head }}"
                        alt="Skincare Ingredients" class="absolute inset-0 w-full h-full object-cover" />
                </div>

                <!-- Right Content -->
                <div class="p-6 md:p-8 flex flex-col justify-between">
                    <div>
                        <h3
                            class="text-lg md:text-xl font-bold mb-3 leading-snug hover:text-[#b86d3b] transition"
                            style="color:{{ $button_text_color }}">
                            {{ $article_head->title }}
                        </h3>
                        <p class="text-xs md:text-sm leading-relaxed mb-6"
                            style="color:{{ $button_text_color }}">
                            {{ Str::words($article_head?->description ?? '', 30, '...') }}
                        </p>
                    </div>

                    <div class="text-[11px]" style="color:{{ $button_text_color }}">
                        <span class="font-medium"
                            style="color:{{ $button_text_color }}">{{ $article_head->author }}</span>
                        <span class="mx-1.5">•</span>
                        <span>{{ $article_head->published_date }}</span>
                    </div>
                </div>
            </div>

            <!-- CARD 1 (Top Right) -->
            <div
                class="rounded-xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 bg-white flex flex-col cursor-pointer">
                <div class="relative h-60 w-full overflow-hidden">
                    <img src="{{ $image_second }}"
                        alt="Haircare Routine"
                        class="w-full h-full object-cover hover:scale-105 transition duration-500" />
                </div>
                <div class="bg-[{{ $button_color }}] p-5 text-white flex-1 flex flex-col justify-between">
                    <h3 class="text-sm md:text-base font-semibold leading-snug mb-4">
                        {{ $article_second->title }}
                    </h3>
                    <div class="text-[11px]">
                        <span style="color:{{ $button_text_color }}">{{ $article_second->author }}</span>
                        <span class="mx-1.5" style="color:{{ $button_text_color }}">-</span>
                        <span style="color:{{ $button_text_color }}">{{ $article_second->published_date }}</span>
                    </div>
                </div>
            </div>

            <!-- ROW 2: 3 COLUMNS OF CARDS BELOW -->
            <div id="bottom-blog-cards" class="lg:col-span-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($articles as $index => $article)
                    @php
                        // Handle image logic
                        $rawImages = is_array($article) ? ($article['images'] ?? $article['image'] ?? null) : $article->images;
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
                    @endphp
                    <div class="rounded-xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 bg-white flex flex-col cursor-pointer">
                        <div class="relative h-60 w-full overflow-hidden">
                            <img src="{{ $image }}" alt="{{ $article->title }}" class="w-full h-full object-cover hover:scale-105 transition duration-500" />
                        </div>
                        <div class="bg-[{{ $button_color }}] p-5 text-white flex-1 flex flex-col justify-between">
                            <h3 class="text-sm md:text-base font-semibold leading-snug mb-4">
                                {{ $article->title }}
                            </h3>
                            <div class="text-[11px]">
                                <span style="color:{{ $button_text_color }}">{{ $article->author }}</span>
                                <span class="mx-1.5" style="color:{{ $button_text_color }}">-</span>
                                <span style="color:{{ $button_text_color }}">{{ $article->published_date }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </div>
</section>

<script>
    // Data untuk 3 Berita di Baris Bawah
    const bottomBlogs = [
        {
            title: "How to Build a Minimalist Skincare Routine That Actually Works",
            author: "Vinova Theme",
            date: "Sep 19, 2024",
            image: "https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80"
        },
        {
            title: "The Ultimate Guide to Choosing the Right Makeup Brushes",
            author: "Vinova Theme",
            date: "Sep 18, 2024",
            image: "https://images.unsplash.com/photo-1586495777744-4413f21062fa?auto=format&fit=crop&w=800&q=80"
        },
        {
            title: "Understanding Your Skin Barrier and How to Repair It",
            author: "Vinova Theme",
            date: "Sep 15, 2024",
            image: "https://images.unsplash.com/photo-1608248597260-652163582684?auto=format&fit=crop&w=800&q=80"
        }
    ];
</script>