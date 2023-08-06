<?php
$title = "webchat";
include('./includes/checksessions.php');
include("./includes/db-connect.php");
include("./includes/logHistoric.php");
$sqlGetChannels = $bdd->query("SELECT idchannel,name FROM webchat");
$channels = $sqlGetChannels->fetchAll(PDO::FETCH_ASSOC);

$sqlGetConvs = $bdd->prepare("SELECT user_id,username,pfp FROM user 
INNER JOIN dm ON (user.user_id = dm.user_id_receiver AND dm.user_id_sender = ?) OR  (user.user_id = dm.user_id_sender AND dm.user_id_receiver = ?) GROUP BY username");
$sqlGetConvs->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$userConvs = $sqlGetConvs->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | WebChat</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/accueil.css">
    <link rel="stylesheet" type="text/css" href="css/webchat.css">
</head>

<body class="webchat-page body-bg-beige">
    <?php include('./includes/header.php'); ?>
    <main>
        <div class="accueil-title">
            <div class="barres"></div>
            <h2>Webchat</h2>
            <div class="barres"></div>
        </div>
        <div id="chatSelectorSection">
            <select onclick="selectChatType(this.value)" name="typeWebchat" id="typeWebchatSelect">
                <option value="publicChat">Chat publique</option>
                <option value="dm">Messages privés</option>
            </select>
        </div>
        <section id="publicChat-container" class="webchat-container">
            <section class="channels-chats-container">
                <h3>Canaux</h3>
                <ul class="chatSelector">
                    <?php
                    foreach ($channels as $channel => $value) {
                        echo "<li draggable=true id='".$channels[$channel]['name']."' ondragstart='dragChat(event)'
                        onclick='selectChat(this.innerHTML)' class='channel-name'>".$channels[$channel]['name']."</li>";
                    }
                    ?>
                </ul>
                <a id="createNewWebchat" href="./new-ticket?obj=createWebchat">Créer un nouveau WebChat</a>
            </section>
            <section id="chatContainer" class="chatType-container" ondrop="dropChat(event)" ondragover="allowDrop(event)">
                <h3 id="webChatName" class="chatName"></h3>
                <div id="webChat" class="webchat-box">

                </div>
                <h4 id="messageSelectChat">
                    Veuillez sélectionner un chat à afficher
                </h4>
                <div id="messageDragDrop">
                    Relâchez ici pour sélectionner le chat
                </div>
                <div class="chatInput">
                    <form id="formInputChat" class="formInputWebchat" style="display:none;" enctype="multipart/form-data">
                        <div id="fileUploadedWebchat" class="file-uploaded-container" style="display:none;">
                            <p class="file-uploaded-box"></p>
                            <span onclick="deleteFileUploaded('imageToSendChat')">&#9587</span>
                        </div>
                        <button id="sendFileWebchat" onclick="addFileMessage('imageToSendChat')" class="sendFileBtnChat" type="button"><img src='./assets/PJ.png'></button>
                        <input type="file" id="imageToSendChat" accept="image/png, image/jpg, image/jpeg" title="imageSendChat" label="+" hidden>
                        <input type="text" autocomplete="off" class="input-message-chat" name="messageToSend" id="messageInputChat" placeholder="Envoyer un message...">
                        <button type="button" id="sendMessageBtn" 
                            onclick="sendMessage('webchat',document.getElementById('messageInputChat').value, document.getElementById('imageToSendChat'))">
                        <img src="./assets/send.png" alt="Envoyer">
                        </button>
                    </form>
                </div>
            </section>
        </section>
        <section id="dm-container" class="webchat-container" style="display:none;">
            <section class="channels-chats-container">
                <h3>Messages privés</h3>
                <div id="dm-create-conv-box">
                    <input type="text" oninput="searchUserToDm(this.value)" name="newUserConv" id="newUserConvInput" placeholder="Chercher un utilisateur">
                    <button onclick="createDm(document.getElementById('newUserConvInput').value)">Créer dm</button>
                </div>
                <ul id="dmConvsNames" class="chatSelector">
                    <?php
                    foreach ($userConvs as $conv => $value) {
                        echo "<li draggable=true id='".$userConvs[$conv]['username']."' ondragstart='dragChat(event)'
                        onclick='fetchDm(this.innerHTML)' class='channel-name'>".$userConvs[$conv]['username']."</li>";
                    }
                    ?>
                </ul>
            </section>
            <section id="dmContainer" class="chatType-container" ondrop="" ondragover="allowDrop(event)">
                <h3 id="dmChatUser" class="chatName"></h3>
                <div id="dmChat" class="webchat-box">

                </div>
                <!--<h4 id="">
                    Veuillez sélectionner une conversation à afficher
                </h4>
                <div id="">
                    Relâchez ici pour sélectionner la conversation
                </div>-->
                <div class="chatInput">
                    <form id="formDmChat" class="formInputWebchat" style="display:none;" method="post" enctype="multipart/form-data">
                        <div id="fileUploadedDm" class="file-uploaded-container" style="display:none;">
                            <p class="file-uploaded-box"></p>
                            <span onclick="deleteFileUploaded('sendFileDmInput')">&#9587</span>
                        </div>
                        <button id="sendFileDm" onclick="addFileMessage('sendFileDmInput')" class="sendFileBtnChat" type="button"><img src='./assets/PJ.png'></button>
                        <input type="file" id="sendFileDmInput" accept="image/png, image/jpg, image/jpeg" title="" hidden>
                        <input type="text" autocomplete="false" class="input-message-chat name="messageToSend" id="messageInputDm" placeholder="Envoyer un message...">
                        <button type="button" id="sendDmBtn" onclick="sendMessage('dm',document.getElementById('messageInputDm').value, document.getElementById('sendFileDmInput'))">
                            <img src="./assets/send.png" alt="Envoyer">
                        </button>
                    </form>
                </div>
            </section>
        </section>
    </main>
    <?php include('./includes/footer.php'); ?>
    <script src="./js/webchat.js"></script>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
</body>

</html>