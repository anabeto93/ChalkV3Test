
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(document).ready(function() {
    /**
     * Custom file name change
     */
    $('input[type="file"]').change(function(e) {
        var fileName = e.target.files[0].name;
        $('.custom-file-label').html(fileName);
    });

    /**
     * Select all functionality
     */
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

    /**
     * Dynamic field generation for QuestionAnswers
     */
    //Allow only one answer when short_exact
    var selectValue = $('select#type').val();

    $('select#type').on('change', function() {
        selectValue = $('select#type').val();
        checkQuestionType();
    });

    //Call check on document ready
    checkQuestionType();

    //Function to check for question type
    function checkQuestionType() {
        //If short_exact
        if (selectValue === 'short_exact') {
            //Remove all other answers except the first
            $('.dynamic-added').remove();
            //Hide the Add Answer button
            $('#add-answer').hide();
            //Check the only possible answer as correct and disable it
            $('input[name="questionAnswerCorrect[]"]').prop('checked', true).prop('disabled', true);
        } else {
            //Show the Add Answer button
            $('#add-answer').show();
            //Re-enable checkbox
            $('input[name="questionAnswerCorrect[]"]').prop('disabled', false);
        }
    }

    //Get questAnswerIndex of dynamically added answers
    var questionAnswerIndex = $('div.dynamic-added').length;

    //Add new answer
    $('#add-answer').click(function(){
        questionAnswerIndex++;

        $('#dynamic-field').append(
            '<div id="row'+questionAnswerIndex+'" class="row form-inline form-group dynamic-added">' +
            '<div class="col-8">' +
            '<div class="input-group">' +
            '<input required type="text" name="questionAnswerTitle[]" class="form-control" />' +
            '<div class="input-group-append">' +
            '<button id="'+questionAnswerIndex+'" name="remove" class="input-group btn btn-danger btn-remove"' +
            ' type="button">' +
            'X' +
            '</button>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-4 text-center">' +
            '<div class="form-check">' +
            '<input type="checkbox" id="checkbox'+questionAnswerIndex+'" name="questionAnswerCorrect[]"' +
            ' class="form-check-input" />' +
            '<label class="form-check-label" for="checkbox'+questionAnswerIndex+'">' +
            'Correct' +
            '</label>' +
            '</div>' +
            '</div>' +
            '</div>'
        );
    });

    //Remove answer
    $(document).on('click', '.btn-remove', function(){
        var button_id = $(this).attr("id");
        $('#row'+button_id+'').remove();
    });
});