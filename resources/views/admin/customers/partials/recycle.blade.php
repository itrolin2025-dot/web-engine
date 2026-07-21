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
</div>

@include('components.modal.confirm-restore') 

@push('scripts')
    <script>
        window.table = null;

        $(document).ready(function () {
            
            // Trigger filter on enter or change
            $('.filter-name').on('keyup change', function () {
                if (window.table) window.table.ajax.reload();
            });

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
                dom: 'lrtip',
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
                    url: "{{ route($modul . '.getDataRecycle') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_province   = $('.filter-province').val();
                        d.filter_city       = $('.filter-city').val();
                        d.filter_source     = $('.filter-source').val();
                        d.filter_status     = $('.filter-status').val();
                    }
                },
                columns: [
                    {
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: 'dt-hide-mobile text-center'
                    },
                    { data: 'name', name: 'name' }, // Recycle usually simpler view
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
                        
                        var msg = response.message;
                        showNotification('error', msg);
                        if (table) {
                            table.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }

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
    </script>
@endpush
