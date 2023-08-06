<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

//page d'inscription ----1-----
if (isset($_POST)) {
    if (isset($_POST['nom']) && !empty($_POST['nom'])) {
        $lastname = $_POST['nom'];
        setcookie('nom', $lastname, time() + 3600 * 24);
        if (strlen($lastname) <= 45) {
            $lastname = strip_tags($lastname);
            $lastname = htmlspecialchars($lastname, ENT_QUOTES);
        } else {
            $msg = "Le nom doit être inférieur à 45 caractères";
            header('location: https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }
    }

    if (isset($_POST['prenom']) && !empty($_POST['prenom'])) {
        $name = $_POST['prenom'];
        setcookie('prenom', $name, time() + 3600 * 24);
        if (strlen($name) <= 45) {
            $name = strip_tags($name);
            $tname = htmlspecialchars($name, ENT_QUOTES);
        } else {
            $msg = "Le prenom doit être inférieur à 45 caractères";
            header('location: https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }
    }

    if (isset($_POST['age']) && !empty($_POST['age'])) {
        $age = $_POST['age'];
        setcookie('age', $age, time() + 3600 * 24);

        $age = strip_tags($age);
        $age = htmlspecialchars($age, ENT_QUOTES);

    }

    if (isset($_POST['ville']) && !empty($_POST['ville'])) {
        $city = $_POST['ville'];
        setcookie('ville', $city, time() + 3600 * 24);
        if (strlen($city) <= 50) {
            $city = strip_tags($city);
            $city = htmlspecialchars($city, ENT_QUOTES);
        } else {
            $msg = "La ville doit être inférieur à 50 caractères";
            header('location: https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }
    }

    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['age']) || empty($_POST['ville'])) {
        $msg = "Vous devez remplir tous les champs.";
        header('location: https://larche.ovh/sign-up?message=' . $msg);
        exit;
    }
}

//page d'inscription ----2----- 
if (isset($_POST['email'])) {

    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        setcookie('email', $email, time() + 3600 * 24);
        $_SESSION['email'] = $email;
        if (strlen($email) <= 255) {
            $email = strip_tags($email);
            $email = htmlspecialchars($email, ENT_QUOTES);
        }
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Email invalide";
        header('location: https://larche.ovh/sign-up?message=' . $msg);
        exit;
    }

    if (isset($_POST['password'])) {
        if (!empty($_POST['password'])) {
            $pwd = $_POST['password'];
            $pwdconfirm = $_POST['pwdconfirm'];

            if ($pwd != $pwdconfirm) {
                $msg = "Il faut écrire le même mot de passe";
                header('location: https://larche.ovh/sign-up?message2=' . $msg);
                exit;
            }

            $pwd = strip_tags($pwd);
            $pwd = htmlspecialchars($pwd, ENT_QUOTES);
            $pwdconfirm = strip_tags($pwdconfirm);
            $pwdconfirm = htmlspecialchars($pwdconfirm, ENT_QUOTES);
        }
        if (strlen($pwd) <= 6) {
            $msg = "Votre mot de passe doit faire plus de 6 caractères !";
            header('location: https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }
        if (strlen($pwd) > 32) {
            $msg = "Votre mot de passe doit faire moins de 32 caractères ! ";
            header('location: https://larche.ovh/sign-up?message= ' . $msg);
        }

        if (empty($email) || empty($pwd) || empty($pwdconfirm)) {
            $msg = "Vous devez remplir tous les champs.";
            header('location:https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }
    }

    $newsletter = (isset($_POST['newsletter']) && $_POST['newsletter'] == 'on') ? 1 : 0;
}

//page d'inscription ----3----- 
if (isset($_POST['username'])) {
    if (!empty($_POST['username'])) {
        $username = $_POST['username'];
        setcookie('username', $username, time() + 3600 * 24);
        if (strlen($username) <= 32 && strlen($username)>4) {
            $username = strip_tags($username);
            $username = htmlspecialchars($username, ENT_QUOTES);
        } else if(strlen($username) < 4 ){
            $msg = "Votre pseudo doit avoir 4 caractères minimum.";
            header('location:https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }
        else if(strlen($username)>32) {
            $msg = "Votre pseudo dépasse 32 caractères";
            header('location:https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }
    }

    if (isset($_POST['description'])) {
        $desc = $_POST['description'];
        setcookie('description', $desc, time() + 3600 * 24);
        if (strlen($desc) <= 255) {
            $desc = strip_tags($desc);
            $desc = htmlspecialchars($desc);
        } else {
            $msg = "Vous avez beaucoup écrit ! Ecrivez moins... !";
            header('location:https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }
    }

    if (empty($_POST['username'])) {
        $msg = "Merci de remplir votre username.";
        header('location:https://larche.ovh/sign-up?message=' . $msg);
        exit;
    }

}
function checkSignUp($name, $lastname, $age, $city, $email, $pwd, $username, $desc, $newsletter)
{
    try {
        include("../confc.php"); // Pour les creds
        $sql = 'mysql:host=' . $dbhost . ';dbname=' . $dbname . ''; // Requête de connexion à la DB
        $bdd = new PDO("$sql", $dbuser, $dbpass); //    On crée l'instance PDO
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $date = date(DATE_RFC2822);

        $pass = "x!" . $pwd . ";A4!";
        $hash = hash("sha512", $pass); // On hash le mot de passe en SHA512 en ayant rajoué notre salt
        //On insère les données reçues       

        $s = 'SELECT username FROM user WHERE username=?';
        $sql = $bdd->prepare($s);
        $sql->execute([$username]);

        $verifuser = $sql->fetchAll(PDO::FETCH_ASSOC); // récupérer toutes les lignes de résultats et les mettre dans un tableau

        if(!empty($verifuser)){
            $msg = 'Pseudo déjà utilisé';
            header('location: https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }

        
        $verif = 'SELECT email FROM user WHERE email = ?';
        $sqlCheck = $bdd->prepare($verif);
        $success = $sqlCheck->execute([$email]);

        $results = $sqlCheck->fetchAll(PDO::FETCH_ASSOC); // récupérer toutes les lignes de résultats et les mettre dans un tableau

        if (!empty($results)) {
            $msg = 'Email déjà utilisé.';
            header('location: https://larche.ovh/sign-up?message=' . $msg);
            exit;
        }

        $q = "INSERT INTO user (ip, name, lastname, age, city, email, password, username, description, status, newsletter_pref) 
        VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $insert = $bdd->prepare($q);
        $ip = getIp();
        $respons = $insert->execute([$ip, $name, $lastname, $age, $city, $email, $hash, $username, $desc, 1, $newsletter]);

        $verif = 'SELECT username, user_id FROM user WHERE email=?';
        $sqlCheck = $bdd->prepare($verif);
        $sqlCheck->execute([$email]);

        $results = $sqlCheck->fetch(PDO::FETCH_ASSOC); // récupérer toutes les lignes de résultats et les mettre dans un tableau
        // Ici on crée la log
        $sqlLogs = $bdd->prepare("INSERT INTO logs (email, user_id, date, success, ip, time) VALUES (?,?,?,?,?,?)");
        $success = ($results == false) ? 0 : 1;
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $sqlLogs->execute([$email, $results['user_id'], $date, $success, $ip, $time]);
        if ($respons === false) { // erreur...
            $msg = 'Erreur lors de la création du compte.';
            header('location: https://larche.ovh/sign-up?message=' . $msg);
            exit;
        } else {
            return true;
        }
    } catch (PDOException $e) {
        die('Erreur PDO : ' . $e->getMessage());
    };
}

if (checkSignUp($name, $lastname, $age, $city, $email, $pwd, $username, $desc, $newsletter)) {

    include("../confc.php"); // Pour les creds
    $sql = 'mysql:host=' . $dbhost . ';dbname=' . $dbname . ''; // Requête de connexion à la DB
    $bdd = new PDO("$sql", $dbuser, $dbpass); //    On crée l'instance PDO
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $date = date(DATE_RFC2822);


    $reset_code = rand(10000, 99999);
    $insertcode = $bdd->prepare('UPDATE user SET reset_code = ? WHERE email = ?');
    $insertcode->execute([$reset_code, $email]);
    $emailObject = "Vérification de compte";
    $emailBody = "<p>Bonjour " . $username . " voici le code qu'il faut mettre sur notre site web afin de vérifier votre compte: <strong>" . $reset_code . "</strong></p>";
    $emailAltBody = "Bonjour" . $username . " voici le code qu'il faut mettre sur notre site web afin de réinitialiser votre mot de passe :" . $reset_code;

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

} else {
    // Si les credentials utilisées sont incorrectes, on redirige vers l'inscription avec un message d'erreur
    //session_start();
    $msg = "Erreur.";
    header('location: https://larche.ovh/sign-up?message=' . $msg);
    exit;
}
    //}
