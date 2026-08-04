<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

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
                        <label class="inline-flex items-center space-x-2" hidden>
                            <input
                            @if(old('is_active', true)) checked @endif
                            class="form-switch h-5 w-10 rounded-full bg-slate-300 before:rounded-full before:bg-slate-50 checked:bg-primary checked:before:bg-white dark:bg-navy-900 dark:before:bg-navy-300 dark:checked:bg-accent dark:checked:before:bg-white"
                            type="checkbox"
                            name = "is_active"
                            />
                            <span>Active</span>
                        </label>
                    </div>
                    <div class="space-y-4 p-4 sm:p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="block space-y-1.5 w-full">
                                <span>Code </span>
                                <x-input name="code" value="{{ old('code', $staff->code ?? '') }}" placeholder="Enter Code" autocomplete="off"/>
                            </label>
                            <label class="block space-y-1.5 w-full">
                                <span>Date Join </span>
                                <x-input 
                                    type="date" 
                                    id="date_join_input" 
                                    name="date_join"
                                    value="{{ old('date_join', $staff->date_join ?? '') }}"
                                />
                            </label>
                        </div>

                        <div class="grid grid-cols-1 gap-4">
                            <label class="block space-y-1.5 w-full">
                                <span>Name <span style="color:red">*</span></span>
                                <x-input name="name" value="{{ old('name', $staff->name ?? $staff->user_name) }}" placeholder="Enter Name" autocomplete="off" required/>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="block space-y-3">
                                <span>Departemen</span>
                                <select name="departemen_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                    <option value="">-- No Departemen --</option>
                                    @foreach ($departemens as $departemen)
                                        <option value="{{ $departemen->id }}" {{ old('departemen_id', $staff->departemen_id ?? '') == $departemen->id ? 'selected' : '' }}>
                                            {{ $departemen->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block space-y-1.5 w-full">
                                <span>Position </span>
                                <x-input name="position" value="{{ old('position', $staff->position ?? '') }}" placeholder="Enter Position" autocomplete="off" />
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 mt-4">
                            
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="block space-y-1.5 w-full">
                                <span>Email <span style="color:red">*</span></span>
                                <x-input type="email" name="email" value="{{ old('email', $staff->email ?? $staff->username) }}" placeholder="Enter Email" autocomplete="off" required/>
                            </label>
                            <label class="block space-y-1.5 w-full">
                                <span>Phone </span>
                                <x-input name="phone" value="{{ old('phone', $staff->phone ?? '') }}" placeholder="Enter Phone" autocomplete="off" />
                            </label>
                        </div>
                        <div class="grid grid-cols-1 gap-4 mt-4">
                            <label class="block w-full">
                                <span class="block mb-1 font-medium text-slate-700 dark:text-navy-100">Address</span>
                                <textarea
                                    rows="4"
                                    placeholder="Your Address"
                                    name = "address"
                                    class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                >{{ old('status', $staff->address ?? '') }}</textarea>
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
                                    src="{{ !empty($staff->photo) ? asset($staff->photo) : asset('images/profile/default.png') }}"
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
                                // If no file, revert to original or default (not easy purely in JS without tracking original state, but acceptable)
                                return;
                            }
                            if (!file.type.startsWith('image/')) {
                                alert('Only image files (JPG, PNG, JPEG, GIF, etc) are allowed.');
                                input.value = "";
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
                                Status
                            </h4>
                        </div>
                    </div>
                    <div class="p-4 sm:p-5">
                        <label class="block w-full">
                            <span class="block mb-1 font-medium text-slate-700 dark:text-navy-100">Status</span>
                            <textarea
                                rows="4"
                                placeholder=" Your Status"
                                name = "status"
                                class="form-textarea w-full rounded-lg border border-slate-300 bg-transparent p-2.5 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                            >{{ old('status', $staff->status ?? '') }}</textarea>
                        </label>
                    </div>
                </div>

                <div class="card">
                    <div class="border-b border-slate-200 p-4 dark:border-navy-500 sm:px-5">
                        <div class="flex items-center space-x-2">
                            <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                                <i class="fa-solid fa-layer-group"></i>
                            </div>
                            <h4 class="text-lg font-medium text-slate-700 dark:text-navy-100">
                                Account
                            </h4>
                        </div>
                    </div>
                    <div class="p-2 sm:p-5">
                        <label class="block w-full">
                            <span class="block mb-1 font-medium text-slate-700 dark:text-navy-100">Username</span>
                            <x-input name="username" value="{{ old('username', $staff->username ?? '') }}" placeholder="Enter Username" type="text" autocomplete="off" />
                        </label>
                        <br>
                        <label class="block w-full">
                            <span class="block mb-1 font-medium text-slate-700 dark:text-navy-100">Password</span>
                            <x-input name="password" value="{{ old('password') }}" placeholder="Enter Password" type="password" autocomplete="off" />
                            @error('password')
                                <span class="text-tiny+ text-error mt-1">{{ $message }}</span>
                            @enderror
                        </label>
                        <br>
                        <label class="block w-full">
                            <span class="block mb-1 font-medium text-slate-700 dark:text-navy-100">Re-type Password</span>
                            <x-input name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Enter Password" type="password" autocomplete="off" />
                            @error('password_confirmation')
                                <span class="text-tiny+ text-error mt-1">{{ $message }}</span>
                            @enderror
                        </label>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Update and Cancel Buttons -->
        <div class="flex flex-row gap-2 pt-3 justify-center items-center">
            <a href="{{ route('dashboard') }}" class="btn bg-slate-300 text-slate-700 py-2 px-6 rounded hover:bg-slate-400 transition-colors">
                Cancel
            </a>
            <button type="submit" class="btn bg-primary text-white font-semibold py-2 px-6 rounded hover:bg-primary/90 transition-colors">
                Update
            </button>
        </div>
        <!-- End Buttons -->

    </form>