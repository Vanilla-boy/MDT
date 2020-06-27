<?php
// database inloggevens
$db_hostname = 'localhost';
$db_username = 'mdtbc8';
$db_password = '#2Geheim';
$db_database = 'MDT_Database_bc8';

// maak de dataverbinding
$mysqli = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);

// als verbinding niet gemaakt kan worden:geef een melding
if(!$mysqli){
    echo"Fout: geen connectie naar database <br>";
    echo"Error:" . mysqli_connect_error() . "<br>";
    echo"Errno:" . mysqli_connect_error() . "<br>";
    exit;
}

?>