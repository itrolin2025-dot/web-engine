<!-- Recycle Table With Filter -->
<div id="table-filter">
    <div class="ac">
        <div class="flex items-center justify-between" style="margin-top:-3em;">
            @include('components.forms.tittle')
            @include('components.datatables.header')
        </div>

        @include('components.datatables.header-filter')
        @include('components.forms.notification')

        <div class="card">
            <div class="is-scrollbar-hidden min-w-full overflow-x-auto rounded-lg border border-slate-200 dark:border-navy-500" style="padding:2em;">
                <table id="datatables" class="is-zebra w-full">
                    <thead>
                        <tr>
                            <th class="rounded-tl-lg table-column" style="text-align:center;">No</th>
                            <th class="table-column text-center" style="text-align:center;">Customer</th>
                            <th class="table-column text-center" style="text-align:center;">Name</th>
                            <th class="table-column text-center" style="text-align:center;">Description</th>
                            <th class="rounded-tr-lg table-column" style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                </table>
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
                        previous: '‹',
                        next: '›'
                    }
                },
                processing: true,
                serverSide: true,
                dom: 'l<"datatable-toolbar">rtip',
                lengthMenu: [25, 50, 100, 1000],
                pageLength: 50,
                order: [[0, 'desc']],
                columnDefs: [
                    {
                        targets: [0, 1, 4],
                        className: 'text-center'
                    },
                    { targets: 0, width: '60px' },
                    { targets: 1, width: '200px' },
                    { targets: 4, width: '150px' },
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
                        className: 'dt-hide-mobile text-center'
                    },
                    { data: 'customer_view', name: 'customer_name', className: 'dt-hide-mobile text-center' },
                    { data: 'mobile_view', name: 'name' },
                    { data: 'description', name: 'description', className: 'dt-hide-mobile', defaultContent: '-' },
                    {
                        render: function (data, type, row) {
                            return row.action;
                        },
                        className: 'dt-hide-mobile text-center'
                    }
                ]
            });

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
