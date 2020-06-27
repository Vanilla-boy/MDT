<?php
// include de sessie
require "session.inc.php";

// include de config
require "config.inc.php";

$Query = "SELECT
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
                    INNER JOIN `rol` ON `project_rol_verband`.`RollID` = `rol`.`ID` WHERE `gebruiker`.`ID` = $session_id
                                            ORDER BY CASE `project`.`Status`
                                                WHEN 'Inactief' THEN 1
                                                WHEN 'Actief' THEN 2
                                                WHEN 'Afgerond' THEN 3
                                                ELSE 4
                                            END";
$QueryResultaat = mysqli_query($mysqli, $Query);

$QueryJoin = "SELECT
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
                    INNER JOIN `project_team` ON `project`.`ID` = `project_team`.`ProjectID`
                     WHERE `project_team`.`GebruikersID` = $session_id AND `project`.`OpdrachtGeversID` != $session_id
                                            ORDER BY CASE `project`.`Status`
                                                WHEN 'Inactief' THEN 1
                                                WHEN 'Actief' THEN 2
                                                WHEN 'Afgerond' THEN 3
                                                ELSE 4
                                            END LIMIT 10";
$QueryJoinResultaat = mysqli_query($mysqli, $QueryJoin);



?>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script><script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
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
    <title>Mijn opdrachten</title>
</head>

<?php
// include header
include "header.php";
?>

<div class="container-fluid">
    <div class="row">
        <div class="container col-6">
            <h1 class="text-glr text-center">Mijn opdrachten</h1>
                <?php
                    // loop door alle opdrachten heen
                    while ($row = mysqli_fetch_array($QueryResultaat)) {

                        // maak een variable aan voor het project id
                        $projectId = $row['ID'];

                        ?>

                        <div class="col-sm-12 my-3">
                            <div class="card rounded-0">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $row['ProjectNaam'];
                                        // check of de status van de opdracht
                                        if ($row['Status'] == "Actief") {

                                            echo '<span class="badge badge-success ml-1">Gestart</span>';

                                        } else if ($row['Status'] == "Inactief") {

                                            echo '<span class="badge badge-danger ml-1">Niet Gestart</span>';

                                        } else if ($row['Status'] == "Afgerond") {

                                            echo '<span class="badge badge-secondary ml-1">Klaar</span>';

                                        }
                                        ?>
                                    </h5>
                                    <h6 class='card-subtitle text-muted mb-1'>Project
                                        Start: <?php echo $row['ProjectStart'] ?> </h6>
                                    <hr class="my-2"/>
                                    <p class="card-text omschrijving">
                                        <?php

                                        $omschrijving = $row['Omschrijving'];

                                        if (strlen($omschrijving) > 150) {
                                            $omschrijving = substr($omschrijving, 0, 150);
                                            $omschrijving = $omschrijving . "...";
                                        }

                                        echo $omschrijving;

                                        ?>
                                        <a href="project_overzicht.php?id=<?php echo base64_encode($row['ID']) ?>">Meer lezen</a>
                                    </p>
                                    <a href="project_overzicht.php?id=<?php echo base64_encode($row['ID'])?>" class="btn btn-lg btn-lg btn-glr">meer info</a>
                                </div>

                                <div class="card-footer text-muted">
                                    <!-- 2 dagen geleden --> Door <a style="color: #ABCD00" href="profiel.php?id=<?php echo base64_encode($row['GebruikerID']) ?>"><b><?php echo $row['Voornaam'] . " " . $row['Achternaam'] ?></b></a> (<span style="color: <?php echo $row['Kleur']?>"><?php echo $row['OpleidingsNaam']?></span>)
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>

        </div>
        <div class="container col-6">
            <h1 class="text-glr text-center">Opdracht waar ik aan mee werk</h1>
            <?php
            // loop door alle opdrachten heen
            while ($row = mysqli_fetch_array($QueryJoinResultaat)) {

                // maak een variable aan voor het project id
                $projectId = $row['ID'];

                ?>

                <div class="col-sm-12 my-3">
                    <div class="card rounded-0">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo $row['ProjectNaam'];
                                // check of de status van de opdracht
                                if ($row['Status'] == "Actief") {

                                    echo '<span class="badge badge-success ml-1">Gestart</span>';

                                } else if ($row['Status'] == "Inactief") {

                                    echo '<span class="badge badge-danger ml-1">Niet Gestart</span>';

                                } else if ($row['Status'] == "Afgerond") {

                                    echo '<span class="badge badge-secondary ml-1">Klaar</span>';

                                }
                                ?>
                            </h5>
                            <h6 class='card-subtitle text-muted mb-1'>Project
                                Start: <?php echo $row['ProjectStart'] ?> </h6>
                            <hr class="my-2"/>
                            <p class="card-text omschrijving">
                                <?php

                                $omschrijving = $row['Omschrijving'];

                                if (strlen($omschrijving) > 150) {
                                    $omschrijving = substr($omschrijving, 0, 150);
                                    $omschrijving = $omschrijving . "...";
                                }

                                echo $omschrijving;

                                ?>
                                <a href="project_overzicht.php?id=<?php echo base64_encode($row['ID']) ?>">Meer
                                    lezen</a>
                            </p>
                            <a href="project_overzicht.php?id=<?php echo base64_encode($row['ID'])?>" class="btn btn-lg btn-lg btn-glr">meer info</a>
                        </div>

                        <div class="card-footer text-muted">
                            <!-- 2 dagen geleden --> Door <a style="color: #ABCD00" href="profiel.php?id=<?php echo base64_encode($row['GebruikerID']) ?>"><b><?php echo $row['Voornaam'] . " " . $row['Achternaam'] ?></b></a> (<span style="color: <?php echo $row['Kleur']?>"><?php echo $row['OpleidingsNaam']?></span>)
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

        </div>
    </div>
</div>


</body>
</html>
