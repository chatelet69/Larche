<div class="staff-create-box gestion-staff-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('addStaffPopup')"><strong>Ajouter un staff</strong></button>
</div>
<div class="popup-acc popup start-50 top-50 translate-middle border-2 border p-3 shadow-sm border-dark-subtle rounded-3 bg-dark-subtle" id="addStaffPopup">
    <form autocomplete="off" action="https://staff.larche.ovh/includes/staff-options.php" class="form" method="post">
        <h2 class="m-1 text-center fs-3 fw-bold text-primary-emphasis">Ajouter un staff</h2>
        <div class="mb-3">
            <label for="username" class="form-label">
                <strong>Pseudo de l'utilisateur</strong>
            </label>
            <input autocomplete="off" list="addUsernameSuggest" class="form-control" type="username" id="username" onkeyup="searchUserForStaffOption(this.value,'addStaffPopup')" placeholder="pseudo" name="username" required />
            <datalist style="display:none;" id="addUsernameSuggest">

            </datalist> 
        </div>
        <div class="mb-3">
            <label for="chooseRole" class="form-label">
                <strong>Choisir le rôle</strong>
            </label>
            <select class="form-control" type="text" name="roleToAdd" required>
                <option value="mod">Modérateur</option>
                <option value="admin">Administrateur</option>
            </select>
        </div>

        <div class="btns-popup-container d-flex justify-content-between">
            <input type="text" name="requestType" value="change-staff" hidden>
            <input type="submit" class="btn btn-success m-2" value="Valider" />
            <button type="button" class="btn btn-danger cancel m-2" onclick="closeAddStaffPopup()">Annuler</button>
        </div>
    </form>
</div>
<script>
    function closeAddStaffPopup() {
        document.getElementById("username").value = "";
        document.getElementById("addUsernameSuggest").innerHTML = "";
        document.getElementById("addUsernameSuggest").style.display = "none";
        document.getElementById("addStaffPopup").style.display = "none";
    }
</script>