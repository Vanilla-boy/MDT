<?php
// include de sessie
require_once "session.inc.php";
// include de config
require "config.inc.php";


if(isset($_POST['submit'])) {

    $bestand =      $_FILES['foto'];
    $id =           $_POST['id'];
    $bio =          $_POST['biografie'];
    $site =         $_POST['website'];
    $email =        $_POST['alt-email'];
    $tel =          $_POST['telefoon'];

} else {

    header("location:inlog.php");

}

$biocheck = false;
$sitecheck = false;
$emailcheck = false;
$telcheck = false;

if (empty($bio)) {

    //toon foutmelding
    echo"De biografie is verplicht in te vullen!!!";

} else if(!is_string($bio)){

    // ga verder naar volgene stap
    // 2 controleer of juist type is

        //toon foutmeling
        echo"bio moet een tekst zijn!!!";

    } else if(strlen($bio) > 500){
        echo "Je bio is te lang";
} else {

        // ga verder naar volgene stap
        //3 controleer en verwijder gevaarlijke tekens
        $bio = htmlentities(mysqli_real_escape_string($mysqli, $bio));
        $biocheck = true;

}

// check of de site is ingevuld
    if(strlen($site) > 0){

        // maak een var voor de eerte 8 karakters
        $firstTwoCharacters = substr($site, 0, 8);

        // check voor de eerste 8 karakters van de website link
        if($firstTwoCharacters != "https://"){
        $site = "https://" . $site;
        }

        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $site)) {
            // TODO terug sturen als een msg
            echo "het ingevulde URL voor uw website klopt niet";
        }
        else {
            $sitecheck = true;
        }
    }else{
        $site = null;
        $sitecheck = true;
    }



if (strlen($email) > 0) {
    // check of de email echt is
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //TODO een msg als je email fout is

        echo "De E-mail is geen echte email";
    }
    else {
        $emailcheck = true;
    }

}
else {
    $email = null;
    $emailcheck = true;
}

if (strlen($tel) > 0) {

    $telcheck = true;

} else {

    $tel = null;
    $telcheck = true;

}

// controleer of de upload geslaagd is
if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0){

    // controleer het bestand type
    if( $_FILES['foto']['type'] == "image/jpg" ||
        $_FILES['foto']['type'] == "image/jpeg"||
        $_FILES['foto']['type'] == "image/png" ||
        $_FILES['foto']['type'] == "image/pjpeg" ||
        $_FILES['foto']['type'] == "image/gif"){

        // controleer welk type het is
        if($_FILES['foto']['type'] == "image/jpg"){
            $fotoInput = $session_id . ".jpg";
        }
        elseif($_FILES['foto']['type'] == "image/jpeg") {
            $fotoInput = $session_id . ".jpeg";
        }
        elseif($_FILES['foto']['type'] == "image/png") {
            $fotoInput = $session_id . ".png";
        }
        elseif($_FILES['foto']['type'] == "image/pjpeg") {
            $fotoInput = $session_id . ".pjpeg";
        }
        elseif($_FILES['foto']['type'] == "image/gif") {
            $fotoInput = $session_id . ".gif";
        }


        // wat is de fisieke locatie van de upload map

        $map = __DIR__ . "/images/profielfotos/";

        // verplaats de upload naa de juiste map met de juiste naam

        move_uploaded_file($_FILES['foto']['tmp_name'], $map . $fotoInput);
    }

}

if($telcheck && $emailcheck && $sitecheck && $biocheck)
{
    if($fotoInput == NULL){
        $query ="UPDATE profiel SET Biografie ='$bio' , Website ='$site', AlternatieveEmail ='$email', TelefoonNummer ='$tel', Geregistreerd = 1 WHERE GebruikersID= '$id'";
    }
    else{
        $query ="UPDATE profiel SET Biografie ='$bio',Profielfoto = '$fotoInput' , Website ='$site', AlternatieveEmail ='$email', TelefoonNummer ='$tel', Geregistreerd = 1 WHERE GebruikersID= '$id'";
    }
}

if (mysqli_query($mysqli, $query)){
    header('location:profiel.php?msg=1');
} else {

    echo mysqli_error($mysqli);

    echo "<button class=\"btn btn-danger\" onclick=\"history.back();return false;\">Annuleren</button>";
}
?>