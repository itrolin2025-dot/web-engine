@php
    $rawContent = $layout->content ?? '';

    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
        // Strip non-standard whitespace/control characters (like raw tabs \t) that break json_decode
        $cleanJson = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $rawContent);
        $content = json_decode($cleanJson, true) ?? json_decode($rawContent, true) ?? [];
    } else {
        $content = [];
    }

    $brand = $content['title_en'] ?? $content['title'] ?? 'nectar';
    $brand_color = $content['title_color'] ?? '#4a1525';
    $background_color = $content['background_color'] ?? '#f8b8cf';
@endphp

<!-- Font tambahan khusus template Candy (Caveat = script, Instrument Serif, DM Sans) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Caveat:wght@600&family=DM+Sans:wght@400;500;700&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

<style>
    .candy-font-serif {
        font-family: 'Playfair Display', 'Instrument Serif', serif;
    }

    .candy-font-script {
        font-family: 'Caveat', cursive;
    }

    /* Canvas untuk particle trail kursor */
    #candy-particle-canvas {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 5;
    }
</style>

<style>
    /* Navbar with exact SVG Scalloped/Wavy Bottom Effect */
    .candy-wavy-header {
        background-color: {{ $background_color }};
        position: relative;
        padding-bottom: 24px;
        /* Space for the wave */
    }

    .candy-wavy-svg-container {
        position: absolute;
        bottom: -18px;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
        z-index: 10;
    }

    .candy-wavy-svg-container svg {
        position: relative;
        display: block;
        width: 100%;
        height: 20px;
    }
</style>

<canvas id="candy-particle-canvas"></canvas>

<header class="candy-wavy-header py-4 px-6 md:px-12 shadow-sm relative z-20">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <!-- Left Navigation Links -->
        <nav class="hidden md:flex items-center space-x-8 text-xs font-semibold uppercase tracking-wider"
            style="color: {{ $brand_color }};">
            <a href="#cart" class="hover:opacity-75 transition-opacity">Find the Cart</a>
            <a href="#event" class="hover:opacity-75 transition-opacity">Book an Event</a>
        </nav>

        <!-- Mobile menu button -->
        <div class="md:hidden flex items-center">
            <button id="candy-mobile-menu-btn" class="focus:outline-none" style="color: {{ $brand_color }};">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>

        <!-- Logo in Center -->
        <div class="absolute left-1/2 transform -translate-x-1/2">
            <span class="candy-font-serif italic font-bold text-3xl md:text-4xl tracking-tight"
                style="color: {{ $brand_color }};">{{ $brand }}</span>
        </div>

        <!-- Right Navigation & Cart -->
        <div class="flex items-center space-x-6">
            <nav class="hidden md:flex items-center space-x-8 text-xs font-semibold uppercase tracking-wider"
                style="color: {{ $brand_color }};">
                <a href="#club" class="hover:opacity-75 transition-opacity">Candy Club</a>
                <a href="#buy" class="hover:opacity-75 transition-opacity">Buy Candy</a>
            </nav>
            <div
                class="relative cursor-pointer group flex items-center space-x-1 bg-white/40 px-3 py-1.5 rounded-full border border-pink-200 shadow-sm hover:bg-white/70 transition-all"
                onclick="candyOpenCartModal()">
                <i class="fa-solid fa-bag-shopping text-sm" style="color: {{ $brand_color }};"></i>
                <span id="cart-badge" class="text-xs font-bold" style="color: {{ $brand_color }};">0</span>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Dropdown -->
    <div id="candy-mobile-menu"
        class="hidden md:hidden pt-4 pb-2 border-t border-pink-300/40 mt-3 flex-col space-y-2 text-xs font-semibold uppercase tracking-wider"
        style="color: {{ $brand_color }};">
        <a href="#cart" class="py-1">Find the Cart</a>
        <a href="#event" class="py-1">Book an Event</a>
        <a href="#club" class="py-1">Candy Club</a>
        <a href="#buy" class="py-1">Buy Candy</a>
    </div>

    <!-- Precise SVG Wavy/Scalloped Bottom Edge -->
    <div class="candy-wavy-svg-container">
        <svg viewBox="0 0 1200 24" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M0,0 C30,20 60,20 90,0 C120,20 150,20 180,0 C210,20 240,20 270,0 C300,20 330,20 360,0 C390,20 420,20 450,0 C480,20 510,20 540,0 C570,20 600,20 630,0 C660,20 690,20 720,0 C750,20 780,20 810,0 C840,20 870,20 900,0 C930,20 960,20 990,0 C1020,20 1050,20 1080,0 C1110,20 1140,20 1170,0 C1200,20 1230,20 1260,0 L1200,0 L0,0 Z"
                fill="{{ $background_color }}"></path>
        </svg>
    </div>
</header>

<!-- Cart Modal (dibuka dari ikon navbar) -->
<div id="candy-cart-modal"
    class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl relative border border-pink-100">
        <button onclick="candyCloseCartModal()" class="absolute top-4 right-4 text-gray-400 hover:text-[#4a1525]">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <h3 class="candy-font-serif italic font-bold text-2xl text-[#4a1525] mb-4">Your Nectar Cart</h3>
        <div id="cart-content" class="py-6 text-center text-[#4a1525]/70 text-sm">
            Keranjang kamu masih kosong. Yuk pilih permen favoritmu!
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
            <span class="font-bold text-sm text-[#4a1525]">Total: Rp 0</span>
            <button onclick="candyCheckoutAction()"
                class="bg-[#f8b8cf] hover:bg-[#f29ebb] text-[#4a1525] text-xs font-bold uppercase tracking-wider py-3 px-6 rounded-full shadow transition-all">
                Checkout Sekarang
            </button>
        </div>
    </div>
</div>

<!-- Notification Toast (dipakai semua section candy) -->
<div id="candy-toast"
    class="fixed bottom-6 right-6 bg-[#4a1525] text-white px-5 py-3 rounded-2xl shadow-xl text-xs font-medium tracking-wide transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center space-x-2">
    <i class="fa-solid fa-candy-cane text-pink-300 text-sm"></i>
    <span id="candy-toast-message">Notifikasi Nectar</span>
</div>

<script>
    // ===== Mobile Menu Toggle =====
    (function () {
        var btn = document.getElementById('candy-mobile-menu-btn');
        var menu = document.getElementById('candy-mobile-menu');
        if (btn && menu) {
            btn.addEventListener('click', function () {
                menu.classList.toggle('hidden');
                menu.classList.toggle('flex');
            });
        }
    })();

    // ===== Cart Modal Handlers =====
    var candyCartCount = 0;

    function candyOpenCartModal() {
        var modal = document.getElementById('candy-cart-modal');
        if (modal) modal.classList.remove('hidden');
    }

    function candyCloseCartModal() {
        var modal = document.getElementById('candy-cart-modal');
        if (modal) modal.classList.add('hidden');
    }

    function candyJoinClubAction() {
        candyCartCount++;
        var badge = document.getElementById('cart-badge');
        if (badge) badge.textContent = candyCartCount;
        candyShowToast("Berhasil bergabung dengan Nectar Candy Club! 🎉");
    }

    function candyCheckoutAction() {
        candyShowToast("Fitur checkout sedang disiapkan. Stay sweet! 🍬");
        candyCloseCartModal();
    }

    // ===== Toast Notification Helper =====
    function candyShowToast(msg) {
        var toast = document.getElementById('candy-toast');
        var message = document.getElementById('candy-toast-message');
        if (!toast || !message) return;
        message.textContent = msg;
        toast.classList.remove('translate-y-20', 'opacity-0');
        setTimeout(function () {
            toast.classList.add('translate-y-20', 'opacity-0');
        }, 3000);
    }

    // ===== Interactive Cursor Particle Trail Effect =====
    (function () {
        var canvas = document.getElementById('candy-particle-canvas');
        if (!canvas) return;
        var ctx = canvas.getContext('2d');
        var particles = [];
        var candyEmojis = ['🍬', '🍭', '🍓', '🍒', '⭐', '🍥'];

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        function Particle(x, y) {
            this.x = x;
            this.y = y;
            this.emoji = candyEmojis[Math.floor(Math.random() * candyEmojis.length)];
            this.size = Math.random() * 12 + 10;
            this.speedX = (Math.random() - 0.5) * 2;
            this.speedY = Math.random() * -1.5 - 0.5;
            this.alpha = 1;
            this.decay = Math.random() * 0.02 + 0.015;
        }
        Particle.prototype.update = function () {
            this.x += this.speedX;
            this.y += this.speedY;
            this.alpha -= this.decay;
        };
        Particle.prototype.draw = function () {
            ctx.save();
            ctx.globalAlpha = Math.max(0, this.alpha);
            ctx.font = this.size + 'px sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.fillText(this.emoji, this.x, this.y);
            ctx.restore();
        };

        var lastSpawn = 0;
        window.addEventListener('mousemove', function (e) {
            var now = Date.now();
            if (now - lastSpawn > 50) {
                particles.push(new Particle(e.clientX, e.clientY));
                lastSpawn = now;
            }
        });

        window.addEventListener('touchmove', function (e) {
            if (e.touches.length > 0) {
                var touch = e.touches[0];
                particles.push(new Particle(touch.clientX, touch.clientY));
            }
        }, { passive: true });

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            for (var i = particles.length - 1; i >= 0; i--) {
                particles[i].update();
                particles[i].draw();
                if (particles[i].alpha <= 0) {
                    particles.splice(i, 1);
                }
            }
            requestAnimationFrame(animateParticles);
        }
        animateParticles();
    })();
</script>
