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

    $desc = $content['description'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

    $image = !empty($content['image']) ? 'images/website/' . $domain . '/' . $content['image'] : 'images/default/broken.png';
@endphp

 <footer class="w-full bg-[{{ $background_color }}] text-[{{ $title_color }}]">

    <!-- Main Footer Links Content -->
    <div class="max-w-7xl mx-auto py-12 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">

            <!-- Brand & Contact (2 Columns Width on LG) -->
            <div class="lg:col-span-2 space-y-4">
                <h2 class="text-2xl font-bold tracking-tight text-[{{ $title_color }}] mb-6">Jolie</h2>

                <p class="text-xs text-[{{ $subtitle_color }}] leading-relaxed">Calista Wise 7292 Dictum Av. Antonio, Italy.</p>
                <p class="text-xs text-[{{ $subtitle_color }}] font-medium">(+01)-800-3456-88</p>
                <p class="text-xs text-[{{ $subtitle_color }}]">aloshopify@alothemes.com</p>
                <p class="text-xs text-[{{ $subtitle_color }}]">jolie.alotheme.com</p>

                <!-- Social Media Icons -->
                <div class="flex items-center gap-2 pt-2">
                    <!-- X / Twitter -->
                    <a href="#"
                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-xs text-[{{ $subtitle_color }}] hover:bg-black hover:text-white hover:border-black transition">
                        x
                    </a>
                    <!-- Facebook -->
                    <a href="#"
                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-xs text-[{{ $subtitle_color }}] hover:bg-black hover:text-white hover:border-black transition">
                        f
                    </a>
                    <!-- Instagram -->
                    <a href="#"
                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-xs text-[{{ $subtitle_color }}] hover:bg-black hover:text-white hover:border-black transition">
                        i
                    </a>
                    <!-- TikTok -->
                    <a href="#"
                        class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center text-xs text-[{{ $subtitle_color }}] hover:bg-black hover:text-white hover:border-black transition">
                        t
                    </a>
                </div>
            </div>

            <!-- Useful Links -->
            <div>
                <h3 class="text-sm font-bold text-[{{ $title_color }}] mb-4">Menu</h3>
                <ul class="space-y-2.5 text-xs text-[{{ $subtitle_color }}]">
                     @foreach($footerPresets['footer_menu'] ?? [] as $menu)
                        @php
                            $menuUrl = $menu['url'] ?? '#';
                            if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                                $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                            }
                        @endphp
                        <li><a href="{{ $menuUrl }}" class="hover:text-black transition">{{ $menu['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Customer Service -->
            <div>
                <h3 class="text-sm font-bold text-[{{ $title_color }}] mb-4">Customer Service</h3>
                <ul class="space-y-2.5 text-xs text-[{{ $subtitle_color }}]">
                    <li><a href="#" class="hover:text-black transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-black transition">Refund Policy</a></li>
                    <li><a href="#" class="hover:text-black transition">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-black transition">Theme FAQs</a></li>
                    <li><a href="#" class="hover:text-black transition">Store Locations</a></li>
                    <li><a href="#" class="hover:text-black transition">Shipping & Return</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h3 class="text-sm font-bold text-[{{ $title_color }}] mb-4">{{ $title }}</h3>
                <p class="text-xs text-[{{ $subtitle_color }}] mb-4 leading-relaxed">
                   {{ $subtitle }}
                </p>

                <form class="space-y-3">
                    <div
                        class="flex items-center bg-gray-50 border border-gray-200 rounded-full p-1 focus-within:border-gray-400 transition">
                        <input type="email" placeholder="Enter your email here"
                            class="w-full bg-transparent px-4 py-2 text-xs text-gray-800 placeholder-gray-400 outline-none"
                            required />
                        <button type="submit"
                            class="bg-[{{ $button_color }}] text-[{{ $button_text_color }}] text-xs font-medium px-5 py-2.5 rounded-full hover:bg-black transition shrink-0">
                            {{ $button_text }}
                        </button>
                    </div>
                </form>

                <p class="text-[11px] text-[{{ $subtitle_color }}] mt-3 leading-relaxed">
                   {{ $desc }}
                </p>
            </div>

        </div>
    </div>

    <!-- Bottom Copyright Bar -->
    <div class="border-t border-gray-100 py-6 px-4">
        <div class="max-w-7xl mx-auto text-xs text-[{{ $title_color }}]">
            Copyright © <span class="text-[{{ $subtitle_color }}]">Rolin</span>. 2026
        </div>
    </div>

</footer>