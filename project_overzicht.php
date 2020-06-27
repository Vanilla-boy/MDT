<?php
// require de sessie
require_once("session.inc.php");
// require de config
require_once("config.inc.php");

// haal het id uit de url
$projectID = base64_decode($_GET['id']);

// maak een qeury om de opdracht uit te lezen
$projectQuery = "    SELECT `project`.`ProjectNaam`,`project`.`Omschrijving`,`project`.`ProjectStart`,
                        `project`.`ProjectDeadline`,`project`.`InschrijvenNaStart`,`project`.`Status`, `gebruiker`.`ID` ,`gebruiker`.`Voornaam`,
                        `gebruiker`.`Achternaam`,`gebruiker`.`Email`,`opleiding`.`OpleidingsNaam`,`opleiding`.Kleur
                     FROM `project`
                     INNER JOIN `gebruiker` ON `gebruiker`.`ID` = `project`.`OpdrachtGeversID`
                     INNER JOIN `profiel` ON `profiel`.`GebruikersID` = `gebruiker`.ID
                     INNER JOIN `opleiding` ON `opleiding`.`ID` = `profiel`.`OpleidingsID`
                     WHERE `project`.`ID` = '$projectID';";

// maak een result aan voor project
$projectQueryresult = mysqli_query($mysqli, $projectQuery);
$projectGegevens = mysqli_fetch_array($projectQueryresult);

$rolQuery = "SELECT 
                `rol`.`RolNaam`,`project_rol_verband`.`Aantal`,`rol`.`ID`
             FROM `project`
             INNER JOIN `project_rol_verband` ON `project_rol_verband`.`ProjectID` = `project`.`ID`
             INNER JOIN `rol` ON `rol`.`ID` = `project_rol_verband`.`RollID`
             WHERE `project`.`ID` = '$projectID'";

// maak een result aan voor rol
$rolQueryResult = mysqli_query($mysqli, $rolQuery);


// maak een functie om all reden optehalen
function get_enum_values($mysqli, $table, $field )
{
    $type = $mysqli->query("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'")->fetch_array(MYSQLI_ASSOC)['Type'];
    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
    $enum = explode("','", $matches[1]);
    return $enum;
}
$deltypevals = get_enum_values($mysqli, 'meldingen', 'Rede');

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

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Title -->
    <title>Hello, world!</title>
</head>
<body>
<!-- header -->
<?php require_once("header.php"); // require de header ?>

<!-- page content -->
<main class="container bg-dark text-light shadow rounded-bottom">
    <div class="row">
        <div class="col-lg-5 py-3">
            <!--<div class="row">

                <div class="col-12">
                    <div class="m-1 p-1 bg-white">
                        <h3><?php echo $projectGegevens['Voornaam'] . " " . $projectGegevens['Achternaam']?></h3>
                        <h5><span style="color: <?php echo $projectGegevens['Kleur'] ?>"><?php echo $projectGegevens['OpleidingsNaam'] . "</span><br>" ?></h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p  class=" p-1 border-dark border rounded">
                    <?php echo $projectGegevens['Omschrijving'] ?>
                    </p>
                </div>
            </div>-->
            <h1 class="text-center text-glr"><?php echo $projectGegevens['ProjectNaam']?></h1>
            <hr class="border-light"/>
            <h2 class="text-light">ProjectOmschrijving:</h2>
            <div class="p-1 bg-light text-dark rounded border border-info" style="white-space:pre-line;"><?php echo $projectGegevens['Omschrijving']?></div>
            <h2 class="text-light">Gegevens:</h2>
            <div class="p-1 bg-light text-dark rounded border border-info">
                <ul class="m-0 list-unstyled">
                    <li><span class="font-weight-bold text-info">Startdatum:</span> <span><?php echo $projectGegevens['ProjectStart']?></span></li>
                    <li><span class="font-weight-bold text-info">Deadline:</span> <span><?php echo $projectGegevens['ProjectDeadline']?></span></li>
                    <li><span class="font-weight-bold text-info">Status:</span>
                        <?php if($projectGegevens['Status'] == "Actief"){

                            echo'<span class="badge badge-success ml-1">Gestart</span>';

                        } else if ($projectGegevens['Status'] == "Inactief") {

                            echo'<span class="badge badge-danger ml-1">Niet Gestart</span>';

                        } else if ($projectGegevens['Status'] == "Afgerond") {

                            echo'<span class="badge badge-secondary ml-1">Klaar</span>';

                        } ?>
                    </li>
                    <li><span class="font-weight-bold text-info">Inschrijven na start:</span> <span><?php if($projectGegevens['InschrijvenNaStart'] == 1) {echo "Ja";} else {echo "Nee";}?></span></li>
                </ul>
                <hr class="my-1"/>
                <span class="font-weight-bold text-info">Projectleider:</span><br/>
                <a class="text-reset" href="profiel.php?id=<?php echo base64_encode($projectGegevens['ID']) ?>"><?php echo $projectGegevens['Voornaam'] . " " . $projectGegevens['Achternaam']?></a> (<span style="color:<?php echo $projectGegevens['Kleur']?>;"><?php echo $projectGegevens['OpleidingsNaam']?></span>)
            </div>
        </div>


        <div class="col-lg-7 py-3">

            <?php

            // Check wie de pagina bekijkt
            if ($projectGegevens['ID'] == $session_id) {
                /* =================
                 * PAGINA VOOR OWNER
                 * ================= */

                // Check of de rollen bestaan in de database
                if (mysqli_num_rows($rolQueryResult) > 0 ) {
                    // Als er rollen zijn voor dit project:

                    ?>

                    <!-- dropdown group -->
                    <ul class="list-group bg-light text-dark border rounded">

                        <?php

                        // Maak het dropdown aantal variabel
                        $dropdownNum = 0;

                        // Maak voor elke rol een dropdown
                        while($rolRij = mysqli_fetch_array($rolQueryResult)) {

                            // rolId
                            $rolID = $rolRij['ID'];

                            // Inschrijvingen ophalen
                            $inschrijvingenQuery = "
                                SELECT
                                    `gebruiker`.`ID`,
                                    `gebruiker`.`Voornaam`,
                                    `gebruiker`.`Achternaam`,
                                    `inschrijving`.`Status`
                                FROM `inschrijving`
                                INNER JOIN `gebruiker` ON `inschrijving`.`GebruikersID` = `gebruiker`.`ID`
                                WHERE
                                    `inschrijving`.`ProjectID` = $projectID AND
                                    `inschrijving`.`RollID` = $rolID
                                ORDER BY CASE `inschrijving`.`Status`
                                    WHEN 'Geaccepteerd' THEN 1
                                    WHEN 'Uitgenodigd' THEN 2
                                    WHEN 'Geintresseerd' THEN 3
                                    WHEN 'Afgewezen' THEN 4
                                    ELSE 5
                                END";
                            $inschrijvingenQueryResultaat = mysqli_query($mysqli, $inschrijvingenQuery);


                            // Inschrijf aantallen ophalen
                            $inschrijvingenAantalQuery = "
                                                SELECT
                                                    `project_rol_verband`.`Aantal` AS 'aantalPlaatsen',
                                                    SUM(CASE WHEN `inschrijving`.`Status` = 'Geaccepteerd' then 1 else 0 end) AS 'aantalGeaccepteerd',
                                                    SUM(CASE WHEN `inschrijving`.`Status` = 'Uitgenodigd' then 1 else 0 end) AS 'aantalUitgenodigd',
                                                    SUM(CASE WHEN `inschrijving`.`Status` = 'Geintresseerd' then 1 else 0 end) AS 'aantalGeintresseerd',
                                                    SUM(CASE WHEN `inschrijving`.`Status` = 'Afgewezen' then 1 else 0 end) AS 'aantalAfgewezen'
                                                FROM `inschrijving`
                                                INNER JOIN `project_rol_verband` ON `inschrijving`.`ProjectID` = `project_rol_verband`.`ProjectID` AND `project_rol_verband`.`RollID` = $rolID
                                                WHERE
                                                    `inschrijving`.`ProjectID` = $projectID AND
                                                    `inschrijving`.`RollID`= $rolID;
                                                ";
                            $inschrijvingAantal = mysqli_fetch_array(mysqli_query($mysqli, $inschrijvingenAantalQuery));

                            ?>

                            <!-- dropdown <?php echo $dropdownNum ?> -->
                            <button class="list-group-item list-group-item-action list-group-item-info active border-light rounded" type="button" data-toggle="collapse" data-target="#collapse<?php echo $dropdownNum ?>" aria-expanded="false">
                                <p class="m-0">
                                    <?php echo $rolRij['RolNaam'] ?>
                                    <span class="float-right font-weight-light">
                                    <!-- dropdown <?php echo $dropdownNum ?> status -->
                                    <span class="text-nowrap text-success" ><?php echo $inschrijvingAantal['aantalGeaccepteerd'];if($inschrijvingAantal['aantalGeaccepteerd'] == null){ echo "0";}?> / <?php echo $inschrijvingAantal['aantalPlaatsen'];?> Geaccepteerd</span> |
                                    <span class="text-nowrap text-warning" ><?php echo $inschrijvingAantal['aantalUitgenodigd'];if($inschrijvingAantal['aantalUitgenodigd'] == null){ echo "0";}?> Uitgenodigd</span> |
                                    <span class="text-nowrap text-light" ><?php echo $inschrijvingAantal['aantalGeintresseerd'];if($inschrijvingAantal['aantalGeintresseerd'] == null){ echo "0";}?> Geïntereseerd</span> |
                                    <span class="text-nowrap text-danger" ><?php echo $inschrijvingAantal['aantalAfgewezen'];if($inschrijvingAantal['aantalAfgewezen'] == null){ echo "0";}?> Afgewezen</span>
                                </span>
                                </p>
                            </button>
                            <li class="list-group-item p-0 border-0 rounded-0">
                                <div class="collapse" id="collapse<?php echo $dropdownNum ?>">

                                    <ul class="list-group list-group-flush">
                                        <!-- dropdown <?php echo $dropdownNum ?> contents -->
                                        <?php

                                        // Check of er inschrijvingen bestaan in de database
                                        if(mysqli_num_rows($inschrijvingenQueryResultaat) > 0){
                                            // Als er inschrijvingen bestaan voor deze rol:

                                            // Maak voor elke rol een list item aan
                                            while($inschrijving = mysqli_fetch_array($inschrijvingenQueryResultaat)){
                                                ?>

                                                <li class="list-group-item"><div>
                                                        <a href="profiel.php?id=<?php echo base64_encode($inschrijving['ID']); ?>" class="text-reset"><?php echo $inschrijving['Voornaam']." ".$inschrijving['Achternaam']; ?></a>

                                                        <?php
                                                        // Projectstatus
                                                        switch ($inschrijving['Status']){

                                                            case 'Geaccepteerd':
                                                                ?>
                                                                <span class="text-muted">(Geaccepteerd)</span>
                                                                <button type="button" class="btn btn-success float-right" disabled>Geaccepteerd</button>
                                                                <?php
                                                                break;
                                                            case 'Uitgenodigd':
                                                                ?>
                                                                <span class="text-muted">(Uitgenodigd)</span>
                                                                <button type="button" class="btn btn-warning float-right" disabled>Uitgenodigd</button>
                                                                <?php
                                                                break;
                                                            case 'Geintresseerd':
                                                                ?>
                                                                <span class="text-muted">(Geïnteresseerd)</span>
                                                                <a class="btn btn-outline-dark float-right" href="inschrijven_verwerk.php?Rol=<?php echo base64_encode('leider')?>&Projectid=<?php echo base64_encode($projectID)?>&Rollid=<?php echo base64_encode($rolRij['ID'])?>&Persoonid=<?php echo base64_encode($inschrijving['ID'])?>" role="button">Uitnodigen</a>
                                                                <?php
                                                                break;
                                                            case 'Afgewezen':
                                                                ?>
                                                                <span class="text-muted">(Afgewezen)</span>
                                                                <button type="button" class="btn btn-danger float-right" disabled>Afgewezen</button>
                                                                <?php
                                                                break;
                                                        }
                                                        ?>

                                                    </div></li>

                                                <?php
                                            }

                                        } else {
                                            // Als er geen inschrijvingen bestaan voor deze rol:

                                            echo "<li class='list-group-item text-italic'>Nog geen inschrijvingen gevonden</li>";

                                        }

                                        ?>
                                    </ul>

                                </div>
                            </li>

                            <?php

                            // Increase het dropdownaantal
                            $dropdownNum++;

                        }
                        ?>

                    </ul>

                    <?php

                } else {
                    // Als er geen rollen zijn voor dit project:
                    echo "<h2 class='text-danger font-weigth-bold'>Geen rollen gevonden!</h2>";
                }

            } else if($session_actief && $session_accounttype == 'Student') {
                /* ======================
                 * PAGINA VOOR STUDENTEN
                 * ====================== */

                ?>
                <div class="accordion" id="accordionExample">
                    <div class="card rounded border">
                        <?php

                        while ($rij = mysqli_fetch_array($rolQueryResult)){

                            // maak een var aan voor het roll id
                            $rollID = $rij['ID'];

                            // maak een var aan voor het tellen van inschrijvingen
                            $rollIDCount = $rij['ID'];

                            // maak een query voor het tellen van de ischrijvingen
                            $countQuery = "SELECT COUNT(`inschrijving`.`RollID`) AS total FROM `inschrijving`
                        WHERE `inschrijving`.`RollID` = $rollIDCount
                        AND `inschrijving`.`ProjectID` = $projectID";

                            // maak een result voor count
                            $countQueryResult = mysqli_query($mysqli, $countQuery);
                            $count = mysqli_fetch_array($countQueryResult);

                            // maak een query aan om te kijken of je al geitreseerd bent
                            $statusQuery = "SELECT `inschrijving`.`Status` FROM `inschrijving` WHERE `inschrijving`.`GebruikersID` = $session_id AND `inschrijving`.`RollID` = $rollID AND `ProjectID` = $projectID";

                            // maak een result aan voor intresse
                            $statusQueryResult = mysqli_query($mysqli,$statusQuery);

                            // maak een var aan voor het result
                            $status = mysqli_fetch_array($statusQueryResult);

                            ?>

                            <div class="card-header" id="headingOne">
                                <div class="row">
                                    <h2 class="mb-0 col-sm-7">
                                        <button  class="btn collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            <?php echo $rij['RolNaam'] . " " . $count['total'] . "/" . $rij['Aantal']  ?>
                                        </button></h2>

                                    <div class="col-sm-5 text-right">

                                        <!-- kijk of persoon is ingeschreven -->
                                        <?php

                                        // check naar de status van de inschrijving
                                        if($status['Status'] == "Geintresseerd") {
                                            // geef deze nog een kleur
                                            echo "<button type=\"button\" class=\"btn btn-outline-secondary ml-auto\">Ingeschreven</button>";
                                        }
                                        else if($status['Status'] == "Uitgenodigd") {
                                            // geef deze nog een kleur
                                            ?>
                                            <!-- link voor het accepteren van de opdracht -->
                                            <button type="button" class="btn btn-outline-secondary ml-auto"><a style="text-decoration: none; color: green" href="inschrijven_verwerk.php?Rol=<?php echo base64_encode('accepteerder')?>&Projectid=<?php echo base64_encode($projectID)?>&Rollid=<?php echo base64_encode($rij['ID'])?>&Persoonid=<?php echo base64_encode($projectGegevens['ID'])?>">inschrijven</a></button>
                                            <button type="button" class="btn btn-outline-secondary ml-auto"><a style="text-decoration: none; color: darkred" href="inschrijven_verwerk.php?Rol=<?php echo base64_encode('afweizen')?>&Projectid=<?php echo base64_encode($projectID)?>&Rollid=<?php echo base64_encode($rij['ID'])?>&Persoonid=<?php echo base64_encode($projectGegevens['ID'])?>">Uitschrijven</a></button>
                                            <?php
                                        }
                                        else if($status['Status'] == "Geaccepteerd"){
                                            // geef deze nog een kleur
                                            echo "<button type=\"button\" class=\"btn btn-outline-secondary ml-auto\">Geaccepteerd</button>";
                                        }
                                        else if($status['Status'] == "Afgewezen"){
                                            // geef deze nog een kleur
                                            echo "<button type=\"button\" class=\"btn btn-outline-secondary ml-auto\">Afgewezen</button>";
                                        } else{ ?>

                                            <button type="button" class="btn btn-outline-secondary ml-auto"><a style="text-decoration: none; color: <?php echo $row['Kleur'] ?>" href="inschrijven_verwerk.php?Rol=<?php echo base64_encode('inschrijver')?>&Projectid=<?php echo base64_encode($projectID)?>&Rollid=<?php echo base64_encode($rij['ID'])?>&Persoonid=<?php echo base64_encode($projectGegevens['ID'])?>">inschrijven</a></button>

                                        <?php } ?>
                                    </div>
                                </div>
                            </div>


                        <?php } ?>
                    </div>
                </div>

                <?php
            } else {
                /* =============================
                 * PAGINA VOOR OVERIGE SITUATIES
                 * ============================= */

                echo "<h2 class='font-weigth-bold d-block text-center mt-2'><a class='text-glr' href='inlog.php'>Log in</a> om in te schrijven</h2>";

            }
            ?>

        </div>
    </div>
    <div class="row flex-row-reverse">
        <div class="m-2">
            <?php if ($projectGegevens['ID'] == $session_id || $session_accounttype == "Administrator") {?>
                <a class="btn btn-outline-glr" href="project_bewerk.php?id=<?php echo base64_encode($projectID) ?>">Wijzig Project</a>
                <button class="btn btn-outline-danger" data-toggle="modal" data-target="#modal2">Verwijder Project</button>
                <?php if($session_accounttype == "Student"){
                    ?>
                    <button class="btn btn-outline-warning" id="afronden">Project Afronden</button>
                    <button class="btn btn-danger" id="AFRONDEN" style="display: none">AFRONDEN</button>
                    <?php
                } } else if ($session_actief) { ?>
                <button class="btn btn-danger" data-toggle="modal" data-target="#modal1">Rapporteer Project</button>
            <?php } ?>
        </div>
    </div>
</main>

<?php if($session_accounttype != "Administrator" && $session_actief || $session_id != $projectGegevens['ID'] && $session_actief ){ ?>
    <!-- maak een modal voor het rapoteren van een project -->
    <div class="modal fade" id="modal1" tabindex="1" role="dialog" aria-labelledby="modal1-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- header van het modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modal1-label">Rappoteer Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- body van het modal -->
                <div class="modal-body">
                    <!-- form -->
                    <form action="">
                        <div class="form-group">
                            <input type="hidden" id="projectID" value="<?php echo $projectID ?>">
                            <label for="reden">Reden:</label>
                            <select class="form-control" name="reden" id="reden">
                                <?php
                                foreach($deltypevals AS $item){
                                    echo "<option> " . $item . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="omschrijving">Omschrijving:</label>
                            <textarea class="form-control" name="omschrijving" id="omschrijving" cols="30" rows="10"></textarea>
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Annuleren</button>
                    <button type="button" class="btn btn-glr" id="rappoteer" data-dismiss="modal" aria-label="Close">Rappoteer</button>
                </div>
            </div>
        </div>
    </div>

<?php }if($session_accounttype == "Administrator" && $session_actief || $session_id == $projectGegevens['ID'] && $session_actief){ ?>

    <!-- maak een modal voor het verwijderen van een project -->
    <div class="modal fade" id="modal2" tabindex="2" role="dialog" aria-labelledby="modal2-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!-- header van het modal -->
                <div class="modal-header">
                    <h5 class="modal-title" id="modal2-label">Verwijder Project</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <!-- body van het modal -->
                <div class="modal-body">
                    <!-- form -->
                    <form action="">
                        <div class="form-group">
                            <input type="hidden" id="verwijderID" value="<?php echo $projectID ?>">
                            <label for="reden">ProjectNaam:</label>
                            <input type="text" style="width: 100%;"  value="<?php echo $projectGegevens['ProjectNaam'] ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="omschrijving">Omschrijving:</label>
                            <textarea class="form-control" style="width: 100%;" name="omschrijving" rows="10" readonly><?php echo $projectGegevens['Omschrijving']; ?></textarea>
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-dismiss="modal" aria-label="Close">Annuleren</button>
                    <button type="button" class="btn btn-danger" id="verwijder" data-dismiss="modal" aria-label="Close">
                        Verwijder
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php } ?>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- Fontawesome Icons -->
<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>
<!-- Extra Javascript -->
<script src="Javascript/rappoteer.js"></script>
<script src="Javascript/verwijder.js"></script>
<script src="Javascript/afronden.js"></script>
</body>
</html>