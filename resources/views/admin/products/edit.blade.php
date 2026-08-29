<x-app-layout>
    @include('components.forms.tittle')

    <form action="{{ route('admin.' . $modul . '.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card p-4 sm:p-5">
            <div class="col-span-12 flex-1 flex flex-col" style="min-width:0;">
                <div class="card flex flex-col space-y-6 h-full p-6">
                    
                    {{-- Row 1: Customer, Category, Code, Name, Price --}}
                    <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                        {{-- Website Select --}}
                        <label class="block space-y-1.5">
                            <span>Website <span class="text-red-500">*</span></span>
                            <select name="customers_website_id" id="customer-select" required onchange="loadCategoriesByCustomer(this.value)" class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Website --</option>
                                @foreach($customers_websites as $cust)
                                    <option value="{{ $cust->id }}" {{ old('customers_website_id', $product->customers_website_id) == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customers_website_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Category Select --}}
                        <label class="block space-y-1.5">
                            <span>Category Product <span class="text-red-500">*</span></span>
                            <select name="category_products_id" id="category-select" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_products_id', $product->category_products_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} {{ $cat->code ? '('.$cat->code.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_products_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Code --}}
                        <label class="block space-y-1.5">
                            <span>Code <span class="text-red-500">*</span></span>
                            <x-input name="code" value="{{ old('code', $product->code) }}" placeholder="Enter Product Code" autocomplete="off" required readonly class="bg-slate-100 dark:bg-navy-600 font-semibold text-slate-700 dark:text-navy-100" />
                            @error('code')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Product Name --}}
                        <label class="block space-y-1.5">
                            <span>Product Name <span class="text-red-500">*</span></span>
                            <x-input name="name" value="{{ old('name', $product->name) }}" placeholder="Enter Product Name" autocomplete="off" required />
                            @error('name')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Price --}}
                        <label class="block space-y-1.5">
                            <span>Price (Rp) <span class="text-red-500">*</span></span>
                            <x-input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price) }}" placeholder="Enter Price" autocomplete="off" required />
                            @error('price')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Row 2: Description --}}
                    <div class="grid grid-cols-1 gap-4">
                        <label class="block space-y-1.5">
                            <span>Description (Optional)</span>
                            <textarea
                                name="description"
                                rows="4"
                                placeholder="Enter Product Description"
                                class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            >{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Row 3: Current Images & Add New Images --}}
                    <div class="grid grid-cols-1 gap-4">
                        @if(!empty($product->images) && is_array($product->images) && count($product->images) > 0)
                            <div>
                                <span class="text-xs font-medium text-slate-600 dark:text-navy-100 block mb-2">Existing Images:</span>
                                <div class="flex flex-wrap gap-4">
                                    @foreach($product->images as $img)
                                        <div class="relative group border border-slate-200 rounded-lg p-1 bg-slate-50 dark:bg-navy-700 shadow-sm" id="img-container-{{ $loop->index }}">
                                            <img src="{{ asset('storage/' . $img) }}" alt="Product Image" class="h-24 w-24 object-cover rounded-md" />
                                            <label class="mt-1 flex items-center justify-center space-x-1 cursor-pointer text-xs text-red-500 hover:text-red-700">
                                                <input type="checkbox" name="deleted_images[]" value="{{ $img }}" class="form-checkbox text-red-500 rounded" />
                                                <span>Delete</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <label class="block space-y-1.5 mt-2">
                            <span>Upload Additional Images (Multiple - Optional)</span>
                            <input
                                type="file"
                                name="images[]"
                                accept="image/*"
                                multiple
                                id="images-input"
                                class="form-input w-full rounded-lg border border-slate-300 bg-transparent p-2 hover:border-slate-400 focus:border-primary dark:border-navy-450"
                                onchange="previewMultipleImages(event)"
                            />
                            <p class="text-xs text-slate-400 mt-1">Select new images to append. Allowed formats: JPG, JPEG, PNG, GIF, WEBP, SVG (Max: 2MB per file)</p>
                            @error('images')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        <div id="images-preview-container" class="hidden mt-2">
                            <span class="text-xs font-medium text-slate-600 dark:text-navy-100 block mb-2">New Images Preview:</span>
                            <div id="images-preview-grid" class="flex flex-wrap gap-3"></div>
                        </div>

                    {{-- Row 4: Existing & New Product Reviews Section --}}
                    <div class="border-t border-slate-200 dark:border-navy-500 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-base font-semibold text-slate-700 dark:text-navy-100 flex items-center gap-2">
                                    <i class="fa-solid fa-star text-amber-400"></i> Product Reviews
                                </h3>
                                <p class="text-xs text-slate-400">Kelola ulasan / review produk yang sudah ada atau tambahkan ulasan baru.</p>
                            </div>
                            <button type="button" onclick="addNewReviewItem()" class="btn bg-primary text-white hover:bg-primary-focus dark:bg-accent dark:hover:bg-accent-focus text-xs font-medium px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                <i class="fa-solid fa-plus text-xs"></i> Add Review
                            </button>
                        </div>

                        {{-- Existing Reviews List --}}
                        @if($product->reviews && $product->reviews->count() > 0)
                            <div class="space-y-4 mb-4" id="existing-reviews-container">
                                @foreach($product->reviews as $review)
                                    <div class="p-4 border border-slate-200 dark:border-navy-500 rounded-xl bg-slate-50/50 dark:bg-navy-800 space-y-3 relative" id="existing-review-item-{{ $review->id }}">
                                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-navy-600 pb-2">
                                            <span class="text-xs font-bold text-slate-700 dark:text-navy-100 uppercase tracking-wider">Existing Review #{{ $loop->iteration }}</span>
                                            <label class="inline-flex items-center space-x-1 cursor-pointer text-xs text-red-500 hover:text-red-700 font-medium">
                                                <input type="checkbox" name="deleted_reviews[]" value="{{ $review->id }}" onchange="toggleDeleteReview({{ $review->id }}, this.checked)" class="form-checkbox text-red-500 rounded" />
                                                <span>Delete Review</span>
                                            </label>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3" id="existing-review-fields-{{ $review->id }}">
                                            {{-- Rating --}}
                                            <div class="sm:col-span-3">
                                                <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Rating (1-5 Star)</label>
                                                <select name="existing_reviews[{{ $review->id }}][rating]" class="form-select w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700">
                                                    @for($s=5; $s>=1; $s--)
                                                        <option value="{{ $s }}" {{ old('existing_reviews.'.$review->id.'.rating', $review->rating) == $s ? 'selected' : '' }}>
                                                            {{ str_repeat('⭐', $s) }} ({{ $s }} Star)
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>

                                            {{-- Reviewer Name --}}
                                            <div class="sm:col-span-4">
                                                <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Reviewer Name</label>
                                                <input type="text" name="existing_reviews[{{ $review->id }}][name]" value="{{ old('existing_reviews.'.$review->id.'.name', $review->name) }}" required class="form-input w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" />
                                            </div>

                                            {{-- Status --}}
                                            <div class="sm:col-span-3 flex items-end pb-1">
                                                <label class="inline-flex items-center space-x-2 cursor-pointer">
                                                    <input type="hidden" name="existing_reviews[{{ $review->id }}][status]" value="0" />
                                                    <input type="checkbox" name="existing_reviews[{{ $review->id }}][status]" value="1" {{ old('existing_reviews.'.$review->id.'.status', $review->status) ? 'checked' : '' }} class="form-checkbox is-outline h-4 w-4 rounded border-slate-300 text-primary dark:border-navy-450 dark:checked:bg-accent" />
                                                    <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Status (Active)</span>
                                                </label>
                                            </div>

                                            {{-- Profile Photo --}}
                                            <div class="sm:col-span-2">
                                                <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Profile Photo</label>
                                                @if($review->profile_photo)
                                                    <div class="flex items-center space-x-2 mb-1">
                                                        <img src="{{ asset('storage/' . $review->profile_photo) }}" class="h-8 w-8 object-cover rounded-full border border-slate-200" />
                                                        <span class="text-[10px] text-slate-400">Current</span>
                                                    </div>
                                                @endif
                                                <input type="file" name="existing_reviews[{{ $review->id }}][profile_photo]" accept="image/*" class="form-input w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" />
                                            </div>
                                        </div>

                                        {{-- Comment --}}
                                        <div>
                                            <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Comment / Review Text</label>
                                            <textarea name="existing_reviews[{{ $review->id }}][comment]" rows="2" class="form-textarea w-full rounded-lg border border-slate-300 bg-white p-2 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700">{{ old('existing_reviews.'.$review->id.'.comment', $review->comment) }}</textarea>
                                        </div>

                                        {{-- Review Photos --}}
                                        <div>
                                            <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Review Photos (Multiple)</label>
                                            @if(!empty($review->photos) && is_array($review->photos) && count($review->photos) > 0)
                                                <div class="flex flex-wrap gap-3 mb-2">
                                                    @foreach($review->photos as $rphoto)
                                                        <div class="relative group border border-slate-200 rounded-lg p-1 bg-white dark:bg-navy-700">
                                                            <img src="{{ asset('storage/' . $rphoto) }}" class="h-14 w-14 object-cover rounded-md" />
                                                            <label class="mt-1 flex items-center justify-center space-x-1 cursor-pointer text-[10px] text-red-500 hover:text-red-700">
                                                                <input type="checkbox" name="existing_reviews[{{ $review->id }}][deleted_photos][]" value="{{ $rphoto }}" class="form-checkbox text-red-500 rounded" />
                                                                <span>Delete</span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            <input type="file" name="existing_reviews[{{ $review->id }}][photos][]" accept="image/*" multiple class="form-input w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" />
                                            <p class="text-[10px] text-slate-400 mt-0.5">Select new images to append for this review.</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- New Reviews Container --}}
                        <div id="new-reviews-repeater-container" class="space-y-4"></div>
                    </div>

                </div>
            </div>
        </div>

        @include('components.forms.update')
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
        let newReviewCount = 0;

        function addNewReviewItem() {
            const container = document.getElementById('new-reviews-repeater-container');
            const index = newReviewCount++;
            const itemDiv = document.createElement('div');
            itemDiv.id = `new-review-item-${index}`;
            itemDiv.className = 'p-4 border border-slate-200 dark:border-navy-500 rounded-xl bg-slate-50/50 dark:bg-navy-800 space-y-3 relative';

            itemDiv.innerHTML = `
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-navy-600 pb-2">
                    <span class="text-xs font-bold text-slate-700 dark:text-navy-100 uppercase tracking-wider">New Review #${index + 1}</span>
                    <button type="button" onclick="removeNewReviewItem(${index})" class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1">
                        <i class="fa-solid fa-trash"></i> Remove
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                    {{-- Rating --}}
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Rating (1-5 Star) <span class="text-red-500">*</span></label>
                        <select name="new_reviews[${index}][rating]" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700">
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
                        <input type="text" name="new_reviews[${index}][name]" required placeholder="Name (e.g. Sarah J.)" class="form-input w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" />
                    </div>

                    {{-- Status --}}
                    <div class="sm:col-span-3 flex items-end pb-1">
                        <label class="inline-flex items-center space-x-2 cursor-pointer">
                            <input type="hidden" name="new_reviews[${index}][status]" value="0" />
                            <input type="checkbox" name="new_reviews[${index}][status]" value="1" checked class="form-checkbox is-outline h-4 w-4 rounded border-slate-300 text-primary dark:border-navy-450 dark:checked:bg-accent" />
                            <span class="text-xs font-medium text-slate-700 dark:text-navy-100">Status (Active)</span>
                        </label>
                    </div>

                    {{-- Profile Photo --}}
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Profile Photo</label>
                        <input type="file" name="new_reviews[${index}][profile_photo]" accept="image/*" class="form-input w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" onchange="previewNewProfilePhoto(event, ${index})" />
                        <div id="new-review-profile-preview-${index}" class="hidden mt-1 flex items-center space-x-2">
                            <img src="" class="h-8 w-8 object-cover rounded-full border border-slate-200" />
                        </div>
                    </div>
                </div>

                {{-- Comment --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Comment / Review Text</label>
                    <textarea name="new_reviews[${index}][comment]" rows="2" placeholder="Tuliskan ulasan / review..." class="form-textarea w-full rounded-lg border border-slate-300 bg-white p-2 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700"></textarea>
                </div>

                {{-- Multiple Review Photos --}}
                <div>
                    <label class="block text-xs font-medium text-slate-600 dark:text-navy-200 mb-1">Review Photos (Multiple)</label>
                    <input type="file" name="new_reviews[${index}][photos][]" accept="image/*" multiple class="form-input w-full rounded-lg border border-slate-300 bg-white px-2 py-1 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700" onchange="previewNewReviewPhotos(event, ${index})" />
                    <div id="new-review-photos-preview-${index}" class="hidden mt-2 flex flex-wrap gap-2"></div>
                </div>
            `;

            container.appendChild(itemDiv);
        }

        function removeNewReviewItem(index) {
            const itemDiv = document.getElementById(`new-review-item-${index}`);
            if (itemDiv) itemDiv.remove();
        }

        function toggleDeleteReview(id, isChecked) {
            const itemDiv = document.getElementById(`existing-review-item-${id}`);
            if (itemDiv) {
                if (isChecked) {
                    itemDiv.classList.add('opacity-50', 'bg-red-50/50', 'line-through');
                } else {
                    itemDiv.classList.remove('opacity-50', 'bg-red-50/50', 'line-through');
                }
            }
        }

        function previewNewProfilePhoto(event, index) {
            const input = event.target;
            const container = document.getElementById(`new-review-profile-preview-${index}`);
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

        function previewNewReviewPhotos(event, index) {
            const input = event.target;
            const container = document.getElementById(`new-review-photos-preview-${index}`);
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
    </script>
    @endpush
</x-app-layout>
