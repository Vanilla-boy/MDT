<?php
// include de sessie
require "session.inc.php";
// include de config
require "config.inc.php";

    // Maak een query voor de projecten
    $query = "
        SELECT
            DISTINCT
            `project`.`ID`,
            `project`.`ProjectNaam`,
            `project`.`Omschrijving`,
            `project`.`ProjectStart`,
            `project`.`Status`,
            `gebruiker`.ID AS GebruikerID, 
            `gebruiker`.`Voornaam`,
            `gebruiker`.`Achternaam`,
            `opleiding`.`OpleidingsNaam`,
            `opleiding`.`Kleur`
        FROM `project`
        INNER JOIN `gebruiker` ON `project`.`OpdrachtGeversID` = `gebruiker`.`ID`
        INNER JOIN `profiel` ON `gebruiker`.`ID` = `profiel`.`GebruikersID`
        INNER JOIN `opleiding` ON `profiel`.`OpleidingsID` = `opleiding`.`ID`
        INNER JOIN `project_rol_verband` ON `project`.`ID` = `project_rol_verband`.`ProjectID`
        WHERE
            `project`.`status` = 'Inactief' OR
            `project`.`Status` = 'Actief' AND `project`.`InschrijvenNaStart` = 1
        ORDER BY `project`.`ID` DESC;;
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
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script><script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- GLR Huisstijl -->
    <link rel="stylesheet" href="stylesheet/glrhuisstijl.css">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Title -->
    <title>Let's make some projects</title>
</head>
<body>

<?php
    // include header
    include "header.php";
?>

<div class="container-fluid">
<div class="row">
<div class="container col-sm-9">

<?php if($session_actief) { ?>
    <h2>SessieGegevens (debug)</h2>
    <ul>
        <li>Sessie Actief: <?php echo $session_actief ?></li>
        <li>ID: <?php echo $session_id ?></li>
        <li>Voornaam: <?php echo $session_voornaam ?></li>
        <li>Achternaam: <?php echo $session_achternaam ?></li>
        <li>Email: <?php echo $session_email ?></li>
        <li>AccountType: <?php echo $session_accounttype ?></li>
    </ul>
    <hr/>


<?php
    }

    // loop door alle opdrachten heen
while ($row = mysqli_fetch_array($projectQuery)){

// maak een variable aan voor het project id
$projectId = $row['ID'];



// check of de datum all geweest is
$date = new DateTime($row['ProjectStart']);
$now = new DateTime();

if($date < $now) {

    // maak een query voor het updaten van de status
    $statusUpdateQuery = "UPDATE `project` SET `Status` = 'Actief' WHERE `project`.`ID` = $projectId";

    // update de status
    mysqli_query($mysqli, $statusUpdateQuery);
}

    echo '<div class="col-sm-12 my-3">
            <div class="card rounded-0">
                <div class="card-body">
                    <h5 class="card-title">' . $row['ProjectNaam'] . '';

                        // check of de status van de opdracht
                        if($row['Status'] == "Actief"){

                            echo'<span class="badge badge-success ml-1">Gestart</span>';

                        } else if ($row['Status'] == "Inactief") {

                            echo'<span class="badge badge-danger ml-1">Niet Gestart</span>';

                        }else if ($row['Status'] == "Afgerond") {

                            echo'<span class="badge badge-secondary ml-1">Klaar</span>';

                        }
                        ?>

                    </h5>
                    <h6 class='card-subtitle text-muted mb-1'>Project Start: <?php echo $row['ProjectStart'] ?> </h6>
                    <hr class="my-2"/>
                    <p class="card-text omschrijving">
                        <?php
                            /*echo $row['Omschrijving'] ?>... <a href="project_overzicht.php?id=<?php echo base64_encode($row['ID'])?>">Meer lezen</a>*/

                        $omschrijving = $row['Omschrijving'];

                        if(strlen($omschrijving) > 150) {
                            $omschrijving = substr($omschrijving,0, 150);
                            $omschrijving = $omschrijving . "...";
                        }

                        echo $omschrijving;

                        ?>
                        <a href="project_overzicht.php?id=<?php echo base64_encode($row['ID'])?>">Meer lezen</a>
                    </p>
                    <a href="project_overzicht.php?id=<?php echo base64_encode($row['ID'])?>" class="btn btn-lg btn-lg btn-glr">meer info</a>
                </div>

                <div class="card-footer text-muted">
                    <!-- 2 dagen geleden --> Door <a style="color: #ABCD00" href="profiel.php?id=<?php echo base64_encode($row['GebruikerID']) ?>"><b><?php echo $row['Voornaam'] . " " . $row['Achternaam'] ?></b></a> (<span style="color: <?php echo $row['Kleur']?>"><?php echo $row['OpleidingsNaam']?></span>)
                </div>
            </div>
        </div>
<?php
//}
    }
?>
    <!-- dit is tijdelijk inactief gezet omdat er nog geen gebruik van gemaakt word -->
<!--
</div>
    <div class="col-sm-3 bg-light">
        <div class="card bg-light mb-3">
            <div class="card-header">
                <form class="form-inline">
                    <input class="form-control mr-sm-2" id="zoeken" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" id="zoekenKnop" type="button">Search</button>
                </form>
            </div>

            <div class="card-body">

                <div class="container-fluid ">
                    <div class="row">

                        <?php
                                // maak een query voor alle mogelijke zoek functies
                            $filterOptiesQuery = "SELECT `RolNaam` , `Kleur` FROM `rol`
                                            INNER JOIN `opleiding_rol_verband` ON `rol`.`ID` =`opleiding_rol_verband`.`RollID`
                                            INNER JOIN `opleiding` ON `opleiding_rol_verband`.`OpleidingsID` = `opleiding`.`ID`";

                            // voer de query uit
                            $filterOptiesQueryResult = mysqli_query($mysqli,$filterOptiesQuery);

                            // loop door alle results heen
                            while ($filter = mysqli_fetch_array($filterOptiesQueryResult)){
                        ?>
                                <div class="card" style="margin: 2px;">
                                    <div class="card-body">
                                        <?php echo "<p style='color:". $filter['Kleur'] .";'>" . $filter['RolNaam'] . "</p>" ?>
                                    </div>
                                </div>
                        <?php

                            }

                        ?>

                </div>
            </div>
            </div>
-->
        </div>
    </div>
</div>
</div>
</div>


<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- Fontawesome Icons -->
<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>
<!-- Extra Javascript -->

</body>
</html>