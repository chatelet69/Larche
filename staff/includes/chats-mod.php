<?php
include("./db-connect.php");
session_start();
if (isset($_POST['requestType']) && !empty($_POST['requestType'])) $requestType = htmlspecialchars($_POST['requestType'], ENT_QUOTES);

if (isset($_POST) && !empty($_POST)) {
    switch ($requestType) {
        case "deleteChat": {
            $chatId = htmlspecialchars($_POST['chatId'], ENT_QUOTES);
            if (is_numeric($chatId)) {
                $staff = $bdd->prepare("SELECT status FROM user WHERE user_id = ?");
                $staff -> execute([$_SESSION['user_id']]);
                $staff = $staff->fetch(PDO::FETCH_ASSOC);

                if (intval($staff['status']) > 3) {
                    $sqlDeleteChat = $bdd->prepare("DELETE FROM webchat WHERE idchannel = ?");
                    $res = $sqlDeleteChat -> execute([$chatId]);
                    if ($res !== false) {
                        echo "ok";
                        break;
                    }
                } else {
                    echo "Vous n'avez pas la permission";
                    exit;
                }
            } else {
                echo "La valeur rentrée n'est pas correcte";
                exit;
            }
            break;
        }
        case "deleteMessageFromChat": {
            $messageId = htmlspecialchars($_POST['messageId'], ENT_QUOTES);
            if (is_numeric($messageId)) {
                $sqlDelMsg = $bdd->prepare("DELETE FROM messages WHERE id_message = ?");
                $res = $sqlDelMsg->execute([$messageId]);

                echo ($res !== false) ? "ok" : "Problème dans la suppresion";
            } else {
                echo "Valeur indiquée incorrecte";
                exit;
            }
            break;
        }
        case "getTicket": {
            $whatToSearch = htmlspecialchars($_POST['whatToSearch'], ENT_QUOTES);
            $search = htmlspecialchars($_POST['search'], ENT_QUOTES);
    
            $sqlGetTicket = $bdd->prepare("SELECT pfp AS user_pfp, id_ticket,date subject, content, user_id_author, username, 
            DATE_FORMAT(date, '%d/%m') AS date,file FROM tickets, user WHERE $whatToSearch = ? AND user_id_author=user.user_id");
            $sqlGetTicket->execute([$search]);
            $ticket = $sqlGetTicket->fetch(PDO::FETCH_ASSOC);
    
            if ($ticket !== false) {
                $sqlGetConv = $bdd->prepare("SELECT pfp,username,user_id_author, content, DATE_FORMAT(answer_timestamp, '%d/%m/%Y %Hh%i') as datetime,file 
                FROM `tickets_convs`, user WHERE ticket_id = ? AND user.user_id = user_id_author");
                $sqlGetConv->execute([$ticket['id_ticket']]);
                $convs = $sqlGetConv->fetchAll(PDO::FETCH_ASSOC);
    
                $ticketId = $ticket['id_ticket'];
                $ticketSubject = $ticket['subject'];
                $ticketDate = $ticket['date'];
                $ticketContent = $ticket['content'];
                $userPfp = $ticket['user_pfp'];
                $ticketAuthor = $ticket['username'];
                echo "<h3 class='text-center fs-5 text-danger'>$ticketSubject</h3><p class='text-center fs-6'>Ouvert le $ticketDate</p>";
                echo "<span style='display:none;' id='ticketId'>$ticketId</span>";
                echo "
                <div class='message-ticket-box mb-2'>
                    <img class='ticket-pfp' style='width:45px;' class='' src='$userPfp' alt='photo de profil'>
                    <div class='message-ticket-text'>
                        <h4 class='ticket-author'>$ticketAuthor</h4>
                        <p class='ticket-content'>$ticketContent</p>";
                        if ($ticket['file'] !== NULL) {
                            echo "<img class='ticket-img' src='".$ticket['file']."' alt='photo ticket'>";
                        }
                echo"</div></div>";
    
                foreach ($convs as $conv => $value) {
                    $userName = $convs[$conv]['username'];
                    $message = $convs[$conv]['content'];
                    $date = $convs[$conv]['datetime'];
                    if ($userName !== $ticketAuthor) {
                        echo "<div class='message-ticket-author-box mb-1'>";
                        echo "<div class='message-ticket-text'>
                        <h4 class='ticket-author'>$userName</h4>
                        <p class='ticket-content'>$message</p>
                        <span class='ticket-date'>$date</span>
                        </div>
                        <img class='ticket-pfp' src='".$convs[$conv]['pfp']."' alt='photo de profil'>";
                    } else {
                        echo "<div class='message-ticket-box mb-1'>";
                        echo "<img class='ticket-pfp' src='$userPfp' alt='photo de profil'>
                        <div class='message-ticket-text'>
                            <p class='ticket-content'>$message</p>
                            <h4 class='ticket-author'>$userName</h4>
                            <span class='ticket-date'>$date</span>
                        </div>";
                    }
                    echo "</div>";
                }
            } else {
                echo "Ticket non trouvé";
            }
            break;
        }
        case "sendAnswerTicket": {
            $staffId = $_SESSION['user_id'];
            $staffUsername = $_SESSION['user'];
            $staffPfp = $_SESSION['user_pfp'];
            $message = strip_tags($_POST['message']);
            $message = htmlspecialchars($message, ENT_QUOTES);
            $ticketId = htmlspecialchars($_POST['ticketId'], ENT_QUOTES);
            $sqlSendMessage = $bdd->prepare("INSERT INTO `tickets_convs` (ticket_id,content,user_id_author) VALUES (?,?,?)");
            $sqlSendMessage->execute([$ticketId,$message,$staffId]);
    
            echo "ok";
            break;
        }
        case "resolveTicket": {
            $ticketId = htmlspecialchars($_POST['ticketId'], ENT_QUOTES);
            $sqlResolveTicket = $bdd->prepare("UPDATE tickets SET status = 1 WHERE id_ticket = ?");
            $sqlResolveTicket->execute([$ticketId]);
            $_SESSION['returnMessage'] = "ticket_resolved";
            break;
        }
        default: {
            echo "Problème";
            exit;
        }
    }
}

if (isset($_GET) && !empty($_GET)) {
    $requestType = htmlspecialchars($_GET['requestType'], ENT_QUOTES);
    switch ($requestType) {
        case "getChat": {
            $chatName = htmlspecialchars($_GET['chatName'], ENT_QUOTES);

            $sqlGetConv = $bdd->prepare("SELECT idchannel,category FROM webchat WHERE name = ?");
            $sqlGetConv->execute([$chatName]);
            $chat = $sqlGetConv->fetch(PDO::FETCH_ASSOC);
    
            if ($chat !== false) {
                $sqlGetConv = $bdd->prepare("SELECT id_message,username,content,type,DATE_FORMAT(date, '%d/%m/%y à %H:%i') 
                FROM messages,user WHERE idchannel = ? AND user.user_id=messages.user_id_author ORDER BY date DESC");
                $sqlGetConv->execute([$chat['idchannel']]);
                $convs = $sqlGetConv->fetchAll(PDO::FETCH_ASSOC);
    
                if (!empty($convs)) {
                    for ($i = 0; $i < count($convs); $i++) {
                        echo "<div id='account-box' class='text-center border border-2 row p-1 m-2 rounded-1'>";
                        foreach($convs[$i] as $key => $value) {
                            if ($key !== "type" && $key !== "content") {
                                echo "<span class='fs-6 m-0 col-sm col-md col-lg col-xl col'>$value</span>";
                            } else {
                                if ($key === "content" && intval($convs[$i]['type']) == 1) {
                                    echo "<span class='fs-6 m-0 col-sm col-md col-lg col-xl col'>$value</span>";
                                } else if ($key === "content" && intval($convs[$i]['type']) == 2) {
                                    echo "<img class='fs-6 m-0 col-sm col-md col-lg col-xl col' src='https://cdn.larche.ovh/webchat_images/$value'>";
                                }
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
        case "searchMessage": {
            if (isset($_GET['type']) && in_array($_GET['type'], array("user", "id"))) {
                $type = strip_tags($_GET['type']);
                $type = htmlspecialchars($type, ENT_QUOTES);
            } else {
                echo "Erreur dans les valeurs envoyées";
                exit;
            }
            if (isset($_GET['search'])) {
                $search = strip_tags($_GET['search']);
                $search = htmlspecialchars($search, ENT_QUOTES);
            }

            $message = array();
            $sqlGetMessage = "";
            if ($type === "id") {
                $sqlGetMessage = $bdd->prepare("SELECT id_message AS 'Id',content AS 'Contenu',
                webchat.name AS 'Canal',user_id_author AS 'Id Auteur',user.username AS 'Auteur',DATE_FORMAT(date, '%d/%m/%Y à %H:%i') AS 'Date',type 
                FROM messages,user,webchat WHERE id_message = ? 
                AND messages.user_id_author = user.user_id AND messages.idchannel = webchat.idchannel");
            } else {
                $sqlGetMessage = $bdd->prepare("SELECT id_message AS 'Id',content AS 'Contenu',
                webchat.name AS 'Canal',user_id_author AS 'Id Auteur',username AS 'Auteur',DATE_FORMAT(date, '%d/%m/%Y à %H:%i') AS 'Date',type
                FROM messages,webchat,user WHERE user_id_author = (SELECT user_id FROM user WHERE username = ?)
                AND messages.user_id_author = user.user_id AND messages.idchannel = webchat.idchannel
                ORDER BY messages.date DESC LIMIT 1;");
            }
            $sqlGetMessage->execute([$search]);
            $message = $sqlGetMessage->fetch(PDO::FETCH_ASSOC);

            if (isset($message['Id'])) {
                echo "<div class='list-group'>";
                foreach ($message as $key => $value) {
                    if (($key !== "Contenu" && $key !== "type") || ($key === "Contenu" && intval($message['type']) === 1)) {
                        echo "<p class='m-0 p-1 list-group-item'>$key : $value</p>";
                    } else if ($key === "Contenu" && intval($message['type']) === 2) {
                        echo "<img style='width:20vw;' src='https://cdn.larche.ovh/webchat_images/$value' alt='photo'>";
                    }
                }
                echo "<button type='button' onclick='deleteMessageFromChat(".$message['Id'].")' class='mt-1 btn btn-danger'>Supprimer</button>";
                echo "</div>";
            } else {
                echo "<p class='text-center'>Messaage non trouvé";
            }
            break;
        }
        case "searchChatToEdit": {
            $chatName = htmlspecialchars($_GET['chatName'], ENT_QUOTES);
            if (strlen($chatName) === 0) {
                echo "La valeur rentrée est vide";
                exit;
            } else {
                $sqlSearchChat = $bdd->prepare("SELECT webchat.idchannel AS 'Id', name AS 'Nom', category AS 'Catégorie',
                COUNT(messages.idchannel) AS 'Nombre de messages' FROM webchat
                LEFT JOIN messages on webchat.idchannel = messages.idchannel WHERE name = ?;");
                $sqlSearchChat->execute([$chatName]);
                $chat = $sqlSearchChat->fetch(PDO::FETCH_ASSOC);

                if (isset($chat['Id'])) {
                    echo "<ul class='list-group'>";
                    foreach ($chat as $key => $value) echo "<li class='list-group-item'>$key : $value</li>";
                    echo "<button type='button' class='mt-1 btn btn-danger' onclick='deleteChat(".$chat['Id'].")'>Supprimer (les messages également)
                    </button></ul>";
                } else {
                    echo "<p class='text-center'>Chat non trouvé</p>";
                    exit;
                }
            }
            break;
        }
        default: {
            echo "Erreur";
            exit;
        }
    }
}
unset($bdd);
?>