<div class="staff-view-box member-mod-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('editAccountPopup')"><strong>Modif/Del/Ban</strong></button>
</div>
<div class="form-popup top-50 start-50 translate-middle" id="editAccountPopup">
    <form class="form-container popup-bg-dark-blue rounded-3 m-0 text-center text-light">
        <h2>Agir sur un membre</h2>
        <label for="username">
            <strong>Nom d'utilisateur</strong>
        </label>
        <input type="text" id="searchUserToEditInput" placeholder="pseudo" class="m-0" oninput="searchMemberToEdit(this.value)">

        <ul id="accountEditInfosBox" class="w-auto list-group p-1 m-1">
            
        </ul>
        <button type="button" class="btn mt-1 btn-danger cancel" onclick="closeEditAccountPopup()">Fermer</button>
    </form>
</div>
<script>
    function closeEditAccountPopup() {
        document.getElementById("searchUserToEditInput").value = "";
        document.getElementById("accountEditInfosBox").innerHTML = "";
        document.getElementById("editAccountPopup").style.display = "none";
    }

    function editAccountInfosPopup(userId) {
        document.getElementById("editAccountInfosPopup").style.display = "block";
        document.getElementById("editAccountInfosPopup").style.zIndex = 11;
    }
    function closeEditAccountInfosPopup() {
        document.getElementById("editAccountInfosPopup").style.display = "none";
    }
</script>