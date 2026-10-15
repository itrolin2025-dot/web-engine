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
    $title_color = $content['title_color'] ?? '#ffffff';
    $subtitle_color = $content['subtitle_color'] ?? '#ffffff';

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
        color: #6b7280;
        transition: color .2s ease, border-color .2s ease;
        white-space: nowrap;
    }
    .catalogue-full .cf-tab:hover { color: #111827; }
    .catalogue-full .cf-tab.is-active {
        color: #111827;
        border-bottom-color: #111827;
    }
</style>

<section class="catalogue-full bg-white py-12 px-6 lg:px-12 text-black" data-catalogue-full>
    <div class="max-w-[1600px] mx-auto">

        <!-- 1. HEADER: judul (kiri) + info hasil (kanan) -->
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 border-b border-gray-200 pb-6">
            <div class="max-w-2xl">
                <h1 class="font-bold leading-tight"
                    style="font-size:2.25rem;line-height:1.2;color:{{ $title_color }};">{{ $title }}</h1>
                @if(!empty($subtitle))
                    <p class="mt-2"
                        style="font-size:0.95rem;line-height:1.5;color:{{ $subtitle_color }};">{{ $subtitle }}</p>
                @endif
            </div>

            <div class="flex flex-col md:items-end gap-3 w-full md:w-auto">
                {{-- SEARCH (pojok kanan atas) — pencarian baru diproses 5 detik
                     setelah ketikan terakhir, jadi tidak memfilter tiap keyup. --}}
                <div class="relative w-full md:w-64">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                        fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                    </svg>
                    <input type="search" class="cf-search w-full rounded-full border border-gray-300 bg-transparent pl-9 pr-4 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:border-black transition"
                        placeholder="Search product..." autocomplete="off">
                </div>

                <div class="text-xs text-gray-500 md:text-right space-y-1">
                    <p><span class="cf-result-count font-semibold text-black">{{ count($products) }}</span> product(s)</p>
                    <p class="cf-search-pending hidden text-[11px] text-gray-400">Menunggu jeda 5 detik...</p>
                    <p class="hidden md:block">Hover produk untuk melihat gambar lainnya</p>
                </div>
            </div>
        </div>

        <!-- 2. KATEGORI (tab underline) + SORTING -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mt-6 mb-8">
            <div class="flex items-center gap-6 overflow-x-auto pb-1 -mb-px">
                <button type="button" data-filter="all"
                    class="cf-tab is-active text-xs font-semibold uppercase tracking-wider pb-2">All</button>

                @if(isset($categories) && count($categories) > 0)
                    @foreach ($categories as $category)
                        @php
                            $cId = is_array($category) ? ($category['id'] ?? $category['kode'] ?? '') : $category->id;
                            $cName = is_array($category) ? ($category['name'] ?? '') : $category->name;
                        @endphp
                        <button type="button" data-filter="{{ $cId }}"
                            class="cf-tab text-xs font-semibold uppercase tracking-wider pb-2">{{ $cName }}</button>
                    @endforeach
                @endif
            </div>

            <label class="flex items-center gap-2 text-xs text-gray-500">
                <span>Sort by</span>
                <select class="cf-sort border border-gray-300 rounded-md bg-transparent px-3 py-1.5 text-xs font-medium text-black focus:outline-none cursor-pointer">
                    <option value="relevance">Relevance</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                </select>
            </label>
        </div>

        <!-- 3. GRID 6 KOLOM (TANPA SIDEBAR FILTER) -->
        <div class="cf-grid grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-x-4 gap-y-8">

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

                    <article class="cf-card group relative flex flex-col space-y-3 cursor-pointer"
                        data-category="{{ $pCatId }}"
                        data-category-name="{{ $pCatName }}"
                        data-name="{{ $pName }}"
                        data-price="{{ $numericPrice }}">

                        <div class="relative w-full aspect-[3/4] rounded-xl bg-[#f6f6f6] overflow-hidden">
                            @if($pCatName)
                                <span
                                    class="absolute top-3 left-3 z-20 bg-black/85 backdrop-blur-sm text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">{{ $pCatName }}</span>
                            @endif

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

                            <!-- Hover Add to Cart Button -->
                            <button type="button"
                                onclick="addToCart('{{ addslashes($pName) }}', {{ $numericPrice }}, '{{ $image }}')"
                                class="absolute bottom-3 left-3 right-3 z-20 bg-black text-white py-2 rounded-lg text-[10px] font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                Add To Cart
                            </button>
                        </div>

                        <div class="space-y-0.5">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-1">{{ $pName }}</h3>
                            <p class="text-xs text-gray-500">Rp {{ number_format($numericPrice, 0, ',', '.') }}</p>
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
