<?php
include("./includes/checkSession.php"); 
include("./includes/db-connect.php");
$sqlGetLogs = $bdd->prepare("SELECT id_log AS 'ID',ip AS 'IP',DATE_FORMAT(date, '%d/%m/%Y') AS 'Date',
time AS 'Heure' FROM logs WHERE email=? ORDER BY id_log DESC LIMIT 3");
$sqlGetLogs->execute([$_SESSION['email']]); // On exec
$myLastLogins = $sqlGetLogs->fetchAll(PDO::FETCH_ASSOC);

$sqlGetActions = $bdd->prepare("SELECT type AS 'Type',content AS 'Détails',  DATE_FORMAT(date, '%d/%m/%y') AS 'Date' 
FROM staff_actions WHERE user_id = ? ORDER BY id_staff_action DESC LIMIT 8");
$sqlGetActions->execute([$_SESSION['user_id']]);
$myActions = $sqlGetActions->fetchAll(PDO::FETCH_ASSOC);
include("./includes/logHistoric.php");
include("./includes/trads.php");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Larche Panel Staff | Mon compte Staff</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="robots" content="noindex,nofollow">
    <link rel="stylesheet" type="text/css" href="./css/staff.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon" href="./assets/favicon.ico">
</head>

<body>
    <div id="account-staff-container" class="container-fluid d-flex flex-column p-2">
        <div class="d-flex flex-row justify-content-between flex-wrap mb-3">
           <!-- My account and last logs --> 
            <div id="staff-box-container" class="">
                <section id="myAccountBox"class="bg-light rounded-2 w-100 p-1">
                    <img style="width:20%;border-radius:50%;" src="<?php echo $_SESSION['user_pfp']; ?>" alt="pdp">
                    <div class="row">
                        <section class="col">
                            <h4 class="text-black">Prénom</h4>
                            <h5
                                class="fs-5 text-clear-grey w-50 bg-opacity-25 border border-dark-subtle rounded-1 border-1">
                                <?php echo $_SESSION['name']; ?>
                            </h5>
                        </section>
                        <section class="col">
                            <h4 class="text-black">Nom</h4>
                            <h5 class="text-clear-grey w-50 bg-opacity-25 border border-dark-subtle rounded-1 border-1">
                                <?php echo $_SESSION['lastname']; ?>
                            </h5>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col">
                            <h4 class="text-black">Email</h4>
                            <h5
                                class="fs-5 text-clear-grey w-auto bg-opacity-25 border border-dark-subtle rounded-1 border-1">
                                <?php echo $_SESSION['email']; ?>
                            </h5>
                        </section>
                        <section class="col">
                            <h4 class="text-black">Rôle</h4>
                            <h5
                                class="fs-5 text-clear-grey w-50 bg-opacity-25 border border-dark-subtle rounded-1 border-1">
                                <?php echo $_SESSION['role']; ?>
                            </h5>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col">
                            <h4 class="text-black">ID</h4>
                            <h5 class="text-clear-grey w-25 bg-opacity-25 border border-dark-subtle rounded-1 border-1">
                                <?php echo  $_SESSION['user_id']; ?>
                            </h5>
                        </section>
                        <section class="col">
                            <h4 class="text-black">Niveau de perms</h4>
                            <h5 class="text-clear-grey w-25 bg-opacity-25 border border-dark-subtle rounded-1 border-1">
                                <?php echo $_SESSION['lvl_perms']; ?>
                            </h5>
                        </section>
                        <section class="col">
                            <a class="btn btn-info" href="https://larche.ovh/profil" target="_blank">Vers profil</a>
                        </section>
                    </div>
                </section>
                <!-- Last logs -->
                <section id="myLastLogins" class="mt-2 form">
                    <h3 class="fw-semibold clear-grey">Mes dernières connexions</h3>
                    <div
                        class="box-clear-blue p-2 rounded-1 d-flex flex-column justify-content-center align-content-center">
                        <?php
                        for ($i = 0; $i < 3; $i++) {
                            echo "<div style='font-size:1.5vh;' class='bg-light-subtle shadow-sm p-2 row rounded-2 m-2'>";
                            foreach ($myLastLogins[$i] as $key => $value) {
                                if ($value === NULL) $value = "Pas d'info";
                                echo "<span class=' col-sm'>$key : $value</span>";
                            }
                            echo "</div>";
                        }
                        ?>
                    </div>
                </section>
            </div>
            <div id="myLastActions" class="me-5">
                <h2 class='text-center'>Mes dernières actions</h2>
                <div id="lastActionsContainer" class="border-1 border border-dark">
                    <?php 
                    if (!empty($myActions)) {
                        foreach ($myActions as $action => $value) {
                            echo "<div style='font-size:1.5vh;' class='bg-light-subtle shadow-sm p-2 row rounded-2 m-2'>";
                            foreach ($myActions[$action] as $key => $value) {
                                if ($value === NULL) $value = "Pas d'info";
                                if ($key === "Type") {
                                    echo "<span class=' col-sm'>$key : ".$staffLogsTradValue[$value]."</span>";
                                } else {
                                    echo "<span class=' col-sm'>$key : $value</span>";
                                }
                            }
                            echo "</div>";
                        }
                    } else {
                        "Pas d'actions";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo "</section></div></main>"; ?>
    <script type="text/javascript" src="https://staff.larche.ovh/js/changeDivBg.js"></script>
</body>

</html>

<!--

                    <form action="./includes/staff/modify-account.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="newUsername" class="form-label">Modif pseudo</label>
                            <input type="text" id="newUsername" name="new-username" class="w-50 form-control">
                        </div>
                        <div class="mb-3">  
                            <label for="newEmail" class="form-label">Modif email</label>
                            <input type="text" id="newEmail" name="new-email" class="w-50 form-control">
                        </div>
                        <div class="mb-3">
                            <label for="newPdp" class="form-label">Modif pdp</label>
                            <input type="file" id="newPdp" accept="image/png, image/jpeg, image/gif image/jpg" name="new-pfp" class="w-50 form-control">
                        </div>
                        <input type="submit" class="btn btn-success">
                    </form>
-->