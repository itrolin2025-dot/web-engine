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

    <div class="max-w-6xl">
        <form action="{{ route('admin.customers-website.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 items-start gap-5 lg:grid-cols-2">
                <!-- ==================== COLUMN KIRI: Add New Customer Website ==================== -->
                <div class="card p-4 sm:p-5">
                    <h3 class="text-base font-medium text-slate-700 dark:text-navy-100 mb-4">Add New Customer Website</h3>

                    <div class="space-y-4">
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
                    </div>
                </div>

                <!-- ==================== COLUMN KANAN: Website Identity ==================== -->
                <div class="card p-4 sm:p-5">
                    <h3 class="text-base font-medium text-slate-700 dark:text-navy-100 mb-4">Website Identity</h3>

                    <div class="space-y-4">
                        <!-- Logo -->
                        <div class="rounded-xl border border-slate-200 dark:border-navy-500 p-4">
                            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                                <div class="sm:w-40 shrink-0">
                                    <label for="logo-input"
                                        class="cursor-pointer group flex w-full flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 p-4 text-center transition-colors hover:border-primary hover:bg-primary/5 dark:border-navy-450 dark:bg-navy-700/40 dark:hover:border-accent dark:hover:bg-accent/5">
                                        <img id="logo-preview"
                                            class="h-24 w-24 rounded-lg border border-slate-200 bg-white object-contain p-1 transition-transform group-hover:scale-105 dark:border-navy-500"
                                            src="{{ asset('images/200x200.png') }}"
                                            alt="Logo preview" />
                                        <span class="flex items-center gap-1.5 text-xs font-medium text-primary dark:text-accent-light">
                                            <i class="fa-solid fa-image"></i>
                                            Upload Logo
                                        </span>
                                        <input id="logo-input" type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml"
                                            onchange="previewLogo(event)" class="hidden" />
                                    </label>
                                </div>
                                <div class="text-xs text-slate-500 dark:text-navy-200 space-y-1">
                                    <p class="font-medium text-slate-700 dark:text-navy-100">Logo Website (Opsional)</p>
                                    <p>Logo utama yang mewakili identitas website ini — akan tampil di navbar &amp; footer storefront.</p>
                                    <p class="text-slate-400">Format: JPG, PNG, WEBP, atau SVG &mdash; maksimal 2MB. PNG transparan disarankan.</p>
                                    <p id="logo-file-name" class="hidden font-medium text-primary dark:text-accent-light"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Colors -->
                        <div class="rounded-xl border border-slate-200 dark:border-navy-500 p-4 space-y-4">
                            <div class="flex items-center space-x-2">
                                <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                    <i class="fa-solid fa-palette"></i>
                                </div>
                                <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Brand Colors</h4>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <!-- Primary Color -->
                                <label class="block">
                                    <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Primary Color</span>
                                    <span class="mt-1.5 flex items-center gap-2">
                                        <input type="color" value="{{ old('primary_color', '#4F46E5') }}" data-color-sync="primary_color"
                                            class="h-10 w-12 shrink-0 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 dark:border-navy-450 dark:bg-navy-700">
                                        <input name="primary_color" value="{{ old('primary_color') }}" placeholder="#4F46E5" autocomplete="off"
                                            data-color-text="primary_color"
                                            class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 font-mono text-xs placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                            type="text">
                                    </span>
                                    @error('primary_color')
                                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                                    @enderror
                                </label>

                                <!-- Secondary Color -->
                                <label class="block">
                                    <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Secondary Color</span>
                                    <span class="mt-1.5 flex items-center gap-2">
                                        <input type="color" value="{{ old('secondary_color', '#0EA5E9') }}" data-color-sync="secondary_color"
                                            class="h-10 w-12 shrink-0 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 dark:border-navy-450 dark:bg-navy-700">
                                        <input name="secondary_color" value="{{ old('secondary_color') }}" placeholder="#0EA5E9" autocomplete="off"
                                            data-color-text="secondary_color"
                                            class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 font-mono text-xs placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                            type="text">
                                    </span>
                                    @error('secondary_color')
                                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                                    @enderror
                                </label>

                                <!-- Accent Color -->
                                <label class="block">
                                    <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Accent Color</span>
                                    <span class="mt-1.5 flex items-center gap-2">
                                        <input type="color" value="{{ old('accent_color', '#F59E0B') }}" data-color-sync="accent_color"
                                            class="h-10 w-12 shrink-0 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 dark:border-navy-450 dark:bg-navy-700">
                                        <input name="accent_color" value="{{ old('accent_color') }}" placeholder="#F59E0B" autocomplete="off"
                                            data-color-text="accent_color"
                                            class="form-input w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 font-mono text-xs placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                            type="text">
                                    </span>
                                    @error('accent_color')
                                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>

                            <!-- Font Color -->
                            <label class="block">
                                <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Font Color</span>
                                <span class="mt-1.5 flex items-center gap-2">
                                    <input type="color" value="{{ old('font_color', '#0F172A') }}" data-color-sync="font_color"
                                        class="h-10 w-12 shrink-0 cursor-pointer rounded-lg border border-slate-300 bg-white p-1 dark:border-navy-450 dark:bg-navy-700">
                                    <input name="font_color" value="{{ old('font_color') }}" placeholder="#0F172A" autocomplete="off"
                                        data-color-text="font_color"
                                        class="form-input w-full sm:max-w-[calc(100%-3.5rem)] rounded-lg border border-slate-300 bg-transparent px-3 py-2 font-mono text-xs placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent sm:max-w-xs"
                                        type="text">
                                </span>
                                @error('font_color')
                                    <span class="text-xs text-error mt-1">{{ $message }}</span>
                                @enderror
                            </label>
                        </div>

                        <!-- Fonts -->
                        <div class="rounded-xl border border-slate-200 dark:border-navy-500 p-4 space-y-4">
                            <div class="flex items-center space-x-2">
                                <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                    <i class="fa-solid fa-font"></i>
                                </div>
                                <h4 class="text-base font-medium text-slate-700 dark:text-navy-100">Brand Fonts</h4>
                            </div>

                            <div class="grid grid-cols-1 items-end gap-4 gap-x-3 sm:grid-cols-2">
                                <!-- Primary Font -->
                                <label class="block">
                                    <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Primary Font</span>
                                    <select name="primary_font" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white dark:bg-navy-700 px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                                        <option value="">-- Default --</option>
                                        @foreach(['Inter','Poppins','Roboto','Open Sans','Lato','Montserrat','Playfair Display','Raleway','Nunito','DM Sans','Plus Jakarta Sans'] as $font)
                                            <option value="{{ $font }}" {{ old('primary_font') == $font ? 'selected' : '' }}>{{ $font }}</option>
                                        @endforeach
                                    </select>
                                    @error('primary_font')
                                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                                    @enderror
                                </label>

                                <!-- Preview Primary Font -->
                                <div data-font-preview="primary_font"
                                    class="flex h-11 items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 dark:border-navy-500 dark:bg-navy-700/40">
                                    <span class="font-preview-aa text-2xl font-semibold leading-none text-slate-700 dark:text-navy-100">Aa</span>
                                    <div class="min-w-0">
                                        <p class="font-preview-name truncate text-xs font-semibold leading-tight text-slate-600 dark:text-navy-200">Default</p>
                                        <p class="font-preview-sample truncate text-[10px] leading-tight text-slate-400">The quick brown fox</p>
                                    </div>
                                </div>

                                <!-- Secondary Font -->
                                <label class="block">
                                    <span class="text-sm font-medium text-slate-700 dark:text-navy-100">Secondary Font</span>
                                    <select name="secondary_font" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white dark:bg-navy-700 px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">
                                        <option value="">-- Default --</option>
                                        @foreach(['Inter','Poppins','Roboto','Open Sans','Lato','Montserrat','Playfair Display','Raleway','Nunito','DM Sans','Plus Jakarta Sans'] as $font)
                                            <option value="{{ $font }}" {{ old('secondary_font') == $font ? 'selected' : '' }}>{{ $font }}</option>
                                        @endforeach
                                    </select>
                                    @error('secondary_font')
                                        <span class="text-xs text-error mt-1">{{ $message }}</span>
                                    @enderror
                                </label>

                                <!-- Preview Secondary Font -->
                                <div data-font-preview="secondary_font"
                                    class="flex h-11 items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 dark:border-navy-500 dark:bg-navy-700/40">
                                    <span class="font-preview-aa text-2xl font-semibold leading-none text-slate-700 dark:text-navy-100">Aa</span>
                                    <div class="min-w-0">
                                        <p class="font-preview-name truncate text-xs font-semibold leading-tight text-slate-600 dark:text-navy-200">Default</p>
                                        <p class="font-preview-sample truncate text-[10px] leading-tight text-slate-400">The quick brown fox</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end space-x-2">
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

            function previewLogo(event) {
                const input = event.target;
                const file = input.files[0];
                const preview = document.getElementById('logo-preview');
                const nameEl = document.getElementById('logo-file-name');

                if (!file) {
                    preview.src = "{{ asset('images/200x200.png') }}";
                    if (nameEl) nameEl.classList.add('hidden');
                    return;
                }
                if (!file.type.startsWith('image/')) {
                    alert('Only image files (JPG, PNG, WEBP, SVG) are allowed.');
                    input.value = '';
                    preview.src = "{{ asset('images/200x200.png') }}";
                    if (nameEl) nameEl.classList.add('hidden');
                    return;
                }
                if (file.type === 'image/svg+xml') {
                    preview.src = URL.createObjectURL(file);
                } else {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
                if (nameEl) {
                    nameEl.textContent = 'Selected: ' + file.name;
                    nameEl.classList.remove('hidden');
                }
            }

            // Sinkronisasi color picker <-> text hex input
            document.querySelectorAll('[data-color-sync]').forEach(function (picker) {
                const key = picker.dataset.colorSync;
                const text = document.querySelector('[data-color-text="' + key + '"]');
                if (!text) return;

                // Saat swatch dipilih -> isi text
                picker.addEventListener('input', function () {
                    text.value = picker.value.toUpperCase();
                });

                // Saat text diisi hex valid -> update swatch
                text.addEventListener('input', function () {
                    const val = text.value.trim();
                    if (/^#[0-9a-fA-F]{6}$/.test(val)) {
                        picker.value = val;
                    }
                });

                // Prefill swatch dari text yang sudah ada (validation old input)
                if (/^#[0-9a-fA-F]{6}$/.test(text.value.trim())) {
                    picker.value = text.value.trim();
                }
            });

            // ===== Font Preview: load Google Font & tampilkan di kotak preview =====
            function ensureGoogleFont(family) {
                if (!family) return;
                var id = 'gf-' + family.replace(/\s+/g, '-').toLowerCase();
                if (document.getElementById(id)) return;
                var link = document.createElement('link');
                link.id = id;
                link.rel = 'stylesheet';
                link.href = 'https://fonts.googleapis.com/css2?family=' + family.replace(/\s+/g, '+') + ':wght@400;600&display=swap';
                document.head.appendChild(link);
            }

            function updateFontPreviews() {
                document.querySelectorAll('[data-font-preview]').forEach(function (box) {
                    var select = document.querySelector('select[name="' + box.dataset.fontPreview + '"]');
                    if (!select) return;
                    var family = select.value;
                    box.style.fontFamily = family ? "'" + family + "', sans-serif" : '';
                    box.querySelector('.font-preview-name').textContent = family || 'Default';
                    ensureGoogleFont(family);
                });
            }

            document.querySelectorAll('select[name="primary_font"], select[name="secondary_font"]').forEach(function (select) {
                select.addEventListener('change', updateFontPreviews);
            });
            updateFontPreviews();
        </script>
    @endpush
</x-app-layout>
