<?php
// include de sessie
require "session.inc.php";
// include de config
require "config.inc.php";

// Maak een query voor het project start datum
$projectStartQuery = "
        SELECT
            `project`.`ID`,
            `project`.`ProjectStart`
        FROM `project`";
// Voer de query uit
$projectStartQueryResult = mysqli_query($mysqli, $projectStartQuery);

?>
<!doctype html>
<html lang="en">
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
    <title>Let's make some projects</title>
</head>
<body>

<?php
// include header
include "header.php";
?>

<div class="container-fluid">
    <div class="row">

        <!-- Deze bij de oplevering even weg hale
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
    <?php } ?>
 tot hier -->
        <!-- rol filter -->
        <div class="col-12 col-md-5 col-lg-4 col-xl-3 bg-light filter-screen">
            <div class="card bg-light mb-3">
                <div class="card-header">
                    <h1 class="text-center text-glr">Filter op Rol</h1>
                </div>

                <div class="card-body">

                    <div class="container-fluid filter-list">
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
                                <div class="list-group-item checkbox" style="width: 100%">
                                    <label><input type="checkbox" class="common-selector rol" value="<?php echo $filter['RolNaam'] ?>"> <?php echo "<span style='color:". $filter['Kleur'] .";'>" . $filter['RolNaam'] . "</span>" ?></label>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container col-sm-12 col-md-7 col-lg-8 col-xl-9 filter_data">
            <?php
            // loop door alle opdrachten heen
            while ($row = mysqli_fetch_array($projectStartQueryResult)){

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
            }

            ?>
        </div>

    </div>
</div>


<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<!-- Fontawesome Icons -->
<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>
<!-- Extra Javascript -->
<script>

    $(document).ready(function () {

        filter_data();

        function filter_data() {
            var action = 'fetch_data';
            var rol = get_filter('rol');

            $.ajax({
                url: "index_filter.php",
                method:"POST",
                data: {action:action, rol:rol},
                success:function (data) {
                    $('.filter_data').html(data);
                }
            })
        }

        function get_filter(class_name) {
            var filter = [];
            $('.'+class_name+':checked').each(function () {
                filter.push($(this).val());
            })
            return filter;
        }

        $(".common-selector").click(function () {
            filter_data();
        })
    })

</script>

</body>
</html>