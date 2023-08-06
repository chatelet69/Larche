<div class="staff-view-box member-mod-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('SearchArticleForEditPopup')"><strong>Editer un article</strong></button>
</div>
<div class="form-popup top-50 start-50 popup translate-middle" id="SearchArticleForEditPopup">
    <form class="form-container popup-bg-dark-blue rounded-3 text-center text-light">
        <h2>Article à modifier</h2>
        <div class="d-flex align-items-center flex-column">
            <label class="form-label" for="idArticleToEditInput">ID Article</label>
            <input class="form-control" min="0" style="width:10vw;" type="number" id="idArticleToEditInput" placeholder="Id Article" oninput="searchArticleForEdit(this.value)" required>
        </div>

        <ul id="articleToEditContainer" class="w-auto list-group p-1 m-1">
            
        </ul>
        <button type="button" class="mt-1 btn btn-danger cancel" onclick="closePopup('SearchArticleForEditPopup')">Fermer</button>
    </form>
</div>