$(document).ready(function () {
    $("#verwijder").click(function () {
        var projectID = $("#verwijderID").val();
        window.location.replace ("https://mdtbc8.ict-lab.nl/index.php");
        $.ajax({
            url: "verwijder_verwerk.php",
            method: "POST",
            data:{
                'projectID': projectID
            }
        })
    })
})