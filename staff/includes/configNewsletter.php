<?php
// Récupération des données de la requête POST
// On récupère les données json dans le cas ou la requête en contient
/*if (isset($_POST) && count($_POST) === 1) {
    $json = file_get_contents('php://input');
    $data = json_decode($json); // on décode les données
    $jsonPostData = [];
}
if (!empty($json)) foreach ($data as $key => $value) $jsonPostData["$key"] = $value; // on assigne ces données à un tableau*/

if (isset($_POST["configMailsRequest"])) $requestType = $_POST["configMailsRequest"]; 

// Connexion à la Base de données
include("./db-connect.php");
session_start();

// Codes des fonctionnalités
if ($requestType === "configNewsletter") {
    try {
        if (isset($_POST['timeFormNews'])) $timeFormNews = $_POST['timeFormNews'];
        if (isset($_POST['daysFormNews'])) $daysFormNews = $_POST['daysFormNews'];

        if (!empty($timeFormNews) && !empty($daysFormNews)) {
            $time = explode(":", $timeFormNews);
            $taskToPut = $time[1]. " ". $time[0]." * * ".$daysFormNews. " php -q /var/www/mails/newsletter.php > /var/log/myjob.log 2>&1";
            $taskToPut = $taskToPut."
30 15 * * * php -q /var/www/mails/event_reminder.php > /var/log/myjob.log 2>&1
30 16 * * * php -q /var/www/mails/inactivityCheck.php > /var/log/myjob.log 2>&1
30 14 * * * php -q /var/www/mails/deleteInactivity.php > /var/log/myjob.log 2>&1";
            shell_exec("echo '$taskToPut' > /var/spool/cron/crontabs/www-data");
        }

        if (isset($_POST['formStartText']) && !empty($_POST['formStartText'])) {
            $startText = $_POST['formStartText'];
            $startFile = fopen("/var/www/cdn/newsletter/newsStartText.txt", 'w');
            fwrite($startFile, "$startText");
            fclose($startFile);
        }
        
        if (isset($_POST['formEndText']) && !empty($_POST['formEndText'])) {
            $endText = $_POST['formEndText'];
            $endFile = fopen("/var/www/cdn/newsletter/newsEndText.txt", 'w');
            fwrite($endFile, "$endText");
            fclose($endFile);
        }

        if (empty($_POST)) {
            $_SESSION['requestRes'] = "need_all_values";
            header("Location: ../module/gestion-mails");
            exit;
        }

        session_start();
        $_SESSION['requestRes'] = "config-changed";
        header("Location: ../module/gestion-mails");
        exit;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

if ($requestType === "configInactivity") {
    try {
        if (isset($_POST['timeFormNews'])) $timeFormNews = $_POST['timeFormNews'];

        if (isset($_POST['formInactivityText']) && !empty($_POST['formInactivityText'])) {
            $textInac = $_POST['formInactivityText'];
            $inacFile = fopen("/var/www/cdn/newsletter/inactivityText.txt", 'w');
            fwrite($inacFile, "$textInac");
            fclose($inacFile);
        }

        session_start();
        $_SESSION['requestRes'] = "config-inac-changed";
        header("Location: ../module/gestion-mails");
        exit;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

?>