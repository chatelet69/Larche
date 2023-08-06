<div class="staff-create-box btn-dashboard col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('popupConfigAvatar')"><strong>Config Avatar</strong></button>
</div>
<div class="form-popup top-50 start-50 translate-middle" id="popupConfigAvatar">
    <form action="./includes/staff-options.php" enctype="multipart/form-data" method="post" class="form-container popup-bg-dark-blue rounded-3 text-center text-light">
        <h2>Uploader une nouvelle image d'Avatar</h2>
        <select class="mb-2 form-select" name="avatarType" id="avatarTypeSelect" required>
            <option value="avatarHead" selected>Tête</option>
            <option value="avatarEars">Oreilles</option>
            <option value="avatarEyes">Yeux</option>
            <option value="avatarMouth">Bouche</option>
            <option value="avatarNoise">Nez</option>
        </select>
        <div class="input-group mb-3">
            <input type="text" maxlength="2" name="avatarName" class="form-control" placeholder="Initiales (Ex : CB pour chat blanc)" required>
            <div>
                <label class="form-label" for="inputFileAvatar">Image PNG</label>
                <input type="file" class="form-control bg-primary-subtle" id="inputFileAvatar" name="newAvatarImage" required>
            </div>
        </div>
        <input type="hidden" name="requestType" value="configAvatar">
        <button type="submit" class="btn btn-primary">Valider</button>
        <button type="button" class="form-control btn btn-danger cancel" onclick="closePopup('popupConfigAvatar')">Fermer</button>
    </form>
</div>