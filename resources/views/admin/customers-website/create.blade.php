<x-app-layout>
    <div class="flex mb-4 items-center justify-between py-5 lg:py-6">
        <div class="flex items-center space-x-4">
            <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">{{ $modul_name }}</h2>
            <div class="hidden h-full py-1 sm:flex">
                <div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>
            </div>
            <ul class="hidden flex-wrap items-center space-x-2 sm:flex">
                <li class="flex items-center space-x-2">
                    <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <i class="fa-solid fa-angle-right text-xs"></i>
                </li>
                <li class="flex items-center space-x-2">
                    <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                        href="{{ route('admin.customers-website') }}">Customers Website</a>
                    <i class="fa-solid fa-angle-right text-xs"></i>
                </li>
                <li>Create</li>
            </ul>
        </div>
    </div>

    <div class="max-w-3xl">
        <div class="card p-4 sm:p-5">
            <h3 class="text-base font-medium text-slate-700 dark:text-navy-100 mb-4">Add New Customer Website</h3>

            <form action="{{ route('admin.customers-website.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Customer -->
                    <label class="block">
                        <span class="font-medium text-slate-700 dark:text-navy-100">Customer <span class="text-error">*</span></span>
                        <select name="customer_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white dark:bg-navy-700 px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} ({{ $customer->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </label>

                    <!-- Customer Type -->
                    <label class="block">
                        <span class="font-medium text-slate-700 dark:text-navy-100">Customer Type</span>
                        <select name="customer_type" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white dark:bg-navy-700 px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                            <option value="">-- Select Customer Type --</option>
                            @foreach(['Free Edition', 'Starter Edition', 'Standar Edition', 'Business Edition', 'Enterprise Edition'] as $customerType)
                                <option value="{{ $customerType }}" {{ old('customer_type') == $customerType ? 'selected' : '' }}>{{ $customerType }}</option>
                            @endforeach
                        </select>
                        @error('customer_type')
                            <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Title -->
                    <label class="block">
                        <span class="font-medium text-slate-700 dark:text-navy-100">Website Title <span class="text-error">*</span></span>
                        <input name="title" value="{{ old('title') }}" placeholder="Enter website title"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text" required>
                        @error('title')
                            <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </label>

                    <!-- Domain -->
                    <label class="block">
                        <span class="font-medium text-slate-700 dark:text-navy-100">Domain</span>
                        <input name="domain" value="{{ old('domain') }}" placeholder="e.g. mywebsite.com"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text">
                        @error('domain')
                            <span class="text-xs text-error mt-1">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <!-- Description -->
                <label class="block">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Description</span>
                    <textarea name="description" rows="3" placeholder="Website description..."
                        class="form-textarea mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                    @enderror
                </label>

                <!-- QR Payment -->
                <div class="rounded-xl border border-slate-200 dark:border-navy-500 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                        <div class="sm:w-40 shrink-0">
                            <label for="qr-payment-input"
                                class="cursor-pointer group flex w-full flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-4 text-center transition-colors hover:border-primary hover:bg-primary/5 dark:border-navy-450 dark:bg-navy-700/40 dark:hover:border-accent dark:hover:bg-accent/5">
                                <img id="qr-payment-preview"
                                    class="h-24 w-24 rounded-lg border border-slate-200 bg-white object-contain p-1 transition-transform group-hover:scale-105 dark:border-navy-500"
                                    src="{{ asset('images/200x200.png') }}"
                                    alt="QR Payment preview" />
                                <span class="flex items-center gap-1.5 text-xs font-medium text-primary dark:text-accent-light">
                                    <i class="fa-solid fa-qrcode"></i>
                                    Upload QR Payment
                                </span>
                                <input id="qr-payment-input" type="file" name="qr_payment" accept="image/png,image/jpeg,image/webp"
                                    onchange="previewQrPayment(event)" class="hidden" />
                            </label>
                        </div>
                        <div class="text-xs text-slate-500 dark:text-navy-200 space-y-1">
                            <p class="font-medium text-slate-700 dark:text-navy-100">QR Payment (Opsional)</p>
                            <p>Unggah gambar QR pembayaran (misalnya QRIS) untuk website ini. QR akan ditampilkan kepada pembeli saat checkout.</p>
                            <p class="text-slate-400">Format: JPG, PNG, atau WEBP &mdash; ukuran maksimal 2MB. Rasio 1:1 disarankan.</p>
                            <p id="qr-payment-file-name" class="hidden font-medium text-primary dark:text-accent-light"></p>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="rounded-xl border border-slate-200 dark:border-navy-500 p-4 space-y-4">
                    <div class="flex items-center space-x-2">
                        <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">
                            Social Media
                        </h4>
                    </div>

                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Instagram URL</span>
                        <input name="instagram" value="{{ old('instagram') }}" placeholder="https://instagram.com/username" autocomplete="off"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text">
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-navy-100">TikTok URL</span>
                        <input name="tiktok" value="{{ old('tiktok') }}" placeholder="https://tiktok.com/@username" autocomplete="off"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text">
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Facebook URL</span>
                        <input name="facebook" value="{{ old('facebook') }}" placeholder="https://facebook.com/username" autocomplete="off"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text">
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-navy-100">X (Twitter) URL</span>
                        <input name="x" value="{{ old('x') }}" placeholder="https://x.com/username" autocomplete="off"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text">
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Threads URL</span>
                        <input name="threads" value="{{ old('threads') }}" placeholder="https://threads.net/@username" autocomplete="off"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text">
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Shopee URL</span>
                        <input name="shopee" value="{{ old('shopee') }}" placeholder="https://shopee.co.id/shopname" autocomplete="off"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text">
                    </label>

                    <label class="block">
                        <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Tokopedia URL</span>
                        <input name="tokopedia" value="{{ old('tokopedia') }}" placeholder="https://tokopedia.com/shopname" autocomplete="off"
                            class="form-input mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            type="text">
                    </label>
                </div>

                <!-- Is Active Status -->
                <div class="flex items-center justify-between pt-2">
                    <span class="font-medium text-slate-700 dark:text-navy-100">Status</span>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input name="is_active" type="checkbox" value="1" checked
                            class="form-switch is-outline h-5 w-10 rounded-full border border-slate-400/70 bg-slate-100 transition-colors checked:bg-primary checked:border-primary dark:border-navy-400 dark:bg-navy-900 dark:checked:bg-accent dark:checked:border-accent">
                        <span class="text-xs font-medium text-slate-600 dark:text-navy-200">Active</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end space-x-2 pt-4">
                    <a href="{{ route('admin.customers-website') }}"
                        class="btn min-w-[7rem] border border-slate-300 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-450 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="btn min-w-[7rem] bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus">
                        Save Website
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewQrPayment(event) {
                const input = event.target;
                const file = input.files[0];
                const preview = document.getElementById('qr-payment-preview');
                const nameEl = document.getElementById('qr-payment-file-name');

                if (!file) {
                    preview.src = "{{ asset('images/200x200.png') }}";
                    if (nameEl) nameEl.classList.add('hidden');
                    return;
                }
                if (!file.type.startsWith('image/')) {
                    alert('Only image files (JPG, PNG, WEBP) are allowed.');
                    input.value = '';
                    preview.src = "{{ asset('images/200x200.png') }}";
                    if (nameEl) nameEl.classList.add('hidden');
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
                if (nameEl) {
                    nameEl.textContent = 'Selected: ' + file.name;
                    nameEl.classList.remove('hidden');
                }
            }
        </script>
    @endpush
</x-app-layout>
