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

    $tag = $content['tag_en'] ?? $content['tag'] ?? 'WEDDINGS · BIRTHDAYS · POP-UPS & MORE';
    $tag_color = $content['tag_color'] ?? '#611328';

    $title = $content['title_en'] ?? $content['title'] ?? 'Your event called. It wants candy!';
    $title_color = $content['title_color'] ?? '#4a1525';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? 'Candy concierge-guided tastings, a mix-your-own candy bag for every guest, and a candy cart that doubles as a conversation piece.';
    $subtitle_color = $content['subtitle_color'] ?? '#4a1525cc';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? 'PLAN YOUR EVENT';
    $button_color = $content['button_color'] ?? '#c23b59';
    $button_text_color = $content['button_text_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#FAF6F0';

    $repeater = $content['repeater'] ?? $content['tagline'] ?? [];
    if (!is_array($repeater)) {
        $repeater = [];
    } else {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }
    
    $badgeClasses = ['candy-scalloped-peach', 'candy-starburst-maroon', 'candy-scalloped-pink'];
    $badgeRotations = ['-rotate-2', 'rotate-3', '-rotate-3'];

    // Foto event (polaroid) — dua foto untuk switcher
    $image = !empty($content['image']) && $content['image'] !== 'your image'
        ? 'images/website/' . $domain . '/' . $content['image']
        : 'images/default/broken.png';
@endphp

<style>
    /* Vertical Pink Stripes Background Pattern */
    .candy-pink-stripe-bg {
        background: repeating-linear-gradient(to right,
                #fce8f0,
                #fce8f0 30px,
                #ffffff 30px,
                #ffffff 60px);
    }

    /* Badge styles */
    .candy-scalloped-peach {
        background-color: #f7a379;
        border-radius: 24px;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.05));
    }

    .candy-starburst-maroon {
        background-color: #611328;
        clip-path: polygon(50% 0%, 61% 10%, 75% 5%, 80% 18%, 95% 20%,
                90% 35%, 100% 48%, 90% 62%, 95% 78%, 80% 80%,
                75% 95%, 61% 90%, 50% 100%, 39% 90%, 25% 95%,
                20% 80%, 5% 78%, 10% 62%, 0% 48%, 10% 35%,
                5% 20%, 20% 18%, 25% 5%, 39% 10%);
    }

    .candy-scalloped-pink {
        background-color: #fbd6e3;
        border-radius: 20px;
        filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.05));
    }
</style>

<section id="event" class="py-10 md:py-16 selection:bg-pink-200" style="background-color: {{ $background_color }};">
    <div class="p-4 md:p-7">
        <div
            class="w-full max-w-7xl mx-auto flex flex-col lg:flex-row items-stretch shadow-xl rounded-3xl overflow-hidden border border-rose-100">

            <!-- LEFT COLUMN: Cream Background & Event Details -->
            <div class="w-full lg:w-1/2 p-8 sm:p-12 md:p-16 flex flex-col justify-center space-y-6 relative"
                style="background-color: {{ $background_color }};">

                <!-- Small Uppercase Header -->
                <!-- <div class="text-[11px] sm:text-xs uppercase font-bold tracking-[0.2em]" style="color: {{ $tag_color }};">
                    {{ $tag }}
                </div> -->

                <!-- Main Heading -->
                <h1 class="candy-font-serif text-4xl sm:text-5xl md:text-6xl lg:text-7xl leading-[0.95] tracking-tight"
                    style="color: {{ $title_color }};">
                    {!! nl2br(e($title)) !!}
                </h1>

                <!-- Description Paragraph -->
                <p class="text-sm sm:text-base font-normal leading-relaxed max-w-lg" style="color: {{ $subtitle_color }};">
                    {{ $subtitle }}
                </p>

                <!-- Repeater Image Row -->
                <!-- <div class="flex flex-wrap items-center justify-center gap-6 pt-2">
                    @foreach ($repeater as $index => $repeat)
                        @php
                            $repeaterImage = null;
                            foreach (['image', 'title', 'image_url', 'url'] as $key) {
                                if (!empty($repeat[$key]) && $repeat[$key] !== 'your image') {
                                    $repeaterImage = $repeat[$key];
                                    break;
                                }
                            }
                        @endphp
                        <div class="relative w-40 h-40 sm:w-48 sm:h-48 md:w-56 md:h-56 flex items-center justify-center -rotate-2 hover:rotate-0 transition-transform duration-300">
                            @if($repeaterImage)
                                <img src="{{ asset('images/website/' . $domain . '/' . $repeaterImage) }}"
                                     alt=""
                                     class="w-full h-full object-contain rounded-lg shadow-md">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#4a1525] rounded-lg shadow-md">
                                    <span class="candy-font-serif font-bold text-2xl">{{ $repeat['label'] ?? '' }}</span>
                                </div>
                            @endif
                            <div class="absolute top-0 left-1/2 -translate-x-1/2 flex items-center justify-center pointer-events-none">
                                <div class="h-12 w-[160%] bg-[#c73e5f]/90 shadow-sm rounded-sm -ml-8"></div>
                            </div>
                        </div>
                    @endforeach
                </div> -->

                <!-- Plan Your Event Button -->
                <div class="pt-4">
                    <button onclick="candyPlanEvent()"
                        class="text-xs font-bold uppercase tracking-widest py-3.5 px-8 rounded-full shadow-md transition-all transform hover:-translate-y-0.5 active:translate-y-0"
                        style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                        {{ $button_text }}
                    </button>
                </div>
            </div>

            <!-- RIGHT COLUMN: Pink Striped Background & Photo Frame with Stickers -->
            <div class="w-full lg:w-1/2 candy-pink-stripe-bg p-8 sm:p-12 md:p-16 flex items-center justify-center relative overflow-hidden">

                <!-- Container for Polaroid Frame with Top-Left Tape & Auto Slide -->
                <div class="relative max-w-md w-full transform rotate-1 hover:rotate-0 transition-transform duration-500">

                    <!-- Red Sticky Tape at Top-Left -->
                    <div class="absolute -top-3 -left-4 z-30 h-7 w-20 bg-[#c73e5f]/80 shadow-sm rotate-[-35deg] rounded-sm" style="backdrop-filter: blur(1px);"></div>

                    <!-- Red Sticky Tape at Top-Right -->
                    <div class="absolute -top-3 -right-4 z-30 h-7 w-20 bg-[#c73e5f]/80 shadow-sm rotate-[35deg] rounded-sm" style="backdrop-filter: blur(1px);"></div>

                    <!-- Red Sticky Tape at Bottom-Left -->
                    <div class="absolute -bottom-3 -left-4 z-30 h-7 w-20 bg-[#c73e5f]/80 shadow-sm rotate-[35deg] rounded-sm" style="backdrop-filter: blur(1px);"></div>

                    <!-- White Polaroid / Photo Frame -->
                    <div class="bg-white p-4 sm:p-5 rounded-sm shadow-2xl border border-gray-200 relative">

                        <!-- Auto Slide from Repeater Images -->
                        <div class="relative overflow-hidden rounded bg-pink-50 aspect-square sm:aspect-auto sm:h-[380px]">
                            <div id="candy-event-slide-track"
                                 class="flex">
                                @if(!empty($repeater))
                                    @foreach ($repeater as $index => $repeatItem)
                                        @php
                                            $slideImage = null;
                                            foreach (['image', 'title', 'image_url', 'url'] as $key) {
                                                if (!empty($repeatItem[$key]) && $repeatItem[$key] !== 'your image') {
                                                    $slideImage = $repeatItem[$key];
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <div class="slide-wrap w-full flex-none"
                                             data-label="{{ $repeatItem['label'] ?? '' }}">
                                            <img src="{{ $slideImage ? asset('images/website/' . $domain . '/' . $slideImage) : 'https://placehold.co/800x600/fde2e4/4a1525?text=No+Image' }}"
                                                 alt=""
                                                 class="w-full h-full object-cover"
                                                 loading="lazy">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        <!-- Pilih Tampilan: prev/next + dots -->
                        @php $totalSlides = !empty($repeater) ? count($repeater) : 0; @endphp
                        <div class="flex items-center justify-center space-x-3 mt-3 pt-2 border-t border-gray-100">
                            <span class="text-[10px] uppercase font-bold text-gray-400"></span>
                            <button type="button"
                                onclick="candyEventPrev()"
                                class="text-[#4a1525] hover:text-[#c73e5f] transition-colors"
                                {{ ($totalSlides < 2) ? 'disabled style="opacity:0.3;pointer-events:none;"' : '' }}
                                aria-label="Previous slide">
                                &#8249;
                            </button>
                            @for($i = 1; $i <= $totalSlides; $i++)
                                <button type="button"
                                    onclick="candyEventGoTo({{ $i }})"
                                    class="candy-event-dot w-3 h-3 rounded-full focus:outline-none ring-2 ring-offset-1 {{ ($i === 1) ? 'bg-[#c73e5f] ring-[#c73e5f]' : 'bg-gray-300 ring-gray-300' }}"></button>
                            @endfor
                            <button type="button"
                                onclick="candyEventNext()"
                                class="text-[#4a1525] hover:text-[#c73e5f] transition-colors"
                                {{ ($totalSlides < 2) ? 'disabled style="opacity:0.3;pointer-events:none;"' : '' }}
                                aria-label="Next slide">
                                &#8250;
                            </button>
                        </div>

                        <!-- Notes sync handled by global candyEventSyncNotes() -->
                    </div>                        <!-- Bottom-Right Tag: Notes from current repeater label -->
                    <div
                        class="absolute -bottom-8 -right-4 sm:-right-8 z-30 bg-[#fffefc] border-2 border-dashed border-[#b54530] p-3 rounded shadow-lg transform rotate-6 max-w-[200px]"
                        id="candy-event-notes-tag">
                        <div class="text-[10px] text-[#4a1525]/70 font-bold uppercase tracking-wider">Notes:
                        </div>
                        <div class="candy-font-script text-xl font-bold text-[#c73e5f]" id="candy-event-notes-text">—</div>

                        <!-- Purple Ribbons Graphic (SVG) -->
                        <div class="absolute -top-4 -left-6 w-12 h-12 pointer-events-none">
                            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M50 30 C40 10, 10 20, 20 40 C30 60, 50 50, 50 50 C50 50, 70 60, 80 40 C90 20, 60 10, 50 30 Z"
                                    fill="#b19cd9" stroke="#7b52ab" stroke-width="3" />
                                <path d="M45 50 Q 30 80, 15 90 M 55 50 Q 70 85, 85 95" stroke="#b19cd9" stroke-width="4"
                                    stroke-linecap="round" />
                            </svg>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<script>
    var candyEventSlides = null;
    var candyEventTotal = 0;
    var candyEventCurrent = 1;
    var candyEventTimer = null;

    function candyEventSyncNotes() {
        var notesText = document.getElementById('candy-event-notes-text');
        if (!notesText) return;
        var currentSlide = null;
        if (candyEventTotal > 0 && typeof candyEventCurrent === 'number' && candyEventCurrent >= 1 && candyEventCurrent <= candyEventTotal) {
            var idx = candyEventCurrent - 1;
            var track = document.getElementById('candy-event-slide-track');
            if (track) {
                var wraps = Array.from(track.querySelectorAll('.slide-wrap'));
                if (wraps[idx]) {
                    currentSlide = wraps[idx];
                }
            }
        }
        if (!currentSlide) {
            notesText.textContent = '—';
            return;
        }
        var rawValue = currentSlide.getAttribute('data-label') || '';
        notesText.textContent = rawValue ? rawValue : '—';
    }

    function candyEventBuildSlides() {
        var track = document.getElementById('candy-event-slide-track');
        if (!track) return;
        var wraps = Array.from(track.querySelectorAll('.slide-wrap'));
        if (wraps.length === 0) {
            console.warn('candy event: no slide-wrap found');
            return;
        }
        candyEventSlides = wraps;
        candyEventTotal = wraps.length;
        candyEventCurrent = 1;
        candyEventApplySlide();
    }

    function candyEventApplySlide() {
        if (!candyEventSlides) return;
        var track = document.getElementById('candy-event-slide-track');
        var dots = document.querySelectorAll('.candy-event-dot');
        if (track) {
            var offset = -(candyEventCurrent - 1) * 100;
            track.style.transform = 'translateX(' + offset + '%)';
            try { track.style.transition = 'transform 0.7s ease-in-out'; } catch (e) {}
        }
        if (dots) {
            dots.forEach(function (dot, idx) {
                var activeCls = 'bg-[#c73e5f] ring-[#c73e5f]';
                var idleCls = 'bg-gray-300 ring-gray-300';
                dot.className = 'candy-event-dot w-3 h-3 rounded-full focus:outline-none ring-2 ring-offset-1 ' + (idx === candyEventCurrent - 1 ? activeCls : idleCls);
            });
        }
    }

    function candyEventGoTo(index) {
        if (!candyEventSlides) candyEventBuildSlides();
        if (candyEventTotal === 0) return;
        if (index < 1) index = candyEventTotal;
        if (index > candyEventTotal) index = 1;
        candyEventCurrent = index;
        candyEventApplySlide();
    }

    function candyEventNext() {
        if (!candyEventSlides) candyEventBuildSlides();
        if (candyEventTotal === 0) return;
        candyEventGoTo(candyEventCurrent + 1);
        candyEventRestartAutoSlide();
    }

    function candyEventPrev() {
        if (!candyEventSlides) candyEventBuildSlides();
        if (candyEventTotal === 0) return;
        candyEventGoTo(candyEventCurrent - 1);
        candyEventRestartAutoSlide();
    }

    function candyEventStartAutoSlide() {
        candyEventStopAutoSlide();
        if (candyEventTotal < 2) return;
        candyEventTimer = setInterval(function () {
            candyEventNext();
            candyEventSyncNotes();
        }, 4000);
    }

    function candyEventRestartAutoSlide() {
        if (candyEventTimer) clearInterval(candyEventTimer);
        candyEventStartAutoSlide();
    }

    function candyEventStopAutoSlide() {
        if (candyEventTimer) {
            clearInterval(candyEventTimer);
            candyEventTimer = null;
        }
    }

    function candyPlanEvent() {
        candyShowToast("Membuka formulir pemesanan event Nectar! 🎉");
    }

    (function () {
        candyEventBuildSlides();
        if (candyEventTotal >= 2) candyEventStartAutoSlide();
        candyEventSyncNotes();
    })();

    document.addEventListener('DOMContentLoaded', function () {
        candyEventBuildSlides();
        if (candyEventTotal >= 2) candyEventStartAutoSlide();
        candyEventSyncNotes();
    });

    var _candyTouchStartX = null;
    var _candyTouchStartTime = null;
    var slideEl = null;
    document.addEventListener('touchstart', function (e) {
        slideEl = e.target.closest('#candy-event-slide-track');
        if (!slideEl) return;
        _candyTouchStartX = e.touches[0].clientX;
        _candyTouchStartTime = Date.now();
    }, { passive: true });
    document.addEventListener('touchend', function (e) {
        if (!_candyTouchStartX || !slideEl || !e.changedTouches) return;
        var diffX = e.changedTouches[0].clientX - _candyTouchStartX;
        var elapsed = Date.now() - _candyTouchStartTime;
        _candyTouchStartX = null;
        slideEl = null;
        if (elapsed > 500) return;
        if (Math.abs(diffX) > 40) {
            if (diffX < 0) {
                candyEventNext();
            } else {
                candyEventPrev();
            }
        }
    }, { passive: true });

    document.addEventListener('mouseover', function (e) {
        if (e.target.closest && e.target.closest('#candy-event-slide-track, .candy-event-dot, [aria-label="Previous slide"], [aria-label="Next slide"]')) {
            candyEventStopAutoSlide();
        }
    }, { passive: true });

    document.addEventListener('mouseout', function (e) {
        if (!e.relatedTarget || !e.target.closest('#candy-event-slide-track, .candy-event-dot, [aria-label="Previous slide"], [aria-label="Next slide"]')) {
            candyEventRestartAutoSlide();
        }
    }, { passive: true });

</script>
