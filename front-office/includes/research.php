<?php
include('./db-connect.php');
    if(isset($_GET['recherche'])){
        $search = $_GET['recherche'];
    
    $srch_user = $bdd -> prepare('SELECT user.user_id, user.pfp, user.username FROM user WHERE user.username LIKE ? ORDER BY user.username LIMIT 3');
    $srch_article = $bdd -> prepare('SELECT articles.id_article, articles.picture, articles.title FROM articles WHERE articles.title LIKE ? ORDER BY articles.title LIMIT 3');
    $srch_event = $bdd -> prepare('SELECT events.id_event, events.image, events.title FROM events WHERE events.title LIKE ? ORDER BY events.title LIMIT 3');

    $srch_user -> execute(['%' . $search . '%']);
    $srch_article -> execute(['%' . $search . '%']);
    $srch_event -> execute(['%' . $search . '%']);

    $users = $srch_user -> fetchAll(PDO::FETCH_ASSOC);
    $articles = $srch_article -> fetchAll(PDO::FETCH_ASSOC);
    $events = $srch_event -> fetchAll(PDO::FETCH_ASSOC);
if (!empty($users)) {

    echo "<div class='research-box'><h3 class='search-title'>USER :</h3>";
    
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
    echo "<div class='research-box'><h3 class='search-title'>ARTICLES :</h3>";
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
if(!empty($events) || !empty($articles) || !empty($users)){
    echo '<a href="https://larche.ovh/search?q=' . $search . '"><div class="schr-page">Rechercher tout pour "' . $search . '".</div></a>';
}
if(empty($events) && empty($articles) && empty($users)){
    echo '<p>Aucun résultat trouvé.</p>';
}
}else{
    echo "erreur";
}
?>