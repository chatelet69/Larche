<?php
    include("/var/www/staff/includes/db-connect.php");
    $sqlGetAccounts = $bdd->query("SELECT user_id,email,username,name,ip,status,pfp FROM user ORDER BY user_id");
    $members = $sqlGetAccounts->fetchAll(PDO::FETCH_ASSOC);
    $tradsRank = [
        "Banni", "Membre", "Contributeur", "Modérateur", "Admin", "SuperAdmin"
    ]
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Staff Zone | Gestion des membres</title>
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
    <script src="https://unpkg.com/pdf-lib@1.4.0/dist/pdf-lib.min.js"></script>
    <script src="https://unpkg.com/downloadjs@1.4.7/download.js"></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
    <script src="https://unpkg.com/jspdf-autotable@3.5.28/dist/jspdf.plugin.autotable.js"></script>
    <!--<script src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.es.min.js"></script>-->
</head>

<body class="gestion-membres-page module-page">
    <?php 
        include("../includes/checkSession.php"); 
        include("/var/www/staff/includes/logHistoric.php");
    ?>
    <div class="mod-main-container container-fluid">
        <h2 class='mod-title fw-semibold'>Gestion des membres | Bonjour <span id="arobase">@<?php echo $_SESSION['user']; ?>
            </span></h2>
        <div id="btns-container" class="p-1 m-0 container-sm mb-2 row">
            <?php
            include("./mod-includes/members/btn-create-account.php");
            include("./mod-includes/members/btn-search-account.php");
            include("./mod-includes/members/btn-edit-account.php");
            if (isset($_SESSION['returnMessage'])) {
                $returnMess = $_SESSION['returnMessage'];
                if ($returnMess === "member_banned") {
                    echo "<h3 class='fs-6 text-warning'>Le membre a bien été banni</h3>";
                } else if ($returnMess === "member_unbanned") {
                    echo "<h3 class='fs-6 text-success'>Le membre a bien été débanni</h3>";
                } else if ($returnMess === "member_deleted") {
                    echo "<h3 class='fs-6 text-success'>Le membre a bien été supprimé</h3>";
                }
                unset($_SESSION['returnMessage']);
            }
            ?>
        </div>
        <section id="members-container" class="mod-section-container border d-flex flex-column border-1 border-dark rounded-1 shadow-sm">
            <ul class="mt-1 ps-4 pe-4 mb-0 list-group text-center list-group-horizontal row">
                <li class="list-group-item col-sm-1 col-1">ID</li>
                <li class="list-group-item col-sm-2 col-2">Email</li>
                <li class="list-group-item col-sm col">Pseudo</li>
                <li class="list-group-item col-sm col">Prénom</li>
                <li class="list-group-item col-sm col">IP</li>
                <li class="list-group-item col-sm col">Statut</li>
                <li class="list-group-item col-sm col">Photo Profil</li>
            </ul>
            <div id="members-box" class="overflow-y-auto mods-boxs" style="height: fit-content;">
                <?php
                    for ($i = 0; $i < count($members); $i++) {
                        $userPfp = $members[$i]['pfp'];
                        if (intval($members[$i]['status']) === 0) {
                            echo "<div id='account-box' class='member-is-banned border border-2 row p-1 m-2 rounded-1'>";
                        } else {
                            echo "<div id='account-box' class='border border-2 row p-1 m-2 rounded-1'>";
                        }
                        $members[$i]['status'] = $tradsRank[$members[$i]['status']];
                        foreach ($members[$i] as $key => $value) {
                            switch($key) {
                                case "user_id":
                                    echo "<span class='fs-6 col-sm-1 text-center col-1 col-md-1 col-lg-1 col-xl-1 m-0'>$value</span>";
                                    break;
                                case "email": {
                                    echo "<span class='fs-6 text-center col-2'>$value</span>";
                                    break;
                                }
                                case "username": {
                                    echo "<span class='fs-6 text-center col-sm col col-md col-lg col-xl'>
                                    <a class='member-profile-link' href='https://larche.ovh/user?id=".$members[$i]['user_id']."' target='_blank'>$value</a></span>";
                                    break;
                                }
                                case "ip": {
                                    echo "<span class=' sensitive-item fs-6 text-center col-sm col col-md col-lg col-xl'>$value</span>";
                                    break;
                                }
                                default:
                                    if ($key !== "pfp") echo "<span class='fs-6 text-center col-sm col col-md col-lg col-xl'>$value</span>";
                                    break;
                            }
                        }
                        echo "<img class='col-sm-1 col-1 col-md-1 ms-5 me-5' style='width:4vw;' src='$userPfp'>";
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
    <script src="https://staff.larche.ovh/js/exportPdf.js"></script>
    <script src="../js/popups.js"></script>
    <script src="../js/apimods.js"></script>
</body>

</html>