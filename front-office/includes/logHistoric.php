<?php
include("/var/www/html/includes/db-connect.php");
$logs = $bdd->prepare('INSERT INTO historic (recherche, date, user_id) VALUES (?, ?, ?)');
$recherche = basename($_SERVER['REQUEST_URI']); // Renvoit le nom de la page ou la requête a été faite
$email = $_SESSION['email'];
$logs->execute([$recherche, date("Y-m-d H:i:s"), $_SESSION['user_id']]); // On exec

?>