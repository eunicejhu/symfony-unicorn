$(document).ready(function () {

    $('#search-input').on('input change click', function () {
        console.log($(this).val());
        if ($(this).val() && $(this).val().length > 0) {
            $('#clear-search').removeClass('invisible');
        } else {
            $('#clear-search').addClass('invisible');
        }
    });

    $('#clear-search').on('click', function () {
        $('#search-input').val('');
        $(this).addClass('invisible');
    });

    if ($('#search-input').val() && $(this).val().length > 0) {
        $('#clear-search').removeClass('invisible');
    } else {
        $('#clear-search').addClass('invisible');
    }
})