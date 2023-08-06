<?php
include("/var/www/staff/includes/db-connect.php");
$sqlGetArticles = $bdd->prepare(
    "SELECT articles.id_article, articles.title, user.username,articles.user_id_author AS user_id_author, DATE_FORMAT(articles.date, '%d/%m/%Y') 
    FROM user INNER JOIN articles ON user.user_id=articles.user_id_author;"
);
// La requête récupère seulement les logs liées à des utilisateurs, grâce aux clés étrangères
$sqlGetArticles->execute();
$articles = $sqlGetArticles->fetchAll(PDO::FETCH_ASSOC);
// On récupère toutes les lignes
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche Back-office | Panel des articles</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" type="text/css" href="../css/modules.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="../css/staff.css">
    <link rel="stylesheet" type="text/css" href="../css/forms.css">
    <link rel="shortcut icon" href="../assets/favicon.ico">
</head>

<body class="gestion-articles-page module-page">
    <?php
    include("../includes/checkSession.php");
    include("/var/www/staff/includes/logHistoric.php");
    ?>
    <div class="mod-main-container container-fluid">
        <h2 class='mod-title fw-semibold'>Gestion des articles | Bonjour <span id="arobase">@<?php echo $_SESSION['user']; ?>
            </span>
        </h2>
        <div id="btns-container" class="p-1 m-0 container-sm mb-2 row">
        <?php
            include("./mod-includes/articles_events/btn-search-article.php");
            include("./mod-includes/articles_events/btn-edit-article.php");
        ?>
        </div>
        <section id="articles-box-container" style="max-height:72vh" class="mod-section-container border d-flex flex-column border-1 rounded-2 shadow-sm">
            <ul class="mt-1 mb-0 row">
                <p class="text-center col-sm-1">Article n°</p>
                <p class="text-center col-sm">Titre</p>
                <p class="text-center col-sm">Auteur</p>
                <p class="text-center col-sm">Date</p>
                <p class="col-sm">Lien Article</p>
            </ul>
            <div id="articles-boxs" class="mh-100">
                <?php
                    foreach($articles as $article => $value) {
                        echo "<div id='article-box' class='border text-center border-2 row p-1 m-2 rounded-1'>";
                        foreach($articles[$article] as $key => $value) {
                            $articleId = $articles[$article]['id_article'];
                            if ($key === "id_article") {
                                echo "<span class='fs-6 col-sm-1 m-0'>$value</span>";
                            } else if ($key !== "user_id_author") {
                                echo "<span class='fs-6 text-center col-sm ms-5'>$value</span>";
                            }
                        }
                        echo "<a class='col btn btn-primary' style='width:5vw;' href='https://larche.ovh/article?id=$articleId' target='_blank'>Aller à l'article</a>";
                        echo "
                        <form action='https://staff.larche.ovh/includes/delete-row.php' class='col-sm-1' method='post'>
                        <input type='text' name='id' value='$articleId' hidden>
                        <input type='text' name='requestType' value='deleteObj' hidden>
                        <input type='text' name='type' value='article' hidden>
                        <input type='submit' value='&#10060;' style='background: transparent; border: none;'></input>
                        </form>
                        ";
                    echo "</div>";
                    }
                ?>
            </div>
        </section>
    </div>
    <?php echo "</section></div></main>"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script src="https://staff.larche.ovh/js/changeDivBg.js"></script>
    <script src="../js/popups.js"></script>
    <script src="../js/apiContent.js"></script>
</body>

</html>