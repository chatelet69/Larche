<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Vérification d'Email</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="stylesheet" type="text/css" href="./css/sign-up.css">
    <link rel="shortcut icon" href="./assets/logo-transparent-vert.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="signin body">
    <div class="header-index-page">
        <img src="./assets/logo-transparent.png" class="logo darkmode"><img src="./assets/logo-transparent-noir.png" alt="logo noir" class="logo lightmode">
        <a href="" title="mode nuit"><img src="./assets/noirdarkmode.png" alt="Lune, dark mode" onclick="darkMode()" class="lightmode darkmoon"><img src="./assets/sun.png" onclick="darkMode()" class="darkmode darkmoon"></a>
    </div>
    </header>
    <div id="signup4">
        <main class="container-main">
            <div class="signin-container">
                <div class="cross-signup">
                </div>
                <progress value="100" max="100">50%</progress>
                <h1 class="connect">Vérification Email</h1>
                <div class="barres"></div>
                <h3 class="sous-texte">Veuillez renseigner le code que vous avez reçu par email :</h3>
                <div>
                    <form action="verifemail.php" method="post">
                        <label for="codemail">Code :</label>
                        <input type="text" name="codemail" class="input" id="codemail">
                        <?php
                        echo '<p class="alert">' . (isset($_GET['message']) ? $_GET['message'] : '') . '</p>';
                        ?>
                        <input type="submit" value="Envoyer" class="boutonconnect-signin2">
                </div>
                </form>
                <div class="links-login">
                    <a href="verifcodenew.php">Renvoyer le code</a>
                </div>
            </div>
        </main>
    </div>
    <script src="./js/verifemail.js"></script>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
</body>

</html>