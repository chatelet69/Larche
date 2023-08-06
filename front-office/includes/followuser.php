<?php
    session_start();
    include('./db-connect.php');

    $id = $_POST['id'];
    $checkabos = $bdd -> prepare('SELECT user_id_subscription FROM abonnement WHERE user_id_follow = ? AND user_id_subscription = ?');
    $checkabos -> execute([$_SESSION['user_id'], $id]);
    $checkabos = $checkabos -> fetch();

    if(!empty($checkabos)){
        $follow = $bdd -> prepare('DELETE FROM abonnement WHERE user_id_follow = ? AND user_id_subscription = ?');
        $follow -> execute([$_SESSION['user_id'], $id]);
        $countfollowers = $bdd -> prepare('SELECT user_id_subscription FROM abonnement WHERE user_id_subscription = ?');
        $countfollowers -> execute([$id]);
        $countfollowers = $countfollowers -> fetchAll(PDO::FETCH_ASSOC);
        echo count($countfollowers);
    }else{
        $username = $bdd -> prepare('SELECT username FROM user WHERE user_id = ?');
        $username -> execute([$id]);
        $username = $username -> fetchAll(PDO::FETCH_ASSOC);

        $follow = $bdd -> prepare('INSERT INTO abonnement (user_id_follow, user_id_subscription) VALUES (?, ?)');
        $follow -> execute([$_SESSION['user_id'], $id]);

        $countfollowers = $bdd -> prepare('SELECT user_id_subscription FROM abonnement WHERE user_id_subscription = ?');
        $countfollowers -> execute([$id]);
        $countfollowers = $countfollowers -> fetchAll(PDO::FETCH_ASSOC);
        echo count($countfollowers);
    }
?>