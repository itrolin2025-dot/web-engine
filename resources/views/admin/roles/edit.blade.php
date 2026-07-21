<x-app-layout>
    @include('components.forms.tittle')

    <form action="{{ route($modul . '.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-12 gap-6">
            {{-- Name Input (Row 1) --}}
            <div class="col-span-12">
                <div class="card flex flex-col space-y-6 h-full p-10">
                    <label class="block space-y-1.5">
                        <span>Name</span>                        
                        <x-input name="name" value="{{ $role->name }}" placeholder="Enter Role Name" autocomplete="off" required/>
                    </label>
                </div>
            </div>

            {{-- Permissions By Modul (Row 2, 4 column per loop) --}}
            <div class="col-span-12">
                <div class="grid grid-cols-1 md:grid-cols-6 lg:grid-cols-6 gap-6">
                    @foreach ($moduls as $mod)
                        @php
                            $modulCheckboxGroup = 'modul-checkbox-group-' . $mod->id;
                        @endphp
                        <div class="card flex flex-col space-y-4 h-full p-6">
                            <div class="flex items-center gap-3 text-sm">
                                @if (count($modul->modulAkses ?? []))
                                    <label class="inline-flex items-center space-x-1">
                                        <input class="select-all-checkbox accent-primary form-checkbox is-basic size-4 rounded-sm border-slate-400/70 checked:bg-success checked:!border-success hover:!border-success focus:!border-success dark:border-navy-400" type="checkbox" data-checkbox-group="{{ $modulCheckboxGroup }}"/>
                                    </label>
                                @endif
                                <span class="font-medium" style="min-width:100px; max-width:100px; display:inline-block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $mod->name }}" data-tooltip="{{ $mod->name }}" >
                                    {{ Str::limit($mod->name, 24, '.....') }}
                                </span>
                                <div class="flex flex-row flex-wrap gap-4 ml-4">
                                    @forelse ($mod->modulAkses ?? [] as $akses)
                                        <label class="inline-flex items-center gap-1 text-xs">
                                            <input 
                                                class="akses-checkbox {{ $modulCheckboxGroup }} form-checkbox is-basic size-3 rounded-sm border-slate-400/70 checked:bg-slate-500 checked:border-slate-500 hover:border-slate-500 focus:border-slate-500 dark:border-navy-400 dark:checked:bg-navy-400" 
                                                type="checkbox" data-checkbox-group="{{ $modulCheckboxGroup }}"
                                                name="akses[]"
                                                value="{{ $akses->id }}"
                                                @checked(in_array($akses->id, $permissions))
                                            />
                                            {{ $akses->akses }}
                                        </label>
                                    @empty
                                        <span class="text-xs text-gray-400"><em>Tidak ada akses</em></span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @include('components.forms.update')

    </form>

    <style>
        .card {
            padding: 2rem !important;
        }
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
    document.addEventListener('DOMContentLoaded', function() {
        // Handler untuk SELECT ALL
        document.querySelectorAll('.select-all-checkbox').forEach(function(selectAll) {
            selectAll.addEventListener('change', function() {
                var group = selectAll.getAttribute('data-checkbox-group');
                var checkboxes = document.querySelectorAll('.' + group);

                if (selectAll.checked) {
                    // Centang semua jika Select All dicentang
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = true;
                    });
                } else {
                    // Uncheck semua jika Select All di-uncheck
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = false;
                    });
                }
            });
        });

        // Handler untuk AKSES CHECKBOX (bukan Select All)
        document.querySelectorAll('.akses-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var group = checkbox.getAttribute('data-checkbox-group');
                var groupCheckboxes = document.querySelectorAll('.akses-checkbox[data-checkbox-group="' + group + '"]');
                var selectAll = document.querySelector('.select-all-checkbox[data-checkbox-group="' + group + '"]');

                // Ketika setiap kali akses checkbox diubah, kita cek apakah semua sudah checked
                // Jika semua tercentang, select all harus ikut tercetang. Jika tidak, select all harus false.
                var checkedCount = Array.from(groupCheckboxes).filter(cb => cb.checked).length;
                if (checkedCount === groupCheckboxes.length && groupCheckboxes.length > 0) {
                    if (selectAll) selectAll.checked = true;
                } else {
                    if (selectAll) selectAll.checked = false;
                }
            });
        });

        // Inisialisasi: pastikan pada saat load, select all tercentang jika semua child-nya centang
        document.querySelectorAll('.select-all-checkbox').forEach(function(selectAll) {
            var group = selectAll.getAttribute('data-checkbox-group');
            var checkboxes = document.querySelectorAll('.akses-checkbox[data-checkbox-group="' + group + '"]');
            var checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            if (checkedCount === checkboxes.length && checkboxes.length > 0) {
                selectAll.checked = true;
            } else {
                selectAll.checked = false;
            }
        });
    });
    </script>
</x-app-layout>