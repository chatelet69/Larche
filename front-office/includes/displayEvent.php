<?php
if (!isset($bdd)) {
    $title = "event";
include("./checksessions.php");
include("./logHistoric.php");

$id = $_GET['id'];

$eventInfo = $bdd->prepare('SELECT title, `description`, date_creation, `image`, date_event, place, nb_max, `subject`, id_author, username_author, email_author, `time`, teaser FROM events WHERE id_event = ?');
$eventInfo->execute([$id]);
$eventInfo = $eventInfo->fetchAll(PDO::FETCH_ASSOC);

if (empty($eventInfo)) {
  header('location: ./error404.html');
  exit;
}
}

echo "<div class='event-infos'>
        <p class='" . ($eventInfo[0]['subject'] == 'Jardinerie' ? 'jardinerie' : $eventInfo[0]['subject']) . "'>" . ($eventInfo[0]['subject'] == 'animals' ? 'Animaux' : $eventInfo[0]['subject']) . "</p>
        <p class='ticket-report'><a href='https://larche.ovh/new-ticket?obj=reportEvent&id=" . $id . "' alt=''>Signaler l'évènement</a></p>
      </div>";
      if ($eventInfo[0]['id_author'] == $_SESSION['user_id']) {
        echo "<div id='buttonModify' class='modifArticle' onclick='editEvent()'>Modifier l'évènement <img src='./assets/edit.png' alt='crayon modif article'></div>";
      }
      echo "<h1 class='title-event' id='0-event'>" . $eventInfo[0]['title'] . "</h1><input id='titre-event' type='text' class='title-event modify' name='title' style='display: none;'>
      <h2 class='teaser-event' id='1-event'>" . $eventInfo[0]['teaser'] . "</h2><input id='teaser-event' type='text' class='teaser-event modify' name='teaser' style='display: none;'>";

      $date = explode('-', $eventInfo[0]['date_creation']);
      $end = end($date);
      $date[2] = $date[0];
      $date[0] = $end;
      $date_creation = implode(' / ', $date);
      echo "<p class='creator-infos'>Créé le " . $date_creation . " par <a href='https://larche.ovh/user?id=" . $eventInfo[0]['id_author'] . "'>" . $eventInfo[0]['username_author'] . "</a></p>";

      $date = explode('-', $eventInfo[0]['date_event']);
      $end = end($date);
      $date[2] = $date[0];
      $date[0] = $end;
      $date_event = implode(' / ', $date);

      $time = explode(':', $eventInfo[0]['time']);
      array_pop($time);
      $time = implode('h', $time);
      echo "<p class='creator-infos'>Se déroule le " . $date_event . " à " . $time . "</p>
      <div class='event-img-box'><img src='" . $eventInfo[0]['image'] . "' alt='image event'></div>";
      
      $contentEvent = explode('`n', $eventInfo[0]['description']);
      for ($i = 0; $i < count($contentEvent); $i++) {
        echo "<p id='" . $i+2 . "-event'>" . $contentEvent[$i] . "</p><textarea type='text' class='modify textarea-modif' name='content' style='display: none;'></textarea><br>";
      }
      echo "<h3 class='place-event'>Lieu de l'évènement :</h3>
            <p class='place'>" . $eventInfo[0]['place'] . "</p>";

      $checkEvent = $bdd->prepare('SELECT user_id, id_event FROM register_event WHERE user_id = ? AND id_event = ?');
      $checkEvent->execute([$_SESSION['user_id'], $id]);
      $checkEvent = $checkEvent->fetch(PDO::FETCH_ASSOC);

      $checkEventAuthor = $bdd->prepare('SELECT id_event, id_author FROM events WHERE id_event = ? AND id_author = ?');
      $checkEventAuthor->execute([$id, $_SESSION['user_id']]);
      $checkEventAuthor = $checkEventAuthor->fetch(PDO::FETCH_ASSOC);

      $countMembers = $bdd->prepare('SELECT user_id FROM register_event WHERE id_event = ?');
      $countMembers->execute([$id]);
      $countMembers = $countMembers->fetch(PDO::FETCH_ASSOC);
      echo "<button type='button' onclick='validModifEvent(" . $id . ")' class='colorbutton btnValidBtn' style='display: none' id='btnValidBtn'>Valider les modifications</button>";
      echo "<div class='buttons-event'>";
      if (!empty($checkEvent)) {
        echo "<div><form method='post' action='./includes/unsignup-event.php'><input type='text' name='event' value='" . $id . "' hidden><button class='colorbutton danger pointeur'>Se désinscrire</button></form></div>";
      } else {
        if((count($countMembers) == $eventInfo[0]['nb_max']) && !$checkEventAuthor){
          echo "<div><button class='colorbutton danger'>COMPLET</button></div>";
        } else {
          if (!empty($checkEventAuthor)) {
            echo "<div><form method='post' action='./includes/cancel-event.php'><input type='text' name='event' value='" . $id . "' hidden><button class='colorbutton danger pointeur'>Annuler l'évènement</button></form></div>";
          }
          if (empty($checkEvent) && empty($checkEventAuthor)) {
            echo "<div><form method='post' action='./includes/signup-event.php'><input type='text' name='event' value='" . $id . "' hidden><button class='colorbutton pointeur'>S'inscrire</button></form></div>";
          }
        }
      }
      echo "<div><a href='https://larche.ovh/webchat?userToDM=" . $eventInfo[0]['username_author'] . "' class='colorbutton'>Envoyer un message privé au créateur</a></div>";
      echo "</div>";
      ?>