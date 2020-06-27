<?php
// require de sessie
require_once("session.inc.php");
// require de config
require_once("config.inc.php");

$query = "SELECT `GebruikersID`,`Biografie`,`Website`,`Profielfoto`,`AlternatieveEmail`,`TelefoonNummer`,`Geregistreerd` FROM `profiel` WHERE `GebruikersID` = $session_id";

// Voer de query uit
$profielQuery = mysqli_query($mysqli, $query);

$row = mysqli_fetch_array($profielQuery);

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
<?php
if($row['Geregistreerd'] == true) {
    require_once("header.php"); // require de header
}
?>
    <div class="container bg-dark text-white" style="padding-bottom: 5px; padding-top: 15px; border-bottom-left-radius: .35rem; border-bottom-right-radius: .35rem;">

        <div style="text-align: center">
        <?php
        if($row['Geregistreerd'] != true){
            echo '<h2>Account Personaliseren.</h2>';
            echo '<p>Voordat u doorgaat naar de website willen wij u vragen om dit formulier in te vullen om uw account compleet te maken!</p>';

        } else {

            echo '<h2>Account Aanpassen</h2>';
            echo '<p>breng hier aanpassingen aan op uw gepersonaliseerde profiel.</p>';

        }

        ?>
        </div>
        <div class="container">
            <div class="row justify-content-around">

                        <div class="col md-2 offset-md-2">
                            <form action="profiel_aanpassen_verwerk.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <?php echo '<img style="border-radius:50%;"  src="images/profielfotos/' . $row['Profielfoto'] . '" height="200px" width="200px" >' ?>
                            <br><br>
                            <input type="file" name="foto" id="foto" placeholder="<?php echo '<img src="images/profielfotos/' . $row["Profielfoto"] . '' ?>">
                        </div>
                    </div>

                <div class="col md-2 offset-md-1">


                            <input type="text" name="id" value="<?php echo $session_id ?>" hidden readonly></td>
                    <br>
                            <label for="biografie">Info over Mezelf:</label>
                            <br>
                            <textarea class="form-control" name="biografie" id="biografie" maxlength="500" style="max-width: 500px;"><?php echo $row['Biografie']; ?></textarea>
                            <h6 class="pull-right" id="count_message"></h6>



                            <label for="website">Mijn website:</label>
                            <br>
                            <input type="text" class="form-control" name="website" id="website" value="<?php echo $row['Website']; ?>" style="max-width: 500px;">



                            <label for="alt-email">Alternatieve E-Mail:</label>
                            <br>
                            <input type="text" class="form-control" name="alt-email" id="alt-email" value="<?php echo $row['AlternatieveEmail']; ?>" style="max-width: 500px;">



                            <label for="telefoon">Telefoonnummer:</label>
                            <br>
                            <input type="text" class="form-control" name="telefoon" id="telefoon" value="<?php echo $row['TelefoonNummer']; ?>" style="max-width: 500px;">
                    <br>

                            <input type="submit" name="submit" id="submit" value="submit" class="btn btn-glr">
                    <?php if($row['Geregistreerd'] == true)
                    {
                        echo'<button class="btn btn-danger" onclick="history.back();return false;">Annuleren</button>';
                    }
                    ?>
                        </form>
                    </div>

            </div>
        </div>
    </div>



<!-- include de jquery en javascurpt -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="Javascript/bioCounter.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>