<?php
$title = "event";
include("./includes/checksessions.php"); // vérifie si la session existe.
include("./includes/logHistoric.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Évènements </title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/events.css">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
</head>

<body class="body-bg-beige event-body body">
    <?php
    include('includes/header.php')
    ?>
    <div class="accueil-title">
        <div class='barres'></div>
        <h2 class="text-index">Évènements</h2>
        <div class='barres'></div>
    </div>

    <section class="list-event" id='list-event'>
    <?php
        include('./includes/display-event.php');
    ?>
    </section>
<section class='sec-event'>
    <?php
    if ($list_event == false) {
        echo "<div class='question'><img src='./assets/sad-cat.gif' alt='chat triste'</img>";
        echo "<p class='text-index'>Il n'y a pas encore d'évenements de prévu...</p>";
        if ($_SESSION['lvl_perms'] > 1) {
            echo "<a href='https://larche.ovh/eventcreation' class='colorbutton'>Créer un évènement</a>";
        } elseif ($_SESSION['lvl_perms'] == 1) {
            echo "<a href='https://larche.ovh/new-ticket?obj=contrib' class='colorbutton'>Devenir contributeur</a>";
        }
        echo "</div>";
    }

    if ($_SESSION['lvl_perms'] == 1 && $list_event != false) {

        echo "<div class='question'><p class='text-index'>Envie de créer des évènements ?</p>";
        echo "<a href='https://larche.ovh/new-ticket?obj=contrib' class='colorbutton'>Devenir contributeur</a></div>";
    } elseif ($_SESSION['lvl_perms'] > 1 && $list_event != false) {
        echo "<div class='question'><p class='text-index'>Envie de créer des évènements ?</p>";
        echo "<a href='https://larche.ovh/eventcreation' class='colorbutton'>Créer un évènement</a></div>";
    }
    ?>
</section>
<div class="popup" id='popup' style="display: none;">
<h2 class='h2-popup'>Annuler l'évènement</h2>
    <p class='paragraph-popup'>Êtes-vous sûr(e) de vouloir annuler l'évènement ?</p><br>
    <p class='paragraph-popup'>Si oui, indiquez le motif :</p>
    <input type="text" name='motif' id='inp-event-motif'>
    <div class='btn-respons'>
    <button type="button" class="input-close" onclick="hidePopup()">Non</button>
    <input type="number" value="" name='event_id' id='inp-event-id' hidden>
    <button type="button" onclick="cancelEvent()" class="delete-confirm">Oui</button>
    </div>
</div>
    <?php
    include('includes/footer.php');
    ?>
    <script src="./js/code.js"></script>
    <script src="./js/darkmode.js"></script>
    <script src="./js/events.js"></script>
</body>

</html>