<x-app-layout>
    @include('components.forms.tittle')

    <form method="POST" action="{{ route('admin.' . $modul . '.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card p-4 sm:p-5">
            <div class="col-span-12 flex-1 flex flex-col" style="min-width:0;">
                <div class="card flex flex-col space-y-6 h-full p-6">
                    
                    {{-- Row 1: Customer & Article Category --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Website Select --}}
                        <label class="block space-y-1.5">
                            <span>Website <span class="text-red-500">*</span></span>
                            <select name="customers_website_id" id="customer-select" required onchange="loadCategoriesByCustomer(this.value)" class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Website --</option>
                                @foreach($customers_websites as $cust)
                                    <option value="{{ $cust->id }}" {{ old('customers_website_id') == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customers_website_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Article Category Select --}}
                        <label class="block space-y-1.5">
                            <span>Article Category <span class="text-red-500">*</span></span>
                            <select name="article_categories_id" id="category-select" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Customer First --</option>
                            </select>
                            @error('article_categories_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Row 2: Title & Subtitle --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Title --}}
                        <label class="block space-y-1.5">
                            <span>Title <span class="text-red-500">*</span></span>
                            <x-input name="title" placeholder="Enter Article Title" autocomplete="off" required value="{{ old('title') }}" />
                            @error('title')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Subtitle --}}
                        <label class="block space-y-1.5">
                            <span>Subtitle</span>
                            <x-input name="subtitle" placeholder="Enter Article Subtitle" autocomplete="off" value="{{ old('subtitle') }}" />
                            @error('subtitle')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Row 3: Author & Published Date --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Author --}}
                        <label class="block space-y-1.5">
                            <span>Author</span>
                            <x-input name="author" placeholder="Enter Author Name" autocomplete="off" value="{{ old('author') }}" />
                            @error('author')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Published Date --}}
                        <label class="block space-y-1.5">
                            <span>Published Date</span>
                            <x-input type="date" name="published_date" value="{{ old('published_date') }}" />
                            @error('published_date')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Row 4: Description --}}
                    <div class="grid grid-cols-1 gap-4">
                        <label class="block space-y-1.5">
                            <span>Description</span>
                            <textarea
                                name="description"
                                rows="5"
                                placeholder="Enter Article Content / Description"
                                class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Row 5: Multiple Images Upload --}}
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

                </div>
            </div>
        </div>

        @include('components.forms.save')
    </form>

    @push('scripts')
    <script>
        function loadCategoriesByCustomer(customerId, selectedCategoryId = null) {
            const categorySelect = $('#category-select');
            categorySelect.html('<option value="">Loading categories...</option>');

            if (!customerId) {
                categorySelect.html('<option value="">-- Select Customer First --</option>');
                return;
            }

            const url = "{{ route('admin.articles.getCategories', ':customers_website_id') }}".replace(':customers_website_id', customerId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    categorySelect.empty();
                    categorySelect.append('<option value="">-- Select Article Category --</option>');

                    if (data && data.length > 0) {
                        $.each(data, function (index, cat) {
                            const selected = (selectedCategoryId && selectedCategoryId == cat.id) ? 'selected' : '';
                            categorySelect.append(`<option value="${cat.id}" ${selected}>${cat.name}</option>`);
                        });
                    } else {
                        categorySelect.append('<option value="">-- No Categories Found --</option>');
                    }
                },
                error: function () {
                    categorySelect.html('<option value="">-- Error Loading Categories --</option>');
                }
            });
        }

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
                        const div = document.createElement('div');
                        div.className = 'relative group border border-slate-200 rounded-lg p-1 shadow-sm bg-white dark:bg-navy-700';
                        div.innerHTML = `<img src="${e.target.result}" alt="Preview" class="h-24 w-24 object-cover rounded-md" />`;
                        previewGrid.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                previewContainer.classList.add('hidden');
            }
        }

        $(document).ready(function() {
            const initialCustId = $('#customer-select').val();
            const oldCatId = "{{ old('article_categories_id') }}";
            if (initialCustId) {
                loadCategoriesByCustomer(initialCustId, oldCatId);
            }
        });
    </script>
    @endpush
</x-app-layout>
