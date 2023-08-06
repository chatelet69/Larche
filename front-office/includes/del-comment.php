<?php
    include('./db-connect.php');
    if (isset($_POST['id'])) {
        if (!empty($_POST['id'])) {
            $id = $_POST['id'];
            $id = htmlspecialchars($id);
            $id = strip_tags($id);
            if (!is_numeric($id)) {
                echo 'id non numérique';
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

    $delComment = $bdd -> prepare('DELETE FROM articles_comment WHERE id_comment = ?');
    $delComment -> execute([$id]);
    echo 'ok';
    exit;
?>