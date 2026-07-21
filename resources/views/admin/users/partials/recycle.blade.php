<div id="table-filter">
    <div class="ac">
        <div class="flex items-center justify-between" style="margin-top:-3em;">

            @include('components.forms.tittle')
            
            @include('components.datatables.header-recycle')
            
        </div>

        @include('components.datatables.header-filter')

        @include('components.forms.notification')
        
        <div class="card">
            <div class="is-scrollbar-hidden min-w-full overflow-x-auto rounded-lg border border-slate-200 dark:border-navy-500" style="padding:20px; padding-right:30px;">
                <table id="datatables" class="is-zebra w-full">
                    <thead>
                        <tr>
                            <th class="rounded-tl-lg table-column" style="text-align:center;">No</th>
                            <th class="table-column text-center" style="text-align:center;">Role</th>
                            <th class="table-column" style="text-align:center;">Name</th>
                            <th class="table-column" style="text-align:center;">Username</th>
                            <th class="rounded-tr-lg table-column" style="text-align:center;">Action</th>
                            <!-- <th class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5" style="text-align:center;">
                                Action
                            </th> -->
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
    $(document).ready(function () {

        const table = $('#datatables').DataTable({
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
                { targets: [0, 4], className: 'text-center' },
                { targets: 0, width: '100px' },
                { targets: 4, width: '120px' },
            ],
            ajax: {
                url: "{{ route($modul . '.getDataRecycle') }}",
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
                { data: 'role_name', className: 'dt-hide-mobile' },
                { data: 'mobile_view', name: 'name' },
                { data: 'email', className: 'dt-hide-mobile' },
                {
                    render: function (data, type, row) {
                        return row.action;
                    },
                    className: 'dt-hide-mobile text-center'
                }
            ]
        });
        

        // Make restoreId available for confirmRestore click, keeping consistent with custom.js logic
        let restoreId = null;

        $('#datatables').on('click', '.restore', function () {
            restoreId = $(this).data('ix');
            const name = $(this).data('name') || '';
            $('#restoreName').text(name);
            $('#restoreId').text(id);
            $('#restoreModal').removeClass('hidden');
        });

        // Close modal (button)
        $(document).on('click', '[data-close-restore-modal]', function (e) {
            e.preventDefault();
            closeRestoreModal();
        });

        // Close modal (overlay)
        $(document).on('click', '#restoreModal .modal-overlay', function (e) {
            if ($(e.target).hasClass('modal-overlay')) {
                closeRestoreModal();
            }
        });

        function closeRestoreModal() {
            $('#restoreModal').addClass('hidden');
            restoreId = null;
        }
        $('#confirmRestore').on('click', function () {
            console.log(restoreId);
            if (!restoreId) return;

            $.ajax({
                url: "{{ url($modul . '/restore') }}/" + restoreId,
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function () {
                    closeRestoreModal();
                    table.ajax.reload(null, false);
                },
                error: function () {
                    alert('Restore failed!');
                }
            });
        });

    });
</script>
@endpush

