{{--
    EMPIRE — CATALOGUE FULL
    Varian dari template.empire.catalogue dengan layout berbeda:
      - tanpa sidebar filter di kiri (grid full width)
      - 6 produk per baris pada layar besar
      - saat gambar di-hover, gambar diganti dengan gambar produk berikutnya
        (kolom `images` di tabel products, fallback: zoom saja bila hanya 1 gambar)
      - kategori dipilih lewat tab underline di atas grid
      - ada sorting by price yang benar-benar bekerja (client side)
      - ada kotak search di pojok kanan atas, pencariannya pakai jeda (debounce)
        5 detik — bukan langsung saat keyup
--}}
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
    $subtitle = $content['subtitle'] ?? $content['subtitle_en'] ?? '';
    $title_color = $content['title_color'] ?? '#000000';
    $subtitle_color = $content['subtitle_color'] ?? '#6b7280';
    $button_color = $content['button_color'] ?? $website->button_color ?? '#000000';
    $button_text_color = $content['button_text_color'] ?? $website->button_text_color ?? '#ffffff';

    $formatHex = function ($color, $default) {
        if (!is_string($color) || trim($color) === '') return $default;
        $color = trim($color);
        return str_starts_with($color, '#') || str_starts_with($color, 'rgb') ? $color : '#' . $color;
    };

    $title_color = $formatHex($title_color, '#000000');
    $subtitle_color = $formatHex($subtitle_color, '#6b7280');
    $button_color = $formatHex($button_color, '#000000');
    $button_text_color = $formatHex($button_text_color, '#ffffff');

    $categories = $categories ?? collect();
    $products = $products ?? collect();

    // Resolver path gambar produk: mendukung path relatif storage ("products/xxx.png")
    // maupun nama file yang di-upload ke images/website/{domain}.
    $resolveProductImage = function ($file) use ($domain) {
        if (!is_string($file) || $file === '') {
            return null;
        }

        return str_contains($file, '/') || str_contains($file, '\\')
            ? asset('storage/' . $file)
            : asset('images/website/' . $domain . '/' . $file);
    };
@endphp

<style>
    .catalogue-full .cf-tab {
        border-bottom: 2px solid transparent;
        color: {{ $title_color }};
        opacity: 0.7;
        transition: color .2s ease, border-color .2s ease, opacity .2s ease;
        white-space: nowrap;
    }
    .catalogue-full .cf-tab:hover {
        color: {{ $title_color }};
        opacity: 1;
    }
    .catalogue-full .cf-tab.is-active {
        color: {{ $title_color }};
        border-bottom-color: {{ $title_color }};
        opacity: 1;
    }
    .catalogue-full .cf-tabs-scroll {
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .catalogue-full .cf-tabs-scroll::-webkit-scrollbar {
        display: none;
    }
    .catalogue-full .cf-search::placeholder {
        color: {{ $title_color }};
        opacity: 0.65;
    }
    .catalogue-full .cf-search::-webkit-input-placeholder {
        color: {{ $title_color }};
        opacity: 0.65;
    }
    .catalogue-full .cf-search::-moz-placeholder {
        color: {{ $title_color }};
        opacity: 0.65;
    }
    .catalogue-full .cf-search:-ms-input-placeholder {
        color: {{ $title_color }};
        opacity: 0.65;
    }
</style>

<section class="catalogue-full bg-white py-12 px-6 sm:px-14 md:px-18 lg:px-24 xl:px-18 text-black" data-catalogue-full>
    <div class="max-w-[1600px] mx-auto">

        <!-- 1. HEADER: judul (kiri di desktop, center di mobile) + sort & search (kanan) -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 border-b border-gray-200 pb-6">
            <div class="w-full md:w-auto max-w-xl text-center md:text-left">
                <h1 class="font-bold leading-tight text-center md:text-left"
                    style="font-size:2.25rem;line-height:1.2;color:{{ $title_color }};">{{ $title }}</h1>
                @if(!empty($subtitle))
                    <p class="mt-2 text-center md:text-left"
                        style="font-size:0.95rem;line-height:1.5;color:{{ $subtitle_color }};">{{ $subtitle }}</p>
                @endif
            </div>

            <div class="flex flex-col md:items-end gap-2 w-full md:w-auto">
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full md:w-auto">
                    {{-- SORT BY (sebelah kiri search di desktop, align right di mobile) --}}
                    <div class="flex items-center justify-end sm:justify-start w-full sm:w-auto order-1 sm:order-1">
                        <label class="flex items-center gap-2 text-xs">
                            <span class="whitespace-nowrap" style="color:{{ $title_color }};">Sort by</span>
                            <select class="cf-sort border rounded-full bg-transparent px-3 py-2 text-xs font-medium focus:outline-none focus:border-black cursor-pointer transition"
                                style="color:{{ $title_color }}; border-color:{{ $title_color }};">
                                <option value="relevance" class="text-black">Relevance</option>
                                <option value="price-asc" class="text-black">Price: Low to High</option>
                                <option value="price-desc" class="text-black">Price: High to Low</option>
                            </select>
                        </label>
                    </div>

                    {{-- SEARCH (pojok kanan atas di desktop, di atas sort pada mobile) --}}
                    <div class="relative w-full sm:w-64 order-2 sm:order-2">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none"
                            style="color:{{ $title_color }};"
                            fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                        <input type="search" class="cf-search w-full rounded-full border bg-transparent pl-9 pr-4 py-2 text-xs focus:outline-none focus:border-black transition"
                            style="color:{{ $title_color }}; border-color:{{ $title_color }};"
                            placeholder="Search product..." autocomplete="off">
                    </div>
                </div>

                <div class="text-xs text-gray-500 text-right w-full">
                    <p class="cf-search-pending hidden text-[11px] text-gray-400">Menunggu jeda 5 detik...</p>
                </div>
            </div>
        </div>

        <!-- 2. KATEGORI (tab underline - SWIPEABLE ON MOBILE, CENTERED ON DESKTOP) -->
        <div class="cf-tabs-scroll w-full overflow-x-auto mt-6 mb-8 pb-1">
            <div class="flex items-center justify-start md:justify-center gap-5 sm:gap-6 min-w-max mx-auto px-2 md:px-0">
                <button type="button" data-filter="all"
                    style="color: {{ $title_color }};"
                    class="cf-tab is-active text-xs font-semibold uppercase tracking-wider pb-2">All</button>

                @if(isset($categories) && count($categories) > 0)
                    @foreach ($categories as $category)
                        @php
                            $cId = is_array($category) ? ($category['id'] ?? $category['kode'] ?? '') : $category->id;
                            $cName = is_array($category) ? ($category['name'] ?? '') : $category->name;
                        @endphp
                        <button type="button" data-filter="{{ $cId }}"
                            style="color: {{ $title_color }};"
                            class="cf-tab text-xs font-semibold uppercase tracking-wider pb-2">{{ $cName }}</button>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- 3. PRODUK (CENTERED) -->
        <div class="cf-grid flex flex-wrap justify-center gap-x-4 gap-y-8">

            @if(isset($products) && count($products) > 0)
                @foreach ($products as $product)
                    @php
                        $pName = is_array($product) ? ($product['name'] ?? '') : $product->name;
                        $pPrice = is_array($product) ? ($product['price'] ?? 0) : $product->price;
                        $pCatId = is_array($product) ? ($product['category_products_id'] ?? $product['kode'] ?? '') : $product->category_products_id;

                        // Nama kategori (dipakai untuk badge + data-category pada card)
                        $pCatName = '';
                        if (isset($categories)) {
                            foreach ($categories as $cat) {
                                $cId = is_array($cat) ? ($cat['id'] ?? $cat['kode'] ?? '') : $cat->id;
                                if ($cId == $pCatId) {
                                    $pCatName = is_array($cat) ? ($cat['name'] ?? '') : $cat->name;
                                    break;
                                }
                            }
                        }



                        // Semua gambar produk (kolom images berisi JSON array).
                        $rawImages = is_array($product) ? ($product['images'] ?? $product['image'] ?? null) : $product->images;
                        if (is_string($rawImages)) {
                            $decoded = json_decode($rawImages, true);
                            $imgList = is_array($decoded) ? $decoded : [$rawImages];
                        } elseif (is_array($rawImages)) {
                            $imgList = $rawImages;
                        } else {
                            $imgList = [];
                        }
                        $imgList = array_values(array_filter($imgList, fn ($img) => is_string($img) && $img !== ''));

                        $image = $resolveProductImage($imgList[0] ?? null) ?? asset('images/default/broken.png');

                        // Gambar kedua = gambar yang muncul saat hover.
                        $hoverImage = $resolveProductImage($imgList[1] ?? null);
                        if ($hoverImage === $image) {
                            $hoverImage = null;
                        }

                        $numericPrice = (float) $pPrice;
                    @endphp

                    <article class="cf-card group relative flex flex-col space-y-3 cursor-pointer w-[calc(50%-0.5rem)] sm:w-[calc(33.333%-0.67rem)] md:w-[calc(25%-0.75rem)] lg:w-[calc(16.666%-0.85rem)] max-w-[260px]"
                        data-category="{{ $pCatId }}"
                        data-category-name="{{ $pCatName }}"
                        data-name="{{ $pName }}"
                        data-price="{{ $numericPrice }}">

                        <div class="relative w-full aspect-[3/4] rounded-xl bg-[#f6f6f6] overflow-hidden">
                            <!-- @if($pCatName)
                                <span
                                    class="absolute top-3 left-3 z-20 bg-black/85 backdrop-blur-sm text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $pCatName }}</span>
                            @endif -->

                            {{-- Image swap: gambar utama + gambar kedua yang fade-in saat hover --}}
                            <div class="absolute inset-0 transition-transform duration-700 ease-out group-hover:scale-110">
                                <img src="{{ $image }}"
                                    alt="{{ $pName }}"
                                    class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 {{ $hoverImage ? 'group-hover:opacity-0' : '' }}" />

                                @if($hoverImage)
                                    <img src="{{ $hoverImage }}"
                                        alt="{{ $pName }} — gambar kedua"
                                        loading="lazy"
                                        class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-500 group-hover:opacity-100" />
                                @endif
                            </div>

                            <!-- Hover Add to Cart Button (Desktop) -->
                            <button type="button"
                                onclick="event.stopPropagation(); addToCart('{{ addslashes($pName) }}', {{ $numericPrice }}, '{{ $image }}')"
                                style="background-color: {{ $button_color }}; color: {{ $button_text_color }};"
                                class="hidden md:flex items-center justify-center gap-1.5 absolute bottom-3 left-3 right-3 z-20 py-2 rounded-lg text-[10px] font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                <svg class="w-3.5 h-3.5" style="color: {{ $button_text_color }}; stroke: {{ $button_text_color }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>Add To Cart</span>
                            </button>
                        </div>

                        <div class="space-y-1.5 text-center">
                            <h3 class="text-xs font-medium line-clamp-1" style="color: {{ $title_color }};">{{ $pName }}</h3>
                            <p class="text-xs" style="color: {{ $subtitle_color }};">Rp {{ number_format($numericPrice, 0, ',', '.') }}</p>

                            <!-- Mobile Add to Cart Button (di bawah harga) -->
                            <button type="button"
                                onclick="event.stopPropagation(); addToCart('{{ addslashes($pName) }}', {{ $numericPrice }}, '{{ $image }}')"
                                aria-label="Add to Cart"
                                style="background-color: {{ $button_color }}; color: {{ $button_text_color }};"
                                class="md:hidden mt-2 w-full py-2 rounded-lg flex items-center justify-center shadow-sm active:scale-95 transition">
                                <svg class="w-4 h-4" style="color: {{ $button_text_color }}; stroke: {{ $button_text_color }};" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </button>
                        </div>
                    </article>
                @endforeach
            @endif

        </div>

        <!-- Empty state (dipakai saat filter kategori tidak menemukan produk) -->
        <p class="cf-empty hidden text-center text-sm text-gray-500 py-16">Tidak ada produk yang cocok.</p>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tiap section punya scope sendiri (section bisa dipasang lebih dari sekali
        // di satu halaman), jadi semua query dibatasi ke root section-nya.
        document.querySelectorAll('[data-catalogue-full]').forEach(function (root) {
            const tabs = root.querySelectorAll('.cf-tab');
            const cards = Array.from(root.querySelectorAll('.cf-card'));
            const grid = root.querySelector('.cf-grid');
            const sortSelect = root.querySelector('.cf-sort');
            const resultCount = root.querySelector('.cf-result-count');
            const emptyState = root.querySelector('.cf-empty');
            const searchInput = root.querySelector('.cf-search');
            const searchPending = root.querySelector('.cf-search-pending');

            // Jeda proses pencarian (debounce) dalam milidetik.
            const SEARCH_DEBOUNCE_MS = 5000;

            // Query yang sudah "committed" (bukan isi input yang sedang diketik).
            const state = { query: '' };

            function activeFilters() {
                return Array.from(tabs)
                    .filter(tab => tab.classList.contains('is-active') && tab.dataset.filter !== 'all')
                    .map(tab => String(tab.dataset.filter));
            }

            function matchesSearch(card) {
                if (!state.query) return true;

                const haystack = ((card.dataset.name || '') + ' ' + (card.dataset.categoryName || '')).toLowerCase();

                return haystack.includes(state.query);
            }

            function applyFilters() {
                const filters = activeFilters();
                let visible = 0;

                cards.forEach(function (card) {
                    const matchTab = filters.length === 0 || filters.includes(String(card.dataset.category));
                    const show = matchTab && matchesSearch(card);
                    card.classList.toggle('hidden', !show);
                    if (show) visible++;
                });

                if (resultCount) resultCount.textContent = visible;
                if (emptyState) emptyState.classList.toggle('hidden', visible !== 0);
            }

            function applySort() {
                if (!grid || !sortSelect) return;

                const mode = sortSelect.value;
                // 'relevance' = urutan asli dari server (index awal card).
                const sorted = cards.slice().sort(function (a, b) {
                    if (mode === 'relevance') {
                        return Number(a.dataset.order) - Number(b.dataset.order);
                    }

                    const priceA = parseFloat(a.dataset.price || '0');
                    const priceB = parseFloat(b.dataset.price || '0');

                    return mode === 'price-asc' ? priceA - priceB : priceB - priceA;
                });

                sorted.forEach(function (card) {
                    grid.appendChild(card);
                });
            }

            // Simpan urutan awal sebagai acuan mode "Relevance".
            cards.forEach(function (card, index) {
                card.dataset.order = index;
            });

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    if (tab.dataset.filter === 'all') {
                        tabs.forEach(other => other.classList.toggle('is-active', other === tab));
                    } else {
                        tab.classList.toggle('is-active');

                        const allTab = Array.from(tabs).find(other => other.dataset.filter === 'all');
                        const anyCategoryActive = Array.from(tabs)
                            .some(other => other.dataset.filter !== 'all' && other.classList.contains('is-active'));

                        if (allTab) allTab.classList.toggle('is-active', !anyCategoryActive);
                    }

                    applyFilters();
                });
            });

            if (sortSelect) {
                sortSelect.addEventListener('change', applySort);
            }

            // SEARCH: event 'input' + debounce 5 detik. Pencarian TIDAK dijalankan
            // saat keyup, tapi 5 detik setelah ketikan terakhir berhenti.
            let searchTimer = null;
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    if (searchPending) searchPending.classList.remove('hidden');

                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(function () {
                        state.query = searchInput.value.trim().toLowerCase();
                        applyFilters();
                        if (searchPending) searchPending.classList.add('hidden');
                    }, SEARCH_DEBOUNCE_MS);
                });
            }

            applyFilters();
        });
    });
</script>
