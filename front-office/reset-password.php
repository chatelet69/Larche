<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
include('./includes/db-connect.php');

if (isset($_POST['requestType'])) $requestType = $_POST['requestType'];

if ($requestType === "askCodeToReset") {
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];
        $check = $bdd->prepare('SELECT user_id,email, username, name, lastname FROM user WHERE email = ?');
        $check->execute([$email]);
        $user = $check->fetch(PDO::FETCH_ASSOC);

        if ($user !== false) {
            $username = $user['username'];
            $reset_code = rand(10000, 99999);
            $insertcode = $bdd->prepare('UPDATE user SET reset_code = ? WHERE email = ?');
            $insertcode->execute([$reset_code, $email]);

            $emailObject = "Mot de passe oublié";
            $emailBody = "<p>Bonjour " . $username . " ! Voici le code qu'il faut mettre sur notre site web afin de réinitialiser votre mot de passe : " . $reset_code . "</p>";
            $emailAltBody = "Bonjour " . $username . " ! Voici le code qu'il faut mettre sur notre site web afin de réinitialiser votre mot de passe :" . $reset_code;

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
                $mail->addAddress($email, $username); //Add a recipient

                //Content
                $mail->isHTML(true); //Set email format to HTML
                $mail->Subject = $emailObject;
                $mail->Body = $emailBody;
                $mail->AltBody = $emailAltBody;

                $mail->send();
                //echo 'Message has been sent';

                $recherche = basename(__FILE__, '.php'); //censé renvoyer le nom du fichié (à voir si ça marche, sinon tester avec $_SERVER['PHP_SELF']; ) // https://forum.phpfrance.com/php-debutant/recuperer-nom-page-nom-fichier-t33464.html
                session_start();

                $logs = $bdd->prepare('INSERT INTO historic (recherche, date, user_id) VALUES (?, ?, ?)');
                $recherche = basename($_SERVER['REQUEST_URI']); // Renvoi le nom de la page ou la requête a été faite
                $logs->execute([$recherche, date("Y-m-d H:i:s"), $user['user_id']]); // On exec

                $_SESSION['emailToResetPass'] = $email;
                header('location: https://larche.ovh/changer-mdp');
                exit;
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            header("Location: forgot-password");
            exit;
        }
    } else {
        session_start();
        $_SESSION['requestRes'] = "email_needed";
        header("Location: forgot-password");
        exit;
    }
} else if ($requestType === "changeNewPass") {
    if (isset($_POST['codeVerifEmail'])) $verifCode = $_POST['codeVerifEmail'];
    if (isset($_POST['emailUser'])) $emailUser = $_POST['emailUser'];
    $sqlGetUser = $bdd->prepare('SELECT user_id,email,username,reset_code FROM user WHERE email = ?');
    $sqlGetUser->execute([$emailUser]);
    $sqlUser = $sqlGetUser->fetch(PDO::FETCH_ASSOC);

    if (isset($sqlUser) && $sqlUser !== false) {
        $code = $sqlUser['reset_code'];
        if ($verifCode == $code) {
            echo "validCode";
            $_SESSION['validEmail'] = $emailUser;
        } else {
            echo "notValidCode";
        }
    }
}else if($requestType === ""){
    
}
?>