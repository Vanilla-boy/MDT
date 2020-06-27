<?php
// commentaar door brian

//include de sessie
require "session.inc.php";

// include de config
require "config.inc.php";

// haal de gegevens op van de from en zet ze in plain text
$projectNaam = htmlentities(mysqli_escape_string($mysqli,$_POST['projectNaam']));
$projectStart = htmlentities(mysqli_escape_string($mysqli,$_POST['startDatum']));
$deadline = htmlentities(mysqli_escape_string($mysqli,$_POST['deadline']));
$omschrijving = htmlentities(mysqli_escape_string($mysqli,$_POST['omschrijving']));
$inschrijven = $_POST['inschrijvenNaStart'];

if($inschrijven == "on"){
    $inschrijven = 1;
}else{
    $inschrijven = 0;
}

$rollenString = $_POST['rollen'];

$rollen = explode("-",$rollenString);

// controleer of de naam leeg is of langer dan 64 characters
if(strlen($projectNaam) > 64 || $projectNaam == ""){
    header("location:project_aanmaak.php?msg=1&startDatum=$projectStart&deadline=$deadline&omSchrijving=$omschrijving&inschrijven=$inschrijven");
    exit();
}

// check of de datum wel is ingevoerd
if($projectStart == ""){
    header("location:project_aanmaak.php?msg=3&projectNaam=$projectNaam&startDatum=$projectStart&deadline=$deadline&omSchrijving=$omschrijving&inschrijven=$inschrijven");
    exit();
}

// check of start date niet eerder is dan date van nu
$date = new DateTime($projectStart);
$now = new DateTime();

if($date < $now && DateTime::createFromFormat('Y-m-d', $projectStart) !== true) {
    header("location:project_aanmaak.php?msg=2&projectNaam=$projectNaam&deadline=$deadline&omSchrijving=$omschrijving&inschrijven=$inschrijven");
    exit();
}

// check of de deadline later dan de startdatum is
$projectStartDate = new DateTime($projectStart);
$deadlineDate     = new DateTime($deadline);

if($deadline != "" && DateTime::createFromFormat('Y-m-d', $deadline) !== true){
    if($deadlineDate < $projectStartDate){
        header("location:project_aanmaak.php?msg=4&projectNaam=$projectNaam&startDatum=$projectStart&omSchrijving=$omschrijving&inschrijven=$inschrijven");
        exit();
    }
}


// check of de omschrijving is ingevuld
if($omschrijving == ""){
    header("location:project_aanmaak.php?msg=5&projectNaam=$projectNaam&startDatum=$projectStart&deadline=$deadline&inschrijven=$inschrijven");
    exit();
}

// check of de rollen leeg zijn
if($rollenString == ""){
    header("location:project_aanmaak.php?msg=6&projectNaam=$projectNaam&startDatum=$projectStart&deadline=$deadline&omSchrijving=$omschrijving&inschrijven=$inschrijven");
    exit();
}

// maak een query voor het invoegen van een project
$projectInvoegQuery = "INSERT INTO `project` (`ID`, `OpdrachtGeversID`, `ProjectNaam`, `Omschrijving`, `ProjectStart`, `ProjectDeadline`, `InschrijvenNaStart`, `Status`) 
                       VALUES (NULL, '$session_id', '$projectNaam', '$omschrijving', '$projectStart', '$deadline', '$inschrijven', 'Inactief');";


// voer het project query uit
mysqli_query($mysqli, $projectInvoegQuery);

// haal het laatst aangemaakt id uit het database
$id = mysqli_insert_id($mysqli);

// maak een query om de leider in het team te stopppen
$teamQuery = "INSERT INTO `project_team` (`GebruikersID`, `ProjectID`) VALUES ('$session_id', '$id');";

// voer de project team query uit
mysqli_query($mysqli,$teamQuery);

foreach ($rollen as $currentRol){

    $rolValue = htmlentities(mysqli_escape_string($mysqli,$_POST['rol-' . $currentRol]));

    if(is_numeric($rolValue) && $rolValue != 0){
        // maak een rol invoeg query aan
        $rolQuery = "INSERT INTO `project_rol_verband` (`ProjectID`, `RollID`, `Aantal`) VALUES ('$id', '$currentRol', '$rolValue')";

        // voer de rol query uit
        mysqli_query($mysqli,$rolQuery);
    }
}

// rederect naar de index pagina
header("location:project_overzicht.php?id=" . base64_encode($id));