<?php
    include('./db-connect.php');
    session_start();
    $author = $_SESSION['user_id'];

    //id 
    if (isset($_POST['id'])) {
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
            $id = htmlspecialchars($id);
            $id = strip_tags($id);

            if (!is_numeric($id)) {
                echo 'mauvais id';
                exit;
            }
        }else{
            echo 'id vide';
            exit;
        }
    }else{
        echo 'erreur sur la récupération de l\'id';
        exit;
    }
    // Content
    if (isset($_POST['content'])) {
        if (!empty($_POST['content'])) {
            $content = $_POST['content'];
            $content = htmlspecialchars($content);
            $content = strip_tags($content);

            if (strlen($content) > 255) {
                echo 'Commentaire trop long';
                exit;
            }
        }else{
            echo 'Commentaire vide';
            exit;
        }
    }else{
        echo 'Erreur lors de la récupération du commentaire';
        exit;
    }

    $sendComment = $bdd -> prepare('INSERT INTO articles_comment (id_article, id_author, content) VALUES (?, ?, ?)');
    $sendComment -> execute([$id, $author, $content]);
    echo 'ok';
    exit;
?>