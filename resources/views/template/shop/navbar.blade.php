<nav class="bg-white border-b border-stone-100 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <!-- Logo Hati -->
        <a href="#" class="text-2xl text-pink-500 font-bold flex items-center gap-1">
            <i class="fa-solid fa-heart text-pink-500"></i>
        </a>

        <!-- Navigation Links -->
        <div
            class="hidden md:flex items-center space-x-8 text-xs uppercase tracking-widest font-semibold text-stone-700">
            <a href="#" class="hover:text-black">Shop</a>
            <a href="#" class="hover:text-black">Collections</a>
            <a href="#" class="hover:text-black">Best Seller</a>
            <a href="#" class="hover:text-black">News</a>
            <a href="#" class="hover:text-black">Contact</a>
            <a href="#" class="hover:text-black">About Us</a>
        </div>

        <!-- Navbar Icons (Search & Cart) -->
        <div class="flex items-center space-x-5 text-lg">
            <button class="hover:opacity-60 transition-opacity"><i class="fa-solid fa-magnifying-glass"></i></button>

            <!-- CART BUTTON WITH COUNTER BADGE -->
            <button onclick="toggleCartDrawer()" class="relative hover:opacity-60 transition-opacity p-1">
                <i class="fa-solid fa-bag-shopping"></i>
                <span id="cart-badge"
                    class="absolute -top-1 -right-2 bg-pink-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold hidden">0</span>
            </button>
        </div>
    </div>
</nav>