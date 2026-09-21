<x-app-layout>
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

        /* === Length Select ("Show _ items" like template module) === */
        .dataTables_length {
            font-size: 0.75rem !important;
            color: #64748b !important;
        }
        .dark .dataTables_length {
            color: #94a3b8 !important;
        }
        .dataTables_length label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0 !important;
        }
        .dataTables_length select {
            background-color: #fff !important;
            border: 1px solid #cbd5e1 !important;
            border-radius: 0.5rem !important;
            color: #334155 !important;
            padding: 0.375rem 2rem 0.375rem 0.75rem !important;
            font-size: 0.75rem !important;
            outline: none;
        }
        .dark .dataTables_length select {
            background-color: #1a2130 !important;
            border-color: #2a3040 !important;
            color: #cbd5e1 !important;
        }
        .dataTables_length select:focus {
            border-color: rgba(99,102,241,0.5) !important;
        }

        /* === Info Text === */
        .dataTables_info {
            color: #64748b !important;
            font-size: 0.75rem !important;
        }
        .dark .dataTables_info {
            color: #94a3b8 !important;
        }

        /* === Search Box (styled like template module) === */
        .dataTables_filter {
            position: relative;
        }
        .dataTables_filter label input {
            width: 100% !important;
            margin-left: 0 !important;
        }
    </style>
    <div>
        <div class="flex mb-4 items-center justify-between py-5 lg:py-6">
            <div class="flex items-center space-x-4">
                <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">{{ $modul_name }}</h2>
                <div class="hidden h-full py-1 sm:flex">
                    <div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>
                </div>
                <ul class="hidden flex-wrap items-center space-x-2 sm:flex">
                    <li class="flex items-center space-x-2">
                        <a class="text-primary transition-colors hover:text-primary-focus dark:text-accent-light dark:hover:text-accent"
                            href="{{ route('admin.dashboard') }}">Dashboard</a>
                        <i class="fa-solid fa-angle-right text-xs"></i>
                    </li>
                    <li>{{ $modul_type }}</li>
                </ul>
            </div>

            @if($canAdd)
            <a href="{{ route('admin.customers-website.create') }}"
                class="btn space-x-2 bg-primary font-medium text-white hover:bg-primary-focus focus:bg-primary-focus active:bg-primary-focus/90 dark:bg-accent dark:hover:bg-accent-focus dark:focus:bg-accent-focus dark:active:bg-accent/90">
                <i class="fa-solid fa-plus text-base"></i>
                <span>Add</span>
            </a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert flex items-center justify-between space-x-2 rounded-lg border border-success bg-success/10 p-4 text-success dark:border-success dark:bg-success/5 mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert flex items-center justify-between space-x-2 rounded-lg border border-warning bg-warning/10 p-4 text-warning dark:border-warning dark:bg-warning/5 mb-4">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    <p class="font-medium">{{ session('warning') }}</p>
                </div>
            </div>
        @endif

        {{-- DataTables Controls (outside card): Show length left, Search right --}}
        <div id="dt-controls" class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between mb-6 px-1">
            <div id="dt-length-area"></div>
            <div class="flex w-full items-center justify-end gap-3 sm:w-auto sm:ml-auto">
                <div id="dt-search-area" class="w-full max-w-xs sm:w-64"></div>
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="card overflow-hidden" style="padding: 0 !important;">
            <table id="datatables" class="w-full text-left customers-table" style="margin: 0;">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Title</th>
                        <th>Customer</th>
                        <th>Customer Type</th>
                        <th>Template</th>
                        <th style="text-align:center;">QR</th>
                        <th>Domain</th>
                        <th style="text-align:center;">Status</th>
                        <th style="width:150px; text-align:right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded by DataTables AJAX -->
                </tbody>
            </table>
        </div>

        {{-- Info + Pagination outside card (like template module) --}}
        <div class="mt-5 flex flex-col items-center gap-3 px-1 sm:flex-row sm:justify-between">
            <div id="dt-info-area"></div>
            <div id="dt-pagination-area"></div>
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
                        previous: '<i class="fa-solid fa-angle-left"></i>',
                        next: '<i class="fa-solid fa-angle-right"></i>'
                    },
                    lengthMenu: 'Show _MENU_ items',
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
                        targets: [0, 5, 7],
                        className: 'text-center'
                    },
                ],
                ajax: {
                    url: "{{ route('admin.customers-website.getData') }}",
                    type: 'GET'
                },
                columns: [
                    {
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: 'dt-hide-mobile text-center'
                    },
                    { data: 'title_view', name: 'title' },
                    { data: 'customer_view', name: 'customer_name', className: 'dt-hide-mobile', defaultContent: '-' },
                    { data: 'customer_type_view', className: 'dt-hide-mobile', defaultContent: '-' },
                    { data: 'template_view', name: 'template_name', className: 'dt-hide-mobile', defaultContent: '-' },
                    { data: 'qr_view', orderable: false, searchable: false, className: 'text-center' },
                    { data: 'domain_view', name: 'domain', className: 'dt-hide-mobile', defaultContent: '-' },
                    { data: 'status_view', name: 'is_active', className: 'dt-hide-mobile text-center' },
                    {
                        render: function (data, type, row) {
                            return row.action;
                        },
                        className: 'dt-hide-mobile text-center'
                    }
                ]
            });

            // Move controls outside card
            var dtWrapper = window.table.table().container();

            // Style & move search box to top right (like template module)
            var $search = $(dtWrapper).find('.dataTables_filter');
            $search.find('label').contents().filter(function () {
                return this.nodeType === 3;
            }).remove();
            $search.find('input')
                .attr('placeholder', 'Search website...')
                .addClass('form-input w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700');
            $search.addClass('relative w-full').prepend('<i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>');
            $search.detach().appendTo('#dt-search-area');

            // Move length dropdown to top left
            $(dtWrapper).find('.dataTables_length').detach().appendTo('#dt-length-area');

            // Move info text to bottom left (outside card)
            $(dtWrapper).find('.dataTables_info').detach().appendTo('#dt-info-area');

            // Move pagination to bottom right (outside card)
            $(dtWrapper).find('.dataTables_paginate').detach().appendTo('#dt-pagination-area');
        });
    </script>
@endpush
</x-app-layout>
