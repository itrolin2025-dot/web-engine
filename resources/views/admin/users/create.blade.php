<x-app-layout>
    
    @include('components.forms.tittle')

    <div class="grid grid-cols-12 gap-4 sm:gap-5 lg:gap-6">
        <div class="col-span-12 sm:col-span-12">
            <form id="user-create-form" method="POST" action="{{ route($modul . '.store') }}" >
                @csrf
                <div class="card p-4 sm:p-5">
                    <p class="text-base font-medium text-slate-700 dark:text-navy-100"></p>
                    <div class="col-span-12 lg:col-span-9 flex-1 flex flex-col" style="min-width:0;">
                        <div class="card flex flex-col space-y-6 h-full" style="padding:1.5em; height:100%;">
                            
                            {{-- BARIS 1 --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="block space-y-1.5">
                                    <span>Name <span style="color:red">*</span></span>
                                    <x-input name="name" placeholder="Enter Name" autocomplete="off" required/>
                                </label>
                            </div>

                            {{-- BARIS 2 --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <label class="block space-y-1.5">
                                    <span>Username <span style="color:red">*</span></span>
                                    <x-input name="email" placeholder="Enter Username" autocomplete="off" required/>
                                </label>
    
                                <label class="block space-y-3">
                                    <span>Role <span style="color:red">*</span></span>
                                    <select name="role_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent" required>
                                        <option value="" class="text-slate-600 dark:text-navy-200">-- Choose Role --</option>
                                        <option value="0" class="text-slate-600 dark:text-navy-200">Super Admin</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </label>
                                
                            </div>

                            {{-- BARIS 3 --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="block space-y-1.5">
                                    <span>Password <span style="color:red">*</span></span>
                                    <x-input name="password" id="password" placeholder="Your Password" type="password" autocomplete="off" required/>
                                </label>
                            </div>

                            {{-- BARIS 4 --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="block space-y-1.5">
                                    <span>Re-type Password <span style="color:red">*</span></span>
                                    <x-input name="repassword" id="repassword" placeholder="Re-type Password" type="password" autocomplete="off" required/>
                                    <p class="mt-1 text-xs text-red-500 hidden" id="retype-error">Passwords do not match.</p>
                                </label>
                            </div>

                        </div>
                    </div>
                </div>

                @include('components.forms.save')

            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('user-create-form').addEventListener('submit', function(e) {
            var password = document.getElementById('password').value;
            var retype = document.getElementById('repassword').value;
            var errorEl = document.getElementById('retype-error');
            if(password !== retype) {
                e.preventDefault();
                errorEl.classList.remove('hidden');
                errorEl.textContent = "Passwords do not match.";
                document.getElementById('repassword').focus();
            } else {
                errorEl.classList.add('hidden');
            }
        });
    </script>
    @endpush

</x-app-layout>