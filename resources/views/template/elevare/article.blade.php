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

<section class="py-16 px-4 sm:px-6 lg:px-12 text-gray-800" style="background-color: {{ $background_color }};">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- SECTION TITLE -->
        <div class="space-y-2">
            <h2 class="text-3xl sm:text-4xl font-serif text-gray-900 tracking-tight">
                {{ $title }}
            </h2>
            @if(!empty($subtitle))
                <p class="text-xs sm:text-sm text-gray-500 font-light">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        @if(count($articles) > 0)
            <!-- ARTICLE GRID -->
            <div class="{{ count($articles) === 1 ? 'flex justify-start' : (count($articles) === 2 ? 'grid grid-cols-1 sm:grid-cols-2 max-w-3xl gap-6 lg:gap-8' : (count($articles) === 3 ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 max-w-5xl gap-6 lg:gap-8' : 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8')) }} items-stretch">
                @foreach ($articles as $article)
                    @php
                        $aTitle = is_array($article) ? ($article['title'] ?? '') : $article->title;
                        $aDesc  = is_array($article) ? ($article['description'] ?? '') : $article->description;
                        $aAuthor = is_array($article) ? ($article['author'] ?? '') : $article->author;
                        $aDate  = is_array($article) ? ($article['published_date'] ?? '') : $article->published_date;

                        $rawImages = is_array($article) ? ($article['images'] ?? null) : $article->images;
                        if (is_string($rawImages)) {
                            $decoded = json_decode($rawImages, true);
                            $firstImg = is_array($decoded) && count($decoded) > 0 ? $decoded[0] : $rawImages;
                        } elseif (is_array($rawImages) && count($rawImages) > 0) {
                            $firstImg = $rawImages[0];
                        } else {
                            $firstImg = null;
                        }

                        if ($firstImg) {
                            $image = (str_contains($firstImg, '/') || str_contains($firstImg, '\\'))
                                ? asset('storage/' . $firstImg)
                                : asset('images/website/' . $domain . '/' . $firstImg);
                        } else {
                            $image = asset('images/default/broken.png');
                        }
                    @endphp

                    <article class="flex flex-col justify-between space-y-4 group w-full {{ count($articles) === 1 ? 'max-w-sm' : '' }}">
                        <div class="space-y-4">
                            <!-- Image -->
                            <div class="aspect-[4/3] w-full rounded-2xl overflow-hidden bg-gray-200 shadow-xs">
                                <img src="{{ $image }}"
                                    alt="{{ $aTitle }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                                    onerror="this.onerror=null; this.src='{{ asset('images/default/broken.png') }}';" />
                            </div>
                            <!-- Content -->
                            <div class="space-y-2">
                                <h3 class="text-base font-semibold text-gray-900 leading-snug group-hover:text-[#a0685b] transition">
                                    {{ $aTitle }}
                                </h3>
                                @if(!empty($aAuthor) || !empty($aDate))
                                    <div class="text-[11px] text-gray-400 font-medium flex items-center gap-2">
                                        @if(!empty($aAuthor)) <span>By {{ $aAuthor }}</span> @endif
                                        @if(!empty($aAuthor) && !empty($aDate)) <span>•</span> @endif
                                        @if(!empty($aDate)) <span>{{ is_string($aDate) ? date('d M Y', strtotime($aDate)) : (method_exists($aDate, 'format') ? $aDate->format('d M Y') : $aDate) }}</span> @endif
                                    </div>
                                @endif
                                <p class="text-xs text-gray-500 leading-relaxed font-light line-clamp-3">
                                    {{ $aDesc }}
                                </p>
                            </div>
                        </div>
                        <!-- Read More Link -->
                        <div class="pt-2">
                            <a href="#"
                                class="inline-block text-xs font-semibold text-gray-700 underline tracking-wider uppercase hover:text-black transition">
                                READ MORE
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-xs text-gray-400 italic">Belum ada artikel yang tersedia.</p>
        @endif

        @if(count($articles) > 4)
            <!-- CAROUSEL PAGINATION DOTS -->
            <div class="flex items-center justify-center space-x-2 pt-6">
                <span class="w-2 h-2 rounded-full bg-gray-600"></span>
                <span class="w-2 h-2 rounded-full bg-gray-300"></span>
            </div>
        @endif

    </div>
</section>