<section class="border-y border-stone-200 bg-white">
    <div class="py-10" style="overflow: hidden;">
        <div id="category-track"
            class="flex flex-nowrap items-center text-sm font-medium text-stone-500 uppercase tracking-widest cursor-grab"
            style="overflow-x: auto; scroll-behavior: smooth; -ms-overflow-style: none; scrollbar-width: none;">

            <a href="#"
                class="category-item flex flex-col items-center gap-3 hover:text-stone-900 transition-colors group flex-shrink-0">
                <div
                    class="category-img rounded-full overflow-hidden bg-stone-100 group-hover:ring-2 group-hover:ring-stone-400 transition-all">
                    <img src="./assets/cat-1.png" alt="Skincare" class="w-full h-full object-cover">
                </div>
                <span>Skincare</span>
            </a>

            <a href="#"
                class="category-item flex flex-col items-center gap-3 hover:text-stone-900 transition-colors group flex-shrink-0">
                <div class="category-img rounded-full overflow-hidden bg-stone-100 group-hover:ring-2 transition-all">
                    <img src="./assets/cat-2.png" alt="Body Care" class="w-full h-full object-cover">
                </div>
                <span>Body Care</span>
            </a>

            <a href="#"
                class="category-item flex flex-col items-center gap-3 hover:text-stone-900 transition-colors group flex-shrink-0">
                <div
                    class="category-img rounded-full overflow-hidden bg-stone-100 group-hover:ring-2 group-hover:ring-stone-400 transition-all">
                    <img src="./assets/cat-3.png" alt="Hair Care" class="w-full h-full object-cover">
                </div>
                <span>Hair Care</span>
            </a>

            <a href="#"
                class="category-item flex flex-col items-center gap-3 hover:text-stone-900 transition-colors group flex-shrink-0">
                <div
                    class="category-img rounded-full overflow-hidden bg-stone-100 group-hover:ring-2 group-hover:ring-stone-400 transition-all">
                    <img src="./assets/cat-4.png" alt="Perfume" class="w-full h-full object-cover">
                </div>
                <span>Perfume</span>
            </a>

            <a href="#"
                class="category-item flex flex-col items-center gap-3 hover:text-stone-900 transition-colors group flex-shrink-0">
                <div
                    class="category-img rounded-full overflow-hidden bg-stone-100 group-hover:ring-2 group-hover:ring-stone-400 transition-all">
                    <img src="./assets/cat-5.png" alt="Decorative Cosmetic" class="w-full h-full object-cover">
                </div>
                <span>Decorative</span>
            </a>

            <a href="#"
                class="category-item flex flex-col items-center gap-3 hover:text-stone-900 transition-colors group flex-shrink-0">
                <div
                    class="category-img rounded-full overflow-hidden bg-stone-100 group-hover:ring-2 group-hover:ring-stone-400 transition-all">
                    <img src="./assets/cat-1.png" alt="Lip Care" class="w-full h-full object-cover">
                </div>
                <span>Lip Care</span>
            </a>

            <a href="#"
                class="category-item flex flex-col items-center gap-3 hover:text-stone-900 transition-colors group flex-shrink-0">
                <div
                    class="category-img rounded-full overflow-hidden bg-stone-100 group-hover:ring-2 group-hover:ring-stone-400 transition-all">
                    <img src="./assets/cat-1.png" alt="Sun Care" class="w-full h-full object-cover">
                </div>
                <span>Sun Care</span>
            </a>

        </div>
    </div>

    <style>
        /* Mobile: 3 items visible */
        .category-item {
            min-width: calc(100vw / 3);
            padding: 0.5rem 0;
        }

        .category-img {
            width: 72px;
            height: 72px;
        }

        /* Tablet: 4 items visible */
        @media (min-width: 768px) {
            .category-item {
                min-width: calc(100vw / 4);
            }

            .category-img {
                width: 96px;
                height: 96px;
            }
        }

        /* Desktop: exactly 5 items visible */
        @media (min-width: 1024px) {
            .category-item {
                min-width: calc(100vw / 5);
            }

            .category-img {
                width: 140px;
                height: 140px;
            }

            #category-track {
                padding: 0 2rem;
            }
        }

        #category-track::-webkit-scrollbar {
            display: none;
        }

        #category-track.is-dragging {
            cursor: grabbing;
            user-select: none;
        }
    </style>
    <script>
        (function () {
            const track = document.getElementById('category-track');
            let isDown = false, startX, scrollLeft;
            track.addEventListener('mousedown', (e) => { isDown = true; track.classList.add('is-dragging'); startX = e.pageX - track.offsetLeft; scrollLeft = track.scrollLeft; });
            track.addEventListener('mouseleave', () => { isDown = false; track.classList.remove('is-dragging'); });
            track.addEventListener('mouseup', () => { isDown = false; track.classList.remove('is-dragging'); });
            track.addEventListener('mousemove', (e) => { if (!isDown) return; e.preventDefault(); track.scrollLeft = scrollLeft - (e.pageX - track.offsetLeft - startX); });
        })();
    </script>
</section>