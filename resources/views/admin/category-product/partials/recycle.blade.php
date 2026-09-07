<!-- Recycle Table With Filter -->
<style>
    /* === Table Header === */
    .customers-table thead th {
        font-size: 0.8125rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.06em !important;
        color: #94a3b8 !important;
        padding: 0.875rem 1rem !important;
        border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    }

    /* === Table Body === */
    .customers-table tbody td {
        font-size: 0.875rem !important;
        color: #cbd5e1 !important;
        padding: 0.875rem 1rem !important;
        border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    }

    .customers-table tbody tr:last-child td {
        border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    }

    .customers-table tbody tr:hover td {
        background-color: rgba(255,255,255,0.02) !important;
    }

    /* === Pagination === */
    .dataTables_paginate .paginate_button {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 2rem !important;
        height: 2rem !important;
        padding: 0 !important;
        margin: 0 2px !important;
        border-radius: 9999px !important;
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        border: none !important;
        color: #94a3b8 !important;
        background: transparent !important;
        transition: all 0.15s ease;
    }
    .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
        background-color: rgba(255,255,255,0.06) !important;
        color: #e2e8f0 !important;
    }
    .dataTables_paginate .paginate_button.current {
        background-color: #6366f1 !important;
        color: #fff !important;
        border: none !important;
    }
    .dataTables_paginate .paginate_button.disabled {
        opacity: 0.25 !important;
        pointer-events: none !important;
    }

    /* === Length Select === */
    .dataTables_length select {
        background-color: rgba(255,255,255,0.05) !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        border-radius: 0.5rem !important;
        color: #94a3b8 !important;
        padding: 0.375rem 2rem 0.375rem 0.75rem !important;
        font-size: 0.8125rem !important;
        outline: none;
    }
    .dataTables_length select:focus {
        border-color: rgba(99,102,241,0.5) !important;
    }

    /* === Info Text === */
    .dataTables_info {
        color: #64748b !important;
        font-size: 0.8125rem !important;
    }
</style>

<div id="table-filter">
    <div class="ac">
        <div class="flex mb-4 items-center justify-between py-5 lg:py-6">
            @include('components.forms.tittle')
            @include('components.datatables.header')
        </div>

        @include('components.datatables.header-filter')
        @include('components.forms.notification')

        {{-- DataTables Controls (outside card) --}}
        <div id="dt-controls" class="flex items-center justify-between gap-3 mb-4 px-1">
            <div id="dt-length-area"></div>
            <div id="dt-search-area"></div>
        </div>

        {{-- Data Table Card --}}
        <div class="card overflow-hidden" style="padding: 0 !important;">
            <table id="datatables" class="w-full text-left customers-table" style="margin: 0;">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Customer</th>
                        <th>Image</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th style="width:150px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded by DataTables AJAX -->
                </tbody>
            </table>

            {{-- Card footer: info left, pagination right --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3" style="border-top: 1px solid rgba(255,255,255,0.08); padding: 0.75rem 1.25rem;">
                <div id="dt-info-area"></div>
                <div id="dt-pagination-area"></div>
            </div>
        </div>
    </div>
</div>

@include('components.modal.confirm-restore')

@push('scripts')
    <script>
        window.table = null;
        window.restoreId = null;

        $(document).ready(function () {
            window.table = $('#datatables').DataTable({
                pagingType: 'simple_numbers',
                language: {
                    paginate: {
                        previous: '<i class="fa-solid fa-chevron-left text-xs"></i>',
                        next: '<i class="fa-solid fa-chevron-right text-xs"></i>'
                    },
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries'
                },
                processing: true,
                serverSide: true,
                dom: 'lfrtip',
                lengthMenu: [25, 50, 100, 1000],
                pageLength: 50,
                order: [[0, 'desc']],
                columnDefs: [
                    {
                        targets: [0, 6],
                        className: 'text-center'
                    },
                ],
                ajax: {
                    url: "{{ route('admin.' . $modul . '.getDataRecycle') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_name = $('.filter-name').val();
                    }
                },
                columns: [
                    {
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: 'text-center'
                    },
                    { data: 'customer_view', name: 'customer_name' },
                    { data: 'image_view', orderable: false, searchable: false },
                    { data: 'code', name: 'code' },
                    { data: 'mobile_view', name: 'name' },
                    { data: 'description', name: 'description', defaultContent: '-' },
                    {
                        render: function (data, type, row) {
                            return row.action;
                        },
                        className: 'text-center'
                    }
                ]
            });

            // Move controls outside card
            var dtWrapper = window.table.table().container();

            // Hide search box
            $(dtWrapper).find('.dataTables_filter').hide();

            // Move length dropdown to top left
            $(dtWrapper).find('.dataTables_length').detach().appendTo('#dt-length-area');

            // Move info text to bottom left (inside card footer)
            $(dtWrapper).find('.dataTables_info').detach().appendTo('#dt-info-area');

            // Move pagination to bottom right (inside card footer)
            $(dtWrapper).find('.dataTables_paginate').detach().appendTo('#dt-pagination-area');

            // Confirm restore handler
            $('#confirmRestore').on('click', function () {
                if (!restoreId) return;

                $('#restoreModal').addClass('hidden');

                $.ajax({
                    url: '{{ route("admin." . $modul . ".restore", ":id") }}'.replace(':id', restoreId),
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        restoreId = null;
                        var msg = response.message;
                        showNotification('success', msg);
                        if (table) {
                            table.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    },
                    error: function (xhr) {
                        let msg = 'Restore failed!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        alert(msg);
                    }
                });
            });
        });
    </script>
@endpush
