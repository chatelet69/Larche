<div class="staff-create-box member-mod-btn col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('createAccPopup')"><strong>Créer un compte</strong></button>
</div>
<div class="popup-acc start-50 top-50 translate-middle border-2 border p-3 shadow-sm border-dark-subtle rounded-3 bg-dark-subtle" id="createAccPopup">
    <form action="https://staff.larche.ovh/includes/staff-options.php" class="form" method="post">
        <h2 class="m-1 text-center fs-3 fw-bold text-primary-emphasis">Création d'un compte</h2>
        <div class="mb-3">
            <label for="username" class="form-label">
                <strong>Nom d'utilisateur</strong>
            </label>
            <input class="form-control" type="text" id="username" placeholder="pseudo" name="username" required />
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">
                <strong>E-mail</strong>
            </label>
            <input class="form-control" type="text" id="email" placeholder="Email" name="email" required />
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">
                <strong>Mot de passe</strong>
            </label>
            <input class="form-control" type="password" id="password" placeholder="mot de passe" name="password" required />
            <div id="passwordInfoSubtle" class="form-text">
                Mot de passe de 30 caractères max
            </div>
        </div>
        <div class="mb-3">
            <label for="lvl_perms" class="form-label">
                <strong>Level de perms</strong>
            </label>
            <input class="form-control" type="number" placeholder="perms" min="0" max="4" name="lvlperms" required />
        </div>

        <div class="btns-popup-container d-flex justify-content-between">
            <input type="text" name="requestType" value="create-account" hidden>
            <input type="submit" class="btn btn-success m-2" value="Créer le compte" />
            <button type="submit" class="btn btn-danger cancel m-2" onclick="closePopup('createAccPopup')">Annuler</button>
        </div>
    </form>
</div>