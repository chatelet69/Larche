<div class="staff-create-box mb-2">
    <button class="btn-popup open-button" onclick="openViewTicketPopup()"><strong>Voir un ticket</strong></button>
</div>
<div style="display:none;" class="start-50 top-50 translate-middle border-2 border p-3 shadow-sm border-dark-subtle rounded-3 bg-dark-subtle" id="searchTicketPopup">
        <div id="chooseTicketToGet">
            <h2 class="m-1 text-center fs-3 fw-bold text-primary-emphasis">Accéder à un ticket :</h2>
            <div class="mb-3">
                <label for="ticket_id_input" class="form-label">
                    <strong>Par ID</strong>
                </label>
                <input class="form-control ticket-user-input" type="text" id="user_id_input" placeholder="id" name="user_id">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">
                    <strong>Nom d'utilisateur</strong>
                </label>
                <input class="form-control ticket-user-input" type="text" id="username" placeholder="pseudo" name="username">
            </div>
            
            <h3 class="fs-6 text-danger text-center" id="alertTicketSearch" style="display:none;">Il faut rentrer au moins une valeur</h3>

            <div class="btns-popup-container d-flex justify-content-between">
                <button type="button" onclick="openViewTicketNext()" class="btn btn-success m-2" value="Suivant">Accéder</button>
                <button type="button" class="btn btn-danger cancel m-2" onclick="closeViewTicketPopup()">Annuler</button>
            </div>
        </div>

        <div id="ticketBoxPopup" class="form" style="display:none;">
            <h2 class="m-1 text-center fs-3 fw-bold text-primary-emphasis">Chat du ticket</h2>
            <div id="ticketChat" class="bg-light">
            </div>
            <div id="ticketPopupBtnsContainer" class="btns-popup-container mt-1">
                <div class="d-flex flex-wrap justify-content-around">
                    <div class="w-100 text-center">
                        <input class="form-control" type="text" id="answerToTicket" name="anwserTicket" placeholder="Ecrivez un message">
                    </div>
                    <button type="button" class="btn btn-danger cancel m-2" onclick="closeViewTicketPopup()">Fermer</button>
                    <input type="button" id="sendAnswerBtn" onclick="sendAnswerTicket(document.getElementById('answerToTicket').value)" 
                    class="btn btn-primary m-2" value="Envoyer message">
                </div>
                <form id="resolveTicketForm" class="d-flex justify-content-center">
                    <input type="text" name="requestType" value="resolveTicket" hidden>
                    <input type="button" onclick="deleteTicket(document.getElementById('ticketId').innerHTML)" class="text-center me-2 btn btn-danger" value="Supprimer">
                    <input type="button" onclick="resolveTicket()" class="text-center btn btn-success" value="Marquer comme résolu">
                </form>
            </div>
        </div>
</div>
<script defer src="../../js/ticketMod.js"></script>
