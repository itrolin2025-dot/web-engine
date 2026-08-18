<section class="bg-[#f7f5ee] py-16 px-4 sm:px-6 lg:px-12 overflow-hidden text-gray-800">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-start gap-8 lg:gap-12">

            <!-- LEFT SIDE: TITLE, DESCRIPTION, CTA & NAVIGATION -->
            <div class="w-full lg:w-1/4 flex flex-col justify-between space-y-6 lg:sticky lg:top-8">
                <div class="space-y-3">
                    <h2 class="text-3xl sm:text-4xl font-serif text-gray-900 tracking-tight">
                        New <span class="italic font-normal">Arrivals</span>
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-500 leading-relaxed font-light">
                        New to the Platform. True to the Standard.
                    </p>
                </div>

                <!-- CTA Button -->
                <div>
                    <a href="#"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-[#b57a6c] text-white text-xs font-semibold tracking-widest uppercase rounded-full hover:bg-[#a0685b] transition duration-200">
                        <span>SHOP THE COLLECTION</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

                <!-- Arrow Navigation Buttons -->
                <div class="flex items-center space-x-3 pt-2">
                    <button
                        class="w-9 h-9 rounded-full bg-black/5 text-gray-600 flex items-center justify-center hover:bg-black/10 transition"
                        aria-label="Previous">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button
                        class="w-9 h-9 rounded-full bg-black/5 text-gray-600 flex items-center justify-center hover:bg-black/10 transition"
                        aria-label="Next">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- RIGHT SIDE: SCROLLABLE PRODUCT CAROUSEL GRID -->
            <div class="w-full lg:w-3/4 overflow-x-auto pb-6 scrollbar-none flex gap-6">

                <!-- PRODUCT CARD 1 -->
                <div class="flex-shrink-0 w-[260px] sm:w-[280px] flex flex-col justify-between space-y-4">
                    <!-- Image Box -->
                    <div
                        class="relative bg-[#eeebe3] rounded-2xl aspect-square flex items-center justify-center p-6 group">
                        <button
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-600 hover:text-black shadow-xs"
                            aria-label="Wishlist">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        <img src="https://images.unsplash.com/photo-1566478989037-eec170784d0b?auto=format&fit=crop&w=500&q=80"
                            alt="Siete Chili Lime Potato Chips"
                            class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                    </div>
                    <!-- Details -->
                    <div class="space-y-1">
                        <span
                            class="text-[11px] font-semibold tracking-wider text-[#b57a6c] uppercase block">SIETE</span>
                        <h3 class="text-xs text-gray-700 font-normal line-clamp-1">Siete Chili Lime Potato Chips</h3>
                        <p class="text-xs font-medium text-gray-800 pt-2">QAR 34.00</p>
                    </div>
                    <!-- Add to Cart Button -->
                    <button
                        class="w-full py-2.5 border border-gray-400 rounded-full text-[11px] font-semibold text-gray-700 tracking-wider hover:bg-gray-900 hover:text-white hover:border-gray-900 transition duration-200 uppercase">
                        ADD TO CART
                    </button>
                </div>

                <!-- PRODUCT CARD 2 (WITH QUICK VIEW OVERLAY) -->
                <div class="flex-shrink-0 w-[260px] sm:w-[280px] flex flex-col justify-between space-y-4">
                    <!-- Image Box -->
                    <div
                        class="relative bg-[#eeebe3] rounded-2xl aspect-square flex items-center justify-center p-6 group">
                        <button
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-600 hover:text-black shadow-xs"
                            aria-label="Wishlist">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        <img src="https://images.unsplash.com/photo-1621447504864-d8686e12698c?auto=format&fit=crop&w=500&q=80"
                            alt="Siete Fuego Puffs"
                            class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                        <!-- Quick View Eye Button (Center Bottom) -->
                        <div
                            class="absolute bottom-3 left-1/2 -translate-x-1/2 bg-white px-3 py-1 rounded-md shadow-xs flex items-center justify-center text-gray-600 hover:text-black">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                    <!-- Details -->
                    <div class="space-y-1">
                        <span
                            class="text-[11px] font-semibold tracking-wider text-[#b57a6c] uppercase block">SIETE</span>
                        <h3 class="text-xs text-gray-700 font-normal underline line-clamp-1">Siete "Fuego" Puffs</h3>
                        <p class="text-xs font-medium text-gray-800 pt-2">QAR 36.00</p>
                    </div>
                    <!-- Add to Cart Button -->
                    <button
                        class="w-full py-2.5 border border-gray-400 rounded-full text-[11px] font-semibold text-gray-700 tracking-wider hover:bg-gray-900 hover:text-white hover:border-gray-900 transition duration-200 uppercase">
                        ADD TO CART
                    </button>
                </div>

                <!-- PRODUCT CARD 3 -->
                <div class="flex-shrink-0 w-[260px] sm:w-[280px] flex flex-col justify-between space-y-4">
                    <!-- Image Box -->
                    <div
                        class="relative bg-[#eeebe3] rounded-2xl aspect-square flex items-center justify-center p-6 group">
                        <button
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-600 hover:text-black shadow-xs"
                            aria-label="Wishlist">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        <img src="https://images.unsplash.com/photo-1527842891421-42eec6e703ea?auto=format&fit=crop&w=500&q=80"
                            alt="Siete Spicy Dill Pickle Potato Chips"
                            class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                    </div>
                    <!-- Details -->
                    <div class="space-y-1">
                        <span
                            class="text-[11px] font-semibold tracking-wider text-[#b57a6c] uppercase block">SIETE</span>
                        <h3 class="text-xs text-gray-700 font-normal line-clamp-1">Siete Spicy Dill Pickle Potato Chips
                        </h3>
                        <p class="text-xs font-medium text-gray-800 pt-2">QAR 34.00</p>
                    </div>
                    <!-- Add to Cart Button -->
                    <button
                        class="w-full py-2.5 border border-gray-400 rounded-full text-[11px] font-semibold text-gray-700 tracking-wider hover:bg-gray-900 hover:text-white hover:border-gray-900 transition duration-200 uppercase">
                        ADD TO CART
                    </button>
                </div>

                <!-- PRODUCT CARD 4 -->
                <div class="flex-shrink-0 w-[260px] sm:w-[280px] flex flex-col justify-between space-y-4">
                    <!-- Image Box -->
                    <div
                        class="relative bg-[#eeebe3] rounded-2xl aspect-square flex items-center justify-center p-6 group">
                        <button
                            class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-600 hover:text-black shadow-xs"
                            aria-label="Wishlist">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                        <img src="https://images.unsplash.com/photo-1621447504864-d8686e12698c?auto=format&fit=crop&w=500&q=80"
                            alt="Madhava Organic Honey"
                            class="max-h-full object-contain group-hover:scale-105 transition duration-300" />
                    </div>
                    <!-- Details -->
                    <div class="space-y-1">
                        <span
                            class="text-[11px] font-semibold tracking-wider text-[#b57a6c] uppercase block">MADHAVA</span>
                        <h3 class="text-xs text-gray-700 font-normal line-clamp-1">Madhava Organic Sweetener</h3>
                        <p class="text-xs font-medium text-gray-800 pt-2">QAR 44.00</p>
                    </div>
                    <!-- Add to Cart Button -->
                    <button
                        class="w-full py-2.5 border border-gray-400 rounded-full text-[11px] font-semibold text-gray-700 tracking-wider hover:bg-gray-900 hover:text-white hover:border-gray-900 transition duration-200 uppercase">
                        ADD TO CART
                    </button>
                </div>

            </div>

        </div>
    </section>