<?php
$lvl_perms = intval($_SESSION['lvl_perms']);
try {
    include("../confc.php");
    $bdd = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . "", $dbuser, $dbpass); // Requête pour la connexion à la DB
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Attributs de connexion
    $sqlGetLastTickets = $bdd->prepare("SELECT id_ticket AS 'Identifiant',subject AS 'Sujet',
    username AS 'Auteur',DATE_FORMAT(date, '%d/%m/%Y') AS date FROM tickets,user WHERE tickets.user_id_author=user.user_id ORDER BY id_ticket DESC LIMIT 4");
    // Récupérer les tickets par ordre décroissant en limitant la recherche à 4 résultats
    $sqlGetLastTickets->execute(); // Exec
    $lastTickets = array();
    $i = 0;
    while ($row = $sqlGetLastTickets->fetch(PDO::FETCH_ASSOC)) {
        $lastTickets[$i] = $row; // On récupère la row actuelle et on l'affecte à la case du tableau
        $i++;
    }
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<section id='last-signup-container' class="includes-mod-container col-2 border-1 border-dark p-2 bg-transparent">
    <h5 class="text-center text-primary-emphasis fw-bold">4 derniers tickets</h5>
    <div style="max-height:28.5vh;" class="h-100 overflow-auto last-view-boxs-container">
        <?php
        if (count($lastTickets) == 0) echo "<h5 class='text-center fw-bold fs-2 text-danger'>Pas de tickets</h5>";
        for ($i = 0; $i < count($lastTickets); $i++) {
            echo "<div class='staff-logs-box border-bottom p-1 border-2 border-primary w-auto'>";
            foreach ($lastTickets[$i] as $key => $value) echo "<li class='fs-6'>$key : $value</li>";
            echo "</div>";
        }
        ?>
    </div>
</section>