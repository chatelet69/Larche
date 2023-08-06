<?php
  include('./checksessions.php');
  include("./logHistoric.php");
  
    $eventinfos = $bdd->prepare('SELECT id_event, title, description, image, date_event FROM events ORDER BY date_event LIMIT 5');
    $eventinfos->execute();
    $eventinfos = $eventinfos->fetchAll(PDO::FETCH_ASSOC);
/* TITRE */
  if(isset($_POST['titre-article'])){
    if(!empty($_POST['titre-article'])){
        $titre = $_POST['titre-article'];
        $titre = strip_tags($titre);
        $titre = htmlspecialchars($titre);
        if (strlen($titre)>50) {
          $msg = "Titre trop long.";
          header('location: https://larche.ovh/createarticle.php?msg=' . $msg);
          exit;
        }
    }else{
        $msg = "Veuillez renseigner tous les champs.";
        header('location: https://larche.ovh/createarticle.php?msg=' . $msg);
        exit;
    }
  }else{
    $msg = "Erreur lors de l'envoi de l'article";
    header('location: https://larche.ovh/createarticle.php?msg=' . $msg);
    exit;
  }
/* TEASER */

if(isset($_POST['teaser'])){
  if(!empty($_POST['teaser'])){
      $teaser = $_POST['teaser'];
      $teaser = strip_tags($teaser);
      $teaser = htmlspecialchars($teaser);
      if (strlen($teaser)>100) {
        $msg = "Teaser trop long.";
        header('location: https://larche.ovh/createarticle.php?msg=' . $msg);
        exit;
      }
  }else{
      $msg = "Veuillez renseigner tous les champs.";
      header('location: https://larche.ovh/createarticle.php?msg=' . $msg);
      exit;
  }
}else{
  $msg = "Erreur lors de l'envoi de l'article";
  header('location: https://larche.ovh/createarticle.php?msg=' . $msg);
  exit;
}

/* CONTENT */
  if(isset($_POST['content-article'])){
    if(!empty($_POST['content-article'])){
        $content = $_POST['content-article'];
        $content = strip_tags($content);
        $content = htmlspecialchars($content);
    }else{
        $msg = "Veuillez renseigner tous les champs.";
        header('location: https://larche.ovh/createarticle.php?msg=' . $msg);
        exit;
    }
  }else{
    $msg = "Erreur lors de l'envoi de l'article";
    header('location: https://larche.ovh/createarticle.php?msg=' . $msg);
    exit;
  }

/* THEME */
  if(isset($_POST['theme-article'])){
    if(!empty($_POST['theme-article'])){
        $theme = $_POST['theme-article'];
        $theme = strip_tags($theme);
        $theme = htmlspecialchars($theme);
        if ($theme == "Animaux") {
            $theme = "animals";
        }elseif ($theme == "Jardinerie") {
            $theme = "Jardinerie";
        }elseif ($theme == "Autre") {
            $theme = "Autre";
        }else{
            $msg = "Erreur sur le choix des thèmes";
            header('location:https://larche.ovh/createarticle?msg=' . $msg);
        }
    }else{
        $msg = "Veuillez renseigner tous les champs.";
        header('location: https://larche.ovh/createarticle?msg=' . $msg);
        exit;
    }
  }else{
    $msg = "Erreur lors de l'envoi de l'article";
    header('location: https://larche.ovh/createarticle?msg=' . $msg);
    exit;
  }
  /* Image */
  if(isset($_FILES['image-article']) && $_FILES['image-article']['error'] != 4){
    if(!empty($_FILES['image-article'])){
        $image = array();
        $image = $_FILES['image-article'];
        
    }else{
        $msg = "Veuillez renseigner tous les champs.";
        header('location: https://larche.ovh/createarticle?msg=' . $msg);
        exit;
    }

    $acceptable = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/jpg'
    ];
   
    if(!in_array($_FILES['image-article']['type'], $acceptable)){
        $msg = 'Le fichier doit être de type jpg, gif ou png';
        header('location: https://larche.ovh/createarticle?msg=' . $msg);
        exit;
        }
        
        $timestamp = time();
        $array = explode('.', $_FILES['image-article']['name']);
        $extension = end($array);

        $maxsize = 2 * 1024 * 1024;

        if($_FILES['image-article']['size'] > $maxsize){
            $msg = "Fichier trop lourd";
            header('location: https://larche.ovh/createarticle?msg=' . $msg);
        }

        $filename =  $_SESSION['user_id'] . '-' . $timestamp . '.' . $extension; 
        // $_FILES['monfichier']['name'] = $filename; ??? pas sur

        $destination= 'https://cdn.larche.ovh/articles_picture/' . $filename;
        $destination2= '/var/www/cdn/articles_picture/' . $filename;
        
       // $_FILES['image-article']['tmp_name'] = $destination; ??? pas sur
        if(!move_uploaded_file($_FILES['image-article']['tmp_name'], $destination2)){ // si le déplacement du fichier renvoi false on renvoi une erreur
            $msg = 'le fichier n\' a pas pu être enregistré ';
            header('location: .https://larche.ovh/createarticle?msg=' . $msg);
        }
        
        $q = 'INSERT INTO articles (content, picture, user_id_author, username_author, name_category, title, teaser) VALUES (:content, :picture, :user_id_author, :username_author, :name_category, :title, :teaser)';
        $req = $bdd->prepare($q);
        $reponse = $req->execute([ 
                'content' => $content,
                'picture' => isset($destination) ? $destination : '',
                'user_id_author' => $_SESSION['user_id'],
                'username_author' => $_SESSION['user'],
                'name_category' => $theme,
                'title' => $titre,
                'teaser' => $teaser
                                ]);

        header('location: https://larche.ovh/profil');
        exit;

  }else{
    $msg = "Erreur lors de l'envoi de l'article";
    header('location: https://larche.ovh/createarticle?msg=' . $msg);
    exit;
  }
  


?>