<div class="staff-create-box btn-dashboard col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('popupConfigCaptcha')"><strong>Ajouter Captcha</strong></button>
</div>
<div class="form-popup top-50 start-50 translate-middle" id="popupConfigCaptcha">
    <form action="./includes/staff-options.php" enctype="multipart/form-data" method="post" class="form-container popup-bg-dark-blue rounded-3 text-center text-light">
        <h2>Uploader une nouvelle image</h2>
        <div class="input-group mb-3">
            <input type="file" class="form-control bg-primary-subtle" id="inputFileCaptcha" name="newCaptchaImage">
        </div>
        <input type="hidden" name="requestType" value="changeCaptcha">
        <button type="submit" class="btn btn-primary">Valider</button>
        <button type="button" class="form-control btn btn-danger cancel" onclick="closePopup('popupConfigCaptcha')">Fermer</button>
    </form>
</div>