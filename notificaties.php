<html>
<title>Hello World</title>
<link rel="stylesheet" href="stylesheet/glrhuisstijl.css">
<body>

<?php

// include de sessie
require_once "session.inc.php";

// inlude het config bestand
require "config.inc.php";

// inlude de header
include "header.php";

// een query variable
$notificatieQuery = "SELECT `ID` , `Notificatie`,`Pagina`,`Gelezen` FROM `notificatie` WHERE GebruikersID = '$session_id' AND `notificatie`.`Gelezen` = 0";
$notificatieResult = mysqli_query($mysqli, $notificatieQuery);
?>
<div class="container border-dark border rounded-bottom border-top-0">
    <div class="row">
        <div class="col-lg-7 mt-3 mb-3">
            <h1>New</h1>
        <?php
            // ga alle notificaties langs
            while ($row = mysqli_fetch_array($notificatieResult)){
                // check of de notificatie gelezen is
                if ($row['Gelezen'] == false){
                    //echo "ID: " . $row['ID'] . "<br>";
                    ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Notificatie msg:</h6>
                            <?php
                            echo '<p class="card-text">' . $row["Notificatie"] . '</p>';
                            //echo "Notificatie msg: " . $row['Notificatie'] . "<br>";

                            if ($row['Pagina'] == "") {
                                echo "Link naar een Project is niet aanwezig <br>";
                            } else {
                                ?>
                                Link naar Project <a href='<?php echo $row['Pagina']; ?>'>Ga naar Project</a><br>
                                <?php
                            }
                            // voeg een knop toe om hem op gelezen te zetten
                            ?>
                            <button class='gelezen btn-glr' value=' <?php echo $row['ID'] ?>'>Gelezen</button>
                            <br>
                        </div>
                    </div>
                <?php
                }

                // kijk of de notificatie gelezen is
            }





        ?></div>
        <div class="col-lg-5 mt-3 mb-3">
            <h1>All gelezen</h1><?php
            // ga alle notificaties langs
            $notificatieQuery1 = "SELECT `ID` , `Notificatie`,`Pagina`,`Gelezen` FROM `notificatie` WHERE GebruikersID = '$session_id' AND `notificatie`.`Gelezen` = 1 ORDER BY ID DESC LIMIT 4";
            $notificatieResult1 = mysqli_query($mysqli, $notificatieQuery1);
            while ($row1 = mysqli_fetch_array($notificatieResult1)){
                // check of de notificatie gelezen is
                if ($row1['Gelezen'] == true){
                    //echo "ID: " . $row['ID'] . "<br>";
                    ?>
                    <div class="card" >
                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Notificatie msg:</h6>
                            <?php
                            echo '<p class="card-text">' . $row1["Notificatie"] . '</p>';
                            //echo "Notificatie msg: " . $row['Notificatie'] . "<br>";

                            if ($row1['Pagina'] == "") {
                                echo "Link naar een Project is niet aanwezig <br>";
                            } else {
                                ?>
                                Link naar Project <a href='<?php echo $row1['Pagina']; ?>'>Ga naar Project</a><br>
                                <?php
                            }
                            // voeg een knop toe om hem op gelezen te zetten
                            ?>
                            <br>
                        </div>
                    </div>
                    <?php
                }

                // kijk of de notificatie gelezen is
            }
        ?></div></div> </div>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<!-- Optional JavaScript -->

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<!-- Ajax van deze pagina -->
<script src="Javascript/notificatie.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<!-- Font awesome icons -->
<script src="https://kit.fontawesome.com/ca14c2ceea.js" crossorigin="anonymous"></script>

</body>
</html>