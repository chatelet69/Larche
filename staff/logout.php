<?php
    session_start();
    $_SESSION = array(); // On vide la variable
    session_unset(); // On libère la variable dans la mémoire
    session_destroy(); // On détruit la session
    setcookie('signedStaff', null, time() - 3600, path:"/", domain:"staff.larche.ovh", secure: true); 
    unset($_COOKIE);
    // On supp le cookie en mettant un temps inférieur au temps actuel
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <title>Session terminée</title>
        <meta charset="UTF-8">
        <meta name="robots" content="noindex,nofollow">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=yes'>
        <style>
            h1,p { text-align: center; color: white; font-weight: bold; }
            p { font-size: 15px; }
            html {background-image: linear-gradient( 83.2deg,  rgba(150,93,233,1) 10.8%, rgba(99,88,238,1) 94.3% );}
        </style>
        <link rel="shortcut icon" href="./assets/favicon.ico">
    </head>
    <body>
        <h1>Vous avez bien été deconnecté</h1>
        <p>Vous serez redirigé vers la page de connexion dans 3 secondes</p>
        <script>
            localStorage.clear();
            setTimeout("location.href = 'https://staff.larche.ovh/login';", 3000);
            // Timeout avant la redirection
        </script>
    </body>
</html>