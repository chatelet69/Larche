let avatarResult = {
    "avatarHead": null,
    "avatarEyes": null,
    "avatarNoise": null,
    "avatarEars": null,
    "avatarMouth": null,
};

function selectAvatarOptions(option) {
    const boxs = {
        "Tête": "Head",
        "Yeux": "Eyes",
        "Nez": "Noise",
        "Bouche": "Mouth",
        "Oreilles": "Ears",
        "Sananes": "Sananes"
    };
    let name = "avatar"+boxs[option.innerHTML]+"Images";
    let oldItem = document.getElementById("avatar-option-selected");
    oldItem.id = oldItem.innerHTML+"-option";
    let oldSelected = "avatar"+boxs[document.getElementById("avatarOptionSelected").innerHTML]+"Images";
    document.getElementById(oldSelected).style.display = "none";
    document.getElementById("avatarOptionSelected").innerHTML = option.innerHTML;
    option.id = "avatar-option-selected";
    document.getElementById(name).style.display = "flex";
}

function selectAvatarItem(itemType,avatarItem) {
    let sananesCheck = document.getElementById("avatarSananes");
    if (itemType !== "avatarSananes" && sananesCheck !== null) {
        document.getElementById("avatarSananes").remove();
        avatarResult['avatarSananes'] = null;
    }
    if (itemType === "avatarSananes") {
        for (let key in avatarResult) avatarResult[key] = null;
    }
    avatarResult[itemType] = avatarItem;
    updateAvatar(avatarResult);
}

function updateAvatar(newAvatar) {
    let avatarContainer = document.getElementById("avatarResult");
    avatarContainer.innerHTML = "";
    for (let key in newAvatar) {
        let newImg = document.createElement("img");
        if (newAvatar[key] !== null) {
            newImg.id = key;
            newImg.src = newAvatar[key];
        }
        avatarContainer.appendChild(newImg);
    }
}

async function saveAvatar() {
    let check = true;
    var canvas = document.getElementById("canvasBox");
    var ctx = canvas.getContext("2d");
    canvas.height = 300;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    let avatarItem = document.querySelectorAll("#avatarResult img");
    let sananesAvatar = document.querySelector("#avatarResult #avatarSananes"), sananesCheck = false;
    if (sananesAvatar === null) {
        for (let i = 0; i < 5; i++) {
            if (avatarItem[i] !== undefined && avatarItem[i].id !== "") {
                ctx.drawImage(document.getElementById(avatarItem[i].id), 0, 0, canvas.width, canvas.height);
            } else {
                check = false;
                break;
            }
        }
        sananesCheck = false;
    } else if (sananesAvatar.src !== null) {
        sananesCheck = true;
    }
    let avatarImage = canvas.toDataURL("image/png");

    if (check === false) {
        alert("Il manque une partie de l'avatar");
    } else {
        let formData = new FormData();
        if (sananesCheck === false) {
            let avatarBlob = await fetch(avatarImage).then(function(res) { return res.blob()});
            formData.append('file', avatarBlob);
        } else if (sananesCheck === true) {
            formData.append('avatarSananes', 'avatarSananes');
        }
        try {
            let request = await fetch("./verif/changeAvatar.php", {
                method: "POST",
                mode: "no-cors",
                credentials: "same-origin",
                referrer: "https://larche.ovh/avatar.php",
                body: formData,
            });
            let response = await request.text();
            if (response === "ok") {
                alert("Avatar enregistré");
                setTimeout("location.href = 'https://larche.ovh/profil';", 500);
            }
        } catch (error) {
            alert("Erreur, Veuillez retenter");
        }
    }
}