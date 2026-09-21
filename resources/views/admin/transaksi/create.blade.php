<x-app-layout>

    @include('components.forms.tittle')

    <form method="POST" action="{{ route('admin.' . $modul . '.store') }}">
        @csrf

        <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">

            {{-- ================= Kolom Kiri ================= --}}
            <div class="col-span-12 lg:col-span-8 flex flex-col gap-4">

                {{-- Info Transaksi --}}
                <div class="card">
                    <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Info Transaksi</h4>
                        </div>
                    </div>
                    <div class="space-y-4 p-4 sm:p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="block space-y-1.5 w-full">
                                <span>Code</span>
                                <span class="text-xs ml-2" style="color:red;">(Auto generated)</span>
                                <x-input name="code" value="{{ old('code', $autoCode) }}" placeholder="Auto Generated Code"
                                    autocomplete="off" readonly
                                    class="bg-slate-100 dark:bg-navy-600 font-semibold text-slate-700 dark:text-navy-100" />
                            </label>
                            <label class="block space-y-1.5 w-full">
                                <span>Customers Website <span style="color:red">*</span></span>
                                <select name="customers_website_id" id="website-select" required
                                    onchange="loadWebsiteData(this.value)"
                                    class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                    <option value="">-- Select Website --</option>
                                    @foreach($customers_websites as $w)
                                        <option value="{{ $w->id }}" {{ old('customers_website_id') == $w->id ? 'selected' : '' }}>
                                            {{ $w->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <label class="block space-y-1.5 w-full">
                                <span>Customer Name <span style="color:red">*</span></span>
                                <span class="text-xs ml-2 text-slate-400">(otomatis terisi setelah memilih website, boleh diubah)</span>
                                <x-input name="customer_name" id="customer-name" value="{{ old('customer_name') }}"
                                    placeholder="Enter Customer Name" autocomplete="off" required />
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <label class="block space-y-1.5 w-full">
                                <span>Customer Address <span style="color:red">*</span></span>
                                <textarea rows="3" name="customer_address" id="customer-address" required
                                    placeholder="Customer Address"
                                    class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('customer_address') }}</textarea>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Detail Barang --}}
                <div class="card">
                    <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                <i class="fa-solid fa-basket-shopping"></i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Detail Barang</h4>
                        </div>
                        <button type="button" onclick="addItemRow()"
                            class="btn bg-primary text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus text-xs font-medium px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                            <i class="fa-solid fa-plus text-xs"></i> Add Item
                        </button>
                    </div>
                    <div class="p-4 sm:p-5">
                        <p class="text-xs text-slate-400 mb-3">
                            <i class="fa-solid fa-circle-info mr-1"></i>
                            Pilih website terlebih dahulu — daftar produk akan dimuat dari website yang dipilih.
                            Harga otomatis terisi dari produk namun tetap bisa diubah (mis. untuk diskon).
                        </p>

                        <div id="items-container" class="space-y-3">
                            <p id="no-items-text" class="text-xs text-slate-400 italic">
                                Belum ada barang. Klik "Add Item" untuk menambahkan produk.
                            </p>
                        </div>

                        {{-- Ringkasan --}}
                        <div class="mt-5 border-t border-slate-200 dark:border-navy-500 pt-4">
                            <div class="flex justify-end">
                                <div class="w-full sm:w-72 space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-navy-200">Subtotal</span>
                                        <span id="summary-subtotal" class="font-medium text-slate-700 dark:text-navy-100">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-500 dark:text-navy-200">Ongkos Kirim</span>
                                        <span id="summary-shipping" class="font-medium text-slate-700 dark:text-navy-100">Rp 0</span>
                                    </div>
                                    <div class="flex justify-between border-t border-slate-200 dark:border-navy-500 pt-2">
                                        <span class="font-semibold text-slate-700 dark:text-navy-100">Total</span>
                                        <span id="summary-total" class="font-bold text-primary dark:text-accent-light">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= Kolom Kanan ================= --}}
            <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">

                {{-- Pengiriman --}}
                <div class="card">
                    <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                        <div class="flex items-center space-x-2">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Pengiriman</h4>
                        </div>
                    </div>
                    <div class="space-y-4 p-4 sm:p-5">
                        <label class="block space-y-1.5 w-full">
                            <span>Shipping Address</span>
                            <span class="text-xs ml-2 text-slate-400">(kosongkan = sama dengan alamat customer)</span>
                            <textarea rows="3" name="shipping_address" id="shipping-address"
                                placeholder="Shipping Address"
                                class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent">{{ old('shipping_address') }}</textarea>
                        </label>

                        <label class="block space-y-1.5 w-full">
                            <span>Courier</span>
                            <select name="shipping_courier"
                                class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Courier --</option>
                                @foreach(['JNE', 'J&T Express', 'SiCepat', 'AnterAja', 'GoSend', 'GrabExpress', 'Ambil Sendiri', 'Lainnya'] as $courier)
                                    <option value="{{ $courier }}" {{ old('shipping_courier') == $courier ? 'selected' : '' }}>{{ $courier }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block space-y-1.5 w-full">
                            <span>Tracking Number</span>
                            <x-input name="shipping_tracking_number" value="{{ old('shipping_tracking_number') }}"
                                placeholder="e.g. JNE1234567890" autocomplete="off" />
                        </label>

                        <label class="block space-y-1.5 w-full">
                            <span>Shipping Cost (Rp)</span>
                            <x-input type="number" step="0.01" min="0" name="shipping_cost" id="shipping-cost"
                                value="{{ old('shipping_cost', 0) }}" placeholder="0" autocomplete="off"
                                oninput="recalcTotals()" />
                        </label>

                        <label class="block space-y-1.5 w-full">
                            <span>Shipping Status</span>
                            <select name="shipping_status"
                                class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                @foreach(['Pending', 'Packing', 'Shipped', 'Delivered'] as $st)
                                    <option value="{{ $st }}" {{ old('shipping_status', 'Pending') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>

                {{-- Status Transaksi --}}
                <div class="card">
                    <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                        <div class="flex items-center space-x-2">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">Status</h4>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5">
                        <label class="block space-y-1.5 w-full">
                            <span>Transaction Status</span>
                            <select name="status"
                                class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                @foreach(['Pending', 'Paid', 'Shipped', 'Completed', 'Cancelled'] as $st)
                                    <option value="{{ $st }}" {{ old('status', 'Pending') == $st ? 'selected' : '' }}>{{ $st }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        @include('components.forms.save')
    </form>

    @push('scripts')
        <script>
            var websiteProducts = [];
            var itemRowIndex = 0;
            var shippingAddressTouched = {{ old('shipping_address') ? 'true' : 'false' }};

            function formatRp(num) {
                return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
            }

            // ===== Load customer info + products saat website dipilih =====
            function loadWebsiteData(websiteId) {
                var nameInput = document.getElementById('customer-name');
                var addressInput = document.getElementById('customer-address');
                var shippingInput = document.getElementById('shipping-address');

                if (!websiteId) {
                    websiteProducts = [];
                    document.getElementById('items-container').innerHTML =
                        '<p id="no-items-text" class="text-xs text-slate-400 italic">Belum ada barang. Klik "Add Item" untuk menambahkan produk.</p>';
                    recalcTotals();
                    return;
                }

                // Info customer (prefill, boleh diubah manual)
                fetch("{{ url('admin/transactions/get-customer') }}/" + websiteId)
                    .then(r => r.json())
                    .then(data => {
                        if (!data) return;
                        if (!nameInput.value || nameInput.dataset.autofilled === '1') {
                            nameInput.value = data.name || '';
                            nameInput.dataset.autofilled = '1';
                        }
                        if (!addressInput.value || addressInput.dataset.autofilled === '1') {
                            addressInput.value = data.address || '';
                            addressInput.dataset.autofilled = '1';
                        }
                        if (!shippingAddressTouched) {
                            shippingInput.value = data.address || '';
                        }
                    })
                    .catch(err => console.error('Gagal memuat info customer:', err));

                // Produk website
                fetch("{{ url('admin/transactions/get-products') }}/" + websiteId)
                    .then(r => r.json())
                    .then(data => {
                        websiteProducts = data || [];
                        // Reset item rows karena daftar produk berubah
                        document.getElementById('items-container').innerHTML = '';
                        itemRowIndex = 0;
                        addItemRow();
                    })
                    .catch(err => console.error('Gagal memuat produk:', err));
            }

            // Track apakah shipping address sudah diedit manual
            document.addEventListener('DOMContentLoaded', function () {
                var shippingInput = document.getElementById('shipping-address');
                if (shippingInput) {
                    shippingInput.addEventListener('input', function () {
                        shippingAddressTouched = true;
                    });
                }
                // Trigger awal jika website sudah terpilih (old input / error redirect)
                var websiteSelect = document.getElementById('website-select');
                if (websiteSelect && websiteSelect.value) {
                    loadWebsiteData(websiteSelect.value);
                }
            });

            // ===== Repeater Detail Barang =====
            function productOptions(selectedId) {
                var html = '<option value="">-- Select Product --</option>';
                websiteProducts.forEach(function (p) {
                    var sel = (selectedId && selectedId == p.id) ? ' selected' : '';
                    html += '<option value="' + p.id + '" data-price="' + p.price + '"' + sel + '>' +
                        p.name + ' — ' + formatRp(p.price) + '</option>';
                });
                return html;
            }

            function addItemRow(product) {
                var container = document.getElementById('items-container');
                var noItems = document.getElementById('no-items-text');
                if (noItems) noItems.remove();

                var idx = itemRowIndex++;
                var row = document.createElement('div');
                row.id = 'item-row-' + idx;
                row.className = 'p-4 border border-slate-200 dark:border-navy-500 rounded-xl bg-slate-50/50 dark:bg-navy-800';

                row.innerHTML =
                    '<div class="flex items-center justify-between border-b border-slate-200 dark:border-navy-500 pb-2 mb-3">' +
                    '<span class="text-xs font-bold text-slate-700 dark:text-navy-100 uppercase tracking-wider">Item #' + (idx + 1) + '</span>' +
                    '<button type="button" onclick="removeItemRow(' + idx + ')" class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1">' +
                    '<i class="fa-solid fa-trash"></i> Remove</button>' +
                    '</div>' +
                    '<div class="grid grid-cols-1 sm:grid-cols-12 gap-3">' +
                    '<div class="sm:col-span-6">' +
                    '<label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Product <span class="text-red-500">*</span></label>' +
                    '<select name="product_id[]" data-idx="' + idx + '" required onchange="onProductChange(this)" ' +
                    'class="form-select w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700">' +
                    productOptions(product ? product.product_id : null) + '</select>' +
                    '<input type="hidden" name="product_name[]" value="' + (product ? product.product_name.replace(/"/g, '&quot;') : '') + '" />' +
                    '</div>' +
                    '<div class="sm:col-span-2">' +
                    '<label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Qty <span class="text-red-500">*</span></label>' +
                    '<input type="number" name="qty[]" min="1" value="' + (product ? product.qty : 1) + '" required oninput="recalcTotals()" ' +
                    'class="form-input w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" />' +
                    '</div>' +
                    '<div class="sm:col-span-2">' +
                    '<label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>' +
                    '<input type="number" name="price[]" step="0.01" min="0" value="' + (product ? product.price : 0) + '" required oninput="recalcTotals()" ' +
                    'class="form-input w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" />' +
                    '</div>' +
                    '<div class="sm:col-span-2">' +
                    '<label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Subtotal</label>' +
                    '<div class="item-subtotal text-xs font-semibold text-slate-700 dark:text-navy-100 px-2.5 py-1.5">Rp 0</div>' +
                    '</div>' +
                    '</div>';

                container.appendChild(row);
                recalcTotals();
            }

            function onProductChange(selectEl) {
                var idx = selectEl.dataset.idx;
                var row = document.getElementById('item-row-' + idx);
                var option = selectEl.options[selectEl.selectedIndex];
                var nameHidden = row.querySelector('input[name="product_name[]"]');
                var priceInput = row.querySelector('input[name="price[]"]');

                if (option && option.value) {
                    nameHidden.value = option.textContent.split(' — ')[0];
                    if (option.dataset.price && parseFloat(priceInput.value) === 0) {
                        priceInput.value = option.dataset.price;
                    }
                } else {
                    nameHidden.value = '';
                }
                recalcTotals();
            }

            function removeItemRow(idx) {
                var row = document.getElementById('item-row-' + idx);
                if (row) row.remove();
                recalcTotals();
            }

            // ===== Perhitungan total =====
            function recalcTotals() {
                var subtotal = 0;
                document.querySelectorAll('#items-container > div[id^="item-row-"]').forEach(function (row) {
                    var qty = parseFloat(row.querySelector('input[name="qty[]"]').value) || 0;
                    var price = parseFloat(row.querySelector('input[name="price[]"]').value) || 0;
                    var rowSubtotal = qty * price;
                    subtotal += rowSubtotal;
                    row.querySelector('.item-subtotal').textContent = formatRp(rowSubtotal);
                });

                var shipping = parseFloat(document.getElementById('shipping-cost').value) || 0;
                document.getElementById('summary-subtotal').textContent = formatRp(subtotal);
                document.getElementById('summary-shipping').textContent = formatRp(shipping);
                document.getElementById('summary-total').textContent = formatRp(subtotal + shipping);
            }
        </script>
    @endpush

</x-app-layout>
