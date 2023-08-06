<?php
include('./includes/checksessions.php');
include('./includes/logHistoric.php');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="stylesheet" type="text/css" href="./css/profil.css">
    <link rel="stylesheet" type="text/css" href="./css/settings.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
    <title>Paramètres</title>
</head>

<body class="body-bg-beige body">
    <?php include('./includes/header.php'); ?>

    <div class="accueil-title info-settings">
        <div class='barres'></div>
        <h2 class="text-index info-settings text-settings">INFORMATIONS DU COMPTE</h2>
        <div class='barres'></div>
    </div>
    <!--changer les p en formulaire-->
    <div class="setting-div">
        <div class="settings-choice">
            <div class="div-color alldiv">
                <h2 class="nav-settings profil-settings-desc">Profil</h2>
            </div>
            <div class="alldiv">
                <h2 class="nav-settings">Données</h2>
            </div>
            <div class="alldiv">
                <h2 class="nav-settings">Apparence</h2>
            </div>
        </div>
    </div>
    <main class="profil-main" id="profil">
        <!-- <form action="verifprofil.php" method="post" class=""> -->
        <div class='banniere'>
            <?php
            $banniere = $bdd->prepare("SELECT link_banner FROM user WHERE email = ?");
            $banniere->execute([$email]);
            $banniere = $banniere->fetch();
            $banniere = $banniere['link_banner'];
            $_SESSION['user_banner'] = $banniere;

            echo "<img src='$banniere' alt='banniere'>";
            ?>
        </div>

        <div class="box-profil">
            <img src=" <?php echo $_SESSION['user_pfp']; ?> " alt="Photo de profil." class="PDP">
            <button type="button" onclick="profilModif()" class="modif">Modifier vos informations</button>
            <a class="display-profil modif-pdp" href="./settings-photo.php">Modifier la photo de profil et la bannière</a>
        </div>

        <form action="./verif/verifprofil.php" method="post" class="form-settings">
            <section class="profil-desc">
                <h5>Description :</h5>
                <?php

                $description = $bdd->prepare("SELECT description FROM user WHERE email = ?"); // de base c'était desc mais vu que c'est le nom d'une propriété SQL il faut mettre des `` pour que ça comprenne que c'est le nom d'une colonne
                $description->execute([$email]);
                $description = $description->fetch(PDO::FETCH_ASSOC);
                $_SESSION['description'] = $description['description'];

                echo '<p class="text-no-change ptoarea">' . $_SESSION['description'] . '</p>';
                ?>
            </section>
            <section class="profil-desc desc2">
                <div class="settings">
                    <h5>Email : </h5>
                    <?php
                    $q = 'SELECT email FROM user WHERE email=?';
                    $sql = $bdd->prepare($q);
                    $sql->execute([$_SESSION['email']]);
                    $sql = $sql->fetch(PDO::FETCH_ASSOC); // Récupère la 1e ligne de résultat ou false
                    $sql = $sql['email'];
                    echo '<p class="p-infos ptoform">' . $sql . '</p>';
                    ?>
                </div>
                <div class="settings">
                    <h5>Pseudo : </h5>
                    <?php
                    $q = 'SELECT username FROM user WHERE email=?';
                    $sql = $bdd->prepare($q);
                    $sql->execute([$email]);
                    $sql = $sql->fetch(PDO::FETCH_ASSOC); // Récupère la 1e ligne de résultat ou false
                    $sql = $sql['username'];
                    echo '<p class="p-infos ptoform">' . $sql . '</p>';
                    ?>
                </div>
                <div class="settings">
                    <h5>Ville : </h5>
                    <?php
                    $q = 'SELECT city FROM user WHERE email=?';
                    $sql = $bdd->prepare($q);
                    $sql->execute([$email]);
                    $result = $sql->fetch(PDO::FETCH_ASSOC); // Récupère la 1e ligne de résultat ou false
                    $sql = $result['city'];
                    echo '<p class="p-infos ptoform">' . $sql . '</p>';
                    ?>
                </div>
                <div class="settings">
                    <h5>Nom : </h5>
                    <?php
                    $q = 'SELECT lastname FROM user WHERE email=?';
                    $sql = $bdd->prepare($q);
                    $sql->execute([$email]);
                    $result = $sql->fetch(PDO::FETCH_ASSOC); // Récupère la 1e ligne de résultat ou false
                    $sql = $result['lastname'];
                    echo '<p class="p-infos ptoform">' . $sql . '</p>';
                    ?>
                </div>
                <div class="settings">
                    <h5>Prénom : </h5>
                    <?php
                    $q = 'SELECT name FROM user WHERE email=?';
                    $sql = $bdd->prepare($q);
                    $sql->execute([$email]);
                    $sql = $sql->fetch(PDO::FETCH_ASSOC); // Récupère la 1e ligne de résultat ou false
                    $sql = $sql['name'];
                    echo '<p class="p-infos ptoform">' . $sql . '</p>';
                    ?>
                </div>
                <button type="button" onclick="showPopupMdp()" id="changemdp" class="newsletter-settings">Changer de Mot de passe</button>

                <?php
                echo '<p class="alert">' . (isset($_SESSION['returnMessage']) ? $_SESSION['returnMessage'] : '') . '</p>';
                echo '<p class="verifcorrect">' . (isset($_SESSION['correctMessage']) ? $_SESSION['correctMessage'] : '') . '</p>';
                unset($_SESSION['returnMessage']);
                unset($_SESSION['correctMessage']);

                ?>
                <!-- <button class="pen"><img src="./assets/pen.png"></button> -->
            </section>
            <button class="display-profil input-register2" type="button">Enregistrer</button>
        </form>
        <div class="popup-bg2"></div>
        <div class="popupmdp" class="container-delete">
            <h2>Changement de Mot de passe</h2>
            <div id="msg-delete2"></div>
            <p>Êtes-vous sûr(e) de vouloir changer de mot de passe ?</p><br>
            <p>Si oui, écrivez votre nouveau mot de passe :</p>
            <form action="./verif/verifmdp.php" method="post">
                <input type="password" name="password" class="input-delete2">
                <p>confirmation du nouveau mot de passe :</p>
                <input type="password" name="pwdchangeconfirm" class="input-delete2">
                <button type="button" onclick="mdpconfirm()" class="delete-confirm2">Confirmer</button>
                <button type="button" class="input-close2" onclick="hidePopupMdp()">Fermer</button>
            </form>
        </div>
    </main>
    <main id="cookies">
        <div class="cookies-div">
            <form method="post" action="newsletterverif.php">
                <div>
                    <?php
                    $q = 'SELECT newsletter_pref FROM user WHERE email=?';
                    $sql = $bdd->prepare($q);
                    $sql->execute([$email]);
                    $sql = $sql->fetch(PDO::FETCH_ASSOC); // Récupère la 1e ligne de résultat ou false
                    $result = $sql['newsletter_pref'];
                    ?>
                    <h2 class="h2-settings">Newsletter :</h2>
                    <div class="check-div">
                        <input class="active-settings" type="radio" name="newsletter" <?php if ($result == 1) echo "checked"; ?> value="1">
                        <label class="label-settings">Je souhaite recevoir la Newsletter</label>
                    </div>
                    <div class="check-div">

                        <input class="active-settings" type="radio" name="newsletter" <?php if ($result == 0) echo "checked"; ?> value="0">
                        <label class="label-settings">Je ne souhaite pas recevoir la Newsletter</label>
                    </div>
                    <div class="div2">
                        <button type="input" onclick="" class="newsletter-settings">Modifier</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="cookies-div">
            <h2 class="h2-settings">Données :</h2>
            <div class="button-data-div">
                <button class="button-data" id="data" onclick="dataUserToPDF()">Exporter mes données</button>
                <button class="button-data" onclick="showPopup()" id="delete">Supprimer mon compte</button>
                <div class="popup-bg"></div>
                <div class="popup" class="container-delete">
                    <h2>Suppression de compte</h2>
                    <p>Êtes-vous sûr(e) de vouloir supprimer votre compte ?</p><br>
                    <p>Si oui, écrivez votre mot de passe</p>
                    <div id="msg-delete"></div>
                    <form action="./verif/verifdelete.php" method="post">
                        <input type="password" name="pwddelete" class="input-delete">
                        <button type="button" onclick="changeSettings()" class="delete-confirm-account">Confirmer</button>
                        <button type="button" class="input-close-account" onclick="hidePopup()">Fermer</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <main id="apparence">
        <div class="contain-appareance">
            <div class="div-apparence firstDiv">
                <img class="img-apparence" src="./assets/lightmode-apparence.png">
                <input style="transform: scale(1.25);" class="button-radio" type="radio" name="mode" class="radio-settings" checked>
            </div>
            <div class="div-apparence secondDiv">
                <img class="img-apparence" src="./assets/darkmode-apparence.png">
                <input style="transform: scale(1.25);" class="button-radio" type="radio" class="radio-settings" name="mode">
            </div>
        </div>
        <div class="div-apparence-button">
            <button onclick="changeApparence()" class="input-apparence">Enregistrer</button>
        </div>
        </div>
    </main>

    <?php include('./includes/footer.php'); ?>
    <script src="./js/darkmode.js"></script>
    <script src="./js/profil.js"></script>
    <script src="./js/code.js"></script>
    <script src="./js/apidata.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://unpkg.com/jspdf-autotable@3.5.28/dist/jspdf.plugin.autotable.js"></script>
</body>

</html>