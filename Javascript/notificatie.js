$(document).ready(function () {
    // check of er op de gelezen knop is gedrukt
    $(".gelezen").click(function () {
        var id = $(this).val();
        // php pagina benaderen
        $.ajax({
            type: "POST",
            url: "notificatie_update.php",
            data: {"varID": id}
        });
        $(this).css("display", "none");
    });
});