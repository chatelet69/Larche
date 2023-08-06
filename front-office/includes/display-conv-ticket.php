<?php
if(!isset($bdd)){
    include('./checksessions.php');
    include('./db-connect.php');
}
$id = $_GET['id'];

$ticketinfo = $bdd->prepare('SELECT id_ticket, `subject`, content, user_id_author, `date`, `status`, `file` FROM tickets WHERE id_ticket = ?');
$ticketinfo->execute([$id]);
$ticketinfo = $ticketinfo->fetchALL(PDO::FETCH_ASSOC);

$ticketconv = $bdd->prepare('SELECT answer_id, user_id_author, ticket_id, content, `file`, answer_timestamp FROM tickets_convs WHERE ticket_id = ? ORDER BY answer_timestamp ASC');
$ticketconv->execute([$id]);
$ticketconv = $ticketconv->fetchALL(PDO::FETCH_ASSOC);

$date = explode('-', $ticketinfo[0]['date']);
$end = end($date);
$date[2] = $date[0];
$date[0] = $end;
$date = implode(' / ', $date);

echo "<div class='conv-msg-author'>
                    <div class='container-pfp'><img src='" . $_SESSION['user_pfp'] . "' alt='photo de profil'></div>
                    <div class='conv-msg-content'>
                        <div class='first-msg-ticket'>
                            <p>" . $ticketinfo[0]['content'] . "</p>
                        </div>";

if ($ticketinfo[0]['file'] !== NULL) {
    echo "<div class='img-ticket-container'><a href='" . $ticketinfo[0]['file'] . "' target='_blank'><img src='" . $ticketinfo[0]['file'] . "' alt='Image ticket' class='img-ticket'></a></div>";
}

echo "<p class='date-msg-ticket'>Ticket créé le " . $date . "</p>
                    </div>
                </div>";

for ($i = 0; $i < count($ticketconv); $i++) {
    $id_ticket = $ticketconv[$i]['ticket_id'];
    $id_author = $ticketconv[$i]['user_id_author'];
    foreach ($ticketconv[$i] as $key => $value) {

        if ($key === 'content') {
            echo "<div class='" . ($id_author == $_SESSION['user_id'] ? 'conv-msg-author' : 'conv-msg-respons') . "'>
                        <div class='container-pfp'><img src='" . ($id_author == $_SESSION['user_id'] ? $_SESSION['user_pfp'] : './assets/logo-transparent-vert.png') . "' alt='photo de profil'></div>
                        <div class='conv-msg-content'>
                        <div class='first-msg-ticket'>
                            <p>" . $ticketconv[$i]['content'] . "</p>
                        </div>";
        }
        if ($key === 'file') {
            if ($ticketconv[$i]['file'] !== '' && $ticketconv[$i]['file'] !== NULL) {
                echo "<div class='img-ticket-container'><a href='" . $ticketconv[$i]['file'] . "' target='_blank'><img src='" . $ticketconv[$i]['file'] . "' alt='Image ticket' class='img-ticket'></a></div>";
            }
        }
        if ($key === 'answer_timestamp') {
            echo "<p class='date-msg-ticket'>Envoyé le ";
            $date = explode(' ', $ticketconv[$i]['answer_timestamp']);
            $time = end($date);
            $date = $date[0];

            $date = explode('-', $date);
            $end = end($date);
            $date[2] = $date[0];
            $date[0] = $end;
            $date = implode(' / ', $date);

            $time = explode(':', $time);
            array_pop($time);
            $time = implode('h', $time);
            echo $date . " à " . $time . "</p></div></div>";
        }
    }
}
?>