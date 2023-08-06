<div class="staff-create-box gestion-staff-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('delStaffPopup')"><strong>Retirer un staff</strong></button>
</div>
<div class="popup-acc popup start-50 top-50 translate-middle border-2 border p-3 shadow-sm border-dark-subtle rounded-3 bg-dark-subtle" id="delStaffPopup">
    <form action="https://staff.larche.ovh/includes/staff-options.php" class="form" method="post">
        <h2 class="m-1 text-center fs-3 fw-bold text-primary-emphasis">Retirer un staff</h2>
        <div class="mb-3">
            <label for="username" class="form-label">
                <strong>Pseudo de l'utilisateur</strong>
            </label>
            <input autocomplete="off" list="delUsernameSuggest" class="form-control" type="text" id="username" placeholder="pseudo" name="username" onkeyup="searchUserForStaffOption(this.value,'delStaffPopup')" required />
            <datalist style="display:none;" id="delUsernameSuggest">

            </datalist>
        </div>
        <div class="mb-3">
            <label for="chooseRole" class="form-label">
                <strong>Choisir le rôle</strong>
            </label>
            <select class="form-control" type="text" name="roleToAdd" required>
                <option value="contributor">Contributeur</option>
                <option value="member">Membre</option>
                <option value="banned">Banni</option>
            </select>
        </div>

        <div class="btns-popup-container d-flex justify-content-between">
            <input type="text" name="requestType" value="change-staff" hidden>
            <input type="submit" class="btn btn-success m-2" value="Valider" />
            <button type="button" class="btn btn-danger cancel m-2" onclick="closeDelStaffPopup()">Annuler</button>
        </div>
    </form>
</div>
<script>
    function closeDelStaffPopup() {
        document.getElementById("username").value = "";
        document.getElementById("delUsernameSuggest").innerHTML = "";
        document.getElementById("delUsernameSuggest").style.display = "none";
        document.getElementById("delStaffPopup").style.display = "none";
    }
</script>