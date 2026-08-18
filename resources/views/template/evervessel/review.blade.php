<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Reviews Section</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        everdark: '#2A2E3D',
                        badge: '#C85A32',
                    },
                    fontFamily: {
                        serif: ['Cinzel', 'Georgia', 'serif'],
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F9F9F9] text-everdark font-sans antialiased py-16 px-4 sm:px-8">

    <!-- REVIEWS SECTION -->
    <section class="max-w-7xl mx-auto">
        
        <!-- HEADER ROW -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
            
            <!-- Left Title -->
            <h2 class="font-serif text-4xl sm:text-5xl font-normal text-everdark">
                Reviews
            </h2>

            <!-- Right Rating & Navigation Controls -->
            <div class="flex flex-col items-start md:items-end gap-3">
                
                <!-- Overall Rating -->
                <div class="flex items-center gap-3">
                    <div class="flex text-everdark space-x-0.5">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 font-normal">
                    4.78/5 — Based on 1116 reviews
                </p>

                <!-- Carousel Navigation Arrows -->
                <div class="flex items-center space-x-4 pt-1">
                    <button class="text-gray-400 hover:text-everdark transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>
                    <button class="text-gray-400 hover:text-everdark transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </button>
                </div>

            </div>

        </div>

        <!-- REVIEWS CARDS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- CARD 1 -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100/60 flex flex-col justify-between">
                <div>
                    <!-- User Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-everdark">David W</h4>
                                <p class="text-[11px] text-gray-400">Tauranga, NZ</p>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400">08/12/2026</span>
                    </div>

                    <!-- Review Content -->
                    <h5 class="text-xs font-bold text-everdark mb-1">Great bottle</h5>
                    <p class="text-xs text-gray-500 leading-relaxed font-light mb-6">
                        I got the full package and I must say the quality is fantastic. The communication from the supplier was fantastic. Recommend anyone looking for a great vessel to purchase
                    </p>
                </div>

                <!-- Footer Verified + Rating -->
                <div>
                    <span class="text-[10px] font-bold tracking-wider text-badge uppercase block mb-1">VERIFIED</span>
                    <div class="flex text-everdark space-x-0.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    </div>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100/60 flex flex-col justify-between">
                <div>
                    <!-- User Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-everdark">John K</h4>
                                <p class="text-[11px] text-gray-400">Melbourne, AU</p>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400">08/12/2026</span>
                    </div>

                    <!-- Review Content -->
                    <h5 class="text-xs font-bold text-everdark mb-1">Superb</h5>
                    <p class="text-xs text-gray-500 leading-relaxed font-light mb-6">
                        Quality of product and great real people to deal with.
                    </p>
                </div>

                <!-- Footer Verified + Rating -->
                <div>
                    <span class="text-[10px] font-bold tracking-wider text-badge uppercase block mb-1">VERIFIED</span>
                    <div class="flex text-everdark space-x-0.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    </div>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100/60 flex flex-col justify-between">
                <div>
                    <!-- User Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-everdark">Toni H</h4>
                                <p class="text-[11px] text-gray-400">Brisbane, AU</p>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400">08/11/2026</span>
                    </div>

                    <!-- Review Content -->
                    <h5 class="text-xs font-bold text-everdark mb-1">Excellent local water bottle brand</h5>
                    <p class="text-xs text-gray-500 leading-relaxed font-light mb-6">
                        Stylish and practical. This is my absolute favourite water bottle. Such a superlative alternative to plastic water bottles.
                    </p>
                </div>

                <!-- Footer Verified + Rating -->
                <div>
                    <span class="text-[10px] font-bold tracking-wider text-badge uppercase block mb-1">VERIFIED</span>
                    <div class="flex text-everdark space-x-0.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    </div>
                </div>
            </div>

            <!-- CARD 4 -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100/60 flex flex-col justify-between">
                <div>
                    <!-- User Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-everdark">Lori J</h4>
                                <p class="text-[11px] text-gray-400">Hickory Hills, US</p>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-400">08/09/2026</span>
                    </div>

                    <!-- Review Content -->
                    <h5 class="text-xs font-bold text-everdark mb-1">A beauty!</h5>
                    <p class="text-xs text-gray-500 leading-relaxed font-light mb-2">
                        I bought the mini previously so I wanted to another size. This one is also sleek tame the teal is a really beautiful color. The cap and handle do...
                    </p>
                    <button class="text-xs text-gray-600 font-medium underline mb-6 block hover:text-black">
                        Show more
                    </button>
                </div>

                <!-- Footer Verified + Rating -->
                <div>
                    <span class="text-[10px] font-bold tracking-wider text-badge uppercase block mb-1">VERIFIED</span>
                    <div class="flex text-everdark space-x-0.5">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    </div>
                </div>
            </div>

        </div>

    </section>

</body>
</html>