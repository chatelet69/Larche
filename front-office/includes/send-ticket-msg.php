<?php
    include('./checksessions.php');
    include('./db-connect.php');

    if(isset($_POST['id'])){
        if (empty($_POST['id'])) {
            echo 'Erreur';
            exit;
        }else{
            $id = $_POST['id'];
            $id = htmlspecialchars($id);
            $id = strip_tags($id);
        }
    }else{
        echo "Erreur";
        exit;
    }

    if(isset($_POST['msg'])){
        if (!empty($_POST['msg'])) {
            $msg = $_POST['msg'];
            $msg = htmlspecialchars($msg);
            $msg = strip_tags($msg);

            $check = $bdd -> prepare('SELECT id_ticket FROM tickets WHERE id_ticket = ? AND user_id_author = ?');
            $check -> execute([$id, $_SESSION['user_id']]);
            $check = $check -> fetchALL(PDO::FETCH_ASSOC);

            if (!empty($check)) {
        
                if (strlen($msg)>255) {
                    echo "Votre message dépasse les 255 caractères autorisés";
                    exit;
                }
            }
        }
    }else{
        echo "Erreur";
        exit;
    }

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
                $_SESSION['err-msg-ticket'] = 'Le fichier doit être de type jpg, gif ou png';
                exit;
                }
            $timestamp = time();
            $array = explode('.', $_FILES['image-ticket']['name']);
            $extension = end($array);
    
            $maxsize = 2 * 1024 * 1024;
    
            if($_FILES['image-ticket']['size'] > $maxsize){
                echo "Fichier trop lourd";
                exit;
            }
            $filename =  $_SESSION['user_id'] . '-' . $timestamp . '.' . $extension; 
    
            $destination2 = '/var/www/cdn/ticket_picture/' . $filename;
            $destination = 'https://cdn.larche.ovh/ticket_picture/' . $filename;
            
            if(!move_uploaded_file($_FILES['image-ticket']['tmp_name'], $destination2)){
                echo 'le fichier n\' a pas pu être enregistré ';
                exit;
            }
        }
    }

    if (empty($msg) && empty($_FILES['image-ticket'])) {
        echo 'Message vide';
        exit;
    }

    $sendMsg = $bdd -> prepare('INSERT INTO tickets_convs (ticket_id, content, user_id_author, file) VALUES (?, ?, ?, ?)');
    $respons = $sendMsg -> execute([$id, (isset($msg) ? $msg : ''), $_SESSION['user_id'], (isset($destination) ? $destination : '')]);
    if ($respons) {
        echo 'ok';
    }else{
        echo "Erreur lors de l'envoi des données";
    }