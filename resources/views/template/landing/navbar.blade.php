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
        align-items: center;
    }

    .navbar-menu li a {
        text-decoration: none;
        color: white;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 1rem;
        transition: color 0.3s ease;
        display: inline-block;
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

    /* ===== DROPDOWN STYLING ===== */
    .dropdown {
        position: relative;
    }

    .dropdown-toggle {
        cursor: pointer;
        user-select: none;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: calc(100% + 0.5rem);
        left: 0;
        background-color: white;
        min-width: 180px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 0.5rem 0;
        list-style: none;
        z-index: 1002;
    }

    .dropdown.active .dropdown-menu {
        display: block;
    }

    .dropdown-menu li {
        width: 100%;
        margin: 0;
    }

    .dropdown-menu li a {
        color: var(--dark) !important;
        padding: 0.6rem 1.2rem;
        display: block;
        font-size: 0.9rem;
        transition: background-color 0.2s ease, color 0.2s ease;
        white-space: nowrap;
    }

    .dropdown-menu li a:hover {
        background-color: var(--cream);
        color: var(--coral) !important;
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

        .navbar-logo img {
            height: 40px;
        }

        .dropdown {
            width: 100%;
            text-align: center;
        }

        .dropdown-menu {
            position: static;
            box-shadow: none;
            background-color: rgba(0, 0, 0, 0.03);
            border-radius: 8px;
            margin-top: 0.5rem;
            width: 100%;
        }

        .dropdown-menu li a {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
    }

    @media (max-width: 576px) {
        .navbar-logo {
            font-size: 1.1rem;
        }

        .navbar-logo img {
            height: 32px;
        }

        .navbar-menu {
            width: 80%;
        }
    }
</style>

@php
    $navContent = isset($layout->content) ? json_decode($layout->content, true) : [];
    
    // Jika ada data dummy navbarPresets dari controller, gabungkan
    if(isset($navbarPresets)) {
        $navContent = array_merge($navbarPresets, $navContent);
    }

    //ambil logo
    $logoFile = $navContent['image'] ?? null;
    $logo = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;

    //ambil nama brand
    $brand = $navContent['brand'] ?? ($website->title ?? 'My Brand');
    //ambil menu
    $menus = $navContent['menus'] ?? [];
    //ambil tombol cta
    $cta_text = $navContent['cta_text'] ?? 'Contact Us';
    $cta_url = $navContent['cta_url'] ?? '#';
    $cta_color = $navContent['cta_color'] ?? '#000000ff';
@endphp

<nav class="navbar">
    <a href="#" class="navbar-logo">
        @if($logo)
            <img src="{{ asset($logo) }}" alt="{{ $brand }}" class="h-20 w-auto object-contain">
        @else
            <span class="text-xl font-bold tracking-tight" style="color: {{ $cta_color }}">{{ $brand }}</span>
        @endif
    </a>
    <div class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </div>
    <ul class="navbar-menu">
        @foreach($menus as $menu)
            @php
                $menuUrl = $menu['url'] ?? '#';
                if (!empty($menuUrl) && $menuUrl !== '#' && !str_starts_with($menuUrl, 'http') && !str_starts_with($menuUrl, '/')) {
                    $menuUrl = '/' . ($website->domain ?? '') . '/' . ltrim($menuUrl, '/');
                }
            @endphp
            @if(!empty($menu['children']))
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">{{ $menu['label'] }} &#9662;</a>
                    <ul class="dropdown-menu">
                        @foreach($menu['children'] as $child)
                            @php
                                $childUrl = $child['url'] ?? '#';
                                if (!empty($childUrl) && $childUrl !== '#' && !str_starts_with($childUrl, 'http') && !str_starts_with($childUrl, '/')) {
                                    $childUrl = '/' . ($website->domain ?? '') . '/' . ltrim($childUrl, '/');
                                }
                            @endphp
                            <li>
                                <a href="{{ $childUrl }}">
                                    {{ $child['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                @php
                    $routeName = !empty($menu['url']) ? 'pages' : 'template';
                    $routeParams = ($routeName === 'template')
                        ? ['client' => $website->domain ?? '']
                        : ['client' => $website->domain ?? '', 'pages' => $menu['url']];
                @endphp
                {{-- Menu biasa --}}
                <li><a href="{{ route($routeName, $routeParams) }}">{{ $menu['label'] }}</a>
            </li>
            @endif
        @endforeach
        <div class="flex items-center gap-5 text-lg text-white">

            {{-- Search --}}
            <!-- <button type="button"
                    class="hover:text-black transition-colors"
                    aria-label="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button> -->

            {{-- Cart --}}
            <li>
                <a type="button"
                        onclick="toggleCartDrawer()"
                        class="relative text-white hover:text-black transition-colors"
                        aria-label="Cart">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span id="cart-badge"
                        class="absolute -top-2 -right-2 bg-pink-500 text-white text-[10px]
                                w-4 h-4 rounded-full flex items-center justify-center font-bold hidden">
                        0
                    </span>
                </a>
            </li>
        </div>
    </ul>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Hamburger Menu Logic
        const hamburger = document.querySelector('.hamburger');
        const navbarMenu = document.querySelector('.navbar-menu');

        if (hamburger && navbarMenu) {
            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('active');
                navbarMenu.classList.toggle('active');
            });
        }

        // Dropdown Toggle Logic on Click
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                const parent = this.closest('.dropdown');

                // Close other dropdowns
                document.querySelectorAll('.dropdown').forEach(item => {
                    if (item !== parent) {
                        item.classList.remove('active');
                    }
                });

                if (parent) {
                    parent.classList.toggle('active');
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown').forEach(item => {
                    item.classList.remove('active');
                });
            }
        });

        // Close mobile menu on link click
        document.querySelectorAll('.navbar-menu a:not(.dropdown-toggle)').forEach(link => {
            link.addEventListener('click', () => {
                if (hamburger && navbarMenu) {
                    hamburger.classList.remove('active');
                    navbarMenu.classList.remove('active');
                }
            });
        });

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            const fabTop = document.getElementById('backToTop');

            if (navbar) {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            }

            if (fabTop) {
                if (window.scrollY > 300) {
                    fabTop.classList.add('visible');
                } else {
                    fabTop.classList.remove('visible');
                }
            }
        });
    });
</script>