<!-- HERO -->
<style>
    /* ===== HERO SECTION ===== */
    .hero {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem 1rem;
        position: relative;
        overflow: hidden;
    }

    .hero-slider-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        z-index: -1;
        transition: transform 1.5s ease-in-out;
    }

    .hero-slide-bg {
        min-width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .hero-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 1;
    }

    .hero-badge {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: white;
        margin-bottom: 1rem;
    }

    .hero h1 {
        font-size: clamp(2.2rem, 6vw, 3.5rem);
        font-weight: 400;
        color: white;
        line-height: 1.2;
        max-width: 800px;
        margin-bottom: 2rem;
    }

    .hero-product {
        width: clamp(280px, 50vw, 500px);
        height: clamp(350px, 60vw, 600px);
        margin: 2rem 0;
        position: relative;
    }

    .hero-product img {
        width: 100%;
        height: auto;
        object-fit: contain;
        margin-top: -8rem;
        margin-bottom: -10rem;
        transform: rotate(-10deg) scale(1.05);
        filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
        position: relative;
        z-index: 1;
    }

    .hero-desc {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        max-width: 500px;
        margin-top: -20rem;
        margin-bottom: 1rem;
        line-height: 1.8;
        padding: 1rem;
    }

    .btn-primary {
        background: white;
        color: var(--coral-dark);
        padding: 1rem 2.5rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: none;
        cursor: pointer;
        display: inline-block;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 576px) {
        .hero h1 {
            font-size: 2.7rem;
        }

        .hero-product {
            width: 85vw;
            height: 105vw;
            max-width: 400px;
            max-height: 500px;
        }

        .hero-product img {
            width: 100%;
            max-width: 100vw;
            margin-top: -7rem;
            padding: 1rem;
            object-fit: contain;
            filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
        }

        .hero-desc {
            margin-top: -16rem;
        }
    }
</style>
<section class="hero">
    <div class="hero-slider-bg">
        <div class="hero-slide-bg" style="background-image: url('{{ asset('images/website/elska/hero-bg.png') }}');">
        </div>
        <div class="hero-slide-bg"
            style="background-image: url('{{ asset('images/website/elska/shop/hero-bg.png') }}');"></div>
    </div>
    <div class="hero-content">
        <div class="hero-badge">New</div>
        <h1>Elevate Every Moment<br>With Signature Fragrance</h1>
        <div class="hero-product">
            <img src="{{ asset('images/website/elska/hero.png') }}" alt="Signature Fragrance" style="width: 100%;"
                text-anchor='middle' font-family='sans-serif'>
        </div>
        <p class="hero-desc">Discover luxury perfumes crafted to express confidence, elegance, and individuality
            through
            every scent.</p>
        <a href="#products" class="btn-primary">Explore Collection</a>
    </div>
</section>