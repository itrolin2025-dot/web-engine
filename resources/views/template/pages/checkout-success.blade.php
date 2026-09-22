<!DOCTYPE html>
<html lang="en">

@php
    $navContent = $navbarPresets ?? [];
    $logoFile = $navContent['image'] ?? null;
    $favicon = $logoFile ? '/images/website/' . ($website->domain ?? '') . '/' . $logoFile : null;
    $homeUrl = url('/' . ($website->domain ?? ''));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $website->title ?? 'Store' }} - Order Success</title>
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ asset($favicon) }}">
    @else
        <link rel="icon" href="data:,">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f3f3; margin: 0; padding: 0; }
    </style>
</head>
<body class="text-zinc-900 antialiased selection:bg-black selection:text-white">

    <header class="w-full bg-[#f3f3f3] border-b border-zinc-200 px-6 lg:px-12 py-4 flex items-center justify-between sticky top-0 z-50">
        <a href="{{ $homeUrl }}" class="font-black tracking-widest text-lg md:text-xl">{{ strtoupper($website->title ?? 'STORE') }}</a>
        <a href="{{ $homeUrl }}" class="text-sm text-zinc-800 hover:text-black"><i class="fa-solid fa-arrow-left-long"></i></a>
    </header>

    <main class="w-full bg-[#f3f3f3] px-6 lg:px-16 py-16">
        <div class="max-w-2xl mx-auto text-center">
            <div class="w-20 h-20 mx-auto rounded-full bg-zinc-900 text-white flex items-center justify-center mb-8">
                <i class="fa-solid fa-check text-3xl"></i>
            </div>

            <h1 class="text-4xl lg:text-5xl font-black tracking-tight mb-4">THANK YOU!</h1>
            <p class="text-sm text-zinc-500 mb-10">Your order has been placed successfully. We will contact you shortly to confirm the delivery.</p>

            @if($transaction)
            <div class="bg-[#eaeaea] rounded-lg border border-zinc-300 p-6 lg:p-8 text-left">
                <div class="flex justify-between items-center border-b border-zinc-300 pb-4 mb-4">
                    <div>
                        <p class="text-xs uppercase tracking-wider text-zinc-500 mb-1">Order Number</p>
                        <p class="text-lg font-black tracking-wide">{{ $transaction->code }}</p>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-widest bg-zinc-900 text-white px-3 py-1.5 rounded">{{ $transaction->status }}</span>
                </div>

                <div class="space-y-1.5 text-sm mb-6">
                    <div class="flex justify-between"><span class="text-zinc-500">Name</span><span class="font-medium">{{ $transaction->customer_name }}</span></div>
                    @if($transaction->customer_phone)
                        <div class="flex justify-between"><span class="text-zinc-500">Phone</span><span class="font-medium">{{ $transaction->customer_phone }}</span></div>
                    @endif
                    @if($transaction->payment_method)
                        <div class="flex justify-between"><span class="text-zinc-500">Payment</span><span class="font-medium">{{ $transaction->payment_method }}</span></div>
                    @endif
                    <div class="flex justify-between"><span class="text-zinc-500">Courier</span><span class="font-medium">{{ $transaction->shipping_courier ?? 'Standard' }}</span></div>
                </div>

                <!-- Items -->
                <div class="divide-y divide-zinc-300 border-y border-zinc-300 mb-4">
                    @foreach($transaction->details as $detail)
                        <div class="py-3 flex justify-between text-sm">
                            <span>{{ $detail->product_name }} <span class="text-zinc-400">&times; {{ $detail->qty }}</span></span>
                            <span class="font-semibold">Rp {{ number_format((float) $detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-zinc-600">
                        <span>Subtotal</span>
                        <span class="font-medium text-zinc-900">Rp {{ number_format((float) $transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-zinc-600">
                        <span>Shipping</span>
                        <span class="font-medium text-zinc-900">{{ (float) $transaction->shipping_cost > 0 ? 'Rp ' . number_format((float) $transaction->shipping_cost, 0, ',', '.') : 'Free' }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-3 border-t border-zinc-300">
                        <span>Total:</span>
                        <span>Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <a href="{{ $homeUrl }}"
                class="inline-block bg-zinc-900 text-white font-bold py-4 px-10 rounded text-xs tracking-widest mt-10 hover:bg-zinc-800 transition-all uppercase">
                Continue Shopping
            </a>
        </div>
    </main>

</body>
</html>
