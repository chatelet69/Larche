<?php session_start(); ?>

<!DOCTYPE html>

<html>

<head>
    <title>L'Arche | S'inscrire</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="stylesheet" type="text/css" href="./css/sign-up.css">
    <link rel="shortcut icon" href="./assets/logo-transparent-vert.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body class="signin body">
    <div class="header-index-page">
        <img src="./assets/logo-transparent.png" class="logo darkmode"><img src="./assets/logo-transparent-noir.png" alt="logo noir" class="logo lightmode">
        <img src="./assets/noirdarkmode.png" alt="Lune, dark mode" class="lightmode darkmoon" onclick="darkMode()"><img src="./assets/sun.png" onclick="darkMode()" class="darkmode darkmoon" id="">
    </div>

    </header>

    <form action="signverif.php" method="post">

        <div id="signup" class="appear">
            <main class="container-main">
                <div class="signin-container">
                    <div class="cross-signup">
                        <a href="index.php" title="retour à l'accueil">
                            <img src="./assets/cross.png" alt="croix"></a>

                    </div>
                    <?php
                    echo '<p class="alert alert-signup">' . (isset($_GET['message']) ? $_GET['message'] : '') . '</p>';
                    ?>
                    <progress value="25" max="100">25%</progress>
                    <h1 class="inscription">Inscription</h1>
                    <div class="barres"></div>
                    <h3 class="sous-texte">Complètez les informations suivantes :</h3>
                    <div class="label-signin2">
                        <label for="nom">Nom :</label>
                        <input id="nom" class="input" type="text" name="nom" maxlength="45" value="<?php echo isset($_COOKIE['nom']) ? htmlspecialchars($_COOKIE['nom']) : ''; ?>">
                        <label for="prenom">Prénom :</label>
                        <input id="prenom" class="input" type="text" name="prenom" maxlength="45" value="<?php echo isset($_COOKIE['prenom']) ? htmlspecialchars($_COOKIE['prenom']) : ''; ?>">
                        <label for="age">Date de Naissance :</label>
                        <input id="age" class="input" type="date" name="age" min="1910-1-1" max="<?= date('Y-m-d'); ?>" value="<?php echo isset($_COOKIE['age']) ? htmlspecialchars($_COOKIE['age']) : ''; ?>">
                        <label for="ville">Lieu de résidence :</label>
                        <input id="ville" class="input" type="text" name="ville" maxlength="50" value="<?php echo isset($_COOKIE['ville']) ? htmlspecialchars($_COOKIE['ville']) : ''; ?>">
                    </div>
                    <div id="message1"></div>
                    <button type="button" onclick="changePage()" class="boutonconnect-signin2">Valider</button><br>
                    <h6 class="login-lien">Vous avez déjà un compte ?</h6>
                    <a class="lien" href="https://larche.ovh/login">connectez-vous</a>
                </div>
            </main>
        </div>
        <div id="signup2" class="page">
            <main class="container-main">
                <div class="signin-container signin-resp">
                    <div class="cross-signup">
                        <a href="https://larche.ovh/index" title="retour à l'accueil"><img src="./assets/cross.png" alt="croix" class=""></a>
                    </div>
                    <?php
                    echo '<p class="alert alert-signup">' . (isset($_GET['message']) ? $_GET['message'] : '') . '</p>';
                    ?>
                    <progress value="50" max="100">50%</progress>
                    <h1 class="inscription">Inscription</h1>
                    <div class="barres"></div>
                    <h3 class="sous-texte">Complètez les informations suivantes :</h3>
                    <div class="label-signin2" id="inputs2">
                        <label for="Email">Email :</label>
                        <input id="Email" class="input" type="email" name="email" maxlength="255" value="<?php echo isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : ''; ?>">
                        <label for="pwd">Mot de passe :</label>
                        <div>
                            <input id="pwd" class="input" class="eyes" type="password" minlength="6" maxlength="32" name="password" value="">
                            <button class="eyes" type="button" onclick="changeType1()" name="eye1"><img src="./assets/eye.png" id="eyeopen" class="eyesclose"><img src="./assets/eyeclose.png" class="eyeschange" id="eyeclose"></button>
                        </div>
                        <label for="pwdconfirm">Confirmation mot de passe :</label>
                        <div>
                            <input id="pwdconfirm" class="input" class="eyes" type="password" minlength="6" maxlength="32" name="pwdconfirm" value="">
                            <button class="eyes" type="button" onclick="changeType2()" name="eye2"><img src="./assets/eye.png" class="eyesclose" id=eyeopen2><img src="./assets/eyeclose.png" class="eyeschange" id="eyeclose2"></button>
                        </div>
                    </div>
                    <div>
                        <div id="checkbox">
                            <input id="active" class="checkbox" type="checkbox" name="newsletter" value="on">
                            <label onclick="checkboxActive()" id="checkboxtest">
                                Je souhaite faire partie de la newsletter
                            </label>
                        </div>
                    </div>
                    <div id="message2"></div>
                    <div>
                        <button type="button" onclick="changePage2()" class="boutonconnect-signin2">Valider</button><br>
                        <button type="button" onclick="changeBack()" class="boutonconnect-signin">Retour</button>
                    </div>
                    <h6 class="login-lien">Vous avez déjà un compte ?</h6>
                    <a class="lien" href="https://larche.ovh/login">connectez-vous</a>
                </div>
            </main>
        </div>
        <div id="signup3" class="page">
            <main class="container-main">
                <div class="signin-container">
                    <div class="cross-signup">
                        <a href="index.php" title="retour à l'accueil">
                            <img src="./assets/cross.png" alt="croix" class=""></a>
                    </div>
                    <?php
                    echo '<p class="alert alert-signup">' . (isset($_GET['message']) ? $_GET['message'] : '') . '</p>';
                    ?>
                    <progress value="75" max="100">75%</progress>
                    <h1 class="inscription">Inscription</h1>

                    <div class="barres"></div>
                    <h3 class="sous-texte">Dernière étape !</h3>
                    <div class="label-signin2">
                        <label for="pseudo">Pseudo :</label>
                        <input id="pseudo" class="input" type="text" maxlength="32" name="username" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>">
                        <label for="description">Description :</label>

                        <textarea col="32" rows="4" id="description1" class="description" name="description" maxlength="175" placeholder="Parlez de vous !"></textarea>
                    </div>
                    <div id="image-container">
                        <h4 class="textcaptcha">Captcha</h4>
                        <h5 class="textcaptcha">Résolvez le Captcha suivant :</h5>
                        <div class="captcha-img">
                            <?php 
                                include('./includes/db-connect.php');
                                $q = 'SELECT link FROM captcha_images';
                                $sql = $bdd->prepare($q);
                                $sql->execute();
                                $sql = $sql->fetchAll(PDO::FETCH_ASSOC);
                                
                                $rand = array_rand($sql);
                                $rand = $sql[$rand]['link'];
                            ?>
                            <img style="width: 250px;" src="<?php echo $rand ?>" alt="" hidden>
                        </div>
                    </div>
                    <div id="message3"></div>
                    <div>

                        <button type="button" onclick="changePage3()" class="boutonconnect-signin2" id="lastsubmit" disabled>Valider</button><br>

                        <button type="button" onclick="changeBack2()" class="boutonconnect-signin">Retour</button>
                    </div>
                    <h6 class="login-lien">Vous avez déjà un compte ?</h6>
                    <a class="lien" href="https://larche.ovh/login">connectez-vous</a>
                </div>
            </main>

        </div>
    </form>



    <script src="./js/signup-script.js"></script>
    <script src="./js/darkmode.js"></script>
    
</body>

</html>