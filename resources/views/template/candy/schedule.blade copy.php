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

    $domain = $website->domain ?? '';

    $title = $content['title_en'] ?? $content['title'] ?? 'THIS WEEK';
    $title_color = $content['title_color'] ?? '#4a1525';

    $tag = $content['tag_en'] ?? $content['tag'] ?? 'Where to Catch the Cart';
    $tag_color = $content['tag_color'] ?? '#4a1525b3';

    $subtitle = $content['subtitle_en'] ?? $content['subtitle'] ?? 'PSST! GET THE SCHEDULE IN YOUR INBOX EVERY WEEK!';
    $subtitle_color = $content['subtitle_color'] ?? '#4a1525';

    $background_color = $content['background_color'] ?? '#fffffff';
    $has_background = !empty($content['background']) && $content['background'] !== 'your image';
    $background = $has_background ? 'images/website/' . $domain . '/' . $content['background'] : null;

    $button_text = $content['button_text_en'] ?? $content['button_text'] ?? 'Find Us Today';
    $button_color = $content['button_color'] ?? '#721c2e';
    $button_text_color = $content['button_text_color'] ?? '#111111';

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

<section id="cart" class="bg-[{{ $background_color }}] min-h-screen font-sans selection:bg-pink-200"
    style="min-height:100vh; align-content: center;">
    <div class="w-full max-w-6xl mx-auto flex flex-col items-center">

        <!-- Agenda Book Container -->
        <div
            class="w-full bg-white rounded-3xl shadow-2xl relative overflow-hidden flex flex-col items-center justify-center py-12 px-8 md:px-10 border-4 border-[#721c2e]/20" style="background-color: {{ $background_color }}; @if($has_background) background-image: url('{{ asset($background) }}'); background-size: cover; background-position: center; background-repeat: no-repeat; @endif;">

            <!-- Spiral Binding Header Circles -->
            <div
                class="absolute top-0 left-0 w-full flex justify-around px-4 md:px-8 py-3 bg-white z-20 border-b border-red-100/50" style="background-color: {{ $background_color }}; @if($has_background) background-image: url('{{ asset($background) }}'); background-size: cover; background-position: center; background-repeat: no-repeat; @endif;">
                @for ($i = 0; $i < 8; $i++)
                    <div class="w-5 h-5 md:w-7 md:h-7 rounded-full bg-[{{ $background_color }}] shadow-inner border-2 border-white"></div>
                @endfor
            </div>

            <div class="w-full flex flex-col items-center py-12 text-center">
                <p class="text-center text-[10px] font-bold uppercase tracking-widest mb-1" style="color: {{ $tag_color }};">
                    {{ $tag }}
                </p>
                <h2 class="candy-font-serif italic font-bold text-center text-2xl tracking-tight"
                    style="color: {{ $title_color }};">
                    {{ $title }}
                </h2>
            </div>
        
            <div class="w-full flex-1 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                @foreach ($repeater as $index => $item)
                    @php
                        $isClosed = strtolower(trim($item['title'] ?? '')) === 'none' || empty($item['title']);
                        $icon = !empty($item['image']) ? $item['image'] : ($dayIcons[$index % count($dayIcons)] ?? '🍬');
                    @endphp
                    <div
                        class="group w-full flex flex-col items-center rounded-2xl px-5 py-10 text-center min-h-[220px] {{ $isClosed ? 'bg-[#721c2e] text-white shadow-md' : 'bg-white shadow-sm border border-red-200/50 hover:shadow-md transition-all' }}">
                        <div
                            class="w-full text-center text-[10px] uppercase font-bold tracking-wider mb-3 {{ $isClosed ? 'opacity-80' : 'text-[#4a1525]/60' }}">
                            <span>{{ $item['label'] ?? '' }}</span>
                        </div>
                        <div class="my-auto w-100 h-100 mx-auto flex items-center justify-center overflow-hidden transition-transform duration-300 group-hover:scale-100">
                            @if(!empty($item['image']) && $item['image'] !== 'your image')
                                <img src="{{ asset('images/website/' . ($website->domain ?? '') . '/' . $item['image']) }}"
                                     alt=""
                                     class="w-50 h-50 object-contain transition-transform duration-300 group-hover:scale-10 group-hover:scale-100">
                            @endif
                        </div>
                        <div class="mt-2 w-full flex flex-col items-center">
                            <!-- <p class="font-bold text-sm leading-tight mb-1 {{ $isClosed ? 'opacity-90 font-serif italic text-base' : 'text-[#4a1525]' }}">
                                {{ $item['title'] ?? 'None' }}
                            </p> -->
                            <p class="text-[12px] font-medium {{ $isClosed ? 'opacity-0' : 'text-[#4a1525]/70' }}">
                                {{ $item['title'] ?? '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="w-full flex flex-col items-center justify-center mt-4 md:mt-6">
                <button onclick="candyFindUs()"
                    class="text-xs font-bold uppercase tracking-wider py-3 px-7 rounded-full shadow-md transition-all transform hover:-translate-y-0.5"
                    style="background-color: {{ $button_color }}; color: {{ $button_text_color }};">
                    {{ $button_text }}
                </button>
            </div>
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
