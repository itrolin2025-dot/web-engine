<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Carousel Grid</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-white py-12 px-4 font-sans text-gray-800 antialiased">

    <!-- CAROUSEL CONTAINER -->
    <div class="max-w-7xl mx-auto relative group">

        <!-- Left Navigation Arrow -->
        <button
            class="absolute -left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white shadow-md rounded-full flex items-center justify-center text-gray-600 hover:text-black hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Right Navigation Arrow -->
        <button
            class="absolute -right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white shadow-md rounded-full flex items-center justify-center text-gray-600 hover:text-black hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- PRODUCT GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- CARD 1: SUPER MAXI -->
            <div class="bg-gray-50 rounded-sm p-6 flex flex-col items-center text-center relative group">
                <!-- Product Image Area -->
                <div class="h-64 w-full flex items-center justify-center mb-6">
                    <img src="https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=500&q=80"
                        alt="Super Maxi" class="max-h-full object-contain mix-blend-multiply">
                </div>

                <!-- Color Swatches Grid -->
                <!-- <div class="grid grid-cols-6 gap-2 mb-6">
                    <span
                        class="w-4 h-4 rounded-full bg-gray-900 border border-white ring-2 ring-gray-900 cursor-pointer"></span>    
                    <span class="w-4 h-4 rounded-full bg-slate-800 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-gray-400 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-red-800 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-teal-800 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-stone-300 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-rose-200 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-indigo-200 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-emerald-200 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-amber-200 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-amber-600 cursor-pointer hover:opacity-80"></span>
                    <span class="w-4 h-4 rounded-full bg-orange-100 cursor-pointer hover:opacity-80"></span>
                </div> -->

                <!-- Product Details -->
                <h3 class="text-xs font-bold tracking-widest text-gray-900 uppercase mb-1">SUPER MAXI</h3>
                <p class="text-sm font-semibold text-gray-800 mb-2">$69</p>
                <div class="text-xs text-gray-500 leading-tight">
                    <p>26oz / 36oz</p>
                    <p>Sports Lid & Insulated</p>
                </div>
            </div>

            <!-- CARD 2: MINI -->
            <div class="bg-gray-50 rounded-sm p-6 flex flex-col items-center text-center relative group">
                <!-- Product Image Area -->
                <div class="h-64 w-full flex items-center justify-center mb-6">
                    <img src="https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=500&q=80"
                        alt="Mini" class="max-h-full object-contain mix-blend-multiply">
                </div>

                <!-- Product Details -->
                <h3 class="text-xs font-bold tracking-widest text-gray-900 uppercase mb-1">MINI</h3>
                <p class="text-sm font-semibold text-gray-800 mb-2">$29</p>
                <div class="text-xs text-gray-500 leading-tight">
                    <p>14oz</p>
                    <p>Single wall</p>
                </div>
            </div>

            <!-- CARD 3: GLASS (With Badge & Artwork Preview) -->
            <div class="bg-gray-50 rounded-sm p-6 flex flex-col items-center text-center relative group">
                <!-- Top Badge Tag -->
                <span
                    class="absolute top-4 left-4 border border-orange-300 text-orange-600 text-[10px] font-medium px-2 py-0.5 rounded bg-white/50">
                    Art
                </span>

                <!-- Top Right Art Preview Thumbnail -->
                <div class="absolute top-4 right-4 w-7 h-7 rounded overflow-hidden border border-gray-200">
                    <img src="https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=100&q=80"
                        alt="Art preview" class="w-full h-full object-cover">
                </div>

                <!-- Product Image Area -->
                <div class="h-64 w-full flex items-center justify-center mb-6">
                    <img src="https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=500&q=80"
                        alt="Glass" class="max-h-full object-contain mix-blend-multiply">
                </div>

                <!-- Product Details -->
                <h3 class="text-xs font-bold tracking-widest text-gray-900 uppercase mb-1">GLASS</h3>
                <p class="text-sm font-semibold text-gray-800 mb-2">$39</p>
                <div class="text-xs text-gray-500 leading-tight">
                    <p>20oz</p>
                    <p>Single wall</p>
                </div>
            </div>

            <!-- CARD 4: GLASS MULTI -->
            <div class="bg-gray-50 rounded-sm p-6 flex flex-col items-center text-center relative group">
                <!-- Product Image Area -->
                <div class="h-64 w-full flex items-center justify-center mb-6">
                    <img src="https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=500&q=80"
                        alt="Glass Multi" class="max-h-full object-contain mix-blend-multiply">
                </div>

                <!-- Product Details -->
                <h3 class="text-xs font-bold tracking-widest text-gray-900 uppercase mb-1">GLASS MULTI</h3>
                <p class="text-sm font-semibold text-gray-800 mb-2">$49</p>
                <div class="text-xs text-gray-500 leading-tight">
                    <p>14oz</p>
                    <p>Double wall</p>
                </div>
            </div>

        </div>
    </div>

</body>

</html>