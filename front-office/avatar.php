<?php
include('./includes/checksessions.php');
include('./includes/db-connect.php');

$sqlGetAvatarImages = $bdd->prepare("SELECT * FROM avatar ORDER BY type");
$sqlGetAvatarImages->execute();
$avatarImages = $sqlGetAvatarImages->fetchAll(PDO::FETCH_ASSOC);

$getAge = $bdd->prepare("SELECT DATE_FORMAT(age, '%d/%m') AS age FROM user WHERE user_id = ?");
$getAge->execute([$_SESSION['user_id']]);
$userAge = $getAge->fetch(PDO::FETCH_ASSOC);
$isSananesBirth = false;
if ($userAge['age'] === "19/04") {
    $isSananesBirth = true;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Créer mon avatar</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="stylesheet" type="text/css" href="./css/profil.css">
    <link rel="stylesheet" type="text/css" href="./css/settings.css">
    <link rel="stylesheet" type="text/css" href="./css/avatar.css">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
</head>

<body class="body-bg-beige">
    <?php include('./includes/header.php'); ?>
    <header>
        <a href="settings-photo.php"><img src="./assets/backblack.png" id="black" class="backlogo"></a>
        <a href="settings-photo.php"><img src="./assets/backwhite.png" id="white" class="backlogo"></a>
    </header>

    <main class="profil-main">
        <section id="avatarSectionContainer">
            <h1>Créer mon avatar</h1>
            <div id="avatarCreatorContainer">
                <ul class="avatarSection avatar-options-list">
                    <h2>Option choisie :</h2>
                    <h2 id="avatarOptionSelected">Tête</h2>
                    <li onclick="selectAvatarOptions(this)" id="avatar-option-selected" class="avatar-option-item">Tête</li>
                    <li onclick="selectAvatarOptions(this)" id="yeux-option" class="avatar-option-item">Yeux</li>
                    <li onclick="selectAvatarOptions(this)" id="nez-option" class="avatar-option-item">Nez</li>
                    <li onclick="selectAvatarOptions(this)" id="bouche-option" class="avatar-option-item">Bouche</li>
                    <li onclick="selectAvatarOptions(this)" id="oreilles-option" class="avatar-option-item">Oreilles</li>
                    <?php
                    if ($isSananesBirth === true) echo "<li onclick='selectAvatarOptions(this)' id='sanane-option' class='avatar-option-item'>Sananes</li>";
                    ?>
                </ul>
                <div class="avatarSection" id="avatarImagesContainer">
                    <div id="avatarHeadImages" class="avatar-images-box">
                    <?php
                        foreach($avatarImages as $avatarItem) {
                            if ($avatarItem["type"] === "avatarHead") {
                                echo "<div draggable=false class='avatar-images-item'>
                                <img onclick='selectAvatarItem(`avatarHead`,this.src)' draggable=false src=".$avatarItem["link"]." alt='image-tete'>
                                </div>";
                            }
                        }
                        ?>
                    </div>
                    <div style="display: none;" id="avatarEyesImages" class="avatar-images-box">
                        <?php
                        foreach($avatarImages as $avatarItem) {
                            if ($avatarItem["type"] === "avatarEyes") {
                                if ($avatarItem["name"] !== "FS-lunettes") {
                                    echo "<div draggable=false class='avatar-images-item'>
                                    <img onclick='selectAvatarItem(`avatarEyes`,this.src)' draggable=false src=".$avatarItem["link"]." alt='image-yeux'>
                                    </div>";
                                } else if ($avatarItem["name"] === "FS-lunettes" && $isSananesBirth === true) {
                                    echo "<div draggable=false class='avatar-images-item'>
                                    <img onclick='selectAvatarItem(`avatarEyes`,this.src)' draggable=false src=".$avatarItem["link"]." alt='image-yeux'>
                                    </div>";
                                }
                            }
                        }
                        ?>
                    </div>
                    <div style="display: none;" id="avatarNoiseImages" class="avatar-images-box">
                        <?php
                        foreach($avatarImages as $avatarItem) {
                            if ($avatarItem["type"] === "avatarNoise") {
                                echo "<div draggable=false class='avatar-images-item'>
                                <img onclick='selectAvatarItem(`avatarNoise`,this.src)' draggable=false src=".$avatarItem["link"]." alt='image-nez'>
                                </div>";
                            }
                        }
                        ?>
                    </div>
                    <div style="display: none;" id="avatarMouthImages" class="avatar-images-box">
                        <?php
                        foreach($avatarImages as $avatarItem) {
                            if ($avatarItem["type"] === "avatarMouth") {
                                echo "<div draggable=false class='avatar-images-item'>
                                <img onclick='selectAvatarItem(`avatarMouth`,this.src)' draggable=false src=".$avatarItem["link"]." alt='image-bouche'>
                                </div>";
                            }
                        }
                        ?>
                    </div>
                    <div style="display: none;" id="avatarEarsImages" class="avatar-images-box">
                        <?php
                        foreach($avatarImages as $avatarItem) {
                            if ($avatarItem["type"] === "avatarEars") {
                                echo "<div draggable=false class='avatar-images-item'>
                                <img onclick='selectAvatarItem(`avatarEars`,this.src)' draggable=false src=".$avatarItem["link"]." alt='image-oreilles'>
                                </div>";
                            }
                        }
                        ?>
                    </div>
                    <?php 
                    if ($isSananesBirth === true) {
                        echo "<div style='display: none;' id='avatarSananesImages' class='avatar-images-box'>";
                        foreach($avatarImages as $avatarItem) {
                            if ($avatarItem["type"] === "avatarSananes") {
                                echo "<div draggable=false class='avatar-images-item'>
                                <img onclick='selectAvatarItem(`avatarSananes`,this.src)' draggable=false src=".$avatarItem["link"]." alt='image-sananes'>
                                </div>";
                            }
                        }
                        echo "</div>";
                    }
                    ?>
                </div>
                <div class="avatarSection avatar-result-container">
                    <h2>Résultat</h2>
                    <div id="avatarResult">
                    </div>
                    <canvas hidden id="canvasBox">
                    </canvas>
                </div>
            </div>
            <div id="buttonsAvatarSection">
                <a type="button" href="./settings-photo">Annuler</a>
                <button type="button" onclick="saveAvatar()">Enregistrer</button>
            </div>
        </section>
    </main>
    <?php include('./includes/footer.php'); ?>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
    <script src="./js/profil.js"></script>
    <script src="./js/avatar.js"></script>
</body>

</html>