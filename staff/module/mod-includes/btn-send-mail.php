<div class="staff-create-box">
    <button class="btn-popup open-button" onclick="openSendMailPopup()"><strong>Envoyer un mail</strong></button>
</div>
<div class="popup-acc start-50 top-50 translate-middle border-2 border p-3 shadow-sm border-dark-subtle rounded-3 bg-dark-subtle" id="sendMailPopup">
    <form action="https://staff.larche.ovh/includes/sendMail.php" class="form" method="post">
        <div id="chooseUserToMailPopup">
            <h2 class="m-1 text-center fs-3 fw-bold text-primary-emphasis">Envoi d'un mail à :</h2>
            <div class="mb-3">
                <label for="user_id_input" class="form-label">
                    <strong>Par ID</strong>
                </label>
                <input class="form-control email-user-input" type="text" id="user_id_input" placeholder="id" name="user_id">
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">
                    <strong>Nom d'utilisateur</strong>
                </label>
                <input class="form-control email-user-input" type="text" id="username" placeholder="pseudo" name="username">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">
                    <strong>E-mail</strong>
                </label>
                <input class="form-control email-user-input" type="text" id="email" placeholder="Email" name="email">
            </div>
            
            <h3 class="fs-6 text-danger text-center" id="alertEmailInput" style="display:none;">Il faut rentrer au moins une valeur</h3>

            <div class="btns-popup-container d-flex justify-content-between">
                <button type="button" onclick="openMailEditorPopup()" class="btn btn-success m-2" value="Suivant">Suivant</button>
                <button type="submit" class="btn btn-danger cancel m-2" onclick="closeSendMailPopup()">Annuler</button>
            </div>
        </div>

        <div id="mailEditorPopup" class="form" style="display:none;">
            <h2 class="m-1 text-center fs-3 fw-bold text-primary-emphasis">Contenu du mail</h2>
            <input class="form-control mb-1" type="text" placeholder="Titre/Objet" name="emailTitle" required>
            <textarea class="form-control mt-1" name="emailContent" placeholder="Contenu" cols="5" rows="5" required></textarea>
            <div class="btns-popup-container d-flex justify-content-between">
                <input type="text" name="requestType" value="sendMail" hidden>
                <input type="submit" class="btn btn-success m-2" value="Envoyer">
                <button type="submit" class="btn btn-danger cancel m-2" onclick="closeSendMailPopup()">Annuler</button>
        </div>
        </div>
    </form>
</div>
<script>
    function openMailEditorPopup() {
        let inputs = document.querySelectorAll(".email-user-input");
        if (inputs[0].value.length != 0 || inputs[1].value.length != 0 || inputs[2].value.length != 0) {
            document.getElementById("chooseUserToMailPopup").style.display = "none";
            document.getElementById("mailEditorPopup").style.display = "block";
        } else {
            document.getElementById("alertEmailInput").style.display = "block";
        }
    }
    function openSendMailPopup() {
        document.getElementById("sendMailPopup").style.display = "block";
    }
    function closeSendMailPopup() {
        document.getElementById("chooseUserToMailPopup").style.display = "block";
        document.getElementById("mailEditorPopup").style.display = "none";
        document.getElementById("sendMailPopup").style.display = "none";
    }
</script>