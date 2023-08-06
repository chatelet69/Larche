<?php
    session_start();
    include('./db-connect.php');

    if (isset($_POST['event'])) {
        if (!empty($_POST['event'])) {
            $event_id = $_POST['event'];
            $event_id = htmlspecialchars($event_id);
            $event_id = strip_tags($event_id);

                $checkSignUp = $bdd->prepare('SELECT user_id FROM register_event WHERE id_event = ? AND user_id = ?');
                $checkSignUp->execute([$event_id, $_SESSION['user_id']]);
                $checkSignUp = $checkSignUp->fetch(PDO::FETCH_ASSOC);

        if (!empty($checkSignUp)) {
            $UnSignUp = $bdd -> prepare('DELETE FROM register_event WHERE id_event = ? AND user_id = ?');
            $UnSignUp->execute([$event_id, $_SESSION['user_id']]);
            
            header('location: https://larche.ovh/events');
            exit();
        }else{
            header('location: https://larche.ovh/events');
            exit();
        } 
        }else {
            header('location: https://larche.ovh/events');
            exit();
        }
    } else {
        header('location: https://larche.ovh/events');
        exit();
    }
?>