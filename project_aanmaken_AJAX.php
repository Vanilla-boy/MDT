<?php


// Commentaar Door:
// Martijn


// include de sessie
require_once "session.inc.php";
// include de config
require "config.inc.php";

//variables
$rolNum = $_POST['NUM'];
$result = "amountResult";

$rollen = mysqli_query($mysqli, 'SELECT `ID` FROM `rol`');


// herhaal de code die hierin staat met de hoeveelheid rollen die in de database staan + 1
while($rol = mysqli_fetch_array($rollen)) {

    $x = $rol['ID'];

    //deze code haalt de namen van de rollen die bij de het getal van de herhaling hoort
    $rolNaamQuery = "SELECT `RolNaam` FROM `rol` AS Rolnaam WHERE `ID` = '$x'";
    $rolNaamQueryResult = mysqli_query($mysqli, $rolNaamQuery);
    $rolresult = mysqli_fetch_array($rolNaamQueryResult);
    $RolResult = $rolresult['RolNaam'];


    // voeg het result en herhalings variabel samen zodat je een resultaat zoals: "amountResult1" of "amountResult2" of "amountResult3" enzv...
    // dit doen we zodat we later dit kunnen gebruiken als dynamische index om de dynamisch gegenereerde variabels van de vorige pagina kunnen opvangen uit de post
    $realResult = $result . '' . $x;

    // laat het resultaat van de rollen query code van lijn 26 t&m 30 zien
    echo " $RolResult " ;

    // laat het variabel amount+nummer van de rol zien
    // door het variabel uit de post van de aanmaken pagina te ontvangen
    // en we gebruiken het op lijn 35 gemaakte variabel als dynamische index voor de post opdracht
    echo ${"amount$rolNum"} = $_POST["$realResult"];

    // zorgt voor een eenter in het textarea
    echo "\r\n";

}
?>
