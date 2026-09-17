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

    $productIds = collect($products ?? [])->pluck('id')->toArray();
    if (!empty($productIds)) {
        $productReviews = \App\Models\ProductReview::with('product')
            ->whereIn('products_id', $productIds)
            ->where('status', 1)
            ->get()->take(10);
    } else {
        $productReviews = \App\Models\ProductReview::with('product')->where('status', 1)->get();
    }

    $count = $productReviews->count();

    // Slide = jumlah review asli (tanpa duplikasi)
    $displayReviews = $productReviews->values();

    // Map review ke array JS-friendly (dipisah dari @json agar parser Blade tidak salah match bracket)
    $testimonials = $displayReviews->map(function ($r) {
        return [
            'avatar' => !empty($r->profile_photo) ? asset('storage/' . $r->profile_photo) : asset('images/default/broken.png'),
            'quote'  => '"' . ($r->comment ?? '') . '"',
            'rating' => (int) ($r->rating ?? 5),
            'name'   => $r->name ?? '',
            'location' => $r->product->name ?? 'Product',
        ];
    })->values()->all();
@endphp

<section class="w-full bg-[#f8f8f8] py-20 px-4 font-sans text-[#1a1a1a] relative overflow-hidden">
    <div class="max-w-3xl mx-auto text-center relative">

        @if($count === 0)
            <!-- Empty Review State -->
            <div class="py-12 flex flex-col items-center">
                <i class="fa-regular fa-comment-dots text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-base font-semibold text-gray-500">Empty Review</h3>
                <p class="text-xs text-gray-400 mt-1">Belum ada review untuk produk ini.</p>
            </div>
        @else

        <!-- Slider Track Container (single, slides rendered by JS) -->
        <div class="relative flex items-center">
            <div id="testimonial-track" class="relative flex-1 min-h-[260px] flex items-center justify-center">
                <!-- Active slide rendered by JS -->
            </div>
        </div>

        <!-- Navigation Dots Indicator (rendered by JS) -->
        <div id="slider-dots" class="flex items-center justify-center gap-2.5 mt-6"></div>

        @endif

    </div>
</section>

<script>
    // Testimonial data injected from server
    const testimonials = @json($testimonials);

    let currentIndex = 0;
    let autoPlayTimer = null;
    const track = document.getElementById('testimonial-track');
    const dotsContainer = document.getElementById('slider-dots');

    // Render Function
    function renderSlider() {
        if (!track || testimonials.length === 0) return;

        // Render Active Slide (dengan animasi fade)
        const item = testimonials[currentIndex];
        track.innerHTML = `
            <div class="flex flex-col items-center animate-fade-in" style="animation: fadeIn 0.5s ease;">
                <!-- Avatar Image -->
                <div class="w-20 h-20 rounded-full overflow-hidden mb-6 border-2 border-white shadow-sm">
                    <img src="${item.avatar}" alt="${item.name}" class="w-full h-full object-cover" />
                </div>

                <!-- Quote Text -->
                <p class="text-sm md:text-base text-gray-700 leading-relaxed font-medium max-w-2xl mb-4 italic">
                    ${item.quote}
                </p>

                <!-- Rating Stars -->
                <div class="flex items-center justify-center gap-1 text-[#ffb800] text-sm mb-3">
                    ${'★'.repeat(item.rating)}
                </div>

                <!-- Author Name & Location -->
                <h3 class="text-base md:text-lg font-semibold text-[#c83232] mb-0.5">
                    ${item.name}
                </h3>
                <p class="text-xs text-gray-500 font-medium">
                    ${item.location}
                </p>
            </div>
        `;

        // Render Navigation Dots (sembunyikan jika hanya 1 slide)
        if (testimonials.length <= 1) {
            dotsContainer.innerHTML = '';
            dotsContainer.classList.add('hidden');
        } else {
            dotsContainer.classList.remove('hidden');
            dotsContainer.innerHTML = testimonials.map((_, idx) => `
                <button type="button" onclick="goToSlide(${idx})"
                        aria-label="Go to slide ${idx + 1}"
                        class="transition-all duration-300 rounded-full flex items-center justify-center ${idx === currentIndex
                    ? 'w-4 h-4 border border-black bg-transparent'
                    : 'w-2 h-2 bg-gray-600 hover:bg-black'
                }">
                    ${idx === currentIndex ? '<span class="w-2 h-2 bg-black rounded-full"></span>' : ''}
                </button>
            `).join('');
        }

    }

    function goToSlide(index) {
        currentIndex = (index + testimonials.length) % testimonials.length;
        renderSlider();
        restartAutoPlay();
    }

    // Auto Play Slider (Setiap 5 detik) - hanya jika ada lebih dari 1 slide
    function startAutoPlay() {
        if (testimonials.length > 1 && !autoPlayTimer) {
            autoPlayTimer = setInterval(() => {
                currentIndex = (currentIndex + 1) % testimonials.length;
                renderSlider();
            }, 5000);
        }
    }

    function stopAutoPlay() {
        if (autoPlayTimer) {
            clearInterval(autoPlayTimer);
            autoPlayTimer = null;
        }
    }

    function restartAutoPlay() {
        stopAutoPlay();
        startAutoPlay();
    }

    if (track) {
        // Initial Render
        renderSlider();
        startAutoPlay();

        // Pause auto-play saat hover, lanjut saat leave
        track.addEventListener('mouseenter', stopAutoPlay);
        track.addEventListener('mouseleave', startAutoPlay);

        // Swipe gesture (mobile)
        let touchStartX = 0;
        let touchEndX = 0;
        track.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        track.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                goToSlide(diff > 0 ? currentIndex + 1 : currentIndex - 1);
            }
        }, { passive: true });
    }
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
