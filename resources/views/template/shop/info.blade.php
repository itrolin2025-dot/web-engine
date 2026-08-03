@php
    $rawContent = $layout->content ?? '';
    if (is_array($rawContent)) {
        $content = $rawContent;
    } elseif (is_string($rawContent) && !empty($rawContent)) {
        $cleanJson = preg_replace('/[\x00-\x1F\x7F]/u', ' ', $rawContent);
        $cleanJson = preg_replace('/,\s*([\]}])/', '$1', $cleanJson);
        $content = json_decode($cleanJson, true) ?? json_decode($rawContent, true) ?? [];
    } else {
        $content = [];
    }
    $infoText = $content['text'] ?? $content['info'] ?? 'Free Shipping for orders over $50';
@endphp

<div class="bg-black text-white text-[11px] py-2 overflow-hidden whitespace-nowrap relative select-none">
    <div class="inline-flex animate-marquee tracking-widest uppercase opacity-90">
        @foreach(range(1, 10) as $i)
            <span class="mx-6">{{ $infoText }}</span>
        @endforeach
    </div>
    <!-- <div class="inline-flex animate-marquee tracking-widest uppercase opacity-90" aria-hidden="true">
        @foreach(range(1, 1) as $i)
            <span class="mx-6">{{ $infoText }}</span>
        @endforeach
    </div> -->
</div>

<style>
    @keyframes marquee {
        0% {
            transform: translateX(0%);
        }

        100% {
            transform: translateX(-100%);
        }
    }

    .animate-marquee {
        display: inline-flex;
        animation: marquee 50s linear infinite;
    }
</style>