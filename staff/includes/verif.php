<?php

// Fonction pour vérifier des variables globales afin de récupérer l'ip de la personne faisant la requête
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

// Récupération des données de la requête POST
if (isset($_POST) && !empty($_POST)) {
    //On vériife pour l'email et le mot de passe qu'ils sont pas vides
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        if (strlen($email) <= 100 && filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) <= 30) {
            $email = strip_tags($email); // strip_tags retire les balises html ou php 
            $email = htmlspecialchars($email, ENT_QUOTES); // encode les balises en caractères, on vérif donc deux fois
            $password = strip_tags($password);
            $password = htmlspecialchars($password, ENT_QUOTES); 
        } else {
            $_SESSION['login-try'] = "problem";
            header("Location: ../login.php"); // On redirect si la longueur de l'email est problématique
            exit;
        }
    }
}

// Fonction qui vérifie les infos de connexion
function checkLogin($email, $password)
{
    try {
        include("../../confc.php"); // Pour les creds
        $sql = 'mysql:host=' . $dbhost . ';dbname=' . $dbname.''; // Requête de connexion à la DB
        $bdd = new PDO("$sql", $dbuser, $dbpass); // On crée l'instance PDO
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pass = "x!".$password.";A4!";
        $hash = hash("sha512", $pass); // On hash le mot de passe en SHA512 en ayant rajoué notre salt
        $sqlCheck = $bdd->prepare("SELECT user_id,email,status,ip FROM user WHERE email=? AND password=?"); // On cherche la row dans la DB avec les infos
        $sqlCheck->execute([$email, $hash]); // on execute la requête sql avec l'email et le hash
        $check = $sqlCheck->fetch(PDO::FETCH_ASSOC); // On récupère ce que la DB ,ous renvoit

        // Si check est différent de false, c'est qu'une ligne a été trouvée
        if ($check !== false && !empty($check)) {
            $sqlUser = $bdd->prepare("SELECT user_id,username,status,pfp,name,lastname,link_banner FROM user WHERE email=?");
            $sqlUser->execute([$email]);
            $GLOBALS['user'] = $sqlUser->fetch(PDO::FETCH_ASSOC);
            // On stocke dans une variable globale à ce fichier des infos de l'utilisateur

            if ($check['ip'] == NULL) {
                $sqlUpdateIp = $bdd->prepare("UPDATE user SET ip=? WHERE user_id=?");
                $sqlUpdateIp->execute([getIp(), $check['user_id']]);
            }
        }
        // Ici on crée la log
        $sqlLogs = $bdd->prepare("INSERT INTO logs (email, user_id, success, ip, time, interface) VALUES (:email, :user_id, :success, :ip, :time, :interface)");
        $success = ($check === false || intval($check['status']) <= 3) ? 0 : 1;
        $time = date("H:i:s");
        $ip = getIp();
        $sqlLogs->execute([
            "email" => $email, 
            "user_id" => $GLOBALS['user']['user_id'],
            "success" => $success, 
            "ip" => $ip, 
            "time" => $time,
            "interface" => "Back-Office"
        ]);
        unset($bdd);

        return ($check !== false && intval($GLOBALS['user']['status']) >= 3) ? true : false;
    } catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

sleep(1);
if (checkLogin($email, $password)) {
    // Si tout est correct et que la fontion renvoie true, on crée la session et le cookie 
    $options = [
        "cookie_secure" => true,
        "cookie_path" => "/",
        "gc_maxlifetime" => 200000
    ];
    session_set_cookie_params(200000, "/", "staff.larche.ovh", true);
    session_start($options);
    $_SESSION['logged'] = true; 
    $_SESSION['email'] = $email; 
    $_SESSION['user_id'] = $GLOBALS['user']['user_id']; 
    $_SESSION['name'] = $GLOBALS['user']['name']; 
    $_SESSION['lastname'] = $GLOBALS['user']['lastname'];
    $_SESSION['user'] = $GLOBALS['user']['username']; 
    $_SESSION['lvl_perms'] = $GLOBALS['user']['status']; 
    $_SESSION['role'] = ($GLOBALS['user']['status'] < 4) ? "Modérateur" : "Admin";
    $_SESSION['themeMode'] = "set-now";
    $_SESSION['user_pfp'] = $GLOBALS['user']['pfp']; 
    setcookie('signedStaff', "$email", time() + 365 * 24 * 120, path:"/", domain:"staff.larche.ovh", secure: true);
    $_SESSION['login-try'] = "success"; 
    header('Location: ../index'); 
    exit;
} else {
    session_start();
    $_SESSION['login-try'] = "problem"; 
    header('Location: ../login');
    exit;
}

?>