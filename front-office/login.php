<?php
if (isset($_SESSION) && isset($_SESSION['user']) && isset($_SESSION['user_id']) && $_SESSION['logged'] === true) {
    header('location: https://larche.ovh/accueil');
  }?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
</head>

<body class="login body">
    <div class="header-index-page">
    <img src="./assets/logo-transparent.png" class="logo darkmode"><img src="./assets/logo-transparent-noir.png" alt="logo noir" class="logo lightmode">
        <img src="./assets/noirdarkmode.png" alt="Lune, dark mode" onclick="darkMode()" class="lightmode darkmoon"><img src="./assets/sun.png" onclick="darkMode()" class="darkmode darkmoon">
    </div>
    <main class="main-login">
        <div class="login-container">
            <div class=cross>
                <a href="https://larche.ovh/index" title="retour à l'accueil"><img src="./assets/cross.png" alt="croix" class="croix"></a>
            </div>
            <div class=content-login>
                <progress value="50" max="100">50%</progress>
                <h1 class="connect">Connexion</h1>
                <div class="barres"></div>
                <h3>Complétez les informations suivantes :</h3>
                <form class="form-log" action="./verif/logverif.php" method="post">
                    <div class="label-signin2" id="inputslog">
                        <label for="email">Email :</label>
                        <input type="email" name="email" class="inputs" id="email" value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>"><br>
                        <label for="pwd">Mot de passe :</label>
                        <div>
                            <input type="password" name="password" class="inputs" id="pwd">
                            <button class="eyes" type="button" onclick="changeTypeLog()" name="eye2"><img src="./assets/eye.png" class="eyesclose" id=eyeopen2><img src="./assets/eyeclose.png" class="eyeschange" id="eyeclose2" alt='voir mot de passe'></button>
                        </div>
                    </div>
                    <?php
                    echo '<p class="alert">' . (isset($_GET['message']) ? $_GET['message'] : '') . '</p>';
                    ?>
                    <div class="submitlog">
                        <input type="submit" class="boutonconnect" name='envoyer'>
                    </div>
                </form>
                <div class="links-login">

                <a href="https://larche.ovh/sign-up" class="noaccount lien">Je n'ai pas de compte.</a>
                <a href="https://larche.ovh/forgot-password" class="forgot-password lien">Mot de passe oublié.</a>

                </div>
            </div>
        </div>
    </main>
    <script>
        function changeTypeLog() {
            const balise2 = document.getElementById("pwd");
            const eyesclose2 = document.getElementById("eyeclose2");
            const eyeopen = document.getElementById("eyeopen2");
            console.log(eyeclose2);
            console.log(eyeopen);
            if (balise2.type === "password") {
                balise2.type = "text";
                eyesclose2.classList.add("eyesclose");
                eyesclose2.classList.remove("eyeschange");
                eyeopen.classList.add("eyeschange");
                eyeopen.classList.remove("eyesclose");

            } else {
                balise2.type = "password";
                eyesclose2.classList.remove("eyesclose");
                eyesclose2.classList.add("eyeschange");
                eyeopen.classList.remove("eyeschange");
                eyeopen.classList.add("eyesclose");

            }
        }
    </script>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
</body>

</html>