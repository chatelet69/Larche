<?php
include('../includes/checksessions.php');
include('../includes/logHistoric.php');
include('../includes/db-connect.php');
$user_id = $_SESSION['user_id'];


$save = $_SESSION['user_pfp'];

if (isset($_FILES['pdp']) && $_FILES['pdp']['error'] != 4) {


    $acceptable = [
        'image/png'
    ];

    $maxSize = 5 * 1024 * 1024; // 10Mo en octets
    if ($_FILES['pdp']['size'] > $maxSize) {

        $_SESSION['returnMessage'] = 'Le fichier doit faire moins de 5Mo';
        header('location: https://larche.ovh/settings-photo');
        exit;
    }

    $array = explode('.', $_FILES['pdp']['name']); //portrait.min.png donne ['portrait', 'min', 'jpg']
    $extension = end($array);

    $i = 1;
    $timestamp = time(); // Renvoie le nb de secondes depuis le 1er janvier 1970


    $filename = $user_id . '_' . $timestamp . '.' . $extension;

    $destination = '/var/www/cdn/users_pfp/' . $filename;
    if (!move_uploaded_file($_FILES['pdp']['tmp_name'], $destination)) {
        $msg = 'Le fichier n\'a pas pu être enregistré.';
        $typeArticle = "danger";
        header('location:https://larche.ovh/settings');
        exit;
    }
}



$filename = 'https://cdn.larche.ovh/users_pfp/' . $filename;
$q = 'UPDATE user SET pfp=? WHERE user_id=? ';
$sqlCheck = $bdd->prepare($q);
$sqlCheck->execute([$filename, $user_id]);
$_SESSION['user_pfp'] = $filename;

$result = $sqlCheck->rowCount();

if ($save != $_SESSION['user_pfp']) {
    $_SESSION['correctMessage'] = "La photo de profil a bien été mis à jour !";
    header('location: https://larche.ovh/settings-photo');
    exit();

} else {
    $_SESSION['returnMessage'] = "Il n'y a eu aucun changement...";
    header('location: https://larche.ovh/settings');
    exit(); 
}
