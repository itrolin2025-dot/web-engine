<!-- CTA -->
<style>
    /* ===== CTA SECTION ===== */
    .cta {
        background-image: url('{{ asset('images/website/elska/footer.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: left;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .cta h2 {
        font-size: clamp(2rem, 4vw, 3rem);
        color: white;
        margin-bottom: 1rem;
        text-align: left;
        padding: 2rem;
        margin-top: -10rem;
        font-family: 'Inter', sans-serif;
    }

    .cta p {
        color: rgba(255, 255, 255, 0.9);
        max-width: 500px;
        text-align: left;
        padding: 2rem;
        margin-top: -4rem;
    }

    .cta-products {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 3rem 0;
        flex-wrap: wrap;
    }

    .cta-products img {
        width: 150px;
        height: 200px;
        object-fit: contain;
        filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.1));
    }

    @media (max-width: 576px) {
        .cta-products img {
            width: 100px;
            height: 140px;
        }
    }
</style>
<section class="cta">
    <h2>Find Your Signature<br>Scent Today</h2>
    <!-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p> -->
</section>