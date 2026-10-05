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

    // Paket harga dari repeater (label = nominal, title = keterangan)
    $repeater = $content['repeater'] ?? null;
    if (!is_array($repeater) || empty($repeater)) {
        $repeater = [
            ['label' => '$399', 'title' => 'CART FEE (3HRS)', 'sort' => '1'],
            ['label' => '$8', 'title' => 'PER GUEST', 'sort' => '2'],
            ['label' => '$99', 'title' => 'PER ADDED HR', 'sort' => '3'],
        ];
    }
    $repeater = collect($repeater)->sortBy('sort')->values()->all();
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
                <div class="text-[11px] sm:text-xs uppercase font-bold tracking-[0.2em]" style="color: {{ $tag_color }};">
                    {{ $tag }}
                </div>

                <!-- Main Heading -->
                <h1 class="candy-font-serif italic text-4xl sm:text-5xl md:text-6xl lg:text-7xl leading-[0.95] tracking-tight"
                    style="color: {{ $title_color }};">
                    {!! nl2br(e($title)) !!}
                </h1>

                <!-- Description Paragraph -->
                <p class="text-sm sm:text-base font-normal leading-relaxed max-w-lg" style="color: {{ $subtitle_color }};">
                    {{ $subtitle }}
                </p>

                <!-- Price Badges in a Row -->
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    @foreach ($repeater as $index => $item)
                        <div
                            class="{{ $badgeClasses[$index % count($badgeClasses)] }} w-28 h-28 flex flex-col items-center justify-center text-center p-2 transform {{ $badgeRotations[$index % count($badgeRotations)] }} hover:rotate-0 transition-transform {{ ($index % count($badgeClasses)) === 1 ? 'text-white' : 'text-[#4a1525]' }}">
                            <span class="candy-font-serif font-bold text-2xl">{{ $item['label'] ?? '' }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider leading-tight mt-0.5">{!! nl2br(e(strtoupper($item['title'] ?? ''))) !!}</span>
                        </div>
                    @endforeach
                </div>

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

                <!-- Container for Tilted Polaroid Frame & Stickers -->
                <div class="relative max-w-md w-full transform rotate-1 hover:rotate-0 transition-transform duration-500">

                    <!-- Top-Left Peach Sticker Badge ("designed to delight!") -->
                    <div
                        class="absolute -top-6 -left-6 z-30 bg-[#fce4d6] border-2 border-[#b54530] px-4 py-2 rounded-full transform -rotate-12 shadow-md flex items-center space-x-1">
                        <span class="candy-font-script text-xs sm:text-sm font-bold text-[#b54530] tracking-wide">designed to
                            delight!</span>
                    </div>

                    <!-- Red Sticky Tape at Top-Right -->
                    <div class="absolute -top-3 right-12 z-30 w-24 h-8 bg-[#c73e5f]/90 transform rotate-6 shadow-sm"></div>

                    <!-- White Polaroid / Photo Frame -->
                    <div class="bg-white p-4 sm:p-5 rounded-sm shadow-2xl border border-gray-200 relative">

                        <!-- Image Display Area with Switcher Capability -->
                        <div class="relative overflow-hidden rounded bg-pink-50">
                            <img id="candy-active-event-image" src="{{ asset($image) }}" alt="{{ $title }}"
                                onerror="this.onerror=null;this.src='https://placehold.co/600x450/fde2e4/4a1525?text=Nectar+Event'"
                                class="w-full h-[320px] sm:h-[380px] object-cover rounded">
                        </div>

                        <!-- Image Switcher Navigation Bar inside the frame -->
                        <div class="flex items-center justify-center space-x-3 mt-3 pt-2 border-t border-gray-100">
                            <span class="text-[10px] uppercase font-bold text-gray-400">Pilih Tampilan:</span>
                            <button onclick="candySwitchEventImage(1)"
                                class="w-3 h-3 rounded-full bg-[#c73e5f] focus:outline-none ring-2 ring-offset-1 ring-[#c73e5f]"
                                id="candy-event-dot-1" title="Tampilan 1"></button>
                            <button onclick="candySwitchEventImage(2)"
                                class="w-3 h-3 rounded-full bg-gray-300 focus:outline-none ring-2 ring-offset-1 ring-gray-300"
                                id="candy-event-dot-2" title="Tampilan 2"></button>
                        </div>
                    </div>

                    <!-- Red Sticky Tape at Bottom-Left -->
                    <div class="absolute -bottom-3 left-8 z-30 w-28 h-8 bg-[#c73e5f]/90 transform -rotate-12 shadow-sm"></div>

                    <!-- Bottom-Right Tag: "Candy Curated By: Your Tastebuds" -->
                    <div
                        class="absolute -bottom-8 -right-4 sm:-right-8 z-30 bg-[#fffefc] border-2 border-dashed border-[#b54530] p-3 rounded shadow-lg transform rotate-6 max-w-[200px]">
                        <div class="text-[10px] text-[#4a1525]/70 font-bold uppercase tracking-wider">Candy Curated By:
                        </div>
                        <div class="candy-font-script text-xl font-bold text-[#c73e5f]">Your Tastebuds</div>

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
    var candyEventImages = ['{{ asset($image) }}'];

    function candySwitchEventImage(index) {
        var imgElement = document.getElementById('candy-active-event-image');
        var dot1 = document.getElementById('candy-event-dot-1');
        var dot2 = document.getElementById('candy-event-dot-2');
        if (!imgElement) return;

        var src = candyEventImages[0];
        if (index === 2 && candyEventImages[1]) {
            src = candyEventImages[1];
        }

        imgElement.src = src;
        if (dot1 && dot2) {
            var activeCls = 'w-3 h-3 rounded-full bg-[#c73e5f] focus:outline-none ring-2 ring-offset-1 ring-[#c73e5f]';
            var idleCls = 'w-3 h-3 rounded-full bg-gray-300 focus:outline-none ring-2 ring-offset-1 ring-gray-300';
            dot1.className = index === 1 ? activeCls : idleCls;
            dot2.className = index === 2 ? activeCls : idleCls;
        }
    }

    function candyPlanEvent() {
        candyShowToast("Membuka formulir pemesanan event Nectar! 🎉");
    }
</script>
