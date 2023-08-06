<?php 
    include("./includes/db-connect.php");
    $sqlGetCaptcha = $bdd->query("SELECT name,link,num FROM captcha_images");
    $captchaImages = $sqlGetCaptcha->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="staff-create-box btn-dashboard col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('popupDeleteCaptcha')"><strong>Voir/Del Captchas</strong></button>
</div>
<div class="form-popup top-50 start-50 translate-middle rounded-3 popup text-center text-light" id="popupDeleteCaptcha">
    <h2>Liste captcha</h2>
    <div class="form-container popup-bg-dark-blue rounded-3">
        <div class="captchas-container">
            <?php
            foreach ($captchaImages as $image => $value) {
                echo "<div class='captcha-box mb-2' id='captcha-".$captchaImages[$image]['num']."'>
                <h3>".$captchaImages[$image]['name']."</h3>
                <img class='image-captcha' src='".$captchaImages[$image]['link']."' alt='image captcha'>
                <button type='button' class='mt-1 w-50 btn btn-danger' onclick='deleteCaptcha(".$captchaImages[$image]['num'].")'>Supprimer</button>
                </div>";
            }
            ?>
        </div>
    <button type="button" class="mt-2 form-control btn btn-danger cancel" onclick="closePopup('popupDeleteCaptcha')">Fermer</button>
    </div>
</div>