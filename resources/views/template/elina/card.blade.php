<section class="bg-[#f7f4f0] font-sans antialiased text-gray-900 py-12 px-4 sm:px-6 lg:px-12">

    <div class="max-w-6xl mx-auto space-y-12">

        <!-- ======================================================= -->
        <!-- CARD 1: FOTO KIRI, TEKS KANAN (PERSIS SEPERTI GAMBAR)   -->
        <!-- ======================================================= -->
        <div class="w-full bg-white flex flex-col md:flex-row items-stretch shadow-sm overflow-hidden">

            <!-- Left: Image -->
            <div class="w-full md:w-1/2 min-h-[320px] sm:min-h-[400px] relative">
                <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=1200&q=80"
                    alt="Latte Art & Espresso on Tray" class="w-full h-full object-cover" />
            </div>

            <!-- Right: Content -->
            <div
                class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center items-center text-center bg-white space-y-6">
                <!-- Title -->
                <h2 class="text-3xl sm:text-4xl font-semibold tracking-widest text-gray-900 uppercase">
                    TASTING
                </h2>

                <!-- Description -->
                <p class="text-xs sm:text-sm text-gray-600 font-normal leading-relaxed max-w-md">
                    We'd love to welcome you in person at our roastery in Dunajská Streda. We'll have a cup of coffee
                    together and tailor our offerings exactly to your needs.
                </p>

                <!-- Button -->
                <div class="pt-2">
                    <button
                        class="px-8 py-2 border border-[#897398] text-[#897398] text-xs font-medium tracking-wider uppercase hover:bg-[#897398] hover:text-white transition duration-200">
                        RESERVATION
                    </button>
                </div>
            </div>

        </div>


        <!-- ======================================================= -->
        <!-- CARD 2: TEKS KIRI, FOTO KANAN (POSISI DIWALIK)          -->
        <!-- ======================================================= -->
        <div class="w-full bg-white flex flex-col md:flex-row items-stretch shadow-sm overflow-hidden">

            <!-- Left: Content (Order 2 on Mobile, Order 1 on Desktop) -->
            <div
                class="w-full md:w-1/2 p-8 sm:p-12 lg:p-16 flex flex-col justify-center items-center text-center bg-white space-y-6 order-2 md:order-1">
                <!-- Title -->
                <h2 class="text-3xl sm:text-4xl font-semibold tracking-widest text-gray-900 uppercase">
                    WORKSHOP
                </h2>

                <!-- Description -->
                <p class="text-xs sm:text-sm text-gray-600 font-normal leading-relaxed max-w-md">
                    Learn the secrets of manual coffee brewing, dial-in espresso extraction, and elevate your home
                    barista skills with our head roaster in an interactive hands-on session.
                </p>

                <!-- Button -->
                <div class="pt-2">
                    <button
                        class="px-8 py-2 border border-[#897398] text-[#897398] text-xs font-medium tracking-wider uppercase hover:bg-[#897398] hover:text-white transition duration-200">
                        EXPLORE MORE
                    </button>
                </div>
            </div>

            <!-- Right: Image (Order 1 on Mobile, Order 2 on Desktop) -->
            <div class="w-full md:w-1/2 min-h-[320px] sm:min-h-[400px] relative order-1 md:order-2">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=80"
                    alt="Coffee Pour Over Workshop" class="w-full h-full object-cover" />
            </div>

        </div>

    </div>

</section>