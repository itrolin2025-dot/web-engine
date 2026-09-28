<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolin Web — Showcase</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ==== Wrapper gambar utama + caption ====
           Ukuran diatur di wrapper agar caption ikut bergeser saat gambar membesar.
           Geometri (canvas 100%):
           - Main rest : 33% → 67%   (w 34%)
           - Main hover: 30% → 70%   (w 40%)
           - Thumb dlm : 17% → 28.5% dan 71.5% → 83%  → tidak pernah tabrakan */
        #stageWrap {
            width: 56%;
            transition: width .7s cubic-bezier(.22,1,.36,1);
        }
        #stageWrap:hover { width: 58%; }
        @media (min-width: 768px) {
            #stageWrap { width: 34%; }
            #stageWrap:hover { width: 40%; }
        }

        /* Stage: default hitam putih, hover berwarna */
        #stage {
            filter: grayscale(1);
            transition: filter .7s ease;
            cursor: pointer;
        }
        #stageWrap:hover #stage { filter: grayscale(0); }

        /* Caption di bawah foto aktif */
        #caption {
            transition: opacity .45s ease;
        }
        #caption.swapping { opacity: 0; }

        /* Thumbnail: default kecil + hitam putih, hover membesar + berwarna */
        .slide-thumb img {
            transition: transform .6s cubic-bezier(.22,1,.36,1), filter .6s ease;
            filter: grayscale(1);
        }
        .slide-thumb:hover img {
            filter: grayscale(0);
            transform: scale(1.12);
        }

        #stageImgA, #stageImgB { transition: opacity .45s ease; }
        .stage-layer { opacity: 0; }
        .stage-layer.is-active { opacity: 1; }
    </style>
</head>
<body class="bg-white m-0 p-0 min-h-screen">

    <!-- ===== WHITE CANVAS (FULL PAGE) ===== -->
    <div class="relative w-full min-h-screen bg-white text-black overflow-hidden">

        <!-- TOP BAR -->
        <header class="absolute top-0 inset-x-0 grid grid-cols-3 items-center px-6 md:px-8 pt-6 z-30">
            <!-- Logo: two overlapping dots -->
            <div class="flex items-center">
                <span class="w-4 h-4 md:w-5 md:h-5 rounded-full bg-black inline-block"></span>
                <span class="w-4 h-4 md:w-5 md:h-5 rounded-full bg-black inline-block -ml-1.5"></span>
            </div>
            <!-- Brand -->
            <div class="text-center text-xs md:text-sm tabular-nums whitespace-nowrap" id="brand">Rolin Web Lab.</div>
            <!-- Nav -->
            <nav class="flex justify-end gap-4 md:gap-7 text-xs md:text-sm">
                <a href="#" class="hover:opacity-50 transition-opacity">Template</a>
                <a href="#" class="hover:opacity-50 transition-opacity">Section</a>
            </nav>
        </header>

        <!-- SLIDER STAGE -->
        <div class="absolute inset-0">

            <!-- Thumbnails kiri -->
            <div class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 w-[17%] md:w-[11.5%] aspect-[137/168] z-10">
                <div class="slide-thumb w-full h-full overflow-hidden cursor-pointer" data-offset="1">
                    <img src="" alt="thumbnail" class="w-full h-full object-cover">
                </div>
            </div>
            <div class="absolute left-[17%] top-1/2 -translate-y-1/2 w-[11.5%] aspect-[137/168] z-10 hidden md:block">
                <div class="slide-thumb w-full h-full overflow-hidden cursor-pointer" data-offset="2">
                    <img src="" alt="thumbnail" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- Thumbnails kanan -->
            <div class="absolute right-[17%] top-1/2 -translate-y-1/2 w-[11.5%] aspect-[137/168] z-10 hidden md:block">
                <div class="slide-thumb w-full h-full overflow-hidden cursor-pointer" data-offset="3">
                    <img src="" alt="thumbnail" class="w-full h-full object-cover">
                </div>
            </div>
            <div class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 w-[17%] md:w-[11.5%] aspect-[137/168] z-10">
                <div class="slide-thumb w-full h-full overflow-hidden cursor-pointer" data-offset="4">
                    <img src="" alt="thumbnail" class="w-full h-full object-cover">
                </div>
            </div>

            <!-- MAIN IMAGE + CAPTION (caption selalu di bawah foto aktif) -->
            <div id="stageWrap" class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-20">
                <div id="stage" class="relative w-full aspect-[437/556] overflow-hidden">
                    <img id="stageImgA" src="" alt="main slide" class="stage-layer absolute inset-0 w-full h-full object-cover">
                    <img id="stageImgB" src="" alt="" class="stage-layer absolute inset-0 w-full h-full object-cover">
                </div>
                <p id="caption" class="text-center text-xs md:text-sm mt-3 md:mt-4 opacity-80 whitespace-nowrap">Nova Studio</p>
            </div>

            <!-- BOTTOM BAR -->
            <div class="absolute bottom-5 md:bottom-6 inset-x-0 px-6 md:px-8 z-30 h-8">
                <!-- Counter: sejajar dengan tepi kiri foto utama -->
                <span class="absolute left-[22%] md:left-[33%] bottom-0 text-xs md:text-sm tabular-nums" id="counter">1/8</span>
                <!-- Arrows -->
                <div class="absolute right-6 md:right-8 bottom-0 flex items-center gap-5">
                    <button id="prevBtn" class="hover:opacity-50 transition-opacity" aria-label="Previous">
                        <svg class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                    </button>
                    <button id="nextBtn" class="hover:opacity-50 transition-opacity" aria-label="Next">
                        <svg class="w-5 h-5 md:w-6 md:h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

<script>
    const FALLBACK_IMG = '{{ asset('images/default/broken.png') }}';

    // ================= DATA SLIDES (dari tabel template via controller) =================
    const slides = @json($slides);

    // Fallback: jika gambar gagal dimuat -> broken image
    function handleImgError(img) {
        if (img.dataset.fallbackApplied) return;
        img.dataset.fallbackApplied = '1';
        img.src = FALLBACK_IMG;
    }
    document.addEventListener('error', (e) => {
        if (e.target.tagName === 'IMG') handleImgError(e.target);
    }, true);

    // ================= STATE =================
    let current = 0;
    let autoTimer = null;
    const AUTO_MS = 3000;

    const stageWrap = document.getElementById('stageWrap');
    const stage     = document.getElementById('stage');
    const layerA    = document.getElementById('stageImgA');
    const layerB    = document.getElementById('stageImgB');
    let activeLayer = layerA;
    const counter   = document.getElementById('counter');
    const caption   = document.getElementById('caption');
    const thumbs    = [...document.querySelectorAll('.slide-thumb')];

    // Preload semua gambar agar transisi mulus
    slides.forEach(s => { const i = new Image(); i.src = s.src; });

    // ================= RENDER =================
    function render() {
        const slide = slides[current];

        // Crossfade tanpa flash kosong: decode dulu, baru fade
        const nextLayer = (activeLayer === layerA) ? layerB : layerA;
        const img = new Image();
        img.src = slide.src;
        const swap = () => {
            nextLayer.src = slide.src;
            nextLayer.classList.add('is-active');
            activeLayer.classList.remove('is-active');
            activeLayer = nextLayer;
        };
        if (img.complete) { swap(); } else { img.onload = swap; }

        counter.textContent = `${current + 1}/${slides.length}`;

        // Caption di bawah foto aktif: fade out -> ganti teks -> fade in
        caption.classList.add('swapping');
        setTimeout(() => {
            caption.textContent = slide.title;
            caption.classList.remove('swapping');
        }, 220);

        // Thumbnail: preload dulu, baru swap (anti-flash)
        thumbs.forEach(t => {
            const offset = parseInt(t.dataset.offset, 10);
            const s = slides[(current + offset) % slides.length];
            const tImg = t.querySelector('img');
            const pre = new Image();
            pre.src = s.src;
            const assign = () => { tImg.src = s.src; };
            if (pre.complete) { assign(); } else { pre.onload = assign; }
        });
    }

    // ================= NAVIGASI =================
    function goTo(i) {
        current = (i + slides.length) % slides.length;
        render();
        restartAuto();
    }
    const next = () => goTo(current + 1);
    const prev = () => goTo(current - 1);

    // ================= AUTO SLIDE =================
    function startAuto() {
        stopAuto();
        autoTimer = setInterval(next, AUTO_MS);
    }
    function stopAuto() {
        if (autoTimer) { clearInterval(autoTimer); autoTimer = null; }
    }
    function restartAuto() { startAuto(); }

    // Hover gambar utama: pause auto-slide (agar tidak pindah saat sedang dibesar)
    stageWrap.addEventListener('mouseenter', stopAuto);
    stageWrap.addEventListener('mouseleave', startAuto);

    // ================= EVENTS =================
    document.getElementById('nextBtn').addEventListener('click', next);
    document.getElementById('prevBtn').addEventListener('click', prev);

    thumbs.forEach(t => {
        t.addEventListener('click', () => {
            const offset = parseInt(t.dataset.offset, 10);
            goTo(current + offset);
        });
        t.addEventListener('mouseenter', stopAuto);
        t.addEventListener('mouseleave', startAuto);
    });

    // Keyboard
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight') next();
        if (e.key === 'ArrowLeft') prev();
    });

    // ================= CLOCK (opsional: aktifkan bila elemen #clock ada) =================
    function tick() {
        const el = document.getElementById('clock');
        if (!el) return;
        const d = new Date();
        el.textContent =
            String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
    }
    tick();
    setInterval(tick, 10000);

    // ================= INIT =================
    render();
    startAuto();
</script>
</body>
</html>
