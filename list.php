<?php
// Connexion à la base de données



require_once("head.php");
require_once("database-connection.php");

$result = $databaseConnection -> query ( "SELECT * FROM pokemon");
while ( $row =$result ->fetch_assoc()) {
    echo '<p>'.$row['nom'].'</p>';
    echo '<p>'.$row['IdPokemon'].'</p>';
    echo '<img src="'. $row["UrlPhoto"] .'"alt="">' ;
    
}




require_once("footer.php")
?>