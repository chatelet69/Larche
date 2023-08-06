<?php
try {
    include("/var/www/confc.php");
    $sql = 'mysql:host=' . $dbhost . ';dbname=' . $dbname.''; // Requête de connexion à la DB
    $bdd = new PDO("$sql", $dbuser, $dbpass); // On crée l'instance PDO
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>