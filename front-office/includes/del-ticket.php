<?php
    include("./checksessions.php");
    include("./db-connect.php");

    $id = $_GET['id'];

    $check = $bdd -> prepare('SELECT id_ticket FROM tickets WHERE user_id_author = ? AND id_ticket = ?');
    $check -> execute([$_SESSION['user_id'], $id]);
    $check = $check -> fetchAll(PDO::FETCH_ASSOC);

    if (!empty($check)) {
        $dlTicketConv = $bdd -> prepare('DELETE FROM tickets_convs WHERE ticket_id = ?');
        $dlTicketConv -> execute([$id]);

        $dlTicket = $bdd -> prepare('DELETE FROM tickets WHERE id_ticket = ? AND user_id_author = ?');
        $dlTicket -> execute([$id, $_SESSION['user_id']]);


        $ticket_list = $bdd -> prepare('SELECT id_ticket, `subject`, `date`, `status` FROM tickets WHERE user_id_author = ? ORDER BY id_ticket DESC');
                    $ticket_list -> execute([$_SESSION['user_id']]);
                    $ticket_list = $ticket_list -> fetchALL(PDO::FETCH_ASSOC);

                    if (!empty($ticket_list)) {
                        for ($i=0; $i < count($ticket_list); $i++) { 
                            $id = $ticket_list[$i]['id_ticket'];
                            foreach ($ticket_list[$i] as $key => $value) {
                                if($key === 'id_ticket') echo "<div class='ticket'><p class='ticket-id'>#" . $value . "</p>";
                                if($key === 'subject') echo "<div class='ticket-info'><a href='https://larche.ovh/ticket?id=" . $id . "'><p class='ticket-title'>" . $value . "</p></a>";
                                if($key === 'date'){
                                    $date = explode('-', $value);
                                    $end = end($date);
                                    $date[2] = $date[0];
                                    $date[0] = $end;
                                    $date = implode(' / ', $date);
                                     echo "<p class='ticket-date'>Envoyé le : " . $date . ".</p>";
                                }
                                if($key === 'status'){
                                    if ($value == 0) {
                                        echo "<div class='ticket-status'><p class='waiting'>En attente</p>";
                                        echo "<img src='./assets/timer.png' alt='chrono attente'></div>";
                                    }
                                    if ($value == 1) {
                                        echo "<div class='ticket-status'><p class='resolved'>Résolu</p>";
                                        echo "<img src='./assets/check.png' alt='ticket validé'></div>";
                                    }
                                    echo "<button class='ticket-del' onclick='delTicket(" . $id . ")'><img class='ticket-del-img' src='./assets/delete.png' alt='supprimer le ticket'></button></div></div>";
                                }
                            }
                        }
                    }else{
                        echo "<p>Vous n'avez aucun tickets récents.</p>";
                    }
    }else{
        header('location:https://larche.ovh/support');
        exit;
    }
?>