<div class="staff-view-box member-mod-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('searchArticlePopup')"><strong>Chercher un article</strong></button>
</div>
<div class="form-popup top-50 start-50 popup translate-middle" id="searchArticlePopup">
    <form class="form-container popup-bg-dark-blue rounded-3 text-center text-light">
        <h2>Chercher un article</h2>
        <div>
            <select class="form-select" onchange="changeInput(this.value)">
                <option value="usernameInputForArticle" selected>Nom d'utilisateur</option>
                <option value="idInputForArticle">ID</option>
            </select>
            <input type="text" class="form-control" id="usernameInputForArticle" placeholder="pseudo" oninput="searchArticleByMember('username_author',this.value)">
            <input style="display:none;" class="mt-1 form-control" type="number" id="idInputForArticle" placeholder="Id" oninput="searchArticleByMember('id_article',this.value)">
        </div>

        <ul id="staffInfosBox" class="w-auto list-group p-1 m-1">
            
        </ul>
        <button type="button" class="btn btn-danger cancel" onclick="closePopup('searchArticlePopup')">Fermer</button>
    </form>
</div>