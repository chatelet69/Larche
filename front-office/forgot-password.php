<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Mot de passe oublié</title>
    <meta charset="UTF-8">
    <meta property="og:title" content="L'Arche | Mot de passe oublié">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://larche.ovh/forgot-password">
    <meta name="theme-color" content="#FAEDCD">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
</head>

<body class="login body">
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
                <progress value="33" max="100">33%</progress>
                <h1 class="connect">Mot de passe oublié ?</h1>
                <div class="barres"></div>
                <h3 class="change">Veuillez renseigner votre email :</h3>
                <form action="reset-password.php" method="post">
                        <label for="email">Email :</label>
                        <input type="email" name="email" class="inputs change" id="email" value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>" required>
                        <br>
                        <input type="text" name="requestType" value="askCodeToReset" hidden>
                        <input type="number" name="verifEmail" class="inputs change" id="verifEmail" style="display: none;">
                    <?php
                    if (isset($_SESSION['requestRes']) && $_SESSION['requestRes'] === "email_needed") {
                        echo '<p class="alert">' . $_SESSION['requestRes'] . '</p>';
                        unset($_SESSION['requestRes']);
                    }
                    ?>
                    <input type="submit" value="Envoyer" class="boutonconnect change" style="align-self: center;">
                </form>
                <div class="links-login">
                <a href="https://larche.ovh/reset-password" class="change" style="display: none">Renvoyer le code</a>
                </div>
            </div>
        </div>
    </main>
    <script type="text/javascript" src="./js/code.js"></script>
    <script src="./js/darkmode.js"></script>

</body>

</html>