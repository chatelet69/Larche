<?php
$title = "articles";
include('./includes/checksessions.php');
include("./includes/logHistoric.php");
include('./includes/check-status.php');

    $eventinfos = $bdd->prepare('SELECT id_event, title, description, image, date_event FROM events ORDER BY date_event LIMIT 5');
    $eventinfos->execute();
    $eventinfos = $eventinfos->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Création d'article</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/createarticle.css">
</head>

<body class="body-article body">
    <?php
    include("./includes/header.php"); // vérifie si la session existe.
    ?>
    <div class="accueil-title">
        <div class='barres'></div>
        <h2 class="text-index">Créer un article</h2>
        <div class='barres'></div>
    </div>
    <main class="container-article">
        <form action="./includes/articlecreation.php" method="post" enctype="multipart/form-data" class="form-article">
            <input type="text" placeholder="Titre de l'article (50 lettres max)" class="grey-input" name="titre-article">
            <input type="text" placeholder="Teaser / Introduction... (100 lettres max)" class="grey-input" name="teaser">
            <textarea name="content-article" placeholder="Ajouter une description... Pour faire un retour à la ligne, écrivez `n" class="grey-input"></textarea>
            <div class="article-options">
                <div class="dropdown">
                    <span class="grey-option">Thème <img src="./assets/triangle.png" alt="flèche déroulante" class="theme-article"></span>
                    <div class="dropdown-content">
                        <div>
                            <input type="radio" id="animaux" name="theme-article" value="Animaux">
                            <label for="animaux">Animaux</label><br>
                        </div>
                        <div>
                            <input type="radio" id="jardinerie" name="theme-article" value="Jardinerie">
                            <label for="jardinerie">Jardinerie</label><br>
                        </div>
                        <div>
                            <input type="radio" id="autre" name="theme-article" value="autre">
                            <label for="autre">Autre</label>
                        </div>
                    </div>
                </div>
                <label for="file" class="label-file grey-option">Image<img src="./assets/PJ.png" alt="pièces jointes" class="PJ-article"></label>
                <input id="file" class="input-file grey-option" type="file" accept="image/jpeg , image/png, image/gif" name="image-article">
            </div>
            <?php
            echo isset($_GET['msg']) ? $_GET['msg'] : '';
            ?>
            <div class="send-article">
                <input type="submit" value="Créer l'article" class="colorbuttonarticle">
            </div>
        </form>
    </main>
    <?php
    include("./includes/footer.php"); // vérifie si la session existe.
    ?>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
</body>

</html>