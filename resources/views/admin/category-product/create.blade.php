<x-app-layout>
    @include('components.forms.tittle')

    <form method="POST" action="{{ route('admin.' . $modul . '.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="card p-4 sm:p-5">
            <div class="col-span-12 flex-1 flex flex-col" style="min-width:0;">
                <div class="card flex flex-col space-y-6 h-full p-6">
                    
                    {{-- Customer & Code & Name --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        {{-- Website Select --}}
                        <label class="block space-y-1.5">
                            <span>Website <span class="text-red-500">*</span></span>
                            <select name="customers_website_id" required class="form-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
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

                        {{-- Auto Generated Code --}}
                        <label class="block space-y-1.5">
                            <span>Code <span class="text-red-500">*</span></span>
                            <x-input name="code" placeholder="Auto Generated Code" autocomplete="off" required value="{{ old('code', $autoCode) }}" readonly class="bg-slate-100 dark:bg-navy-600 font-semibold text-slate-700 dark:text-navy-100" />
                            @error('code')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        {{-- Name --}}
                        <label class="block space-y-1.5">
                            <span>Name <span class="text-red-500">*</span></span>
                            <x-input name="name" placeholder="Enter Category Name" autocomplete="off" required value="{{ old('name') }}" />
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
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>

                    {{-- Image Upload --}}
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
                            <p class="text-xs text-slate-400 mt-1">Allowed formats: JPG, JPEG, PNG, GIF, WEBP, SVG (Max: 2MB)</p>
                            @error('image')
                                <span class="text-xs text-red-500">{{ $message }}</span>
                            @enderror
                        </label>

                        <div id="image-preview-container" class="hidden mt-2">
                            <span class="text-xs font-medium text-slate-600 dark:text-navy-100 block mb-1">Image Preview:</span>
                            <img id="image-preview" src="#" alt="Preview" class="h-32 w-32 object-cover rounded-lg border border-slate-200 shadow-sm" />
                        </div>
                    </div>

                </div>
            </div>
        </div>

        @include('components.forms.save')
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
