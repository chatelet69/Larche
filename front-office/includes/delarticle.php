<?php
include('./checksessions.php');
include("./logHistoric.php");

if(isset($_POST['id-article'])){
    if (!empty($_POST['id-article'])) {
        $article = $_POST['id-article'];
        $article = htmlspecialchars($article);
        $article = strip_tags($article);

        $delarticle = $bdd->prepare('DELETE FROM articles WHERE id_article = ? AND user_id_author = ?');
        $delarticle -> execute([$_POST['id-article'], $_SESSION['user_id']]);
        header('location: https://larche.ovh/profil');
        exit;
    }else{
        header('location: https://larche.ovh/profil');
        exit;
    }
}else{
    header('location: https://larche.ovh/profil');
    exit;
}
?>