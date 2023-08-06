<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '/var/www/PHPMailer/src/Exception.php';
require '/var/www/PHPMailer/src/PHPMailer.php';
require '/var/www/PHPMailer/src/SMTP.php';

$fileText  = "/var/www/cdn/newsletter/inactivityText.txt";
$formText = file_get_contents($fileText);
$formText = nl2br($formText);

include("/var/www/mails/db-connect.php");

function sendMail($email, $nameLastname, $emailBody, $emailObject, $emailAltBody)
{
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true; 
        $mail->Username = 'mail_source';
        $mail->Password = 'mail_mdp'; 
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('mail_source');
        $mail->FromName = "Support L'Arche"; 
        $mail->addAddress($email, $nameLastname); 

        $endBody = "<div style='text-align:center'><h3 style='color:#5bc77a;'>Support de L'Arche<br>Le repère des amis de la nature</h3><img src='https://cdn.larche.ovh/banner_email.png' alt='banniere' width='150px' height='50px'></div>";
        $emailBody = $emailBody. "<br>". $endBody;
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

$sqlGetInacUsers = $bdd->prepare("SELECT username,user.email,name, MAX(logs.date) AS last_login 
FROM user INNER JOIN logs ON user.user_id = logs.user_id
WHERE user.mail_inac = 0 AND user.status >= 1
GROUP BY user.username, user.name, user.lastname HAVING last_login <= now() - INTERVAL ? WEEK");
$sqlGetInacUsers->execute([$range]);
$usersToSend = $sqlGetInacUsers->fetchAll(PDO::FETCH_ASSOC);

foreach ($usersToSend as $user => $array) {
    $altBody = "Bonjour ". $usersToSend[$user]['name']. $formText;
    $emailBody = "<div><h2>Bonjour ".$usersToSend[$user]['name']. "</h2><br><p>". $formText. "</p></div>";
    sendMail($usersToSend[$user]['email'], $usersToSend[$user]['name'], $emailBody, "Inactivité supérieure à 6 mois", $altBody);
    $sqlGetInacUsers = $bdd->prepare("UPDATE user SET mail_inac = 1 WHERE username= ? AND email = ?");
    $sqlGetInacUsers->execute([$usersToSend[$user]['username'], $usersToSend[$user]['email']]);
}

?>