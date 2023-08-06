<div class="staff-view-box member-mod-btn mb-2">
    <button class="btn-popup open-button" onclick="openPopup('searchEventPopup')"><strong>Chercher un évènement</strong></button>
</div>
<div class="form-popup top-50 start-50 popup translate-middle" id="searchEventPopup">
    <form class="form-container popup-bg-dark-blue rounded-3 text-center text-light">
        <h2>Chercher un évènement</h2>
        <div>
            <label class="form-label" for="eventIdToSearchInput">ID Event</label>
            <input class="form-control" type="number" id="eventIdToSearchInput" placeholder="ID" oninput="searchEventById(this.value)" required>
        </div>

        <ul id="InfosContainer" class="w-auto list-group p-1 m-1">
            
        </ul>
        <button type="button" class="btn btn-danger cancel" onclick="closePopup('searchEventPopup')">Fermer</button>
    </form>
</div>