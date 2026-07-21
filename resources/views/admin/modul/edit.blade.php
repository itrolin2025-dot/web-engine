<x-app-layout>
    
    @include('components.forms.tittle')
    
    <form action="{{ route($modul . '.update', $modul_data) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-12 gap-6">

            {{-- KOLOM KIRI --}}
            <div class="col-span-12 lg:col-span-8">
                <div class="card p-4 sm:p-5 h-full flex flex-col space-y-6">
                    
                    <label class="block space-y-3">
                        <span>Parent</span>
                        <select name="parent_id" class="form-select mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                            <option value="">-- No Parent --</option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ $modul_data->parent_id == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="block space-y-1.5">
                            <span>Name</span>
                            <x-input name="name" value="{{ $modul_data->name }}" placeholder="Enter Modul Name" required />
                        </label>

                        <label class="block space-y-1.5">
                            <span>Code</span>
                            <x-input name="kode" value="{{ $modul_data->kode }}" placeholder="Enter Modul Code" required />
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="block space-y-3">
                            <span>Icon</span>
                            <select id="icon" name="icon" class="form-select  mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="">-- Pilih Icon --</option>
                                <!-- <option value="fa-solid fa-house">Home</option> -->
                                @foreach ($icons as $icon)
                                    <option value="{{ $icon->code }}"
                                        {{ $modul_data->icon == $icon->code ? 'selected' : '' }}>
                                        {{ $icon->name }}
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block space-y-2.0 gap-4">
                            <span></span><br><br>
                            <i id="iconPreview" class="text-xl"></i>
                            <span id="iconName"></span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="block space-y-3">
                            <span>Shortcut</span>
                            <select name="shortcut" class="form-select  mt-1.5 w-full rounded-lg border border-slate-300 bg-white px-3 py-2 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 dark:hover:border-navy-400 dark:focus:border-accent">
                                <option value="none" @if($modul_data->shortcut === 'none' || $modul_data->shortcut === null || $modul_data->shortcut === '') selected @endif>None</option>
                                <option value="left" @if($modul_data->shortcut === 'left') selected @endif>Left Menu</option>
                                <option value="right" @if($modul_data->shortcut === 'right') selected @endif>Right Menu</option>
                                <option value="dashboard" @if($modul_data->shortcut === 'dashboard') selected @endif>Dashboard</option>
                            </select>
                        </label>
                    </div>
                </div>
            </div>


            {{-- KOLOM KANAN --}}
            <div class="col-span-12 lg:col-span-3">
                <div class="card p-4 sm:p-5 h-full flex flex-col">
                    <span class="font-medium">Access</span>

                    <div class="mt-3 space-y-2">
                    @foreach ($permissions as $akses)
                        <label class="flex items-center gap-2">
                            <input
                                type="checkbox"
                                name="akses[]"
                                value="{{ $akses->label }}"
                                {{ in_array($akses->label, $selectedAccess) ? 'checked' : '' }}
                                class="form-checkbox is-basic size-5 rounded-sm bg-slate-100 border-slate-400/70
                                    checked:bg-slate-500 checked:border-slate-500 hover:border-slate-500
                                    focus:border-slate-500 dark:bg-navy-900 dark:border-navy-500
                                    dark:checked:bg-navy-400 dark:checked:border-navy-400">
                            {{ $akses->name }}
                        </label>
                    @endforeach

                    </div>

                </div>
            </div>
        </div>

        <div class="flex justify-center gap-2 pt-6">
            <a href="{{ route('modul.index') }}" class="btn border">Cancel</a>
            <button class="btn bg-primary text-white">Update</button>
        </div>

    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // alert($modul->icon);
            var icon = document.getElementById('icon').value;
            document.getElementById('iconPreview').className = icon + ' text-xl';
            document.getElementById('iconName').textContent = icon ;
        });

        document.getElementById('icon').addEventListener('change', function () {
            const icon = this.value;
            document.getElementById('iconPreview').className = icon + ' text-xl';
            document.getElementById('iconName').textContent = icon;
        });

    </script>

</x-app-layout>