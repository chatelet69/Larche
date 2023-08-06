<?php 
include('../includes/checksessions.php');
include('../includes/logHistoric.php');
$user_id = $_SESSION['user_id'];


if(isset($_POST['password']) && isset($_POST['pwdchangeconfirm'])){
    if(!empty($_POST['password']) && !empty($_POST['pwdchangeconfirm'])){
        $pwd = $_POST['password'];
        $pwdconfirm = $_POST['pwdchangeconfirm'];

        $pwd = strip_tags($pwd);
        $pwd = htmlspecialchars($pwd, ENT_QUOTES);

        $pwdconfirm = strip_tags($pwdconfirm);
        $pwdconfirm = htmlspecialchars($pwdconfirm, ENT_QUOTES);


    }
}

try {
    include('../includes/db-connect.php');
        $pass = "x!" . $pwd . ";A4!";
        $hash = hash("sha512", $pass); 

        $q = 'UPDATE user SET password=? WHERE user_id=?';
        $sqlCheck = $bdd->prepare($q);
        $sqlCheck->execute([$hash, $user_id]);
    
        $result = $sqlCheck->rowCount();
    
        if ($result > 0) {        
            $_SESSION['correctMessage'] = "Le mot de passe a été modifié avec succès.";
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
