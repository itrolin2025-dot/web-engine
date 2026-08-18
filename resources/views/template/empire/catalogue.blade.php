<section class="bg-white py-10 px-6 lg:px-12 text-black">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- 1. CATEGORIES TITLE & PILLS -->
        <div class="space-y-4">
            <h2 class="text-3xl font-bold tracking-tight">Categories</h2>

            <div class="flex flex-wrap items-center gap-2">
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Clothing</button>
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Music</button>
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Accessories</button>
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Collab</button>
                <button
                    class="px-5 py-2 rounded-full border border-gray-300 text-xs font-medium text-gray-700 hover:border-black transition">Home
                    and Lifestyle</button>
            </div>
        </div>

        <!-- 2. SORT & RESULTS BAR -->
        <div class="flex items-center justify-end space-x-4 pt-2 text-xs text-gray-600">
            <div class="flex items-center space-x-1 cursor-pointer">
                <span>Sort by </span>
                <span class="font-semibold text-black">Relevance</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <span class="text-gray-400">1723 product(s)</span>
        </div>

        <!-- 3. MAIN CATALOG CONTENT (STICKY SIDEBAR + PRODUCT GRID) -->
        <div class="flex flex-col lg:flex-row gap-8 items-start relative">

            <!-- LEFT SIDEBAR FILTER (STICKY) -->
            <aside
                class="w-full lg:w-1/5 sticky top-24 self-start space-y-6 max-h-[calc(100vh-6rem)] overflow-y-auto pr-2 scrollbar-none">
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <span class="font-bold text-sm">Filters</span>
                    <span
                        class="w-5 h-5 rounded-full border border-gray-400 text-[10px] font-semibold flex items-center justify-center">0</span>
                </div>

                <!-- Artist Filter -->
                <div class="border-b border-gray-100 pb-4">
                    <button class="w-full flex items-center justify-between text-xs font-semibold py-1">
                        <span>Artist</span>
                        <span class="text-base font-light">+</span>
                    </button>
                </div>

                <!-- Genre Filter -->
                <div class="border-b border-gray-100 pb-4">
                    <button class="w-full flex items-center justify-between text-xs font-semibold py-1">
                        <span>Genre</span>
                        <span class="text-base font-light">+</span>
                    </button>
                </div>

                <!-- Pre-Order Checkbox -->
                <div class="space-y-3 border-b border-gray-100 pb-4">
                    <span class="text-xs font-semibold block">Pre-Order</span>
                    <label
                        class="flex items-center space-x-2 bg-gray-50 p-2.5 rounded-md cursor-pointer hover:bg-gray-100 transition">
                        <input type="checkbox" class="w-4 h-4 rounded-xs border-gray-300 text-black focus:ring-0">
                        <span class="text-xs text-gray-700">Pre-Order Only</span>
                    </label>
                </div>

                <!-- Sale Checkbox -->
                <div class="space-y-3 border-b border-gray-100 pb-4">
                    <span class="text-xs font-semibold block">Sale</span>
                    <label
                        class="flex items-center space-x-2 bg-gray-50 p-2.5 rounded-md cursor-pointer hover:bg-gray-100 transition">
                        <input type="checkbox" class="w-4 h-4 rounded-xs border-gray-300 text-black focus:ring-0">
                        <span class="text-xs text-gray-700">On Sale Only</span>
                    </label>
                </div>

                <!-- Price Filter -->
                <div class="pb-4">
                    <button class="w-full flex items-center justify-between text-xs font-semibold py-1">
                        <span>Price</span>
                        <span class="text-base font-light">+</span>
                    </button>
                </div>
            </aside>

            <!-- RIGHT PRODUCT GRID (2 BARIS / 8 PRODUCTS) -->
            <main class="w-full lg:w-4/5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-5 gap-y-8">

                    <!-- BARIS 1 - CARD 1 -->
                    <div class="group flex flex-col justify-between space-y-3 cursor-pointer">
                        <div
                            class="relative bg-[#f6f6f6] rounded-xl aspect-square flex items-center justify-center p-6 overflow-hidden">
                            <span
                                class="absolute top-3 left-3 bg-black text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider z-10">T-SHIRT</span>
                            <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=500&q=80"
                                alt="Baby Money Blue T-Shirt"
                                class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                            <!-- Hover Add to Cart Button -->
                            <button
                                class="absolute bottom-3 left-3 right-3 bg-black text-white py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                Add To Cart
                            </button>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-1">Baby Money - I'M A BOSS
                                T-Shirt (Blue)</h3>
                            <p class="text-xs text-gray-500">$40.00</p>
                        </div>
                    </div>

                    <!-- BARIS 1 - CARD 2 -->
                    <div class="group flex flex-col justify-between space-y-3 cursor-pointer">
                        <div
                            class="relative bg-[#f6f6f6] rounded-xl aspect-square flex items-center justify-center p-6 overflow-hidden">
                            <span
                                class="absolute top-3 left-3 bg-black text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider z-10">T-SHIRT</span>
                            <img src="https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=500&q=80"
                                alt="Baby Money Red T-Shirt"
                                class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                            <!-- Hover Add to Cart Button -->
                            <button
                                class="absolute bottom-3 left-3 right-3 bg-black text-white py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                Add To Cart
                            </button>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-1">Baby Money - I'M A BOSS
                                T-Shirt (Red)</h3>
                            <p class="text-xs text-gray-500">$40.00</p>
                        </div>
                    </div>

                    <!-- BARIS 1 - CARD 3 -->
                    <div class="group flex flex-col justify-between space-y-3 cursor-pointer">
                        <div
                            class="relative bg-[#f6f6f6] rounded-xl aspect-square flex items-center justify-center p-6 overflow-hidden">
                            <span
                                class="absolute top-3 left-3 bg-black text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider z-10">T-SHIRT</span>
                            <img src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=500&q=80"
                                alt="Baby Money Black T-Shirt"
                                class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                            <!-- Hover Add to Cart Button -->
                            <button
                                class="absolute bottom-3 left-3 right-3 bg-black text-white py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                Add To Cart
                            </button>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-1">Baby Money - I'M A BOSS
                                T-Shirt (Black)</h3>
                            <p class="text-xs text-gray-500">$40.00</p>
                        </div>
                    </div>

                    <!-- BARIS 1 - CARD 4 -->
                    <div class="group flex flex-col justify-between space-y-3 cursor-pointer">
                        <div
                            class="relative bg-[#f6f6f6] rounded-xl aspect-square flex items-center justify-center p-6 overflow-hidden">
                            <div class="absolute top-3 left-3 flex items-center space-x-1.5 z-10">
                                <span
                                    class="bg-black text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">VINYL</span>
                                <span
                                    class="bg-gray-200 text-gray-700 text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">PRE-ORDER</span>
                            </div>
                            <img src="https://images.unsplash.com/photo-1539185441755-769473a23570?auto=format&fit=crop&w=500&q=80"
                                alt="Young Dolph Vinyl"
                                class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                            <!-- Hover Add to Cart Button -->
                            <button
                                class="absolute bottom-3 left-3 right-3 bg-black text-white py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                Add To Cart
                            </button>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-2">Young Dolph - King of Memphis
                                (10 Yr Anniversary Vinyl)</h3>
                            <p class="text-xs text-gray-500">$24.00</p>
                        </div>
                    </div>

                    <!-- BARIS 2 - CARD 5 -->
                    <div class="group flex flex-col justify-between space-y-3 cursor-pointer">
                        <div
                            class="relative bg-[#f6f6f6] rounded-xl aspect-square flex items-center justify-center p-6 overflow-hidden">
                            <div class="absolute top-3 left-3 flex items-center space-x-1.5 z-10">
                                <span
                                    class="bg-black text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">VINYL</span>
                                <span
                                    class="bg-gray-200 text-gray-700 text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">PRE-ORDER</span>
                            </div>
                            <img src="https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=500&q=80"
                                alt="Vinyl Album 2"
                                class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                            <!-- Hover Add to Cart Button -->
                            <button
                                class="absolute bottom-3 left-3 right-3 bg-black text-white py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                Add To Cart
                            </button>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-1">Key Glock - Glockoma 2 Vinyl
                            </h3>
                            <p class="text-[#b57a6c] font-semibold text-xs">$30.00</p>
                        </div>
                    </div>

                    <!-- BARIS 2 - CARD 6 -->
                    <div class="group flex flex-col justify-between space-y-3 cursor-pointer">
                        <div
                            class="relative bg-[#f6f6f6] rounded-xl aspect-square flex items-center justify-center p-6 overflow-hidden">
                            <div class="absolute top-3 left-3 flex items-center space-x-1.5 z-10">
                                <span
                                    class="bg-black text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">CASSETTE</span>
                                <span
                                    class="bg-gray-200 text-gray-700 text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">PRE-ORDER</span>
                            </div>
                            <img src="https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=500&q=80"
                                alt="Cassette"
                                class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                            <!-- Hover Add to Cart Button -->
                            <button
                                class="absolute bottom-3 left-3 right-3 bg-black text-white py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                Add To Cart
                            </button>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-1">Rio Da Yung OG - City On My
                                Back Cassette</h3>
                            <p class="text-xs text-gray-500">$15.00</p>
                        </div>
                    </div>

                    <!-- BARIS 2 - CARD 7 -->
                    <div class="group flex flex-col justify-between space-y-3 cursor-pointer">
                        <div
                            class="relative bg-[#f6f6f6] rounded-xl aspect-square flex items-center justify-center p-6 overflow-hidden">
                            <div class="absolute top-3 left-3 flex items-center space-x-1.5 z-10">
                                <span
                                    class="bg-black text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">CD</span>
                                <span
                                    class="bg-gray-200 text-gray-700 text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">PRE-ORDER</span>
                            </div>
                            <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=500&q=80"
                                alt="CD Album"
                                class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                            <!-- Hover Add to Cart Button -->
                            <button
                                class="absolute bottom-3 left-3 right-3 bg-black text-white py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                Add To Cart
                            </button>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-1">Young Dolph - Paper Route
                                Frank CD</h3>
                            <p class="text-xs text-gray-500">$12.00</p>
                        </div>
                    </div>

                    <!-- BARIS 2 - CARD 8 -->
                    <div class="group flex flex-col justify-between space-y-3 cursor-pointer">
                        <div
                            class="relative bg-[#f6f6f6] rounded-xl aspect-square flex items-center justify-center p-6 overflow-hidden">
                            <span
                                class="absolute top-3 left-3 bg-black text-white text-[9px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider z-10">T-SHIRT</span>
                            <img src="https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=500&q=80"
                                alt="Graphic Tee"
                                class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                            <!-- Hover Add to Cart Button -->
                            <button
                                class="absolute bottom-3 left-3 right-3 bg-black text-white py-2.5 rounded-lg text-xs font-semibold uppercase tracking-wider opacity-0 translate-y-3 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 shadow-md">
                                Add To Cart
                            </button>
                        </div>
                        <div class="space-y-0.5">
                            <h3 class="text-xs font-medium text-gray-800 line-clamp-1">EMPIRE Official Vintage
                                Graphic Tee</h3>
                            <p class="text-xs text-gray-500">$35.00</p>
                        </div>
                    </div>

                </div>
            </main>

        </div>

    </div>
</section>