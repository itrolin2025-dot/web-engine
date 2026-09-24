<!-- Transactions Recycle Table -->
<div id="table-filter">
    <div class="ac">
        <div class="flex mb-4 items-center justify-between py-5 lg:py-6">
            @include('components.forms.tittle')
        </div>

        @include('components.forms.notification')

        {{-- DataTables Controls (outside card) --}}
        <div id="dt-controls" class="flex items-center justify-between gap-3 mb-4 px-1">
            <div id="dt-length-area"></div>
            <div id="dt-search-area"></div>
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
                        <th>Total</th>
                        <th>Status</th>
                        <th style="width:120px; text-align:center;">Action</th>
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
                },
                columns: [
                    {
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: 'text-center'
                    },
                    { data: 'code', name: 'code' },
                    { data: 'website_title', name: 'website_title', defaultContent: '-' },
                    { data: 'customer_name', name: 'customer_name' },
                    {
                        render: function (data, type, row) {
                            return 'Rp ' + number_format((parseFloat(row.total) || 0));
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function (data, type, row) {
                            var labels = { Pending: 'Pending', Paid: 'Validasi Pembayaran', Shipped: 'Proses Pengiriman', ShippedOut: 'Dalam Pengiriman', Completed: 'Barang Diterima', Cancelled: 'Transaksi Dibatalkan' };
                            return labels[row.status] || row.status || '-';
                        }
                    },
                    {
                        render: function (data, type, row) {
                            return row.action;
                        },
                        className: 'text-center'
                    }
                ]
            });

            var dtWrapper = window.table.table().container();

            $(dtWrapper).find('.dataTables_filter').hide();
            $(dtWrapper).find('.dataTables_length').detach().appendTo('#dt-length-area');
            $(dtWrapper).find('.dataTables_info').detach().appendTo('#dt-info-area');
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

        function number_format(num) {
            return Number(num).toLocaleString('id-ID');
        }
    </script>
@endpush
