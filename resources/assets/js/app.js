
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(document).ready(function() {
    //Custom file name change
    $('input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });

    //Select all functionality
    $("#select_all").change(function () {
        $(":checkbox").prop('checked', $(this).prop("checked"));
    });

    $(':checkbox').change(function () {
        if (false === $(this).prop("checked")) {
            $("#select_all").prop('checked', false);
        }

        if ($(':checkbox:checked').length === $(':checkbox').length-1) {
            $("#select_all").prop('checked', true);
        }
    });
});