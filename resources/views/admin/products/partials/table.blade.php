<!-- Table With Filter -->
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
                            <th class="table-column text-center" style="text-align:center;">Image</th>
                            <th class="table-column text-center" style="text-align:center;">Category</th>
                            <th class="table-column text-center" style="text-align:center;">Code</th>
                            <th class="table-column text-center" style="text-align:center;">Name</th>
                            <th class="table-column text-center" style="text-align:center;">Price</th>
                            <th class="table-column text-center" style="text-align:center;">Description</th>
                            <th class="rounded-tr-lg table-column" style="text-align:center;">Action</th>
                        </tr>
                    </thead>
                </table>
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
                        targets: [0, 1, 2, 3, 5, 7],
                        className: 'text-center'
                    },
                    { targets: 0, width: '50px' },
                    { targets: 1, width: '80px' },
                    { targets: 2, width: '140px' },
                    { targets: 3, width: '120px' },
                    { targets: 5, width: '120px' },
                    { targets: 7, width: '140px' },
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
                        className: 'dt-hide-mobile text-center'
                    },
                    { data: 'image_view', name: 'images', className: 'dt-hide-mobile text-center' },
                    { data: 'category_view', name: 'category_name', className: 'dt-hide-mobile text-center' },
                    { data: 'code', name: 'code', className: 'dt-hide-mobile text-center' },
                    { data: 'mobile_view', name: 'name' },
                    { data: 'price_view', name: 'price', className: 'dt-hide-mobile text-center font-semibold text-slate-700 dark:text-navy-100' },
                    { data: 'description', name: 'description', className: 'dt-hide-mobile', defaultContent: '-' },
                    {
                        render: function (data, type, row) {
                            return row.action;
                        },
                        className: 'dt-hide-mobile text-center'
                    }
                ]
            });

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
