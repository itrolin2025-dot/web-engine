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
    $title_color = $content['title_color'] ?? '#ffffff';

    $background_color = $content['background_color'] ?? '#ffffff';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $repeater = $content['repeater'] ?? $content['tagline'];
    if (is_array($repeater)) {
        $repeater = collect($repeater)->sortBy('sort')->values()->all();
    }

    $desc = $content['desc_en'] ?? $content['desc'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    $image = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : 'images/default/broken.png';
@endphp

<footer class="w-full font-sans text-[#1a1a1a] overflow-hidden">

    <!-- BOTTOM: Footer Content Container -->
    <div class="bg-[{{ $background_color }}] pt-20 pb-20 px-4">
        <div class="max-w-7xl mx-auto flex flex-col gap-10">

            <!-- 3 Columns Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center text-center md:text-left">

                <!-- Left Column: Social Media Icons -->
                <div class="flex flex-col items-center md:items-start gap-3">
                    <h4 class="text-xs font-bold text-[{{ $title_color }}] tracking-wider uppercase">
                        Our Social
                    </h4>
                    <div class="flex items-center gap-2">
                        <a href="#" aria-label="Facebook"
                            class="w-8 h-8 rounded-full bg-[{{ $button_color }}] shadow-sm flex items-center justify-center text-[{{ $button_text_color }}] text-xs hover:bg-black hover:text-white transition">f</a>
                        <a href="#" aria-label="Instagram"
                            class="w-8 h-8 rounded-full bg-[{{ $button_color }}] shadow-sm flex items-center justify-center text-[{{ $button_text_color }}] text-xs hover:bg-black hover:text-white transition">i</a>
                        <a href="#" aria-label="Twitter / X"
                            class="w-8 h-8 rounded-full bg-[{{ $button_color }}] shadow-sm flex items-center justify-center text-[{{ $button_text_color }}] text-xs hover:bg-black hover:text-white transition">x</a>
                        <a href="#" aria-label="TikTok"
                            class="w-8 h-8 rounded-full bg-[{{ $button_color }}] shadow-sm flex items-center justify-center text-[{{ $button_text_color }}] text-xs hover:bg-black hover:text-white transition">t</a>
                    </div>
                </div>

                <!-- Center Column: Brand Logo, Navigation Links, & Newsletter -->
                <div class="flex flex-col items-center text-center">
                    <!-- Brand Title -->
                    <h3 class="text-xl md:text-2xl font-bold tracking-[0.25em] text-[{{ $title_color }}] uppercase mb-4">
                        {{ $title }}
                    </h3>

                    <!-- Navigation Links -->
                    <nav
                        class="flex flex-wrap justify-center gap-4 md:gap-6 text-[11px] font-bold text-[{{ $title_color }}] uppercase tracking-wider mb-6">
                        @foreach($content['repeater'] as $item)
                            <a href="#" class="hover:text-black transition">{{ $item['label'] }}</a>
                        @endforeach
                    </nav>
                </div>

                <!-- Right Column: Payment Methods -->
                <div class="flex flex-col items-center md:items-end gap-3">
                    <div class="flex items-center gap-2">
                        <form
                            class="flex w-full max-w-md bg-[{{ $button_color }}] rounded-md overflow-hidden p-1 shadow-sm border border-gray-200"
                            onsubmit="event.preventDefault();">
                            <input type="email" placeholder="Enter your email"
                                class="w-full px-4 py-2 text-xs text-[{{ $button_text_color }}] outline-none placeholder-gray-400"
                                required />
                            <button type="submit"
                                class="bg-[{{ $button_color }}] hover:bg-[{{$background_color}}] text-[{{ $button_text_color }}] text-xs font-bold px-6 py-2.5 rounded transition">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Copyright Text -->
            <div class="text-center pt-12 border-t border-gray-200/60 text-[11px] text-[{{ $title_color }}]">
                Copyright © 2024 Rolin. All Rights Reserved.
            </div>

        </div>
    </div>
</footer>