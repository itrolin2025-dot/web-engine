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

    $title = $content['title_en'] ?? $content['title'] ?? '';
    $title_color = $content['title_color'] ?? '#6B5B4A';

    $background_color = $content['background_color'] ?? '#FDF6E9';

    $repeater = $content['repeater'] ?? $content['tagline'] ?? [];
    if (!is_array($repeater)) {
        $repeater = [];
    } else {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }

    // Only keep items that actually have an image to show
    $galleryItems = array_values(array_filter($repeater, function ($item) {
        return is_array($item) && !empty($item['image']);
    }));

    $rawCount = count($galleryItems);
@endphp

@if ($rawCount > 0)
    @php
        // Ensure 1 set has enough items to fill wide viewports (minimum 10 items)
        $singleSet = $galleryItems;
        while (count($singleSet) < 10) {
            $singleSet = array_merge($singleSet, $galleryItems);
        }

        $itemsInSet = count($singleSet);
        // Calculate a relaxed, steady speed (~3.5 seconds per item in 1 set)
        $duration = max(30, $itemsInSet * 3.5);
        $uid = 'island-gallery-' . substr(md5(($layout->id ?? 'gallery') . $domain . $itemsInSet . $duration), 0, 8);
    @endphp

    <style>
        @keyframes {{ $uid }}-scroll {
            0%   { transform: translate3d(0, 0, 0); }
            100% { transform: translate3d(-50%, 0, 0); }
        }

        .{{ $uid }}-viewport {
            overflow: hidden;
            width: 100%;
            -webkit-mask-image: linear-gradient(to right, transparent 0, #000 4%, #000 96%, transparent 100%);
            mask-image: linear-gradient(to right, transparent 0, #000 4%, #000 96%, transparent 100%);
        }

        .{{ $uid }}-track {
            display: flex;
            width: max-content;
            animation: {{ $uid }}-scroll {{ $duration }}s linear infinite;
            will-change: transform;
        }

        .{{ $uid }}-group {
            display: flex;
            flex-shrink: 0;
        }

        .{{ $uid }}-viewport:hover .{{ $uid }}-track {
            animation-play-state: paused;
        }

        @media (prefers-reduced-motion: reduce) {
            .{{ $uid }}-track { animation: none; }
        }
    </style>

    <section class="w-full bg-[{{ $background_color }}] py-8 md:py-12" aria-label="{{ $title !== '' ? $title : 'Gallery' }}">
        @if ($title !== '')
            <h2 class="font-script text-4xl md:text-5xl text-center text-[{{ $title_color }}] mb-8 px-6">{{ $title }}</h2>
        @endif

        <div class="{{ $uid }}-viewport">
            <div class="{{ $uid }}-track">
                {{-- Group 1 --}}
                <div class="{{ $uid }}-group">
                    @foreach ($singleSet as $img)
                        <a href="#" class="block shrink-0 overflow-hidden px-1.5 md:px-2">
                            <img src="{{ asset('images/website/' . $domain . '/' . $img['image']) }}"
                                alt="{{ $img['title'] ?? 'Gallery' }}"
                                loading="lazy"
                                class="w-28 h-28 sm:w-36 sm:h-36 md:w-48 md:h-48 lg:w-56 lg:h-56 object-cover rounded-xl transition-transform duration-300 hover:scale-105" />
                        </a>
                    @endforeach
                </div>
                {{-- Group 2 (Identical clone for seamless infinite loop) --}}
                <div class="{{ $uid }}-group" aria-hidden="true">
                    @foreach ($singleSet as $img)
                        <a href="#" class="block shrink-0 overflow-hidden px-1.5 md:px-2" tabindex="-1">
                            <img src="{{ asset('images/website/' . $domain . '/' . $img['image']) }}"
                                alt="{{ $img['title'] ?? 'Gallery' }}"
                                loading="lazy"
                                class="w-28 h-28 sm:w-36 sm:h-36 md:w-48 md:h-48 lg:w-56 lg:h-56 object-cover rounded-xl transition-transform duration-300 hover:scale-105" />
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif