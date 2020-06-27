<?php

// include de config
require "config.inc.php";

// haal alle gegevens op
$omschrijving = htmlentities($_POST['omschrijving']);
$reden = htmlentities($_POST['reden']);
$projectID = htmlentities($_POST['projectID']);

mysqli_query($mysqli, "INSERT INTO `meldingen` (`Omscrijving`, `ProjectID`, `Rede`, `Verwerkt`) VALUES ('$omschrijving', '$projectID', '$reden', '0')");
