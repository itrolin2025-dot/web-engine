<!-- ABOUT -->
<style>
    /* ===== ABOUT SECTION ===== */
    .about {
        padding: 6rem 1rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .about-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
    }

    .about-image {
        background: linear-gradient(135deg, var(--sky) 0%, var(--lavender) 100%);
        border-radius: 24px;
        overflow: hidden;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .about-image img {
        width: 80%;
        height: 80%;
        object-fit: contain;
    }

    .about-content h2 {
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        margin-bottom: 1.5rem;
        color: var(--dark);
    }

    .about-content p {
        color: var(--gray);
        margin-bottom: 2rem;
        line-height: 1.8;
    }

    .features {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .feature-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--cream);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .feature-text {
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .about-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
    }
</style>
<section class="about">
    <div class="about-grid">
        <div class="about-image" style="
                    width: 100%;
                    min-height: 500px;
                    background-image: url('{{ asset('images/website/elska/about.png') }}');
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: contain;
                ">
            <!-- <img src="{{ asset('images/website/elska/Artboard 18 1.png') }}" alt="Signature Fragrance" style="width: 100%;"
                    text-anchor='middle' font-family='sans-serif'> -->
        </div>
        <div class="about-content">
            <h2>Crafted Beyond Fragrance</h2>
            <p>Perfume kami hadir untuk menghadirkan pengalaman wewangian yang lebih dari sekadar aroma. Setiap
                koleksi dirancang dengan perpaduan premium ingredients, artistic formulation, dan karakter yang
                meninggalkan kesan mendalam.</p>
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">&#10003;</div>
                    <span class="feature-text">Long Lasting Formula</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">&#10003;</div>
                    <span class="feature-text">100% extrait de parfum</span>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">&#10003;</div>
                    <span class="feature-text">crafted by fragrance expert</span>
                </div>
            </div>
        </div>
    </div>
</section>