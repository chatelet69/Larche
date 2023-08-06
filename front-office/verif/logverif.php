<?php

if (isset($_COOKIE['signed']) && !empty($_COOKIE['signed'])) {
    header('location: https://larche.ovh/accueil');
  }

function getIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

  if (isset($_POST['email']) && !empty($_POST['email'])) {

        setcookie('email', $_POST['email'], time()+3600*24);
    }
    if(empty($_POST['email']) || empty($_POST['password'])){

        $msg = "Vous devez remplir les 2 champs.";
        header('location:https://larche.ovh/login?message=' . $msg);
        exit();
    }
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $msg = "Email invalide";
        header('location: https://larche.ovh/login?message=' . $msg);
        exit;
    }

    if(isset($_POST['email']) && !empty($_POST['email'])){
        $email = $_POST['email'];
        if (strlen($email) <= 100) {
            $email = strip_tags($email);
            $email = htmlspecialchars($email, ENT_QUOTES);
        }else{
            $msg= "Adresse mail incorrecte";
            header('location: https://larche.ovh/login?message=' . $msg);
            exit;
        }
    }

    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = $_POST['password'];
        if (strlen($password) <= 30) {
            $password = strip_tags($password);
            $password = htmlspecialchars($password, ENT_QUOTES);
        }else{
            $msg= "Mot de passe incorrecte";
            header('location: https://larche.ovh/login?message=' . $msg);
            exit;
        }
    }

function checkLogin($email, $password)
{
    try {
        include("../../confc.php"); // Pour les creds de la bdd
        $sql = 'mysql:host=' . $dbhost . ';dbname=' . $dbname.''; // Requête de connexion à la DB
        $bdd = new PDO("$sql", $dbuser, $dbpass); // On crée l'instance PDO
        //$bdd = new PDO("mysql:host=".$dbhost.";dbname=".$dbname."", $dbuser, $dbpass);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $date = date(DATE_RFC2822); 
        $pass = "x!".$password.";A4!";
        $hash = hash("sha512", $pass); // On hash le mot de passe en SHA512 en ayant rajoué notre salt
        //$bhash = password_hash($password, PASSWORD_BCRYPT);
        $sqlCheck = $bdd->prepare("SELECT user_id,status FROM user WHERE email=? AND password=?"); // On cherche la row dans la DB avec les infos
        $sqlCheck->execute([$email, $hash]); // on execute la requête sql avec l'email et le hash
        $check = $sqlCheck->fetch(); // On récupère ce que la DB nous renvoit

        // Si check est différent de false, c'est qu'une ligne a été trouvée
        if ($check !== false && !empty($check)) {
            $sqlUser = $bdd->prepare("SELECT user_id,username,status,pfp FROM user WHERE email=?");
            $sqlUser->execute([$email]);
            $GLOBALS['user'] = $sqlUser->fetch(PDO::FETCH_ASSOC);
            // On stocke dans une variable globale à ce fichier des infos de l'utilisateur
        }

        // Ici on crée la log
        $sqlLogs = $bdd->prepare("INSERT INTO logs (email, user_id, date, success, ip, time) VALUES (?,?,?,?,?,?)");
        $success = ($check === false) ? 0 : 1;
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $ip = getIp();
        $sqlLogs->execute([$email, $check['user_id'], $date, $success, $ip, $time]);

        if ((isset($check) && !empty($check) ) && (intval($GLOBALS['user']['status']) === 0 || intval($check['status']) === 0)) {
            $check = false;
            header("Location:../login?message=Vous êtes banni");
            exit;
        }

        return ($check !== false) ? true : false;
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

if (checkLogin($email, $password)) {
    // Si tout est correct et que la fontion renvoie true, on crée la session et le cookie 
    $options = [
        "cookie_secure" => true,
        "gc_maxlifetime" => 3200
    ];
    session_start($options);
    $_SESSION['logged'] = true; // On assigne à la variable globale
    $_SESSION['email'] = $email; // Email du user
    $_SESSION['user_id'] = $GLOBALS['user']['user_id']; // L'id
    $_SESSION['user'] = $GLOBALS['user']['username']; // On récupère la valeur de la clé username
    $_SESSION['lvl_perms'] = $GLOBALS['user']['status']; // Niveau de permission du staff
    $_SESSION['role'] = ($GLOBALS['user']['status'] < 4) ? "modérateur" : "Admin";
    $_SESSION['user_pfp'] = $GLOBALS['user']['pfp']; // le lien de la photo de profil
    setcookie('signed', "$email", time() + 3200, path:"/", domain:"larche.ovh", secure: true); // Création du cookie qui indique que la personne est co
    $_SESSION['login-try'] = "success"; // Pour le message d'erreur en cas de besoin
    header('Location: https://larche.ovh/accueil'); // On redirige
    exit;
} else {
    session_start();
    $msg = "Email ou mot de passe incorrect.";
    header('location:https://larche.ovh/login?message=' . $msg );
    exit;
}