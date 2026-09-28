<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rolin Web — Showcase</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- Inter font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ===== STAGE (foto besar kanan) ===== */
        #stageImgA, #stageImgB { transition: opacity .45s ease; }
        .stage-layer { opacity: 0; }
        .stage-layer.is-active { opacity: 1; }

        #stage { cursor: pointer; }
        #stageLink:not([href]) #stage { cursor: default; }

        /* ===== JUDUL RAKSASA ===== */
        #bigTitle {
            transition: opacity .3s ease;
            letter-spacing: -0.02em;
        }
        #bigTitle.swapping { opacity: 0; }

        /* ===== CAPTION ROW di bawah foto ===== */
        #captionRight { transition: opacity .3s ease; }
        #captionRight.swapping { opacity: 0; }

        /* ===== STRIP THUMBNAIL =====
           Default: hitam putih. Hover / aktif: berwarna. */
        .thumb img {
            filter: grayscale(1);
            transition: filter .4s ease, transform .6s cubic-bezier(.22,1,.36,1);
        }
        .thumb:hover img, .thumb.is-active img {
            filter: grayscale(0);
        }
        .thumb:hover img { transform: scale(1.06); }
        .thumb .num { transition: opacity .3s ease; }
        .thumb.is-active .num { opacity: 1 !important; font-weight: 700; }

        #thumbStrip::-webkit-scrollbar { height: 4px; }
        #thumbStrip::-webkit-scrollbar-thumb { background: #d4d4d4; border-radius: 2px; }
    </style>
</head>
<body class="bg-white m-0 p-0 min-h-screen">

    <!-- ===== WHITE CANVAS (FULL PAGE, tanpa bingkai hitam) ===== -->
    <div class="relative w-full bg-white text-black flex flex-col overflow-x-hidden">

        <!-- ===== VIEWPORT PERTAMA (persis 1 layar: nav + main + strip) ===== -->
        <div class="flex flex-col h-screen min-h-[600px]">

        <!-- ===== NAV (kiri atas: brand + item aktif ber-parentheses) ===== -->
        <header class="flex items-center gap-5 md:gap-8 px-4 md:px-8 pt-4 md:pt-5 pb-2 text-[10px] md:text-[11px] uppercase tracking-wide z-30">
            <!-- Brand -->
            <a href="?tab=client" class="text-xl md:text-2xl font-black normal-case tracking-tight leading-none mr-2 md:mr-5" aria-label="Rolin home">Rolin.</a>
            <a href="?tab=client"
               class="transition-opacity {{ $tab === 'client' ? 'font-bold' : 'hover:opacity-50' }}">
                {{ $tab === 'client' ? '[Client Website]' : 'Client Website' }}
            </a>
            <a href="?tab=template"
               class="transition-opacity {{ $tab === 'template' ? 'font-bold' : 'hover:opacity-50' }}">
                {{ $tab === 'template' ? '[Template]' : 'Template' }}
            </a>
            <a href="?tab=section"
               class="transition-opacity {{ $tab === 'section' ? 'font-bold' : 'hover:opacity-50' }}">
                {{ $tab === 'section' ? '[Section]' : 'Section' }}
            </a>
            @if($tab === 'section')
                {{-- Dropdown filter slug (group by slug) — di samping kanan [Section] --}}
                <label class="flex items-center gap-1.5 ml-1 normal-case" aria-label="Filter section by slug">
                    <select onchange="if (this.value) window.location = '?tab=section&slug=' + encodeURIComponent(this.value); else window.location = '?tab=section';"
                            class="bg-white border border-neutral-300 rounded px-1.5 py-0.5 text-[10px] md:text-[11px] cursor-pointer hover:border-neutral-500 focus:outline-none focus:border-black transition-colors">
                        <option value="" {{ $sectionSlug ? '' : 'selected' }}>All Slugs ({{ $sectionSlugs->sum('total') }})</option>
                        @foreach($sectionSlugs as $s)
                            <option value="{{ $s->slug }}" {{ $sectionSlug === $s->slug ? 'selected' : '' }}>
                                {{ $s->slug }} ({{ $s->total }})
                            </option>
                        @endforeach
                    </select>
                </label>
            @endif
        </header>

        <!-- ===== MAIN: kiri (paragraf + counter + judul) | kanan (foto besar) ===== -->
        <div class="flex-1 grid grid-cols-1 md:grid-cols-[1fr_38%]">

            <!-- KOLOM KIRI (desktop: kolom 1; mobile: di bawah foto) -->
            <div class="order-last md:order-none flex flex-col px-4 md:px-8 pt-4 md:pt-5 pb-2">
                <!-- Paragraf deskripsi (kiri atas kolom kiri) -->
                <br>
                <p class="max-w-xs md:max-w-sm text-[10px] md:text-[11px] leading-relaxed text-justify text-neutral-500">
                    A collection of digital spaces, thoughtfully curated from real projects, ready-made templates, and carefully crafted components. Each one tells a different story — shaped by its purpose, its personality, and the people behind it.

                    Some are quiet. Some are expressive. Some are built to inspire, while others are ready to become something entirely new. Explore the collection and discover a starting point for your next idea.
                </p>

                <!-- Counter + Judul raksasa -->
                <div class="mt-auto pt-10">
                    <p id="counter" class="text-[10px] md:text-xs tabular-nums mb-2 md:mb-3">(01 / 09)</p>
                    <h1 id="bigTitle" class="uppercase font-black leading-[0.95] text-4xl sm:text-5xl md:text-6xl lg:text-7xl">
                        Portraits
                    </h1>
                </div>
            </div>

            <!-- FOTO BESAR: SEBELAH KANAN (desktop: kolom 2; mobile: di atas) -->
            <div class="order-first md:order-none flex flex-col h-[46vh] md:h-auto">
                <div class="relative flex-1">
                    <!-- Tab Client: klik item membuka URL website yang terdaftar di tab baru -->
                    <a id="stageLink" href="#" target="_blank" rel="noopener" class="block absolute inset-0" aria-label="Open website">
                        <div id="stage" class="relative w-full h-full overflow-hidden bg-white">
                            <img id="stageImgA" src="" alt="main slide"
                                 class="stage-layer absolute inset-0 w-full h-full {{ $tab === 'client' ? 'object-contain object-center' : 'object-contain object-top' }}">
                            <img id="stageImgB" src="" alt=""
                                 class="stage-layer absolute inset-0 w-full h-full {{ $tab === 'client' ? 'object-contain object-center' : 'object-contain object-top' }}">
                        </div>
                    </a>
                </div>
                <!-- Caption row di bawah foto: hanya / 01 (kiri) dan NAMA (kanan) -->
                <div class="flex items-center justify-between px-2 md:px-3 py-2 text-[9px] md:text-[10px] uppercase tracking-wide">
                    <span id="captionLeft" class="tabular-nums">/ 01</span>
                    <span id="captionRight" class="opacity-70 truncate max-w-[70%] text-right">The Quiet Watch</span>
                </div>
            </div>
        </div>

        <!-- ===== STRIP THUMBNAIL + NAVIGASI =====
             Thumbnail tidak full width (berhenti sebelum area navigasi);
             panah kiri/kanan sejajar dengan baris thumbnail di kanan. -->
        <div class="px-4 md:px-8 pb-4 md:pb-6 pt-2 flex items-end gap-4">
            <div id="thumbStrip" class="flex gap-2 md:gap-3 overflow-x-auto pb-1 flex-1"></div>
            <div class="shrink-0 flex items-center gap-4 md:gap-5 pb-1">
                <button id="prevBtn" class="hover:opacity-40 transition-opacity" aria-label="Previous">
                    <svg class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                </button>
                <button id="nextBtn" class="hover:opacity-40 transition-opacity" aria-label="Next">
                    <svg class="w-4 h-4 md:w-5 md:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        </div>
        <!-- ===== /VIEWPORT PERTAMA ===== -->

        <!-- ===== FOOTER (di bawah layar pertama — harus scroll untuk terlihat) ===== -->
        <footer class="border-t border-neutral-200">
            <div class="px-4 md:px-8 py-6 md:py-8 flex flex-col md:flex-row md:items-end md:justify-between gap-6">
                <!-- Brand + tagline -->
                <div>
                    <p class="text-lg md:text-xl font-black uppercase leading-none tracking-tight">Rolin Web</p>
                    <p class="mt-2 max-w-xs text-[10px] md:text-[11px] leading-relaxed text-neutral-500">
                        From the first idea to the final detail, we focus on making the process simple without taking away the creativity behind it.
                    </p>
                </div>

                <!-- Menu -->
                <nav class="flex flex-wrap gap-x-6 gap-y-2 text-[10px] md:text-[11px] uppercase tracking-wide">
                    <a href="?tab=client" class="transition-opacity {{ $tab === 'client' ? 'font-bold' : 'hover:opacity-50' }}">Client Website</a>
                    <a href="?tab=template" class="transition-opacity {{ $tab === 'template' ? 'font-bold' : 'hover:opacity-50' }}">Template</a>
                    <a href="?tab=section" class="transition-opacity {{ $tab === 'section' ? 'font-bold' : 'hover:opacity-50' }}">Section</a>
                </nav>

                <!-- Kontak + copyright -->
                <div class="text-[10px] md:text-[11px] text-neutral-500 md:text-right">
                    <p><a href="mailto:hello@rolin.web" class="hover:text-black transition-colors">hello@rolin.web</a></p>
                    <p class="mt-1">© {{ date('Y') }} Rolin Web. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

<script>
    const FALLBACK_IMG = '{{ asset('images/default/broken.png') }}';

    // ================= DATA SLIDES (sesuai tab navbar yang aktif) =================
    const slides = @json($slides);

    // Tab yang itemnya punya URL: Client (website customer) & Template (preview template)
    const HAS_URL_TAB = @json(in_array($tab, ['client', 'template']));
    // Client & Template sama-sama membuka di tab baru
    const OPEN_IN_NEW = HAS_URL_TAB;

    // Fit thumbnail: client (logo) object-contain agar tidak terpotong;
    // template/section (screenshot tinggi) object-cover object-top.
    const THUMB_FIT = OPEN_IN_NEW ? 'object-contain' : 'object-cover object-top';

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

    const stage     = document.getElementById('stage');
    const layerA    = document.getElementById('stageImgA');
    const layerB    = document.getElementById('stageImgB');
    let activeLayer = layerA;
    const counter   = document.getElementById('counter');
    const bigTitle  = document.getElementById('bigTitle');
    const captionLeft  = document.getElementById('captionLeft');
    const captionRight = document.getElementById('captionRight');
    const stageLink = document.getElementById('stageLink');
    const thumbStrip = document.getElementById('thumbStrip');

    const pad2 = (n) => String(n).padStart(2, '0');

    // Preload semua gambar agar transisi mulus
    slides.forEach(s => { const i = new Image(); i.src = s.src; });

    // ================= BUILD THUMBNAIL STRIP (semua slide) =================
    slides.forEach((s, i) => {
        const btn = document.createElement('button');
        btn.className = 'thumb shrink-0 flex-1 min-w-[56px] max-w-[130px] text-left';
        btn.innerHTML =
            `<span class="num block text-[8px] md:text-[9px] tabular-nums mb-1 opacity-50">/${pad2(i + 1)}</span>` +
            `<span class="block aspect-[4/5] overflow-hidden bg-neutral-100 pointer-events-none">` +
            `<img src="${s.src}" alt="${s.title}" class="w-full h-full ${THUMB_FIT}" loading="lazy"></span>`;
        btn.addEventListener('click', () => goTo(i));
        btn.addEventListener('mouseenter', stopAuto);
        btn.addEventListener('mouseleave', startAuto);
        thumbStrip.appendChild(btn);
    });
    const thumbs = [...thumbStrip.querySelectorAll('.thumb')];

    // ================= RENDER =================
    function render() {
        const slide = slides[current];

        // Tab Client & Template: item aktif di-link ke URL-nya
        // (Client -> website customer di tab baru, Template -> halaman preview).
        // Tab Section tidak punya URL -> link dimatikan.
        if (HAS_URL_TAB && slide.url && slide.url !== '#') {
            stageLink.href = slide.url;
            stageLink.target = '_blank';
            stageLink.classList.remove('pointer-events-none');
            stage.style.cursor = 'pointer';
        } else {
            stageLink.removeAttribute('href');
            stageLink.classList.add('pointer-events-none');
            stage.style.cursor = 'default';
        }

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

        // Counter format (01 / 19)
        counter.textContent = `(${pad2(current + 1)} / ${pad2(slides.length)})`;

        // Caption row di bawah foto
        captionLeft.textContent = `/ ${pad2(current + 1)}`;
        captionRight.classList.add('swapping');
        setTimeout(() => {
            captionRight.textContent = (slide.title || '').toUpperCase();
            captionRight.classList.remove('swapping');
        }, 200);

        // Judul raksasa: fade out -> ganti -> fade in
        bigTitle.classList.add('swapping');
        setTimeout(() => {
            bigTitle.textContent = slide.title || '';
            bigTitle.classList.remove('swapping');
        }, 220);

        // State thumbnail aktif + scroll agar terlihat
        thumbs.forEach((t, i) => t.classList.toggle('is-active', i === current));
        if (thumbs[current]) {
            thumbs[current].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
        }
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

    // Hover gambar utama: pause auto-slide (agar tidak pindah saat sedang dilihat)
    stage.addEventListener('mouseenter', stopAuto);
    stage.addEventListener('mouseleave', startAuto);

    // ================= EVENTS =================
    document.getElementById('nextBtn').addEventListener('click', next);
    document.getElementById('prevBtn').addEventListener('click', prev);

    // Keyboard
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowRight') next();
        if (e.key === 'ArrowLeft') prev();
    });

    // ================= INIT =================
    render();
    startAuto();
</script>
</body>
</html>
