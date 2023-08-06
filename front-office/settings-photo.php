<?php
include('./includes/checksessions.php');
include('./includes/db-connect.php');
include('./includes/logHistoric.php');
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="./css/settings.css">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="stylesheet" type="text/css" href="./css/profil.css">
    <link rel="stylesheet" type="text/css" href="./css/settings.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
    <title>Paramètres photo</title>
</head>

<body class="body-bg-beige">
    <?php include('./includes/header.php'); ?>
    <header>
        <a href="settings.php" class="settings-return"><img src="./assets/backblack.png" id="black" class="backlogo"></a>
        <a href="settings.php" class="settings-return"><img src="./assets/backwhite.png" id="white" class="backlogo"></a>
    </header>

    <!--changer les p en formulaire-->
    <main class="profil-main">
        <div class="popup-bg" style="width: 100%;"></div>
        <!-- <div class="divphotoedit"> -->
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
        <div class="img-edit">
            <img src=" <?php echo $_SESSION['user_pfp']; ?> " alt="Photo de profil." class="photosettings">
        </div>
        <h2 class="text-index">Que voulez-vous faire ?</h2>
        <div class="div-button-edits">
            <a href="avatar.php"><button id="settings-avatar" class="button-edits">
                    <p>Personnaliser un avatar</p>
                </button></a>
            <button id="settings-import" onclick="showPopup()" class="button-edits">
                <p>Importer une photo de profil</p>
            </button>
            <button id="banniere-import" onclick="showPopupBanniere()" class="button-edits">
                <p>Importer une bannière de profil</p>
            </button>
            <div class="popup" class="container-delete">
                <h2>Importer votre photo :</h2>
                <h5>Moins de 5MO, type png accepté !</h5>
                <div id="msg-delete"></div>
                <form action="./verif/changepdp.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="pdp" id="pdp-settings" class="file-settings" accept="image/png">
                    <button type="button" onclick="changePDP()" class="settings-confirm" id="confirm-settings-pdp">Confirmer</button>
                    <button type="button" class="settings-close" onclick="hidePopup()">Fermer</button>
                </form>
            </div>
            <div class="popupbanniere" class="container-delete">
                <h2>Importer votre bannière :</h2>
                <h5>Moins de 5MO, type jpg et png acceptés !</h5>
                <div id="msg-delete2"></div>
                <form action="./verif/changebanniere.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="banniere" id="banniere-settings" class="file-settings" accept="image/png, image/jpg">
                    <button type="button" onclick="changeBanniere()" class="settings-confirm" id="confirm-settings-banniere">Confirmer</button>
                    <button type="button" class="settings-close" onclick="hidePopupBanniere()">Fermer</button>
                </form>
            </div>

        </div>
        <!-- </div> -->
        <?php
            echo '<p class="alert">' . (isset($_SESSION['returnMessage']) ? $_SESSION['returnMessage'] : '') . '</p>';
            echo '<p class="verifcorrect">' . (isset($_SESSION['correctMessage']) ? $_SESSION['correctMessage'] : '') . '</p>';
            unset($_SESSION['returnMessage']);
            unset($_SESSION['correctMessage']);
            ?>

    </main>
    <?php include('./includes/footer.php'); ?>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
    <script src="./js/settings-photo.js"></script>

</body>

</html>