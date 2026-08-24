<x-app-layout>
    @include('components.forms.tittle')

    <form method="POST" action="{{ route('admin.' . $modul . '.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card p-4 sm:p-5">
            <div class="col-span-12 flex-1 flex flex-col" style="min-width:0;">
                <div class="card flex flex-col space-y-6 h-full p-6">
                    
                    {{-- Row 1: Category, Code, Name, Price --}}
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                        {{-- Category Select --}}
                        <label class="block space-y-1.5">
                            <span>Category Product <span class="text-red-500">*</span></span>
                            <select name="category_products_id" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_products_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} {{ $cat->code ? '('.$cat->code.')' : '' }}
                                    </option>
                                @endforeach
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
    </script>
    @endpush
</x-app-layout>
