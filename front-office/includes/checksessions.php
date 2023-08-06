<?php
session_start([
    // Les options de la session
    "cookie_secure" => true,
    "gc_maxlifetime" => 2000
]);

if (!isset($_SESSION['email']) || !isset($_SESSION['user_id'])) {
    header("Location: https://larche.ovh/login");
    exit;
}

if (isset($_SESSION['lvl_perms']) && intval($_SESSION['lvl_perms']) === 0) {
    session_destroy();
    header("Location: https://larche.ovh/index");
    exit;
}

if (!isset($_SESSION['logged']) || $_SESSION['logged'] !== true) {
    header("Location: https://larche.ovh/login");
    exit;
} else if ($_SESSION['logged'] === true) {
    $user = [
        "username" => $_SESSION['user'],
        "lvl" => $_SESSION['lvl_perms']
    ];
    $name = $user['username'];
    $lvl = $user['lvl'];
}
?>