<?php
// commentaar door brian

//include de sessie
require "session.inc.php";

// include de config
require "config.inc.php";

// haal de gegevens op van de from en zet ze in plain text
$projectID      = base64_decode(htmlentities(mysqli_escape_string($mysqli,$_POST['project'])));
$projectIDBack  = $_POST['project'];
$projectNaam    = htmlentities(mysqli_escape_string($mysqli,$_POST['title']));
$projectStart   = htmlentities(mysqli_escape_string($mysqli,$_POST['startdatum']));
$deadline       = htmlentities(mysqli_escape_string($mysqli,$_POST['deadline']));
$omschrijving   = htmlentities(mysqli_escape_string($mysqli,$_POST['omschrijving']));
$inschrijven    = $_POST['inschrijven'];

if($inschrijven == "on"){
    $inschrijven = 1;
}else{
    $inschrijven = 0;
}

// controleer of de naam leeg is of langer dan 64 characters
if(strlen($projectNaam) > 64 || $projectNaam == ""){
    header("location:project_bewerk.php?msg=1&startDatum=$projectStart&deadline=$deadline&omSchrijving=$omschrijving&inschrijven=$inschrijven&id=$projectIDBack");
    exit();
}

// check of de datum wel is ingevoerd
if($projectStart == ""){
    header("location:project_bewerk.php?msg=3&projectNaam=$projectNaam&startDatum=$projectStart&deadline=$deadline&omSchrijving=$omschrijving&inschrijven=$inschrijven&id=$projectIDBack");
    exit();
}

// check of start date niet eerder is dan date van nu

if(DateTime::createFromFormat('Y-m-d', $projectStartDate) !== false) {
    header("location:project_bewerk.php?msg=2&projectNaam=$projectNaam&deadline=$deadline&omSchrijving=$omschrijving&inschrijven=$inschrijven&id=$projectIDBack");
    exit();
}

// check of de deadline later dan de startdatum is
$projectStartDate = new DateTime($projectStart);
$deadlineDate     = new DateTime($deadline);

if($deadline != "" && DateTime::createFromFormat('Y-m-d', $deadline) !== true){
    if($deadlineDate < $projectStartDate){
        header("location:project_bewerk.php?msg=4&projectNaam=$projectNaam&startDatum=$projectStart&omSchrijving=$omschrijving&inschrijven=$inschrijven&id=$projectIDBack");
        exit();
    }
}


// check of de omschrijving is ingevuld
if($omschrijving == ""){
    header("location:project_bewerk.php?msg=5&projectNaam=$projectNaam&startDatum=$projectStart&deadline=$deadline&inschrijven=$inschrijven&id=$projectIDBack");
    exit();
}

// maak een query voor het invoegen van een project
$projectInvoegQuery = "UPDATE `project` SET `ProjectNaam` = '$projectNaam', `Omschrijving` = '$omschrijving', `ProjectStart` =  '$projectStart', `ProjectDeadline` ='$deadline', `InschrijvenNaStart` = '$inschrijven' WHERE `project`.`ID` = $projectID";


// voer de update query uit
mysqli_query($mysqli, $projectInvoegQuery);

// rederect naar de index pagina
header("location:project_overzicht.php?id=$projectIDBack");