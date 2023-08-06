<?php
if(!isset($bdd)){
    include('./checksessions.php');
    include('./db-connect.php');
}
$event = $bdd->prepare("SELECT events.id_event, events.date_event, events.time, user.user_id, user.pfp, user.username, user.name, user.lastname, user.status, events.subject, events.title, events.image, events.teaser, events.place, events.nb_max FROM events, user WHERE date_event >= CURRENT_DATE AND events.id_author = user.user_id ORDER BY date_event");
        $event->execute();
        $list_event = array();
        $i = 0;

        while ($row = $event->fetch(PDO::FETCH_ASSOC)) {
            $list_event[$i] = $row;
            $i++;
        }

        for ($i = 0; $i < count($list_event); $i++) {
            $event_id = $list_event[$i]['id_event'];
            $id = $list_event[$i]['user_id'];
            foreach ($list_event[$i] as $key => $value) {
                if ($key === "id_event") echo "<div class='event-item'><div class='evenement'>";
                if ($key === "date_event"){ 
                    $date = explode('-', $value);
                    $end = end($date);
                    $date[2] = $date[0];
                    $date[0] = $end;
                    $date = implode(' / ', $date);
                    echo "<div class='event_info'><p class='date_event'>" . $date . "</p>";
                }
                if ($key === "time"){ 
                    $date = explode(':', $value);
                    array_pop($date);
                    $time = implode('h', $date);
                    echo "<p class='date_event'>" . $time . "</p>";
                }
                if ($key === "pfp") echo "<img src='" . $value . "' alt='PDP créateur event'>";
                if ($key === "username") echo "<p class='event-creator'><a href='https://larche.ovh/user?id=" . $id . "' class='profil-visit'>" . $value . "</a></p>";
                if ($key === "name") echo "<p class='event-names'>" . $value . " ";
                if ($key === "lastname") echo $value . "</p>";
                if ($key === "status") {
                    switch ($value) {
                        case 2:
                            echo '<p class="badge-contri">Contributeur</p>';
                            break;
                        case 3:
                            echo '<p class="badge-mod">Modérateur</p></div>';
                            break;
                        case 4:
                            echo '<p class="badge-admin">Administrateur</p>';
                            break;
                        default:
                            echo '';
                            break;
                    }
                }
                if ($key === "subject") echo "<p class='" . ($value == 'Jardinerie' ? 'jardinerie' : $value) . "'>" . ($value == 'animals' ? 'Animaux' : ($value == 'autre' ? 'Autre' : $value)) . "</p>";
                if ($key === "title") echo "<p class='event-title'>" . $value . "</p></div>";
                if ($key === "image") {
                    echo "<div class='event_image'><a href='https://larche.ovh/event?id=" . $event_id . "'><img src='" . $value . "' class='img_event' alt='illustration event'></a>";
                    echo "<button type='button' onclick='infoEvent($event_id)'><img src='./assets/infos.png' class='info-event' class='pointeur'></button>";
                    $checkEvent = $bdd->prepare('SELECT user_id, id_event FROM register_event WHERE user_id = ? AND id_event = ?');
                    $checkEvent->execute([$_SESSION['user_id'], $event_id]);
                    $checkEvent = $checkEvent->fetch(PDO::FETCH_ASSOC);
                    
                    $checkEventAuthor = $bdd->prepare('SELECT id_event, id_author FROM events WHERE id_event = ? AND id_author = ?');
                    $checkEventAuthor->execute([$event_id, $_SESSION['user_id']]);
                    $checkEventAuthor = $checkEventAuthor->fetch(PDO::FETCH_ASSOC);

                    $countMembers = $bdd -> prepare('SELECT COUNT(user_id) AS nb_participants FROM register_event WHERE id_event = ?');
                    $countMembers -> execute([$event_id]);
                    $countMembers = $countMembers -> fetch(PDO::FETCH_ASSOC);

                    if (!empty($checkEvent)) {
                        echo "<form method='post' action='./includes/unsignup-event.php'><input type='text' name='event' value='" . $event_id . "'><button class='colorbutton choiceEvent danger'>Se désinscrire</button></form>";
                    }else{
                    if((count($countMembers) == $list_event[$i]['nb_max']) && !$checkEventAuthor){
                        echo "<form method='post' action='./includes/unsignup-event.php'><input type='text' name='event' value='" . $event_id . "'><button class='colorbutton choiceEvent danger'>COMPLET</button></form>";
                    }else{
                    if (!empty($checkEventAuthor)) {
                        echo "<button type='button' class='colorbutton choiceEvent danger' onclick='showPopup(" . $event_id . ")' >Annuler l'évènement</button>";
                    }
                    if (empty($checkEvent) && empty($checkEventAuthor)) {
                        echo "<form method='post' action='./includes/signup-event.php'><input type='text' name='event' value='" . $event_id . "'><button class='colorbutton choiceEvent'>S'inscrire</button></form>";
                    }
                }
            }
                    echo "</div>";
                }
                if ($key === "teaser") echo "</div><div class='event_details' style='display: none' id='$event_id'> <h3>Teaser de l'évènement :</h3><p>" . $value . "</p>";
                if ($key === "place") echo "<h3>Lieu de l'évènement :</h3><p class='place'>" . $value . "</p>";
                if ($key === "nb_max"){
                    $countMembers = $bdd -> prepare('SELECT COUNT(user_id) AS nb_participants FROM register_event WHERE id_event = ?');
                    $countMembers -> execute([$event_id]);
                    $countMembers = $countMembers -> fetch(PDO::FETCH_ASSOC);
                    echo "<h3>Nombre de participants :</h3><p>" . $countMembers['nb_participants'] . ' / ' . $value . "</p></div></div></div>";
                }
            }
        }
?>