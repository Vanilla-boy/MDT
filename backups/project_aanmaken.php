<?php

//Commentaar door:
//Kelvin
//Marijn
//Brian



// include de sessie
require_once "session.inc.php";
// include de config
require "config.inc.php";

// haal eventuele get gegevens op
$msg            = $_GET['msg'];
$projectNaam    = $_GET['projectNaam'];
$startDatum     = $_GET['startDatum'];
$deadline       = $_GET['deadline'];
$omschrijving   = $_GET['omSchrijving'];

// maak een qeury om de naam en kleur van de opleiding te pakken

$opleidingQuery = " SELECT `opleiding`.`OpleidingsNaam`,`opleiding`.`Kleur`,`opleiding`.`Omschrijving`,`opleiding`.`ID`
                    FROM `opleiding`
                   ";

// maak een query aan voor het tellen van de rollen
$rolCountQuery = "SELECT COUNT(`ID`) AS rolCount FROM `rol`";

// maak een result aan voor de query
$rolCountQueryResult = mysqli_query($mysqli, $rolCountQuery);

// haal de gegevens uit het array
$countRow = mysqli_fetch_array($rolCountQueryResult);


// maak een result voor opleidingen
$opleidingQueryResult = mysqli_query($mysqli, $opleidingQuery);

$i = -1;

if($session_actief == false || $session_accounttype != "Student"){
    header("location:(old)index.php");
}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- scripts... again -->
    <!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>


    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSS -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet/pa_style.css">

    <!-- Fontawesome Icons -->
    <script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Title -->
    <title>Project aanmaken</title>
</head>

<body>
<!-- header -->
<?php require_once("header.php"); // require de header ?>

<!-- De zwarte container op de achtergrond -->
<div class="container bg-dark text-white" style="border-bottom-left-radius: .35rem; border-bottom-right-radius: .35rem;">

    <!-- De titel bovenaan de container-->
    <h2 class="pa_title">Project Aanmaken</h2>

    <!-- Begin een form -->
    <form action="project_aanmaak_verwerk.php" method="post">
        <div class="form-row">
            <!-- Begint de linker kolom -->
            <div class="col p-3">
                <div>
                    <!-- Maak een tekstveld voor de projectnaam -->
                    <p class="pa_text">Project Naam:</p>
                    <input type="text" class="form-control pa_text_veld" name="projectNaam" placeholder="Project Naam" value="<?php echo $projectNaam ?>" maxlength="64">
                </div>
                <div>
                    <!-- Maak een dateveld voor de startdatum -->
                    <p class="pa_text">Opdracht Startdatum:</p>
                    <input type="date" class="form-control pa_text_veld" name="startDatum" placeholder="yyyy/mm/dd" value="<?php echo $startDatum ?>">
                </div>
                <div>
                    <!-- Maak een dateveld voor de deadline -->
                    <p class="pa_text">Opdracht Deadline:</p>
                    <input type="date" class="form-control pa_text_veld" name="deadline" placeholder="yyyy/mm/dd" value="<?php echo $deadline ?>">
                </div>
                <div>
                    <!-- Maak een textbox voor de beschrijving -->
                    <p class="pa_text">Opdracht Beschrijving:</p>
                    <textarea rows="8" class="form-control pa_text_veld" name="omschrijving"  placeholder="Opdracht Beschrijving"><?php echo $omschrijving ?></textarea>
                </div>
                <input type="hidden" id="actieveRollen" value="" name="rollen" readonly>
            </div>

            <!-- Begint de 2de kolom -->
            <div class="col p-4">

                <!-- dropdown group -->
                <ul class="list-group">

                    <?php

                    $dropdownNr = 0;

                    while ($opleiding = mysqli_fetch_array($opleidingQueryResult)){

                        $opleidingID = $opleiding['ID'];
                        // maak een rol query aan
                        $rolQuery = "SELECT `rol`.`RolNaam`, `rol`.`ID` AS RolID
                                    FROM `opleiding`
                                    INNER JOIN `opleiding_rol_verband` ON `opleiding_rol_verband`.`OpleidingsID` = `opleiding`.`ID`
                                    INNER JOIN `rol` ON `rol`.`ID` = `opleiding_rol_verband`.`RollID`
                                    WHERE `opleiding_rol_verband`.`OpleidingsID` = $opleidingID";
                        // maak een result voor rol
                        $rollen = mysqli_query($mysqli, $rolQuery);

                        ?>

                        <!-- dropdown <?php echo $dropdownNr; ?> -->
                        <button class="list-group-item list-group-item-action list-group-item-info rounded-0 text-light border-0" style="background-color: <?php echo $opleiding['Kleur'] ?>; border-color: <?php echo $opleiding['Kleur'] ?>;" type="button" data-toggle="collapse" data-target="#collapse<?php echo $dropdownNr; ?>" aria-expanded="false">
                        <?php echo $opleiding['OpleidingsNaam']; ?>
                        </button>
                        <li class="list-group-item p-0 rounded-0 border-0">
                            <div class="collapse" id="collapse<?php echo $dropdownNr; ?>">

                                <ul class="list-group list-group-flush">

                                    <!-- dropdown <?php echo $dropdownNr; ?> contents -->
                                    <?php while ($rol = mysqli_fetch_array($rollen)) {

                                        ?>
                                        <li class="list-group-item text-dark">
                                            <?php echo $rol['RolNaam']; ?>
                                            <input type='number' min='0' max='5' class='form-control rolInput' id="<?php echo $rol['RolID']; ?>" name="rol-<?php echo $rol['RolID']; ?>" style='width: 50px; height: 30px; float: right;'>
                                            <!-- Martijn's Magische Script -->
                                            <script>
                                                $(document).ready(function () {

                                                    // checkt wanneer je op een nummerveld click die overeenkomt met een van de rollideeën in de database
                                                    $("#<?php echo $rol['RolID']; ?>").click(function()
                                                    {
                                                        <?php

                                                        // dit zorgt dat de code binnen de loop herhaalt word het aantal keer dat gelijk is aan het aantal rollen in de database
                                                        for($x = 0; $x <= $countRow['rolCount']; $x += 1){
                                                        ?>

                                                        // maak het aantal variabels aan gelijk aan de hoeveelheid rollen met de naam: "amount+nummer van de loop" en als value de waarde van het nummerveld met een id gelijk aan het nummer van de herhaling
                                                        var amount<?php echo $x; ?> = $("#<?php echo $x; ?>").val();

                                                        //log alle variabels in de console
                                                        console.log("amount<?php echo $x; ?> =" + amount<?php echo $x; ?>);
                                                        <?php
                                                        }
                                                        ?>

                                                        // stuur de binnenstaande dingen naar project aanmaken Ajax
                                                        $.post("project_aanmaken_AJAX.php", {

                                                            // dit zorgt dat de code binnen de loop herhaalt word het aantal keer dat gelijk is aan het aantal rollen in de database
                                                            <?php for($y = 0; $y <= $countRow['rolCount']; $y += 1){ ?>

                                                            // dit stuurt de hiervoor gemaakte variabels door met het nummer van de het nummerveld waar het variabel in zat
                                                            amountResult<?php echo $y; ?>: amount<?php echo $y; ?>, NUM: <?php echo $y; ?>,
                                                            <?php } ?>
                                                        })

                                                        // als de hievoorstaande code gelukt is / correcte code van de php pagina krijgt:
                                                            .done(function (dataVanPHP) {

                                                                // zet de code van de php pagina in de div met de result class
                                                                $(".result").html(dataVanPHP);

                                                            })
                                                            // als de hievoorstaande code niet gelukt is / incorrecte code van de php pagina krijgt:
                                                            .fail(function(xhr, status, error) {

                                                                // zet de code van de php pagina in de div met de result class
                                                                $(".result").html("FOUT BIJ LADEN!");
                                                            });
                                                    });
                                                });
                                            </script>
                                        </li>
                                        <?php

                                    }?>

                                </ul>

                            </div>
                        </li>

                        <?php

                        // voeg 1 toe aan dropdownNr
                        $dropdownNr++;
                    }?>

                </ul>

                <!-- Team Overview textbox -->
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Team Overview</label>
                <textarea class="form-control result" id="exampleFormControlTextarea1" rows="10" readonly></textarea>
            </div>

                <!-- Buttons -->
            <button type="button submit" class="btn btn-primary">Opslaan</button>
            <a href="(old)index.php" class="btn btn-danger">Cancel</a>
        </div>
    </div>
</form>

    <?php
        // check of er een msg is
        if (isset($_GET['msg'])) {

    ?>

        <div class="m-4 mb-5 shadow alert alert-danger alert-dismissible fade show fixed-bottom" role="alert">
            <?php

            // echo de bijbehorende message
            switch($msg){
                case 1:
                    echo "Het project naam is leeg of langer dan 64 characters";
                    break;
                case 2:
                    echo "Deze datum is al geweest, kies een andere datum";
                    break;
                case 3;
                    echo "De startDatum is verplicht intevullen";
                    break;
                case 4;
                    echo "De Deadline moet na de startdatum komen";
                    break;
                case 5;
                    echo "De omschrijving van je project moet je invullen";
                    break;
                case 6;
                    echo "Je moet minimaal 1 rol door geven";
                    break;
                case 7;
                    echo "De Startdatum moet een datum zijn";
                    break;
                case 8;
                    echo "De deadline moet een datum zijn";
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

</div>
</body>
<!--Scripts-->
<script>
    $(document).ready(function () {

        let rollen = [];
        let actieveRollen;

        $(".rolInput").bind("change paste keyup", function () {

            actieveRollen = "";
            let rolId = $(this).attr("id");
            let rolVal = $(this).val();

            if(rolVal == 0 || rolVal == "e" || rolVal =="E" || isNaN(rolVal)){

                $(this).val("");
                if(rollen.includes(rolId)){
                    rollen.forEach(removeCurrentId);
                }

            }
            else if(rollen.includes(rolId) == false){
                rollen.push(rolId);
                rollen.sort((a,b) => a - b);
                console.log(rollen);
            }
            else{
                console.log(rollen);
            }

            rollen.forEach(rolArrayToString);

            actieveRollen = actieveRollen.slice(0,-1);
            $("#actieveRollen").val(actieveRollen);

            function rolArrayToString(item, index){
                console.log(item + " " + index);
                actieveRollen += item + "-";
            }

            function removeCurrentId(item, index){
                if(item == rolId){
                    rollen.splice(index, 1);
                }
            }

        });

    });
</script>
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>
<!--<script src="Javascript/project_aanmaken.js"></script>-->
</html>
