<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche Back-office | Panel des Mails</title>
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

<body class="panel-logs-page module-page">
    <?php
    include("../includes/checkSession.php");
    include("/var/www/staff/includes/logHistoric.php");
    ?>
    <div class="mod-main-container container-fluid">
        <h2 class='mod-title fw-semibold'>Gestion Newsletter et Mails | Bonjour <span id="arobase">@<?php echo $_SESSION['user']; ?>
            </span>
        </h2>
        <?php
        include("./mod-includes/btn-send-mail.php");
        if (isset($_SESSION['requestRes']) && $_SESSION['requestRes'] === "mail-sent") {
            echo "<h3 class='text-success fs-6'>Le mail a bien été envoyé</h3>";
            unset($_SESSION['requestRes']);
        }
        ?>
        <section id="config-mails-container" class="bg-transparent p-1 row">
            <section id="config-news" class="col border-1 m-2 border border-secondary rounded-2 container">
                <h3 class="mt-1 fs-5 text-center title-box-config-mails">Configuration des mails Newsletter Articles</h3>
                <form action="https://staff.larche.ovh/includes/configNewsletter.php" class="form p-1" method="post">
                    <div class="mb-2">
                        <h4 class="fs-6 text-primary-emphasis">Récurrence</h4>
                        <div class="d-flex flex-row">
                            <label class="mt-1 me-1">Heure/Min</label>
                            <input type="time" class="input-mails-config form-control w-25 me-1" name="timeFormNews" id="recurrence">
                            <select class="input-mails-config form-control" name="daysFormNews"
                                placeholder="Jour de la semaine" id="input-mails-days">
                                <option value="" selected>Choisir jours</option>
                                <option value="1">Lundi</option>
                                <option value="2">Mardi</option>
                                <option value="3">Mercredi</option>
                                <option value="4">Jeudi</option>
                                <option value="5">Vendredi</option>
                                <option value="6">Samedi</option>
                                <option value="0">Dimanche</option>
                                <option value="*">Tous les jours</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="forme_texte">Forme du Texte de début</label>
                        <textarea maxlength="525" rows="2" cols="3" class="form-control" type="text" name="formStartText"
                            placeholder="525 caractères maximum"></textarea>
                        <label class="form-label" for="forme_texte">Forme du Texte de fin</label>
                        <textarea maxlength="525" rows="2" cols="3" class="form-control" type="text" name="formEndText"
                            placeholder="525 caractères maximum"></textarea>
                    </div>
                    <div class="d-flex justify-content-center">
                        <input type="text" name="configMailsRequest" value="configNewsletter" hidden>
                        <input type="submit" class="mt-1 btn btn-success" name="save"
                            value="Enregistrer les modifications">
                    </div>
                </form>
                <?php
                    if (isset($_SESSION['requestRes']) && $_SESSION['requestRes'] === "config-changed") {
                        echo "<h3 class='text-success fs-6'>La nouvelle configuration a bien été appliquée</h3>";
                        unset($_SESSION['requestRes']);
                    } else if (isset($_SESSION['requestRes']) && $_SESSION['requestRes'] === "need_all_values") {
                        echo "<h3 class='text-danger fs-6'>Il manque une valeur</h3>";
                        unset($_SESSION['requestRes']);
                    }
                ?>
            </section>
            <section id="config-inactivity" class="col m-2 border-1 border border-secondary rounded-2 container">
                <h3 class="text-center fs-5 mt-1 title-box-config-mails">Configuration des mails d'inactivité</h3>
                <form action="https://staff.larche.ovh/includes/configNewsletter.php" class="form p-1" method="post">
                    <div class="mb-2">
                        <label class="form-label" for="forme_texte">Forme du Texte</label>
                        <textarea maxlength="525" rows="7" cols="3" class="form-control" type="text" name="formInactivityText"
                            placeholder="525 caractères maximum"></textarea>
                    </div>
                    <div class="d-flex justify-content-center">
                    <input type="text" name="configMailsRequest" value="configInactivity" hidden>
                        <input type="submit" class="mt-1 btn btn-success" name="save"
                            value="Enregistrer les modifications">
                    </div>
                </form>
                <?php
                    if (isset($_SESSION['requestRes']) && $_SESSION['requestRes'] === "config-inac-changed") {
                        echo "<h3 class='text-success fs-6'>La nouvelle configuration a bien été appliquée</h3>";
                        unset($_SESSION['requestRes']);
                    }
                ?>
            </section>
        </section>
    </div>
    <?php echo "</section></div></main>"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://staff.larche.ovh/js/changeDivBg.js"></script>
</body>

</html>