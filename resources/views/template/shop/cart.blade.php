<div id="cart-drawer" class="fixed inset-0 z-50 overflow-hidden hidden">
    <!-- Backdrop Overlay -->
    <div onclick="toggleCartDrawer()" class="absolute inset-0 bg-black/50 transition-opacity"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div class="w-screen max-w-md bg-white shadow-xl flex flex-col justify-between">

            <!-- Drawer Header -->
            <div class="p-6 border-b border-stone-100 flex items-center justify-between">
                <h3 class="font-serif-heading font-bold text-lg">Your Shopping Bag</h3>
                <button onclick="toggleCartDrawer()" class="text-stone-400 hover:text-black">
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
    let cart = getCookie('shopping_cart') || [];

    // --- TOGGLE CART DRAWER ---
    function toggleCartDrawer() {
        const drawer = document.getElementById('cart-drawer');
        drawer.classList.toggle('hidden');
    }

    // --- ADD ITEM TO CART ---
    function addToCart(id, name, price, image) {
        const existingIndex = cart.findIndex(item => item.id === id);
        if (existingIndex > -1) {
            cart[existingIndex].quantity += 1;
        } else {
            cart.push({ id, name, price, image, quantity: 1 });
        }
        saveAndUpdateCart();
        toggleCartDrawer(); // Buka drawer saat item ditambahkan
    }

    // --- UPDATE QUANTITY ---
    function updateQuantity(id, change) {
        const index = cart.findIndex(item => item.id === id);
        if (index > -1) {
            cart[index].quantity += change;
            if (cart[index].quantity <= 0) {
                cart.splice(index, 1);
            }
        }
        saveAndUpdateCart();
    }

    // --- REMOVE ITEM ---
    function removeItem(id) {
        cart = cart.filter(item => item.id !== id);
        saveAndUpdateCart();
    }

    // --- SAVE TO COOKIES & UPDATE UI ---
    function saveAndUpdateCart() {
        setCookie('shopping_cart', cart, 7); // Simpan cookie selama 7 hari
        renderCartUI();
    }

    // --- RENDER CART INTERFACE ---
    function renderCartUI() {
        const container = document.getElementById('cart-items-container');
        const badge = document.getElementById('cart-badge');
        const subtotalEl = document.getElementById('cart-subtotal');

        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

        // Badge counter update
        if (totalItems > 0) {
            badge.innerText = totalItems;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }

        // Render Cart Items
        if (cart.length === 0) {
            container.innerHTML = `<div class="text-center text-stone-400 py-12 text-xs uppercase tracking-widest">Keranjang belanja Anda kosong.</div>`;
            subtotalEl.innerText = 'Rp 0';
            return;
        }

        let html = '';
        let subtotal = 0;

        cart.forEach(item => {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;

            html += `
                    <div class="flex gap-4 items-center border-b border-stone-100 pb-4">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg bg-stone-100">
                        <div class="flex-1">
                            <h4 class="font-bold text-xs">${item.name}</h4>
                            <p class="text-xs text-stone-500">Rp ${item.price.toLocaleString('id-ID')}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <div class="flex items-center border border-stone-200 rounded px-2 py-0.5 text-xs">
                                    <button onclick="updateQuantity(${item.id}, -1)" class="px-1 text-stone-500 hover:text-black">-</button>
                                    <span class="px-2 font-semibold">${item.quantity}</span>
                                    <button onclick="updateQuantity(${item.id}, 1)" class="px-1 text-stone-500 hover:text-black">+</button>
                                </div>
                                <button onclick="removeItem(${item.id})" class="text-[10px] text-red-500 underline">Hapus</button>
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