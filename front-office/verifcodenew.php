<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

$email = $_SESSION['email'];
include('./includes/db-connect.php');

    $q = 'SELECT username FROM user WHERE email=?';
    $sql = $bdd->prepare($q);
    $sql->execute([$email]);
    $sql = $sql->fetch(PDO::FETCH_ASSOC); // Récupère la 1e ligne de résultat ou false
    $username = $sql['username'];

    $reset_code = rand(10000, 99999);
    $insertcode = $bdd->prepare('UPDATE user SET reset_code = ? WHERE email = ?');
    $insertcode->execute([$reset_code, $email]);
    $emailObject = "Vérification de compte";
    $emailBody = "<p>Bonjour " . $username . " voici un nouveau code qu'il faut mettre sur notre site web afin de vérifier votre compte: <strong>" . $reset_code . "</strong></p>";
    $emailAltBody = "Bonjour" . $username . " voici un nouveau code qu'il faut mettre sur notre site web afin de réinitialiser votre mot de passe :" . $reset_code;

    $mail = new PHPMailer(true);
    $testEmail = false;

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
        $mail->addAddress($email, $username);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $emailObject;
        $mail->Body    = $emailBody;
        $mail->AltBody = $emailAltBody;

        $mail->send();
        //echo 'Message has been sent';
        header('location:signverifemail.php');
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }


    unset($mail);
