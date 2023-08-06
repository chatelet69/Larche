<?php
include("./includes/checksessions.php"); // vérifie si la session existe.
include("./includes/logHistoric.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Support</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/support.css">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
</head>

<body class="body-bg-beige body">
    <?php
    include('./includes/header.php');
    ?>
    <div class="accueil-title">
      <div class='barres'></div>
      <h2 class="text-index">Mes tickets</h2>
      <div class='barres'></div>
    </div>
    <main class='support-box'>
        <section class='ticket-box'>
            <div class="ticket-list" id="ticket-list">
                <?php
                    $ticket_list = $bdd -> prepare('SELECT id_ticket, `subject`, `date`, `status` FROM tickets WHERE user_id_author = ? ORDER BY id_ticket DESC');
                    $ticket_list -> execute([$_SESSION['user_id']]);
                    $ticket_list = $ticket_list -> fetchALL(PDO::FETCH_ASSOC);

                    if (!empty($ticket_list)) {
                        for ($i=0; $i < count($ticket_list); $i++) { 
                            $id = $ticket_list[$i]['id_ticket'];
                            foreach ($ticket_list[$i] as $key => $value) {
                                if($key === 'id_ticket') echo "<div class='ticket'><p class='ticket-id'>#" . $value . "</p>";
                                if($key === 'subject') echo "<div class='ticket-info'><a href='https://larche.ovh/ticket?id=" . $id . "'><p class='ticket-title'>" . $value . "</p></a>";
                                if($key === 'date'){
                                    $date = explode('-', $value);
                                    $end = end($date);
                                    $date[2] = $date[0];
                                    $date[0] = $end;
                                    $date = implode(' / ', $date);
                                     echo "<p class='ticket-date'>Envoyé le : " . $date . ".</p>";
                                }
                                if($key === 'status'){
                                    if ($value == 0) {
                                        echo "<div class='ticket-status'><p class='waiting'>En attente</p>";
                                        echo "<img src='./assets/timer.png' alt='chrono attente'></div>";
                                    }
                                    if ($value == 1) {
                                        echo "<div class='ticket-status'><p class='resolved'>Résolu</p>";
                                        echo "<img src='./assets/check.png' alt='ticket validé'></div>";
                                    }
                                    echo "<button class='ticket-del' onclick='delTicket(" . $id . ")'><img class='ticket-del-img' src='./assets/delete.png' alt='supprimer le ticket'></button></div></div>";
                                }
                            }
                        }
                    }else{
                        echo "<p class='no-recent-ticket'>Vous n'avez aucun tickets récents.</p>";
                    }
                ?>
            </div>
            <a href='https://larche.ovh/new-ticket' class='new-ticket'>Nouveau ticket</a>
        </section>
    </main>

    <?php
    include('./includes/footer.php');
    ?>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
    <script src="./js/support.js"></script>
</body>
</html>