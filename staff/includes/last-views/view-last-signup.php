<?php
try {
    include("../confc.php");
    $bdd = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . "", $dbuser, $dbpass); // Requête de connexion à la DB
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Attributs
    $sqlGetLogs = $bdd->prepare("SELECT user_id AS 'Id',username AS 'Pseudo',email AS 'Email',status AS 'Statut',DATE_FORMAT(date_created, '%d/%m/%Y') AS `Date d'inscription` FROM user ORDER BY user_id DESC LIMIT 4");
    $sqlGetLogs->execute();
    $lastSignsUp = $sqlGetLogs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<section id='last-signup-container'
    class="includes-mod-container col-2 border-1 border-dark p-2 bg-transparent">
    <?php
    if (isset($_GET['msg']))
        echo "<h6 class='text-success'>Le compte a bien été supprimé</h6>";
    ?>
    <h5 class="text-center text-primary-emphasis fw-bold">4 Dernières inscriptions</h5>
    <div class="h-100 overflow-auto last-view-boxs-container">
        <?php
        for ($i = 0; $i < 4; $i++) {
            $userIdToDelete = $lastSignsUp[$i]['Id'];
            echo "<div class='staff-logs-box border-bottom p-1 border-2 border-primary w-auto'>";
            foreach ($lastSignsUp[$i] as $key => $value) echo "<li class='fs-6'>$key : $value</li>";
            echo "</div>";
        }
        ?>
    </div>
</section>