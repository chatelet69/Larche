<?php
    session_start();
    $email = $_SESSION['email']; 
    setcookie('email', $email, time()-3600*24);
    $_SESSION = array(); // On vide la variable
    session_unset(); // On libère la variable dans la mémoire
    session_destroy(); // On détruit la session
    setcookie('signed', "$email", time() - 3600, path:"/", domain:"larche.ovh", secure: true); 
    // On supp le cookie en mettant un temps inférieur au temps actuel
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Déconnexion</title>
        <meta charset="UTF-8">
        <meta name="robots" content="noindex,nofollow">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=no'>
        <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
        <style>
            h1,p { text-align: center; color: black; }
            p { font-size: 15px; }
        </style>
    </head>
    <body>
        <h1>Vous avez bien été deconnectée</h1>
        <p>Vous serez redirigé vers la page de connexion dans 3 secondes</p>
        <script type="text/javascript">
            localStorage.clear();
            setTimeout("location.href = 'https://larche.ovh/index';", 3000);
            // Timeout avant la redirection
        </script>
    </body>
</html>