@php
    $rawContent = $layout->content ?? '';

    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
        // Strip non-standard whitespace/control characters (like raw tabs \t) that break json_decode
        $cleanJson = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $rawContent);
        $content = json_decode($cleanJson, true) ?? json_decode($rawContent, true) ?? [];
    } else {
        $content = [];
    }

    $title = $content['title_en'] ?? $content['title'] ?? 'THIS WEEK';
    $title_color = $content['title_color'] ?? '#4a1525';

    $tag = $content['tag_en'] ?? $content['tag'] ?? 'Where to Catch the Cart';
    $tag_color = $content['tag_color'] ?? '#4a1525b3';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? 'PSST! GET THE SCHEDULE IN YOUR INBOX EVERY WEEK!';
    $subtitle_color = $content['subtitle_color'] ?? '#4a1525';

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? 'Find Us Today';
    $button_color = $content['button_color'] ?? '#721c2e';
    $button_text_color = $content['button_text_color'] ?? '#ffffff';

    // Jadwal mingguan dari repeater (label = hari/tanggal, title = nama vendor, subtitle = jam)
    $repeater = $content['repeater'] ?? null;
    if (!is_array($repeater) || empty($repeater)) {
        $repeater = [
            ['label' => 'MAR 03 (TUE)', 'title' => 'None', 'subtitle' => '', 'image' => ''],
            ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🍩'],
            ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🧸'],
            ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🍫'],
            ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🍒'],
            ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🍬'],
            ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🥤'],
        ];
    }
    $repeater = collect($repeater)->sortBy('sort')->values()->all();
    $dayIcons = ['🍩', '🧸', '🍫', '🍒', '🍬', '🥤', '🍭'];
@endphp

<section id="cart" class="bg-[#e54666] py-10 md:py-16 px-4 md:p-8 font-sans selection:bg-pink-200">
    <div class="w-full max-w-6xl mx-auto flex flex-col items-center">

        <!-- Agenda Book Container -->
        <div
            class="w-full bg-white rounded-3xl shadow-2xl relative overflow-hidden pt-8 pb-10 px-4 md:px-10 border-4 border-[#721c2e]/20">

            <!-- Spiral Binding Header Circles -->
            <div
                class="absolute top-0 left-0 w-full flex justify-around px-4 md:px-8 py-3 bg-white z-20 border-b border-red-100/50">
                @for ($i = 0; $i < 8; $i++)
                    <div class="w-5 h-5 md:w-7 md:h-7 rounded-full bg-red-500 shadow-inner border-2 border-white"></div>
                @endfor
            </div>

            <div class="text-center mt-6 mb-8">
                <p class="text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1" style="color: {{ $tag_color }};">
                    {{ $tag }}
                </p>
                <h2 class="candy-font-serif italic font-bold text-2xl md:text-4xl tracking-tight"
                    style="color: {{ $title_color }};">
                    {{ $title }}
                </h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3 mb-8">
                @foreach ($repeater as $index => $item)
                    @php
                        $isClosed = strtolower(trim($item['title'] ?? '')) === 'none' || empty($item['title']);
                        $icon = !empty($item['image']) ? $item['image'] : ($dayIcons[$index % count($dayIcons)] ?? '🍬');
                    @endphp
                    <div
                        class="rounded-2xl p-4 flex flex-col justify-between items-center text-center min-h-[180px] {{ $isClosed ? 'bg-[#721c2e] text-white shadow-md' : 'bg-white shadow-sm border border-red-200/50 hover:shadow-md transition-all' }}">
                        <div
                            class="w-full flex justify-between items-center text-[10px] uppercase font-bold tracking-wider {{ $isClosed ? 'opacity-80' : 'text-[#4a1525]/60' }}">
                            <span>{{ $item['label'] ?? '' }}</span>
                        </div>
                        <div class="my-auto text-3xl my-2">
                            {{ $icon }}
                        </div>
                        <div>
                            <p class="font-bold text-xs leading-tight mb-1 {{ $isClosed ? 'opacity-90 font-serif italic text-lg' : 'text-[#4a1525]' }}">
                                {{ $item['title'] ?? 'None' }}
                            </p>
                            <p class="text-[11px] font-medium {{ $isClosed ? 'opacity-0' : 'text-[#4a1525]/70' }}">
                                {{ $item['subtitle'] ?? '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center">
                <button onclick="candyFindUs()"
                    class="text-xs font-bold uppercase tracking-wider py-3.5 px-8 rounded-full shadow-md transition-all transform hover:-translate-y-0.5"
                    style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                    {{ $button_text }}
                </button>
            </div>

        </div>

        <div
            class="w-full bg-[#e58a9a] rounded-2xl p-6 mt-6 shadow-lg flex flex-col md:flex-row items-center justify-between gap-4 border border-pink-300">
            <div class="font-bold text-xs md:text-sm tracking-wide text-center md:text-left"
                style="color: {{ $subtitle_color }};">
                {{ $subtitle }}
            </div>

            <form onsubmit="candyHandleSubscribe(event)"
                class="flex flex-col sm:flex-row items-center gap-2 w-full md:w-auto">
                <input type="email" id="candy-subscriber-email" placeholder="EMAIL ADDRESS" required
                    class="bg-white text-[#4a1525] placeholder-[#4a1525]/50 text-xs font-semibold py-3 px-5 rounded-full border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#721c2e] w-full sm:w-64">
                <button type="submit"
                    class="text-xs font-bold uppercase tracking-wider py-3 px-6 rounded-full shadow transition-all w-full sm:w-auto"
                    style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                    Sign Up
                </button>
            </form>
        </div>

    </div>
</section>

<script>
    function candyFindUs() {
        candyShowToast("Membuka peta lokasi cart minggu ini! 🗺️");
    }

    function candyHandleSubscribe(e) {
        e.preventDefault();
        var emailInput = document.getElementById('candy-subscriber-email');
        if (emailInput && emailInput.value) {
            candyShowToast("Terima kasih! Jadwal mingguan telah dikirim ke email kamu 💌");
            emailInput.value = '';
        }
    }
</script>
