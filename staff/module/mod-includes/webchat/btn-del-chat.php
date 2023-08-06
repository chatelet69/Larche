<div class="staff-view-box member-mod-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('viewChatPopup')"><strong>Voir/Supp un chat</strong></button>
</div>
<div class="popup-acc start-50 top-50 popup translate-middle" id="viewChatPopup">
    <form class="form-container popup-bg-dark-blue rounded-3 text-center shadow-sm text-light" method="post">
        <h2 class="m-1 text-center fs-3 fw-bold text-light">Voir/Supp</h2>
        <div class="mb-3">
            <label for="chatNameToSearch" class="form-label"><strong>Nom du chat</strong></label>
            <input max="40" class="form-control" oninput="searchChatToView(this.value)" type="text" id="chatNameToSearch" placeholder="Nom" name="chatName">
        </div>

        <div id="chatViewContainer">

        </div>

        <div class="btns-popup-container d-flex justify-content-center">
            <button type="button" class="btn btn-danger cancel m-2" onclick="closePopup('viewChatPopup')">Annuler</button>
        </div>
    </form>
</div>