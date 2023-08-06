<?php 
$options = [
    "cookie_secure" => true,
    "gc_maxlifetime" => 3200
];
session_start($options);
$email = $_SESSION['email'];

if(isset($_POST['codemail'])){
    $codemail = $_POST['codemail'];

    if(!empty($codemail)){
        $codemail = strip_tags($codemail);
        $codemail = htmlspecialchars($codemail, ENT_QUOTES);
    } else{
        $msg = "Il faut remplir le champ.";
        header('location:signverifemail.php?message=' . $msg);
        exit;
    }
}

    include('./includes/db-connect.php');

    $testEmail = false ;
    $verifEmail = $bdd->prepare('SELECT reset_code FROM user WHERE email=?');
    $verifEmail->execute([$email]);
    $verifEmail = $verifEmail->fetch();
    $verifEmail = $verifEmail['reset_code'];
    
    //$email = $bdd->prepare("SELECT email FROM user WHERE email=?");
    if($verifEmail == $codemail){
        $testEmail = true ;
    } else {
        $msg ="Ce n'est pas le bon code.";
        // header('location:signverifemail.php?message=' . $msg);
        // exit();


    }


if ($testEmail === true) {
        // Si tout est correct et que la fontion renvoie true, on crée la session et le cookie 
        //foreach ($_COOKIE as $key => $value) if ($key !== "email") setcookie($key, $value, time() - 3600 * 10);

        $sqlGetUserId = $bdd->prepare("SELECT user_id, username FROM user WHERE email = ?");
        $sqlGetUserId->execute([$email]);
        $userId = $sqlGetUserId->fetch(PDO::FETCH_ASSOC);

        $_SESSION['logged'] = true; // On assigne à la variable globale
        $_SESSION['email'] = $email; // Email du user
        $_SESSION['user_id'] = $userId['user_id']; // L'id
        $_SESSION['user'] = $userId['username']; // On récupère la valeur de la clé username
        $_SESSION['lvl_perms'] = 1; // Niveau de permission du staff
        $_SESSION['role'] = "Membre";
        $_SESSION['user_pfp'] = "https://cdn.larche.ovh/users_pfp/default_pfp.png"; // le lien de la photo de profil
        setcookie('signed', $email, time() + 365 * 24 * 120, path: "/", domain: "larche.ovh", secure: true); // Création du cookie qui indique que la personne est co
        $_SESSION['login-try'] = "success"; // Pour le message d'erreur en cas de besoin
        header('location:accueil.php'); // On redirige
        exit;
    }
