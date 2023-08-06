<?php
    include("/var/www/staff/includes/db-connect.php");
    $sqlHistoric = $bdd->prepare("INSERT INTO historic (recherche, date, user_id) VALUES (:recherche, :date, :user_id)");
    $type = "page";
    /*if ((strpos($_SERVER['REQUEST_URI'], "includes")) || (strpos($_SERVER['REQUEST_URI'], "mod-includes")) !== false) {
        $type = "module";
    }*/
    $sqlHistoric->execute([
        "recherche" => $_SERVER['SERVER_NAME'].explode("?", $_SERVER['REQUEST_URI'])[0], 
        "date" => date("Y-m-d H:i:s"), 
        "user_id" => $_SESSION['user_id']
    ]);
?>