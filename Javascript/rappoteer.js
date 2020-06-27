$(document).ready(function () {
    $("#rappoteer").click(function () {
        var omschrijving = $("#omschrijving").val();
        var reden = $("#reden").val();
        var projectID = $("#projectID").val();
        $.ajax({
            url: "rappoteer_verwerk.php",
            method: "POST",
            data:{
                'omschrijving': omschrijving,
                'reden': reden,
                'projectID': projectID
            }
        })
    })
})