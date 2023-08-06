<?php
    session_start();
    // Si l'utilisateur est connecté, on le redirect vers l'index
    // On regarde les varaibles de session et du cookie pour voir si on y retrouve des infos
    if (isset($_SESSION['user_id']) && isset($_SESSION['lvl_perms']) && intval($_SESSION['lvl_perms']) >= 3) {
        header("Location: ./index");
        exit;
    }
    if (!empty($_SESSION['user']) && isset($_COOKIE['signedStaff']) && $_COOKIE['signedStaff'] === $_SESSION['email']) {
        header("Location: ./index");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche Panel Staff | Connexion</title>
    <meta charset="UTF-8">
    <meta name="description" content="Back-office">
    <meta name="robots" content="noindex, nofollow, notranslate">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=yes'>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="./css/staff.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon"
        href="https://cdn.discordapp.com/attachments/1020814204468469770/1082325713673257110/logo-transparent-vert.ico">
</head>

<body class="login-page">
    <div id="staff-login-container" class="container vh-100 d-flex flex-column align-items-center justify-content-center">
        <form class="w-auto bg-secondary-subtle border-top rounded-3 border-primary border-4 border-bottom-0 p-4" action="./includes/verif.php" method="post">
            <h1 class="text-center fs-1 text-primary">Espace Staff Larche</h1>
            <!-- Partie Email -->
            <div class="form-inline mb-4 w-100 m-auto">
                <label class="form-label" for="emailInput">Email</label>
                <input name="email" type="email" id="emailInput" class="form-control" required>
            </div>
            
            <!-- Mot de passe -->
            <div class="form-outline mb-4 w-100 m-auto">
                <label class="form-label" for="passInput">Mot de Passe</label>
                <input name="password" type="password" id="passInput" class="form-control" required>
                <div class="form-text text-center text-danger">En cas d'oubli contactez un administrateur</div>
            </div>
            
            <!-- Bouton de connexion -->
            <div class="text-center">
                <input type="submit" class="connect-button btn btn-outline-primary btn-block mb-4" value="Se connecter">
            </div>

            <?php
                if (isset($_SESSION['login-try'])) {
                    switch($_SESSION['login-try']) {
                        case "unauthorized":
                            echo "<h4 id='problem-msg'>Vous n'êtes pas autorisé à vous connecter ici</h4>";
                            break;
                        case "problem": 
                            echo "<h4 id='problem-msg'>Email ou mot de passe invalide</h4>";
                            break;
                        default: 
                            echo "<h4 id='problem-msg'Problème lors de la connexion</h4>";
                            break;
                    }
                    $_SESSION['login-try'] = null;
                }
            ?>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>

</html>