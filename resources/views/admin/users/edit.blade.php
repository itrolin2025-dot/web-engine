<x-app-layout>
    
    @include('components.forms.tittle')

    <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
        <div class="col-span-12 sm:col-span-12">
            <form id="user-create-form" method="POST" action="{{ route($modul . '.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="card p-4 sm:p-5">
                    <p class="text-base font-medium text-slate-700 dark:text-navy-100"></p>
                    <div class="col-span-12 lg:col-span-9 flex-1 flex flex-col" style="min-width:0;">
                        <div class="card flex flex-col space-y-6 h-full" style="padding:1.5em; height:100%;">
                            
                            {{-- BARIS 1 --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="block space-y-1.5">
                                    <span>Name <span style="color:red">*</span></span>
                                    <x-input name="name" value="{{ $user->name }}" placeholder="Enter Name" autocomplete="off" required/>
                                </label>
                            </div>

                            {{-- BARIS 2 --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <label class="block space-y-1.5">
                                    <span>Username <span style="color:red">*</span></span>
                                    <x-input name="email" value="{{ $user->email }}" placeholder="Enter Username" autocomplete="off" required/>
                                </label>
    
                                <label class="block space-y-3">
                                    <span>Role <span style="color:red">*</span></span>
                                    <select name="role_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" required>
                                        <option value="" class="text-slate-600 dark:text-navy-200">-- Choose Role --</option>
                                        <option value="0" {{ $user->role_id == 0 ? 'selected' : '' }} class="text-slate-600 dark:text-navy-200">Super Admin</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                            {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                                
                            </div>

                            {{-- BARIS 3 --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="block space-y-1.5">
                                    <span>Password</span>
                                    <x-input name="password" id="password" placeholder="Your Password" type="password" autocomplete="off"/>
                                </label>
                            </div>

                            {{-- BARIS 4 --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="block space-y-1.5">
                                    <span>Re-type Password</span>
                                    <x-input name="password_confirmation" id="password_confirmation" placeholder="Re-type Password" type="password" autocomplete="off"/>
                                    <p class="mt-1 text-xs text-red-500 hidden" id="retype-error">Passwords do not match.</p>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                @include('components.forms.update')
                
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('user-create-form').addEventListener('submit', function(e) {
            var password = document.getElementById('password').value;
            var retype = document.getElementById('password_confirmation').value;
            var errorEl = document.getElementById('retype-error');

            // Password tidak wajib diisi pada edit user; hanya validasi jika salah satu diisi
            if (password === "" && retype === "") {
                errorEl.classList.add('hidden');
                return;
            }

            if (password === "" || retype === "") {
                e.preventDefault();
                errorEl.classList.remove('hidden');
                errorEl.textContent = "Please fill in both password fields.";
                if(password === "") {
                    document.getElementById('password').focus();
                } else {
                    document.getElementById('password_confirmation').focus();
                }
                return;
            }

            if (password !== retype) {
                e.preventDefault();
                errorEl.classList.remove('hidden');
                errorEl.textContent = "Passwords do not match.";
                document.getElementById('password_confirmation').focus();
            } else {
                errorEl.classList.add('hidden');
            }
        });
    </script>
    @endpush

</x-app-layout>