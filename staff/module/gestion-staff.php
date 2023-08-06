<?php
include("../includes/db-connect.php");
$sqlGetAccounts = $bdd->query("SELECT user_id,email,username,name,status,pfp FROM user WHERE status>=3 ORDER BY user_id");
// On ne récupère que les utilisateurs ayant au moins le lvl de perms 3, donc forcément des staff
$members = $sqlGetAccounts->fetchAll(PDO::FETCH_ASSOC);;
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Staff Zone | Gestion des staff</title>
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

<body class="gestion-staff-page module-page">
    <?php 
        include("../includes/checkSession.php"); 
        include("/var/www/staff/includes/logHistoric.php");
    ?>
    <div class="mod-main-container container-fluid">
        <h2 class='mod-title fw-semibold'>Gestion des staff | Bonjour <span id="arobase">@<?php echo $_SESSION['user']; ?>
            </span></h2>
        <div id="btns-container" class="p-1 m-0 container-sm  mb-2 row">
            <?php
                include("./mod-includes/staff/btn-add-staff.php");
                include("./mod-includes/staff/btn-del-staff.php");
                if (isset($_SESSION['returnMessage'])) {
                    echo "<h3 class='text-info fs-6'>".$_SESSION['returnMessage']."</h3>";
                    unset($_SESSION['returnMessage']);
                }
            ?>
        </div>
        <section id="members-container" class="mod-section-container border d-flex flex-column border-1 border-dark rounded-1 shadow-sm">
            <ul class="mt-1 row">
                <p class="col-1">ID</p>
                <p class="col-sm ms-3 me-3">Email</p>
                <p class="col-sm ms-3 me-3">Pseudo</p>
                <p class="col-sm ms-3 me-3">Prénom</p>
                <p class="col-sm ms-3 me-3">Statut</p>
                <p class="col-sm ms-3 me-3">Profil</p>
            </ul>
            <div class="members-box overflow-y-auto" style="height: fit-content;">
                <?php
                // Boucle pour afficher chaque box de staff
                    for ($i = 0; $i < count($members); $i++) {
                        $username = $members[$i]['username'];
                        $userId = $members[$i]['user_id'];
                        if ($members[$i]['username'] === $_SESSION['user']) {
                            echo "<div style='background-color:#6fd98b;' id='account-box' class='staff-member-box border border-2 row p-1 m-2 rounded-1'>";
                        } else {
                            echo "<div id='account-box' class='staff-member-box border border-2 row p-1 m-2 rounded-1'>";
                        }
                        foreach ($members[$i] as $key => $value) {
                            if ($key === "status") $value = (intval($value) >= 4) ? "Admin" : "Modérateur";
                            if ($key === "user_id") {
                                echo "<span class='fs-6 col-sm-1 m-0'>$value</span>";
                            } else if ($key !== "pfp") {
                                echo "<span class='fs-6 text-center col-sm ms-2'>$value</span>";
                            }
                        }
                        echo "
                            <a href='https://larche.ovh/user?id=$userId' id='link-profil-ges-staff'
                            target='_blank' class='fs6 text-decoration-none rounded-5 border border-2 border-primary-subtle col ms-4 me-3'>
                                <img style='width:40px;margin-left:-10px;margin-right:6px;border-radius:50%;' src='$value'>
                                Accéder au profil
                            </a>
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
    <script src="../js/fetchUserStaffPage.js"></script>
    <script src="../js/popups.js"></script>
</body>

</html>