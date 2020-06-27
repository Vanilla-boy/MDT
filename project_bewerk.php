<?php
// require de sessie
require_once("session.inc.php");
// require de config
require_once("config.inc.php");

// haal het id uit de url
$projectID = base64_decode($_GET['id']);

// haal eventuele get gegevens op
$msg            = $_GET['msg'];
$projectNaam    = $_GET['projectNaam'];
$startDatum     = $_GET['startDatum'];
$deadline       = $_GET['deadline'];
$omschrijving   = $_GET['omSchrijving'];
$inschrijven    = $_GET['inschrijven'];

// maak een qeury om de opdracht uit te lezen
$projectQuery = "    SELECT `project`.`ProjectNaam`,`project`.`Omschrijving`,`project`.`ProjectStart`,
                        `project`.`ProjectDeadline`,`project`.`InschrijvenNaStart`
                     FROM `project`
                     WHERE `project`.`ID` = '$projectID';";

// maak een result aan voor project
$projectQueryresult = mysqli_query($mysqli, $projectQuery);
$projectGegevens = mysqli_fetch_array($projectQueryresult);

if(isset($projectNaam)){
    $projectNaam = $_GET['projectNaam'];
}else{
    $projectNaam = $projectGegevens['ProjectNaam'];
}

if(isset($startDatum)){
    $startDatum = $_GET['startDatum'];
}else{
    $startDatum = $projectGegevens['ProjectStart'];

}

if(isset($deadline)){
    $deadline = $_GET['deadline'];
}else{
    $deadline = $projectGegevens['ProjectDeadline'];
}

if(isset($omschrijving)){
    $omschrijving = $_GET['omSchrijving'];
}else{
    $omschrijving = $projectGegevens['Omschrijving'];
}

if(isset($inschrijven)){
    $inschrijven = $_GET['inschrijven'];
}else{
    $inschrijven = $projectGegevens['InschrijvenNaStart'];
}



?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet/pa_style.css">
    <link rel="stylesheet" href="stylesheet/glrhuisstijl.css">

    <!-- Fontawesome Icons -->
    <script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Title -->
    <title>Project bewerken</title>
</head>
<body>
<!-- header -->
<?php require_once("header.php"); // require de header ?>

<!-- page content -->
<main class="container rounded-bottom bg-dark text-light shadow py-3">
    <h1 class="text-glr text-center">Bewerk je Project</h1>
    <form action="project_bewerk_verwerk.php" method="post">
        <div class="form-group">
            <label for="">Titel:</label>
            <input class="form-control form-control-lg" type="text" name="title" value="<?php echo $projectNaam ?>">
        </div>
        <div class="form-group">
            <label for="">Omschrijving:</label>
            <textarea name="omschrijving" class="form-control form-control-lg" id="" cols="30" rows="5"><?php echo $omschrijving ?></textarea>
        </div>
        <div class="form-group">
            <label for="">StartDatum:</label>
            <input type="date" name="startdatum" class="form-control form-control-lg" value="<?php echo $startDatum ?>">
        </div>
        <div class="form-group form-check form-check-inline">
            <label class="form-check-label" style="width: 400px" for="inlineCheckbox1">Inschrijven na start datum: </label>
            <input class="form-check-input form-control form-control-lg" type="checkbox" name="inschrijven" id="inlineCheckbox1" <?php if($inschrijven == 1){ ?> checked <?php } ?>>
        </div>
        <div class="form-group">
            <label for="">DeadlineDatum:</label>
            <input type="date" name="deadline" class="form-control form-control-lg" value="<?php echo $deadline ?>">
        </div>

        <input type="hidden" name="project" value="<?php echo base64_encode($projectID) ?>">

        <div class="self-center">
            <a href="index.php" class="btn btn-danger">Annuleren</a>
            <button class="btn btn-glr">Opslaan</button>
        </div>
    </form>

    <?php if (isset($_GET['msg'])) { // check of er een msg is ?>
    <!-- Alert Message-->
    <div class="m-4 mb-5 shadow alert alert-danger alert-dismissible fade show fixed-bottom" role="alert">
        <?php

        // echo de bijbehorende message
        switch ($msg) {
            case 1:
                echo "Het project naam is leeg of langer dan 64 characters";
                break;
            case 2:
                echo "Deze datum is al geweest, kies een andere datum";
                break;
            case 3;
                echo "De startDatum is verplicht intevullen";
                break;
            case 4;
                echo "De Deadline moet na de startdatum komen";
                break;
            case 5;
                echo "De omschrijving van je project moet je invullen";
                break;
            case 7;
                echo "De Startdatum moet een datum zijn";
                break;
            case 8;
                echo "De deadline moet een datum zijn";
                break;
            default:
                echo $msg;
                break;
        }

        ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?php } ?>
</main>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- Fontawesome Icons -->
<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>
<!-- Extra Javascript -->
</body>
</html>