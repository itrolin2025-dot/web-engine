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

    $background = !empty($content['background']) ? 'images/website/' . $domain . '/' . $content['background'] : 'images/default/broken.png';
    $image      = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';
@endphp

<section class="w-full py-16 md:py-24 bg-white font-sans text-[#1a1a1a] overflow-hidden">
    <div class="w-full grid grid-cols-1 lg:grid-cols-2">

        <!-- Left Side: Content & Dual Image Grid -->
        <div class="flex flex-col justify-between bg-[#f9f9fb] pt-8 md:pt-16 lg:pt-20 px-8 md:px-16 lg:px-20 pb-0">

            <!-- Text Content -->
            <div class="text-center max-w-xl mx-auto mb-12">
                <span class="text-[11px] md:text-xs font-bold tracking-[0.2em] text-[{{ $tag_color }}] uppercase block mb-3">
                    {{ $tag }}
                </span>

                <h2 class="text-3xl md:text-4xl font-bold text-[{{ $title_color }}] tracking-tight mb-6">
                    {{ $title}}
                </h2>

                <p class="text-xs md:text-sm text-[{{ $subtitle_color }}] leading-relaxed italic">
                    {{ $subtitle }} 
                </p>
            </div>

            <!-- Two Column Bottom Images (Rapat tanpa jarak / gap-0) -->
            <div
                class="grid grid-cols-2 gap-0 h-64 md:h-80 -mx-8 md:-mx-16 lg:-mx-20 w-[calc(100%+4rem)] md:w-[calc(100%+8rem)] lg:w-[calc(100%+10rem)] max-w-none mt-auto">
                <div class="relative overflow-hidden h-full">
                    <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80"
                        alt="Skincare Serum Water Ripple" class="w-full h-full object-cover" />
                </div>
                <div class="relative overflow-hidden h-full">
                    <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=800&q=80"
                        alt="Applying Cream Application" class="w-full h-full object-cover" />
                </div>
            </div>

        </div>

        <!-- Right Side: Video Feature Banner -->
        <div
            class="relative min-h-[450px] lg:min-h-full bg-[#b8a7c6] flex items-center justify-center overflow-hidden group">

            <!-- Background Image -->
            <img src="{{ $image }}"
                alt="Story"
                class="absolute inset-0 w-full h-full object-cover object-center transition duration-700 group-hover:scale-105" />

            <!-- Circular Interactive Play Video Button -->
            <!-- <button aria-label="Play Video"
                class="relative z-10 w-20 h-20 md:w-24 md:h-24 rounded-full bg-white/90 backdrop-blur-sm flex items-center justify-center shadow-xl hover:scale-110 hover:bg-white transition duration-300">
              
                <svg class="absolute w-full h-full animate-spin-slow p-1" viewBox="0 0 100 100">
                    <path id="circlePath" fill="none" stroke="none"
                        d="M 10, 50 a 40,40 0 1,1 80,0 a 40,40 0 1,1 -80,0" />
                    <text class="text-[8px] font-bold tracking-widest uppercase fill-gray-800">
                        <textPath href="#circlePath">
                            • PRESS PLAY AND IMMERSE YOURSELF IN VIDEO
                        </textPath>
                    </text>
                </svg>

                <svg class="w-6 h-6 text-gray-900 ml-1" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                </svg>
            </button> -->

        </div>

    </div>
</section>

<!-- Custom Animation Style for Rotating Badge -->
<!-- <style>
    @keyframes spinSlow {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin-slow {
        animation: spinSlow 12s linear infinite;
    }
</style> -->