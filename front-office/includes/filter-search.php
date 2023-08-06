<?php
session_start();
include('./db-connect.php');

$cat = $_GET['cat'];
$rech = $_GET['rech'];
if($cat === 'users'){
$srch_user = $bdd -> prepare('SELECT user_id, pfp, username FROM user WHERE username LIKE ?');
$srch_user -> execute(['%' . $rech . '%']);
$users = $srch_user -> fetchAll(PDO::FETCH_ASSOC);

if (!empty($users)) {

    echo "<div class='research-box'><h3 class='search-title'>Utilisateurs :</h3>";
    
    for ($i=0; $i < count($users) ; $i++) {
    $id = $users[$i]['user_id'];
    foreach ($users[$i] as $key => $value) {
        if($key === 'pfp') echo "<div class='user-item'><div class='rchr-pdp'><img src='" . $value . "' alt='pdp profil'></div>";
        if($key === 'username') echo "<p class='search-content'><a href='https://larche.ovh/user?id=" . $id . "'>" . $value . "</a></p></div>";
    }
    }
    echo "</div>";
    }
    if (empty($users)) {
        echo "<p class='search-content'>Aucun résultat trouvé.</p>";
      }
}elseif ($cat === 'articles') {
$srch_article = $bdd -> prepare('SELECT id_article, picture, title FROM articles WHERE title LIKE ?');
$srch_article -> execute(['%' . $rech . '%']);
$articles = $srch_article -> fetchAll(PDO::FETCH_ASSOC);

if(!empty($articles)){
    echo "<div class='research-box'><h3 class='search-title'>Articles :</h3>";
    for ($i=0; $i < count($articles) ; $i++) {
        $id = $articles[$i]['id_article'];
        foreach ($articles[$i] as $key => $value) {
            if($key === 'picture') echo "<div class='user-item'><div class='rchr-img'><img src='" . $value . "' alt='pdp profil'></div>";
            if($key === 'title') echo "<p class='search-content'><a href='https://larche.ovh/article?id=" . $id . "'>" . $value . "</a></p></div>";
        }
    }
    echo "</div>";
    }
    if (empty($articles)) {
        echo "<p class='search-content'>Aucun résultat trouvé.</p>";
      }
}elseif ($cat === 'events') {
$srch_event = $bdd -> prepare('SELECT id_event, image, title FROM events WHERE title LIKE ?');
$srch_event -> execute(['%' . $rech . '%']);
$events = $srch_event -> fetchAll(PDO::FETCH_ASSOC);

if(!empty($events)){
    echo "<div class='research-box'><h3 class='search-title'>Evènements :</h3>";
    for ($i=0; $i < count($events) ; $i++) {
        $id = $events[$i]['id_event'];
        foreach ($events[$i] as $key => $value) {
            if($key === 'image') echo "<div class='user-item'><div class='rchr-img'><img src='" . $value . "' alt='pdp profil'></div>";
            if($key === 'title') echo "<p class='search-content'><a href='https://larche.ovh/event?id=" . $id . "'>" . $value . "</a></p></div>";
        }
    }
    echo "</div>";
    echo "</div>";
    }
    if (empty($events)) {
        echo "<p class='search-content'>Aucun résultat trouvé.</p>";
      }
}elseif ($cat === "all") {
    $srch_user = $bdd -> prepare('SELECT user.user_id, user.pfp, user.username FROM user WHERE user.username LIKE ?');
    $srch_article = $bdd -> prepare('SELECT articles.id_article, articles.picture, articles.title FROM articles WHERE articles.title LIKE ?');
    $srch_event = $bdd -> prepare('SELECT events.id_event, events.image, events.title FROM events WHERE events.title LIKE ?');
  
    $srch_user -> execute(['%' . $rech . '%']);
    $srch_article -> execute(['%' . $rech . '%']);
    $srch_event -> execute(['%' . $rech . '%']);

    $users = $srch_user -> fetchAll(PDO::FETCH_ASSOC);
    $articles = $srch_article -> fetchAll(PDO::FETCH_ASSOC);
    $events = $srch_event -> fetchAll(PDO::FETCH_ASSOC);

    if (!empty($users)) {

        echo "<div class='research-box'><h3 class='search-title'>Utilisateurs :</h3>";
        
        for ($i=0; $i < count($users) ; $i++) {
        $id = $users[$i]['user_id'];
        foreach ($users[$i] as $key => $value) {
            if($key === 'pfp') echo "<div class='user-item'><div class='rchr-pdp'><img src='" . $value . "' alt='pdp profil'></div>";
            if($key === 'username') echo "<p class='search-content'><a href='https://larche.ovh/user?id=" . $id . "'>" . $value . "</a></p></div>";
        }
        }
        echo "</div>";
        }
        if(!empty($articles)){
        echo "<div class='research-box'><h3 class='search-title'>Articles :</h3>";
        for ($i=0; $i < count($articles) ; $i++) {
            $id = $articles[$i]['id_article'];
            foreach ($articles[$i] as $key => $value) {
                if($key === 'picture') echo "<div class='user-item'><div class='rchr-img'><img src='" . $value . "' alt='pdp profil'></div>";
                if($key === 'title') echo "<p class='search-content'><a href='https://larche.ovh/article?id=" . $id . "'>" . $value . "</a></p></div>";
            }
        }
        echo "</div>";
        }
        if(!empty($events)){
        echo "<div class='research-box'><h3 class='search-title'>Evènements :</h3>";
        for ($i=0; $i < count($events) ; $i++) {
            $id = $events[$i]['id_event'];
            foreach ($events[$i] as $key => $value) {
                if($key === 'image') echo "<div class='user-item'><div class='rchr-img'><img src='" . $value . "' alt='pdp profil'></div>";
                if($key === 'title') echo "<p class='search-content'><a href='https://larche.ovh/event?id=" . $id . "'>" . $value . "</a></p></div>";
            }
        }
        echo "</div>";
        echo "</div>";
        }
        if (empty($events) && empty($articles) && empty($users)) {
            echo "<p class='search-content'>Aucun résultat trouvé.</p>";
          }
}else{
    echo "Erreur, veuillez rafraichir la page.";
}
?>