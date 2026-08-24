<x-app-layout>
    @include('components.forms.tittle')

    <form action="{{ route('admin.' . $modul . '.update', $category_product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card p-4 sm:p-5">
            <div class="col-span-12 flex-1 flex flex-col" style="min-width:0;">
                <div class="card flex flex-col space-y-6 h-full p-6">
                    
                    {{-- Customer & Code & Name --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Customer Select --}}
                        <label class="block space-y-1.5">
                            <span>Customer <span class="text-red-500">*</span></span>
                            <select name="customer_id" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Customer --</option>
                                @foreach($customers as $cust)
                                    <option value="{{ $cust->id }}" {{ old('customer_id', $category_product->customer_id) == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->name }} {{ $cust->code ? '('.$cust->code.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('customer_id')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Code --}}
                        <label class="block space-y-1.5">
                            <span>Code <span class="text-red-500">*</span></span>
                            <x-input name="code" value="{{ old('code', $category_product->code) }}" placeholder="Enter Category Code" autocomplete="off" required readonly class="bg-slate-100 dark:bg-navy-600 font-semibold text-slate-700 dark:text-navy-100" />
                            @error('code')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Name --}}
                        <label class="block space-y-1.5">
                            <span>Name <span class="text-red-500">*</span></span>
                            <x-input name="name" value="{{ old('name', $category_product->name) }}" placeholder="Enter Category Name" autocomplete="off" required />
                            @error('name')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Description --}}
                    <div class="grid grid-cols-1 gap-4">
                        <label class="block space-y-1.5">
                            <span>Description</span>
                            <textarea
                                name="description"
                                rows="4"
                                placeholder="Enter Category Description"
                                class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            >{{ old('description', $category_product->description) }}</textarea>
                            @error('description')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Image Upload & Current Image Preview --}}
                    <div class="grid grid-cols-1 gap-4">
                        <label class="block space-y-1.5">
                            <span>Image</span>
                            <input
                                type="file"
                                name="image"
                                accept="image/*"
                                id="image-input"
                                class="form-input w-full rounded-lg border border-slate-300 bg-transparent p-2 hover:border-slate-400 focus:border-primary dark:border-navy-450"
                                onchange="previewImage(event)"
                            />
                            <p class="text-xs text-slate-400 mt-1">Leave blank to keep existing image. Allowed formats: JPG, JPEG, PNG, GIF, WEBP, SVG (Max: 2MB)</p>
                            @error('image')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        <div class="mt-2 flex space-x-6 items-center">
                            @if($category_product->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($category_product->image))
                                <div>
                                    <span class="text-xs font-medium text-slate-600 dark:text-navy-100 block mb-1">Current Image:</span>
                                    <img src="{{ asset('storage/' . $category_product->image) }}" alt="Current Image" class="h-32 w-32 object-cover rounded-lg border border-slate-200 shadow-sm" />
                                </div>
                            @endif

                            <div id="image-preview-container" class="hidden">
                                <span class="text-xs font-medium text-slate-600 dark:text-navy-100 block mb-1">New Image Preview:</span>
                                <img id="image-preview" src="#" alt="New Preview" class="h-32 w-32 object-cover rounded-lg border border-slate-200 shadow-sm" />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('components.forms.update')
    </form>

    @push('scripts')
    <script>
        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('image-preview-container');
            const preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
