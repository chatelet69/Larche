function openForm() {
    document.getElementById("popupForm").style.display = "block";
}
function closeForm() {
    document.getElementById("popupForm").style.display = "none";
}
async function deleteCaptcha(captchaId) {
    try {
        let request = await fetch("./includes/delete-row.php", {
            method: "POST",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://staff.larche.ovh/index.php",
            body: `requestType=deleteCaptcha&captchaId=${captchaId}`
        });
        let response = await request.text();
        if (response === "ok") {
            document.getElementById('captcha-'+captchaId).remove();
        } else {
            alert(`Erreur durant la suppresion\n${response}`);
        }
    } catch (error) {
        alert("Erreur, veuillez retenter");
    }
}

async function deleteAvatar(avatarId) {
    try {
        let request = await fetch("./includes/delete-row.php", {
            method: "POST",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://staff.larche.ovh/index.php",
            body: `requestType=deleteAvatar&avatarId=${avatarId}`
        });
        let response = await request.text();
        if (response === "ok") {
            document.getElementById('avatar-'+avatarId).remove();
        } else {
            alert(`Erreur durant la suppresion\n${response}`);
        }
    } catch (error) {
        alert("Erreur, veuillez retenter");
    }
}

function selectAvatarOptions(avatarNameSection) {
    document.querySelectorAll('.avatar-option-container').forEach(section => {
        section.style.display = "none";
    })
    document.getElementById(avatarNameSection).style.display = "flex";
}