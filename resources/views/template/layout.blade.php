<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editorial Fashion Layout</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

<body class="bg-white font-sans antialiased text-gray-900 m-0 p-0 overflow-x-hidden">
    
    <section class="w-full bg-white py-10 px-6" id="products">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Select Your Layout</h2>
        <div class="flex flex-wrap gap-2 mb-6">
            <button class="tab-btn px-4 py-2 rounded-full text-sm font-medium bg-gray-900 text-white border border-gray-300 hover:bg-gray-700 transition active" data-filter="all">All</button>
            @foreach ($tabs as $tab)
                <button class="tab-btn px-4 py-2 rounded-full text-sm font-medium bg-white text-gray-700 border border-gray-300 hover:bg-gray-100 transition" data-filter="{{ $tab->slug }}">{{ $tab->name }}</button>
            @endforeach
        </div>
    </section>
    
    <!-- SECTION: Grid layout dari templates_section -->
    <section class="w-full">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 w-full">

            @foreach ($sections as $section)
            <div class="relative aspect-[16/9] w-full overflow-hidden group cursor-pointer bg-gray-200" data-category="{{ $section->slug }}">
                @if($section->preview)
                    <img src="{{ $section->preview }}"
                        alt="{{ $section->name }}"
                        class="w-full h-full object-contain group-hover:scale-105 transition duration-700 ease-out" />
                @else
                    <!-- Broken image placeholder -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-200 gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                            <line x1="2" y1="2" x2="22" y2="22" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span class="text-xs text-gray-400 font-medium tracking-wide">No Preview</span>
                    </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-80">
                </div>
                <div class="absolute bottom-6 left-6 z-10 text-left space-y-1">
                    <h3 class="text-xl font-medium tracking-wide text-white">{{ $section->name }}</h3>
                    <span class="inline-flex items-center text-[11px] font-semibold tracking-widest uppercase text-white/60">
                        {{ $section->template_name }}
                    </span>
                </div>
            </div>
            @endforeach

        </div>
    </section>

<script>
    const tabBtns = document.querySelectorAll('.tab-btn');
    const cards   = document.querySelectorAll('[data-category]');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Update active style
            tabBtns.forEach(b => {
                b.classList.remove('bg-gray-900', 'text-white');
                b.classList.add('bg-white', 'text-gray-700');
            });
            btn.classList.remove('bg-white', 'text-gray-700');
            btn.classList.add('bg-gray-900', 'text-white');

            const filter = btn.dataset.filter;

            // Show / hide cards
            cards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>

</body>

</html>