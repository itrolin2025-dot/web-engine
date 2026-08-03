<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
</head>

<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --coral: #FF9B7A;
            --coral-dark: #E8876A;
            --peach: #FFB8A0;
            --pink: #FFC4D6;
            --lavender: #C4B5FD;
            --mint: #A7F3D0;
            --sky: #BAE6FD;
            --cream: #FFF5F0;
            --dark: #2D2D2D;
            --gray: #6B7280;
            --light-gray: #F3F4F6;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
            /* font-family: 'Plus Jakarta Sans', sans-serif; */
            background-color: #FCFBFA;
            color: #2C2A29;
        }

        .font-serif-brand {
            font-family: 'Playfair Display', serif;
        }

        h1,
        h2,
        h3,
        h4 {
            font-family: 'Playfair Display', serif;
        }

        /* ===== FLOATING BUTTONS ===== */
        .floating-actions {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            display: flex;
            gap: 1rem;
            z-index: 1000;
        }

        .fab {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, background-color 0.3s, opacity 0.3s;
            cursor: pointer;
            border: none;
        }

        .fab:hover {
            transform: translateY(-5px);
        }

        .fab-wa {
            background-color: #25D366;
            color: white;
        }

        .fab-wa:hover {
            background-color: #128C7E;
        }

        .fab-top {
            background-color: var(--dark);
            font-size: 1.2rem;
            opacity: 0;
            pointer-events: none;
        }

        .fab-top.visible {
            opacity: 1;
            pointer-events: auto;
        }

        /* Broken image styling */
        img {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        img::before {
            content: "Image";
        }
    </style>
</head>

<body>
    @foreach($layouts as $layout)
        @include($layout->template_path . '.' . $layout->section_slug)
    @endforeach


    <script>
        document.getElementById('backToTop').addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    </script>

    <!-- FLOATING BUTTONS -->
    <div class="floating-actions">
        <button class="fab fab-top" id="backToTop" aria-label="Back to top">
            &#8593;
        </button>
        <a href="https://wa.me/6281234567890" target="_blank" class="fab fab-wa" aria-label="WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
            </svg>
        </a>
    </div>
</body>

</html>