<?php
try {
    include("../confc.php");
    $bdd = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . "", $dbuser, $dbpass); // Requête pour la connexion à la DB
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Attributs
    $sqlGetLastActions = $bdd->prepare
    ("SELECT id_staff_action AS 'Identifiant',type AS 'Type',username AS 'Auteur',
    DATE_FORMAT(date, '%d/%m/%Y') AS 'Date' FROM staff_actions,user WHERE user.user_id=staff_actions.user_id ORDER BY id_staff_action DESC LIMIT 4");
    // On récupère les dernières actions staff en ordre décroissant et en limittant la recherche à 4 rows
    $sqlGetLastActions->execute(); // On exec
    $lastActions = array(); // Création du tableau contenant les actions staff
    $i = 0;
    while ($row = $sqlGetLastActions->fetch(PDO::FETCH_ASSOC)) {
        $lastActions[$i] = $row; // On assigne chaque row à une case du tableau
        $i++;
    }
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<section id='last-staff-action-container' class="includes-mod-container col-2 border-1 border-dark p-2 bg-transparent">
    <h5 class="text-center text-primary-emphasis fw-bold">4 dernières Actions Staff</h5>
    <div style="max-height:28.5vh;" class="h-100 overflow-auto last-view-boxs-container">
        <?php
            include("./includes/trads.php");
            for ($i = 0; $i < count($lastActions); $i++) {
                echo "<div class='staff-logs-box border-bottom p-1 border-2 border-primary w-auto'>";
                foreach ($lastActions[$i] as $key => $value) {
                    if ($key !== "Type") {
                        echo "<li class='fs-6'>$key : <span>$value</span></li>"; 
                    } else {
                        echo "<li class='fs-6'>$key : <span>".$staffLogsTradValue[$value]."</span></li>"; 
                    }
                }
                echo "</div>";
            }
        ?>
    </div>
</section>