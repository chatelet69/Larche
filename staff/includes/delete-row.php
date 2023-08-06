<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
// Récupération des données de la requête POST
if (!empty($_POST)) {
    if (isset($_POST['log-row'])) {
        $idLog = $_POST['log-row'];
        $idMember = $_POST['last-signup'];
    }
}

// Fonction qui supprime la row sélectionnée
session_start();
if (!isset($_SESSION) || $_SESSION['logged'] !== true || $_SESSION['email'] !== $_COOKIE['signedStaff']) {
    header("Location: https://staff.larche.ovh/login");
    exit;
}
$date = date(DATE_RFC2822);
include("./db-connect.php");
$staffId = $_SESSION['user_id'];
$staffUsername = $_SESSION['user'];
date_default_timezone_set("Europe/Paris");

if (isset($_POST['last-signup']) && !empty($_POST['last-signup'])) {
    $sqlDeleteLogs = $bdd->prepare("DELETE FROM user_created WHERE (user_id = $idMember)");
    $sqlDeleteLogs->execute();
    $message = "member-deleted";
} else if (isset($_POST['last-signup']) && !empty($_POST['last-signup'])) {
    $sqlDeleteLogs = $bdd->prepare("DELETE FROM logs WHERE (idlogs = $idLog)");
    $sqlDeleteLogs->execute();
    $message = "log-deleted";
}

if (isset($_POST['requestType'])) {
    $requestType = $_POST['requestType'];

    switch ($requestType) {
        // Delete a log
        case "deleteLog":
            if (isset($_POST['logId']))
                $logId = $_POST['logId'];
            // Ajout de la log staff
            $sqlBanMember = $bdd->prepare("INSERT INTO staff_actions (type, content, user_id, time) VALUES (?,?,?,?)");
            $sqlBanMember->execute(["log_deleted", "Suppression de la log : $logId", $staffId, date("H:i:s")]);
            $sqlDeleteLogs = $bdd->prepare("DELETE FROM logs WHERE (id_log = ?)");
            $sqlDeleteLogs->execute([$logId]);
            $redirect = "module/panel-logs";
            break;
        // Ban Member
        case "banMember":
            if (isset($_POST['userIdToBan']))
                $userId = $_POST['userIdToBan'];

            $staffId = $_SESSION['user_id'];

            $sqlGetUserStatus = $bdd->prepare("SELECT status FROM user WHERE user_id = ?");
            $sqlGetUserStatus->execute([$userId]);
            $userStatus = $sqlGetUserStatus->fetch(PDO::FETCH_ASSOC);
            $userStatus = intval($userStatus['status']);

            $sqlVerifStaff = $bdd->prepare("SELECT status FROM user WHERE user_id = ?");
            $sqlVerifStaff->execute([$staffId]);
            $staffStatus = $sqlVerifStaff->fetch(PDO::FETCH_ASSOC);
            $staffStatus = intval($staffStatus['status']);

            if (($staffStatus < 5 && $userStatus >= 4) || ($staffStatus < 4 && $userStatus > 3)) {
                $_SESSION['returnMessage'] = "forbidden";
                header("Location:../module/gestion-membres");
                exit;
            } else {
                require '../../PHPMailer/src/Exception.php';
                require '../../PHPMailer/src/PHPMailer.php';
                require '../../PHPMailer/src/SMTP.php';
                $sqlBanMember = $bdd->prepare("INSERT INTO staff_actions (type, content, user_id, time) VALUES (?,?,?,?)");
                $sqlBanMember->execute(["account_banned", "Bannissement utilisateur : $userId", $staffId, date("H:i:s")]);

                $sqlGetuserToBan = $bdd->prepare("SELECT ip,email,name,lastname FROM user WHERE user_id = ?");
                $sqlGetuserToBan->execute([$userId]);
                $userToBan = $sqlGetuserToBan->fetch(PDO::FETCH_ASSOC);
                //$userIp =  $userToBan['ip'];
                //exec("echo 'Require not ip $userIp' >> /etc/apache2/ipblacklist");

                $sqlBanMember = $bdd->prepare("UPDATE user SET status=0 WHERE user_id = ?");
                $sqlBanMember->execute([$userId]);
                $_SESSION['returnMessage'] = "member_banned";
                $redirect = "module/gestion-membres";

                $mail = new PHPMailer(true);

                try {
                    $mail->SMTPDebug = false; //Enable verbose debug output
                    $mail->isSMTP(); //Send using SMTP
                    $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
                    $mail->SMTPAuth = true; //Enable SMTP authentication
                    $mail->Username = 'larcheovh@gmail.com'; //SMTP username
                    $mail->Password = 'ibfuyocbfdbmlrap'; //SMTP password
                    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;           //Enable implicit TLS encryption
                    $mail->Port = 465;
                    $mail->SMTPSecure = "ssl";
                    $mail->CharSet = 'UTF-8'; //Format d'encodage à utiliser pour les caractères    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('larcheovh@gmail.com');
                    $mail->FromName = "Support L'Arche"; //L'alias à afficher pour l'envoi
                    $mail->addAddress($userToBan['email'], ($userToBan['name']." ". $userToBan['lastname'])); //Add a recipient

                    $emailBody = "<div>Bonjour <strong>". $userToBan['name']."</strong>.<br>
                    Nous vous contactons afin de vous informer que vous avec été malheureusement banni de L'Arche.<br>
                    Vous pouvez contacter notre support à larcheovh@gmail.com</div>";

                    $mail->isHTML(true); 
                    $mail->Subject = "Bannissement de L'Arche";
                    $mail->Body = $emailBody;
                    $mail->AltBody = "Bonjour ".$userToBan['name'].", vous recevez ce message car vous avec été banni de L'Arche.";

                    $mail->send();
                } catch (Exception $e) {
                    header("Location: ../module/gestion-membres");
                    exit;
                }
                unset($mail);
                $redirect = false;
                echo "<script>location.href = 'https://staff.larche.ovh/module/gestion-membres'</script>";
                exit;
            }
        // Unban Member
        case "unbanMember":
            if (isset($_POST['userIdToUnban']))
                $userId = $_POST['userIdToUnban'];
            $sqlBanMember = $bdd->prepare("INSERT INTO staff_actions (type, content, user_id, time) VALUES (?,?,?,?)");
            $sqlBanMember->execute(["account_unbanned", "Débannissement user : $userId", $staffId, date("H:i:s")]);

            //$userIp =  $userToBan['ip'];
            //exec("sed -i '/Require not ip $userIp/d' /etc/apache2/ipblacklist");

            $sqlBanMember = $bdd->prepare("UPDATE user SET status=1 WHERE user_id = ?");
            $sqlBanMember->execute([$userId]);
            $_SESSION['returnMessage'] = "member_unbanned";
            $redirect = "module/gestion-membres";
            break;
        // Delete member
        case "deleteMember": {
            if (isset($_POST['userIdToDelete']))
                $userId = intval($_POST['userIdToDelete']);

            // Ajout de la log staff
            $sqlBanMember = $bdd->prepare("INSERT INTO staff_actions (type, content, user_id, username, time) VALUES (?,?,?,?,?)");
            $sqlBanMember->execute(["account_deleted", "Suppression du user : $userId", $staffId, $staffUsername, date("H:i:s")]);

            $sqlBanMember = $bdd->prepare("DELETE FROM user WHERE user_id=?");
            $sqlBanMember->execute([$userId]);

            $_SESSION['returnMessage'] = "member_deleted";
            $redirect = "module/gestion-membres";
            break;
        }
        case "deleteObj": {
            $acceptables = array("event", "ticket", "article");
            $deleteType = htmlspecialchars($_POST['type'], ENT_QUOTES);
            if (in_array($deleteType, $acceptables)) {
                if (isset($_POST['id'])) $objId = intval($_POST['id']);
                
                $sqlReq = $bdd->prepare("INSERT INTO staff_actions (type, content, user_id, time) VALUES (?,?,?,?)");
                $sqlReq->execute([$deleteType."_deleted", "Suppression $deleteType $objId", $staffId, date("H:i:s")]);
                
                $req = "DELETE FROM ".$deleteType."s WHERE id_".$deleteType." = ?";
                $sqlReq = $bdd->prepare($req);
                $sqlReq->execute([$objId]);
                $sqlReq = $sqlReq -> fetch();
                
                $_SESSION['returnMessage'] = ($sqlReq !== false ) ? $deleteType."_deleted" : "error";
                $redirect = "module/gestion-".$deleteType."s";
                break;
            } else {
                $redirect = "index";
                $_SESSION['returnMessage'] = "incorrect_value";
                break;
            }
        }
        case "deleteCaptcha": {
            $captchaId = htmlspecialchars($_POST['captchaId'], ENT_QUOTES);
            if (!is_numeric($captchaId)) {
                echo "Le captcha envoyé est incorrect";
                exit;
            } else {
                $staff = $bdd->prepare("SELECT captcha_images.name AS name_image,user.status as status 
                FROM user,captcha_images WHERE user_id = ? AND num = ?;");
                $staff -> execute([$staffId, $captchaId]);
                $staff = $staff->fetch(PDO::FETCH_ASSOC);

                if (intval($staff['status']) > 3) {
                    $deleteCaptcha = $bdd->prepare("DELETE FROM captcha_images WHERE num = ?");
                    $res = $deleteCaptcha->execute([$captchaId]);

                    $file = "/var/www/cdn/captcha/".$staff['name_image'].".png";
                    $del = unlink($file);

                    $sqlLogStaff = $bdd->prepare("INSERT INTO staff_actions (type,content,date,user_id,time) VALUES (?,?,?,?,?)");
                    $sqlLogStaff->execute(["captcha_deleted", "Captcha ".$captchaId." supprimé",date("Y-m-d"),$staffId, date("h:i:s")]);

                    $redirect = false;
                    if ($res !== false && $del !== false) {
                        echo "ok";
                    } else {
                        echo "Erreur";
                    }
                    break;
                } else {
                    echo "Vous n'avez pas la permission";
                    exit;
                }
            }
        }
        case "deleteAvatar": {
            $avatarId = htmlspecialchars($_POST['avatarId'], ENT_QUOTES);
            if (!is_numeric($avatarId)) {
                echo "Le captcha envoyé est incorrect";
                exit;
            } else {
                $staff = $bdd->prepare("SELECT avatar.link AS name_image,user.status as status 
                FROM user,avatar WHERE user_id = ? AND id_item = ?;");
                $staff -> execute([$staffId, $avatarId]);
                $staff = $staff->fetch(PDO::FETCH_ASSOC);

                if (intval($staff['status']) >= 3) {
                    $deleteCaptcha = $bdd->prepare("DELETE FROM avatar WHERE id_item = ?");
                    $res = $deleteCaptcha->execute([$avatarId]);

                    $file = "/var/www/html/".$staff['name_image'];
                    $del = unlink($file);

                    $sqlLogStaff = $bdd->prepare("INSERT INTO staff_actions (type,content,date,user_id,time) VALUES (?,?,?,?,?)");
                    $sqlLogStaff->execute(["avatar_deleted", "Avatar ".$avatarId." supprimé",date("Y-m-d"),$staffId, date("h:i:s")]);

                    $redirect = false;
                    if ($res !== false && $del !== false) {
                        echo "ok";
                    } else {
                        echo "Erreur";
                    }
                    break;
                } else {
                    echo "Vous n'avez pas la permission";
                    exit;
                }
            }
        }
        default:
            echo "Erreur";
            header("Location: ../index");
            exit;
    }
}

if($redirect !== false && !empty($redirect)) {
    header("Location: https://staff.larche.ovh/$redirect");
    exit;
}

?>