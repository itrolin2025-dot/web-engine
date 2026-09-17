<section class="py-12 px-4 max-w-7xl mx-auto font-sans text-[#1a1a1a]">

    <!-- Gallery Grid Container -->
    <div id="category-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 auto-rows-[280px]"></div>

</section>

<script>
    // Data Galeri Kategori
    const categories = [
        {
            title: "T-shirts",
            count: "14 products",
            image: "https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=800&q=80",
            bg: "bg-[#eef2f5]",
            span: "lg:col-span-2 lg:row-span-1" // Lebar 2 Kolom
        },
        {
            title: "Sunglasses",
            count: "18 products",
            image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80",
            bg: "bg-[#f4f3ed]",
            span: "lg:col-span-1 lg:row-span-1"
        },
        {
            title: "Sneakers",
            count: "7 products",
            image: "https://images.unsplash.com/photo-1552374196-1ab2a1c593e8?auto=format&fit=crop&w=800&q=80",
            bg: "bg-[#f7f0f0]",
            span: "lg:col-span-1 lg:row-span-2" // Tinggi 2 Baris (Full Right)
        },
        {
            title: "Blazers",
            count: "10 products",
            image: "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=800&q=80",
            bg: "bg-[#fbf0eb]",
            span: "lg:col-span-1 lg:row-span-1"
        },
        {
            title: "Backpacks",
            count: "18 products",
            image: "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80",
            bg: "bg-[#e7f0ec]",
            span: "lg:col-span-1 lg:row-span-1"
        },
        {
            title: "Jackets",
            count: "1 product",
            image: "https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=800&q=80",
            bg: "bg-[#f2f2f2]",
            span: "lg:col-span-1 lg:row-span-1"
        }
    ];

    // Render Grid
    const grid_category = document.getElementById('category-grid');
    grid_category.innerHTML = categories.map(cat => `
        <div class="relative group overflow-hidden rounded-xl ${cat.bg} ${cat.span} p-6 flex flex-col justify-between cursor-pointer">
            
            <!-- Category Info (Top Left or Bottom Left depending on layout) -->
            <div class="relative z-10">
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-black transition">
                    ${cat.title}
                </h3>
                <p class="text-xs text-gray-500 font-medium mt-1">
                    ${cat.count}
                </p>
            </div>

            <!-- Background / Side Image -->
            <img src="${cat.image}" 
                alt="${cat.title}" 
                class="absolute inset-0 w-full h-full object-cover object-center transition duration-500 group-hover:scale-105 mix-blend-multiply opacity-90" />

        </div>
    `).join('');
</script>