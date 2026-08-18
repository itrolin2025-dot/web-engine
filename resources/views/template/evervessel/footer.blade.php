<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsletter & Footer Section</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        everdark: '#2d3345',
                        everdarker: '#252938',
                        everlight: '#f1f1f1',
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
<body class="bg-white font-sans antialiased text-white">

    <!-- FOOTER WRAPPER -->
    <footer>
        
        <!-- 1. TOP NEWSLETTER BANNER -->
        <div class="bg-everlight text-everdark py-12 md:py-16 px-6 relative overflow-hidden">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-center gap-8 md:gap-12 relative z-10">
                
                <!-- Bottle Image -->
                <div class="w-32 md:w-44 flex-shrink-0">
                    <img 
                        src="https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=500&q=80" 
                        alt="Evervessel Bottle" 
                        class="w-full h-auto object-contain mix-blend-multiply drop-shadow-xl"
                    />
                </div>

                <!-- Content & Form -->
                <div class="max-w-xl text-center md:text-left space-y-6">
                    <h3 class="font-serif text-2xl sm:text-3xl lg:text-4xl leading-tight font-normal text-everdark">
                        Subscribe for exclusive offers and product updates
                    </h3>

                    <!-- Email Input Group -->
                    <form onsubmit="event.preventDefault();" class="flex flex-col sm:flex-row items-center bg-white rounded shadow-md overflow-hidden p-1.5 border border-gray-200 focus-within:ring-2 focus-within:ring-everdark/20 transition">
                        <input 
                            type="email" 
                            placeholder="Enter your email*" 
                            required
                            class="w-full px-4 py-3 text-sm text-gray-700 bg-transparent placeholder-gray-400 focus:outline-none"
                        />
                        <button 
                            type="submit" 
                            class="w-full sm:w-auto mt-2 sm:mt-0 bg-everdark hover:bg-everdarker text-white text-xs font-semibold px-8 py-3.5 rounded transition duration-200 tracking-wider"
                        >
                            Submit
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <!-- 2. MAIN FOOTER CONTENT -->
        <div class="bg-everdark pt-16 pb-12 px-6 sm:px-12 text-gray-300">
            <div class="max-w-7xl mx-auto">
                
                <div class="grid grid-cols-1 md:grid-cols-12 gap-10 pb-16">
                    
                    <!-- Left Columns: Navigation Links (7 cols) -->
                    <div class="md:col-span-8 lg:col-span-7 grid grid-cols-1 sm:grid-cols-3 gap-8">
                        
                        <!-- Column 1: Service -->
                        <div class="space-y-4">
                            <h4 class="text-white text-sm font-bold tracking-wider">Service</h4>
                            <ul class="space-y-3 text-xs text-gray-300 font-light">
                                <li><a href="#" class="hover:text-white transition">Support</a></li>
                                <li><a href="#" class="hover:text-white transition">Shipping and Delivery</a></li>
                                <li><a href="#" class="hover:text-white transition">Warranty and Returns</a></li>
                                <li><a href="#" class="hover:text-white transition">Terms and Conditions</a></li>
                                <li><a href="#" class="hover:text-white transition">Policies</a></li>
                                <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                            </ul>
                        </div>

                        <!-- Column 2: Company -->
                        <div class="space-y-4">
                            <h4 class="text-white text-sm font-bold tracking-wider">Company</h4>
                            <ul class="space-y-3 text-xs text-gray-300 font-light">
                                <li><a href="#" class="hover:text-white transition">Products</a></li>
                                <li><a href="#" class="hover:text-white transition">Our Story</a></li>
                                <li><a href="#" class="hover:text-white transition">The Bigger Vessel</a></li>
                                <li><a href="#" class="hover:text-white transition">Our Technology</a></li>
                            </ul>
                        </div>

                        <!-- Column 3: Resources -->
                        <div class="space-y-4">
                            <h4 class="text-white text-sm font-bold tracking-wider">Resources</h4>
                            <ul class="space-y-3 text-xs text-gray-300 font-light">
                                <li><a href="#" class="hover:text-white transition">Blog</a></li>
                                <li><a href="#" class="hover:text-white transition">Custom Orders</a></li>
                                <li><a href="#" class="hover:text-white transition">Store Locator</a></li>
                            </ul>
                        </div>

                    </div>

                    <!-- Right Column: Social Media Icons (5 cols) -->
                    <div class="md:col-span-4 lg:col-span-5 flex justify-start md:justify-end items-start pt-2">
                        <div class="flex items-center space-x-5 text-white text-lg">
                            <a href="#" class="hover:opacity-75 transition" aria-label="Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                            <a href="#" class="hover:opacity-75 transition" aria-label="YouTube">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                            <a href="#" class="hover:opacity-75 transition" aria-label="Pinterest">
                                <i class="fa-brands fa-pinterest-p"></i>
                            </a>
                            <a href="#" class="hover:opacity-75 transition" aria-label="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#" class="hover:opacity-75 transition" aria-label="Email">
                                <i class="fa-regular fa-envelope"></i>
                            </a>
                        </div>
                    </div>

                </div>

                <!-- Copyright Bar -->
                <div class="pt-8 flex justify-end text-[11px] text-gray-400 font-light">
                    <p>© 2026 Ever Vessel. All Rights Reserved.</p>
                </div>

            </div>
        </div>

    </footer>

</body>
</html>