<!-- REVIEWS -->
<style>
    /* ===== REVIEWS ===== */
    .reviews {
        background: var(--light-gray);
        padding: 5rem 1rem;
    }

    .reviews h2 {
        text-align: center;
        font-size: clamp(1.8rem, 3vw, 2.5rem);
        margin-bottom: 3rem;
    }

    .reviews-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .review-card {
        background: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .review-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .review-meta h4 {
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 600;
    }

    .stars {
        color: #FBBF24;
        font-size: 0.9rem;
        letter-spacing: 2px;
    }

    .review-text {
        color: var(--gray);
        font-size: 0.9rem;
        line-height: 1.7;
    }

    .verified {
        color: #10B981;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.5rem;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {

        .reviews-grid {
            grid-template-columns: 1fr;
        }

    }
</style>
<section class="reviews">
    <h2>Customer Reviews</h2>
    <div class="reviews-grid">
        <div class="review-card">
            <div class="review-header">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Ccircle cx='25' cy='25' r='25' fill='%23ccc'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='12' fill='%23666'%3EA%3C/text%3E%3C/svg%3E"
                    alt="Avatar" class="review-avatar">
                <div class="review-meta">
                    <h4>Charlotte W.</h4>
                    <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                </div>
            </div>
            <p class="review-text">"The fragrance lasts surprisingly long and feels very premium."</p><br>
            <div class="verified">&#10003; Verified Purchase</div>
        </div>

        <div class="review-card">
            <div class="review-header">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Ccircle cx='25' cy='25' r='25' fill='%23ccc'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='12' fill='%23666'%3ES%3C/text%3E%3C/svg%3E"
                    alt="Avatar" class="review-avatar">
                <div class="review-meta">
                    <h4>Sarah M.</h4>
                    <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                </div>
            </div>
            <p class="review-text">"The packaging is beautiful and perfect for gifting."</p><br>
            <div class="verified">&#10003; Verified Purchase</div>
        </div>

        <div class="review-card">
            <div class="review-header">
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='50' height='50'%3E%3Ccircle cx='25' cy='25' r='25' fill='%23ccc'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='12' fill='%23666'%3EL%3C/text%3E%3C/svg%3E"
                    alt="Avatar" class="review-avatar">
                <div class="review-meta">
                    <h4>Lily K.</h4>
                    <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9734;</div>
                </div>
            </div>
            <p class="review-text">"My daily body care routine feels much more luxurious now."</p><br>
            <div class="verified">&#10003; Verified Purchase</div>
        </div>
    </div>
</section>