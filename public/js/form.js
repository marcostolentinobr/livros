// Validação de maxlength para campos number
$(document).ready(function() {
    $('input[type="number"][data-maxlength]').on('input', function() {
        const maxLength = parseInt($(this).data('maxlength'));
        if (maxLength && $(this).val().toString().length > maxLength) {
            $(this).val($(this).val().toString().substring(0, maxLength));
        }
    });
});

