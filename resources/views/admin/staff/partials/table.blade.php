<!-- Table With Filter -->
<div id="table-filter">
    <div class="ac">
        <div class="flex items-center justify-between" style="margin-top:-3em;">

            @include('components.forms.tittle')
            
            @include('components.datatables.header')
            
        </div>

        @include('components.datatables.header-filter')

        @include('components.forms.notification')
        
        <div id="card-view-wrapper" class="mt-6 flex flex-col">
            <!-- Top Controls Placeholder -->
            <div id="top-controls" class="flex justify-start mb-4"></div>

            <!-- Card Grid Container -->
            <div id="card-grid" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <!-- Data loaded via DataTables drawCallback -->
            </div>

            <!-- Bottom Controls Placeholder -->
            <div id="bottom-controls" class="mt-6 flex flex-col items-center">
                <div id="info-placeholder" class="mb-1 opacity-70"></div>
                <div id="pagination-placeholder"></div>
            </div>

            <!-- Invisible DataTable for logic -->
            <div class="hidden">
                <table id="datatables">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Departemen</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
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
                processing: true,
                serverSide: true,
                lengthMenu: [8, 16, 32, 64],
                pageLength: 8,
                order: [[0, 'desc']],
                ajax: {
                    url: "{{ route($modul . '.getData') }}",
                    type: 'GET',
                    data: function (d) {
                        d.filter_name = $('.filter-name').val();
                        d.filter_departemen = $('.filter-departemen').val();
                    }
                },
                columns: [
                    { data: 'id', visible: false },
                    { data: 'code' },
                    { data: 'name' },
                    { data: 'departemen' },
                    { data: 'action' }
                ],
                drawCallback: function (settings) {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).data();
                    var container = $('#card-grid');
                    container.empty();

                    // Move controls to placeholders
                    $('#top-controls').append($('.dataTables_length'));
                    $('#info-placeholder').append($('.dataTables_info'));
                    $('#pagination-placeholder').append($('.dataTables_paginate'));

                    if (rows.length === 0) {
                        container.append('<div class="col-span-full py-10 card text-center text-slate-500">No records found.</div>');
                        return;
                    }

                    rows.each(function (row, i) {
                        var photoUrl = row.photo ? '{{ asset("") }}' + row.photo : '{{ asset("images/profile/default.png") }}';
                        var card = `
                            <div class="card p-5 dark:bg-navy-700 border border-slate-200 dark:border-navy-500 hover:shadow-lg transition-all duration-300">
                                <div class="flex flex-col items-center">
                                    <div class="avatar size-24 relative">
                                        <img class="mask is-squircle border-4 border-white dark:border-navy-600 shadow-md object-cover w-full h-full" 
                                             src="${photoUrl}" 
                                             alt="${row.name}">
                                        <span class="absolute right-0 bottom-0 size-5 rounded-full border-2 border-white bg-success dark:border-navy-700"></span>
                                    </div>

                                    <div class="mt-4 text-center">
                                        <h3 class="font-inter text-base font-bold text-slate-800 dark:text-navy-100 line-clamp-1">${row.name}</h3>
                                        <div class="mt-1">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-[#D4AF37]">${row.code}</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 w-full space-y-2 border-t border-slate-100 dark:border-navy-600 pt-4 text-[11px]">
                                        <div class="flex items-center justify-between">
                                            <span class="text-slate-400 font-medium">Department</span>
                                            <span class="font-bold text-slate-700 dark:text-navy-200">${row.departemen || '-'}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-slate-400 font-medium">Position</span>
                                            <span class="font-bold text-slate-700 dark:text-navy-200">${row.position || '-'}</span>
                                        </div>
                                    </div>

                                    <div class="mt-5 flex w-full justify-center space-x-2 pt-2 border-t border-slate-50 dark:border-navy-600/50">
                                        ${row.action}
                                    </div>
                                </div>
                            </div>
                        `;
                        container.append(card);
                    });
                }
            });

            // Re-draw on filter
            $('#btn_filter').on('click', function() {
                window.table.draw();
            });

            $('#btn_cancel').on('click', function() {
                $('.filter-item').val('');
                window.table.draw();
            });

            // Confirm delete handler
            $('#confirmDelete').on('click', function () {
                if (!deleteId) return;

                $('#deleteModal').addClass('hidden');

                $.ajax({
                    url: '{{ route($modul . ".destroy", ":id") }}'.replace(':id', deleteId),
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