<?php
    // include het session bestand
    require_once "session.inc.php";

    // test of er al een sessie bestaat
    if($session_actief){

        // als er al een sessie bestaat:

        // redirect naar de homepagina
        header("location:(old)index.php");
        exit();

    }

    // haal eventuele GET gegevens op
    $msg = $_GET['msg'];
    $email = $_GET['email'];

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Extra CSS -->
    <link rel="stylesheet" href="stylesheet/inlog_style.css">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Title -->
    <title>Inloggen</title>
</head>
<body class="d-flex flex-column justify-content-center align-items-center">
<div id="logincard" class="p-3 border rounded-lg shadow bg-light">
    <div class="container-fluid">
        <div class="row my-4">
            <h1 class="m-0">Inloggen</h1>
            <img class="border d-block ml-auto" src="images/glrhuisstijl/GLR_logo.jpg" height="100px" width="100px">
        </div>
    </div>
    <form action="inlog_verwerk.php" method="post" class="container-fluid">
        <div class="row my-1"><label for="mail" class="col-sm-5">Email: </label><input class="col-sm-7" type="email" name="mail" id="mail" placeholder="email" value="<?php echo $email ?>" required></div>
        <div class="row my-1"><label for="wachtwoord" class="col-sm-5">Wachtwoord: </label><input class="col-sm-7" type="password" name="wachtwoord" id="wachtwoord" placeholder="wachtwoord" required></div>
        <div class="row"><input class="btn btn-primary col-6 my-2 mr-auto" name="submit" type="submit" value="inloggen"></div>
    </form>
    <a class="d-inline-block my-2" href="index.php">Doorgaan zonder in te loggen</a>
</div>

<?php if (isset($_GET['msg'])) { ?>
    <div class="mt-4 shadow alert alert-danger alert-dismissible fade show" role="alert">
        <?php

            // echo de bijbehorende message
            switch($msg){
                case 1:
                    echo "Email of Wachtwoord Incorrect";
                    break;
                case 2:
                    echo "Niet alle velden zijn ingevuld";
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

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>