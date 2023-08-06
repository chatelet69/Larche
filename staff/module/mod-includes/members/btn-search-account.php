<div class="staff-view-box member-mod-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('viewStaffPopup')"><strong>Voir un Compte</strong></button>
</div>
<div class="form-popup top-50 start-50 translate-middle" id="viewStaffPopup">
    <form class="form-container popup-bg-dark-blue rounded-3 text-center text-light">
        <h2>Chercher un membre</h2>
        <label for="username">
            <strong>Nom d'utilisateur</strong>
        </label>
        <input type="text" id="searchUsername" placeholder="pseudo" oninput="searchMember(this.value)" required>

        <ul id="staffInfosBox" class="w-auto list-group p-1 m-1">
            
        </ul>
        <button type="button" class="btn btn-danger cancel" onclick="closeViewMemberSearch()">Fermer</button>
    </form>
</div>
<script>
    function closeViewMemberSearch() {
        document.getElementById("staffInfosBox").innerHTML = "";
        document.getElementById("searchUsername").value = "";
        document.getElementById("viewStaffPopup").style.display = "none";
    }
</script>