<!-- Table With Filter -->
<style>
    /* === Table Header === */
    .customers-table thead th {
        font-size: 0.8125rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.06em !important;
        color: #94a3b8 !important;
        padding: 0.75rem 1rem !important;
        height: 2.5rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    }

    /* === Table Body === */
    .customers-table tbody td {
        font-size: 0.875rem !important;
        color: #cbd5e1 !important;
        padding: 0.5rem 1rem !important;
        border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    }

    /* === Dark Mode: brighter, more readable text === */
    .dark .customers-table thead th {
        color: #cbd5e1 !important;
    }
    .dark .customers-table tbody td {
        color: #e2e8f0 !important;
    }

    /* === Light Mode: darker text & row separators === */
    html:not(.dark) .customers-table thead th {
        color: #475569 !important;
    }
    html:not(.dark) .customers-table tbody td {
        color: #1e293b !important;
    }
    html:not(.dark) .customers-table thead th,
    html:not(.dark) .customers-table tbody td,
    html:not(.dark) .customers-table tbody tr:last-child td {
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .customers-table tbody tr:hover td {
        background-color: rgba(255,255,255,0.02) !important;
    }

    /* === Pagination (matches template module style) === */
    .dataTables_paginate .paginate_button {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 2rem !important;
        height: 2rem !important;
        padding: 0 !important;
        margin: 0 !important;
        border-radius: 0.5rem !important;
        font-size: 0.75rem !important;
        font-weight: 400 !important;
        border: 1px solid #e2e8f0 !important;
        color: #475569 !important;
        background: #fff !important;
        transition: all 0.15s ease;
    }
    .dark .dataTables_paginate .paginate_button {
        border-color: #2a3040 !important;
        background: #1a2130 !important;
        color: #cbd5e1 !important;
    }
    .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
        background-color: #f8fafc !important;
        color: #334155 !important;
    }
    .dark .dataTables_paginate .paginate_button:hover:not(.current):not(.disabled) {
        background-color: #232a3a !important;
        color: #e2e8f0 !important;
    }
    .dataTables_paginate .paginate_button.current {
        background-color: #6366f1 !important;
        color: #fff !important;
        border-color: #6366f1 !important;
    }
    .dataTables_paginate .paginate_button.disabled {
        opacity: 0.35 !important;
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
        <div class="flex mb-2 items-center justify-between py-1 lg:py-2">
        </div>

        @include('components.forms.notification')

        {{-- DataTables Controls (outside card) --}}
        <div id="dt-controls" class="flex items-center justify-between gap-3 mb-4 px-1">
            <div id="dt-length-area"></div>
            <div class="flex items-center gap-3">
                <div id="dt-search-area"></div>
                @if($canAdd)
                <a href="{{ route('admin.' . $modul . '.create') }}"
                    class="btn h-9 px-3 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span class="text-xs">&nbsp; Add New</span>
                </a>
                @endif
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="card overflow-hidden" style="padding: 0 !important;">
            <table id="datatables" class="w-full text-left customers-table" style="margin: 0;">                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Customer</th>
                        <th>Category</th>
                        <th style="width:60px;">Image</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Published Date</th>
                        <th style="width:120px;">Action</th>
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

@include('components.modal.confirm-delete')

@push('scripts')
    <script>
        window.table = null;
        window.deleteId = null;

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
                        targets: [0, 7],
                        className: 'text-center'
                    },
                ],
                ajax: {
                    url: "{{ route('admin.' . $modul . '.getData') }}",
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
                    { data: 'category_view', name: 'category_name' },
                    { data: 'image_view', orderable: false, searchable: false },
                    { data: 'title', name: 'title' },
                    { data: 'author', name: 'author', defaultContent: '-' },
                    { data: 'published_date_view', name: 'published_date' },
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

            // Confirm delete handler
            $('#confirmDelete').on('click', function () {
                if (!deleteId) return;

                $('#deleteModal').addClass('hidden');

                $.ajax({
                    url: '{{ route("admin." . $modul . ".destroy", ":id") }}'.replace(':id', deleteId),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        deleteId = null;
                        var msg = response.message;
                        showNotification('success', msg);
                        if (table) {
                            table.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    },
                    error: function (xhr) {
                        let msg = 'Delete failed!';
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
