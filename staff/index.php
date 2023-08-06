<!DOCTYPE html>
<html class="index-page" lang="fr">
    <head>
        <title>Staff Zone | Dashboard</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <meta name="robots" content="noindex,nofollow">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <link rel="stylesheet" type="text/css" href="./css/staff.css">
        <link rel="stylesheet" type="text/css" href="./css/forms.css">
        <link rel="shortcut icon" href="./assets/favicon.ico">
    </head>
    <body>
        <?php 
            include("./includes/checkSession.php");
            include("./includes/logHistoric.php");
            date_default_timezone_set("Europe/Paris");
            $date = (int)date('H');
            $hello = ($date > 18 || $date < 5) ? "Bonsoir" : "Bonjour";
        ?>
            <div class="index-dash-container container-fluid p-2">
                    <?php 
                        echo "<h1 id='staff-main-title'>Accès Staff | $hello <span id='arobase'>@".$_SESSION['user']."</span></h1>";
                        if (isset($_SESSION['returnMessage'])) {
                            echo "<script><alert(".$_SESSION['returnMessage'].")</script>";
                            unset($_SESSION['returnMessage']);
                        }
                        echo "<div id='btns-container' class='p-1 m-0 row row-cols-6'>";
                        include("./includes/btns-dashboard/btn-config-captcha.php");
                        include("./includes/btns-dashboard/btn-del-captcha.php");
                        include("./includes/btns-dashboard/btn-config-avatar.php");
                        include("./includes/btns-dashboard/btn-del-avatar.php");
                        echo "</div><div id='includes-container' class='m-0 d-flex flex-row mt-1 p-1'>";
                        include("./includes/last-views/view-last-signup.php");
                        echo "<div class='d-flex align-content-start flex-wrap'>";
                        include("./includes/last-views/view-last-articles.php");
                        include("./includes/last-views/view-last-events.php");
                        include("./includes/last-views/view-last-staff-actions.php");
                        include("./includes/last-views/view-last-tickets.php");
                        echo "</div></div>";
                    ?>
            </div>
        <?php echo "</section></div></main>"; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
        <script defer src="./js/index.js"></script>
        <script src="./js/popups.js"></script>
        <script src="./js/changeDivBg.js"></script>
    </body>
</html>