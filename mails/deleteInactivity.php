<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '/var/www/PHPMailer/src/Exception.php';
require '/var/www/PHPMailer/src/PHPMailer.php';
require '/var/www/PHPMailer/src/SMTP.php';
 
include("/var/www/mails/db-connect.php");

function sendMail($email, $nameLastname, $emailBody, $emailObject, $emailAltBody)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = false; 
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true; 
        $mail->Username = 'mail_source'; 
        $mail->Password = 'a_remplir'; 
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->CharSet = 'UTF-8';

        //Recipients
        $mail->setFrom('mail_source');
        $mail->FromName = "Support L'Arche"; //L'alias à afficher pour l'envoi
        $mail->addAddress($email, $nameLastname); //Add a recipient

        $endBody = "<div style='text-align:center'><h3 style='color:#5bc77a;'>Support de L'Arche<br>Le repère des amis de la nature</h3><img src='https://cdn.larche.ovh/banner_email.png' alt='banniere' width='150px' height='50px'></div>";
        $emailBody = $emailBody. "<br>". $endBody;
        //Content
        $mail->isHTML(true);
        $mail->Subject = $emailObject;
        $mail->Body = $emailBody;
        $mail->AltBody = $emailAltBody;

        $mail->send();

        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

$range = $range+2;
$sqlGetInacUsers = $bdd->prepare("SELECT username,user.user_id,user.email,name, MAX(logs.date) AS last_login 
FROM user INNER JOIN logs ON user.user_id = logs.user_id
WHERE user.mail_inac = 0 AND user.status >= 1
GROUP BY user.username, user.name, user.lastname HAVING last_login <= now() - INTERVAL ? WEEK");
$sqlGetInacUsers->execute([$range]);
$usersToSend = $sqlGetInacUsers->fetchAll(PDO::FETCH_ASSOC);

foreach ($usersToSend as $user => $array) {
    $altBody = "Bonjour ". $usersToSend[$user]['name']. "<br>Votre compte a été supprimé suite à votre inactivité<br>";
    $emailBody = "<div><h2>Bonjour ".$usersToSend[$user]['name']. "</h2><br><p>Suitre à votre inactivité, votre compte a été supprimé.</p></div>";
    sendMail($usersToSend[$user]['email'], $usersToSend[$user]['name'], $emailBody, "Suppresion du compte", $altBody);
    $sqlGetInacUsers = $bdd->prepare("DELETE FROM user WHERE user_id = ? AND email = ?");
    $sqlGetInacUsers->execute([$usersToSend[$user]['user_id'], $usersToSend[$user]['email']]);
}

?>