<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require '/var/www/PHPMailer/src/Exception.php';
require '/var/www/PHPMailer/src/PHPMailer.php';
require '/var/www/PHPMailer/src/SMTP.php';

include("/var/www/html/includes/db-connect.php");

    $eventCheck = $bdd -> prepare('SELECT id_event FROM events WHERE DateDiff(date_event, Now()) = 1');
    $eventCheck -> execute();
    $events = array();
    $events = $eventCheck -> fetchAll(PDO::FETCH_ASSOC);
for($i=0; $i<count($events); $i = $i +1 ){
    $reminder = $bdd -> prepare('SELECT user.email, user.username, events.image, events.title, events.description, events.date_event, events.time FROM register_event, user, events WHERE user.user_id = register_event.user_id AND register_event.id_event = ? AND events.id_event = ?');
    $reminder -> execute([$events[$i]['id_event'], $events[$i]['id_event']]);
    $reminder = $reminder -> fetchALL(PDO::FETCH_ASSOC);
   
    if(!empty($reminder)){
        for($j=0; $j<count($reminder); $j = $j +1 ){
        $mail = new PHPMailer(true);
        $email = $reminder[$j]['email'];
        $nameLastname = $reminder[$j]['username'];
        $date = explode(':', $reminder[$j]['time']);
        array_pop($date);
        $time = implode('h', $date);
        $emailObject='Rappel de l\'évènement "' . $reminder[$j]['title'] . '" demain !';
        $emailBody = "<p>Bonjour " .  $reminder[$j]['username'] . " !</p><p>N'oubliez pas l'évènement \"" .  $reminder[$j]['title'] . "\" de demain à " .  $time . " :)</p><p>Si vous souhaitez vous désinscrire, faites le au plus vite sur notre site https://larche.ovh/events pour laissez la place aux autres utilisateurs !</p><p>Cordialement, l'Arche.";
        $emailAltBody = "Bonjour " . $reminder[$j]['username'] . " ! N'oubliez pas l'évènement \"" . $reminder[$j]['title'] . "\n de demain à " . $time . " :) Si vous souhaitez vous souhaitez vous désinscrire, faites le au plus vite sur notre site https://larche.ovh/events pour laissez la place aux autres utilisateurs ! Cordialement, l'Arche";

            try {
                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'mail_source';
                $mail->Password   =  'mail_mdp';
                $mail->Port       = 465;         
                $mail->SMTPSecure = "ssl";   
                $mail->CharSet = 'UTF-8';
            
                //Recipients
                $mail->setFrom('mail_source');
                $mail->FromName = "Support L'Arche";
                $mail->addAddress($email, $nameLastname);
            
                //Content
                $mail->isHTML(true);
                $mail->Subject = $emailObject;
                $mail->Body    = $emailBody;
                $mail->AltBody = $emailAltBody;
            
                $mail->send();
                //echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>