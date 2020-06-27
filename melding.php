<?php

// include config
require "config.inc.php";

// include de sessie
require "session.inc.php";

// kijk of er een sessie actief is en of het echt alleen de admin is

if($session_actief == false || $session_accounttype != "Administrator"){
    header("location:(old)index.php");
}

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- GLR Huisstijl -->
    <link rel="stylesheet" href="stylesheet/glrhuisstijl.css">


    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <title>Meldingen</title>
</head>

<?php
    // inlcude de header
include "header.php";

// maak een query voor het ophalen van alle meldingen
$meldingQuery = "SELECT `ID`,`Omscrijving`,`ProjectID`,`Rede`,`Verwerkt` FROM `meldingen` WHERE `meldingen`.`Verwerkt` = 0;";

// verwerk de query in een result

$meldingQueryResult = mysqli_query($mysqli, $meldingQuery);

// loop door alle meldingen heen

while ($row = mysqli_fetch_array($meldingQueryResult)){

    ?>
    <div class="col-sm-12 my-3">
        <div class="card rounded-0">
            <div class="card-body">
                <h5 class="card-title">Reden van Melding: <?php echo $row['Rede'] ?></h5>
                <hr class="my-2"/>
                <p class="card-text omschrijving">
                    <?php

                     echo $row['Omscrijving'];

                    ?>
                </p>
                <a href="project_overzicht.php?id=<?php echo base64_encode($row['ProjectID'])?>" class="btn btn-lg btn-lg btn-glr">Bekijk project</a>
            </div>

            <div class="card-footer text-muted">
                <button type="button" class="btn btn-warning verwerk" value="<?php echo $row['ID'] ?>">Melding bekeken</button>
            </div>
        </div>
    </div>

<?php
}
?>


<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- Fontawesome Icons -->
<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>
<!-- Extra Javascript -->
<script src="Javascript/melding.js"></script>

</body>
</html>
