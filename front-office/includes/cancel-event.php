<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

session_start();
include('./db-connect.php');
if (isset($_POST['motif'])) {
    if (!empty($_POST['motif'])) {
        $motif = $_POST['motif'];
        $motif = htmlspecialchars($motif);
        $motif = strip_tags($motif);

        if (strlen($motif)>255) {
            echo 'motif trop long';
        }
    }else{
        echo 'Veuillez renseigner le motif';
        exit;
    }
}else{
    echo 'Erreur lors de l\'envoi du motif';
    exit;
}

if (isset($_POST['id'])) {
    if(!empty($_POST['id'])){
        $event_id = $_POST['id'];
        $event_id = htmlspecialchars($event_id);
        $event_id = strip_tags($event_id);

        $checkEventAuthor = $bdd->prepare('SELECT id_event, id_author, title, date_event, `time` FROM events WHERE id_event = ? AND id_author = ?');
        $checkEventAuthor->execute([$event_id, $_SESSION['user_id']]);
        $checkEventAuthor = $checkEventAuthor->fetch(PDO::FETCH_ASSOC);
        
        $date = explode('-', $checkEventAuthor['date_event']);
      $end = end($date);
      $date[2] = $date[0];
      $date[0] = $end;
      $date = implode(' / ', $date);

      $time = explode(':', $checkEventAuthor['time']);
      array_pop($time);
      $time = implode('h', $time);

        if (!empty($checkEventAuthor)) {
        
            $findUsers = $bdd -> prepare('SELECT user.user_id, user.username, user.email FROM user, register_event WHERE  user.user_id = register_event.user_id AND id_event = ?');
            $findUsers -> execute([$event_id]);
            $user = $findUsers -> fetchAll(PDO::FETCH_ASSOC);

        for ($j=0; $j < count($user); $j++) {             
        $mail = new PHPMailer(true);
        $email = $user[$j]['email'];
        $nameLastname = $user[$j]['username'];
        $emailObject='Annulation de l\'évènement "' . $checkEventAuthor['title'] . '" !';
        $emailBody = "<p>Bonjour " .  $user[$j]['username'] . " !</p><p>Malheureusement, l'évènement \"" .  $checkEventAuthor['title'] . "\" du " . $date . " à " .  $time . " est annulé...</p><p>Motif : " . $motif . ".</p><p>Cordialement, l'Arche.";
        $emailAltBody = "Bonjour " . $user[$j]['username'] . " ! Malheureusement l'évènement \"" . $checkEventAuthor['title'] . "\n du " . $date . " à " . $time . " est annulé...\n Motif : " . $motif . ".\n Cordialement, l'Arche";
            try {
                //Server settings
                $mail->SMTPDebug = false;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'larcheovh@gmail.com';                     //SMTP username
                $mail->Password   =  'ibfuyocbfdbmlrap';                      //SMTP password
                //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
                $mail->Port       = 465;         
                $mail->SMTPSecure = "ssl";   
                $mail->CharSet = 'UTF-8'; //Format d'encodage à utiliser pour les caractères                        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
                //Recipients
                $mail->setFrom('larcheovh@gmail.com');
                $mail->FromName = "Support L'Arche";             //L'alias à afficher pour l'envoi
                $mail->addAddress($email, $nameLastname);     //Add a recipient
            
                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $emailObject;
                $mail->Body    = $emailBody;
                $mail->AltBody = $emailAltBody;
            
                $mail->send();
                //echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

        }

        $cancel_event = $bdd -> prepare('DELETE FROM events WHERE id_event = ? AND id_author = ?');
        $cancel_event->execute([$event_id, $_SESSION['user_id']]);

        echo 'ok';
        exit();
        }else{
            echo 'Mauvais id';
            exit();
        }

    }else{
        echo 'id vide';
        exit();
    }
}else{
    echo 'id inexistant';
    exit();
}

?>