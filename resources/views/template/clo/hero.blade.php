<style>
    
    /* ==== Marquee animation ==== */
    @keyframes marquee {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .marquee-track {
        display: inline-flex;
        white-space: nowrap;
        animation: marquee 28s linear infinite;
    }
</style>

<section class="w-full relative overflow-hidden bg-gradient-to-br from-[#BFDFF5] via-[#DCEEFB] to-[#E9F5EE] py-14 md:py-20 px-6 md:px-20">
    <!-- Clouds -->
    <div class="absolute top-6 left-[8%] w-64 h-24 bg-white/70 rounded-full blur-2xl"></div>
    <div class="absolute bottom-10 right-[6%] w-80 h-28 bg-white/60 rounded-full blur-3xl"></div>
    <div class="absolute top-1/2 left-[45%] w-52 h-20 bg-white/50 rounded-full blur-2xl"></div>

    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-12 gap-10 items-center relative z-10">
        <!-- Foto lingkaran dengan ring gingham berbunga -->
        <div class="md:col-span-6 flex justify-center relative">
            <!-- <div class="green-gingham relative w-72 h-72 md:w-[26rem] md:h-[26rem] rounded-full flex items-center justify-center shadow-xl"> -->
                <!-- Bunga-bunga kecil di ring -->
                <!-- <span class="absolute top-6 left-1/2 -translate-x-1/2 text-white text-lg font-bold">✽</span>
                <span class="absolute top-1/4 left-[14%] text-white text-sm font-bold">✽</span>
                <span class="absolute top-1/4 right-[14%] text-white text-sm font-bold">✽</span>
                <span class="absolute top-1/2 left-[6%] text-white text-lg font-bold">✽</span>
                <span class="absolute top-1/2 right-[6%] text-white text-lg font-bold">✽</span>
                <span class="absolute bottom-1/4 left-[14%] text-white text-sm font-bold">✽</span>
                <span class="absolute bottom-1/4 right-[14%] text-white text-sm font-bold">✽</span>
                <span class="absolute bottom-5 left-1/2 -translate-x-1/2 text-white text-lg font-bold">✽</span> -->
                <div class="w-60 h-60 md:w-80 md:h-80 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80" alt="Crafting Hands" class="w-full h-full object-cover">
                </div>
            <!-- </div> -->
        </div>

        <!-- Teks hero -->
        <div class="md:col-span-6 text-center md:text-left flex flex-col items-center md:items-start">
            <h1 class="font-script text-6xl md:text-8xl font-bold text-[#55682B] leading-[0.95] mb-5">
                DIY Content<br>For The Crafty.
            </h1>
            <p class="text-sm md:text-base text-[#4A3B2E] max-w-md mb-8 leading-relaxed">
                Create something new with me today and take your skills and brand to the next level with my templates and tutorials.
            </p>
            <div class="relative inline-block">
                <button class="bg-[#C74A3C] hover:bg-[#A83B21] text-white font-sans-custom font-bold px-9 py-3.5 rounded-lg shadow-md uppercase tracking-[0.15em] text-xs transition-transform transform hover:scale-105">
                    LET'S CONNECT
                </button>
            </div>
        </div>
    </div>
</section>