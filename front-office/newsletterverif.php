<?php 
include('./includes/checksessions.php');
include('./includes/logHistoric.php');
$user_id = $_SESSION['user_id'];

$newsletter = (isset($_POST['newsletter']) && $_POST['newsletter'] == '1') ? 1 : 0;


include('./includes/db-connect.php');

$verif = 'UPDATE user SET newsletter_pref=? WHERE user_id=?';
$sqlCheck = $bdd->prepare($verif);
$sqlCheck->execute([$newsletter, $user_id]);
$result = $sqlCheck->rowCount();

if ($result > 0) {        

    $_SESSION['correctMessage'] = "Les informations de l'utilisateur ont été mises à jour avec succès.";
    header('location:https://larche.ovh/settings');
    exit;

} else {
    $_SESSION['returnMessage'] = "Il n'y a eu aucun changement...";
    header('location: https://larche.ovh/settings');
    exit(); 
}





















?>