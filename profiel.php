<?php
// include de sessie
require_once "session.inc.php";
// include de config
require "config.inc.php";

// haal het id uit de url
$urlID = base64_decode($_GET['id']);
$msg = $_GET['msg'];

// check of er via de url een id wordt gestuurd
if(isset($_GET['id'])){
    $gebruikersID = $urlID;
} else {
    $gebruikersID = $session_id;
}

$query = "
    SELECT
        `gebruiker`.`Voornaam`,
        `gebruiker`.`Achternaam`,
        `gebruiker`.`Email`,
        `gebruiker`.`ID`,
        `opleiding`.`OpleidingsNaam`,
        `opleiding`.`Kleur`,
        `profiel`.`Biografie`,
        `profiel`.`Website`,
        `profiel`.`Profielfoto`,
        `profiel`.`AlternatieveEmail`,
        `profiel`.`TelefoonNummer`,
        `profiel`.`Website`,
        `specialisatie`.`SpecialisatieNaam`
    FROM `gebruiker`
    INNER JOIN `profiel` ON `gebruiker`.`ID` = `profiel`.`GebruikersID`
    INNER JOIN `opleiding` ON `profiel`.`OpleidingsID` = `opleiding`.`ID`
    INNER JOIN `specialisatie` ON `profiel`.`SpecialisatieID` = `specialisatie`.`ID`
    WHERE `gebruiker`.`ID` = $gebruikersID;
    ";

// Voer de query uit
$projectQuery = mysqli_query($mysqli, $query);
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- GLR Huisstijl -->
    <link rel="stylesheet" href="stylesheet/glrhuisstijl.css">

    <!-- Eigen CSS -->
    <link rel="stylesheet" href="stylesheet/profiel-info.css">

    <!-- Fontawesome Icons -->
    <script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Title -->
    <title>Profiel</title>
</head>
<body>
<!-- header -->
<?php require_once("header.php"); // require de header ?>
<?php
// loop door alle opdrachten heen
$row = mysqli_fetch_array($projectQuery) ?>
<!-- Container voor profiel info en biografie -->
<div class="container">
    <div class="row">
        <!-- Styling van profiel info -->
        <div class="container bg-dark text-white">
            <div class="border border-dark rounded-lg row justify-content-start shadow row mx-0 my-2 p-2">
                <!-- Profiel info img -->
                <div class="col-md-3"></div>
                <div class="col-md-3">
                    <?php

                    if($row['Profielfoto'] == NULL || $row['Profielfoto'] == "")
                    {
                        echo'<i class="fas fa-user-circle fa-2 d-inline-block align-top" style="font-size: 150px; color:' . $row["Kleur"] .  '; margin-bottom: 20px;"></i>';
                    } else {
                        echo'<img class="d-inline-block align-top" src="images/profielfotos/' . $row['Profielfoto'] . '" height="150px" width="150px" style="border-radius: 50%; margin-bottom: 20px;">';
                    }

                    ?>
                </div>
                <!-- Profiel infomatie -->
                <div class="col-md-4" style="padding: 2px;  margin-left: 15px;">
                    <!-- Profiel info Voornaam en Achternaam-->
                    <div>
                        <p class="text-break"> <?php echo $row['Voornaam']?> <?php echo $row['Achternaam'] ?></p>
                    </div>

                    <!-- Profiel info Studentnummer -->
                    <div>
                        <p class="text-break"> <?php echo $row['Email'] ?></p>
                    </div>
                    <!-- Profiel info Alternatieve email -->
                    <div>
                        <p class="text-break"> <?php echo $row['AlternatieveEmail'] ?></p>
                    </div>
                    <!-- Profiel info TelefoonNummer -->
                    <div>
                        <p class="text-break"> <?php echo $row['TelefoonNummer'] ?></p>
                    </div>
                </div>
                <!-- Profiel info Opleiding -->
                <input class="mt-2 border d-inline-flex p-2 bd-highlight form-control" type="text" name="Opleiding" value="<?php echo $row['OpleidingsNaam'] ?>" readonly style="color: <?php echo $row['Kleur'] ?> ; width: 100%; margin-bottom: 8px; margin-left: 15px; margin-right: 15px">
                <!-- Infomatie over Specialisatie -->
                <?php if($row['SpecialisatieNaam'] != "Geen") { ?>
                    <input class="mt-2 border d-inline-flex p-2 bd-highlight form-control" type="text" name="Specialisatie" value="<?php echo $row['SpecialisatieNaam']; ?>" readonly style="color: <?php echo $row['Kleur'] ?> ; width: 100%; margin-bottom: 8px; margin-left: 15px; margin-right: 15px">
                <?php } ?>
                <!-- Biografie/infomatie over jezelf -->

                <div style="margin-left: 15px; margin-top: 8px; margin-bottom: 8px;">
                    <p class="text-break"> <?php echo $row['Biografie'] ?></p>
                    <!-- Infomatie over Website -->
                    <!-- Styling info over website -->
                    <div class="input-group mb-3 d-flex align-items-end">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon3">Website/Portofolio:</span>
                        </div>
                        <a href="<?php echo $row['Website']; ?>"> <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3" readonly value="<?php echo $row['Website']; ?>"></a>
                    </div>
                </div>

            </div>
        </div>


    </div>
</div>



<!-- Aan kunnen passen en opdrachten buttons -->
<!-- Kijk of het gebruikers id gelijk is aan het sessie id -->
<div class="d-flex justify-content-center" style="margin-top: 20px;">
    <?php if($row['ID'] == $session_id && $session_actief == true){ ?>
        <a href="profiel_aanpassen.php" class="btn btn-lg btn-lg btn-primary" style="margin-right: 15px;">Aanpassen</a>
        <a href="mijn_opdracht_overzicht.php" class="btn btn-lg btn-lg btn-glr">Mijn opdrachten</a>
    <?php } ?>
</div>

<?php if (isset($_GET['msg'])) { ?>
    <div class="mt-4 shadow alert alert-success alert-dismissible fade show" role="alert">
        <?php

        // echo de bijbehorende message
        switch($msg){
            case 1:
                echo "Profiel is geupdate";
                break;
            default:
                echo $msg;
                break;
        }

        ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php } ?>

<!-- <a href="profiel_aanpassen.php" class="col-sm-4"><button type="button" class="btn btn-primary">Wijzigen</button></a> -->
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>