<?php
include("./checksessions.php");
include("./db-connect.php");

// TITLE 
if (isset($_POST['title'])) {
    if (!empty($_POST['title'])) {
        $titre = $_POST['title'];
        $titre = htmlspecialchars($titre);
        $titre = strip_tags($titre);
        if (strlen($titre)>40) {
            $msg = 'Veuillez remplir tous les champs.';
            header('location:https://larche.ovh/new-ticket?msg=' . $msg);
            exit;
        }
    }else{
        $msg = 'Veuillez remplir tous les champs.';
        header('location:https://larche.ovh/new-ticket?msg=' . $msg);
        exit;
    }
}else{
    $msg = 'Erreur lors de la création du ticket';
    header('location:https://larche.ovh/new-ticket?msg=' . $msg);
    exit;
}

// Demande / problème

if (isset($_POST['request'])) {
    if (!empty($_POST['request'])) {
        $request = $_POST['request'];
        $request = htmlspecialchars($request);
        $request = strip_tags($request);
    }else{
        $msg = 'Veuillez remplir tous les champs.';
        header('location:https://larche.ovh/new-ticket?msg=' . $msg);
        exit;
    }
}else{
    $msg = 'Erreur lors de la création du ticket';
    header('location:https://larche.ovh/new-ticket?msg=' . $msg);
    exit;
}

// FICHIER
if (isset($_FILES['image-ticket']) && $_FILES['image-ticket']['error'] != 4) {
    if(!empty($_FILES['image-ticket'])){
        $image = array();
        $image = $_FILES['image-ticket'];
        $acceptable = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/jpg'
        ];
        if(!in_array($_FILES['image-ticket']['type'], $acceptable)){
            $msg = 'Le fichier doit être de type jpg, gif ou png';
            header('location:https://larche.ovh/new-ticket?msg=' . $msg);
            exit;
            }
        $timestamp = time();
        $array = explode('.', $_FILES['image-ticket']['name']);
        $extension = end($array);

        $maxsize = 2 * 1024 * 1024;

        if($_FILES['image-ticket']['size'] > $maxsize){
            $msg = "Fichier trop lourd";
            header('location:https://larche.ovh/new-ticket?msg=' . $msg);
            exit;
        }
        $filename =  $_SESSION['user_id'] . '-' . $timestamp . '.' . $extension; 

        $destination2= '/var/www/cdn/ticket_picture/' . $filename;
        $destination = 'https://cdn.larche.ovh/ticket_picture/' . $filename;
        
        if(!move_uploaded_file($_FILES['image-ticket']['tmp_name'], $destination2)){
            $msg = 'le fichier n\' a pas pu être enregistré ';
            header('location:https://larche.ovh/new-ticket?msg=' . $msg);
            exit;
        }
    }
}

$sendTicket = $bdd -> prepare('INSERT INTO tickets (subject, content, user_id_author, date, file) VALUES (?, ?, ?, ?, ?)');
$sendTicket -> execute([$titre, $request, $_SESSION['user_id'], date('Y-m-d'), $destination]);

header('location:https://larche.ovh/support');
exit;
?>