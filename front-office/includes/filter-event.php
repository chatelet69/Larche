<?php
include('./db-connect.php');
session_start();
$type = $_GET['type'];

if($type === 'create'){
    $event_created = $bdd -> prepare('SELECT events.id_event, events.date_event, events.time, events.image, events.subject, events.title, events.teaser FROM events WHERE events.id_author = ? AND date_event >= CURRENT_DATE ORDER BY date_event');
    $event_created -> execute([$_SESSION['user_id']]);
    $event_created = $event_created -> fetchALL(PDO::FETCH_ASSOC);
    if(!empty($event_created)){
        for ($i=0; $i < count($event_created); $i++) { 
            $event_id = $event_created[$i]['id_event'];
            $date = $event_created[$i]['date_event'];
            $time = $event_created[$i]['time'];

            $date = explode('-', $date);
            $end = end($date);
            $date[2] = $date[0];
            $date[0] = $end;
            $date_event = implode(' / ', $date);

            $time = explode(':', $time);
            array_pop($time);
            $time = implode('h', $time);
            foreach ($event_created[$i] as $key => $value) {
                if($key === "id_event") echo "<a href='https://larche.ovh/event?id=" . $value . "' class='infos-events-profil'>";
                
                if($key === "image") echo "<div class='articles'><img src='" . $value . "' class='article-picture'>";
                if($key === "subject") 
                echo "<div class='title-article'><p class='" . ($value == 'Jardinerie' ? 'jardinerie' : $value) . "'>" . ($value == 'animals' ? 'Animaux' : $value) . "</p>";
                if($key === "title") echo "<p class='date-event-profile'>Le $date_event à $time.</p><p class='article-titre'>" . $value . "</p>";
                if($key === "teaser"){ 
                    echo "<p class='event-teaser'>" . $value . "</p></a>";
                    echo "<div><form method='post' action='./includes/cancel-event.php'><input type='text' name='event' value='" . $event_id . "' hidden><button class='colorbutton danger pointeur btn-abso'>Annuler l'évènement</button></form></div></div></div>";
                }
            }
        }
}else{
    echo "<p style='margin-top:1vw'>Vous avez créé aucun évènement.</p>";
}
}
if ($type === 'register') {
    $event_created = $bdd -> prepare('SELECT events.id_event, events.date_event, events.time, events.image, events.subject, events.title, events.teaser FROM events, register_event WHERE register_event.user_id = ? AND register_event.id_event = events.id_event AND date_event >= CURRENT_DATE ORDER BY date_event');
    $event_created -> execute([$_SESSION['user_id']]);
    $event_created = $event_created -> fetchALL(PDO::FETCH_ASSOC);
if(!empty($event_created)){
    for ($i=0; $i < count($event_created); $i++) { 
        $event_id = $event_created[$i]['id_event'];
        $date = $event_created[$i]['date_event'];
        $time = $event_created[$i]['time'];

        $date = explode('-', $date);
        $end = end($date);
        $date[2] = $date[0];
        $date[0] = $end;
        $date_event = implode(' / ', $date);

        $time = explode(':', $time);
        array_pop($time);
        $time = implode('h', $time);
        foreach ($event_created[$i] as $key => $value) {
            if($key === "id_event") echo "<a href='https://larche.ovh/event?id=" . $value . "' class='infos-events-profil'>";
            
            if($key === "image") echo "<div class='articles'><img src='" . $value . "' class='article-picture'>";
            if($key === "subject") 
            echo "<div class='title-article'><p class='" . ($value == 'Jardinerie' ? 'jardinerie' : $value) . "'>" . ($value == 'animals' ? 'Animaux' : $value) . "</p>";
            if($key === "title") echo "<p class='date-event-profile'>Le $date_event à $time.</p><p class='article-titre'>" . $value . "</p>";
            if($key === "teaser"){ 
                echo "<p class='event-teaser'>" . $value . "</p></a>";
                echo "<div><form method='post' action='./includes/cancel-event.php'><input type='text' name='event' value='" . $event_id . "' hidden><button class='colorbutton danger pointeur btn-abso'>Annuler l'évènement</button></form></div></div></div>";
            }
        }
    }
}else{
    echo "<p style='margin-top:1vw'>Vous êtes inscrit à aucun évènement.</p>";
}
}
if ($type != 'register' && $type != 'create') {
    echo "Erreur";
}
?>