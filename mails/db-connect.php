<?php
try {
    include("/var/www/confc.php");
    $sql = 'mysql:host=' . $dbhost . ';dbname=' . $dbname . ''; // Requête de connexion à la DB
    $bdd = new PDO("$sql", $dbuser, $dbpass); // On crée l'instance PDO
    //$bdd = new PDO("mysql:host=".$dbhost.";dbname=".$dbname."", $dbuser, $dbpass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>