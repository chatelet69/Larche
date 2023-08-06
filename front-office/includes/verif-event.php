<?php
session_start();
  include("./logHistoric.php");
  $eventinfos = $bdd->prepare('SELECT id_event, title, description, image, date_event FROM events ORDER BY date_event LIMIT 5');
  $eventinfos->execute();
  $eventinfos = $eventinfos->fetchAll(PDO::FETCH_ASSOC);

/* TITRE */
if (isset($_POST['titre-event'])) {
  if (!empty($_POST['titre-event'])) {
    $titre = $_POST['titre-event'];
    $titre = strip_tags($titre);
    $titre = htmlspecialchars($titre);
    if(strlen($titre)>50){
      $msg = "Titre trop long.";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
    }
  } else {
    $msg = "Veuillez renseigner tous les champs.";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
  }
} else {
  $msg = "Erreur lors de l'envoi de l'event 1";
  header('location: https://larche.ovh/eventcreation?msg=' . $msg);
  exit;
}
/* TEASER */

if (isset($_POST['teaser-event'])) {
  if (!empty($_POST['teaser-event'])) {
    $teaser = $_POST['teaser-event'];
    $teaser = strip_tags($teaser);
    $teaser = htmlspecialchars($teaser);
    if(strlen($teaser)>50){
      $msg = "Teaser trop long.";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
    }
  } else {
    $msg = "Veuillez renseigner tous les champs.";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
  }
} else {
  $msg = "Erreur lors de l'envoi de l'event 1";
  header('location: https://larche.ovh/eventcreation?msg=' . $msg);
  exit;
}

/* DESCRIPTION */
if (isset($_POST['content-event'])) {
  if (!empty($_POST['content-event'])) {
    $content = $_POST['content-event'];
    $content = strip_tags($content);
    $content = htmlspecialchars($content);
  } else {
    $msg = "Veuillez renseigner tous les champs.";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
  }
} else {
  $msg = "Erreur lors de l'envoi de l'event 2";
  header('location: https://larche.ovh/eventcreation?msg=' . $msg);
  exit;
}

/* THEME */
if (isset($_POST['theme-event'])) {
  if (!empty($_POST['theme-event'])) {
    $theme = $_POST['theme-event'];
    $theme = strip_tags($theme);
    $theme = htmlspecialchars($theme);
    if ($theme == "Animaux") {
      $theme = "animals";
    } elseif ($theme == "Jardinerie") {
      $theme = "Jardinerie";
    } elseif ($theme == "Autre") {
      $theme = "Autre";
    } else {
      $msg = "Erreur sur le choix des thèmes";
      header('location:https://larche.ovh/eventcreation?msg=' . $msg);
    }
  } else {
    $msg = "Veuillez renseigner tous les champs.";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
  }
} else {
  $msg = "Erreur lors de l'envoi de l'event 3";
  header('location: https://larche.ovh/eventcreation?msg=' . $msg);
  exit;
}

/* LIEU */

if (isset($_POST['lieu-event'])) {
  if (!empty($_POST['lieu-event'])) {
    $lieu = $_POST['lieu-event'];
    $lieu = strip_tags($lieu);
    $lieu = htmlspecialchars($lieu);
    if(strlen($lieu)>50){
      $msg = "Lieu trop long.";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
    }
  } else {
    $msg = "Veuillez remplir tous les champs";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
  }
} else {
  $msg = "Erreur lors de l'envoi de l'event 4";
  header('location: https://larche.ovh/eventcreation?msg=' . $msg);
  exit;
}

/* DATE */

if (isset($_POST['date-event'])) {
  if (!empty($_POST['date-event'])) {
    $date_part = explode("-", $_POST['date-event']);
    $year = $date_part[0];
    $month = $date_part[1];
    $day = $date_part[2];

    if (checkdate($month, $day, $year)) {
      date_default_timezone_set('UTC');
      $date1 = strtotime($_POST['date-event']);
      $date2 = strtotime(date("Y-m-d"));
      if ($date1 > $date2) {
        $date_event = $_POST['date-event'];
      } else {
        $msg = "Veuillez avoir au minimum un jour d'avance";
        header('location: https://larche.ovh/eventcreation?msg=' . $msg);
        exit;
      }
    } else {
      $msg = "Merci de rentrer une date valide";
      header('location: https://larche.ovh/eventcreation?msg=' . $msg);
      exit;
    }
  } else {
    $msg = "Veuillez remplir tous les champs";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
  }
} else {
  $msg = "Erreur lors de l'envoi de l'event 5";
  header('location: https://larche.ovh/eventcreation?msg=' . $msg);
  exit;
}
/* TIME */

if (isset($_POST['time-event'])) {
  if (!empty($_POST['time-event'])) {
    $time_event = $_POST['time-event'];
    $time_evenement = strtotime($time_event);
    $time_today = strtotime(date("H:i:s"));
    if ($time_evenement < $time_today) {
      if ($date1 <= $date2) {
        $msg = "Merci de rentrer une date valide";
        header('location: https://larche.ovh/eventcreation?msg=' . $msg);
        exit;
      }
    }
  } else {
    $msg = "Veuillez remplir tous les champs";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
  }
} else {
  $msg = "Erreur lors de l'envoi de l'event";
  header('location: https://larche.ovh/eventcreation?msg=' . $msg);
  exit;
}

/* Image */
if (isset($_FILES['image-event']) && $_FILES['image-event']['error'] != 4) {
  if (!empty($_FILES['image-event'])) {
    $image = array();
    $image = $_FILES['image-event'];
  } else {
    $msg = "Veuillez renseigner tous les champs.";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
  }

  $acceptable = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/jpg'
  ];

  if (!in_array($_FILES['image-event']['type'], $acceptable)) {
    $msg = 'Le fichier doit être de type jpg, gif ou png';
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit;
  }

  $timestamp = time();
  $array = explode('.', $_FILES['image-event']['name']);
  $extension = end($array);

  $maxsize = 2 * 1024 * 1024;

  if ($_FILES['image-event']['size'] > $maxsize) {
    $msg = "Fichier trop lourd";
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit();
  }

  $filename =  $_SESSION['user_id'] . '-' . $timestamp . '.' . $extension;
  // $_FILES['monfichier']['name'] = $filename; ??? pas sur

  $destination = 'https://cdn.larche.ovh/events_picture/' . $filename;
  $destination2 = '/var/www/cdn/events_picture/' . $filename;

  // $_FILES['image-event']['tmp_name'] = $destination; ??? pas sur
  if (!move_uploaded_file($_FILES['image-event']['tmp_name'], $destination2)) { // si le déplacement du fichier renvoi false on renvoi une erreur
    $msg = 'le fichier n\' a pas pu être enregistré ';
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit();
  }

  // Nombre de participants maximum
  $maxpers = $_POST['nb_max_event'];
  if (isset($maxpers)) {
    if (!empty($maxpers)) {
      $maxpers = htmlspecialchars($maxpers);
      $maxpers = strip_tags($maxpers);
      if ($maxpers <= 0) {
        $msg = 'Veuillez définir un nombre de participants positif.';
        header('location: https://larche.ovh/eventcreation?msg=' . $msg);
        exit();
      }
    } else {
      $msg = 'Veuillez renseigner tous les champs.';
      header('location: https://larche.ovh/eventcreation?msg=' . $msg);
      exit();
    }
  } else {
    $msg = 'Erreur lors de l\'envoi de l\'event';
    header('location: https://larche.ovh/eventcreation?msg=' . $msg);
    exit();
  }

  $q = 'INSERT INTO events (title, `description`, `image`, date_event, place, nb_max, `subject`, id_author, username_author, email_author, `time`, teaser) VALUES (:title, :description, :image, :date_event, :place, :nb_max, :subject, :id_author, :username_author, :email_author, :time, :teaser)';
  $req = $bdd->prepare($q);
  $reponse = $req->execute([
    'title' => $titre,
    'description' => $content,
    'image' => isset($destination) ? $destination : '',
    'date_event' => $date_event,
    'place' => $lieu,
    'nb_max' => $maxpers,
    'subject' => $theme,
    'id_author' => $_SESSION['user_id'],
    'username_author' => $_SESSION['user'],
    'email_author' => $_SESSION['email'],
    'time' => $time_event,
    'teaser' => $teaser
  ]);

  header('location: https://larche.ovh/events');
  exit;
} else {
  $msg = "Erreur lors de l'envoi de l'event 6";
  header('location: https://larche.ovh/eventcreation?msg=' . $msg);
  exit;
}
?>
