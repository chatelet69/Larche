<?php 
    include("./includes/db-connect.php");
    $sqlGetAvatars = $bdd->query("SELECT * FROM avatar ORDER BY type");
    $avatarImages = $sqlGetAvatars->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="staff-create-box btn-dashboard col-sm-2">
    <button class="btn-popup open-button" onclick="openPopup('popupViewDelAvatar')"><strong>Voir/Del Avatar</strong></button>
</div>
<div class="form-popup top-50 start-50 translate-middle rounded-3 popup text-center text-light" id="popupViewDelAvatar">
    <div class="form-container text-center popup-bg-dark-blue rounded-3">
        <h2>Liste avatar</h2>
    <select class="form-select" name="selectAvatarOption" onchange="selectAvatarOptions(this.value)" id="AvatarOptionSelect">
        <option value="avatarHead" selected>Tête</option>
        <option value="avatarMouth">Bouche</option>
        <option value="avatarEyes">Yeux</option>
        <option value="avatarNoise">Nez</option>
        <option value="avatarEars">Oreilles</option>
    </select>
        <div id="avatarHead" class="avatar-option-container">
            <?php
            foreach ($avatarImages as $avatarItem => $value) {
                if ($avatarImages[$avatarItem]['type'] === "avatarHead") {
                    echo "<div class='avatar-box mb-2' id='avatar-".$avatarImages[$avatarItem]['id_item']."'>
                    <h3>".$avatarImages[$avatarItem]['name']."</h3>
                    <img class='image-captcha' src='https://larche.ovh/".$avatarImages[$avatarItem]['link']."' alt='image avatar'>
                    <button type='button' class='mt-1 btn btn-danger' onclick='deleteAvatar(".$avatarImages[$avatarItem]['id_item'].")'>Supprimer</button>
                    </div>";
                }
            }
            ?>
        </div>
        <div id="avatarMouth" style="display:none;" class="avatar-option-container">
            <?php
            foreach ($avatarImages as $avatarItem => $value) {
                if ($avatarImages[$avatarItem]['type'] === "avatarMouth") {
                    echo "<div class='avatar-box mb-2' id='avatar-".$avatarImages[$avatarItem]['id_item']."'>
                    <h3>".$avatarImages[$avatarItem]['name']."</h3>
                    <img class='image-captcha' src='https://larche.ovh/".$avatarImages[$avatarItem]['link']."' alt='image avatar'>
                    <button type='button' class='mt-1 btn btn-danger' onclick='deleteAvatar(".$avatarImages[$avatarItem]['id_item'].")'>Supprimer</button>
                    </div>";
                }
            }
            ?>
        </div>
        <div id="avatarEyes" style="display:none;" class="avatar-option-container">
            <?php
            foreach ($avatarImages as $avatarItem => $value) {
                if ($avatarImages[$avatarItem]['type'] === "avatarEyes") {
                    echo "<div class='avatar-box mb-2' id='avatar-".$avatarImages[$avatarItem]['id_item']."'>
                    <h3>".$avatarImages[$avatarItem]['name']."</h3>
                    <img class='image-captcha' src='https://larche.ovh/".$avatarImages[$avatarItem]['link']."' alt='image avatar'>
                    <button type='button' class='mt-1 btn btn-danger' onclick='deleteAvatar(".$avatarImages[$avatarItem]['id_item'].")'>Supprimer</button>
                    </div>";
                }
            }
            ?>
        </div>
        <div id="avatarNoise" style="display:none;" class="avatar-option-container">
            <?php
            foreach ($avatarImages as $avatarItem => $value) {
                if ($avatarImages[$avatarItem]['type'] === "avatarNoise") {
                    echo "<div class='avatar-box mb-2' id='avatar-".$avatarImages[$avatarItem]['id_item']."'>
                    <h3>".$avatarImages[$avatarItem]['name']."</h3>
                    <img class='image-captcha' src='https://larche.ovh/".$avatarImages[$avatarItem]['link']."' alt='image avatar'>
                    <button type='button' class='mt-1 btn btn-danger' onclick='deleteAvatar(".$avatarImages[$avatarItem]['id_item'].")'>Supprimer</button>
                    </div>";
                }
            }
            ?>
        </div>
        <div id="avatarEars" style="display:none;" class="avatar-option-container">
            <?php
            foreach ($avatarImages as $avatarItem => $value) {
                if ($avatarImages[$avatarItem]['type'] === "avatarEars") {
                    echo "<div class='avatar-box mb-2' id='avatar-".$avatarImages[$avatarItem]['id_item']."'>
                    <h3>".$avatarImages[$avatarItem]['name']."</h3>
                    <img class='image-captcha' src='https://larche.ovh/".$avatarImages[$avatarItem]['link']."' alt='image avatar'>
                    <button type='button' class='mt-1 btn btn-danger' onclick='deleteAvatar(".$avatarImages[$avatarItem]['id_item'].")'>Supprimer</button>
                    </div>";
                }
            }
            ?>
        </div>
    <button type="button" class="mt-2 form-control btn btn-danger cancel" onclick="closePopup('popupViewDelAvatar')">Fermer</button>
    </div>
</div>