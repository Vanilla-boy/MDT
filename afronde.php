<?php

// include de config
require "config.inc.php";

$projectID = $_POST['ProjectID'];

if(isset($projectID))
{
    // maak een afrond query
    $afrondQuery = "UPDATE `project` SET `Status` = 'Afgerond' WHERE `ID` = $projectID";

}
mysqli_query($mysqli, $afrondQuery);
?>