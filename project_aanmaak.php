<?php

//Commentaar door:
//Kelvin
//Marijn
//Brian
//Jos

// include de sessie
require_once "session.inc.php";
// include de config
require "config.inc.php";

// test of de grbuiker wel een student is
if($session_actief == false || $session_accounttype != "Student"){
    header("location:(old)index.php");
}

// haal eventuele get gegevens op
$msg            = $_GET['msg'];
$projectNaam    = $_GET['projectNaam'];
$startDatum     = $_GET['startDatum'];
$deadline       = $_GET['deadline'];
$omschrijving   = $_GET['omSchrijving'];
$inschrijven    = $_GET['inschrijven'];

// maak een qeury om de naam, kleur en ID van de opleiding te pakken
$opleidingQuery =
"SELECT
    `opleiding`.`OpleidingsNaam`,
    `opleiding`.`Kleur`,
    `opleiding`.`ID`
FROM `opleiding`";
// maak een result voor opleidingen
$opleidingQueryResult = mysqli_query($mysqli, $opleidingQuery);

// haal alle rollen op en zet ze in een array
$allerollenQuery =
"SELECT
	`rol`.`ID`,
	`rol`.`RolNaam`
FROM `rol`";
$allerollenQueryResultaat = mysqli_query($mysqli, $allerollenQuery);
$allerollen = array();
while($currentRol = mysqli_fetch_array($allerollenQueryResultaat)) {
    $allerollen[$currentRol['ID']] = $currentRol['RolNaam'];
}

?>
<!doctype html>
<html lang="en">
<head>
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
<!-- Header -->
<?php require_once("header.php"); // require de header ?>

<!-- Main Container -->
<main class="container bg-dark text-white rounded-bottom">

    <!-- De titel bovenaan de container-->
    <h2 class="pa_title">Project Aanmaken</h2>

    <!-- Begin een form -->
    <form action="project_aanmaak_verwerk.php" method="post">
        <div class="form-row">
            <!-- Begint de linker kolom -->
            <div class="col-lg-6 p-3">
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
                <div class="form-check mt-1 mx-2 clearfix">
                    <div class="float-right">
                        <input class="form-check-input" type="checkbox" id="inschrijvenNaStartCheckbox" name="inschrijvenNaStart" <?php if($inschrijven == 1){ ?> checked <?php } ?>>
                        <label class="form-check-label" for="inschrijvenNaStartCheckbox">inschrijven na start toestaan</label>
                    </div>
                </div>
                <div>
                    <!-- Maak een dateveld voor de deadline -->
                    <p class="pa_text mt-0">Opdracht Deadline:</p>
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
            <div class="col-lg-6 p-4">

                <!-- dropdown group -->
                <ul class="list-group">

                    <?php

                    $dropdownNr = 0;

                    while ($opleiding = mysqli_fetch_array($opleidingQueryResult)){

                        $opleidingID = $opleiding['ID'];
                        // maak een rol query aan
                        $opleidingsRollenQuery = "SELECT `rol`.`RolNaam`, `rol`.`ID` AS RolID
                                    FROM `opleiding`
                                    INNER JOIN `opleiding_rol_verband` ON `opleiding_rol_verband`.`OpleidingsID` = `opleiding`.`ID`
                                    INNER JOIN `rol` ON `rol`.`ID` = `opleiding_rol_verband`.`RollID`
                                    WHERE `opleiding_rol_verband`.`OpleidingsID` = $opleidingID";
                        // maak een result voor rol
                        $opleidingsRollen = mysqli_query($mysqli, $opleidingsRollenQuery);

                        ?>

                        <!-- dropdown <?php echo $dropdownNr; ?> -->
                        <button class="list-group-item list-group-item-action list-group-item-info rounded-0 text-light border-0" style="background-color: <?php echo $opleiding['Kleur'] ?>; border-color: <?php echo $opleiding['Kleur'] ?>;" type="button" data-toggle="collapse" data-target="#collapse<?php echo $dropdownNr; ?>" aria-expanded="false">
                        <?php echo $opleiding['OpleidingsNaam']; ?>
                        </button>
                        <li class="list-group-item p-0 rounded-0 border-0">
                            <div class="collapse" id="collapse<?php echo $dropdownNr; ?>">

                                <ul class="list-group list-group-flush">

                                    <!-- dropdown <?php echo $dropdownNr; ?> contents -->
                                    <?php while ($rol = mysqli_fetch_array($opleidingsRollen)) {

                                        ?>
                                        <li class="list-group-item text-dark">
                                            <?php echo $rol['RolNaam']; ?>
                                            <input type='number' min='0' max='5' class='form-control rolInput' name="rol-<?php echo $rol['RolID']; ?>" style='width: 50px; height: 30px; float: right;'>
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
                    <label for="rolOverview">Team Overview</label>
                    <textarea class="form-control result" id="rolOverview" rows="10" readonly></textarea>
                </div>

                <!-- Buttons -->
                <button type="button submit" class="btn btn-primary">Opslaan</button>
                <a href="index.php" class="btn btn-danger">Cancel</a>
            </div>
        </div>
    </form>

    <?php if (isset($_GET['msg'])) { // check of er een msg is ?>
        <!-- Alert Message-->
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

</main>

<!--Scripts-->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>
<!-- custom script -->
<script>
    $(document).ready(function () {

        let alleRollen = <?php echo json_encode($allerollen) ?>;
        let rolAantal = [];
        let overviewText = "";

        let rolIDOverview = [];
        let actieveRollen;

        $(".rolInput").bind("change paste keyup", function () {

            actieveRollen = "";
            let rolId = $(this).attr("name").replace('rol-', '');
            let rolVal = $(this).val();

            rolAantal[rolId] = rolVal;

            if(rolVal == 0 || rolVal == "e" || rolVal =="E" || isNaN(rolVal)){

                $(this).val("");
                rolAantal.splice(rolId);
                if(rolIDOverview.includes(rolId)){
                    rolIDOverview.forEach(removeCurrentId);
                }

            }
            else if(rolIDOverview.includes(rolId) == false){
                rolIDOverview.push(rolId);
                rolIDOverview.sort((a,b) => a - b);
                console.log(rolIDOverview);
            }

            rolIDOverview.forEach(rolArrayToString);

            actieveRollen = actieveRollen.slice(0,-1);
            $("#actieveRollen").val(actieveRollen);

            overviewText = "";
            rolIDOverview.forEach(writeOverview);
            $("#rolOverview").text(overviewText);

            function rolArrayToString(item, index){
                actieveRollen += item + "-";
            }

            function removeCurrentId(item, index){
                if(item == rolId){
                    rolIDOverview.splice(index, 1);
                }
            }

            function writeOverview(item, index) {
                overviewText += alleRollen[item] + " = " + rolAantal[item] + "\r\n";
            }

        });

    });
</script>
</body>
</html>
