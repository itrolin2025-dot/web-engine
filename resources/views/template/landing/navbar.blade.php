<!-- NAVBAR -->
<style>
    /* ===== NAVBAR ===== */
    .navbar {
        background-color: transparent;
        padding: 1rem 5%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        transition: background-color 0.3s ease, padding 0.3s ease, box-shadow 0.3s ease;
    }

    .navbar.scrolled {
        background-color: white;
        padding: 0.8rem 5%;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .navbar-logo {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .navbar.scrolled .navbar-logo {
        color: var(--dark);
    }

    .navbar-menu {
        display: flex;
        gap: 2.5rem;
        list-style: none;
    }

    .navbar-menu li a {
        text-decoration: none;
        color: white;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .navbar.scrolled .navbar-menu li a {
        color: var(--dark);
    }

    .navbar-menu li a:hover {
        color: var(--coral);
    }

    .hamburger {
        display: none;
        flex-direction: column;
        cursor: pointer;
        gap: 5px;
        z-index: 1001;
    }

    .hamburger span {
        width: 25px;
        height: 3px;
        background-color: white;
        border-radius: 2px;
        transition: all 0.3s ease;
    }

    .navbar.scrolled .hamburger span {
        background-color: var(--dark);
    }

    .hamburger.active span:nth-child(1) {
        transform: translateY(8px) rotate(45deg);
    }

    .hamburger.active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger.active span:nth-child(3) {
        transform: translateY(-8px) rotate(-45deg);
    }

    .hamburger.active span {
        background-color: var(--dark) !important;
    }

    @media (max-width: 768px) {
        .navbar {
            padding: 0.8rem 5%;
        }

        .navbar.scrolled {
            padding: 0.6rem 5%;
        }

        .hamburger {
            display: flex;
        }

        .navbar-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100vh;
            background-color: white;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 2rem;
            transition: right 0.3s ease;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
        }

        .navbar-menu.active {
            right: 0;
        }

        .navbar-menu li a {
            color: var(--dark);
            font-size: 1.2rem;
        }

        .navbar-logo {
            z-index: 1001;
        }
    }

    @media (max-width: 576px) {
        .navbar-logo {
            font-size: 1.2rem;
        }

        .navbar-menu {
            width: 80%;
        }
    }
</style>

<nav class="navbar">
    <a href="#" class="navbar-logo">Elska</a>
    <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <ul class="navbar-menu">
        <li><a href="#">Home</a></li>
        <li><a href="./about.html">About</a></li>
        <li><a href="./shop.html">Shop</a></li>
    </ul>
</nav>