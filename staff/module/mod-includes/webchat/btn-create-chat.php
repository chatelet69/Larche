<div class="staff-create-box member-mod-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('createChatPopup')"><strong>Créer un webchat</strong></button>
</div>
<div class="popup-acc start-50 top-50 popup translate-middle border-2 border p-3 shadow-sm border-dark-subtle rounded-3 bg-dark-subtle" id="createChatPopup">
    <form action="https://staff.larche.ovh/includes/staff-options.php" class="form" method="post">
        <h2 class="m-1 text-center fs-3 fw-bold text-primary-emphasis">Création d'un chat</h2>
        <div class="mb-3">
            <label for="chatNameToCreate" class="form-label">
                <strong>Nom du chat</strong>
            </label>
            <input max="40" class="form-control" type="text" id="chatNameToCreate" placeholder="Nom (25 caractères max)" name="chatName" required />
        </div>
        <div class="mb-3">
            <label for="chatCategory" class="form-label">
                <strong>Catégorie : </strong>
            </label>
            <select name="chatCategory" id="chatCategory" required>
                <option value="animaux">Animaux</option>
                <option value="jardinerie">Jardinerie</option>
                <option value="autre">Autre</option>
            </select>
        </div>

        <div class="btns-popup-container d-flex justify-content-between">
            <input type="text" name="requestType" value="createChat" hidden>
            <input type="submit" class="btn btn-success m-2" value="Créer le chat" />
            <button type="button" class="btn btn-danger cancel m-2" onclick="closePopup('createChatPopup')">Annuler</button>
        </div>
    </form>
</div>