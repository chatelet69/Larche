<?php
    include('checksessions.php');
    include('db-connect.php');
    $user_id = $_SESSION['user_id'];


    $q = 'SELECT username, email, name, lastname, ip, city, age, date_created FROM user WHERE user_id=?';
    $sql = $bdd->prepare($q);
    $sql->execute([$user_id]);

    $sql = $sql->fetch(PDO::FETCH_ASSOC); 

    $q = 'SELECT user_id_subscription, date_follow FROM abonnement WHERE user_id_follow=?';
    $sql2 = $bdd->prepare($q);
    $sql2->execute([$user_id]);
    $sql2 = $sql2->fetchAll(PDO::FETCH_ASSOC); 

    $q = 'SELECT title, date, name_category FROM articles WHERE user_id_author=?';
    $sql3 = $bdd->prepare($q);
    $sql3->execute([$user_id]);

    $sql3 = $sql3->fetchAll(PDO::FETCH_ASSOC); 

    $q = 'SELECT COUNT(title) FROM articles WHERE user_id_author=?';
    $sql32 = $bdd->prepare($q);
    $sql32->execute([$user_id]);
    $sql32 = $sql32->fetch(PDO::FETCH_ASSOC); 

    $q = 'SELECT content, id_article, date FROM articles_comment WHERE id_author=?';
    $sql4 = $bdd->prepare($q);
    $sql4->execute([$user_id]);

    $sql4 = $sql4->fetchAll(PDO::FETCH_ASSOC); 

    $q = 'SELECT COUNT(id_article) FROM articles_likes WHERE user_id=?';
    $sql5 = $bdd->prepare($q);
    $sql5->execute([$user_id]);

    $sql5 = $sql5->fetch(PDO::FETCH_ASSOC); 

    $q = 'SELECT content, date_time FROM dm WHERE user_id_sender=?';
    $sql6 = $bdd->prepare($q);
    $sql6->execute([$user_id]);

    $sql6 = $sql6->fetchAll(PDO::FETCH_ASSOC); 

    $q = 'SELECT title, date_creation, date_event, subject FROM events WHERE id_author=?';
    $sql7 = $bdd->prepare($q);
    $sql7->execute([$user_id]);

    $sql7 = $sql7->fetchAll(PDO::FETCH_ASSOC); 

    $q = 'SELECT content, date FROM messages WHERE user_id_author=?';
    $sql8 = $bdd->prepare($q);
    $sql8->execute([$user_id]);

    $sql8 = $sql8->fetchAll(PDO::FETCH_ASSOC); 

    $q = 'SELECT subject, content FROM tickets WHERE user_id_author=?';
    $sql9 = $bdd->prepare($q);
    $sql9->execute([$user_id]);

    $sql9 = $sql9->fetchAll(PDO::FETCH_ASSOC); 

    $username = $sql['username'];
    $email = $sql['email'];
    $name = $sql['name'];
    $lastname = $sql['lastname'];
    $city = $sql['city'];
    $ip = $sql['ip'];
    $age = $sql['age'];
    $date_created = $sql['date_created'];
    

    echo 'Pseudo : ' . $username . ',';
    echo ' Email : ' . $email . ',';
    echo ' Nom : ' . $lastname . ',';
    echo ' Prénom : ' . $name . ',';
    echo ' IP : ' . $ip . ',';
    echo ' Ville : ' . $city . ',';
    echo ' Date de naissance : ' . $age . ',';
    echo ' Date de création de compte: ' . $date_created . '.';

    foreach($sql2 as $row){
        $user_id_subscription = $row['user_id_subscription'];
        $date_follow = $row['date_follow'];
        echo "Abonné à l'utilisateur $user_id_subscription depuis le $date_follow ;";
    }
    
    foreach($sql3 as $row){
        $title = $row['title'];
        $date = $row['date'];
        $name_category = $row['name_category'];
        echo "Titre de l'article : $title, Date de publication : $date, Catégorie : $name_category ;";
    }
    
    foreach($sql4 as $row){
        $content = $row['content'];
        $id_article = $row['id_article'];
        $date = $row['date'];
        echo "Contenu de l'article $id_article : $content, Date de publication : $date ;";
    }
    
    foreach($sql6 as $row){
        $content = $row['content'];
        $date_time = $row['date_time'];
        echo "Contenu du message : $content, Date d'envoi : $date_time ;";
    }
    
    foreach($sql7 as $row){
        $title = $row['title'];
        $date_creation = $row['date_creation'];
        $date_event = $row['date_event'];
        $subject = $row['subject'];
        echo "Titre de l'événement : $title, Date de création : $date_creation, Date de l'événement : $date_event, Sujet : $subject ;";
    }
    
    foreach($sql8 as $row){
        $content = $row['content'];
        $date = $row['date'];
        echo "Contenu du message : $content, Date de publication : $date ;";
    }
    
    foreach($sql9 as $row){
        $subject = $row['subject'];
        $content = $row['content'];
        echo "Sujet du ticket : $subject, Contenu du ticket : $content ;";
    }
?>