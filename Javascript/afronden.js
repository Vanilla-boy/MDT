$(document).ready(function () {
    $("#afronden").click(function () {
        alert("Weet je zeker dat je het project wilt afronden?");
        $("#AFRONDEN").show();
    });

    $("#AFRONDEN").click(function () {
        var afrond = $("#verwijderID").val();
        $.ajax({
            type: "POST",
            url: "afronde.php",
            data: {"ProjectID": afrond}
        });
        window.location.replace ("https://mdtbc8.ict-lab.nl/index.php");
    })
})