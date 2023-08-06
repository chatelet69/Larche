<?php
include("../includes/db-connect.php");
session_start();

if (isset($_POST) && !empty($_POST) || isset($_FILES) && !empty($_FILES)) {
    $user = $_SESSION['user_id'];
    if (isset($_POST['avatarSananes'])) {
        $sqlUpdateUserPfp = $bdd->prepare("UPDATE user SET pfp = ? WHERE user_id = ?");
        $res = $sqlUpdateUserPfp->execute(["https://cdn.larche.ovh/users_pfp/FS-avatar.png", $user]);
        $_SESSION['user_pfp'] = "https://cdn.larche.ovh/users_pfp/FS-avatar.png";
        
        echo ($res !== false) ? "ok" : "ko";
        exit;
    } else {
        $checkSize = getimagesize($_FILES["file"]["tmp_name"]);
        $newAvatar = $_FILES['file'];
        $dir = "/var/www/cdn/users_pfp/";
        $extension = pathinfo($_FILES['file']['name']);
        $file = $dir . $user . ".png";
        $sqlFile = "https://cdn.larche.ovh/users_pfp/" . $user . ".png";
        $uploadOk = ($checkSize !== false) ? 1 : 0;

        if ($_FILES['file']['type'] !== "image/png") {
            echo $_FILES['file']['type'];
            echo "Type du fichier inccorect";
            exit;
        }

        // On vérifie la taille du fichier, si elle est trop lourdre ou au contraire si c'est 0
        if ($_FILES["file"]["size"] > 500000 || $_FILES["file"]["size"] === 0) {
            echo "La taille du fichier est trop importante ou bien est vide";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Il y a eu un problème et le fichier n'a pas été importé";
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $file)) {
                $sqlUpdateUserPfp = $bdd->prepare("UPDATE user SET pfp = ? WHERE user_id = ?");
                $sqlUpdateUserPfp->execute([$sqlFile, $user]);
                $_SESSION['user_pfp'] = $sqlFile;
                echo "ok";
                exit;
            } else {
                echo "Il y a eu une erreur durant l'importation<br>";
            }
        }
    }
} else {
    echo "Fichier non trouvé";
}
?>