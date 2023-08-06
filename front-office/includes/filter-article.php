<?php
session_start();
include('./db-connect.php');
$cat = $_GET['category'];

if (isset($cat)) {
    if (!empty($cat)) {
        $selectArticles = $bdd->prepare('SELECT id_article, picture, `date`, user_id_author, username_author, title, teaser FROM articles WHERE name_category = ?');
        $selectArticles->execute([$cat]);
        $selectArticles = $selectArticles->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($selectArticles)) {
        for ($i = 0; $i < count($selectArticles); $i = $i + 1) {
            foreach ($selectArticles[$i] as $key => $value) {
                if($key === "id_article") echo '<div class="article-box"><a href="https://larche.ovh/article?id=' . $value . '">';
                    if($key === "picture") echo '<div class="container-img"><img src="' . $value .'" alt="image d\'illustration article" class="img-article"></div></a>';
                    if($key === "date"){ 
                        $date = explode('-', $value);
                        $end = end($date);
                        $date[2] = $date[0];
                        $date[0] = $end;
                        $date = implode(' / ', $date);
                        echo '<p class="article-infos"> Le ' . $date . ' par ';
                    }
                    if($key === "user_id_author") echo '<a href="https://larche.ovh/user?id=' . $value . '" class="profil-visit">';
                    if($key === "username_author") echo $value . '</a>.</p>';

                    if($key === "title") echo '<div class="h3-container"><h3>' . $value . '</h3></div>';
                    if($key === "teaser"){
                         echo '<div class="content-box"><p class="text-no-change">' . $value . '</p></div>';
                         $checkLike = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ? AND user_id = ?');
                         $checkLike->execute([$selectArticles[$i]['id_article'], $_SESSION['user_id']]);
                         $checkLike = $checkLike->fetchAll(PDO::FETCH_ASSOC);
     
                         $nbLike = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ?');
                         $nbLike->execute([$selectArticles[$i]['id_article']]);
                         $nbLike = $nbLike->fetchAll(PDO::FETCH_ASSOC);
                         echo '<div class="likes"><p class="likeArticle text-no-change" id="' . $selectArticles[$i]['id_article'] . '">' . count($nbLike) . '</p>';
     
                         if (!empty($checkLike)) {
                             echo '<button class="button-like" onclick="LikeArticle(' . $selectArticles[$i]['id_article'] . ')"><img src="./assets/like.png" alt="likes" id="like' . $selectArticles[$i]['id_article'] . '" class="liked coeur"></button></div></div>';
                         }else{
                             echo '<button class="button-like" onclick="LikeArticle(' . $selectArticles[$i]['id_article'] . ')"><img src="./assets/like.png" alt="likes" id="like' . $selectArticles[$i]['id_article'] . '" class="coeur"></button></div></div>';
                         }
                    }
            }
        }
    }else{
        echo "<div class='no-article'><img src='./assets/hibou.jpg' alt='hibou triste'</img>";
        echo "<p>Il n'y a aucun article disponible dans cette catégorie...</p></div>";
    }
    } else {
        $selectArticles = $bdd->prepare('SELECT id_article, picture, `date`, user_id_author, username_author, title, teaser FROM articles');
        $selectArticles->execute();
        $selectArticles = $selectArticles->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($selectArticles)) {
        for ($i = 0; $i < count($selectArticles); $i = $i + 1) {
            foreach ($selectArticles[$i] as $key => $value) {
                if($key === "id_article") echo '<div class="article-box"><a href="https://larche.ovh/article?id=' . $value . '">';
                    if($key === "picture") echo '<div class="container-img"><img src="' . $value .'" alt="image d\'illustration article" class="img-article"></div></a>';
                    if($key === "date"){ 
                        $date = explode('-', $value);
                        $end = end($date);
                        $date[2] = $date[0];
                        $date[0] = $end;
                        $date = implode(' / ', $date);
                        echo '<p class="article-infos"> Le ' . $date . ' par ';
                    }
                    if($key === "user_id_author") echo '<a href="https://larche.ovh/user?id=' . $value . '" class="profil-visit">';
                    if($key === "username_author") echo $value . '</a>.</p>';

                    if($key === "title") echo '<div class="h3-container"><h3>' . $value . '</h3></div>';
                    if($key === "teaser"){
                         echo '<div class="content-box"><p class="text-no-change">' . $value . '</p></div>';
                         $checkLike = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ? AND user_id = ?');
                         $checkLike->execute([$selectArticles[$i]['id_article'], $_SESSION['user_id']]);
                         $checkLike = $checkLike->fetchAll(PDO::FETCH_ASSOC);
     
                         $nbLike = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ?');
                         $nbLike->execute([$selectArticles[$i]['id_article']]);
                         $nbLike = $nbLike->fetchAll(PDO::FETCH_ASSOC);
                         echo '<div class="likes"><p class="likeArticle text-no-change" id="' . $selectArticles[$i]['id_article'] . '">' . count($nbLike) . '</p>';
     
                         if (!empty($checkLike)) {
                             echo '<button class="button-like" onclick="LikeArticle(' . $selectArticles[$i]['id_article'] . ')"><img src="./assets/like.png" alt="likes" id="like' . $selectArticles[$i]['id_article'] . '" class="liked coeur"></button></div></div>';
                         }else{
                             echo '<button class="button-like" onclick="LikeArticle(' . $selectArticles[$i]['id_article'] . ')"><img src="./assets/like.png" alt="likes" id="like' . $selectArticles[$i]['id_article'] . '" class="coeur"></button></div></div>';
                         }
                    }
            }
        }
    }else{
        echo "<div class='no-article'><img src='./assets/hibou.jpg' alt='hibou triste'</img>";
        echo "<p>Il n'y a aucun article disponible...</p></div>";
    }
    }
} else {
    echo '<p class="danger"> Erreur lors de la tentative de filtrage, veuillez contacter le support. </p>';
}
?>