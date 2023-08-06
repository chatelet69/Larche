<div class="staff-view-box mb-2 mt-2">
    <button class="btn-popup open-button" onclick="openPopup('viewLogStaffSearchPopup')"><strong>Chercher une Log Staff</strong></button>
</div>
<div class="form-popup top-50 start-50 translate-middle" id="viewLogStaffSearchPopup">
    <form class="form-container bg-secondary p-3 popup-bg-dark-blue rounded-3 text-center text-light">
        <h2 class='fw-bold'>Chercher une Log Staff</h2>
        <div class="mb-1">
            <label class="form-label" for="username">
                <strong>Par Pseudo</strong>
            </label>
            <input class="form-control" type="text" name="username" placeholder="pseudo" onkeyup="searchStaffLog(this)" required>
        </div>
        <div class="mb-2 d-flex flex-column align-items-center">
            <label class="form-label" for="id_log">
                <strong>Par ID</strong>
            </label>
            <input class="w-50 form-control" type="number" name="id_log" placeholder="ID" onkeyup="searchStaffLog(this)" required>
        </div>
        <ul id="logInfoBox" class="w-auto list-group p-1 m-1">
            
        </ul>
        <button class="btn btn-danger cancel" onclick="closePopup('viewLogStaffSearchPopup')">Fermer</button>
    </form>
</div>