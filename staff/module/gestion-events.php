<?php
include("/var/www/staff/includes/db-connect.php");
$sqlGetEvents = $bdd->query("SELECT id_event, title, username_author, DATE_FORMAT(date_event, '%d/%m/%Y') AS date FROM events");
$events = $sqlGetEvents->fetchAll(PDO::FETCH_ASSOC);
// On récupère toutes les lignes
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche Back-office | Panel des évènements</title>
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

<body class="gestion-events-page module-page">
    <?php
    include("../includes/checkSession.php");
    include("/var/www/staff/includes/logHistoric.php");
    ?>
    <div class="mod-main-container container-fluid">
        <h2 class='mod-title fw-semibold'>Gestion des évènements | Bonjour <span id="arobase">@<?php echo $_SESSION['user']; ?>
            </span>
        </h2>
        <?php
            include("./mod-includes/articles_events/btn-search-event.php");
        ?>
        <section id="events-box-container" style="max-height:72vh" class="mod-section-container border d-flex flex-column border-1 rounded-2 shadow-sm">
            <ul class="mt-1 mb-0 row">
                <p class="text-center col-sm-1">Event n°</p>
                <p class="text-center col-sm">Titre</p>
                <p class="text-center col-sm">Auteur</p>
                <p class="text-center col-sm">Date</p>
                <p class="col-sm">Lien Event</p>
            </ul>
            <div id="events-boxs" class="mh-100">
                <?php
                    foreach($events as $event => $value) {
                        $eventId = $events[$event]['id_event'];
                        echo "<div id='event-".$eventId."' class='border text-center border-2 row p-1 m-2 rounded-1'>";
                        foreach($events[$event] as $key => $value) {
                            if ($key === "id_event") {
                                echo "<span class='fs-6 col-sm-1 m-0'>$value</span>";
                            } else {
                                echo "<span class='fs-6 text-center col-sm ms-5'>$value</span>";
                            }
                        }
                        echo "<a class='col btn btn-primary' style='width:5vw;' href='https://larche.ovh/event?id=$eventId' target='_blank'>Aller à l'event</a>";
                        echo "
                        <form action='https://staff.larche.ovh/includes/delete-row.php' class='col-sm-1' method='post'>
                        <input type='text' name='id' value='$eventId' hidden>
                        <input type='text' name='type' value='event' hidden>
                        <input type='text' name='requestType' value='deleteObj' hidden>
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
    <script src="https://staff.larche.ovh/js/changeDivBg.js"></script>
    <script src="../js/popups.js"></script>
    <script src="../js/apiContent.js"></script>
</body>

</html>