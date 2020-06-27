<?php

// include de config
require "config.inc.php";

if(isset($_POST['action'])){
    $query = "SELECT
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
        INNER JOIN `rol` ON `project_rol_verband`.`RollID` = `rol`.`ID` WHERE ";

    if(isset($_POST['rol']) && !empty($_POST['rol'])){
        $rol_filter = implode("','",$_POST['rol']);
        $query .= "`project`.`status` = 'Inactief' AND `rol`.`RolNaam` IN('". $rol_filter ."') OR
            `project`.`Status` = 'Actief' AND `project`.`InschrijvenNaStart` = 1 AND
            `rol`.`RolNaam` IN('". $rol_filter ."')";
    }

    if(!isset($_POST['rol']) && empty($_POST['rol'])) {
        $query .= "`project`.`status` = 'Inactief' OR
            `project`.`Status` = 'Actief' AND `project`.`InschrijvenNaStart` = 1 ";
    }

    $query .= "ORDER BY `project`.`ID` DESC;";

    $filterQuery = mysqli_query($mysqli, $query);

    $total_row = mysqli_num_rows($filterQuery);
    $output = "";

    if($total_row > 0){
        While ($row = mysqli_fetch_array($filterQuery)){

            if($row["Status"] == "Actief"){

                $status = '<span class="badge badge-success ml-1">Gestart</span>';

            } else if ($row['Status'] == "Inactief") {

                $status = '<span class="badge badge-danger ml-1">Niet Gestart</span>';

            }else if ($row['Status'] == "Afgerond") {

                $status = '<span class="badge badge-secondary ml-1">Klaar</span>';

            }

            $omschrijving = $row['Omschrijving'];
            if(strlen($omschrijving) > 150) {
                $omschrijving = substr($omschrijving,0, 150);
                $omschrijving = $omschrijving . "...";
            }

            $output .= '<div class="col-sm-12 my-3">
            <div class="card rounded-0">
                <div class="card-body">
                    <h5 class="card-title">' . $row["ProjectNaam"] .' ' . $status . '</h5>
                    <h6 class="card-subtitle text-muted mb-1">Project Start: '. $row["ProjectStart"] .' </h6>
                    <hr class="my-2"/>
                    <p class="card-text omschrijving">'. $omschrijving .'
                        <a href="project_overzicht.php?id='. base64_encode($row["ID"]).'">Meer lezen</a>
                    </p>
                    <a href="project_overzicht.php?id='. base64_encode($row["ID"]).'" class="btn btn-lg btn-lg btn-glr">meer info</a>
                </div>

                <div class="card-footer text-muted">
                    Door <a style="color: #ABCD00" href="profiel.php?id= '. base64_encode($row["GebruikerID"]) .'"><b> '. $row["Voornaam"] . ' ' . $row["Achternaam"] .'</b></a> (<span style="color: '. $row["Kleur"].'">'. $row["OpleidingsNaam"].'</span>)
                </div>
            </div>
        </div>';
        }
    }
    else {
        $output = "<h1 class='text-glr text-center mt-5'>Geen projecten gevonden</h1>";
    }

    echo $output;
}

?>