<?php

// include de config
require "config.inc.php";

$id = $_POST['id'];

// maak een query voor het updaten van de query
$meldingQuery = "UPDATE `meldingen` SET `meldingen`.`Verwerkt` = 1 WHERE `meldingen`.`ID` = '$id'";

mysqli_query($mysqli, $meldingQuery);