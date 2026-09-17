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
    
    $subtitle = $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

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

    $articles  = collect($articles ?? [])->take(3);
    $article_head = collect($articles ?? [])->sortByDesc('created_at')->first();
    $images = $article_head?->images? json_decode($article_head->images, true): [];
    $image_head = !empty($images[0])? asset('storage/' . $images[0]): asset('images/default/broken.png');

    $article_second = collect($articles ?? [])->sortByDesc('created_at')->skip(1)->first();
    $images = $article_second?->images ? json_decode($article_second->images, true) : [];
    $image_second = !empty($images[0]) ? asset('storage/' . $images[0]) : asset('images/default/broken.png');

@endphp

<section class="w-full bg-[{{ $background_color }}] py-16 px-4 md:px-8 lg:px-12 font-sans text-[#1a1a1a]">

    <!-- Section Header -->
    <div class="text-center mb-10 max-w-xl mx-auto">
        <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-2" style="color: {{ $title_color }}">
            {{ $title }}
        </h2>
        <p class="text-xs md:text-sm text-[{{ $subtitle_color }}]">
            {{ $subtitle }}
        </p>
    </div>

    <!-- Blog Grid Container -->
    <div id="blog-grid" class="flex flex-wrap justify-center gap-6 md:gap-8 max-w-7xl mx-auto">
        @foreach ($articles as $article)
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
        
            <article class="group flex flex-col cursor-pointer w-full md:w-[calc((100%-4rem)/3)]">
                <!-- Thumbnail Image -->
                <div class="overflow-hidden rounded-xl bg-gray-100 aspect-[16/10] mb-4">
                    <img src="{{ $image }}" 
                        alt="{{ $article->title }}" 
                        class="w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                </div>

                <!-- Content -->
                <div class="flex flex-col flex-grow">
                    <!-- Category -->
                    <span class="text-[10px] font-bold tracking-wider text-[#c83232] uppercase mb-1">
                        {{ $article->article_category}}
                    </span>

                    <!-- Title -->
                    <h3 class="text-sm md:text-base font-bold text-[{{ $title_color }}] group-hover:text-[#c83232] transition duration-200 mb-2 line-clamp-1">
                        {{ $article->title }}
                    </h3>

                    <!-- Meta Info (Date, Author, Comments) -->
                    <div class="flex items-center gap-2 text-[11px] text-[{{ $subtitle_color }}] font-medium mb-3">
                        <span>{{ $article->published_date }}</span>
                        <span class="text-[{{ $subtitle_color }}]">|</span>
                        <span>{{ $article->author }}</span>
                    </div>

                    <!-- Description Snippet -->
                    <p class="text-xs text-[{{ $subtitle_color }}] leading-relaxed line-clamp-3">
                        {{ Str::words($article?->description ?? '', 30, '...') }}
                    </p>
                </div>
            </article>
        @endforeach
    </div>

</section>

