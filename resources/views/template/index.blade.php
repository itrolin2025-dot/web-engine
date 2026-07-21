<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
</head>

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --coral: #FF9B7A;
            --coral-dark: #E8876A;
            --peach: #FFB8A0;
            --pink: #FFC4D6;
            --lavender: #C4B5FD;
            --mint: #A7F3D0;
            --sky: #BAE6FD;
            --cream: #FFF5F0;
            --dark: #2D2D2D;
            --gray: #6B7280;
            --light-gray: #F3F4F6;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Playfair Display', serif;
        }

        /* ===== FLOATING BUTTONS ===== */
        .floating-actions {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            gap: 1rem;
            z-index: 1000;
        }

        .fab {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, background-color 0.3s, opacity 0.3s;
            cursor: pointer;
            border: none;
        }

        .fab:hover {
            transform: translateY(-5px);
        }

        .fab-wa {
            background-color: #25D366;
            color: white;
        }

        .fab-wa:hover {
            background-color: #128C7E;
        }

        .fab-top {
            background-color: var(--dark);
            font-size: 1.2rem;
            opacity: 0;
            pointer-events: none;
        }

        .fab-top.visible {
            opacity: 1;
            pointer-events: auto;
        }

        /* Broken image styling */
        img {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        img::before {
            content: "Image";
        }
    </style>
</head>

<body>

    @include('template.landing.navbar')

    @include('template.landing.hero')

    @include('template.landing.about')

    @include('template.landing.gallery')

    @include('template.landing.product')

    @include('template.landing.review')

    @include('template.landing.cta')

    @include('template.landing.footer')


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Infinite Gallery Marquee
            const track = document.getElementById('galleryMarquee');
            if (track) {
                const originalImage = track.innerHTML;
                // Add enough copies to fill standard screens (e.g., 15 copies)
                for (let i = 0; i < 15; i++) {
                    track.innerHTML += originalImage;
                }
                // Duplicate the entire content once more to ensure smooth CSS infinite transform: translateX(-50%)
                track.innerHTML += track.innerHTML;
            }

            // Collection Filter
            const filterBtns = document.querySelectorAll('.tab-btn');
            const products = document.querySelectorAll('.product-card');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Remove active class from all buttons
                    filterBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    btn.classList.add('active');

                    const filterValue = btn.getAttribute('data-filter');

                    products.forEach(product => {
                        if (filterValue === 'all') {
                            product.classList.remove('hidden');
                        } else {
                            const categories = product.getAttribute('data-category');
                            if (categories && categories.includes(filterValue)) {
                                product.classList.remove('hidden');
                            } else {
                                product.classList.add('hidden');
                            }
                        }
                    });
                });
            });
        });

        // Hamburger Menu Logic
        const hamburger = document.querySelector('.hamburger');
        const navbarMenu = document.querySelector('.navbar-menu');

        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navbarMenu.classList.toggle('active');
        });

        document.querySelectorAll('.navbar-menu li a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navbarMenu.classList.remove('active');
            });
        });

        // Navbar scroll effect and back to top button
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            const fabTop = document.getElementById('backToTop');

            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }

            if (window.scrollY > 300) {
                fabTop.classList.add('visible');
            } else {
                fabTop.classList.remove('visible');
            }
        });

        document.getElementById('backToTop').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Carousel Slider Logic
        const wrapper = document.querySelector('.carousel-wrapper');
        const slides = document.querySelectorAll('.carousel-slide');
        const nextBtn = document.querySelector('.carousel-btn.next');
        const prevBtn = document.querySelector('.carousel-btn.prev');
        const dots = document.querySelectorAll('.dot');
        let currentSlide = 0;

        function updateCarousel() {
            if (wrapper) {
                wrapper.style.transform = `translateX(-${currentSlide * 100}vw)`;
                dots.forEach(dot => dot.classList.remove('active'));
                if (dots[currentSlide]) dots[currentSlide].classList.add('active');
            }
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % slides.length;
                updateCarousel();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                updateCarousel();
            });
        }

        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateCarousel();
            });
        });

        // Hero Background Slider Logic
        const heroBgSlider = document.querySelector('.hero-slider-bg');
        if (heroBgSlider) {
            const heroSlides = heroBgSlider.querySelectorAll('.hero-slide-bg');
            if (heroSlides.length > 1) {
                let currentBgSlide = 0;
                setInterval(() => {
                    currentBgSlide = (currentBgSlide + 1) % heroSlides.length;
                    heroBgSlider.style.transform = `translateX(-${currentBgSlide * 100}%)`;
                }, 5000);
            }
        }

        // Auto slide
        if (slides.length > 0) {
            setInterval(() => {
                currentSlide = (currentSlide + 1) % slides.length;
                updateCarousel();
            }, 5000);
        }
    </script>

    <!-- FLOATING BUTTONS -->
    <div class="floating-actions">
        <button class="fab fab-top" id="backToTop" aria-label="Back to top">
            &#8593;
        </button>
        <a href="https://wa.me/6281234567890" target="_blank" class="fab fab-wa" aria-label="WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
            </svg>
        </a>
    </div>
</body>

</html>