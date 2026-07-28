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

    $reviews = $content['reviews'] ?? [];

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    // $hero_bg = !empty($content['hero_bg']) ? 'images/website/' . $domain . '/' . $content['hero_bg'] : 'images/default/broken.png';
    $about_image = !empty($content['about_image']) ? 'images/website/' . $domain . '/' . $content['about_image'] : 'images/default/broken.png';

@endphp

<section class="py-20 overflow-hidden bg-stone-50">
    <div class="text-center mb-12">
        <span class="text-xs font-bold uppercase tracking-widest text-stone-400 block mb-2">OUR CUSTOMER</span>
        <h2 class="text-3xl font-serif-brand text-stone-800">What They Say</h2>
    </div>

    <style>
        .testimonial-marquee {
            display: flex;
            overflow: hidden;
            user-select: none;
            gap: 2rem;
        }

        .testimonial-track {
            flex-shrink: 0;
            display: flex;
            justify-content: space-around;
            gap: 2rem;
            min-width: 100%;
            animation: marquee 25s linear infinite;
        }

        @keyframes marquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(calc(-100% - 2rem));
            }
        }

        .testimonial-marquee:hover .testimonial-track {
            animation-play-state: paused;
        }

        /* Star SVG component */
        .star-icon {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }
    </style>

    <div class="testimonial-marquee w-full">
        <!-- Track 1 -->
        <div class="testimonial-track px-4">
            <!-- Card 1 -->
            @foreach ($reviews as $review)
                @php
                    $avatar = !empty($review['avatar'])
                        ? asset('images/website/' . $domain . '/' . $review['avatar'])
                        : asset('images/default/broken.png');
                @endphp
                <div
                    class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm w-[340px] h-[240px] md:w-[480px] flex-shrink-0 flex flex-col justify-between">
                    <div class="flex items-start gap-4 mb-5">
                        <img src="{{ $avatar }}" alt="{{ $review['name'] }}"
                            class="w-14 h-14 rounded-full object-cover shrink-0">
                        <p class="text-stone-700 italic font-serif-brand text-base md:text-lg leading-snug">
                            {{ '"' . $review['text'] . '"' }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex gap-1">
                            @php $starCount = (int) ($review['star'] ?? 5); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="star-icon {{ $i <= $starCount ? 'text-yellow-400' : 'text-stone-300' }}"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-xs font-bold text-stone-800 tracking-wider">{{ $review['name'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Track 2 (Clone for infinite loop) -->
        <div class="testimonial-track px-4" aria-hidden="true">
            <!-- Card 1 -->
            @foreach ($reviews as $review)
                @php
                    $avatar = !empty($review['avatar'])
                        ? asset('images/website/' . $domain . '/' . $review['avatar'])
                        : asset('images/default/broken.png');
                @endphp
                <div
                    class="bg-white p-6 rounded-2xl border border-stone-200 shadow-sm w-[340px] h-[240px] md:w-[480px] flex-shrink-0 flex flex-col justify-between">
                    <div class="flex items-start gap-4 mb-5">
                        <img src="{{ $avatar }}" alt="{{ $review['name'] }}"
                            class="w-14 h-14 rounded-full object-cover shrink-0">
                        <p class="text-stone-700 italic font-serif-brand text-base md:text-lg leading-snug">
                            {{ '"' . $review['text'] . '"' }}
                        </p>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex gap-1">
                            @php $starCount = (int) ($review['star'] ?? 5); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="star-icon {{ $i <= $starCount ? 'text-yellow-400' : 'text-stone-300' }}"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-xs font-bold text-stone-800 tracking-wider">{{ $review['name'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>