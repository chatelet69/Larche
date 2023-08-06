<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

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
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
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
    }
}
?>