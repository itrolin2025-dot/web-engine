<section id="products-section" class="max-w-7xl mx-auto px-6 py-16">
    <div class="flex justify-between items-end mb-10">
        <h2 class="text-2xl md:text-3xl font-serif-heading font-bold uppercase tracking-wider">PRODUCTS</h2>
        <a href="#" class="text-xs font-semibold tracking-widest uppercase border-b border-black pb-0.5">See All
            Products</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <!-- Product Item 1 -->
        <div class="border border-stone-100 rounded-lg p-3 text-center flex flex-col justify-between">
            <div>
                <div class="bg-yellow-100 aspect-square rounded-lg overflow-hidden mb-3">
                    <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&auto=format&fit=crop&q=80"
                        class="w-full h-full object-cover">
                </div>
                <span class="text-[9px] text-stone-400 font-semibold uppercase tracking-widest">BODY LOTION</span>
                <h4 class="font-bold text-sm mb-1">BLOOMING GARDEN</h4>
                <p class="text-xs font-semibold text-stone-600 mb-3">Rp 129.000</p>
            </div>
            <button
                onclick="addToCart(4, 'BLOOMING GARDEN', 129000, 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=400&auto=format&fit=crop&q=80')"
                class="w-full border border-black text-black py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">Add
                To Cart</button>
        </div>

        <!-- Product Item 2 -->
        <div class="border border-stone-100 rounded-lg p-3 text-center flex flex-col justify-between">
            <div>
                <div class="bg-purple-100 aspect-square rounded-lg overflow-hidden mb-3">
                    <img src="https://images.unsplash.com/photo-1547887537-6158d64c35b3?w=400&auto=format&fit=crop&q=80"
                        class="w-full h-full object-cover">
                </div>
                <span class="text-[9px] text-stone-400 font-semibold uppercase tracking-widest">EXTRAIT DE
                    PARFUM</span>
                <h4 class="font-bold text-sm mb-1">SUMMER BREEZE</h4>
                <p class="text-xs font-semibold text-stone-600 mb-3">Rp 179.000</p>
            </div>
            <button
                onclick="addToCart(5, 'SUMMER BREEZE', 179000, 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?w=400&auto=format&fit=crop&q=80')"
                class="w-full border border-black text-black py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">Add
                To Cart</button>
        </div>

        <!-- Product Item 3 -->
        <div class="border border-stone-100 rounded-lg p-3 text-center flex flex-col justify-between">
            <div>
                <div class="bg-pink-100 aspect-square rounded-lg overflow-hidden mb-3">
                    <img src="https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=400&auto=format&fit=crop&q=80"
                        class="w-full h-full object-cover">
                </div>
                <span class="text-[9px] text-stone-400 font-semibold uppercase tracking-widest">BODY MIST</span>
                <h4 class="font-bold text-sm mb-1">COCO FLORAL</h4>
                <p class="text-xs font-semibold text-stone-600 mb-3">Rp 119.000</p>
            </div>
            <button
                onclick="addToCart(6, 'COCO FLORAL', 119000, 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=400&auto=format&fit=crop&q=80')"
                class="w-full border border-black text-black py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-black hover:text-white transition-colors">Add
                To Cart</button>
        </div>

        <!-- Product Item 4 -->
        <div class="border border-stone-100 rounded-lg p-3 text-center flex flex-col justify-between">
            <div>
                <div class="bg-blue-100 aspect-square rounded-lg overflow-hidden mb-3">
                    <img src="https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=400&auto=format&fit=crop&q=80"
                        class="w-full h-full object-cover">
                </div>
                <span class="text-[9px] text-stone-400 font-semibold uppercase tracking-widest">BUNDLE SET</span>
                <h4 class="font-bold text-sm mb-1">TRIO PACK SET</h4>
                <p class="text-xs font-semibold text-stone-600 mb-3">Rp 399.000</p>
            </div>
            <button
                onclick="addToCart(7, 'TRIO PACK SET', 399000, 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?w=400&auto=format&fit=crop&q=80')"
                class="w-full bg-black text-white py-2 text-[10px] font-bold uppercase tracking-widest hover:bg-stone-800 transition-colors">Add
                To Cart</button>
        </div>
    </div>
</section>