<?php
require_once("head.php");
require_once("database-connection.php");
$sql = "SELECT * FROM pokemon WHERE nom LIKE '%" .$_GET["q"]. "%'";
$res = $databaseConnection->query($sql)->fetch_all(MYSQLI_ASSOC);
foreach ($res as $row){
    echo '<p>'.$row['nom'].'</p>';
    echo '<p>'.$row["IdPokemon"].'</p>';
    echo '<img src="'. $row["UrlPhoto"] .'"alt="">' ;
}


require_once("footer.php");
?>