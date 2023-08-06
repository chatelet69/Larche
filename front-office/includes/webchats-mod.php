<?php

include("./db-connect.php");

session_start();
if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    echo "Pas connecté";
    exit;
}

$acceptableImages = [
    "image/jpeg",
    "image/jpg",
    "image/png",
    "image/gif"
];

if (isset($_GET) && !empty($_GET) || (isset($_POST) && !empty($_POST))) {
    $requestType = (!empty($_GET['requestType'])) ? $_GET['requestType'] : $_POST['requestType'];

    switch ($requestType) {
        case "getConv": {
            $chatName = htmlspecialchars($_GET['chatName'], ENT_QUOTES);

            $sqlGetConv = $bdd->prepare("SELECT idchannel,category FROM webchat WHERE name = ?");
            $sqlGetConv->execute([$chatName]);
            $chat = $sqlGetConv->fetch(PDO::FETCH_ASSOC);
    
            if ($chat !== false) {
                $sqlGetConv = $bdd->prepare("SELECT id_message,content,user_id_author,type,username,
                DATE_FORMAT(date, '%d/%m/%Y') AS date_format, DATE_FORMAT(date, '%H:%i') AS time,pfp 
                FROM messages,user WHERE idchannel = ? AND user.user_id=messages.user_id_author ORDER BY date ASC");
                $sqlGetConv->execute([$chat['idchannel']]);
                $convs = $sqlGetConv->fetchAll(PDO::FETCH_ASSOC);
    
                if (!empty($convs)) {
                    for ($i = 0; $i < count($convs); $i++) {
                        $thisMessage = $convs[$i];
                        $userPfp = $thisMessage['pfp'];
                        $id_message = $thisMessage['id_message'];
                        $userName = $thisMessage['username'];
                        $message = $thisMessage['content'];
                        $messageDate = ($thisMessage['date_format'] === date("d/m/Y")) ? "Aujourd'hui" : $thisMessage['date_format'];
                        $messageDate = $messageDate. " à ". $thisMessage['time'];
                        // Boucle qui va déterminer quel type de message à envoyer
                        // Si c'est le premier message ou bien que l'auteur est différent de l'ancien message
                        if ($i==0 || $userName !== $convs[$i-1]['username']) {
                            if ($userName !== $_SESSION['user']) {
                                echo "<div draggable='false' id='message-$id_message' class='message-box'>
                                <img draggable='false' class='message-box-pfp' src='$userPfp' alt='photo de profil'>
                                <div draggable='false' class='message-box-text'>
                                    <div class='message-author-date'>
                                        <h4 class='message-author'>$userName</h4>
                                        <h5 class='message-date'>$messageDate</h5>
                                    </div>";
                                    if (intval($thisMessage['type']) === 1) {
                                        echo "<p draggable='false' class='message-content'>$message</p>";
                                    } else if (intval($thisMessage['type']) === 2) {
                                        echo "<img draggable='false' src='https://cdn.larche.ovh/webchat_images/$message' class='message-image'>";
                                    }
                                echo "</div>";
                            } else {
                                echo "<div draggable=false id='message-$id_message' class='message-box-author'>
                                <div draggable=false class='message-box-text'>
                                    <div class='message-author-date'>
                                        <h4 draggable=false class='message-author'>$userName</h4>
                                        <h5 class='message-date'>$messageDate</h5>
                                    </div>";
                                    if (intval($thisMessage['type']) === 1) {
                                        echo "<p class='message-content'>$message</p>";
                                    } else if (intval($thisMessage['type']) === 2) {
                                        echo "<img draggable=false src='https://cdn.larche.ovh/webchat_images/$message' class='message-image'>";
                                    }
                                echo "</div>
                                <img draggable=false class='message-box-pfp' src='$userPfp' alt='photo de profil'>";
                            }
                        // Sinon si l'auteur est le même que le précédent message
                        } else {
                            if ($userName === $_SESSION['user']) {
                                echo "<div draggable='false' id='message-$id_message' class='message-box-author'>
                                <div draggable=false class='message-box-author-text-multiple'>";
                                if (intval($thisMessage['type']) === 1) {
                                    echo "<p draggable=false class='message-content'>$message</p>";
                                } else if (intval($thisMessage['type']) === 2) {
                                    echo "<img draggable=false src='https://cdn.larche.ovh/webchat_images/$message' class='message-image message-multiple'>";
                                }
                                echo "</div>";
                            } else {
                                echo "<div draggable=false id='message-$id_message' class='message-box'>
                                <div draggable=false class='message-box-text-multiple'>";
                                if (intval($thisMessage['type']) === 1) {
                                    echo "<p draggable=false class='message-content message-multiple'>$message</p>";
                                } else if (intval($thisMessage['type']) === 2) {
                                    echo "<img draggable=false src='https://cdn.larche.ovh/webchat_images/$message' 
                                    class='message-image message-multiple'>";
                                }
                                echo "</div>";
                            }
                        }
                        echo "</div>";
                    }
                } else {
                    echo "Pas de messages";
                }
            } else {
                echo "Chat non trouvé";
            }
            break;
        }
        case "getDm": {
            $userConv = htmlspecialchars($_GET['userConv'], ENT_QUOTES);

            $sqlGetConv = $bdd->prepare("SELECT user_id,username,pfp FROM user WHERE username = ?");
            $sqlGetConv->execute([$userConv]);
            $userDm = $sqlGetConv->fetch(PDO::FETCH_ASSOC);

            $sqlGetConv = $bdd->prepare("SELECT username AS envoyeur,
            (SELECT username FROM user WHERE user_id=dm.user_id_receiver) AS destinataire,content,type,date_time,
            DATE_FORMAT(date_time, '%d/%m/%Y') AS date_format, DATE_FORMAT(date_time, '%H:%i') AS time FROM user
            INNER JOIN dm ON user.user_id = dm.user_id_sender AND dm.user_id_receiver=?
            WHERE user_id = ? UNION SELECT username AS envoyeur,
            (SELECT username FROM user WHERE user_id=dm.user_id_receiver) AS destinataire,content,type,date_time,
            DATE_FORMAT(date_time, '%d/%m/%Y') AS date_format, DATE_FORMAT(date_time, '%H:%i') AS time FROM user 
            INNER JOIN dm ON user.user_id = dm.user_id_sender AND dm.user_id_receiver=?
            WHERE user_id = ? ORDER BY date_time ASC");
            $sqlGetConv->execute([$userDm['user_id'], $_SESSION['user_id'], $_SESSION['user_id'], $userDm['user_id']]);
            $dmConv = $sqlGetConv->fetchAll(PDO::FETCH_ASSOC);
    
            if ($dmConv !== false) {
                if (!empty($dmConv)) {
                    for ($i = 0; $i < count($dmConv); $i++) {
                        $userPfp = ($dmConv[$i]['envoyeur'] === $_SESSION['user']) ? $_SESSION['user_pfp'] : $userDm['pfp'];
                        $userName = $dmConv[$i]['envoyeur'];
                        $message = $dmConv[$i]['content'];
                        $messageDate = ($dmConv[$i]['date_format'] === date("d/m/Y")) ? "Aujourd'hui" : $dmConv[$i]['date_format'];
                        $messageDate = $messageDate. " à ". $dmConv[$i]['time'];
                        if ($i==0 || $userName !== $dmConv[$i-1]['envoyeur']) {
                            if ($userName !== $_SESSION['user']) {
                                echo "<div class='message-box'>
                                <img draggable=false class='message-box-pfp' src='$userPfp' alt='photo de profil'>
                                <div class='message-box-text'>
                                    <div class='message-author-date'>
                                        <h4 class='message-author'>$userName</h4>
                                        <h5 class='message-date'>$messageDate</h5>
                                    </div>";
                                if (intval($dmConv[$i]['type']) === 1) {
                                    echo "<p class='message-content'>$message</p>";
                                } else if (intval($dmConv[$i]['type']) === 2) {
                                    echo "<img draggable='false' src='https://cdn.larche.ovh/webchat_images/$message' class='message-image'>";
                                }
                                echo "</div>";
                            } else {
                                echo "<div class='message-box-author'>
                                <div class='message-box-text'>
                                    <div class='message-author-date'>
                                        <h4 class='message-author'>$userName</h4>
                                        <h5 class='message-date'>$messageDate</h5>
                                    </div>";
                                    if (intval($dmConv[$i]['type']) === 1) {
                                       echo "<p class='message-content'>$message</p>";
                                    } else if (intval($dmConv[$i]['type']) === 2) {
                                        echo "<img draggable='false' src='https://cdn.larche.ovh/webchat_images/$message' class='message-image'>";
                                    }
                                echo "</div>
                                <img draggable=false class='' src='$userPfp' alt='photo de profil'>";
                            }
                        } else {
                            if ($userName !== $_SESSION['user']) {
                                echo "<div class='message-box'>
                                <div class='message-box-text-multiple'>";
                                if (intval($dmConv[$i]['type']) === 1) {
                                    echo "<p class='message-content message-multiple'>$message</p></div>";
                                } else if (intval($dmConv[$i]['type']) === 2) {
                                    echo "<img draggable='false' src='https://cdn.larche.ovh/webchat_images/$message' class='message-image message-multiple'>";
                                }
                            } else {
                                echo "<div class='message-box-author'>
                                <div class='message-box-author-text-multiple'>";
                                if (intval($dmConv[$i]['type']) === 1) {
                                    echo "<p class='message-content'>$message</p></div>";
                                } else if (intval($dmConv[$i]['type']) === 2) {
                                    echo "<img draggable='false' src='https://cdn.larche.ovh/webchat_images/$message' class='message-image message-multiple'>";
                                }
                                echo "</div>";
                            }
                        }
                        echo "</div>";
                    }
                } else {
                    echo "Pas de messages";
                }
            } else {
                echo "Pas de conversation entre vous deux";
            }
            break;
        }
        case "sendMessage": {
            if (!empty($_POST)) {
                $authorId = $_SESSION['user_id'];
                $chatName = htmlspecialchars($_POST['chatName'], ENT_QUOTES);
                $message = strip_tags($_POST['message']);
                $message = htmlspecialchars($_POST['message'], ENT_QUOTES);
                $message = preg_replace('/\b(system|exec|shell_exec|proc_open|pcntl_exec|<script>|&lt;script&gt;)\b/i', '', $message);
                $image = false;

                if (!empty($_FILES)) {
                    $image = $_FILES;
                    $checkSize = getimagesize($image["image"]["tmp_name"]);
                    $dir = "/var/www/cdn/webchat_images/";
                    $extension = pathinfo($_FILES['image']['name']);
                    $imageName = "Image-". time(). "-". $_SESSION['user_id'];
                    $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $dirFile = $dir . $imageName .".".$imageFileType;
                    $uploadOk = ($checkSize !== false) ? 1 : 0;
                
                    if (!in_array($image['image']['type'], $acceptableImages)) {
                        echo "Format d'image interdit";
                        exit;
                    }
                
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                        echo "Format du fichier non accepté (Il faut un PNG,JPG,GIF ou JPEG)";
                        $uploadOk = 0;
                        header("Location:https://staff.larche.ovh/index");
                        exit;
                    }
                
                    if ($_FILES["image"]["size"] > 500000) {
                        $uploadOk = 0;
                        echo "La taille du fichier est trop importante";
                        exit;
                    } else if ($_FILES["image"]["size"] === 0) {
                        $uploadOk = 0;
                        echo "Le fichier est vide";
                        exit;
                    }
                    
                    if ($uploadOk == 0) {
                        echo "Il y a eu un problème et le fichier n'a pas été importé";
                    } else if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $dirFile)) {
                            $imageName = $imageName.'.'.$imageFileType;
                            $sqlInsertImage = $bdd->prepare("INSERT INTO messages (content,idchannel,user_id_author, type) 
                            VALUES (?,(SELECT idchannel FROM webchat WHERE name = ?),?,?)");
                            $sqlInsertImage->execute([$imageName, $chatName, $authorId, 2]);
                            $image = true;
                        } else {
                            echo "Il y a eu une erreur durant l'importation";
                            exit;
                        }
                    }
                }

                if (strlen($message) > 255 || (strlen($message) == 0 && $image === false)) {
                    echo "<alert>Message trop long ou vide (255 caractères max)</alert>";
                } else {
                    if (strlen($message) > 0) {
                        $sqlInsertMessage = $bdd->prepare("INSERT INTO messages (content, idchannel, user_id_author, type)
                        VALUES (?,(SELECT idchannel FROM webchat WHERE name = ?),?,?)");
                        $res = $sqlInsertMessage->execute([$message,$chatName,$authorId,1]);
                    } else if ($image === true && strlen($message) === 0) {
                        $res = true;
                    }
                    
                    echo ($res !== false) ? "ok" : "nop";
                }
            } else {
                echo "Problème";
            }
            break;
        }
        case "sendDm": {
            if (!empty($_POST)) {
                $userConv = htmlspecialchars($_POST['userConv'], ENT_QUOTES);
                $message = strip_tags($_POST['message']);
                $message = htmlspecialchars($_POST['message'], ENT_QUOTES);
                $message = preg_replace('/\b(system|exec|shell_exec|proc_open|pcntl_exec|<script>|&lt;script&gt;)\b/i', '', $message);
                $image = false;
                $authorId = $_SESSION['user_id'];

                if (!empty($_FILES)) {
                    $image = $_FILES;
                    $checkSize = getimagesize($image["image"]["tmp_name"]);
                    $dir = "/var/www/cdn/webchat_images/";
                    $extension = pathinfo($_FILES['image']['name']);
                    $imageName = "Image-". time(). "-". $_SESSION['user_id'];
                    $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $dirFile = $dir . $imageName .".".$imageFileType;
                    $uploadOk = ($checkSize !== false) ? 1 : 0;
                
                    if (!in_array($image['image']['type'], $acceptableImages)) {
                        echo "Format d'image interdit";
                        exit;
                    }
                
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                        echo "Format du fichier non accepté (Il faut un PNG,JPG,GIF ou JPEG)";
                        $uploadOk = 0;
                        header("Location:https://staff.larche.ovh/index");
                        exit;
                    }
                
                    if ($_FILES["image"]["size"] > 500000) {
                        $uploadOk = 0;
                        echo "La taille du fichier est trop importante";
                        exit;
                    } else if ($_FILES["image"]["size"] === 0) {
                        $uploadOk = 0;
                        echo "Le fichier est vide";
                        exit;
                    }
                    
                    if ($uploadOk == 0) {
                        echo "Il y a eu un problème et le fichier n'a pas été importé";
                    } else if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $dirFile)) {
                            $imageName = $imageName.'.'.$imageFileType;
                            $sqlInsertImage = $bdd->prepare("INSERT INTO dm (user_id_sender,user_id_receiver,content,type) 
                            VALUES (?,(SELECT user_id FROM user WHERE username= ?),?,?)");
                            $sqlInsertImage->execute([$authorId, $userConv, $imageName, 2]);
                            $image = true;
                        } else {
                            echo "Il y a eu une erreur durant l'importation";
                            exit;
                        }
                    }
                }
                
                if (strlen($message) > 255 || (strlen($message) == 0 && $image === false)) {
                    echo "<alert>Message trop long (255 caractères) ou vide</alert>";
                } else {    
                    $authorName = $_SESSION['user'];
                    if (strlen($message) > 0) {
                        $sqlInsertMessage = $bdd->prepare("INSERT INTO dm (user_id_sender, user_id_receiver, content,type)
                        VALUES (?, (SELECT user_id FROM user WHERE username= ?), ?,?)");
                        $res = $sqlInsertMessage->execute([$authorId,$userConv,$message,1]);
                    } else if ($image === true && strlen($message) === 0) {
                        $res = true;
                    }
                    
                    if ($res) echo "ok";
                }
            } else {
                echo "Problème";
            }
            break;
        }
        case "deleteMsg": {
            if (!empty($_POST)) {
                $messageId = htmlspecialchars($_POST['messageId'], ENT_QUOTES);
                $authorMessage = $bdd->prepare("SELECT user_id_author AS user_id FROM messages WHERE id_message = ?");
                $authorMessage->execute([$messageId]);
                $authorMessage = $authorMessage->fetch(PDO::FETCH_ASSOC);
                $authorMessage = $authorMessage['user_id'];
                
                if ($_SESSION['user_id'] == $authorMessage) {
                    $sqlDeleteMessage = $bdd->prepare("DELETE FROM messages WHERE id_message = ? AND user_id_author = ?");
                    $res = $sqlDeleteMessage->execute([$messageId,$_SESSION['user_id']]);
                    
                    echo ($res === true) ? "ok" : "nop";
                } else {
                    echo "nop";
                    break;
                }
            } else {
                echo "Problème";
            }
            break;
        }
        case "createDm": {
            if (!empty($_POST)) {
                if (isset($_POST['userName'])) $user = htmlspecialchars($_POST['userName'], ENT_QUOTES);
                if (strlen($user) > 32) {
                    echo "too_long";
                    exit;
                }

                $sqlGetUser = $bdd->prepare("SELECT user_id,username FROM user WHERE username = ?");
                $sqlGetUser->execute([$user]);
                $sqlUser = $sqlGetUser->fetch(PDO::FETCH_ASSOC);

                if ($sqlUser !== false && isset($sqlUser['user_id'])) {
                    $sqlCheckConvs = $bdd->prepare("SELECT content,user_id_sender, user_id_receiver FROM dm 
                    WHERE (user_id_sender = ? AND user_id_receiver = ?) OR (user_id_receiver = ? AND user_id_sender = ?);");
                    $sqlCheckConvs->execute([$_SESSION['user_id'], $sqlUser['user_id'], $_SESSION['user_id'], $sqlUser['user_id']]);
                    $sqlCheckConvs = $sqlCheckConvs->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    echo "Utilisateur introuvable";
                    exit;
                }

                if ($sqlUser !== false && !empty($sqlUser)) {
                    if ($sqlUser['user_id'] == $_SESSION['user_id']) {
                        echo "same";
                        exit;
                    } else if (empty($sqlCheckConvs)) {
                        /*$sqlCreateConv = $bdd->prepare("INSERT INTO dm (user_id_sender, user_id_receiver, content, date_time,type) 
                        VALUES (?,?,?,?,?)");
                        $res = $sqlCreateConv->execute([$_SESSION['user_id'],$sqlUser['user_id'],"Bonjour ".$sqlUser['username'], date("Y-m-d H:i:s"),1]);

                        if ($res) {
                            echo $sqlUser['username'];
                            exit;
                        }*/
                        echo $sqlUser['username'];
                        exit;
                    } else {
                        echo "exist";
                        exit;
                    }
                }
            }
            break;
        }
        case "searchUserToDm": {
            if (!empty($_GET)) {
                if (isset($_GET['userName'])) $userName = htmlspecialchars($_GET['userName'], ENT_QUOTES);
                if (strlen($userName) > 32) {
                    echo "too_long";
                    exit;
                }

                $sqlGetUser = $bdd->prepare("SELECT username FROM user WHERE username LIKE ?");
                $sqlGetUser->execute([$userName.'%']);
                $sqlUsers = $sqlGetUser->fetchAll(PDO::FETCH_ASSOC);

                if ($sqlUsers !== false) {
                    foreach($sqlUsers as $sqlUser => $value)
                    if($sqlUsers[$sqlUser]['username'] !== $_SESSION['user']) echo $sqlUsers[$sqlUser]['username'];
                    exit;
                }
            }
            break;
        }
    }
}

?>