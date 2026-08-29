<x-app-layout>
    @include('components.forms.tittle')

    <form action="{{ route('admin.' . $modul . '.update', $article) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
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
                                    <option value="{{ $cust->id }}" {{ old('customers_website_id', $article->customers_website_id) == $cust->id ? 'selected' : '' }}>
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
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('article_categories_id', $article->article_categories_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
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
                            <x-input name="title" value="{{ old('title', $article->title) }}" placeholder="Enter Article Title" autocomplete="off" required />
                            @error('title')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Subtitle --}}
                        <label class="block space-y-1.5">
                            <span>Subtitle</span>
                            <x-input name="subtitle" value="{{ old('subtitle', $article->subtitle) }}" placeholder="Enter Article Subtitle" autocomplete="off" />
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
                            <x-input name="author" value="{{ old('author', $article->author) }}" placeholder="Enter Author Name" autocomplete="off" />
                            @error('author')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Published Date --}}
                        <label class="block space-y-1.5">
                            <span>Published Date</span>
                            <x-input type="date" name="published_date" value="{{ old('published_date', $article->published_date ? $article->published_date->format('Y-m-d') : '') }}" />
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
                            >{{ old('description', $article->description) }}</textarea>
                            @error('description')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Row 5: Images Upload & Existing Images Preview --}}
                    <div class="grid grid-cols-1 gap-4">
                        {{-- Existing Images --}}
                        @if(!empty($article->images) && is_array($article->images) && count($article->images) > 0)
                            <div>
                                <span class="text-xs font-medium text-slate-600 dark:text-navy-100 block mb-2">Existing Images:</span>
                                <div class="flex flex-wrap gap-3" id="existing-images-container">
                                    @foreach($article->images as $imgPath)
                                        @if(\Illuminate\Support\Facades\Storage::disk('public')->exists($imgPath))
                                            <div class="relative group border border-slate-200 rounded-lg p-1 shadow-sm bg-white dark:bg-navy-700" id="img-item-{{ Str::slug($imgPath) }}">
                                                <img src="{{ asset('storage/' . $imgPath) }}" alt="Article Image" class="h-24 w-24 object-cover rounded-md" />
                                                <button type="button" onclick="markImageForDeletion('{{ $imgPath }}', 'img-item-{{ Str::slug($imgPath) }}')" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs shadow hover:bg-red-600 focus:outline-none" title="Remove Image">
                                                    <i class="fa-solid fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div id="deleted-images-inputs"></div>
                            </div>
                        @endif

                        {{-- Upload New Images --}}
                        <label class="block space-y-1.5 mt-2">
                            <span>Add New Images (Optional)</span>
                            <input
                                type="file"
                                name="images[]"
                                accept="image/*"
                                multiple
                                id="images-input"
                                class="form-input w-full rounded-lg border border-slate-300 bg-transparent p-2 hover:border-slate-400 focus:border-primary dark:border-navy-450"
                                onchange="previewMultipleImages(event)"
                            />
                            <p class="text-xs text-slate-400 mt-1">Select one or more images to append. Allowed formats: JPG, JPEG, PNG, GIF, WEBP, SVG (Max: 2MB per file)</p>
                            @error('images')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                            @error('images.*')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        <div id="images-preview-container" class="hidden mt-2">
                            <span class="text-xs font-medium text-slate-600 dark:text-navy-100 block mb-2">New Images Preview:</span>
                            <div id="images-preview-grid" class="flex flex-wrap gap-3"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('components.forms.update')
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

        function markImageForDeletion(imgPath, elementId) {
            if (confirm('Are you sure you want to remove this image?')) {
                $(`#${elementId}`).remove();
                $('#deleted-images-inputs').append(`<input type="hidden" name="deleted_images[]" value="${imgPath}">`);
            }
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
    </script>
    @endpush
</x-app-layout>
