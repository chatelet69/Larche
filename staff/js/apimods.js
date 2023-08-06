// Gestion chat
async function searchChatName(input) {
    try {
        let request = await fetch(`../includes/chats-mod.php?requestType=searchChat&input=${input}`, {
            method: "GET",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://staff.larche.ovh/gestion-chat.php",
        });
        let response = await request.text();
        console.log(response);
        document.getElementById("messagesContainer").innerHTML = response;
    } catch (error) {
        console.log(error);
    }
}

async function fetchChatLogs(chatName) {
    if (chatName.length === 0) {
        document.getElementById("messagesContainer").innerHTML = "";
    } else {
        try {
            let request = await fetch(`../includes/chats-mod.php?requestType=getChat&chatName=${chatName}`, {
            method: "GET",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
                referrer: "https://staff.larche.ovh/gestion-chat.php",
            });
            let response = await request.text();
            document.getElementById("messagesContainer").innerHTML = response;
        } catch (error) {
            console.log(error);
        }
    }
}

async function searchMessage(type, input) {
    if (input.length === 0) {
        document.getElementById("messageSearchContainer").innerHTML = "";
    } else {
        try {
            let request = await fetch(`../includes/chats-mod.php?requestType=searchMessage&type=${type}&search=${input}`, {
                method: "GET",
                mode: "no-cors",
                credentials: "same-origin",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                },
                referrer: "https://staff.larche.ovh/gestion-chat.php",
            });
            let response = await request.text();
            document.getElementById("messageSearchContainer").innerHTML = response;
        } catch (error) {
            console.log(error);
        }
    }
}

async function searchChatToView(chatName) {
    try {
        let request = await fetch(`../includes/chats-mod.php?requestType=searchChatToEdit&chatName=${chatName}`, {
            method: "GET",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://staff.larche.ovh/gestion-chat.php",
        });
        let response = await request.text();
        if (response !== "ko") document.getElementById("chatViewContainer").innerHTML = response;
    } catch (error) {
        alert("Erreur dans l'execution veuillez retenter");
    }
}

async function deleteChat(chatId) {
    try {
        let request = await fetch(`../includes/chats-mod.php`, {
            method: "POST",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://staff.larche.ovh/gestion-chat.php",
            body: `requestType=deleteChat&chatId=${chatId}`
        });
        let response = await request.text();
        if (response === "ok") {
            document.getElementById("chatViewContainer").innerHTML = "";
            document.getElementById("chatNameToSearch").value = "";
        } else {
            alert(`Erreur veuillez retenter\n${response}`);
        }
    } catch (error) {
        alert("Erreur veuillez retenter");
    }
}

async function deleteMessageFromChat(messageId) {
    if (messageId.length !== 0) {
        try {
            let request = await fetch(`../includes/chats-mod.php`, {
                method: "POST",
                mode: "no-cors",
                credentials: "same-origin",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                },
                referrer: "https://staff.larche.ovh/gestion-chat.php",
                body: `requestType=deleteMessageFromChat&messageId=${messageId}`
            });
            let response = await request.text();
            if (response === "ok") {
                document.getElementById("messageSearchContainer").innerHTML = "";
                document.querySelectorAll("#viewMessageChatPopup input").forEach(input => {input.value = "";});
            } else {
                alert(`Erreur veuillez retenter\n${response}`);
            }
        } catch (error) {
            alert("Erreur veuillez retenter");
        }
    } else {
        alert("Problème dans la valeur indiquée");
    }
}

// Logs Staff
async function searchStaffLog(str) {
    if (str.value.length == 0) {
        document.getElementById("logInfoBox").innerHTML = "";
        document.getElementById("logInfoBox").style.display = "none";
    } else {
        try {
            let request = await fetch(`../includes/staff-options.php`, {
                method: "POST",
                mode: "no-cors",
                credentials: "same-origin",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                },
                referrer: "https://staff.larche.ovh/logs-staff.php",
                body: `requestType=staffLogSearch&logToSearch=${str.value}&typeToSearch=${(str.type === "number") ? "id_staff_action" : "username"}`
            });
            let response = await request.text();
            if (response !== "error" && response !== false) {
                document.getElementById("logInfoBox").style.display = "block";
                document.getElementById("logInfoBox").innerHTML = response;
            } else {
                alert(response);
            }
        } catch (error) {
            alert("Erreur veuillez retenter");
        }
    }
}

/* Gestion membres */
async function searchMember(memberName) {
    if (memberName.length == 0) {
        document.getElementById("staffInfosBox").innerHTML = "";
        document.getElementById("staffInfosBox").style.display = "none";
        return;
    } else {
        try {
            let request = await fetch(`../includes/membersMod.php`, {
                method: "POST",
                mode: "no-cors",
                credentials: "same-origin",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                },
                referrer: "https://staff.larche.ovh/gestion-chat.php",
                body: `requestType=searchUser&username=${memberName}`
            });
            let response = await request.text();
            if (response !== "KO") {
                document.getElementById("staffInfosBox").style.display = "block";
                document.getElementById("staffInfosBox").innerHTML =response;
            }
        } catch(error) {
            alert("Erreur veuillez retenter");
        }
    }
};

async function searchMemberToEdit(memberName) {
    if (memberName.length === 0) {
        document.getElementById("accountEditInfosBox").innerHTML = "";
        document.getElementById("accountEditInfosBox").style.display = "none";
        return;
    } else {
        try {
            let request = await fetch(`../includes/membersMod.php?requestType=editAccount&userName=${memberName}`, {
                method: "GET",
                mode: "no-cors",
                credentials: "same-origin",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                },
                referrer: "https://staff.larche.ovh/gestion-chat.php",
            });
            let response = await request.text();
            if (response !== "KO") {
                document.getElementById("accountEditInfosBox").style.display = "block";
                document.getElementById("accountEditInfosBox").innerHTML =response;
            }
        } catch(error) {
            alert("Erreur veuillez retenter");
        }
    }
};

async function saveEditAccountInfos(userId) {
    let formData = new FormData();
    let editElements = document.querySelectorAll("#editAccountInfosPopup input");
    editElements.forEach(element => {
        if (element.value.length !== 0 && element.value !== element.placeholder) {
            formData.append(`${element.name}`, element.value);
        }
    }) 
    // On récupère la première entrée du formData 
    if (formData.entries().next().value !== undefined) {
        formData.append('requestType', 'saveEditAccount');
        formData.append('user_id', userId);
        try {
            let request = await fetch(`../includes/membersMod.php`, {
                method: "POST",
                mode: "no-cors",
                credentials: "same-origin",
                referrer: "https://staff.larche.ovh/gestion-chat.php",
                body: formData
            });
            let response = await request.text();
            if (response === "ok") {
                document.getElementById("editAccountInfosPopup").style.display = "none";
                searchMemberToEdit(document.getElementById('searchUserToEditInput').value);
            } else {
                alert(`Erreur\n${response}`);
            }
        } catch(error) {
            alert("Erreur veuillez retenter");
        }
    } else {
        alert("Aucune valeur remplie");
    }
}