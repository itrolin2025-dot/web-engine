<!-- Table With Filter -->
<div id="table-filter">
    <div class="ac">
        <div class="flex items-center justify-between" style="margin-top:-3em;">

            @include('components.forms.tittle')
            
            @include('components.datatables.header-recycle')

        </div>

        @include('components.datatables.header-filter')

        @include('components.forms.notification')
        
        <div class="flex justify-center items-center w-full">
            <div class="card px-6 py-6 w-full">
                <div class="min-w-full rounded-lg">
                    <table id="datatables" class="is-hoverable w-full text-left" style="border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th class="rounded-tl-lg table-column" style="text-align:center; width:100px;">No</th>
                                <th class="table-column text-center" style="text-align:center;">Name</th>
                                <th class="rounded-tr-lg table-column" style="text-align:center; width:100px;">Action</th>
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
</div>

@include('components.modal.confirm-restore') 

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
                        targets: [0, 2], // Code & Action
                        className: 'text-center'
                    }
                ],
                ajax: {
                    url: "{{ route($modul . '.getDataRecycle') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_name   = $('.filter-name').val();
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
                    {
                        render: function (data, type, row) {
                            return row.action;
                        },
                        className: 'dt-hide-mobile text-center'
                    }
                ]
            });

            $('#confirmRestore').on('click', function () {
                let restoreId = $('#restoreId').val();
                if (!restoreId) return;
                
                $('#restoreModal').addClass('hidden');

                $.ajax({
                    url: "{{ url($modul . '/restore') }}/" + restoreId,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response && response.status) {
                            
                            restoreId = null;
                            
                            var msg = response.message;
                            showNotification('success', msg);
                            if (table) {
                                table.ajax.reload(null, false);
                            } else {
                                location.reload();
                            }

                        } else {
                            alert(response && response.message ? response.message : 'Restore failed!');
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