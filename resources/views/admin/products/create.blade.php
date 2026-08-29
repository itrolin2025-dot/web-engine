<x-app-layout>
    @include('components.forms.tittle')

    <form method="POST" action="{{ route('admin.' . $modul . '.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card p-4 sm:p-5">
            <div class="col-span-12 flex-1 flex flex-col" style="min-width:0;">
                <div class="card flex flex-col space-y-6 h-full p-6">
                    
                    {{-- Row 1: Customer, Category, Code, Name, Price --}}
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                        {{-- Customer Select --}}
                        <label class="block space-y-1.5">
                            <span>Customer <span class="text-red-500">*</span></span>
                            <select name="customers_id" id="customer-select" required onchange="loadCategoriesByCustomer(this.value)" class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $cust)
                                    <option value="{{ $cust->id }}" {{ old('customers_id') == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->name }} {{ $cust->code ? '('.$cust->code.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customers_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Category Select --}}
                        <label class="block space-y-1.5">
                            <span>Category Product <span class="text-red-500">*</span></span>
                            <select name="category_products_id" id="category-select" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Customer First --</option>
                            </select>
                            @error('category_products_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Auto Code --}}
                        <label class="block space-y-1.5">
                            <span>Code <span class="text-red-500">*</span></span>
                            <x-input name="code" placeholder="Auto Generated Code" autocomplete="off" required value="{{ old('code', $autoCode) }}" readonly class="bg-slate-100 dark:bg-navy-600 font-semibold text-slate-700 dark:text-navy-100" />
                            @error('code')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Product Name --}}
                        <label class="block space-y-1.5">
                            <span>Product Name <span class="text-red-500">*</span></span>
                            <x-input name="name" placeholder="Enter Product Name" autocomplete="off" required value="{{ old('name') }}" />
                            @error('name')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Price --}}
                        <label class="block space-y-1.5">
                            <span>Price (Rp) <span class="text-red-500">*</span></span>
                            <x-input type="number" step="0.01" min="0" name="price" placeholder="Enter Price (e.g. 150000)" autocomplete="off" required value="{{ old('price') }}" />
                            @error('price')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Row 2: Description (Nullable) --}}
                    <div class="grid grid-cols-1 gap-4">
                        <label class="block space-y-1.5">
                            <span>Description (Optional)</span>
                            <textarea
                                name="description"
                                rows="4"
                                placeholder="Enter Product Description"
                                class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Row 3: Multiple Images Upload (Nullable) --}}
                    <div class="grid grid-cols-1 gap-4">
                        <label class="block space-y-1.5">
                            <span>Images (Multiple - Optional)</span>
                            <input
                                type="file"
                                name="images[]"
                                accept="image/*"
                                multiple
                                id="images-input"
                                class="form-input w-full rounded-lg border border-slate-300 bg-transparent p-2 hover:border-slate-400 focus:border-primary dark:border-navy-450"
                                onchange="previewMultipleImages(event)"
                            />
                            <p class="text-xs text-slate-400 mt-1">Select one or more images. Allowed formats: JPG, JPEG, PNG, GIF, WEBP, SVG (Max: 2MB per file)</p>
                            @error('images')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                            @error('images.*')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        <div id="images-preview-container" class="hidden mt-2">
                            <span class="text-xs font-medium text-slate-600 dark:text-navy-100 block mb-2">Images Preview:</span>
                            <div id="images-preview-grid" class="flex flex-wrap gap-3"></div>
                        </div>
                    </div>

                    {{-- Row 4: Product Reviews Section (Repeater) --}}
                    <div class="border-t border-slate-200 dark:border-navy-500 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100 flex items-center gap-2">
                                    <i class="fa-solid fa-star text-amber-400"></i> Product Reviews (Optional)
                                </h3>
                                <p class="text-xs text-slate-400">Tambahkan ulasan / review produk untuk ditampilkan pada website.</p>
                            </div>
                            <button type="button" onclick="addReviewItem()" class="btn bg-primary text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus text-xs font-medium px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                <i class="fa-solid fa-plus text-xs"></i> Add Review
                            </button>
                        </div>

                        <div id="reviews-repeater-container" class="space-y-4">
                            <p id="no-reviews-text" class="text-xs text-slate-400 italic">Belum ada review ditambahkan. Klik "Add Review" untuk menambahkan.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('components.forms.save')
    </form>

    @push('scripts')
    <script>
        function previewMultipleImages(event) {
            const input = event.target;
            const previewContainer = document.getElementById('images-preview-container');
            const previewGrid = document.getElementById('images-preview-grid');

            previewGrid.innerHTML = '';

            if (input.files && input.files.length > 0) {
                previewContainer.classList.remove('hidden');
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imgWrapper = document.createElement('div');
                        imgWrapper.className = 'relative group';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-24 w-24 object-cover rounded-lg border border-slate-200 shadow-sm';

                        imgWrapper.appendChild(img);
                        previewGrid.appendChild(imgWrapper);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                previewContainer.classList.add('hidden');
            }
        }
        let reviewCount = 0;

        function addReviewItem() {
            const container = document.getElementById('reviews-repeater-container');
            const noReviewsText = document.getElementById('no-reviews-text');
            if (noReviewsText) noReviewsText.classList.add('hidden');

            const index = reviewCount++;
            const itemDiv = document.createElement('div');
            itemDiv.id = `review-item-${index}`;
            itemDiv.className = 'p-4 border border-slate-200 dark:border-navy-500 rounded-xl bg-slate-50/50 dark:bg-navy-800 space-y-3 relative';

            itemDiv.innerHTML = `
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-navy-600 pb-2">
                    <span class="text-xs font-bold text-slate-700 dark:text-navy-100 uppercase tracking-wider">Review #${index + 1}</span>
                    <button type="button" onclick="removeReviewItem(${index})" class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-trash"></i> Remove
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    {{-- Rating --}}
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Rating (1-5 Star) <span class="text-red-500">*</span></label>
                        <select name="reviews[${index}][rating]" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700">
                            <option value="5" selected>⭐⭐⭐⭐⭐ (5 Star)</option>
                            <option value="4">⭐⭐⭐⭐ (4 Star)</option>
                            <option value="3">⭐⭐⭐ (3 Star)</option>
                            <option value="2">⭐⭐ (2 Star)</option>
                            <option value="1">⭐ (1 Star)</option>
                        </select>
                    </div>

                    {{-- Reviewer Name --}}
                    <div class="sm:col-span-4">
                        <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Reviewer Name <span class="text-red-500">*</span></label>
                        <input type="text" name="reviews[${index}][name]" required placeholder="Name (e.g. Sarah J.)" class="form-input w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" />
                    </div>

                    {{-- Status --}}
                    <div class="sm:col-span-3 flex items-end pb-1">
                        <label class="inline-flex items-center space-x-2 cursor-pointer">
                            <input type="hidden" name="reviews[${index}][status]" value="0" />
                            <input type="checkbox" name="reviews[${index}][status]" value="1" checked class="form-checkbox is-outline h-4 w-4 rounded border-slate-300 text-primary dark:border-navy-450 dark:checked:bg-accent" />
                            <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Status (Active)</span>
                        </label>
                    </div>

                    {{-- Profile Photo --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Profile Photo</label>
                        <input type="file" name="reviews[${index}][profile_photo]" accept="image/*" class="form-input w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" onchange="previewProfilePhoto(event, ${index})" />
                        <div id="review-profile-preview-${index}" class="hidden mt-1 flex items-center space-x-2">
                            <img src="" class="h-8 w-8 object-cover rounded-full border border-slate-200" />
                        </div>
                    </div>
                </div>

                {{-- Comment --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Comment / Review Text</label>
                    <textarea name="reviews[${index}][comment]" rows="2" placeholder="Tuliskan ulasan / review..." class="form-textarea w-full rounded-lg border border-slate-300 bg-white p-2 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700"></textarea>
                </div>

                {{-- Multiple Review Photos --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Review Photos (Multiple)</label>
                    <input type="file" name="reviews[${index}][photos][]" accept="image/*" multiple class="form-input w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" onchange="previewReviewPhotos(event, ${index})" />
                    <div id="review-photos-preview-${index}" class="hidden mt-2 flex flex-wrap gap-2"></div>
                </div>
            `;

            container.appendChild(itemDiv);
        }

        function removeReviewItem(index) {
            const itemDiv = document.getElementById(`review-item-${index}`);
            if (itemDiv) itemDiv.remove();

            const container = document.getElementById('reviews-repeater-container');
            if (container.children.length === 0 || (container.children.length === 1 && container.children[0].id === 'no-reviews-text')) {
                const noReviewsText = document.getElementById('no-reviews-text');
                if (noReviewsText) noReviewsText.classList.remove('hidden');
            }
        }

        function previewProfilePhoto(event, index) {
            const input = event.target;
            const container = document.getElementById(`review-profile-preview-${index}`);
            if (!container) return;

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = container.querySelector('img');
                    img.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                container.classList.add('hidden');
            }
        }

        function previewReviewPhotos(event, index) {
            const input = event.target;
            const container = document.getElementById(`review-photos-preview-${index}`);
            if (!container) return;

            container.innerHTML = '';
            if (input.files && input.files.length > 0) {
                container.classList.remove('hidden');
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'h-16 w-16 object-cover rounded-lg border border-slate-200 shadow-sm';
                        container.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                container.classList.add('hidden');
            }
        }

        function loadCategoriesByCustomer(customerId, selectedCategoryId = null) {
            const categorySelect = document.getElementById('category-select');
            categorySelect.innerHTML = '<option value="">Loading...</option>';

            if (!customerId) {
                categorySelect.innerHTML = '<option value="">-- Select Customer First --</option>';
                return;
            }

            fetch("{{ url('admin/products/get-categories') }}/" + customerId)
                .then(response => response.json())
                .then(data => {
                    categorySelect.innerHTML = '<option value="">-- Select Category --</option>';
                    if (data.length === 0) {
                        categorySelect.innerHTML = '<option value="">-- No Categories Found for this Customer --</option>';
                    } else {
                        data.forEach(cat => {
                            const selected = (selectedCategoryId && selectedCategoryId == cat.id) ? 'selected' : '';
                            const option = document.createElement('option');
                            option.value = cat.id;
                            option.textContent = cat.name + (cat.code ? ' (' + cat.code + ')' : '');
                            if (selected) option.selected = true;
                            categorySelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                    categorySelect.innerHTML = '<option value="">-- Error Loading Categories --</option>';
                });
        }

        // Auto trigger on page load if customer is preselected (e.g. old input)
        document.addEventListener('DOMContentLoaded', function() {
            const customerSelect = document.getElementById('customer-select');
            const oldCategoryId = "{{ old('category_products_id') }}";
            if (customerSelect && customerSelect.value) {
                loadCategoriesByCustomer(customerSelect.value, oldCategoryId);
            }
        });
    </script>
    @endpush
</x-app-layout>
