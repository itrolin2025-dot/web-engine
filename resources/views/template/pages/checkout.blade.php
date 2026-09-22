<!DOCTYPE html>
<html lang="en">

@php
    $navContent = $navbarPresets ?? [];
    $logoFile = $navContent['image'] ?? null;
    $favicon = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;
    $homeUrl = url('/' . ($website->domain ?? ''));
    $qrUrl = !empty($website->qr_payment) && file_exists(public_path($website->qr_payment)) ? asset($website->qr_payment) : null;
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Checkout</title>
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset($favicon) }}">
    @else
        <link rel="icon" href="data:,">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f3f3;
            margin: 0;
            padding: 0;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #000 !important;
        }
    </style>
</head>
<body class="text-zinc-900 antialiased selection:bg-black selection:text-white">

    <header class="w-full bg-[#f3f3f3] border-b border-zinc-200 px-6 lg:px-12 py-4 flex items-center justify-between sticky top-0 z-50">
        <!-- Brand Logo -->
        <a href="{{ $homeUrl }}" class="font-black tracking-widest text-lg md:text-xl">{{ strtoupper($title ?? 'STORE') }}</a>

        <!-- Navigation Links -->
        <nav class="hidden md:flex items-center space-x-8 text-xs font-semibold tracking-wider">
            @foreach(($navContent['menus'] ?? []) as $menu)
                @continue(empty($menu['title'] ?? null))
                <a href="{{ $menu['url'] ?? $homeUrl }}" class="hover:text-zinc-500 transition-colors">{{ strtoupper($menu['title']) }}</a>
            @endforeach
        </nav>

        <!-- Icons -->
        <div class="flex items-center space-x-5 text-sm text-zinc-800">
            <a href="{{ $homeUrl }}" class="hover:text-black"><i class="fa-solid fa-arrow-left-long"></i></a>
            <a href="#" onclick="return false;" class="hover:text-black flex items-center space-x-1">
                <i class="fa-solid fa-bag-shopping"></i>
                <span id="header-bag-count" class="text-[10px] font-bold">0</span>
            </a>
        </div>
    </header>

    <main class="w-full bg-[#f3f3f3] px-6 lg:px-16 py-8 lg:py-12">
        <!-- Title CHECKOUT -->
        <h1 class="text-4xl lg:text-6xl font-black tracking-tight mb-10 text-zinc-900">CHECKOUT</h1>

        <!-- Empty state: no checked items were passed from the cart -->
        <div id="checkout-empty" class="hidden text-center py-20">
            <i class="fa-solid fa-bag-shopping text-5xl text-zinc-300 mb-6"></i>
            <p class="text-sm text-zinc-500 mb-2">Your shopping bag is empty.</p>
            <p class="text-xs text-zinc-400 mb-8">Select items in your bag first, then continue to checkout.</p>
            <a href="{{ $homeUrl }}" class="inline-block bg-zinc-900 text-white font-bold py-3 px-8 rounded text-xs tracking-widest hover:bg-zinc-800 transition-all uppercase">Back to Shop</a>
        </div>

        <!-- Grid Layout for Form & Shopping Bag -->
        <div id="checkout-content" class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">

            <!-- Left Column: Information, Delivery, Payment -->
            <div class="lg:col-span-7 space-y-10">

                <form id="checkout-form" action="{{ route('pages.checkout.order', ['client' => $website->domain]) }}" method="POST" class="space-y-10">
                    @csrf

                    <!-- Hidden mirror of the checked items from the cart drawer -->
                    <div id="checkout-items-input"></div>

                    <!-- Information Section -->
                    <section>
                        <div class="flex justify-between items-center mb-6 border-b border-zinc-300 pb-3">
                            <h2 class="text-lg font-bold">Information</h2>
                        </div>

                        <!-- Personal Information -->
                        <div class="mb-8">
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-600 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <input type="text" name="customer_name" id="customer_name" placeholder="Full name" required
                                        class="w-full bg-transparent border-b border-zinc-400 py-2 text-sm text-zinc-900 placeholder-zinc-500">
                                </div>
                                <div>
                                    <input type="tel" name="customer_phone" id="customer_phone" placeholder="Phone number"
                                        class="w-full bg-transparent border-b border-zinc-400 py-2 text-sm text-zinc-900 placeholder-zinc-500">
                                </div>
                                <div class="sm:col-span-2">
                                    <input type="email" name="customer_email" id="customer_email" placeholder="Email"
                                        class="w-full bg-transparent border-b border-zinc-400 py-2 text-sm text-zinc-900 placeholder-zinc-500">
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div>
                            <h3 class="text-xs font-semibold uppercase tracking-wider text-zinc-600 mb-4">Shipping Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <input type="text" name="shipping_city" id="shipping_city" placeholder="City"
                                        class="w-full bg-transparent border-b border-zinc-400 py-2 text-sm text-zinc-900 placeholder-zinc-500">
                                </div>
                                <div>
                                    <input type="text" name="shipping_postal" id="shipping_postal" placeholder="Zip / Postal code"
                                        class="w-full bg-transparent border-b border-zinc-400 py-2 text-sm text-zinc-900 placeholder-zinc-500">
                                </div>
                                <div class="sm:col-span-2">
                                    <textarea name="shipping_address" id="shipping_address" rows="2" placeholder="Address" required
                                        class="w-full bg-transparent border-b border-zinc-400 py-2 text-sm text-zinc-900 placeholder-zinc-500 resize-none"></textarea>
                                </div>
                            </div>
                            <label class="flex items-center space-x-2 text-xs text-zinc-700 cursor-pointer mt-3">
                                <input type="checkbox" required class="rounded border-zinc-400 text-black focus:ring-0">
                                <span>I agree to data processing</span>
                            </label>
                        </div>
                    </section>

                    <!-- Delivery Section -->
                    <section>
                        <h2 class="text-lg font-bold mb-6 border-b border-zinc-300 pb-3">Delivery</h2>
                        <div class="space-y-4">
                            <label class="flex items-center justify-between p-4 border border-zinc-300 rounded cursor-pointer hover:border-zinc-500 transition-colors bg-[#f3f3f3]">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="courier" value="Standard (5-7 days)" checked class="text-black focus:ring-0 js-courier" data-cost="0">
                                    <div>
                                        <p class="text-sm font-semibold">Standard Delivery</p>
                                        <p class="text-xs text-zinc-500">Delivery within 5-7 days</p>
                                    </div>
                                </div>
                                <span class="text-sm font-medium js-courier-label">Free</span>
                            </label>

                            <label class="flex items-center justify-between p-4 border border-zinc-300 rounded cursor-pointer hover:border-zinc-500 transition-colors bg-[#f3f3f3]">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="courier" value="Express (1-3 days)" class="text-black focus:ring-0 js-courier" data-cost="50000">
                                    <div>
                                        <p class="text-sm font-semibold">Express Shipping</p>
                                        <p class="text-xs text-zinc-500">Delivery within 1-3 days</p>
                                    </div>
                                </div>
                                <span class="text-sm font-medium js-courier-label">Rp 50.000</span>
                            </label>
                        </div>
                    </section>

                    <!-- Payment Section -->
                    <section>
                        <h2 class="text-lg font-bold mb-6 border-b border-zinc-300 pb-3">Payment</h2>
                        <div class="space-y-4">
                            @if($qrUrl)
                            <!-- QR Payment Option (from admin upload) -->
                            <div class="border-2 border-zinc-900 rounded p-4 bg-[#f3f3f3]">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="radio" name="payment_method" value="QR Payment" checked class="text-black focus:ring-0">
                                        <span class="text-sm font-semibold">QR Payment</span>
                                    </label>
                                    <i class="fa-solid fa-qrcode text-xl"></i>
                                </div>
                                <div class="flex items-center gap-4 pl-6">
                                    <img src="{{ $qrUrl }}" alt="QR Payment" class="w-28 h-28 object-contain rounded bg-white border border-zinc-200 p-1">
                                    <p class="text-xs text-zinc-500 leading-relaxed">Scan the QR code with your e-wallet or m-banking app<br>(GoPay, OVO, DANA, ShopeePay, m-Banking).</p>
                                </div>
                            </div>
                            @endif

                            <!-- Bank Transfer Option -->
                            <label class="flex items-center justify-between p-4 border border-zinc-300 rounded cursor-pointer hover:border-zinc-500 transition-colors bg-[#f3f3f3] {{ $qrUrl ? '' : 'border-2 border-zinc-900' }}">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="Bank Transfer" {{ $qrUrl ? '' : 'checked' }} class="text-black focus:ring-0">
                                    <span class="text-sm font-semibold">Bank Transfer / VA</span>
                                </div>
                                <i class="fa-solid fa-building-columns text-lg text-zinc-600"></i>
                            </label>

                            <!-- Credit Card Option -->
                            <label class="flex items-center justify-between p-4 border border-zinc-300 rounded cursor-pointer hover:border-zinc-500 transition-colors bg-[#f3f3f3]">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="Credit Card" class="text-black focus:ring-0">
                                    <span class="text-sm font-semibold">Credit Card</span>
                                </div>
                                <div class="flex space-x-1 text-xl">
                                    <i class="fa-brands fa-cc-visa text-blue-900"></i>
                                    <i class="fa-brands fa-cc-mastercard text-orange-600"></i>
                                </div>
                            </label>
                        </div>

                        <label class="flex items-center space-x-2 text-xs text-zinc-700 cursor-pointer mt-6">
                            <input type="checkbox" required class="rounded border-zinc-400 text-black focus:ring-0">
                            <span>I agree to data processing</span>
                        </label>

                        <!-- Pay and Place Order Button -->
                        <button type="submit"
                            class="w-full bg-zinc-900 text-white font-bold py-4 rounded text-xs tracking-widest mt-8 hover:bg-zinc-800 transition-all uppercase">
                            Pay and Place Order
                        </button>
                    </section>
                </form>

            </div>

            <div class="lg:col-span-5 bg-[#eaeaea] p-6 lg:p-8 rounded-lg border border-zinc-300">
                <h2 class="text-lg font-bold mb-6 border-b border-zinc-300 pb-3">Shopping Bag (<span id="bag-count">0</span>)</h2>

                <!-- Products List (rendered from the checked cart items) -->
                <div id="bag-items" class="divide-y divide-zinc-300"></div>

                <!-- Promocode Section -->
                <div class="mt-6 pt-6 border-t border-zinc-300 flex space-x-2">
                    <input type="text" placeholder="Promocode" class="flex-1 bg-transparent border-b border-zinc-400 py-2 text-xs uppercase placeholder-zinc-500">
                    <button type="button" onclick="alert('Promo code is not available yet.')" class="bg-zinc-300 hover:bg-zinc-400 text-zinc-800 text-xs font-semibold px-5 rounded transition-colors">Apply</button>
                </div>

                <!-- Cost Calculation -->
                <div class="mt-6 pt-6 border-t border-zinc-300 space-y-3 text-sm">
                    <div class="flex justify-between text-zinc-600">
                        <span>Subtotal</span>
                        <span class="font-medium text-zinc-900" id="bag-subtotal">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-zinc-600">
                        <span>Shipping</span>
                        <span class="font-medium text-zinc-900" id="bag-shipping">Free</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-4 border-t border-zinc-300 text-zinc-900">
                        <span>Total:</span>
                        <span id="bag-total">Rp 0</span>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <footer class="w-full bg-black text-white px-6 lg:px-16 pt-16 pb-8">

        <!-- Top Footer Grid: Get in touch & Big Typography -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 pb-16 border-b border-zinc-800">
            <div class="lg:col-span-4">
                <h4 class="text-sm font-semibold tracking-wider text-zinc-400 mb-2">GET IN TOUCH<br>WITH {{ strtoupper($title ?? 'US') }}</h4>
                <p class="text-xs text-zinc-500 max-w-xs">{{ $website->description ?? 'Contact us and our team will be happy to answer all your questions' }}</p>
            </div>
            <div class="lg:col-span-8 flex items-center justify-start lg:justify-end">
                <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tighter uppercase leading-none text-right">
                    THANK YOU<br>FOR YOUR<br>ORDER
                </h2>
            </div>
        </div>

        <!-- Bottom Footer: Copyright -->
        <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500 space-y-4 sm:space-y-0">
            <p>&copy; {{ date('Y') }} {{ $title ?? 'Store' }}. All rights reserved.</p>
            <div class="flex items-center space-x-4 text-xl">
                <i class="fa-brands fa-cc-visa text-white"></i>
                <i class="fa-brands fa-cc-mastercard text-white"></i>
            </div>
        </div>

    </footer>

    <script>
        // ================= CART COOKIE HELPERS (same convention as template.shop.cart) =================
        const pathSegment = window.location.pathname.split('/').filter(Boolean)[0] || 'default';
        const cookieName = 'shopping_cart_' + pathSegment;

        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) === 0) {
                    try { return JSON.parse(decodeURIComponent(c.substring(nameEQ.length, c.length))); }
                    catch (e) { return null; }
                }
            }
            return null;
        }

        function setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            document.cookie = name + "=" + encodeURIComponent(JSON.stringify(value)) +
                "; expires=" + date.toUTCString() + "; path=/; SameSite=Lax";
        }

        // ================= CHECKOUT STATE =================
        // Items explicitly checked in the cart drawer are passed via the
        // "checkout_items" cookie (set by the drawer's Checkout button).
        // Fallback: treat every item in the cart as checked.
        let checkedItems = getCookie('checkout_items_' + pathSegment);
        let cart = getCookie(cookieName) || [];

        if (!Array.isArray(checkedItems) || checkedItems.length === 0) {
            checkedItems = cart.map(item => item.name);
        }

        const bagItems = cart.filter(item => checkedItems.includes(item.name));

        const fmt = n => 'Rp ' + Number(n || 0).toLocaleString('id-ID');
        const shippingCost = () => {
            const active = document.querySelector('.js-courier:checked');
            return active ? parseInt(active.dataset.cost || 0) : 0;
        };
        const bagSubtotal = () => bagItems.reduce((s, it) => s + (Number(it.price) || 0) * (Number(it.quantity) || 0), 0);

        function renderBag() {
            const container = document.getElementById('bag-items');
            const hasItems = bagItems.length > 0;

            document.getElementById('checkout-empty').classList.toggle('hidden', hasItems);
            document.getElementById('checkout-content').classList.toggle('hidden', !hasItems);

            const count = bagItems.reduce((s, it) => s + (Number(it.quantity) || 0), 0);
            document.getElementById('bag-count').innerText = count;
            document.getElementById('header-bag-count').innerText = count;

            // Mirror the checked items into hidden inputs for the order POST.
            // Indexed names (items[0][name]) are REQUIRED: items[][name] makes PHP
            // treat each field as a separate element and validation breaks.
            const mirror = document.getElementById('checkout-items-input');
            mirror.innerHTML = bagItems.map((it, i) =>
                `<input type="hidden" name="items[${i}][name]" value="${String(it.name).replace(/"/g, '&quot;')}">` +
                `<input type="hidden" name="items[${i}][qty]" value="${Number(it.quantity) || 1}">` +
                `<input type="hidden" name="items[${i}][price]" value="${Number(it.price) || 0}">`
            ).join('');

            if (!hasItems) return;

            container.innerHTML = bagItems.map(it => {
                const img = it.image && /^https?:\/\//.test(it.image) ? it.image : (it.image ? '/' + String(it.image).replace(/^\//, '') : '');
                const price = Number(it.price) || 0;
                return `
                <div class="py-4 flex space-x-4 items-start">
                    ${img ? `<img src="${img}" alt="${it.name}" class="w-16 h-20 object-cover rounded bg-zinc-300">` : `<div class="w-16 h-20 rounded bg-zinc-300 flex items-center justify-center text-zinc-400"><i class="fa-solid fa-image"></i></div>`}
                    <div class="flex-1">
                        <div class="flex justify-between text-sm font-bold">
                            <span>${it.name}</span>
                            <span>${fmt(price * it.quantity)}</span>
                        </div>
                        <div class="text-xs text-zinc-500 space-y-0.5 mt-1">
                            <p>${fmt(price)} each</p>
                            <p>Quantity: ${it.quantity}</p>
                        </div>
                    </div>
                </div>`;
            }).join('');

            updateTotals();
        }

        function updateTotals() {
            const subtotal = bagSubtotal();
            const ship = shippingCost();
            document.getElementById('bag-subtotal').innerText = fmt(subtotal);
            document.getElementById('bag-shipping').innerText = ship > 0 ? fmt(ship) : 'Free';
            document.getElementById('bag-total').innerText = fmt(subtotal + ship);
        }

        // Recompute totals when the delivery option changes
        document.querySelectorAll('.js-courier').forEach(radio => {
            radio.addEventListener('change', updateTotals);
        });

        // ================= PLACE ORDER =================
        document.getElementById('checkout-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = 'Processing...';

            try {
                const res = await fetch(this.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: new FormData(this)
                });
                const data = await res.json();

                if (!res.ok || !data.success) {
                    // Surface Laravel validation errors so the buyer sees what to fix
                    const msg = data.errors ? Object.values(data.errors).flat().join('\n') : (data.message || 'Failed to place order. Please try again.');
                    alert(msg);
                    btn.disabled = false;
                    btn.innerText = 'Pay and Place Order';
                    return;
                }

                // Remove the ordered items from the shared cart cookie
                cart = cart.filter(item => !checkedItems.includes(item.name));
                setCookie(cookieName, cart, 7);
                document.cookie = 'checkout_items_' + pathSegment + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Lax';

                window.location.href = data.redirect;
            } catch (err) {
                alert('Failed to place order. Please try again.');
                btn.disabled = false;
                btn.innerText = 'Pay and Place Order';
            }
        });

        // Init
        renderBag();
    </script>

</body>
</html>
