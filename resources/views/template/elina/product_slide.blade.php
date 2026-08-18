<!-- LATEST DROPS SECTION -->
<section class="w-full border-t border-b border-black py-8 bg-white font-sans antialiased text-black py-10">

    <!-- SECTION HEADER (TITLE & SLIDE CONTROLS) -->
    <div class="max-w-[1700px] mx-auto px-6 sm:px-10 flex items-center justify-between mb-8">
        <!-- Title -->
        <h2 class="text-4xl sm:text-5xl font-black italic tracking-tighter uppercase">
            Latest drops
        </h2>

        <!-- Header Controls (Prev/Next Arrows + Shop Merch Button) -->
        <div class="flex items-center space-x-3">
            <!-- Left Slide Button -->
            <button id="prevBtn"
                class="w-10 h-10 rounded-full border border-black flex items-center justify-center hover:bg-black hover:text-white transition duration-200"
                aria-label="Previous Slide">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                    </path>
                </svg>
            </button>

            <!-- Right Slide Button -->
            <button id="nextBtn"
                class="w-10 h-10 rounded-full border border-black flex items-center justify-center hover:bg-black hover:text-white transition duration-200"
                aria-label="Next Slide">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Shop Merch Button -->
            <a href="#"
                class="ml-2 px-6 py-2.5 rounded-full border border-black text-xs font-semibold tracking-wide hover:bg-black hover:text-white transition duration-200">
                Shop merch
            </a>
        </div>
    </div>

    <!-- PRODUCT CAROUSEL CONTAINER -->
    <div id="productContainer"
        class="flex overflow-x-auto scroll-smooth no-scrollbar border-t border-black divide-x divide-black">

        <!-- PRODUCT 1 -->
        <div class="flex-none w-[85vw] sm:w-[380px] p-6 flex flex-col justify-between">
            <div>
                <!-- Image Card with Sold Out Badge -->
                <div
                    class="relative bg-[#f5f5f5] aspect-square rounded-sm flex items-center justify-center p-8 mb-6">
                    <span
                        class="absolute top-3 right-3 bg-white text-[11px] font-semibold px-2.5 py-1 rounded-full shadow-sm">
                        Sold Out
                    </span>
                    <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80"
                        alt="CSBD mug" class="max-h-full object-contain">
                </div>
                <!-- Product Title -->
                <h3 class="font-black text-xl mb-4 tracking-tight">CSBD mug</h3>
            </div>
            <!-- Bottom Action / Price Bar -->
            <div
                class="w-full py-2.5 px-4 border border-black rounded-full flex items-center justify-between text-xs font-semibold">
                <span>Shop</span>
                <span>—</span>
            </div>
        </div>

        <!-- PRODUCT 2 -->
        <div class="flex-none w-[85vw] sm:w-[380px] p-6 flex flex-col justify-between">
            <div>
                <div
                    class="relative bg-[#f5f5f5] aspect-square rounded-sm flex items-center justify-center p-8 mb-6">
                    <img src="https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=600&q=80"
                        alt="Coffee flame snapback" class="max-h-full object-contain">
                </div>
                <h3 class="font-black text-xl mb-4 tracking-tight">Coffee flame snapback</h3>
            </div>
            <div
                class="w-full py-2.5 px-4 border border-black rounded-full flex items-center justify-between text-xs font-semibold">
                <span>Shop</span>
                <span>Rp 452.271,31</span>
            </div>
        </div>

        <!-- PRODUCT 3 -->
        <div class="flex-none w-[85vw] sm:w-[380px] p-6 flex flex-col justify-between">
            <div>
                <div
                    class="relative bg-[#f5f5f5] aspect-square rounded-sm flex items-center justify-center p-8 mb-6">
                    <span
                        class="absolute top-3 right-3 bg-white text-[11px] font-semibold px-2.5 py-1 rounded-full shadow-sm">
                        Sold Out
                    </span>
                    <img src="https://images.unsplash.com/photo-1577937927133-66ef06acdf18?auto=format&fit=crop&w=600&q=80"
                        alt="Wreath mug" class="max-h-full object-contain">
                </div>
                <h3 class="font-black text-xl mb-4 tracking-tight">Wreath mug</h3>
            </div>
            <div
                class="w-full py-2.5 px-4 border border-black rounded-full flex items-center justify-between text-xs font-semibold">
                <span>Shop</span>
                <span>—</span>
            </div>
        </div>

        <!-- PRODUCT 4 -->
        <div class="flex-none w-[85vw] sm:w-[380px] p-6 flex flex-col justify-between">
            <div>
                <div
                    class="relative bg-[#f5f5f5] aspect-square rounded-sm flex items-center justify-center p-8 mb-6">
                    <img src="https://images.unsplash.com/photo-1517256064527-09c73fc73e38?auto=format&fit=crop&w=600&q=80"
                        alt="Deadstock x MiiR" class="max-h-full object-contain">
                </div>
                <h3 class="font-black text-xl mb-4 tracking-tight">Deadstock x MiiR</h3>
            </div>
            <div
                class="w-full py-2.5 px-4 border border-black rounded-full flex items-center justify-between text-xs font-semibold">
                <span>Shop</span>
                <span>Rp 680.000,00</span>
            </div>
        </div>

        <!-- PRODUCT 5 -->
        <div class="flex-none w-[85vw] sm:w-[380px] p-6 flex flex-col justify-between">
            <div>
                <div
                    class="relative bg-[#f5f5f5] aspect-square rounded-sm flex items-center justify-center p-8 mb-6">
                    <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&q=80"
                        alt="Classic Logo Tee" class="max-h-full object-contain">
                </div>
                <h3 class="font-black text-xl mb-4 tracking-tight">Classic Flame Tee</h3>
            </div>
            <div
                class="w-full py-2.5 px-4 border border-black rounded-full flex items-center justify-between text-xs font-semibold">
                <span>Shop</span>
                <span>Rp 520.000,00</span>
            </div>
        </div>

        <!-- PRODUCT 6 -->
        <div class="flex-none w-[85vw] sm:w-[380px] p-6 flex flex-col justify-between">
            <div>
                <div
                    class="relative bg-[#f5f5f5] aspect-square rounded-sm flex items-center justify-center p-8 mb-6">
                    <span
                        class="absolute top-3 right-3 bg-white text-[11px] font-semibold px-2.5 py-1 rounded-full shadow-sm">
                        Sold Out
                    </span>
                    <img src="https://images.unsplash.com/photo-1516826957135-700dedea698c?auto=format&fit=crop&w=600&q=80"
                        alt="Vintage Hoodie" class="max-h-full object-contain">
                </div>
                <h3 class="font-black text-xl mb-4 tracking-tight">Heavyweight Hoodie</h3>
            </div>
            <div
                class="w-full py-2.5 px-4 border border-black rounded-full flex items-center justify-between text-xs font-semibold">
                <span>Shop</span>
                <span>—</span>
            </div>
        </div>

        <!-- PRODUCT 7 -->
        <div class="flex-none w-[85vw] sm:w-[380px] p-6 flex flex-col justify-between">
            <div>
                <div
                    class="relative bg-[#f5f5f5] aspect-square rounded-sm flex items-center justify-center p-8 mb-6">
                    <img src="https://images.unsplash.com/photo-1539185441755-769473a23570?auto=format&fit=crop&w=600&q=80"
                        alt="Canvas Tote Bag" class="max-h-full object-contain">
                </div>
                <h3 class="font-black text-xl mb-4 tracking-tight">Dope Coffee Tote</h3>
            </div>
            <div
                class="w-full py-2.5 px-4 border border-black rounded-full flex items-center justify-between text-xs font-semibold">
                <span>Shop</span>
                <span>Rp 280.000,00</span>
            </div>
        </div>

    </div>

</section>

<!-- SCRIPT FOR SLIDE LEFT / RIGHT FUNCTIONALITY -->
<script>
    const productContainer = document.getElementById('productContainer');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    // Jarak scroll horizontal sekali klik (sesuai lebar card 380px)
    const scrollAmount = 380;

        nextBtn.addEventListener('click', () => {
            productContainer.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        prevBtn.addEventListener('click', () => {
            productContainer.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });
    </script>