<?php
$lvl_perms = intval($_SESSION['lvl_perms']);
try {
    include("../confc.php");
    $bdd = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . "", $dbuser, $dbpass); // Requête de connexion à la DB
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Attributs
    $sqlGetLastEvent = $bdd->prepare("SELECT title,id_event,username_author,status,DATE_FORMAT(date_event, '%d/%m/%Y') AS date_event FROM events ORDER BY id_event DESC LIMIT 4");
    // Récupération des derniers events, en limitant par 4 et en triant par ordre décroissant par id
    $sqlGetLastEvent->execute();
    $lastsEvent = array(); // Tableau qui contient chaque vent
    $i = 0;
    while ($row = $sqlGetLastEvent->fetch(PDO::FETCH_ASSOC)) {
        $lastsEvent[$i] = $row;
        $i++;
    }
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<section id='last-signup-container'
    class="includes-mod-container col-2 border-1 border-dark p-2 bg-transparent">
    <h5 class="text-center text-primary-emphasis fw-bold">4 derniers Events</h5>
    <div style="max-height:25vh;" class="h-100 overflow-auto last-view-boxs-container">
        <?php
        $tradKeys = [
            "title" => "Titre",
            "id_event" => "Id de l'Event",
            "username_author" => "Auteur",
            "status" => "Statut",
            "date_event" => "Date"
        ];
        // Boucle qui affiche chaque event dans sa box
        for ($i = 0; $i < count($lastsEvent); $i++) {
            echo "<div class='last-event-box border-bottom p-1 border-2 border-primary w-auto'>";
            foreach ($lastsEvent[$i] as $key => $value) {
                $idEvent = $lastsEvent[$i]['id_event'];
                echo "<li class='fs-6'>$tradKeys[$key] : $value</li>";
            }
            echo "
            <div class='d-flex justify-content-end'>
            <a class='btn bg-primary-subtle' href='https://larche.ovh/event?id=$idEvent' target='_blank'>Voir Event</a></div>";
            echo "</div>";
        }
        ?>
    </div>
</section>