<!-- COLLECTIONS -->
<style>
    /* ===== COLLECTIONS ===== */
    .collections {
        padding: 5rem 1rem;
        text-align: center;
        background: white;
        margin-bottom: -5rem;
    }

    .collections h2 {
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        margin-bottom: 2rem;
    }

    .collection-tabs {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .tab-btn {
        padding: 0.75rem 2rem;
        border: 2px solid var(--light-gray);
        background: white;
        border-radius: 50px;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--gray);
    }

    .tab-btn.active,
    .tab-btn:hover {
        border-color: var(--coral);
        color: var(--coral-dark);
        background: var(--cream);
    }

    /* ===== PRODUCTS ===== */
    .products {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem 5rem;
    }

    .product-card {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
        padding: 2rem;
    }

    .product-card.hidden {
        display: none !important;
    }

    .product-card:nth-child(even) {
        direction: rtl;
    }

    .product-card:nth-child(even)>* {
        direction: ltr;
    }

    .product-card:nth-child(even) .product-info {
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .product-image {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .product-image .shape {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        height: 80%;
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        z-index: 0;
    }

    .product-card:nth-child(1) .shape {
        background: var(--peach);
    }

    .product-card:nth-child(2) .shape {
        background: var(--sky);
    }

    .product-card:nth-child(3) .shape {
        background: var(--pink);
    }

    .product-card:nth-child(4) .shape {
        background: var(--lavender);
    }

    .product-card:nth-child(5) .shape {
        background: var(--mint);
    }

    .product-image img {
        width: 70%;
        height: auto;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 15px 30px rgba(0, 0, 0, 0.1));
    }

    .product-info h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .product-tag {
        color: var(--coral-dark);
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 1rem;
        display: block;
    }

    .product-notes {
        color: var(--gray);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        line-height: 1.8;
    }

    .product-notes strong {
        color: var(--dark);
        display: block;
        margin-bottom: 0.25rem;
    }

    .btn-outline {
        border: 2px solid var(--coral);
        color: var(--coral-dark);
        padding: 0.75rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-block;
        background: transparent;
    }

    .btn-outline:hover {
        background: var(--coral);
        color: white;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .product-card {
            grid-template-columns: 1fr;
            gap: 2rem;
            text-align: center;
        }

        .product-card:nth-child(even) {
            direction: ltr;
        }

        .product-card:nth-child(even) .product-info {
            text-align: center;
            align-items: center;
        }
    }

    @media (max-width: 576px) {
        .features {
            justify-content: center;
        }

        .collection-tabs {
            gap: 0.5rem;
        }

        .tab-btn {
            padding: 0.6rem 1.2rem;
            font-size: 0.8rem;
        }

        .product-card {
            padding: 1rem;
        }
    }
</style>
<section class="collections" id="products">
    <h2>A Scent For Every Personality</h2>
    <div class="collection-tabs">
        <button class="tab-btn active" data-filter="all">All Collection</button>
        <button class="tab-btn" data-filter="floral">Floral Collection</button>
        <button class="tab-btn" data-filter="fresh">Fresh Collection</button>
        <button class="tab-btn" data-filter="fruity">Fruity Collection</button>
    </div>
</section>

<!-- PRODUCTS -->
<section class="products">
    <div class="product-card" data-category="fresh fruity">
        <div class="product-image" style="
                    width: 100%;
                    min-height: 500px;
                    background-image: url('{{ asset('images/website/elska/prod1.png') }}');
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: contain;">
        </div>
        <div class="product-info">
            <span class="product-tag">Extrait de Parfum</span>
            <h3>Date Night</h3>
            <div class="product-notes">
                Aroma fruity, fresh, dan green notes langsung memberikan kesan segar yang ringan dan menyenangkan.
                Berlanjut ke aroma inti yaitu floral berpadu dengan fruity dan sentuhan amber yang hangat,
                menciptakan karakter wangi yang lembut dan sedikit manis. Di fase akhir, woody, fruity, dan ambery
                notes memberikan drydown yang hangat, smooth, dan nyaman, meninggalkan jejak wangi yang soft dan
                berkesan.<br><br>
                <strong>Top Notes:</strong> Fruity, Fresh, Green<br>
                <strong>Middle Notes:</strong> Floral, Fruity, Ambery<br>
                <strong>Base Notes:</strong> Woody, Fruity, Ambery
            </div>
            <a href="#" class="btn-outline">Buy Now</a>
        </div>
    </div>

    <div class="product-card" data-category="floral">
        <div class="product-image" style="
                    width: 100%;
                    min-height: 500px;
                    background-image: url('{{ asset('images/website/elska/prod2.png') }}');
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: contain;">
        </div>
        <div class="product-info">
            <span class="product-tag">Extrait de Parfum</span>
            <h3>Breezy Girl</h3>
            <div class="product-notes">
                Perpaduan fruity, green notes, dan sentuhan floral menghadirkan kesan segar yang bersih, seperti
                habis mandi. Berlanjut ke aroma floral yang dipadukan dengan green dan ambery, menciptakan nuansa
                lembut, tenang, dan sedikit hangat. Di fase akhir, aromanya berubah menjadi perpaduan musky, amber,
                dan lactonic yang memberikan drydown yang calm dan comforting, meninggalkan jejak wangi yang halus,
                bersih, dan terasa dekat di kulit.<br><br>
                <strong>Top Notes:</strong> Fruity, Green, Floral<br>
                <strong>Middle Notes:</strong> Floral, Green, Ambery<br>
                <strong>Base Notes:</strong> Musky, Amber, Lactonic
            </div>
            <a href="#" class="btn-outline">Buy Now</a>
        </div>
    </div>

    <div class="product-card" data-category="fruity">
        <div class="product-image" style="
                    width: 100%;
                    min-height: 500px;
                    background-image: url('{{ asset('images/website/elska/prod3.png') }}');
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: contain;">
        </div>
        <div class="product-info">
            <span class="product-tag">Extrait de Parfum</span>
            <h3>Peppy Cheerleader</h3>
            <div class="product-notes">
                Perpaduan manis dan segar dari buah pear, peach dan berries membuka aroma ini dengan kesan yang
                langsung memikat. Diikuti oleh kelembutan floral khususnya aroma Lily yang berpadu dengan nuansa
                fruity yang manis dan juice. Ditutup dengan sandalwood yang hangat dan vanilla musk yang creamy
                menghadirkan kesan elegan, lembut dan menenangkan sepanjang hari.<br><br>
                <strong>Top Notes:</strong> Pear, Peach, Berries<br>
                <strong>Middle Notes:</strong> Floral, Lily, Fruity<br>
                <strong>Base Notes:</strong> Sandalwood, Vanilla, Musk
            </div>
            <a href="#" class="btn-outline">Buy Now</a>
        </div>
    </div>

    <div class="product-card" data-category="fruity">
        <div class="product-image" style="
                    width: 100%;
                    min-height: 500px;
                    background-image: url('{{ asset('images/website/elska/prod4.png') }}');
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: contain;">
        </div>
        <div class="product-info">
            <span class="product-tag">Extrait de Parfum</span>
            <h3>4Ever Young</h3>
            <div class="product-notes">
                Perpaduan aroma segar dari fruity, cucumber, dan green notes memberikan kesan ringan dan watery yang
                menyegarkan. Berlanjut ke sentuhan floral dengan green dan watermelon yang juicy, menciptakan nuansa
                ceria dan playful. Ditutup dengan aroma musky, sweet, dan fruity menghadirkan wangi yang soft,
                manis, dan clean, nyaman dipakai sepanjang hari.<br><br>
                <strong>Top Notes:</strong> Fruity, Cucumber, Green<br>
                <strong>Middle Notes:</strong> Floral, Green, Watermelon<br>
                <strong>Base Notes:</strong> Musky, Sweet, Fruity
            </div>
            <a href="#" class="btn-outline">Buy Now</a>
        </div>
    </div>
</section>