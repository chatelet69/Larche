<div class="staff-view-box member-mod-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('viewMessageChatPopup')"><strong>Voir un message</strong></button>
</div>
<div class="form-popup top-50 start-50 translate-middle popup" id="viewMessageChatPopup">
    <form class="form-container popup-bg-dark-blue rounded-3 text-center text-light">
        <h2>Chercher un message</h2>
        <div>
            <label for="username"><strong>Pseudo (dernier message)</strong></label>
            <input type="text" id="searchUsername" placeholder="pseudo" oninput="searchMessage('user',this.value)" required>
        </div>
        <div>
            <label for="searchMessageId"><strong>ID</strong></label>
            <input type="text" id="searchMessageId" placeholder="ID" oninput="searchMessage('id',this.value)" required>
        </div>

        <ul id="messageSearchContainer" class="w-auto list-group p-1 m-1">
            
        </ul>
        <button type="button" class="btn btn-danger cancel" onclick="closePopup('viewMessageChatPopup')">Fermer</button>
    </form>
</div>