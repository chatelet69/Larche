<?php
$options = [
    "session.cookie_secure" => true,
    "session.gc_maxlifetime" => 2000
];
session_start($options);

if ($_COOKIE['signed'] !== $_SESSION['email']) {
    header("Location: ./login");
    exit;
}

if (!isset($_COOKIE) || empty($_COOKIE) || isset($_SESSION['logged']) === false || $_SESSION['logged'] !== true) {
    header("Location: ./login");
    exit;
} else if ($_SESSION['logged'] === true && !empty($_COOKIE) && isset($_COOKIE['signed'])) {
    include("./includes/dashboard.php");
    $user = [
        "username" => $_SESSION['user'],
        "lvl" => $_SESSION['lvl_perms']
    ];
    $staffName = $user['username'];
    $lvl = $user['lvl'];
}
?>

<?php

// Récupération des données de la requête POST
if (!empty($_POST) || !empty($_FILES)) {
    $sqlRequest = "";
    if (isset($_POST['email']) && !empty($_POST['new-email'])) {
        $email = $_POST['email'];
        if (strlen($email) <= 70) {
            $email = strip_tags($email);
            $email = htmlspecialchars($email, ENT_QUOTES);
            $sqlRequest = $sqlRequest . "email='$email'";
        } else {
            header("Location: ../../my-account?msg=email");
            exit;
        }
    }

    if (isset($_POST['password']) && !empty($_POST['new-password'])) {
        $password = $_POST['password'];
        if (strlen($password) <= 30) {
            $password = strip_tags($password);
            $password = htmlspecialchars($password, ENT_QUOTES);
        } else {
            header("Location: ../../my-account?msg=mdp");
            exit;
        }
    }

    if (!empty($_FILES['new-pfp'])) {
        $check = getimagesize($_FILES["new-pfp"]["tmp_name"]);
        
        $pfp = $_FILES['new-pfp'];
        var_dump($pfp);
        $users_dir = "https://cdn.larche.ovh/users_pfp/";
        $extension = pathinfo($_FILES['new-pfp']['name']);
        $user_file = $users_dir . basename($_SESSION['user_id']) . '.' . $extension['extension'];
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($user_file, PATHINFO_EXTENSION));
        
        $sqlRequest = $sqlRequest . "pfp='$user_file'";

        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            echo "Format du fichier non accepté (Il faut un PNG,JPG,GIF ou JPEG)";
            $uploadOk = 0;
        } 
        if ($_FILES["new-pfp"]["size"] > 500000) {
            echo "La taille du fichier est trop importante";
            $uploadOk = 0;
        } 

        if (file_exists($user_file)) {
            echo "Le fichier existe déjà";
            $uploadOk = 0;
        }
    
        if ($uploadOk == 0) {
            echo "Il y a eu un problème et le fichier n'a pas été importé";
        } else {
            if (move_uploaded_file($_FILES["new-pfp"]["tmp_name"], $user_file)) {
              //echo "Le fichier ". htmlspecialchars( basename( $_FILES["new-pfp"]["name"])). " a bien été importé";
            } else {
              echo "Il y a eu une erreur durant l'importation<br>";
            }
        }
    }
}

// Fonction qui qui vérifie les infos de connexion
try {
    $sqlRequest = str_replace(" ", ",", $sqlRequest);
    $date = date(DATE_RFC2822);
    include("../../../confc.php");
    $bdd = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . "", $dbuser, $dbpass);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*$userid = $_SESSION['user_id'];
    $sqlEditAccount = $bdd->prepare("UPDATE staff SET $sqlRequest WHERE idstaff = $userid");
    $date = date("Y-m-d");
    $sqlEditAccount->execute();*/

    //header("Location: ../../index");
    //exit;
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

?>