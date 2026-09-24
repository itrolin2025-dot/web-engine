<!-- Transactions Table With Filter -->
<style>
    .transactions-table thead th {
        font-size: 0.8125rem !important;
        font-weight: 700 !important;
        letter-spacing: 0.06em !important;
        color: #94a3b8 !important;
        padding: 0.75rem 1rem !important;
        vertical-align: middle !important;
        border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    }

    .transactions-table tbody td {
        font-size: 0.875rem !important;
        color: #cbd5e1 !important;
        padding: 0.75rem 1rem !important;
        border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    }

    html:not(.dark) .transactions-table thead th {
        color: #475569 !important;
    }
    html:not(.dark) .transactions-table tbody td {
        color: #1e293b !important;
    }
    html:not(.dark) .transactions-table thead th,
    html:not(.dark) .transactions-table tbody td,
    html:not(.dark) .transactions-table tbody tr:last-child td {
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .transactions-table tbody tr:hover td {
        background-color: rgba(255,255,255,0.02) !important;
    }

    .dataTables_paginate .paginate_button {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        min-width: 2rem !important;
        height: 2rem !important;
        padding: 0 !important;
        margin: 0 2px !important;
        border-radius: 0.5rem !important;
        font-size: 0.75rem !important;
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
    .dataTables_paginate .paginate_button.current {
        background-color: #6366f1 !important;
        color: #fff !important;
        border-color: #6366f1 !important;
    }
    .dataTables_paginate .paginate_button.disabled {
        opacity: 0.35 !important;
        pointer-events: none !important;
    }

    .dataTables_info {
        color: #64748b !important;
        font-size: 0.8125rem !important;
    }
    .dataTables_length {
        font-size: 0.8125rem !important;
        color: #94a3b8 !important;
    }
</style>

<div id="table-filter">
    <div class="ac">

        @include('components.forms.notification')

        {{-- Filters: Website & Status --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-6 px-1">
            <select class="form-select w-full sm:w-64 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 filter-website">
                <option value="">-- Semua Website --</option>
                @foreach(\App\Models\CustomersWebsite::orderBy('title')->get() as $w)
                    <option value="{{ $w->id }}">{{ $w->title }}</option>
                @endforeach
            </select>
            <select class="form-select w-full sm:w-48 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700 filter-status">
                <option value="">-- Semua Status --</option>
                @foreach(['Pending' => 'Pending', 'Paid' => 'Validasi Pembayaran', 'Shipped' => 'Proses Pengiriman', 'ShippedOut' => 'Dalam Pengiriman', 'Completed' => 'Barang Diterima', 'Cancelled' => 'Transaksi Dibatalkan'] as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- DataTables Controls (outside card) --}}
        <div id="dt-controls" class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between mb-6 px-1">
            <div id="dt-length-area"></div>
            <div class="flex w-full items-center justify-end gap-3 sm:w-auto sm:ml-auto">
                <div id="dt-search-area" class="w-full max-w-xs sm:w-64"></div>
            </div>
        </div>

        {{-- Data Table Card --}}
        <div class="card overflow-hidden" style="padding: 0 !important;">
            <table id="datatables" class="w-full text-left transactions-table" style="margin: 0;">
                <thead>
                    <tr>
                        <th style="width:50px; text-align:center;">No</th>
                        <th>Code</th>
                        <th>Website</th>
                        <th>Customer</th>
                        <th>Pengiriman</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th style="width:150px; text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data loaded by DataTables AJAX -->
                </tbody>
            </table>
        </div>

        {{-- Info + Pagination outside card --}}
        <div class="mt-5 flex flex-col items-center gap-3 px-1 sm:flex-row sm:justify-between">
            <div id="dt-info-area"></div>
            <div id="dt-pagination-area"></div>
        </div>
    </div>
</div>

@include('components.modal.confirm-delete')

{{-- Status Change Modal --}}
<div id="statusModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/70 modal-overlay-status"></div>
    <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm modal-overlay-status"></div>

    <div class="relative flex max-w-sm w-full mx-2 flex-col overflow-hidden rounded-lg bg-white dark:bg-navy-700">
        <div class="border-b border-slate-200 p-4 sm:px-5 dark:border-navy-500">
            <div class="flex items-center space-x-2">
                <div class="flex size-7 items-center justify-center rounded-lg bg-primary/10 p-1 text-primary dark:bg-accent-light/10 dark:text-accent-light">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-700 dark:text-navy-100">Ubah Status Transaksi</h3>
            </div>
            <p class="mt-1 text-xs text-slate-400">Kode: <span id="statusModalCode" class="font-semibold text-slate-500 dark:text-navy-200"></span></p>
        </div>
        <div id="statusOptions" class="space-y-1.5 p-4 sm:px-5">
            @foreach(['Pending' => 'Pending', 'Paid' => 'Validasi Pembayaran', 'Shipped' => 'Proses Pengiriman', 'ShippedOut' => 'Dalam Pengiriman', 'Completed' => 'Barang Diterima', 'Cancelled' => 'Transaksi Dibatalkan'] as $value => $label)
                <button type="button" data-value="{{ $value }}"
                    class="status-option flex w-full items-center justify-between rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-600 transition-colors hover:border-primary hover:bg-primary/5 dark:border-navy-500 dark:text-navy-100 dark:hover:border-accent dark:hover:bg-accent/5">
                    <span class="font-medium">{{ $label }}</span>
                    <i class="fa-solid fa-circle-check status-check hidden text-success"></i>
                </button>
            @endforeach
        </div>
    </div>
</div>

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
                        targets: [0, 7],
                        className: 'text-center'
                    },
                ],
                ajax: {
                    url: "{{ route('admin.' . $modul . '.getData') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_website = $('.filter-website').val();
                        d.filter_status = $('.filter-status').val();
                    }
                },
                columns: [
                    {
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: 'dt-hide-mobile text-center'
                    },
                    { data: 'mobile_view', name: 'code' },
                    { data: 'website_title', name: 'website_title', className: 'dt-hide-mobile', defaultContent: '-' },
                    { data: 'customer_name', name: 'customer_name', className: 'dt-hide-mobile' },
                    {
                        render: function (data, type, row) {
                            if (type !== 'display') return row.shipping_courier || '';
                            var ship = row.shipping_courier || '-';
                            if (row.shipping_tracking_number) {
                                ship += ' <span class="text-xs text-slate-400">(' + row.shipping_tracking_number + ')</span>';
                            }
                            var shipBadge = row.shipping_status
                                ? ' <span class="badge bg-slate-100 text-slate-500 dark:bg-navy-600 dark:text-navy-100 px-2 py-0.5 text-[10px]">' + row.shipping_status + '</span>'
                                : '';
                            return ship + shipBadge;
                        },
                        className: 'dt-hide-mobile'
                    },
                    { data: 'total_view', name: 'total', orderable: false, searchable: false, className: 'dt-hide-mobile' },
                    { data: 'status_view', name: 'status', className: 'dt-hide-mobile' },
                    {
                        render: function (data, type, row) {
                            return row.action;
                        },
                        className: 'dt-hide-mobile text-center'
                    }
                ]
            });

            var dtWrapper = window.table.table().container();

            // Style & move search box to top right
            var $search = $(dtWrapper).find('.dataTables_filter');
            $search.find('label').contents().filter(function () {
                return this.nodeType === 3;
            }).remove();
            $search.find('input')
                .attr('placeholder', 'Search transaksi...')
                .addClass('form-input w-full rounded-lg border border-slate-300 bg-white py-2 pl-9 pr-3 text-xs hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:bg-navy-700');
            $search.addClass('relative w-full').prepend('<i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>');
            $search.detach().appendTo('#dt-search-area');

            // Move length dropdown to top left
            $(dtWrapper).find('.dataTables_length').detach().appendTo('#dt-length-area');

            // Move info text to bottom left
            $(dtWrapper).find('.dataTables_info').detach().appendTo('#dt-info-area');

            // Move pagination to bottom right
            $(dtWrapper).find('.dataTables_paginate').detach().appendTo('#dt-pagination-area');

            // Reload table when filters change
            $('.filter-website, .filter-status').on('change', function () {
                window.table.ajax.reload(null, false);
            });

            // =================== Status Modal (klik badge status) ===================
            var statusTransactionId = null;

            // Buka popup saat badge status diklik (delegated: works after redraw)
            $('#datatables').on('click', '.js-status-toggle', function () {
                statusTransactionId = $(this).data('id');
                var status = $(this).data('status');
                $('#statusModalCode').text($(this).data('code') || '-');

                // Tandai status saat ini
                $('#statusOptions .status-option').each(function () {
                    var isCurrent = $(this).data('value') === status;
                    $(this).toggleClass('border-primary bg-primary/5 dark:border-accent dark:bg-accent/5', isCurrent);
                    $(this).find('.status-check').toggleClass('hidden', !isCurrent);
                });

                $('#statusModal').removeClass('hidden');
            });

            function closeStatusModal() {
                $('#statusModal').addClass('hidden');
                statusTransactionId = null;
            }

            // Close via overlay
            $('#statusModal .modal-overlay-status').on('click', function (e) {
                if ($(e.target).hasClass('modal-overlay-status')) closeStatusModal();
            });

            // Pilih status baru -> simpan via AJAX
            $('#statusOptions').on('click', '.status-option', function () {
                if (!statusTransactionId) return;
                var newStatus = $(this).data('value');

                $.ajax({
                    url: '{{ route('admin.' . $modul . '.updateStatus', ':id') }}'.replace(':id', statusTransactionId),
                    type: 'PUT',
                    data: { status: newStatus },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        closeStatusModal();
                        showNotification('success', response.message);
                        if (window.table) {
                            window.table.ajax.reload(null, false);
                        }
                    },
                    error: function (xhr) {
                        var msg = (xhr.responseJSON && xhr.responseJSON.message) || 'Gagal mengubah status.';
                        closeStatusModal();
                        showNotification('error', msg);
                    }
                });
            });

            // Confirm delete handler
            $('#confirmDelete').on('click', function () {
                if (!deleteId) return;

                $('#deleteModal').addClass('hidden');

                $.ajax({
                    url: '{{ route('admin.' . $modul . '.destroy', ':id') }}'.replace(':id', deleteId),
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
                        let msg = 'Delete failed !';
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
