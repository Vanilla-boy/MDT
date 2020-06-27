var text_max = $('#biografie').attr("maxlength");

$('#count_message').html(text_max + ' Tekens over');

$('#biografie').keyup(function () {
    var text_lenght = $('#biografie').val().length;
    var text_remaining = text_max - text_lenght;

    $('#count_message').html(text_remaining + ' Tekens over');
});