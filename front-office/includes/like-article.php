<?php
session_start();

include('./db-connect.php');

$checkLike = $bdd->prepare('SELECT id_article, user_id FROM articles_likes WHERE id_article = ? AND user_id = ?');
$checkLike->execute([$_GET['id'], $_SESSION['user_id']]);
$checkLike = $checkLike->fetchAll(PDO::FETCH_ASSOC);
if (!empty($checkLike)) {
    $delLike = $bdd -> prepare('DELETE FROM articles_likes WHERE id_article = ? AND user_id = ?');
    $delLike -> execute([$_GET['id'], $_SESSION['user_id']]);
    $selectArticles = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ?');
        $selectArticles->execute([$_GET['id']]);
        $selectArticles = $selectArticles->fetchAll(PDO::FETCH_ASSOC);
    echo count($selectArticles);
}else{
    $delLike = $bdd -> prepare('INSERT INTO articles_likes (id_article, user_id) VALUES (?, ?)');
    $delLike -> execute([$_GET['id'], $_SESSION['user_id']]);
    $selectArticles = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ?');
        $selectArticles->execute([$_GET['id']]);
        $selectArticles = $selectArticles->fetchAll(PDO::FETCH_ASSOC);
    echo count($selectArticles);
}
?>