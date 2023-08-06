<?php
include("./includes/checksessions.php"); // vérifie si la session existe.
include("./includes/logHistoric.php");

$id = $_GET['id'];

$ticketinfo = $bdd->prepare('SELECT id_ticket, `subject`, content, user_id_author, `date`, `status`, `file` FROM tickets WHERE id_ticket = ?');
$ticketinfo->execute([$id]);
$ticketinfo = $ticketinfo->fetchALL(PDO::FETCH_ASSOC);

$ticketconv = $bdd->prepare('SELECT answer_id, user_id_author, ticket_id, content, `file`, answer_timestamp FROM tickets_convs WHERE ticket_id = ? ORDER BY answer_timestamp ASC');
$ticketconv->execute([$id]);
$ticketconv = $ticketconv->fetchALL(PDO::FETCH_ASSOC);

if ($_SESSION['user_id'] !== $ticketinfo[0]['user_id_author']) {
    header('location:https://larche.ovh/error404');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Ticket #<?php echo $id; ?></title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/support.css">
    <link rel="stylesheet" type="text/css" href="css/new-ticket.css">
    <link rel="stylesheet" type="text/css" href="css/ticket.css">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
</head>

<body class="body-bg-beige body">
    <?php
    include('./includes/header.php');
    ?>
    <div class="accueil-title">
        <div class='barres'></div>
        <h2 class="text-index">Ticket #<?php echo $id; ?></h2>
        <div class='barres'></div>
    </div>
    <p class='title-ticket text-index'><?php echo $ticketinfo[0]['subject'] ?></p>
    <main class='support-box'>
        <section class='ticket-box'>
            <div class='conv-ticket' id='conv-ticket'>
                <?php
                    include('./includes/display-conv-ticket.php');
                    ?>
            </div>
            <div class='send-msg-ticket' id='send-msg-ticket'>
                <?php
                if ($ticketinfo[0]['status'] == 1) {
                    echo "    <div class='end-ticket'>
                    <div class='barres-ticket'></div>
                    <h2 class='resolved-ticket'>Ticket résolu</h2>
                    <div class='barres-ticket'></div>
                    </div>";
                } else {
                    echo "
                        <form id='formSendMsg' enctype='multipart/form-data' method='post'>
                        <div class='ticket-msg'>
                        <label for='ticket-file' class='label-file grey-option'><img src='./assets/PJ.png' alt='pièces jointes' class='PJ-article'></label>
                        <input id='ticket-file' class='input-file grey-option' type='file' accept='image/jpeg , image/png, image/gif' name='image-ticket' name='file'>
                        <input type='text' class='grey-input' placeholder='Ecrivez votre message' id='contentMsgTicket' name='msg-ticket'>
                        <input type='text' id='ticket-id' value='$id' name='id' hidden>
                        <button type='submit' class='button-no-style button-ticket'><img src='./assets/send.png' alt='bouton-envoyer' class='btn-ticket-msg' id='btn-ticket-msg'></button>
                        </div>    
                        </form>";
                }
                ?>
            </div>
            <a href='https://larche.ovh/support'><img src="./assets/back.png" alt="flèche retour" class="arrow-back"></a>
            <?php
            if ($ticketinfo[0]['status'] == 0) {
                echo "<div class='ticket-status'><p class='waiting'>En attente</p>";
                echo "<img src='./assets/timer.png' alt='chrono attente'></div>";
            }
            if ($ticketinfo[0]['status'] == 1) {
                echo "<div class='ticket-status'><p class='resolved'>Résolu</p>";
                echo "<img src='./assets/check.png' alt='ticket validé'></div>";
            }
            ?>
        </section>
    </main>

    <?php
    include('./includes/footer.php');
    ?>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
    <script src="./js/support.js"></script>
    <script>
        scrollBottom();
    </script>
</body>

</html>