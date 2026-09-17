@php
    $rawContent = $layout->content ?? '';

    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
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
    $image = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : '';

    $repeater = $content['repeater'] ?? $content['tagline'];
    if (is_array($repeater)) {
        // Hanya ambil 4 item pertama (setelah sort by 'sort')
        $repeater = collect($repeater)->sortBy('sort')->take(4)->values()->all();
    }
@endphp

<section class="relative w-full min-h-[500px] lg:min-h-[700px] overflow-hidden" style="background-color: {{ $background_color }}">

    {{-- Slides --}}
    @foreach($repeater as $i => $item)
        @php
            $slideBg = !empty($item['image']) ? asset('images/website/' . $domain . '/' . $item['image']) : asset($background);
            $slideTag = $item['label'] ?? $tag;
            $slideTagColor = $item['color'] ?? $tag_color;
            $slideTitle = $item['title'] ?? $title;
            $slideTitleColor = $item['title_color'] ?? $title_color;
            $slideSubtitle = $item['description'] ?? $subtitle;
            $slideBtnText = $item['button_text'] ?? $button_text;
            $slideBtnColor = $item['button_color'] ?? $button_color;
            $slideBtnTextColor = $item['button_text_color'] ?? $button_text_color;
        @endphp
        <div class="slide-item {{ $i === 0 ? 'active-slide' : 'hidden' }} absolute inset-0 w-full h-full">
            {{-- Background Image --}}
            <img src="{{ $slideBg }}" alt="{{ $slideTitle }}"
                class="absolute inset-0 w-full h-full object-cover object-center">

            {{-- Dark Overlay --}}
            <div class="absolute inset-0 bg-black/30"></div>

            {{-- Content --}}
            <div class="relative z-10 flex flex-col items-end justify-center h-full min-h-[500px] lg:min-h-[700px] px-8 md:px-16 lg:px-24 text-left">
                @if($slideTag)
                    <p class="text-xs md:text-sm font-bold uppercase tracking-widest mb-4" style="color: {{ $slideTagColor }}">
                        {{ $slideTag }}
                    </p>
                @endif
                @if($slideTitle)
                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold tracking-tight mb-6" style="color: {{ $slideTitleColor }}">
                        {{ $slideTitle }}
                    </h1>
                @endif
                @if($slideSubtitle)
                    <p class="text-white/80 text-sm md:text-lg leading-relaxed mb-10 max-w-xl text-left">
                        {{ $slideSubtitle }}
                    </p>
                @endif
                @if($slideBtnText)
                    <a href="#" class="inline-block text-sm md:text-base font-semibold px-10 py-4 rounded-full transition duration-300 shadow-lg hover:scale-105 text-left"
                        style="background-color: {{ $slideBtnColor }}; color: {{ $slideBtnTextColor }}">
                        {{ $slideBtnText }}
                    </a>
                @endif
            </div>
        </div>
    @endforeach

    @if(count($repeater) > 1)
        {{-- Navigation Arrow - Left --}}
        <button onclick="prevSlide()"
            class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 z-30 p-3 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white shadow-md transition">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M15 18l-6-6 6-6" />
            </svg>
        </button>

        {{-- Navigation Arrow - Right --}}
        <button onclick="nextSlide()"
            class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 z-30 p-3 rounded-full bg-white/20 hover:bg-white/40 backdrop-blur-sm text-white shadow-md transition">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 18l6-6-6-6" />
            </svg>
        </button>

        {{-- Dots Navigation --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-3 z-30">
            @foreach($repeater as $i => $item)
                <button onclick="goToSlide({{ $i }})"
                    class="dot-btn w-3 h-3 rounded-full transition-all {{ $i === 0 ? 'scale-125' : 'bg-white/50 hover:bg-white/70' }}"
                    style="{{ $i === 0 ? 'background-color: ' . $button_color : '' }}"></button>
            @endforeach
        </div>
    @endif
</section>

<script>
    let currentSlide = 0;
    let isAnimating = false;
    const slides = document.querySelectorAll('.slide-item');
    const dots = document.querySelectorAll('.dot-btn');
    const accentColor = '{{ $button_color }}';
    const TRANSITION_MS = 800;
    const AUTO_SLIDE_MS = 6000;
    let autoTimer = null;

    // Init: all slides absolute, only first visible
    slides.forEach((slide, i) => {
        slide.style.opacity = i === 0 ? '1' : '0';
        slide.style.transition = 'opacity ' + TRANSITION_MS + 'ms ease-in-out';
        slide.style.zIndex = i === 0 ? '1' : '0';
    });

    function showSlide(index) {
        if (isAnimating || index === currentSlide) return;
        isAnimating = true;

        const prev = slides[currentSlide];
        const next = slides[index];

        // Prepare next slide behind
        next.style.zIndex = '2';
        next.style.opacity = '0';
        next.classList.remove('hidden');

        // Force reflow so browser registers opacity:0 before transition
        void next.offsetWidth;

        // Fade in next, fade out prev simultaneously
        next.style.opacity = '1';
        prev.style.opacity = '0';

        // Update dots
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('bg-white/50');
                dot.classList.add('scale-125');
                dot.style.backgroundColor = accentColor;
            } else {
                dot.classList.add('bg-white/50');
                dot.classList.remove('scale-125');
                dot.style.backgroundColor = '';
            }
        });

        setTimeout(() => {
            prev.classList.add('hidden');
            prev.style.zIndex = '0';
            next.style.zIndex = '1';
            currentSlide = index;
            isAnimating = false;
        }, TRANSITION_MS);
    }

    function nextSlide() {
        showSlide((currentSlide + 1) % slides.length);
    }

    function prevSlide() {
        showSlide((currentSlide - 1 + slides.length) % slides.length);
    }

    function goToSlide(index) {
        showSlide(index);
        resetAuto();
    }

    function resetAuto() {
        clearInterval(autoTimer);
        if (slides.length > 1) {
            autoTimer = setInterval(nextSlide, AUTO_SLIDE_MS);
        }
    }

    resetAuto();
</script>
