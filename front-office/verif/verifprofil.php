<?php
include('../includes/checksessions.php');
include('../includes/logHistoric.php');
$user_id = $_SESSION['user_id'];
if (isset($_POST)) {
    $lastname = $_POST['nom'];

    if (isset($_POST['nom']) && !empty($_POST['nom'])) {

        if (strlen($lastname) <= 45) {
            $lastname = strip_tags($lastname);
            $lastname = htmlspecialchars($lastname, ENT_QUOTES);
        } else {

            $_SESSION['returnMessage'] = "Le nom doit être inférieur à 45 caractères";
            header('location:https://larche.ovh/settings');
            exit;
        }
    }
    // echo "test";
    // echo $city;
    // echo $desc;
    // echo $email;


    if (isset($_POST['prenom']) && !empty($_POST['prenom'])) {
        $name = $_POST['prenom'];
        if (strlen($name) <= 45) {
            $name = strip_tags($name);
            $tname = htmlspecialchars($name, ENT_QUOTES);
        } else {

            $_SESSION['returnMessage'] = "Le prenom doit être inférieur à 45 caractères";
            header('location:https://larche.ovh/settings');
            exit;
        }
    }

    if (isset($_POST['ville']) && !empty($_POST['ville'])) {
        $city = $_POST['ville'];
        if (strlen($city) <= 50) {
            $city = strip_tags($city);
            $city = htmlspecialchars($city, ENT_QUOTES);
        } else {
            $_SESSION['returnMessage'] = "La ville doit être inférieur à 50 caractères";
            header('location:https://larche.ovh/settings');
            exit;
        }
    }

    


    //page d'inscription ----2----- 
    if (isset($_POST['email'])) {

        if (!empty($_POST['email'])) {
            $email = $_POST['email'];
            setcookie('email', $email, time() + 3600 * 24);

            if (strlen($email) <= 255) {
                $email = strip_tags($email);
                $email = htmlspecialchars($email, ENT_QUOTES);
            }
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['returnMessage'] = "Email invalide";
            header('location:https://larche.ovh/settings');
            exit;
        }


        // $newsletter = (isset($_POST['newsletter']) && $_POST['newsletter'] == 'on') ? 1 : 0;
    }

    //page d'inscription ----3----- 
    if (isset($_POST['username'])) {
        if (!empty($_POST['username'])) {
            $username = $_POST['username'];
            if (strlen($username) <= 32) {
                $username = strip_tags($username);
                $username = htmlspecialchars($username, ENT_QUOTES);
            } else {

                $_SESSION['returnMessage'] = "Ton pseudo dépasse 32 caractères";
                header('location:https://larche.ovh/settings');
                exit;
            }
        }

        if (isset($_POST['description'])) {
            $desc = $_POST['description'];

            if (strlen($desc) <= 255) {
                $desc = strip_tags($desc);
                $desc = htmlspecialchars($desc);
            } else {
                $_SESSION['returnMessage'] = "La description est trop grande.";
                header('location:https://larche.ovh/settings');
                exit;
            }
        }


    }

    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['ville']) || empty($_POST['email']) || empty($_POST['username'])) {
        $_SESSION['returnMessage'] = "Vous devez remplir tous les champs.";
        header('location: https://larche.ovh/settings');
        exit;
    }

    
}

try {
include('./includes/db-connect.php');
    $verif = 'UPDATE user SET username=?, email=?, name=?, lastname=?, city=?, description=? WHERE user_id=?';
    $sqlCheck = $bdd->prepare($verif);
    $sqlCheck->execute([$username, $email, $name, $lastname, $city, $desc, $user_id]);

    $result = $sqlCheck->rowCount();

    if ($result > 0) {        
        $_SESSION['correctMessage'] = "Les informations de l'utilisateur ont été mises à jour avec succès.";
        $msg = "Les informations de l'utilisateur ont été mises à jour avec succès.";
        header('location: https://larche.ovh/settings');
        exit();

    } else {
            $_SESSION['returnMessage'] = "Il n'y a eu aucun changement...";
            header('location: https://larche.ovh/settings');
            exit(); 
    }
} catch (PDOException $e) {
    die('Erreur PDO : ' . $e->getMessage());
};

