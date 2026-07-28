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

    $title = $content['title'] ?? $content['title_en'] ?? '';
    $title_color = $content['title_color'] ?? '#ffffff';

    $subtitle = $content['subtitle'] ?? $content['subtitle_en'] ?? '';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

    $desc = $content['desc'] ?? $content['desc_en'] ?? '';
    $desc_color = $content['desc_color'] ?? '#ffffff';

    $button_text = $content['button_text'] ?? $content['button_text_en'] ?? '';
    $button_text_color = $content['button_text_color'] ?? '#FF9B7A';
    $button_color = $content['button_color'] ?? '#ffffff';

@endphp

<footer class="bg-[#1A1A1A] text-white pt-16 pb-12 px-6 md:px-12 lg:px-20">
    <div class="max-w-7xl mx-auto space-y-12">

        <!-- Top Section: Title, Subtitle & Newsletter Form -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-12 border-b border-stone-800 items-start">
            
            <!-- Left: Brand / Title & Subtitle -->
            <div class="lg:col-span-6 space-y-4">
                @if(!empty($title))
                    <h3 class="text-2xl md:text-3xl font-serif-brand text-white font-bold tracking-wide" style="color: {{ $title_color }};">
                        {{ $title }}
                    </h3>
                @endif

                @if(!empty($subtitle))
                    <p class="text-base md:text-lg font-normal leading-relaxed text-stone-300 max-w-lg" style="color: {{ $subtitle_color }};">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>

            <!-- Right: Newsletter Subscription -->
            <div class="lg:col-span-6 space-y-3">
                <form action="#" method="POST" class="flex flex-col sm:flex-row gap-3 w-full">
                    <input type="email" 
                        placeholder="{{ !empty($desc) ? $desc : 'Enter your email' }}" 
                        required
                        class="flex-1 bg-stone-900 border border-stone-700 text-white placeholder-stone-400 px-5 py-3.5 rounded-full text-sm outline-none focus:border-stone-500 transition-colors">
                    
                    <button type="submit" 
                        class="px-8 py-3.5 rounded-full text-sm font-semibold transition-all shrink-0 shadow-md bg-white text-stone-900 hover:bg-stone-100 border border-stone-200 cursor-pointer">
                        {{ !empty($button_text) ? $button_text : 'Subscribe' }}
                    </button>
                </form>
            </div>
        </div>

        <!-- Middle Section: Navigation Links -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 py-4">
            
            <div class="space-y-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-stone-300">Navigation</h4>
                <ul class="space-y-2.5 text-sm text-stone-400">
                    <li><a href="#" class="hover:text-white transition-colors">Home</a></li>
                    <li><a href="#about" class="hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#products" class="hover:text-white transition-colors">Products</a></li>
                    <li><a href="#collections" class="hover:text-white transition-colors">Collections</a></li>
                </ul>
            </div>

            <div class="space-y-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-stone-300">Customer Care</h4>
                <ul class="space-y-2.5 text-sm text-stone-400">
                    <li><a href="#" class="hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Shipping & Returns</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Store Locator</a></li>
                </ul>
            </div>

            <div class="space-y-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-stone-300">Legal</h4>
                <ul class="space-y-2.5 text-sm text-stone-400">
                    <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Terms of Service</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Cookie Policy</a></li>
                </ul>
            </div>

            <div class="space-y-4">
                <h4 class="text-sm font-bold uppercase tracking-wider text-stone-300">Social Media</h4>
                <div class="flex gap-4 text-stone-400">
                    <a href="#" class="w-10 h-10 rounded-full bg-stone-900 border border-stone-800 flex items-center justify-center hover:bg-stone-800 hover:text-white transition-colors" aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-stone-900 border border-stone-800 flex items-center justify-center hover:bg-stone-800 hover:text-white transition-colors" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-stone-900 border border-stone-800 flex items-center justify-center hover:bg-stone-800 hover:text-white transition-colors" aria-label="TikTok">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>
                </div>
            </div>

        </div>

        <!-- Bottom Section: Copyright -->
        <div class="pt-8 border-t border-stone-800/80 flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-stone-500">
            <p>&copy; {{ date('Y') }} {{ $website->title ?? 'Company' }}. All rights reserved.</p>
            <p>Designed for excellence.</p>
        </div>

    </div>
</footer>