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

    $button_text = $content['button_text'] ?? $content['button_text_en'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $hero_img = !empty($content['hero_img']) ? 'images/website/' . $domain . '/' . $content['hero_img'] : '';
@endphp

<section 
  class="relative min-h-[500px] lg:min-h-[600px] flex items-center overflow-hidden px-6 lg:px-16 bg-[#f8ccd4] bg-[length:100%_auto] bg-center bg-no-repeat"
  style="background-image: url('{{ asset($hero_bg) }}');">
    <div class="max-w-7xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 items-center gap-8 py-12">
        <div class="lg:col-span-7 z-10 space-y-6">
            <h1
                class="text-4xl sm:text-5xl lg:text-6xl font-serif-heading font-bold text-white leading-tight uppercase tracking-wide">
                {{ $title }}
            </h1>
            <p class="text-white tracking-widest uppercase text-sm font-medium">
                {{ $subtitle }}
            </p>
            @php
                if  (!empty($button_text)){
                    echo "<a href=\"#products-section\" class=\"inline-block bg-black text-white px-8 py-3.5 text-xs font-semibold tracking-widest uppercase hover:bg-stone-800 transition-colors\">".$button_text."</a>";
                }
            @endphp
        </div>
        <!-- <div class="lg:col-span-5 relative flex justify-center">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80"
                alt="Beauty Model" class="rounded-2xl shadow-xl w-full max-w-md object-cover h-[420px]">
        </div> -->
    </div>
</section>