<?php
session_start();

if (isset($_POST['id'])) {
    if (!empty($_POST['id'])) {
        $id = $_POST['id'];
        $id = htmlspecialchars($id);
        $id = strip_tags($id);

        if (!is_numeric($id)) {
            echo 'id non numérique.';
            exit;
        }
    }else{
        echo "id vide";
        exit;
    }
}else{
    echo 'erreur sur la récupération de l\'id';
    exit;
}

include('./db-connect.php');

$checkLike = $bdd->prepare('SELECT id_comment, id_liker FROM comments_liked WHERE id_comment = ? AND id_liker = ?');
$checkLike->execute([$id, $_SESSION['user_id']]);
$checkLike = $checkLike->fetchAll(PDO::FETCH_ASSOC);
if (!empty($checkLike)) {
    $delLike = $bdd -> prepare('DELETE FROM comments_liked WHERE id_comment = ? AND id_liker = ?');
    $delLike -> execute([$id, $_SESSION['user_id']]);
    $selectArticles = $bdd->prepare('SELECT id_comment FROM comments_liked WHERE id_comment = ?');
        $selectArticles->execute([$id]);
        $selectArticles = $selectArticles->fetchAll(PDO::FETCH_ASSOC);
    echo count($selectArticles);
}else{
    $delLike = $bdd -> prepare('INSERT INTO comments_liked (id_comment, id_liker) VALUES (?, ?)');
    $delLike -> execute([$id, $_SESSION['user_id']]);
    $selectArticles = $bdd->prepare('SELECT id_comment FROM comments_liked WHERE id_comment = ?');
        $selectArticles->execute([$id]);
        $selectArticles = $selectArticles->fetchAll(PDO::FETCH_ASSOC);
    echo count($selectArticles);
}
?>