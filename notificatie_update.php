 <?php

 // lees het config bestand uit
 require "config.inc.php";

 // haal de var uit het js bestand
 $id = $_POST['varID'];

 echo $id;

 // maak een qeury
 $query = "UPDATE `notificatie` SET `Gelezen` = 1 WHERE `notificatie`.`ID` = '$id';";

 // voer de query uit
 mysqli_query($mysqli, $query);
 ?>
