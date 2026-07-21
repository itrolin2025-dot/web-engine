<x-app-layout>

    @include('components.forms.tittle')

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-100 px-4 py-3 text-green-800">
            {{ session('success') }}
            <a href="{{ route($modul . '.index') }}" class="underline text-green-700 hover:text-green-900 ml-2">Kembali ke Daftar {{ $modul_name }}</a>
        </div>
    @endif

    <form method="POST" action="{{ route($modul . '.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
            
            <div class="col-span-12 lg:col-span-8">
                <div class="card h-full">
                    <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">
                                Info
                            </h4>
                        </div>
                    </div>
                    <div class="space-y-4 p-4 sm:p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="block w-full">
                                <div class="flex items-center space-x-2 mb-1.5">
                                    <span>Code</span>
                                    <span class="text-xs ml-2" style="color:red;">(Keep empty for auto generated code)</span>
                                </div>
                                <x-input name="code" value="{{ old('code') }}" placeholder="Enter Code" autocomplete="off"/>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <label class="block space-y-1.5 w-full">
                                <span>Name <span style="color:red">*</span></span>
                                <x-input name="name" value="{{ old('name') }}" placeholder="Enter Name" autocomplete="off" required/>
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="block space-y-3">
                                <span>Province</span>
                                <select name="province" id="province-select" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                    <option value="">-- Select Province --</option>
                                    @foreach($provinces as $prov)
                                        <option value="{{ $prov->name }}" {{ old('province') == $prov->name ? 'selected' : '' }}>{{ $prov->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block space-y-3">
                                <span>City</span>
                                <select name="city" id="city-select" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                    <option value="">-- Select City --</option>
                                </select>
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 mt-4">
                            
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="block space-y-1.5 w-full">
                                <span>Email</span>
                                <x-input type="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" autocomplete="off"/>
                            </label>
                            <label class="block space-y-1.5 w-full">
                                <span>Phone </span>
                                <x-input name="phone" value="{{ old('phone') }}" placeholder="Enter Phone" autocomplete="off" />
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mt-4">
                            <label class="block space-y-1.5 w-full">
                                <span>Address </span>
                                <x-textarea
                                    rows="4"
                                    placeholder="Your Address"
                                    name="address"
                                >{{ old('address') }}</x-textarea>
                            </label>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 flex flex-col gap-4">
                <!-- Photo (kanan atas) SEDERHANA -->
                <div class="card">
                    <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                        <div class="flex items-center space-x-2">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">
                                Photo
                            </h4>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5">
                        <div class="flex flex-col items-center w-full">
                            <label for="photo-upload" class="cursor-pointer group flex flex-col items-center w-full">
                                <img
                                    id="photo-preview"
                                    class="w-24 h-24 object-cover border-slate-200 bg-gray-100 transition-transform group-hover:scale-105"
                                    src="{{ old('photo') ? old('photo') : asset('images/'.$modul_path.'/default.png') }}"
                                    alt="avatar"
                                    style="border-radius: 0.75rem;"
                                />
                                <input
                                    id="photo-upload"
                                    type="file"
                                    name="photo"
                                    accept="image/*"
                                    onchange="previewPhoto(event)"
                                    class="hidden"
                                    alt="click for change the image"
                                />
                            </label>
                        </div>
                        <script>
                        function previewPhoto(event) {
                            const input = event.target;
                            const file = input.files[0];
                            const preview = document.getElementById('photo-preview');
                            
                            if (!file) {
                                preview.src = "{{ asset('images/'.$modul_path.'/default.png') }}";
                                return;
                            }
                            if (!file.type.startsWith('image/')) {
                                alert('Only image files (JPG, PNG, JPEG, GIF, etc) are allowed.');
                                input.value = "";
                                preview.src = "{{ asset('images/'.$modul_path.'/default.png') }}";
                                return;
                            }
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                        </script>
                    </div>
                </div>
                <div class="card">
                    <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                        <div class="flex items-center space-x-2">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">
                                Interested
                            </h4>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5">
                        <label class="block space-y-3">
                            <span>Source</span>
                            <select name="source" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Source --</option>
                                <option value="Website">Website</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Tiktok">Tiktok</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Internal">Internal</option>
                            </select>
                        </label>
                        <br>
                        <label class="block space-y-3 mt-2">
                            <span>Product Interest</span>
                            <x-input name="interest" value="{{ old('interest') }}" placeholder="Enter Interest" autocomplete="off" />
                        </label>
                        <br>
                        <label class="block space-y-3">
                            <span>Status</span>
                            <select name="status" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Select Status --</option>
                                <option value="New">New</option>
                                <option value="Contacted">Contacted</option>
                                <option value="Qualified">Qualified</option>
                                <option value="Rejected">Rejected</option>
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
        $(document).ready(function() {
            var oldCity = "{{ old('city') }}";
            var provinceSelect = $('#province-select');
            
            // Trigger change on page load if province is selected (to reload cities if validation fails)
            if (provinceSelect.val()) {
                loadCities(provinceSelect.val(), oldCity);
            }

            provinceSelect.on('change', function() {
                loadCities($(this).val());
            });

            function loadCities(provinceName, selectedCity = null) {
                var citySelect = $('#city-select');
                citySelect.html('<option value="">Loading...</option>');
                
                if (!provinceName) {
                    citySelect.html('<option value="">-- Select City --</option>');
                    return;
                }

                $.ajax({
                    url: "{{ url('leads/get-cities') }}/" + encodeURIComponent(provinceName),
                    type: 'GET',
                    success: function(cities) {
                        var html = '<option value="">-- Select City --</option>';
                        cities.forEach(function(city) {
                            var isSelected = selectedCity === city.name ? 'selected' : '';
                            html += `<option value="${city.name}" ${isSelected}>${city.name}</option>`;
                        });
                        citySelect.html(html);
                    },
                    error: function() {
                        citySelect.html('<option value="">Error loading cities</option>');
                    }
                });
            }
        });
    </script>
    @endpush

</x-app-layout>