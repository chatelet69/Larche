<?php
$check = true;
$options = [
    "cookie_secure" => true,
    "cookie_path" => "/",
    "gc_maxlifetime" => 200000
];
session_set_cookie_params(200000, "/", "staff.larche.ovh", true);
session_start($options);

if (!isset($_SESSION['email']) || !isset($_SESSION['lvl_perms']) || $_SESSION['lvl_perms'] < 3) {
    session_destroy();
    $check = false;
    header("Location: https://staff.larche.ovh/login");
    exit;
}

if (!isset($_COOKIE['signedStaff']) || $_COOKIE['signedStaff'] !== $_SESSION['email']) {
    $check = false;
    header("Location: https://staff.larche.ovh/login");
    exit;
}

if (!isset($_COOKIE) || empty($_COOKIE) || isset($_SESSION['logged']) === false || $_SESSION['logged'] !== true) {
    $check = false;
    header("Location: https://staff.larche.ovh/login");
    exit;
}

if ($check === false) {
    header("Location: https://staff.larche.ovh/login");
    exit;
} else if ($check == true && $_SESSION['logged'] === true && !empty($_COOKIE) && isset($_COOKIE['signedStaff'])) {  
    include("/var/www/staff/includes/dashboard.php");
    include("/var/www/staff/includes/db-connect.php");
    $sqlCheckStatus = $bdd->prepare("SELECT status FROM user WHERE user_id = ?");
    $sqlCheckStatus->execute([$_SESSION['user_id']]);
    $userStatus = $sqlCheckStatus->fetch(PDO::FETCH_ASSOC);
    $userStatus = intval($userStatus['status']);
    if ($userStatus < 3) {
        session_destroy();
        unset($_COOKIE);
    }
    $user = [
        "username" => $_SESSION['user'],
        "lvl" => $_SESSION['lvl_perms']
    ];
    $staffName = $user['username'];
    $lvl = $user['lvl'];

    date_default_timezone_set("Europe/Paris");
}
?>