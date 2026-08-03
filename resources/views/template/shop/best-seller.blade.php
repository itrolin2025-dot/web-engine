<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="flex justify-between items-end mb-10">
        <h2 class="text-2xl md:text-3xl font-serif-heading font-bold uppercase tracking-wider">Bestsellers</h2>
        <a href="#" class="text-xs font-semibold tracking-widest uppercase border-b border-black pb-0.5">View All
            Bestsellers</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Product Item 1 -->
        <div class="group">
            <div class="bg-pink-100 aspect-square rounded-2xl overflow-hidden mb-4 relative">
                <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?w=500&auto=format&fit=crop&q=80"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <span class="text-[10px] text-stone-400 font-semibold uppercase tracking-widest">BODY WASH</span>
            <h3 class="font-serif-heading font-bold text-lg mb-3">SHOOTING STAR</h3>
            <button
                onclick="addToCart(1, 'SHOOTING STAR', 159000, 'https://images.unsplash.com/photo-1556228720-195a672e8a03?w=500&auto=format&fit=crop&q=80')"
                class="bg-black text-white px-6 py-2 text-xs uppercase tracking-widest hover:bg-stone-800 transition-colors">Add
                To Cart</button>
        </div>

        <!-- Product Item 2 -->
        <div class="group">
            <div class="bg-yellow-100 aspect-square rounded-2xl overflow-hidden mb-4 relative">
                <img src="https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=500&auto=format&fit=crop&q=80"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <span class="text-[10px] text-stone-400 font-semibold uppercase tracking-widest">EXTRAIT DE
                PARFUM</span>
            <h3 class="font-serif-heading font-bold text-lg mb-3">ROSY CLOUD</h3>
            <button
                onclick="addToCart(2, 'ROSY CLOUD', 189000, 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=500&auto=format&fit=crop&q=80')"
                class="bg-black text-white px-6 py-2 text-xs uppercase tracking-widest hover:bg-stone-800 transition-colors">Add
                To Cart</button>
        </div>

        <!-- Product Item 3 -->
        <div class="group">
            <div class="bg-purple-100 aspect-square rounded-2xl overflow-hidden mb-4 relative">
                <img src="https://images.unsplash.com/photo-1547887537-6158d64c35b3?w=500&auto=format&fit=crop&q=80"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>
            <span class="text-[10px] text-stone-400 font-semibold uppercase tracking-widest">HAIR AND BODY
                MIST</span>
            <h3 class="font-serif-heading font-bold text-lg mb-3">MYSTICAL VANILLA</h3>
            <button
                onclick="addToCart(3, 'MYSTICAL VANILLA', 139000, 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?w=500&auto=format&fit=crop&q=80')"
                class="bg-black text-white px-6 py-2 text-xs uppercase tracking-widest hover:bg-stone-800 transition-colors">Add
                To Cart</button>
        </div>
    </div>
</section>