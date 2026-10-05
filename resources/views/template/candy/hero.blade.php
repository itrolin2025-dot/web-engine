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

    $tag = $content['tag_en'] ?? $content['tag'] ?? '(Based in Pittsburgh, PA)';
    $tag_color = $content['tag_color'] ?? '#4a1525b3';

    $title = $content['title_en'] ?? $content['title'] ?? 'The Swedish Candy Cart of Your Dreams';
    $title_color = $content['title_color'] ?? '#4a1525';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? 'Your go-to cart to call for events and catering, monthly candy club deliveries, online candy fixes, and more.';
    $subtitle_color = $content['subtitle_color'] ?? '#4a1525cc';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? 'Join the Club';
    $button_color = $content['button_color'] ?? '#c2b2f0';
    $button_text_color = $content['button_text_color'] ?? '#4a1525';

    $background_color = $content['background_color'] ?? '#fcf8f5';

    $image = !empty($content['image']) && $content['image'] !== 'your image'
        ? 'images/website/' . $domain . '/' . $content['image']
        : 'images/default/broken.png';
@endphp

<style>
    /* Grid Background Pattern */
    .candy-grid-bg {
        background-image:
            linear-gradient(to right, rgba(230, 180, 190, 0.25) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(230, 180, 190, 0.25) 1px, transparent 1px);
        background-size: 32px 32px;
    }

    /* Floating animations for decorative elements */
    @keyframes candyFloatSlow {

        0%,
        100% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-8px) rotate(3deg);
        }
    }

    .candy-animate-float {
        animation: candyFloatSlow 4s ease-in-out infinite;
    }
</style>

<section id="buy"
    class="candy-grid-bg py-12 md:py-20 selection:bg-pink-200"
    style="background-color: {{ $background_color }};">
    <main class="max-w-7xl mx-auto w-full px-6 md:px-12 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">

        <!-- Left Column: Content & Typography -->
        <div class="lg:col-span-6 flex flex-col justify-center space-y-6 relative">

            <!-- Subtitle based in Pittsburgh -->
            <div class="text-xs uppercase tracking-widest font-bold" style="color: {{ $tag_color }};">
                {{ $tag }}
            </div>

            <!-- Main Heading -->
            <h1 class="candy-font-serif italic font-bold text-4xl sm:text-5xl lg:text-6xl leading-tight"
                style="color: {{ $title_color }};">
                {{ $title }}
            </h1>

            <!-- Description -->
            <p class="text-sm md:text-base max-w-md leading-relaxed font-normal" style="color: {{ $subtitle_color }};">
                {{ $subtitle }}
            </p>

            <!-- Call to Action Button & Curved Arrow -->
            <div class="pt-4 flex items-center space-x-6 relative">
                <button onclick="candyJoinClubAction()"
                    class="text-xs font-bold uppercase tracking-wider py-3.5 px-7 rounded-full shadow-md transition-all transform hover:-translate-y-0.5 active:translate-y-0"
                    style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                    {{ $button_text }}
                </button>

                <!-- Decorative Curved Arrow SVG pointing to the cart -->
                <div class="hidden sm:block absolute left-48 top-8 animate-pulse" style="color: {{ $title_color }};">
                    <svg width="110" height="60" viewBox="0 0 120 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 10C30 50 80 50 95 30M95 30L80 20M95 30L85 42" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>

            <!-- Small gummy bear decoration floating -->
            <div
                class="absolute -bottom-10 left-12 candy-animate-float text-2xl select-none pointer-events-none hidden md:block">
                🧸
            </div>
        </div>

        <!-- Right Column: Distinct Candy Cart Card & Background Elements -->
        <div class="lg:col-span-6 flex justify-center items-center relative">

            <!-- Card Container with distinct background styling & soft shadow -->
            <div
                class="relative bg-white/70 backdrop-blur-md p-6 sm:p-8 rounded-3xl shadow-xl border border-pink-200/60 w-full max-w-lg group hover:shadow-2xl transition-all duration-300">

                <!-- Candy Cart Image with fallback -->
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-pink-50 to-pink-100 p-2">
                    <img src="{{ asset($image) }}" alt="{{ $title }}"
                        onerror="this.onerror=null;this.src='https://placehold.co/600x400/fde2e4/4a1525?text=Nectar+Candy+Cart'"
                        class="w-full h-auto object-cover rounded-xl transform group-hover:scale-105 transition-transform duration-500">
                </div>

                <!-- Handwritten text overlay: catch me if you candy! -->
                <div
                    class="absolute -bottom-6 -right-2 sm:-right-6 bg-pink-100/90 backdrop-blur-sm border border-pink-300 px-4 py-2 rounded-2xl shadow-md rotate-3 candy-animate-float">
                    <span class="candy-font-script text-xl text-[#c73e5f] font-bold tracking-wide">
                        catch me if you candy!
                    </span>
                </div>

                <!-- Floating Cherry Icon attached near top right of cart card -->
                <div class="absolute -top-4 -right-3 text-3xl candy-animate-float select-none pointer-events-none">
                    🍒
                </div>
            </div>

            <!-- Background decorative sweets -->
            <div class="absolute -top-10 left-4 candy-animate-float text-3xl select-none pointer-events-none">
                🍬
            </div>
            <div class="absolute -bottom-8 right-12 candy-animate-float text-3xl select-none pointer-events-none"
                style="animation-delay: 2s;">
                🍓
            </div>
        </div>
    </main>
</section>
