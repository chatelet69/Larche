<?php
    if (!isset($bdd)) {
        include('./db-connect.php');
        session_start();
    }
    if (!isset($id)) {
        $id = $_GET['id'];
    }
    $comment = $bdd -> prepare('SELECT  id_author, `date`, content, id_comment, id_article FROM articles_comment WHERE id_article = ? ORDER BY `date` DESC'); // FAIRE UN ORDER BY PAR NOMBRE DE LIKE (select dans un select)
    $comment -> execute([$id]);
    $comment = $comment -> fetchALL(PDO::FETCH_ASSOC);

    for ($i=0; $i < count($comment) ; $i++) {
        $author_comment = $bdd -> prepare('SELECT user_id, username, name, lastname, pfp FROM user WHERE user_id = ?');
        $author_comment -> execute([$comment[$i]['id_author']]);
        $author_comment = $author_comment -> fetch(PDO::FETCH_ASSOC);

        $checkLike = $bdd->prepare('SELECT id_comment, id_liker FROM comments_liked WHERE id_comment = ? AND id_liker = ?');
        $checkLike->execute([$comment[$i]['id_comment'], $_SESSION['user_id']]);
        $checkLike = $checkLike->fetchAll(PDO::FETCH_ASSOC);

        $countLike = $bdd->prepare('SELECT id_comment FROM comments_liked WHERE id_comment = ?');
        $countLike->execute([$comment[$i]['id_comment']]);
        $countLike = $countLike->fetchAll(PDO::FETCH_ASSOC);

        $date = explode('-', $comment[$i]['date']);
        $end = end($date);
        $date[2] = $date[0];
        $date[0] = $end;
        $date = implode(' / ', $date);

        echo "<div class='comment-item'>";
        foreach ($comment[$i] as $key => $value) {
            if($key === 'id_author') echo "<div class='author-comment-ticket'><div class='img-ticket-container'><a href='https://larche.ovh/user?id=" . $value . "'><img src='" . $author_comment['pfp'] . "' alt='photo de profil'></a></div>";
            if($key === 'date') echo "<div class='comment-author-info'><p class='author-comment'><a href='https://larche.ovh/user?id=" . $author_comment['user_id'] . "'>" . $author_comment['username'] . "</a></p><p class='date-comment'>Le " . $date . "</p></div></div>";
            if($key === 'content') echo "<p class='content-comment'>" . $value . "</p>";
            if($key === 'id_comment'){ echo "<div class='option-comment'><div class='like-comment' onclick='likeComment(" . $comment[$i]['id_comment'] . ")'><p id='nb-like-comment-" . $comment[$i]['id_comment'] . "'>" . count($countLike) ."</p><img src='../assets/like.png' alt='coeur like' class='" . (!empty($checkLike) ? 'liked' : '') . "' id='like-comment-" . $comment[$i]['id_comment'] . "'></div>";
                if($_SESSION['user_id'] !== $comment[$i]['id_author']){
                echo "<p class='report-comment'><a href='https://larche.ovh/new-ticket?obj=reportComment&id=" . $comment[$i]['id_comment'] . "'>Signaler le commentaire</a></p></div>";
            }else{
                echo "<p class='del-comment' onclick='delComment(" . $comment[$i]['id_article'] . ", " . $comment[$i]['id_comment'] . ")'>Supprimer le commentaire</p></div>";
            }
        }
        }
        echo "</div>";
    }
?>