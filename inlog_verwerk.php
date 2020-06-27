<?php
// start de sessie
session_start();
// require de config
require_once "config.inc.php";

// Check of het form is gesubmit
if (isset($_POST['submit'])){

    // haal alle waardes op en filter ze
    $email = htmlentities(mysqli_escape_string($mysqli, $_POST['mail']));
    $password = htmlentities(mysqli_escape_string($mysqli, $_POST['wachtwoord']));

    // check of de waardes zijn ingevuld
    if (strlen($email) > 0 &&
        strlen($password) > 0) {

        // encrypt het wachtwoord
        $password = md5($password);

        // query om te checken of dit overeenkomt met de database
        $inlogCheckQuery =
            "SELECT
                `gebruiker`.`ID`,
	            `gebruiker`.`Email`,
                `gebruiker`.`Password`,
                `gebruiker`.`Voornaam`,
                `gebruiker`.`Achternaam`,
                `gebruiker`.`Type`,
                `profiel`.`Geregistreerd`
            FROM `gebruiker`
            LEFT JOIN `profiel` ON `profiel`.`GebruikersID` = `gebruiker`.`ID`
            WHERE
                `gebruiker`.`Email` = '$email' AND
                `gebruiker`.`Password` = '$password';";

        // voer de query uit en haal het resultaat op
        $inlogCheckQueryResultaat = mysqli_query($mysqli, $inlogCheckQuery);

        // check of de gegevens correct zijn
        if (mysqli_num_rows($inlogCheckQueryResultaat) > 0) {

            // zet de gegevens in een array
            $gebruiker = mysqli_fetch_array($inlogCheckQueryResultaat);

            // sla de gegevens op in de sessie
            $_SESSION['ID']         = $gebruiker['ID'];
            $_SESSION['voornaam']   = $gebruiker['Voornaam'];
            $_SESSION['achternaam'] = $gebruiker['Achternaam'];
            $_SESSION['email']      = $gebruiker['Email'];
            $_SESSION['accounttype'] = $gebruiker['Type'];
            $_SESSION['status']     = 'actief';

            // zet het gebruiker id in een variable
            $gebruikerID = $gebruiker['ID'];

            // controleer of de gebruiker voor het eerst ingelod is
            if($gebruiker['Geregistreerd'] == false && $gebruiker['accouttype'] == "Student"){
                // redirect naar voor het eerst ingelogdss
                header("location:profiel_aanpassen.php");
                exit();
            } else {
                // redirect naar de index
                header("location:index.php");
                exit();
            }

        } else {
            // gegevens niet gevonden in de database:
            header("location:inlog.php?msg=1&email=$email");
            exit();
        }

    } else {
        // gegevens niet allemaal ingevuld:
        header("location:inlog.php?msg=2&email=$email");
        exit();
    }

} else {
    // formulier niet ingevuld
    header("location:inlog.php");
    exit();
}