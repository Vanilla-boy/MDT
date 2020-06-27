<?php

// include de sessie
require "session.inc.php";

// include de config
require "config.inc.php";

// haal de rol uit de url
$rol = base64_decode($_GET['Rol']);

// haal het project id uit de url
$projectID = base64_decode($_GET['Projectid']);

// haal de url niet ge decode uit de url
$pagina = $_GET['Projectid'];

// haal het gebruiker id op
$gebruikerId = $session_id;

// maak een variable voor de voor en achternaam
$gebruikerNaam = $session_voornaam . " " . $session_achternaam;

// haal het persoon id uit de url
$persoonID = base64_decode($_GET['Persoonid']);

// haal de roll id uit de url
$rollID = base64_decode($_GET['Rollid']);

// maak een var voor de link
$paginaLink = "project_overzicht.php?id=$pagina";

// maak een notificatie var aan
$notificatie = "?";

// kijk of de rol inschrijver is
if($rol == "inschrijver"){

    // maak een qeury voor het invoegen van een inschrijving
    $inschrijfQuery = "INSERT INTO `inschrijving` (`ID`, `ProjectID`, `GebruikersID`, `RollID`, `Status`) VALUES (NULL, $projectID, $gebruikerId, $rollID, 'Geintresseerd');";

    // vul de notificatie var
    $notificatie = $gebruikerNaam . " is geintresseerd in jou opdracht";

}

if ($rol == "leider"){

    // vul de status var
    $status = "Uitgenodigd";

    // verander het gebruikers id
    $gebruikerId = $persoonID;

    // vul de notificatie
    $notificatie = "Je bent uitgenodigd voor een opdracht";

}

if ($rol == "accepteerder"){

    // vul de status var
    $status = "Geaccepteerd";

    // verander het persoonsid

    // vul de notificatie var
    $notificatie = "$gebruikerNaam heeft de uitnodiging geaccepteerd";

    // maak een query om iemand in het team te zetten
    $projectTeamQuery = "INSERT INTO `project_team` (`GebruikersID`, `ProjectID`) VALUES ('$gebruikerId', '$projectID');";

}

if ($rol == "afweizen"){

    // vul de status var
    $status = "Afgewezen";

    // vul de notificatie var
    $notificatie = "$gebruikerNaam heeft de uitnodiging Afgewezen";

}

// maak een inschrijf query
$inschrijfUpdateQuery = "UPDATE `inschrijving` SET `Status` = '$status' WHERE `inschrijving`.`GebruikersID` = $gebruikerId AND `inschrijving`.`ProjectID` = $projectID AND `inschrijving`.`RollID` = $rollID";

// maak een notificatie query
$notificatieQuery = "INSERT INTO `notificatie` (`ID`, `GebruikersID`, `Notificatie`, `Pagina`, `Gelezen`) VALUES (NULL, '$persoonID', '$notificatie', '$paginaLink', '0');";


// voer de querys uit
mysqli_query($mysqli,$inschrijfUpdateQuery);
mysqli_query($mysqli, $inschrijfQuery);
mysqli_query($mysqli, $notificatieQuery);
mysqli_query($mysqli,$projectTeamQuery);


// stuur de persoon terug naar de opdracht
header("location:project_overzicht.php?id=". base64_encode($projectID));