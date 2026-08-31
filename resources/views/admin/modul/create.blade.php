<x-app-layout>
    
    @include('components.forms.tittle')

    <form action="{{ route('admin.' . $modul . '.store') }}" method="POST">
        @csrf

        <!-- Agar kolom kiri dan kanan sama tinggi, gunakan flex pada parent dan h-full pada child -->
        <div class="grid grid-cols-12 gap-6">

            {{-- KOLOM KIRI --}}
            <div class="col-span-12 lg:col-span-8">
                <div class="card p-4 sm:p-5 h-full flex flex-col space-y-6">

                    {{-- BARIS 1 --}}
                    <label class="block space-y-3">
                        <span>Parent</span>
                        <select name="parent_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                            <option value="">-- No Parent --</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}">
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    {{-- BARIS 2 --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="block space-y-1.5">
                            <span>Name</span>
                            <x-input name="name" placeholder="Enter Modul Name" autocomplete="off" required/>
                        </label>

                        <label class="block space-y-1.5">
                            <span>Code</span>
                            <x-input name="kode" placeholder="Enter Modul Code" autocomplete="off" required/>
                        </label>
                    </div>

                    {{-- BARIS 3 --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="block space-y-3">
                            <span>Icon</span>
                            <select id="icon" name="icon" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Pilih Icon --</option>
                                @foreach ($icons as $icon)
                                    <option value="{{ $icon->code }}">
                                        {{ $icon->name }}
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block space-y-2.0">
                            <span></span><br><br>
                            <i id="iconPreview" class="text-xl"></i>
                            <span id="iconName"></span>
                        </label>
                            
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="block space-y-3">
                            <span>Shortcut</span>
                            <select name="shortcut" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="none">None</option>
                                <option value="left">Left Menu</option>
                                <option value="right">Right Menu</option>
                                <option value="dashboard">Dashboard</option>
                            </select>
                        </label>
                    </div>

                </div>
            </div>


            {{-- KOLOM KANAN --}}
            <div class="col-span-12 lg:col-span-3">
                <div class="card p-4 sm:p-5 h-full flex flex-col">
                    <span class="font-medium">Access</span>

                    <div class="mt-3 space-y-3">
                        @foreach ($permissions as $akses)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="akses[]" value="{{ $akses->name }}"
                                    class="form-checkbox is-basic size-5 rounded-sm bg-slate-100 border-slate-400/70 checked:bg-slate-500 checked:border-slate-500 hover:border-slate-500 focus:border-slate-500 dark:bg-navy-900 dark:border-navy-500 dark:checked:bg-navy-400 dark:checked:border-navy-400" type="checkbox">
                                    {{ $akses->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-center gap-2 pt-6">
            <a href="{{ route('admin.modul.index') }}" class="btn border">Cancel</a>
            <button class="btn bg-primary text-white">Save</button>
        </div>

    </form>

    <style>
        .card {
            padding: 2rem !important;
        }

        /* Membuat kolom grid-child sama tinggi di grid custom ini */
        @media (min-width: 1024px) {
            .grid.grid-cols-12 {
                align-items: stretch !important;
            }
            .card {
                height: 100%;
            }
        }
    </style>
    <script>
        document.getElementById('icon').addEventListener('change', function () {
            const icon = this.value;
            document.getElementById('iconPreview').className = icon + ' text-xl';
            document.getElementById('iconName').textContent = icon;
        });
    </script>

</x-app-layout>