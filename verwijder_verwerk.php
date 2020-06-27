<?php

// include de config
require "config.inc.php";

// haal alle gegevens op
$projectID = $_POST['projectID'];

mysqli_query($mysqli,  "DELETE FROM `project` WHERE `project`.`ID` = '$projectID'");
header("location:(old)index.php");
