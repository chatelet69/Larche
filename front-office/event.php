<?php
$title = "event";
include("./includes/checksessions.php");
include("./includes/logHistoric.php");

$id = $_GET['id'];

$eventInfo = $bdd->prepare('SELECT title, `description`, date_creation, `image`, date_event, place, nb_max, `subject`, id_author, username_author, email_author, `time`, teaser FROM events WHERE id_event = ?');
$eventInfo->execute([$id]);
$eventInfo = $eventInfo->fetchAll(PDO::FETCH_ASSOC);
if (empty($eventInfo)) {
  header('location: ./error404.html');
  exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>L'Arche | Évènement</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/displayevent.css">
</head>

<body class="body-bg-beige body">
  <?php
  include('./includes/header.php');
  ?>
  <main class="main-event">
    <div class="event-content" id='event-content'>
      <?php
        include('./includes/displayEvent.php');
      ?>
    </div>
  </main>
  <?php
  include('./includes/footer.php');
  ?>
  <script src="./js/darkmode.js"></script>
  <script src="./js/code.js"></script>
  <script src="./js/editEvent.js"></script>
</body>

</html>