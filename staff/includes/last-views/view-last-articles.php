<?php
$lvl_perms = intval($_SESSION['lvl_perms']);
try {
    include("../confc.php");
    $bdd = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . "", $dbuser, $dbpass); // Requête de connexion à la DB
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sqlGetLastArticles = $bdd->prepare("SELECT title,id_article,username_author,
    DATE_FORMAT(date, '%d/%m/%Y') AS date FROM articles ORDER BY id_article DESC LIMIT 4");
    // ON récupère les 4 derniers articles, triés par leur id en ordre décroissant 
    $sqlGetLastArticles->execute(); // On exec
    $lastArticles = array(); // Création du tableau qui va contenir chaque article
    $i = 0;
    while ($row = $sqlGetLastArticles->fetch(PDO::FETCH_ASSOC)) {
        $lastArticles[$i] = $row;
        $i++;
    }
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>

<section id='last-signup-container'
    class="includes-mod-container col-2 border-1 border-dark p-2 bg-transparent">
    <?php
    if (isset($_GET['msg']) === "row-deleted")
        echo "<h6 class='text-success'>Le compte a bien été supprimé</h6>";
    ?>
    <h5 class="text-center text-primary-emphasis fw-bold">4 derniers articles</h5>
    <div style="max-height:25vh;" class="h-100 overflow-auto last-view-boxs-container">
        <?php
        $keys = [
            "title" => "Titre",
            "id_article" => "Id",
            "username_author" => "Auteur",
            "date" => "Date"
        ];
        // Après avoir traduit les clés en français, on fait la boucle pour afficher chaque box avec l'article
        for ($i = 0; $i < count($lastArticles); $i++) {
            $idArticle = $lastArticles[$i]['id_article'];
            echo "<div class='last-article-box border-bottom p-1 border-2 border-primary w-auto'>";
            foreach ($lastArticles[$i] as $key => $value)
                echo "<li class='fs-6'>$keys[$key] : <span>$value</span></li>";

            // Si c'est un admin, on affiche le bouton pour supprimer
            echo "
                <form action='./includes/delete-row.php' method='post' class='m-1 d-flex justify-content-end align-items-center'>
                    <input type='hidden' name='id' value='$idArticle'>
                    <input type='text' name='requestType' value='deleteObj' hidden>
                    <input type='text' name='type' value='article' hidden>
                    <input type='submit' class='btn btn-danger btn-sm' name='deleteMember' value='Supprimer article'>
                </form>
                </div>";
        }
        ?>
    </div>
</section>