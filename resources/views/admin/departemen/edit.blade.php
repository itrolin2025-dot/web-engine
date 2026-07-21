<x-app-layout>
    @include('components.forms.tittle')

    <form action="{{ route($modul . '.update', $departemen) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-12 gap-6">
            {{-- Name Input (Row 1) --}}
            <div class="col-span-12">
                <div class="card flex flex-col space-y-6 h-full p-10">
                    <label class="block space-y-1.5">
                        <span>Name</span>                        
                        <x-input name="name" value="{{ $departemen->name }}" placeholder="Enter Name" autocomplete="off" required/>
                    </label>
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