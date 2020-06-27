$(document).ready(function () {
    $(".verwerk").click(function () {
        var id = $(this).val();
        $.ajax({
            type: "POST",
            url: "melding_verwerk.php",
            data: {"id": id}
        });
        $(this).css("display", "none");
    });
});