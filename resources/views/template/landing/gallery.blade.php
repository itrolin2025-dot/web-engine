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