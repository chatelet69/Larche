<?php
    // Inclusion de db-connect, et fetch d'une requête query pour récupérer toutes les logs staff
    include("/var/www/staff/includes/db-connect.php");
    $sqlGetStaffLogs = $bdd->query("SELECT id_staff_action,type,username,DATE_FORMAT(date, '%d/%m/%Y') 
    FROM staff_actions,user WHERE user.user_id = staff_actions.user_id"); 
    $staffLogs = $sqlGetStaffLogs->fetchAll(PDO::FETCH_ASSOC);   
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche Back-office | Panel des Staff Logs</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" type="text/css" href="../css/modules.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="../css/staff.css">
    <link rel="stylesheet" type="text/css" href="../css/forms.css">
    <link rel="shortcut icon" href="../assets/favicon.ico">
</head>

<body class="staff-logs-page module-page">
    <?php 
    include("../includes/checkSession.php"); 
    include("/var/www/staff/includes/logHistoric.php");
    ?>
    <div class="mod-main-container container-fluid main-container">
        <h2 class='mod-title fw-semibold'>Panel des Actions Staff | Bonjour <span id="arobase">@<?php echo $_SESSION['user']; ?></span></h2>
        <?php include("./mod-includes/staff/btn-search-staff-log.php"); ?>
        <section id="staff-logs-container" style="max-height:71vh" class="mod-section-container border d-flex flex-column border-1 border-dark-subtle rounded-1 shadow-sm">
            <ul class="mt-1 d-flex flex-row justify-content-start text-center ps-2 pe-2 list-group list-group-horizontal text-center mb-0">
                <p class="list-group-item mb-0 w-25">Log n°</p>
                <p class="list-group-item mb-0 w-50">Type</p>
                <p class="list-group-item mb-0 w-25">Auteur</p>
                <p class="list-group-item mb-0 w-25">Date</p>
            </ul>
            <div id="staff-logs-box" class="mods-boxs overflow-y-auto mh-100">
                <?php
                include("../includes/trads.php");
                // Boucle pour afficher chaque log box
                    for ($i = 0; $i < count($staffLogs); $i++) {
                        echo "<div id='staff-log-box' class='d-flex flex-row justify-content-start border border-2 p-1 m-2 rounded-1'>";
                        foreach ($staffLogs[$i] as $key => $value) {
                            $logId = $staffLogs[$i]['id_staff_action'];
                            switch ($key) {
                                case 'id_staff_action':
                                    echo "<span class='text-center ms-2 w-25 fs-6 m-0'>$value</span>";
                                    break;
                                case 'type':
                                    echo "<span class='text-center ms-4 w-50 fs-6 m-0'>$staffLogsTradValue[$value]</span>";
                                    break;
                                default:
                                    echo "<span class='text-center ms-4 w-25 fs-6 item-$key'>$value</span>";
                                    break;
                            }
                        }   
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
    <script src="../js/apimods.js"></script>
    <script src="../js/popups.js"></script>
</body>

</html>