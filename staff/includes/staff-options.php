<?php
// Récupération des données de la requête POST
// On récupère les données json dans le cas ou la requête en contient
if (isset($_POST) && count($_POST) === 1 && !isset($_POST['requestType'])) {
    $json = file_get_contents('php://input');
    $data = json_decode($json); // on décode les données
    $jsonPostData = [];
}
if (!empty($json)) foreach ($data as $key => $value) $jsonPostData["$key"] = $value; // on assigne ces données à un tableau

if (isset($_POST["requestType"])) { // Si requestType est trouvée directement on assigne la valeur
    $requestType = $_POST["requestType"]; 
} else if (!empty($jsonPostData)) { // Sinon on regarde dans les données JSON
    $requestType = $jsonPostData["requestType"];
}

include("./db-connect.php");
include("./trads.php"); // Tableaux de traduction de certains éléments en DB

$roles = [
    "admin" => 4,
    "mod" => 3,
    "contributor" => 2,
    "member" => 1,
    "banned" => 0
];

session_start();

if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location:../login");
    exit;
}

// Fonctionnalités

if (isset($requestType)) {
    switch($requestType) {
        case "change-staff": {
            // Récupération des variables du POST et sécurisation des données
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
            if (strlen($username) > 32 || strlen($username) === 0) {
                $_SESSION['returnMessage'] = "Valeur entrée incorrecte";
                header("Location:../module/gestion-staff");
                exit;
            }
            $role = htmlspecialchars($_POST['roleToAdd'], ENT_QUOTES);
            if (in_array($role, array("admin", "mod", "contributor", "member", "banned"))) $lvl = $roles[$role];

            $sqlStaffAuthor = $bdd->prepare("SELECT status FROM user WHERE user_id = ?");
            $sqlStaffAuthor->execute([$_SESSION['user_id']]);
            $sqlStaffAuthor = $sqlStaffAuthor->fetch(PDO::FETCH_ASSOC);

            $checkUsername = $bdd->prepare("SELECT user_id,status FROM user WHERE username = ?");
            $checkUsername -> execute([$username]);
            $checkUsername = $checkUsername->fetch(PDO::FETCH_ASSOC);
            
            if (intval($sqlStaffAuthor['status']) > 3 && $checkUsername !== false && isset($checkUsername['user_id'])) {
                // Mise à jour du user dans la base de données
                $sqlAddStaff = $bdd->prepare("UPDATE user SET status = ? WHERE username = ?");
                $sqlAddStaff->execute([$lvl,$username]);
                // Ajout de la log staff
                $sqlBanMember = $bdd->prepare("INSERT INTO staff_actions (type, content, user_id, time) VALUES (?,?,?,?)");
                $sqlBanMember->execute(["perms_modif", "Changement perms : $username", $_SESSION['user_id'], date("H:i:s")]);
            } else {
                $_SESSION['returnMessage'] = "Permission manquante ou utilisateur introuvable";
                header("Location:../module/gestion-staff");
                exit;
            }
            header("Location:../module/gestion-staff");
            exit;
        }
        case "searchUserForStaffOption": {
            if (isset($_POST['usernameToSearch'])) {
                $searchUser = htmlspecialchars($_POST['usernameToSearch'], ENT_QUOTES);
                $searchUser = '%'.$searchUser.'%';
            }
            $sqlSearchUser = $bdd->prepare("SELECT username FROM user WHERE username LIKE ?");
            $sqlSearchUser->execute(array($searchUser)); // on l'exec avec comme paramètre le pseudo à rechercher
            $users = $sqlSearchUser->fetchAll(PDO::FETCH_ASSOC); // On récupère les données et on les associes à array
            
            header("Content-type: application/json");
            foreach ($users as $user => $value) {
                echo $users[$user]['username'].',';
            }
            break;
        }
        case "logSearch": {
            $searchLog = htmlspecialchars($_POST["logToSearch"], ENT_QUOTES); 
            $whatToSearch = htmlspecialchars($_POST["typeToSearch"], ENT_QUOTES); 
            // En fonction de ce que l'on cherche, la requête Sql n'est pas la même, soit on cherche avec username, soit avec id_log
            if ($whatToSearch === "username") {
                $sqlFindLog = $bdd->prepare("SELECT id_log,username,logs.email,logs.ip,DATE_FORMAT(date, '%d/%m/%Y') AS date,time,
                interface,(CASE success WHEN '1' THEN 'Réussie' WHEN '0' THEN 'Echouée' END) AS success 
                FROM logs,user WHERE (username = ?) AND logs.user_id = user.user_id ORDER BY id_log DESC");
            } else {
                $sqlFindLog = $bdd->prepare("SELECT id_log,username,logs.email,logs.ip,DATE_FORMAT(date, '%d/%m/%Y') AS date,time,
                interface, (CASE success WHEN '1' THEN 'Réussie' WHEN '0' THEN 'Echouée' END) AS success 
                FROM logs,user WHERE ($whatToSearch = ?) AND logs.user_id = user.user_id");
            }
            $sqlFindLog->execute([$searchLog]); // On exec
            $array = $sqlFindLog->fetch(PDO::FETCH_ASSOC); // On récupère en associant les valeurs
        
            if (isset($array['username'])) {
                echo "<div class='list-group rounded-2'>";
                foreach ($array as $key => $value) {
                    if ($key !== "id_log") echo "<li class='p-1 list-group-item'>$logsTrads[$key] : <span class='item-$key'>$value</span></li>";
                }
                echo "<form></form><form action='../includes/delete-row' method='post'>
                <input type='text' name='requestType' value='deleteLog' hidden>
                <input type='text' name='logId' value='".$array['id_log']."' hidden>
                <input class='mt-1 p-1 btn btn-danger' type='submit' value='Supprimer'>
                </form>
                </div>";
            }
            break;
        }
        case "staffLogSearch": {
            $searchStaffLog = htmlspecialchars($_POST["logToSearch"], ENT_QUOTES); 
            $whatToSearch = htmlspecialchars($_POST["typeToSearch"], ENT_QUOTES); 
            if ($whatToSearch === "username") {
                $sqlFindStaffLog = $bdd->prepare("SELECT username,type,DATE_FORMAT(date, '%d/%m/%Y') AS date,time,content 
                FROM user,staff_actions WHERE user.username = ? AND user.user_id = staff_actions.user_id ORDER BY id_staff_action DESC");
            } else {
                $sqlFindStaffLog = $bdd->prepare("SELECT username,type,DATE_FORMAT(date, '%d/%m/%Y') AS date,time,content 
                FROM user,staff_actions WHERE id_staff_action = ?");
            }
            $sqlFindStaffLog->execute([$searchStaffLog]);
            $staffLogArray = $sqlFindStaffLog->fetch(PDO::FETCH_ASSOC);
        
            if (isset($staffLogArray) && !empty($staffLogArray)) {
                $keys = [
                    "username" => "Pseudo", 
                    "type" => "Type",
                    "date" => "Date",
                    "time" => "Heure/Min",
                    "content" => "Détails"
                ];
                echo "<div class='list-group rounded-2'>";
                foreach ($staffLogArray as $key => $value) {
                    if ($key === "type") {
                        echo "<li class='list-group-item'>$keys[$key] : <span class='item-$key'>$staffLogsTradValue[$value]</span></li>";
                    } else {
                        echo "<li class='list-group-item'>$keys[$key] : <span class='item-$key'>$value</span></li>";
                    }
                }
                echo "</div>";
            } else {
                echo "<p class='text-center'>Log non trouvée</p>";
                exit;
            }
            break;
        }
        case "getGraphLogs": {
            // On récupère la date actuelle et une intervalle d'il y a 6 jours afin de récupérer la date d'il y a une semaine
            $date = new DateTime();
            $date->sub(new DateInterval('P6D'));
            $dateToSearch = $date->format('Y-m-d');
            
            // En fonction de ce que l'on cherche, la requête Sql n'est pas la même, soit on cherche avec username, soit avec id_log
            $sqlGetLogs = $bdd->prepare("SELECT COUNT(*) as amount,DATE_FORMAT(date, '%d/%m') AS date FROM logs WHERE date >= date(?) GROUP BY date DESC");
            $sqlGetLogs->execute([$dateToSearch]); // On exécute la requête SQL
            $logs = $sqlGetLogs->fetchAll(PDO::FETCH_ASSOC); // On récupère en associant les valeurs
            $jsonLogs = json_encode($logs); // On encode les données en JSON
    
            header('Content-Type: application/json'); // En-tête de réponse pour désigner qu'on envoit du JSON
            echo $jsonLogs; // On affiche le JSON
            break;
        }
        case "changeCaptcha": {
            if (isset($_FILES) && !empty($_FILES['newCaptchaImage'])) {
                $sqlGetNumImage = $bdd->query("SELECT num FROM captcha_images ORDER BY num DESC LIMIT 1");
                $numImage = $sqlGetNumImage->fetch(PDO::FETCH_ASSOC);
                $numImage = intval($numImage['num']) + 1;
                $check = getimagesize($_FILES["newCaptchaImage"]["tmp_name"]);
                $newCaptchaImage = $_FILES['newCaptchaImage'];
                $filesize = $newCaptchaImage['size'];
                $dir = "/var/www/cdn/captcha/";
                $extension = pathinfo($_FILES['newCaptchaImage']['name']);
                $imageName = "Image-Captcha-".$numImage;
                $imageFileType = strtolower(pathinfo($_FILES['newCaptchaImage']['name'], PATHINFO_EXTENSION));
                $file = $dir . "Image-Captcha-". $numImage .".".$imageFileType;
                $sqlLink = "https://cdn.larche.ovh/captcha/"."Image-Captcha-".$numImage.".".$imageFileType;
                $uploadOk = ($check !== false) ? 1 : 0;
        
                if (($_FILES['newCaptchaImage']['type'] !== "image/png")) {
                    $_SESSION['returnMessage'] = "Type du fichier inccorect";
                    header("Location: https://staff.larche.ovh/index");
                    exit;
                }
        
                if($imageFileType != "png") {
                    echo "Format du fichier non accepté (Il faut un PNG)";
                    $uploadOk = 0;
                    header("Location:https://staff.larche.ovh/index");
                    exit;
                }
        
                if ($_FILES["newCaptchaImage"]["size"] > 5000000) {
                    $_SESSION['returnMessage'] = "La taille du fichier est trop importante (5 mo max)";
                    $uploadOk = 0;
                    header("Location: ../index");
                    exit;
                } else if ($_FILES["newCaptchaImage"]["size"] === 0) {
                    $uploadOk = 0;
                }
            
                if ($uploadOk == 0) {
                    echo "Il y a eu un problème et le fichier n'a pas été importé";
                } else {
                    if (move_uploaded_file($_FILES["newCaptchaImage"]["tmp_name"], $file)) {
                        $sqlInsertImage = $bdd->prepare("INSERT INTO captcha_images (name,link,num) VALUES (?,?,?)");
                        $sqlInsertImage->execute([$imageName, $sqlLink, $numImage]);
                        header("Location: ../index");
                        exit;
                    } else {
                      echo "Il y a eu une erreur durant l'importation<br>";
                    }
                }
            } else {
                $_SESSION['returnMessage'] = "Image vide";
                header("Location:../index");
                break;
            }
            break;
        }
        case "createChat": {
            $chatName = htmlspecialchars($_POST['chatName'], ENT_QUOTES);
            $chatCategory = htmlspecialchars($_POST['chatCategory'], ENT_QUOTES);

            $categorys = array("animaux", "jardinerie", "autre");
            if (strlen($chatName) > 25 || !in_array($chatCategory, $categorys)) {
                $_SESSION['returnMessage'] = "Nom trop long ou catégorie incorrecte";
                header("Location:../module/gestion-chat");
                exit;
            } else {
                $sqlCheckChat = $bdd->prepare("SELECT idchannel FROM webchat WHERE name = ?");
                $sqlCheckChat->execute([$chatName]);
                $sqlCheckChat = $sqlCheckChat->fetch(PDO::FETCH_ASSOC);
                if ($sqlCheckChat === false) {
                    $sqlCreateChat = $bdd->prepare("INSERT INTO webchat (category, name) VALUES (?,?)");
                    $sqlCreateChat->execute([$chatCategory, $chatName]);
                }

                $_SESSION['returnMessage'] = ($sqlCheckChat !== false) ? "Chat déjà existant" : "Le chat a bien été créée";
                header("Location:../module/gestion-chat");
                exit;
            }
        }
        case "configAvatar": {
            $avatarTypes = array("avatarHead", "avatarMouth", "avatarEyes", "avatarNoise", "avatarEars");
            if (isset($_POST['avatarType']) && in_array($_POST['avatarType'], $avatarTypes)) {
                $avatarType = htmlspecialchars($_POST['avatarType']);
            } else {
                $msg = "Problème dans le nom de l'option sélectionnée";
                $check = 0;
            }
            if (!empty($_FILES)) {
                $avatarTrad = [
                    "avatarHead" => "tete", 
                    "avatarMouth" => "bouche", 
                    "avatarEyes" => "yeux", 
                    "avatarNoise" => "nez",
                    "avatarEars" => "oreilles"
                ];
                $avatarName = htmlspecialchars($_POST['avatarName'], ENT_QUOTES);
                $avatarName = $avatarName."-".$avatarTrad[$avatarType];
                $check = getimagesize($_FILES["newAvatarImage"]["tmp_name"]);
                $newCaptchaImage = $_FILES['newAvatarImage'];
                $filesize = $newCaptchaImage['size'];
                $dir = "/var/www/html/assets/avatar/";
                $sqlLink = "/assets/avatar/".$avatarName.".png";
                $extension = pathinfo($_FILES['newAvatarImage']['name']);
                $imageFileType = strtolower(pathinfo($_FILES['newAvatarImage']['name'], PATHINFO_EXTENSION));
                $file = $dir .$avatarName .".".$imageFileType;
                $uploadOk = ($check !== false) ? 1 : 0;

                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                    echo "Format du fichier non accepté (Il faut un PNG,JPG,GIF ou JPEG)";
                    $uploadOk = 0;
                    header("Location:https://staff.larche.ovh/index");
                    exit;
                }
        
                if ($_FILES["newAvatarImage"]["size"] > 5000000) {
                    $_SESSION['returnMessage'] = "La taille du fichier est trop importante (5mo max)";
                    $uploadOk = 0;
                    header("Location: ../index");
                    exit;
                } else if ($_FILES["newAvatarImage"]["size"] === 0) {
                    $uploadOk = 0;
                }
            
                if ($uploadOk == 0) {
                    echo "Il y a eu un problème et le fichier n'a pas été importé";
                } else {
                    if (move_uploaded_file($_FILES["newAvatarImage"]["tmp_name"], $file)) {
                        $sqlInsertImage = $bdd->prepare("INSERT INTO avatar (name,link,type) VALUES (?,?,?)");
                        $sqlInsertImage->execute([$avatarName, $sqlLink, $avatarType]);
                        header("Location: ../index");
                        exit;
                    } else {
                      echo "Il y a eu une erreur durant l'importation<br>";
                    }
                }
            } else {
                $_SESSION['returnMessage'] = "Image vide";
                header("Location:../index");
                break;
            }
            break;
        }
        case "editArticle": {
            $req = "UPDATE articles ";
            $exec = array();
            if (isset($_POST['newTitle'])) {
                $newTitle = htmlspecialchars($_POST['newTitle'], ENT_QUOTES);
                if (strlen($newTitle) > 50) {
                    echo "Titre trop long (50 caractères max";
                    exit;
                } else if (strlen($newTitle) > 0) {
                    $req = $req."SET title = ? ";
                    array_push($exec, $newTitle);
                }
            }

            if (isset($_POST['newTeaser'])) {
                $newTeaser = htmlspecialchars($_POST['newTeaser'], ENT_QUOTES);
                if (strlen($newTeaser) > 100) {
                    echo "Teaser trop long (100 caractères max";
                    exit;
                } else if (strlen($newTeaser) > 0) {    
                    $req = $req. "SET teaser = ? ";
                    array_push($exec, $newTeaser);
                }
            }
            if (isset($_POST['newContent'])) {
                $newContent = htmlspecialchars($_POST['newContent'], ENT_QUOTES);
                if (strlen($newContent) > 65535) {
                    echo "Contenu trop long (65535 caractères max";
                    exit;
                } else if (strlen($newContent) > 0) {
                    $req = $req. "SET content = ? ";
                    array_push($exec, $newContent);
                }
            }
            $articleId = htmlspecialchars($_POST['articleId'], ENT_QUOTES);
            array_push($exec, $articleId);
            $req = $req. " WHERE id_article = ?";
            $sqlUpdateArticle = $bdd->prepare($req);
            $res = $sqlUpdateArticle->execute($exec);

            if ($res !== false) echo "ok";
            break;
        }
    }

    if ($requestType === "create-account") {
        if (!empty($_POST)) {
            if (isset($_POST['email']) && !empty($_POST['email'])) {
                $email = $_POST['email'];
                if (strlen($email) <= 70) {
                    $email = strip_tags($email);
                    $email = htmlspecialchars($email, ENT_QUOTES);
                } else {
                    $_SESSION['resRequest'] = "email_problem";
                    header("Location: ../index");
                    exit;
                }
            }

            if (isset($_POST['password']) && !empty($_POST['password'])) {
                $password = $_POST['password'];
                if (strlen($password) <= 30) {
                    $password = strip_tags($password);
                    $password = htmlspecialchars($password, ENT_QUOTES);
                    $pass = "x!" . $password . ";A4!";
                    $hash = hash("sha512", $pass); // On hash le mot de passe avec le salt
                } else {
                    $_SESSION['resRequest'] = "pwd_problem";
                    header("Location: ../index");
                    exit;
                }
            }

            if (isset($_POST['username']))
                $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
            if (isset($_POST['lvlperms']))
                $lvl_perms = htmlspecialchars($_POST['lvlperms'], ENT_QUOTES);
        }

        $date = date("Y-m-d");
        $sqlCheck = $bdd->prepare("SELECT username,email FROM user WHERE email=?");
        $sqlCheck->execute([$email]);
        $check = $sqlCheck->fetch(PDO::FETCH_ASSOC);
        // Cette requête permet de vérifier si l'utilisateur existe déjà ou non

        // S'il n'existe pas, on crée la requête pour insérer les données dans la DB
        if ($check === false) {
            $sqlCreateAccount = $bdd->prepare("INSERT INTO user (username, email, password, status, date_created) VALUES (?,?,?,?,?)");
            $sqlCreateAccount->execute([$username, $email, $hash, $lvl_perms, $date]);
            $_SESSION['resRequest'] = "user_created";
            header("Location: ../../index");
            exit;
        } else {
            // Sinon on redirect avec un message d'erreur
            $_SESSION['resRequest'] = "already_exist";
            header("Location: ../index");
            exit;
        }
    // Si la requête est de type 'viewt-staff'
    } else if ($requestType === "view-staff") {
        $searchUsername = htmlspecialchars($_POST['staff-search-username'], ENT_QUOTES);
        $sqlFindStaff = $bdd->prepare("SELECT username,email,status,DATE_FORMAT(date_created, '%d/%m/%Y') as DATE FROM user WHERE (username = ?)");
        $sqlFindStaff->execute([$searchUsername]);
        $check = $sqlFindStaff->fetch(PDO::FETCH_ASSOC);

        foreach ($check as $key => $value) echo "<li>$key : $value</li>";
    }
}

// Si la requête est une requête GET
if (isset($_GET) && !empty($_GET)) {
    $requestType = $_GET['requestType'];
    switch ($requestType) {
        case "searchEvent": {
            $eventId = htmlspecialchars($_GET['eventId'], ENT_QUOTES);
            if (is_numeric($eventId) !== true) {
                echo "La valeur cherchée est incorrecte";
                exit;
            } else if (is_numeric($eventId) === true) {
                $sqlGetEvent = $bdd->prepare("SELECT id_event AS 'Id',title as 'Titre',
                DATE_FORMAT(date_creation, '%d/%m/%Y') AS 'Date Création',DATE_FORMAT(date_event, '%d/%m/%Y') AS 'Date',place AS 'Lieu',
                id_author,username_author AS 'Auteur',DATE_FORMAT(time, '%Hh%i') AS 'Heure',status AS 'Statut'
                FROM events WHERE id_event = ?");
                $sqlGetEvent->execute([$eventId]);
                $event = $sqlGetEvent->fetch(PDO::FETCH_ASSOC);

                if ($event !== false && isset($event['Titre'])) {
                    echo "<div class='list-group rounded-2'>";
                    foreach ($event as $key => $value) {
                        if ($key === "Auteur") {
                            echo "<li class='p-1 list-group-item'>$key : 
                            <a target='_blank' href='https://larche.ovh/user?id=".$event['id_author']."'>$value</a></li>";
                        } else if ($key !== "id_author") {
                            echo "<li class='p-1 list-group-item'>$key : <span class='item-$key'>$value</span></li>";
                        }
                    }
                    echo "<a class='mt-1 btn btn-secondary' target='_blank' href='https://larche.ovh/event?id=".$event['Id']."'>Voir l'event</a>
                    <form>
                        <input type='text' name='requestType' value='deleteEvent' hidden>
                        <input type='text' name='eventId' value='".$event['Id']."' hidden>
                        <input class='btn btn-danger' onclick='deleteEvent(".$event['Id'].")' value='Supprimer' type='button'>
                    </form>
                    </div>";
                } else {
                    echo "<p class='text-center mb-1'>Event non trouvé</p>";
                    exit;
                }
            }
            break;
        }
        case "searchArticle" : {
            $type = htmlspecialchars($_GET['type'], ENT_QUOTES);
            $search = htmlspecialchars($_GET['search'], ENT_QUOTES);
        
            if (in_array($type, array("username_author", "id_article"))) {
                if ($type === "id_article") $type = "articles.id_article";
                $req = "SELECT user_id_author AS id_author,articles.id_article AS 'Id', title AS 'Titre',username_author AS 'Auteur',
                DATE_FORMAT(articles.date, '%d/%m/%Y') AS 'Date',COUNT(articles_likes.id_article) AS 'Nombre de likes'
                FROM articles LEFT JOIN articles_likes on articles.id_article = articles_likes.id_article WHERE ".$type." = ?
                GROUP BY articles.id_article ORDER BY articles.id_article DESC LIMIT 1;";
                $sqlGetArticle = $bdd->prepare($req);
                $sqlGetArticle->execute([$search]);
                $array = $sqlGetArticle->fetch(PDO::FETCH_ASSOC); 
            }
    
            if (isset($array['Auteur'])) {
                echo "<div class='list-group rounded-2'>";
                foreach ($array as $key => $value) {
                    if ($key === "Auteur") {
                        echo "<li class='p-1 list-group-item'>$key : 
                        <a target='_blank' href='https://larche.ovh/user?id=".$array['id_author']."'>$value</a></li>";
                    } else if ($key !== "id_author") {
                        echo "<li class='p-1 list-group-item'>$key : <span class='item-$key'>$value</span></li>";
                    }
                }
                echo "<a class='mt-1 btn btn-secondary' target='_blank' href='https://larche.ovh/article?id=".$array['Id']."'>Voir l'article</a>
                <form></form><form action='../includes/delete-row.php' method='post'>
                    <input type='text' name='requestType' value='deleteObj' hidden>
                    <input type='text' name='type' value='article' hidden>
                    <input type='text' name='id' value='".$array['Id']."' hidden>
                    <input class='btn btn-danger' value='Supprimer' type='submit'>
                </form>
                </div>";
            } else {
                echo "Article non trouvé";
                exit;
            }
            break;
        }
        case "exportPdf": {
            $userId = $_GET['userToExport'];
            $authorRequest = (isset($_SESSION)) ? $_SESSION['user_id'] : "problem";
    
            if ($authorRequest !== "problem") {
                $sqlCheckStaff = $bdd->prepare("SELECT status FROM user WHERE user_id = ?");
                $sqlCheckStaff->execute([$authorRequest]);
                $sqlCheckStaff = $sqlCheckStaff->fetch(PDO::FETCH_ASSOC);
            } else {
                header("Location: ../login");
                exit;
            }
    
            if (intval($sqlCheckStaff['status']) >= 3) {
                $sqlGetUserData = $bdd->prepare("SELECT user.username AS 'pseudo', user.email, user.name AS 'prénom', user.lastname AS 'nom',
                user.status AS 'rang',user.ip,user.city AS 'ville',DATE_FORMAT(user.age, '%d/%m/%Y') AS 'Date de naissance',
                DATE_FORMAT(user.date_created, '%d/%m/%Y') AS 'Inscription',user.description,user.newsletter_pref AS 'préférence newsletter',
                COUNT(DISTINCT logs.id_log) AS 'Nombre de connexions',COUNT(DISTINCT historic.id_historic) AS 'Nombre de pages visitées'
                FROM user 
                INNER JOIN logs ON user.user_id = logs.user_id 
                INNER JOIN historic ON user.user_id = historic.user_id
                WHERE user.user_id = ?");
                        
                $sqlGetUserData->execute([$userId]);
                $userInfos = $sqlGetUserData->fetch(PDO::FETCH_ASSOC);
                $userData = json_encode($userInfos);
    
                header("Content-Type: application/json");
                echo $userData;
                exit;
            } else {
                header("Location: ../login");
                exit;
            }
        }
        case "searchArticleToEdit": {
            $articleId = htmlspecialchars($_GET['articleId'], ENT_QUOTES);
            $array = [];
        
            if (is_numeric($articleId)) {
                $sqlGetArticleEdit = $bdd->prepare("SELECT content,id_article, teaser, title FROM articles WHERE articles.id_article = ?;");
                $sqlGetArticleEdit->execute([$articleId]);
                $array = $sqlGetArticleEdit->fetch(PDO::FETCH_ASSOC); 
            }
            
            if (isset($array['id_article'])) {
                echo "<form></form><form id='articleEditBoxContainer'>";
                echo "<div><label for='newTitleArticleInput' class='form-label'>Titre</label>
                <input type='text' class='form-control' id='newTitleArticleInput' name='newTitleArticle' placeholder='".$array['title']."'>
                </div>";
                echo "<div><label for='newContentInput' class='form-label'>Contenu</label>
                <textarea type='text' class='form-control' id='newContentInput' name='newContentArticle'>".$array['content']."</textarea>
                </div>";
                echo "<div><label for='newTeaserArticleInput' class='form-label'>Teaser</label>
                <input type='text' class='form-control' id='newTeaserArticleInput' name='newTeaserArticle' placeholder='".$array['teaser']."'>
                </div>";
                echo "<div>
                    <button type='button' onclick='saveArticleEdit()'>Enregistrer</button>
                </div>";
                echo "</form>";
            } else {
                echo "Article non trouvé";
                exit;
            }
            break;
        }
    }
}

?>