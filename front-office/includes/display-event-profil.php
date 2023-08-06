<?php
if (!isset($bdd)) {
    session_start();
    include('./db-connect.php');
}
                $event_created = $bdd -> prepare('SELECT events.id_event, events.date_event, events.time, events.image, events.subject, events.title, events.teaser FROM events WHERE events.id_author = ? AND date_event >= CURRENT_DATE ORDER BY date_event');
                $event_created -> execute([$_SESSION['user_id']]);
                $event_created = $event_created -> fetchALL(PDO::FETCH_ASSOC);
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
                            echo "<button class='colorbutton danger pointeur btn-abso' onclick='showPopup(" . $event_id . ")'>Annuler l'évènement</button></div></div>";
                        }
                    }
                }
            ?>