<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '/var/www/PHPMailer/src/Exception.php';
require '/var/www/PHPMailer/src/PHPMailer.php';
require '/var/www/PHPMailer/src/SMTP.php';

include("/var/www/mails/db-connect.php");

$fileStartText = "/var/www/cdn/newsletter/newsStartText.txt";
$firstText = file($fileStartText);
$startFormText = $firstText[0];

$fileEndText = "/var/www/cdn/newsletter/newsEndText.txt";
$lastText = file($fileEndText);
$endFormText = $lastText[0];

function sendMail($email, $nameLastname, $emailBody, $emailObject, $emailAltBody)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'mail_source';
        $mail->Password = 'mail_mdp';
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->CharSet = 'UTF-8';

        //Recipients
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


$sqlGetNewsUsers = $bdd->prepare("SELECT username,email,name,lastname FROM user WHERE newsletter_pref = 1");
$sqlGetNewsUsers->execute();
$usersToSend = $sqlGetNewsUsers->fetchAll(PDO::FETCH_ASSOC);

$sqlGetLastArticles = $bdd->prepare("SELECT title,username_author,name_category,date, picture FROM articles ORDER BY date DESC LIMIT 3");
$sqlGetLastArticles->execute();
$sqlLastArticles = $sqlGetLastArticles->fetchAll(PDO::FETCH_ASSOC);

$lastArticles = "";
foreach ($sqlLastArticles as $article => $arrayArticle) {
    $author = $sqlLastArticles[$article]['username_author'];
    $picture = $sqlLastArticles[$article]['picture'];
    $name = $sqlLastArticles[$article]['name_category'];
    $title = $sqlLastArticles[$article]['title'];
    $date = $sqlLastArticles[$article]['date']; 
    $str = "<div style='text-align:center;margin:auto;'><h3>Article de $author.</h3>
    <img style='width:300px;' src='$picture' alt='image'><p>Titre : $title<br>Catégorie : $name<br>Sorti le $date</p></div>";
    $lastArticles = $lastArticles . $str;
}

$emailBody = "<div style='text-align:center;'><h1 style='font-weight:bold;font-size:30px;'>$startFormText</h1><br>". $lastArticles . $endFormText . "</div>";

foreach ($usersToSend as $user => $array) {
    $nameLastname = $usersToSend[$user]['name'] . " " . $usersToSend[$user]['lastname'];
    sendMail($usersToSend[$user]['email'], $nameLastname, $emailBody, "Newsletter L'Arche", "ALTBODY");
}

?>