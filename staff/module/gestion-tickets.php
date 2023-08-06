<?php
    include("/var/www/staff/includes/db-connect.php");
    $sqlGetTickets = $bdd->prepare("SELECT id_ticket, username, subject, 
    DATE_FORMAT(date, '%d/%m/%Y'), tickets.status FROM tickets,user WHERE user.user_id=tickets.user_id_author");
    $sqlGetTickets->execute();
    $tickets = $sqlGetTickets->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche Back-office | Panel des Tickets</title>
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

<body class="panel-logs-page module-page">
    <?php 
        include("../includes/checkSession.php");
        include("/var/www/staff/includes/logHistoric.php");
    ?>
    <div class="mod-main-container container-fluid">
        <h2 class='mod-title fw-semibold'>Gestion des tickets | Bonjour <span id="arobase">@<?php echo $_SESSION['user']; ?>
            </span></h2>
            <div class="d-flex flex-row">
                <?php
                    include("./mod-includes/btn-view-ticket.php");
                ?>
            </div>
        <section id="logs-container" style="max-height:71vh" class="mod-section-container border d-flex flex-column border-1 border-dark rounded-1 shadow-sm">
            <ul class="mt-1 mb-0 m-2 row text-center list-group list-group-horizontal">
                <p class="list-group-item col-1" style="text-align:left;">Ticket n°</p>
                <p class="list-group-item col">Auteur</p>
                <p class="list-group-item col">Sujet</p>
                <p class="list-group-item col">Date</p>
                <p class="list-group-item col">Statut</p>
                <p class="list-group-item col">Accéder/Supp</p>
            </ul>
            <div id="back-office-logs" class="members-box mh-100 back-office-logs">
                <?php
                // On affiche ici les box des logs
                    foreach ($tickets as $ticket => $array) {
                        $ticketId = $tickets[$ticket]['id_ticket'];
                        echo "<div id='ticket-$ticketId' class='border mod-list-box p-1 border-2 row m-2 rounded-1'>";
                        foreach ($tickets[$ticket] as $key => $value) {
                            if ($key === "id_ticket") {
                                echo "<p class='col-1'>$value</p>";
                            } else if ($key === "status") {
                                $value = ($value === "1") ? "Résolu" : "Non résolu";
                                echo "<p class='col text-center'>$value</p>";
                            } else {
                                echo "<p class='col text-center'>$value</p>";
                            }
                        }   
                        echo "<button id='openTicketBtn' class='col-sm-1 ms-1 me-2 btn btn-primary' onclick='openTicket($ticketId)'>Accéder</button>";
                        echo "
                            <form action='https://staff.larche.ovh/includes/delete-row.php' class='ps-4 pt-1 col-sm-1 col-md-1' method='post'>
                            <input type='text' name='id' value='$ticketId' hidden>
                            <input type='text' name='requestType' value='deleteObj' hidden>
                            <input type='text' name='type' value='ticket' hidden>
                            <input type='submit' value='&#10060;' style='background: transparent; border: none;'></input>
                            </form>
                            ";
                        echo "</div>";
                    }
                ?>
            </div>
        </section>
    </div>
    <?php echo "</section></div></main>"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://staff.larche.ovh/js/changeDivBg.js"></script>    
</body>

</html>