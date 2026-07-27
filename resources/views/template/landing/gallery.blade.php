<!-- GALLERY STRIP -->
<style>
    /* ===== GALLERY STRIP ===== */
    .gallery-strip {
        background: linear-gradient(90deg, var(--lavender) 0%, var(--pink) 50%, var(--peach) 100%);
        padding: 0;
        overflow: hidden;
    }

    .gallery-track {
        display: flex;
        animation: scroll 400s linear infinite;
        width: max-content;
    }

    .gallery-track img {
        width: 350px;
        height: 350px;
        object-fit: cover;
        flex-shrink: 0;
    }

    @keyframes scroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    /* ===== CAROUSEL SLIDER ===== */
    .carousel-slider-container {
        width: 100vw;
        position: relative;
        overflow: hidden;
        background: var(--light-gray);
    }

    .carousel-wrapper {
        display: flex;
        width: 300vw;
        transition: transform 0.5s ease-in-out;
    }

    .carousel-slide {
        width: 100vw;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .carousel-slide img {
        width: 100%;
        height: auto;
        object-fit: cover;
        max-height: 90vh;
    }
</style>
<section class="gallery-strip">
    <div class="gallery-track" id="galleryMarquee">
        <img src="{{ asset('images/website/elska/slide1.png') }}" alt="Gallery">
        <img src="{{ asset('images/website/elska/slide2.png') }}" alt="Gallery">
        <img src="{{ asset('images/website/elska/slide3.png') }}" alt="Gallery">
        <img src="{{ asset('images/website/elska/slide4.png') }}" alt="Gallery">
        <img src="{{ asset('images/website/elska/slide5.png') }}" alt="Gallery">
    </div>
</section>

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
</script>