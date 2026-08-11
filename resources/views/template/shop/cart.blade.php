<div id="cart-drawer" class="fixed inset-0 z-[99999] isolate overflow-hidden pointer-events-none opacity-0 transition-opacity duration-300" style="visibility: hidden;">
    <!-- Backdrop Overlay -->
    <div id="cart-backdrop" onclick="toggleCartDrawer()" class="absolute inset-0 bg-black/0 transition-all duration-300"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div id="cart-panel" class="w-screen max-w-md bg-white shadow-xl flex flex-col justify-between translate-x-full transition-transform duration-300 ease-out">

            <!-- Drawer Header -->
            <div class="p-6 border-b border-stone-100 flex items-center justify-between">
                <h3 class="font-serif-heading font-bold text-lg">Your Shopping Bag</h3>
                <button onclick="toggleCartDrawer()" class="text-stone-400 hover:text-black transition-colors">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Drawer Cart Items Body -->
            <div id="cart-items-container" class="p-6 flex-1 overflow-y-auto space-y-4">
                <!-- Items generated dynamically via JS -->
            </div>

            <!-- Drawer Footer -->
            <div class="p-6 border-t border-stone-100 bg-stone-50 space-y-4">
                <div class="flex justify-between text-sm font-bold">
                    <span>Subtotal:</span>
                    <span id="cart-subtotal">Rp 0</span>
                </div>
                <button onclick="openWizardModal()"
                    class="w-full bg-black text-white py-3.5 text-xs font-bold uppercase tracking-widest hover:bg-stone-800 transition-colors">
                    Checkout
                </button>
                <button onclick="clearCart()"
                    class="w-full border border-red-500 text-red-500 py-3 text-xs font-bold uppercase tracking-widest hover:bg-red-500 hover:text-white transition-colors">
                    <i class="fa-solid fa-trash-can mr-1.5"></i> Clear Cart
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    // --- HELPER COOKIE FUNCTIONS ---
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        // SameSite=Lax untuk keamanan cookie
        document.cookie = name + "=" + (encodeURIComponent(JSON.stringify(value)) || "") + expires + "; path=/; SameSite=Lax";
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) {
                try {
                    return JSON.parse(decodeURIComponent(c.substring(nameEQ.length, c.length)));
                } catch (e) {
                    return null;
                }
            }
        }
        return null;
    }

    // --- CART STATE ---
    // Get unique path segment (e.g. "nature" or "elska") to scope the shopping cart cookie
    const pathSegment = window.location.pathname.split('/').filter(Boolean)[0] || 'default';
    const cookieName = 'shopping_cart_' + pathSegment;
    let cart = getCookie(cookieName) || [];

    // --- TOGGLE CART DRAWER ---
    let cartOpen = false;
    function toggleCartDrawer() {
        const drawer = document.getElementById('cart-drawer');
        const panel = document.getElementById('cart-panel');
        const backdrop = document.getElementById('cart-backdrop');

        if (!cartOpen) {
            // Open
            drawer.style.visibility = 'visible';
            drawer.classList.remove('pointer-events-none', 'opacity-0');
            drawer.classList.add('pointer-events-auto', 'opacity-100');
            panel.classList.remove('translate-x-full');
            panel.classList.add('translate-x-0');
            backdrop.classList.remove('bg-black/0');
            backdrop.classList.add('bg-black/50');
            cartOpen = true;
        } else {
            // Close
            panel.classList.remove('translate-x-0');
            panel.classList.add('translate-x-full');
            backdrop.classList.remove('bg-black/50');
            backdrop.classList.add('bg-black/0');
            drawer.classList.remove('pointer-events-auto', 'opacity-100');
            drawer.classList.add('pointer-events-none', 'opacity-0');
            setTimeout(() => { drawer.style.visibility = 'hidden'; }, 300);
            cartOpen = false;
        }
    }

    // --- ADD ITEM TO CART ---
    function addToCart(name, price, image) {
        const numericPrice = typeof price === 'number' ? price : parseInt(String(price).replace(/[^0-9]/g, '')) || 0;
        const existingIndex = cart.findIndex(item => item.name === name);
        if (existingIndex > -1) {
            cart[existingIndex].quantity += 1;
        } else {
            cart.push({ name, price: numericPrice, image, quantity: 1 });
        }
        saveAndUpdateCart();
    }

    // --- UPDATE QUANTITY ---
    function updateQuantity(name, change) {
        const index = cart.findIndex(item => item.name === name);
        if (index > -1) {
            cart[index].quantity += change;
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }
        }
        saveAndUpdateCart();
    }

    // --- REMOVE ITEM ---
    function removeItem(name) {
        cart = cart.filter(item => item.name !== name);
        saveAndUpdateCart();
    }

    // --- CLEAR ENTIRE CART ---
    function clearCart() {
        cart = [];
        saveAndUpdateCart();
    }

    // --- SAVE TO COOKIES & UPDATE UI ---
    function saveAndUpdateCart() {
        setCookie(cookieName, cart, 7);
        renderCartUI();
    }

    // --- RENDER CART INTERFACE ---
    function renderCartUI() {
        const container = document.getElementById('cart-items-container');
        const badge = document.getElementById('cart-badge');
        const subtotalEl = document.getElementById('cart-subtotal');

        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

        if (totalItems > 0) {
            badge.innerText = totalItems;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        if (cart.length === 0) {
            container.innerHTML = `<div class="text-center text-stone-400 py-12 text-xs uppercase tracking-widest">Keranjang belanja Anda kosong.</div>`;
            subtotalEl.innerText = 'Rp 0';
            return;
        }

        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            const itemPrice = typeof item.price === 'number' ? item.price : parseInt(String(item.price).replace(/[^0-9]/g, '')) || 0;
            const itemTotal = itemPrice * item.quantity;
            subtotal += itemTotal;

            const escapedName = item.name.replace(/'/g, "\\'");

            html += `
                    <div class="flex gap-4 items-center border-b border-stone-100 pb-4">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg bg-stone-100">
                        <div class="flex-1">
                            <h4 class="font-bold text-xs">${item.name}</h4>
                            <p class="text-xs text-stone-500">Rp ${itemPrice.toLocaleString('id-ID')}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex items-center border border-stone-200 rounded px-2 py-0.5 text-xs">
                                    <button onclick="updateQuantity('${escapedName}', -1)" class="px-1 text-stone-500 hover:text-black">-</button>
                                    <span class="px-2 font-semibold">${item.quantity}</span>
                                    <button onclick="updateQuantity('${escapedName}', 1)" class="px-1 text-stone-500 hover:text-black">+</button>
                                </div>
                                <button onclick="removeItem('${escapedName}')" class="text-[10px] text-red-500 underline">Hapus</button>
                            </div>
                        </div>
                    </div>
                `;
        });

        container.innerHTML = html;
        subtotalEl.innerText = `Rp ${subtotal.toLocaleString('id-ID')}`;
    }

    // Initialize UI on Page Load
    document.addEventListener('DOMContentLoaded', () => {
        renderCartUI();
    });
</script>