<?php
    session_start();
    if (isset($_SESSION['emailToResetPass'])) $email = $_SESSION['emailToResetPass'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Valider code</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="shortcut icon" href="./assets/logo-transparent-vert.png" type="image/x-icon">
</head>

<body class="login changer-mdp-page body">
    <div class="header-index-page">
        <img src="./assets/logo-transparent-noir.png" alt="logo noir" class="logo">
        <img src="./assets/noirdarkmode.png" alt="Lune, dark mode" class="darkmoon">
    </div>
    <main class="main-login">
        <div class="login-container">
            <div class="cross">
                <a href="https://larche.ovh/login" title="Se connecter"><img src="./assets/cross.png" alt="croix" class="croix"></a>
            </div>
            <div class="content-login">
                <progress value="66" max="100">66%</progress>
                <h1 class="connect">Mot de passe oublié</h1>
                <div class="barres"></div>
                <h2 id="titlePutCode" class="change" style="font-size:2vh;margin-bottom:4px;">Veuillez renseigner le code reçu par email</h2>
                <form id="formPutCodeToReset">
                    <label for="verifEmail" class="change">Code :</label>
                    <input type="number" name="verifEmail" class="inputs change" id="verifEmail" required>
                    <input type="button" onclick="checkResetPassCode(document.getElementById('verifEmail').value, '<?php echo $email ?>')" value="Vérifier" class="boutonconnect change" style="align-self: center;">
                </form>
                <form id="formPutNewPassword" style="display:none;" action="./includes/change-pwd.php" method="post">
                    <label for="newPassword" class="change">Nouveau mot de passe :</label>
                    <input type="password" name="newPassword" class="inputs change" id="verifEmail" placeholder="Le mot de passe doit être entre 6 et 32 caractères" required>
                    <label for="confirmNewPassword" class="change">Confirmer mot de passe :</label>
                    <input type="password" name="confirmNewPassword" class="inputs change" id="verifEmail" required>
                    <input type="text" name="requestType" value="putNewPassword" hidden>
                    <input type="text" name="emailUser" value="<?php echo $email ?>" hidden>
                    <input type="submit" onclick="" value="Confirmer" class="boutonconnect change">
                    <?php
                    echo '<p class="alert">' . (isset($_GET['msg']) ? $_GET['msg'] : '') . '</p>';
                    ?>
                </form>
                <h3 id="resetCodeNotValid" style="color:red;display:none;">Code non valide</h3>
                <div class="links-login">
                </div>
            </div>
        </div>
    </main>
    <script src="./js/change-pwd.js"></script>
    <script src="./js/darkmode.js"></script>
</body>

</html>