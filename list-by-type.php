<?php

require_once("head.php");
require_once("database-connection.php");

$databaseConnection = new mysqli("localhost", "root", "", "pokemon");
if ($databaseConnection->connect_error) die("Connexion échouée: " . $databaseConnection->connect_error);
$databaseConnection->set_charset("utf8");

$typesResult = $databaseConnection->query("SELECT idType, nomtype FROM types ORDER BY nomtype");

while($typeRow = $typesResult->fetch_assoc()) {
    echo '<h3>'.$typeRow['nomtype'].'</h3>';
    
    $result = $databaseConnection->query("SELECT * FROM pokemon WHERE idType1 = ".$typeRow['idType']);
    while ($row = $result->fetch_assoc()) {
        echo '<p>'.$row['nom'].'</p>';
        echo '<p>'.$row['IdPokemon'].'</p>';
        echo '<img src="'. $row["UrlPhoto"] .'"alt="">';
    }
    echo '<hr>';
}
    
require_once("footer.php")
?>


