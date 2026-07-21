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
            <div class="is-scrollbar-hidden min-w-full overflow-x-auto rounded-lg border border-slate-200 dark:border-navy-500"
                style="padding:2em;">
                <table id="datatables" class="is-zebra w-full">
                    <thead>
                        <tr>
                            <th class="rounded-tl-lg table-column" style="text-align:center; width:50px;">No</th>
                            <th class="table-column text-center" style="text-align:center;">Name</th>
                            <th class="table-column text-center" style="text-align:center;">Source</th>
                            <th class="table-column text-center" style="text-align:center;">Status</th>
                            <th class="table-column text-center" style="text-align:center;">City</th>
                            <th class="rounded-tr-lg table-column" style="text-align:center; width:150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data loaded by DataTables AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('components.modal.confirm-delete')

@push('scripts')
    <script>

        window.table = null; // Global assignment
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
                        targets: [0, 5],
                        className: 'text-center'
                    },
                ],
                ajax: {
                    url: "{{ route('admin.' . $modul . '.getData') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_province = $('.filter-province').val();
                        d.filter_city = $('.filter-city').val();
                        d.filter_source = $('.filter-source').val();
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
                    { data: 'mobile_view', name: 'name' },
                    { data: 'source', name: 'source', className: 'dt-hide-mobile', defaultContent: '-' },
                    { data: 'status', name: 'status', className: 'dt-hide-mobile', defaultContent: '-' },
                    { data: 'city', className: 'dt-hide-mobile' },
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

                // AJAX to delete
                $.ajax({
                    url: '{{ route('admin.' . $modul . ".destroy", ":id") }}'.replace(':id', deleteId),
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