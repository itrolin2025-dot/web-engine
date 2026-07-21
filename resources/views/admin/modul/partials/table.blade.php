<!-- Table With Filter -->
<div id="table-filter">
    <div class="ac">
        
        <div class="flex items-center justify-between" style="margin-top:-3em;">
            
            @include('components.forms.tittle')
            
            @include('components.datatables.header')

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
                                <th class="table-column text-center" style="text-align:center;">Parent</th>
                                <th class="table-column text-center" style="text-align:center;">Name</th>
                                <th class="table-column text-center" style="text-align:center;">Code</th>
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

@include('components.modal.confirm-delete') 

@push('scripts')
    <script>

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
                        targets: [0, 4], // Code & Action
                        className: 'text-center'
                    },
                ],
                ajax: {
                    url: "{{ route($modul . '.getData') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_code   = $('.filter-kode').val();
                        d.filter_name   = $('.filter-name').val();
                        d.filter_parent = $('.filter-parent').val();
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Show custom error for model not found
                        if (
                            xhr.responseJSON 
                            && xhr.responseJSON.message 
                            && xhr.responseJSON.message.includes('No query results for model')
                        ) {
                            // Spesifik tangani kasus user vs modul
                            if (xhr.responseJSON.message.includes('[App\\Models\\User]')) {
                                alert('Data tidak ditemukan (Model yang dicari adalah User, seharusnya Modul): ' + xhr.responseJSON.message);
                            } else if (xhr.responseJSON.message.includes('[App\\Models\\Modul]')) {
                                alert('Data tidak ditemukan atau model Modul tidak tersedia: ' + xhr.responseJSON.message);
                            } else {
                                alert('Data tidak ditemukan atau model tidak tersedia: ' + xhr.responseJSON.message);
                            }
                        } else {
                            alert('Gagal memuat data tabel. ' + (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : ''));
                        }
                        $('#datatables').dataTable().fnClearTable();
                    }
                },
                columns: [
                    {
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        className: 'dt-hide-mobile'
                    },
                    { data: 'parent_name', className: 'dt-hide-mobile' },
                    { data: 'mobile_view', name: 'name' },
                    { data: 'kode', className: 'dt-hide-mobile' },
                    {
                        render: function (data, type, row) {
                            return row.action;
                        },
                        className: 'dt-hide-mobile'
                    }
                ]
            });

            // Confirm delete handler
            $('#confirmDelete').on('click', function () {
                if (!deleteId) return;

                $('#deleteModal').addClass('hidden');

                // AJAX to delete
                $.ajax({
                    url: '{{ url($modul) }}/' + deleteId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        deleteId = null;
                        // Success notification (gunakan json message dari backend jika ada)
                        var msg = response.message;
                        showNotification('success', msg);
                        if (table) {
                            table.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    },
                    error: function (xhr) {
                        var msg = 'Delete failed!';
                        if(xhr && xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        showNotification('error', msg);
                    }
                });
            });

        });
    </script>
@endpush
