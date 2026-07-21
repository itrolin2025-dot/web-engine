// Set Global DataTables Defaults
if ($.fn.dataTable) {
    $.extend(true, $.fn.dataTable.defaults, {
        pagingType: 'simple_numbers',
        language: {
            paginate: {
                previous: '<i class="fa fa-chevron-left text-[10px]"></i>',
                next: '<i class="fa fa-chevron-right text-[10px]"></i>'
            },
            lengthMenu: "Show _MENU_ entries",
            search: "_INPUT_",
            searchPlaceholder: "Search here..."
        },
        dom: 'lrtip' // Standardize DOM layout: length, table, reveal, info, pagination
    });
}

function showNotification(type, message) {
    var notificationArea = $('#js-notification-area');
    var bgColor, icon, alertClass;
    if (type === 'success') {
        bgColor = 'bg-success';
        alertClass = 'alert-success';
        icon = '<i class="fa fa-check-circle mr-2"></i>';
    } else {
        bgColor = 'bg-error';
        alertClass = 'alert-danger';
        icon = '<i class="fa fa-exclamation-circle mr-2"></i>';
    }
    var html = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="margin-bottom: 1.5em;">
            <div class="alert flex rounded-lg ${bgColor} px-4 py-4 text-white sm:px-5" style="margin-bottom: 3em;">
                ${icon}${message}
            </div>
        </div>
    `;
    notificationArea.html(html);
    setTimeout(function () {
        notificationArea.children().fadeOut(600, function () {
            $(this).slideUp(300, function () {
                notificationArea.html('');
            });
        });
    }, 3000);
}

$(document).ready(function () {

    var notifSuccess = $('#js-notification-area').attr('content');
    if (typeof notifSuccess !== 'undefined' && notifSuccess !== null) {
        if (notifSuccess.length > 0) {
            showNotification('success', notifSuccess);
        }
    }
    var notifError = $('#js-notification-area').attr('content');
    if (typeof notifError !== 'undefined' && notifError !== null) {
        if (notifError.length > 0) {
            showNotification('error', notifError);
        }
    }

    // filter start //
    let searchTimeout;
    $('.table-search-input').on('keyup', function () {
        clearTimeout(searchTimeout);
        const value = this.value;
        searchTimeout = setTimeout(function () {
            table.search(value).draw();
        }, 3000);
    });

    // TOGGLE SEARCH INPUT
    $('.table-search-toggle').on('click', function () {
        var $input = $('.table-search-input');
        if ($('#status_filter').val() == 'x') {
            $('#status_filter').val('y');
            $input.removeClass('w-0').addClass('w-48').focus();
        } else {
            $('#status_filter').val('x');
            $input.removeClass('w-48').addClass('w-0').blur();
        }
        // closeFilter();
    });

    // FILTER STATUS
    $('#filterstatus').on('change', function () {
        table.draw();
        closeFilter();
    });

    $('#btn_filter').on('click', function () {
        table.draw();
        closeFilter();
    });

    $('#btn_cancel').on('click', function () {
        $('#ac-panel').removeClass('is-active is-open');
        $('.filter-item').val("");
        table.draw();
        closeFilter();
    });

    function closeFilter() {
        $('.ac-trigger').trigger('click');
    }

    // filter end //


    // Datatable Start //

    // Expand Mobile //
    $('#datatables').on('click', '.toggle-expand', function () {
        var $expandable = $(this).closest(".mobile-expandable");
        $expandable.toggleClass("open");
        var $details = $expandable.find(".mobile-details");
        if ($expandable.hasClass("open")) {
            $details.slideDown(150);
            $(this).find("i").removeClass("fa-chevron-down").addClass("fa-chevron-up");
        } else {
            $details.slideUp(150);
            $(this).find("i").removeClass("fa-chevron-up").addClass("fa-chevron-down");
        }
    });

    // Datatable End //

    // Delete Start //
    $('#datatables').on('click', '.delete', function () {
        deleteId = $(this).data('ix');
        const name = $(this).data('name') || '';
        $('#deleteName').text(name);
        $('#deleteModal').removeClass('hidden');
    });

    // Close modal (button)
    $(document).on('click', '[data-close-delete-modal]', function (e) {
        e.preventDefault();
        closeDeleteModal();
    });

    // Close modal (overlay)
    $(document).on('click', '#deleteModal .modal-overlay', function (e) {
        if ($(e.target).hasClass('modal-overlay')) {
            closeDeleteModal();
        }
    });

    function closeDeleteModal() {
        $('#deleteModal').addClass('hidden');
        deleteId = null;
    }
    // Delete End //


    // Restore Start //

    let restoreId = null;

    $('#datatables').on('click', '.restore', function () {
        console.log('restore clicked');

        restoreId = $(this).data('ix');
        const name = $(this).data('name') || '';

        $('#restoreId').val(restoreId);
        $('#restoreName').text(name);
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
    // Restore End //


});
