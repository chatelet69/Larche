<?php 
include('../includes/checksessions.php');
include('../includes/logHistoric.php');
$user_id = $_SESSION['user_id'];

include('../includes/db-connect.php');

if(isset($_POST['pwddelete'])){
    $pwddelete = $_POST['pwddelete'];
    $pwddelete = strip_tags($pwddelete);
    $pwddelete = htmlspecialchars($pwddelete, ENT_QUOTES);

    $pwddelete = "x!".$pwddelete.";A4!";
    $hash = hash("sha512", $pwddelete); // On hash le mot de passe en SHA512 en ayant rajoué notre salt

    $q = 'SELECT  password FROM user WHERE user_id=?';
    $sql = $bdd->prepare($q);
    $sql->execute([$user_id]);
    $pwd = $sql->fetch(PDO::FETCH_ASSOC);
    $pwd = $pwd['password'];
    
    if($pwd == $hash){
        $q = 'DELETE FROM user WHERE user_id= ?';
        $sql = $bdd->prepare($q);
        $sql->execute([$user_id]);
        $result = $sql->rowCount();
        
        if($result > 0){
   
            $email = $_SESSION['email']; 
            setcookie('email', $_COOKIE['email'], time()-3600*24);
 
            $_SESSION = array(); // On vide la variable
            session_unset(); // On libère la variable dans la mémoire

            session_destroy(); // On détruit la session
            setcookie('signed', "$email", time() - 3600, path:"/", domain:"larche.ovh", secure: true); 
            header('location:https://larche.ovh/deleteaccount');
            exit;
        
        }
    } else {
    
            $_SESSION['returnMessage'] = "Ce n'est pas le bon mot de passe.";
            header('location:https://larche.ovh/settings');
            exit;
    
    }
}



