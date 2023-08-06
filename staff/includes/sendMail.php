<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../../PHPMailer/src/Exception.php';
require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';

$endText = "<h3 style='color:#5bc77a;'>Support de L'Arche<br>Le repère des amis de la nature</h3><img src='https://cdn.larche.ovh/banner_email.png' alt='banniere' width='150px' height='50px'>";

function sendMail($email, $nameLastname, $emailBody, $emailObject, $emailAltBody) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
        $mail->isSMTP(); //Send using SMTP
        $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
        $mail->SMTPAuth = true; //Enable SMTP authentication
        $mail->Username = 'larcheovh@gmail.com'; //SMTP username
        $mail->Password = 'ibfuyocbfdbmlrap'; //SMTP password
        //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
        $mail->Port = 465;
        $mail->SMTPSecure = "ssl";
        $mail->CharSet = 'UTF-8'; //Format d'encodage à utiliser pour les caractères                        //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('larcheovh@gmail.com');
        $mail->FromName = "Support L'Arche"; //L'alias à afficher pour l'envoi
        $mail->addAddress($email, $nameLastname); //Add a recipient

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true); //Set email format to HTML
        $mail->Subject = $emailObject;
        $mail->Body = $emailBody;
        $mail->AltBody = $emailAltBody;

        $mail->send();
        //echo 'Message has been sent';

        unset($mail);
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

if (isset($_POST)) {
    if (!empty($_POST['requestType']))
        $requestType = $_POST['requestType'];

    if ($requestType === "sendMail") {
        if (!empty($_POST['user_id'])) {
            $toUser = $_POST['user_id'];
            $whatToSearch = "user_id";
        } else if (!empty($_POST['username'])) {
            $toUser = $_POST['username'];
            $whatToSearch = "username";
        } else if (!empty($_POST['email'])) {
            $toUser = $_POST['email'];
            $whatToSearch = "email";
        }

        $emailBody = $_POST['emailContent'];
        $emailAltBody = $emailBody . "Support de L'ArcheLe repère des amis de la nature";
        $emailObject = $_POST['emailTitle'];

        $emailBody = $emailBody . "<br>". $endText;

        include("./db-connect.php");
        $sqlGetUser = $bdd->prepare("SELECT user_id,username,email,name,lastname FROM user WHERE ($whatToSearch = ?)");
        $sqlGetUser->execute([$toUser]); // On exec
        $user = $sqlGetUser->fetch(PDO::FETCH_ASSOC); // On récupère en associant les valeurs
        $nameLastname = $user['name'] . " " . $user['lastname'];
        $email = $user['email'];

        session_start();
        if (sendMail($email,$nameLastname, $emailBody, $emailObject, $emailAltBody) === true) {
            $_SESSION['requestRes'] = "mail-sent"; 
            header("Location: ../module/gestion-mails");
            exit;
        } else {
            $_SESSION['requestRes'] = "mail-problem";
            header("Location: ../module/gestion-mails");
            exit;
        }
    }
}

?>