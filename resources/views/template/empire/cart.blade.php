<div id="cartOverlay"
    class="fixed inset-0 bg-black/50 backdrop-blur-xs z-50 opacity-0 pointer-events-none transition-opacity duration-300"
    onclick="toggleCart(false)"></div>

<!-- Floating Sidebar Panel (Sesuai Gambar) -->
<aside id="cartDrawer"
    class="fixed top-3 right-3 bottom-3 w-[340px] sm:w-[380px] bg-white text-black rounded-2xl shadow-2xl z-50 transform translate-x-[105%] transition-transform duration-300 flex flex-col overflow-hidden">
    <!-- Drawer Header -->
    <div class="flex items-center justify-between p-5 border-b border-gray-100">
        <h2 class="text-sm font-bold text-gray-900">Your cart is empty</h2>
        <button onclick="toggleCart(false)" class="p-1 text-gray-500 hover:text-black transition"
            aria-label="Close Cart">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Scrollable Popular Items Section -->
    <div class="flex-1 overflow-y-auto p-5 space-y-4 scrollbar-none">

        <div class="flex items-center justify-between pb-1">
            <span class="text-xs font-medium text-gray-500">Popular items</span>
            <a href="#" class="text-xs font-semibold text-gray-900 hover:underline flex items-center">
                Go to Shop <span class="ml-1">›</span>
            </a>
        </div>

        <!-- Popular Item 1 -->
        <div class="flex items-start space-x-3 group cursor-pointer">
            <div class="w-16 h-16 bg-[#f4f4f4] rounded-xl flex-shrink-0 flex items-center justify-center p-2">
                <img src="https://images.unsplash.com/photo-1539185441755-769473a23570?auto=format&fit=crop&w=200&q=80"
                    alt="Gold Crown Vinyl" class="max-h-full object-contain" />
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-medium text-gray-800 leading-snug line-clamp-2 group-hover:text-black">
                    Young Dolph - King of Memphis (10 Yr Anniversary - Gold Crown Vinyl: D2C Exclusive)
                </h4>
            </div>
        </div>

        <!-- Popular Item 2 -->
        <div class="flex items-start space-x-3 group cursor-pointer">
            <div class="w-16 h-16 bg-[#f4f4f4] rounded-xl flex-shrink-0 flex items-center justify-center p-2">
                <img src="https://images.unsplash.com/photo-1603048588665-791ca8aea617?auto=format&fit=crop&w=200&q=80"
                    alt="Royalty Blue Vinyl" class="max-h-full object-contain" />
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-medium text-gray-800 leading-snug line-clamp-2 group-hover:text-black">
                    Young Dolph - King of Memphis (10 Yr Anniversary - Royalty Blue & Gold Vinyl)
                </h4>
            </div>
        </div>

        <!-- Popular Item 3 -->
        <div class="flex items-start space-x-3 group cursor-pointer">
            <div class="w-16 h-16 bg-[#f4f4f4] rounded-xl flex-shrink-0 flex items-center justify-center p-2">
                <img src="https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=200&q=80"
                    alt="Drakeo Vinyl" class="max-h-full object-contain" />
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-medium text-gray-800 leading-snug line-clamp-2 group-hover:text-black">
                    Drakeo the Ruler - Thank You For Using GTL (5 Year Anniversary Vinyl)
                </h4>
            </div>
        </div>

        <!-- Popular Item 4 -->
        <div class="flex items-start space-x-3 group cursor-pointer">
            <div class="w-16 h-16 bg-[#f4f4f4] rounded-xl flex-shrink-0 flex items-center justify-center p-2">
                <img src="https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=200&q=80"
                    alt="VANN DA T-Shirt" class="max-h-full object-contain" />
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-xs font-medium text-gray-800 leading-snug line-clamp-2 group-hover:text-black">
                    VANN DA - TREYVISAI I: 'THE SEARCH FOR LIGHT' T-Shirt
                </h4>
            </div>
        </div>

    </div>

    <!-- Drawer Footer Payment Badges -->
    <div class="p-4 border-t border-gray-100 flex items-center justify-center space-x-2 bg-white">
        <span class="px-2 py-1 bg-gray-100 rounded text-[9px] font-bold text-blue-900 italic">VISA</span>
        <div class="px-2 py-1 bg-gray-100 rounded flex items-center space-x-0.5">
            <span class="w-2.5 h-2.5 rounded-full bg-red-500 inline-block opacity-90"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block -ml-1.5"></span>
        </div>
        <span class="px-2 py-1 bg-gray-100 rounded text-[8px] font-bold text-orange-600 uppercase">DISCOVER</span>
        <span class="px-2 py-1 bg-gray-100 rounded text-[8px] font-bold text-blue-600 uppercase">AMEX</span>
    </div>
</aside>

<script>
    function toggleCart(isOpen) {
        const overlay = document.getElementById('cartOverlay');
        const drawer = document.getElementById('cartDrawer');

        if (isOpen) {
            overlay.classList.remove('opacity-0', 'pointer-events-none');
            overlay.classList.add('opacity-100', 'pointer-events-auto');
            drawer.classList.remove('translate-x-[105%]');
            drawer.classList.add('translate-x-0');
        } else {
            overlay.classList.remove('opacity-100', 'pointer-events-auto');
            overlay.classList.add('opacity-0', 'pointer-events-none');
            drawer.classList.remove('translate-x-0');
            drawer.classList.add('translate-x-[105%]');
        }
    }

    // Hubungkan semua tombol 'Add To Cart' ke fungsi dummy toggleCart(true)
    document.addEventListener('DOMContentLoaded', () => {
        const addToCartButtons = document.querySelectorAll('button');
        addToCartButtons.forEach(btn => {
            if (btn.textContent.trim().toLowerCase().includes('add to cart')) {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    toggleCart(true);
                });
            }
        });
    });
</script>