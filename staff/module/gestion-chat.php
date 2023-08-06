<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche Back-office | Gestion du Chat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" type="text/css" href="../css/modules.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="../css/staff.css">
    <link rel="stylesheet" type="text/css" href="../css/forms.css">
    <link rel="shortcut icon" href="../assets/favicon.ico">
</head>

<body class="gestion-chat-page module-page">
    <?php
        include("../includes/checkSession.php");
        include("/var/www/staff/includes/logHistoric.php");
    ?>
    <div class="mod-main-container container-fluid">
        <h2 class='mod-title fw-semibold'>Gestion Chat | Bonjour <span id="arobase">@<?php echo $_SESSION['user']; ?></span></h2>
        <div id="btns-container" class="p-1 m-0 mb-2 row">
        <?php
            include("./mod-includes/webchat/btn-create-chat.php");
            include("./mod-includes/webchat/btn-del-chat.php");
            include("./mod-includes/webchat/btn-search-message-chat.php");
            if (isset($_SESSION['returnMessage'])) {
                echo "<h3 class='text-info fs-6'>".$_SESSION['returnMessage']."</h3>";
                unset($_SESSION['returnMessage']);
            }
        ?>
        </div>
        <div>
            <h3>Chercher un chat à afficher</h3>
            <input type="text" name="chatSelect" id="chatSearchToView" oninput="fetchChatLogs(this.value)">
        </div>
        <section id="chat-messages-container" class="mt-2 mod-section-container border d-flex flex-column border-1 border-dark rounded-1 shadow-sm">
            <h3 class="text-center">Messages du chat</h3>
            <ul class="list-group list-group-horizontal row text-center me-2 ms-2">
                <li class="list-group-item fs-6 col-sm col-md col-lg col-xl col">ID</li>
                <li class="list-group-item fs-6 col-sm col-md col-lg col-xl col">Auteur</li>
                <li class="list-group-item fs-6 col-sm col-md col-lg col-xl col">Contenu</li>
                <li class="list-group-item fs-6 col-sm col-md col-lg col-xl col">Date</li>
            </ul>
            <div style="max-height:46vh;" class="overflow-y-auto module-scroll-bar" id="messagesContainer">

            </div>
        </section>
    </div>
    <?php echo "</section></div></main>"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://staff.larche.ovh/js/changeDivBg.js"></script>
    <script src="../js/popups.js"></script>
    <script src="../js/apimods.js"></script>
</body>

</html>