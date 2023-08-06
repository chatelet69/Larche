<?php
    include("/var/www/staff/includes/db-connect.php");
    $sqlGetBackLogs = $bdd->prepare(
        "SELECT logs.id_log, user.email, user.username, DATE_FORMAT(logs.date, '%d/%m/%Y'), logs.ip 
        FROM user INNER JOIN logs ON user.user_id=logs.user_id WHERE interface='Back-Office' ORDER BY id_log ASC;"
    );
    // La requête récupère seulement les logs liées à des utilisateurs, grâce aux clés étrangères
    $sqlGetBackLogs->execute();
    $backOfficeLogs = $sqlGetBackLogs->fetchAll(PDO::FETCH_ASSOC);
    // On récupère toutes les lignes

    $sqlGetFrontLogs = $bdd->prepare(
        "SELECT logs.id_log, user.email, user.username, DATE_FORMAT(logs.date, '%d/%m/%Y'), logs.ip 
        FROM user INNER JOIN logs ON user.user_id=logs.user_id WHERE interface='Front-Office' ORDER BY id_log ASC;"
    );
    // La requête récupère seulement les logs liées à des utilisateurs, grâce aux clés étrangères
    $sqlGetFrontLogs->execute();
    $frontOfficeLogs = $sqlGetFrontLogs->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche Back-office | Panel des Logs</title>
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
        <h2 class='mod-title fw-semibold'>Panel des logs de connexion | Bonjour <span id="arobase">@<?php echo $_SESSION['user']; ?>
            </span></h2>
            <div class="row">
                <?php
                    include("./mod-includes/btn-search-log.php");
                    include("./mod-includes/btn-view-graph-logs.php");
                ?>
                <div class="staff-view-box mb-2 mt-2 col-sm-3">
                <select class="h-100 btn-popup" name="choose-what-logs" id="whatLogToOutput" onchange="changeLogsOutput(this)">
                    <option class="option-log-choose" value="Back-Office">Connexions Back-Office</option>
                    <option class="option-log-choose" value="Front-Office">Connexions Front-Office</option>
                </select>
                </div>
            </div>
        <section id="logs-container" style="max-height:71vh" class="mod-section-container border d-flex flex-column border-1 border-dark rounded-1 shadow-sm">
            <ul class="mt-1 mb-0 row">
                <p class="col-sm-1 col-1 col-md-1 col-lg-1 col-xl-1">Log n°</p>
                <p class="col-sm-1 col col-md col-lg col-xl text-center">Email</p>
                <p class="col-sm-1 col col-md col-lg col-xl ms-5">Pseudo</p>
                <p class="col-sm-1 col col-md col-lg col-xl">Date</p>
                <p class="col-sm-1 col col-md col-lg col-xl">IP</p>
            </ul>
            <div id="back-office-logs" class="members-box mods-boxs mh-100 back-office-logs">
                <?php
                // On affiche ici les box des logs
                    foreach ($backOfficeLogs as $member => $array) {
                        echo "<div id='account-box' class='border border-2 row p-1 m-2 rounded-1'>";
                        foreach ($backOfficeLogs[$member] as $key => $value) {
                            $logId = $backOfficeLogs[$member]['id_log'];
                            if ($key === "id_log") {
                                echo "<span class='fs-6 col-sm-1 col-md-1 col-lg-1 col-xl-1 col-1 m-0'>$value</span>";
                            } else {
                                echo "<span class='fs-6 col text-center col-md col-lg col-xl col-sm-1'>$value</span>";
                            }
                        }   
                        // Formulaire avec des input invisibles afin de mettre des données POST
                        // Si l'utilisateur clique sur la croix, ça envoit dans la requête l'id de la log et la requestType
                        echo "
                            <form action='https://staff.larche.ovh/includes/delete-row.php' class='col-sm-1' method='post'>
                            <input type='text' name='logId' value='$logId' hidden>
                            <input type='text' name='requestType' value='deleteLog' hidden>
                            <input type='submit' value='&#10060;' style='background: transparent; border: none;'></input>
                            </form>
                            ";
                        echo "</div>";
                    }
                ?>
            </div>
            <div id="front-office-logs" style="display:none;" class="mods-boxs members-box mh-100">
                <?php
                // On affiche ici les box des logs
                    foreach ($frontOfficeLogs as $member => $array) {
                        echo "<div id='account-box' class='border border-2 row p-1 m-2 rounded-1'>";
                        foreach ($frontOfficeLogs[$member] as $key => $value) {
                            $logId = $frontOfficeLogs[$member]['id_log'];
                            if ($key === "id_log") {
                                echo "<span class='fs-6 m-0 col-sm-1 col-md-1 col-lg-1 col-xl-1 col-1'>$value</span>";
                            } else {
                                echo "<span class='fs-6 col text-center col-md col-lg col-xl col-sm-1'>$value</span>";
                            }
                        }   
                        // Formulaire avec des input invisibles afin de mettre des données POST
                        // Si l'utilisateur clique sur la croix, ça envoit dans la requête l'id de la log et la requestType
                        echo "
                            <form action='https://staff.larche.ovh/includes/delete-row.php' class='col-sm-1' method='post'>
                            <input type='text' name='logId' value='$logId' hidden>
                            <input type='text' name='requestType' value='deleteLog' hidden>
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

    <script>
        function changeLogsOutput(selectedOption) {
            let option = selectedOption.value; 
            let backOfficeElement =  document.getElementById("back-office-logs");
            let frontOfficeElement = document.getElementById("front-office-logs");
            if (option === "Front-Office") {
                backOfficeElement.style.display = "none";
                frontOfficeElement.style.display = "block";
            } else if (option === "Back-Office") {
                frontOfficeElement.style.display = "none";
                backOfficeElement.style.display = "block";
            }
        }
    </script>
    <script src="../js/popups.js"></script>
</body>

</html>