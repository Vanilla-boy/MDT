<?php

// variabelen opstellen
$profielnaam = $session_voornaam . ' ' . $session_achternaam;
$NotiNum = 0;

// alle query voor de header

// kleur query
$kleurQuery = mysqli_query($mysqli, "
    SELECT opleiding.Kleur, profiel.GebruikersID, opleiding.ID, profiel.Profielfoto
    FROM opleiding
    INNER JOIN profiel ON opleiding.ID
    WHERE profiel.GebruikersID = '$session_id' AND profiel.OpleidingsID = opleiding.ID");

// notificatie
$notificatieQuery = mysqli_query($mysqli, "
    SELECT notificatie.GebruikersID, notificatie.Notificatie, notificatie.Pagina, notificatie.Gelezen 
    FROM `notificatie` 
    WHERE '$session_id'");

// maak een query voor het tellen van meldingen
$meldingQuery = "SELECT COUNT(`ID`) AS meldingen FROM `meldingen` WHERE `Verwerkt` = 0";

// maak een result van meldingen
$meldingQueryResult = mysqli_query($mysqli,$meldingQuery);

// haal de gegevens uit de array
// kleur
$kleurkiezen = mysqli_fetch_array($kleurQuery);

if(!isset($kleurkiezen['Kleur'])){
    $kleurkiezen['Kleur'] = '#abcd00';
}

// meldingen
$melding = mysqli_fetch_array($meldingQueryResult);

// loop door de notificaties heen
while($notificatie = mysqli_fetch_array($notificatieQuery)){

    // controleer of de notificatie voor jou is en kijk of notificatie al gelezen is
    if($notificatie['GebruikersID'] == $session_id && $notificatie['Gelezen'] == false){
        // tel notificaties op
        $NotiNum ++;

    }
}




?>

<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>

<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <a href="index.php">
        <img src="images/glrhuisstijl/GLR_logo.jpg" height="50" width="50" class="d-inline-block align-top mr-1">
    </a>

    <a class="navbar-brand ml-1" href="index.php">MDT GLR</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>

            <?php
                // controleer of het een student is
                if($session_actief && $session_accounttype == "Student") {
            ?>

            <li class="nav-item active">
                <a class="nav-link" href="project_aanmaak.php">Project aanmaken</a>
            </li>

            <?php
                //controleer of het een admin is
                }
                else if($session_actief && $session_accounttype == "Administrator"){
            ?>

            <li class="nav-item active">
                <a class="nav-link" href="melding.php">Meldingen bekijken <span class="badge badge-danger"><?php echo $melding['meldingen'];?></span></a>
            </li>

            <?php
                }
            ?>

            <li class="nav-item active d-block d-lg-none">
                <?php
                    // check of je wel ingelogd bent:
                    if($session_actief == true) {
                        //laat dan de navlink voor profiel zien

                        if($session_accounttype == "Student"){
                            echo'<a class="nav-link" href = "profiel.php" > Profiel</a><a class="nav-link" href = "notificaties.php" > Notifications</a>';
                        }
                        echo'<a class="nav-link" href = "uitlog.php" > Uitloggen</a>';
                    }
                    // anders als je niet ingelogd bent:
                    else if(!$session_actief == true) {
                        //laat dan de navlink voor inloggen zien
                        echo'<a class="nav-link" href = "inlog.php"> Inloggen</a>';
                    }
                ?>
            </li>
        </ul>

        <form class="form-inline my-2 my-lg-0 d-none d-lg-flex">
            <?php
            // check of je wel ingelogd bent:
            if($session_actief == true ) {
                // check of je een student bent
                if($session_accounttype == "Student"){
                    echo '<a href="notificaties.php" class="nav-link">';
                    //check of er notitficaties zijn
                    if ($NotiNum != 0) {
                        // ja? dan laat je een rood bolletje bij de bel zien met het aantal notificaties erin
                        echo '<div style="position: absolute; margin-left: 15px; background-color: red; color: white; border-radius: 50%; height: 24px; width: 24px; text-align: center; padding-top: -28px;">' . $NotiNum . '</div>';
                    }
                    ?>
                    <!-- laat het bell icon zien, -->
                <i class="fas fa-bell fa-2x" style="color: white;"></i></a>
                <?php
                    }
                ?>


                <!-- de link naar je profiel...-->
                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right: 10px; color: white;">
                        <?php echo $profielnaam ?>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <?php if($session_accounttype == "Student"){ ?>
                            <a class="dropdown-item" href="profiel.php">Profiel</a>
                        <?php } ?>
                        <a class="dropdown-item" href="uitlog.php">Uitloggen</a>
                    </div>
                </div>

                <!-- profiel foto -->
                <?php

                if($kleurkiezen['Profielfoto'] == NULL || $kleurkiezen['Profielfoto'] == "")
                {
                    echo'<i class="fas fa-user-circle fa-2 d-inline-block align-top" style="font-size: 50px; color:' . $kleurkiezen["Kleur"] .  '"></i>';
                }
                else {
                    echo'<a href="profiel.php"><img class="d-inline-block align-top" src="images/profielfotos/' . $kleurkiezen['Profielfoto'] . '" height="50px" width="50px" style="border-radius: 50px;"></a>';
                }
            }
            // anders als je niet ingelogd bent:
            else if(!$session_actief == true)
            {
                //laat de inloggen link dan zien rechtsboven
                echo '<a class="nav-link" style="color: white;" href="inlog.php">Inloggen</a>';
            }
            ?>
        </form>
    </div>
</nav>
