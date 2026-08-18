<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVERVESSEL Layout</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        everdark: '#2A2E3D',
                        evergray: '#6B7280',
                    },
                    fontFamily: {
                        serif: ['Cinzel', 'Georgia', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* Pattern Titik-Titik (Polka Dot) khas Evervessel */
        .bg-dotted {
            background-image: radial-gradient(#2A2E3D 1px, transparent 1px);
            background-size: 16px 16px;
        }
    </style>
</head>

<body class="bg-white text-everdark font-sans antialiased">

    <!-- HEADER / NAVIGATION -->
    <header class="border-b border-gray-100 bg-white sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <!-- Left Menu -->
            <nav class="flex items-center space-x-8 text-xs font-semibold tracking-widest text-everdark uppercase">
                <div class="flex items-center space-x-1.5 cursor-pointer hover:opacity-70 transition">
                    <span>PRODUCTS</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
                <div class="flex items-center space-x-1.5 cursor-pointer hover:opacity-70 transition">
                    <span>ABOUT</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </nav>

            <!-- Center Logo -->
            <div class="flex items-center justify-center">
                <a href="#" class="font-bold text-2xl tracking-[0.2em] font-sans text-everdark">
                    EVER<span class="inline-block transform -skew-x-12">V</span>ESSEL
                </a>
            </div>

            <!-- Right Menu -->
            <div class="flex items-center space-x-6 text-xs font-semibold text-everdark">
                <div class="flex items-center space-x-1 cursor-pointer hover:opacity-70 transition">
                    <span>USD</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>

                <!-- Cart Icon with Badge -->
                <a href="#" class="relative p-1 hover:opacity-70 transition">
                    <svg class="w-6 h-6 text-everdark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span
                        class="absolute top-0 right-0 bg-everdark text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center font-bold">1</span>
                </a>
            </div>

        </div>
    </header>

</body>

</html>