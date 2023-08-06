<?php
  session_start();
  include('./db-connect.php');

  
  /* TITRE */
  if(isset($_POST['titre'])){
    if(!empty($_POST['titre'])){
      $titre = $_POST['titre'];
      $titre = strip_tags($titre);
      $titre = htmlspecialchars($titre);
      if (strlen($titre)>50) {
        echo "Titre trop long.";
        exit;
      }
    }else{
      echo "Veuillez renseigner tous les champs. 1";
      exit;
    }
  }else{
    echo "Erreur lors de l'envoi de l'évènement";
    exit;
  }
// ID
  if (isset($_POST['id'])) {
    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $id = htmlspecialchars($id);
        $id = strip_tags($id);
    }else{
        echo 'id vide';
        exit;
    }
}else{
    echo 'erreur sur le récupération de l\'id';
    exit;
}
/* TEASER */

if(isset($_POST['teaser'])){
  if(!empty($_POST['teaser'])){
      $teaser = $_POST['teaser'];
      $teaser = strip_tags($teaser);
      $teaser = htmlspecialchars($teaser);
      if (strlen($teaser)>100) {
        echo "Teaser trop long.";
        exit;
      }
  }else{
      echo "Veuillez renseigner tous les champs. 2";
      exit;
  }
}else{
  echo "Erreur lors de l'envoi de l'évènement";
  exit;
}

/* CONTENT */
  if(isset($_POST['content'])){
    if(!empty($_POST['content'])){
        $content = $_POST['content'];
        $content = strip_tags($content);
        $content = htmlspecialchars($content);
    }
  }else{
    echo "Erreur lors de l'envoi de l'évènement";
    exit;
  }

  $update = $bdd -> prepare('UPDATE events SET `description` = ?, title = ?, teaser = ? WHERE id_event = ?');
  $update -> execute([$content, $titre, $teaser, $id]);
  echo 'ok';
  exit;
?>