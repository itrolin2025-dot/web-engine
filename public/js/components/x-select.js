document.addEventListener('DOMContentLoaded', function () {
    $('.x-select').each(function () {
        const placeholder = $(this).data('placeholder') || 'Select option';

        $(this).select2({
            placeholder: placeholder,
            allowClear: true,
            width: '100%'
        });
    });
});
