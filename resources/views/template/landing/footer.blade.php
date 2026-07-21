<!-- FOOTER -->
<style>
    /* ===== FOOTER ===== */
    footer {
        background: var(--dark);
        color: white;
        padding: 4rem 1rem 2rem;
    }

    .footer-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }

    .footer-brand h3 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .footer-brand p {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        line-height: 1.7;
    }

    .footer-newsletter {
        display: flex;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }

    .footer-newsletter input {
        flex: 1;
        padding: 0.75rem 1rem;
        border-radius: 50px;
        border: none;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
    }

    .footer-newsletter button {
        background: var(--coral);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        cursor: pointer;
        font-weight: 600;
    }

    .footer-links h4 {
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1.5rem;
    }

    .footer-links a {
        display: block;
        color: rgba(255, 255, 255, 0.6);
        text-decoration: none;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: white;
    }

    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 2rem;
        text-align: center;
        color: rgba(255, 255, 255, 0.4);
        font-size: 0.85rem;
    }

    .social-links {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1rem;
    }

    .social-links a {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        text-decoration: none;
        transition: background 0.3s;
    }

    .social-links a:hover {
        background: var(--coral);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 992px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 576px) {
        .footer-grid {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .footer-newsletter {
            flex-direction: column;
        }

        .footer-newsletter button {
            width: 100%;
        }

    }
</style>
<footer>
    <div class="footer-grid">
        <div class="footer-brand">
            <h3>Signature Fragrance</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore
                et dolore magna aliqua.</p>
            <div class="footer-newsletter">
                <input type="email" placeholder="Enter your email">
                <button>Subscribe</button>
            </div>
        </div>
        <div class="footer-links">
            <h4>Explore</h4>
            <a href="#">New Arrivals</a>
            <a href="#">Best Sellers</a>
            <a href="#">Collections</a>
            <a href="#">Gift Sets</a>
        </div>
        <div class="footer-links">
            <h4>Company</h4>
            <a href="#">About Us</a>
            <a href="#">Careers</a>
            <a href="#">Press</a>
            <a href="#">Sustainability</a>
        </div>
        <div class="footer-links">
            <h4>Support</h4>
            <a href="#">Contact Us</a>
            <a href="#">FAQs</a>
            <a href="#">Shipping</a>
            <a href="#">Returns</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2026 Signature Fragrance. All rights reserved.</p>
        <div class="social-links">
            <a href="#">f</a>
            <a href="#">t</a>
            <a href="#">i</a>
            <a href="#">y</a>
        </div>
    </div>
</footer>