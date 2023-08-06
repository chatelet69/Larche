<?php
$title = "event";
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
    <title>L'Arche | Création d'évenements</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/createarticle.css">
    <link rel="stylesheet" type="text/css" href="css/eventcreation.css">
</head>

<body class="body-article body">
    <?php
    include("./includes/header.php"); // vérifie si la session existe.
    ?>
    <div class="accueil-title">
        <div class='barres'></div>
        <h2 class='text-index'>Créer un évenement
        </h2>
        <div class='barres'></div>
    </div>
    <main class="container-article">
        <form action="./includes/verif-event.php" method="post" enctype="multipart/form-data" class="form-article">
            <input type="text" placeholder="Titre de l'évenement (50 caractères maximum)" class="grey-input" name="titre-event">
            <input type="text" placeholder="Teaser de l'évènement (50 caractères maximum)" class="grey-input" name="teaser-event">
            <input name="content-event" placeholder="Ajouter une description... (écrivez `n pour faire un retour à la ligne)." class="grey-input">
            <input type="text" placeholder="Lieu de l'évenement (50 caractères maximum)" class="grey-input" name="lieu-event">
            <div class="article-options-1">
                <div>
                    <label for="date" class="">Date de l'évenement :</label>
                    <input id="date" class="grey-option" type="date" name="date-event">
                </div>
                <div>
                    <label for="time" class="">Heure de l'évenement :</label>
                    <input id="time" class="grey-option" type="time" name="time-event">
                </div>
                <div>
                    <label for="nb_max_event">Nombre de participants :</label>
                    <input type="number" id="nb_max_event" name="nb_max_event" class="grey-option">
                </div>
            </div>
            <div class="article-options">
                <div class="dropdown">
                    <span class="grey-option">Thème <img src="./assets/triangle.png" alt="flèche déroulante" class="theme-article"></span>
                    <div class="dropdown-content">
                        <div>
                            <input type="radio" id="animaux" name="theme-event" value="Animaux">
                            <label for="animaux">Animaux</label><br>
                        </div>
                        <div>
                            <input type="radio" id="jardinerie" name="theme-event" value="Jardinerie">
                            <label for="jardinerie">Jardinerie</label><br>
                        </div>
                        <div>
                            <input type="radio" id="autre" name="theme-event" value="autre">
                            <label for="autre">Autre</label>
                        </div>
                    </div>
                </div>
                <label for="file" class="label-file grey-option">Image<img src="./assets/PJ.png" alt="pièces jointes" class="PJ-article"></label>
                <input id="file" class="input-file grey-option" type="file" accept="image/jpeg , image/png, image/gif" name="image-event">

            </div>
            <?php

            if (isset($_GET['msg'])) {

                if (!empty($_GET['msg'])) {
                    echo "<p class='alert'>" . $_GET['msg'] . "</p>";
                }
            }
            ?>
            <div class="send-article">
                <input type="submit" value="Créer l'évènement" class="colorbutton">
            </div>
        </form>
    </main>
    <?php
    include("./includes/footer.php"); // vérifie si la session existe.
    ?>
      <script src="./js/code.js"></script>
    <script src="./js/darkmode.js"></script>
</body>

</html>