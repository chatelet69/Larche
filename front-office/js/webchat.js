var oldRes = 0;
const urlSearch = window.location.search;
const urlParams = new URLSearchParams(urlSearch);
if (urlParams.size > 0 && urlSearch !== undefined) {
    let userToDm = urlParams.get('userToDM');
    if (userToDm !== undefined && userToDm.length > 0) {
        selectChatType('dm');
        searchUserToDm(userToDm);
        urlParams.delete('userToDM');
        localStorage.setItem('selectedDm', userToDm);
        location.href = 'https://larche.ovh/webchat';
    }
}

let chatTypeStorage = localStorage.getItem("chat");
if (chatTypeStorage !== null && chatTypeStorage !== undefined) {
    chatTypeStorage = chatTypeStorage.split("-")[0];
    if (selectChatType(chatTypeStorage)) {
        document.getElementById("typeWebchatSelect").value = localStorage.getItem("chat").split("-")[0];
    } else {
        localStorage.setItem('chat', 'publicChat');
    }
}

if (localStorage.getItem('selectedChat') !== null && localStorage.getItem("chatType") === "webchat") {
    selectChat((localStorage.getItem('selectedChat')));
}

if (localStorage.getItem('selectedDm') !== null && localStorage.getItem("chat") === "dm-container") {
    fetchDm((localStorage.getItem('selectedDm')));
}

let childsContainer = document.querySelectorAll('#chatContainer *');

// Parcourez chaque élément et annulez l'événement 'dragstart'
for (let i = 0; i < childsContainer.length; i++) {
    childsContainer[i].addEventListener('dragstart', function(event) {
        event.preventDefault();
        childsContainer[i].setAttribute('draggable', false);
    });
}

function selectChatType(webchatType) {
    try {
        let newType = webchatType+"-container";
        localStorage.setItem("chat", newType);
        let oldWebChatType = (webchatType === "dm") ? "publicChat-container" : "dm-container";
        if (document.getElementById(newType).style.display !== "flex") {
            document.getElementById(newType).style.display = "flex";
            document.getElementById(oldWebChatType).style.display = "none";
        }
        return true;
    } catch (error) {
        alert("Erreur, Veuillez retenter");
    }
}

function dragChat(chat) { 
    chat.dataTransfer.setData("text/plain", chat.target.id); 
    document.getElementById("chatContainer").classList.add('inDragAndDrop');
    document.getElementById("messageDragDrop").style.display = "block";
    document.getElementById("messageDragDrop").style.position = "absolute";
}

function allowDrop(chat) { 
    chat.preventDefault(); 
    document.getElementById("chatContainer").classList.add('inDragAndDrop');
    document.getElementById("messageDragDrop").style.display = "block";
    document.getElementById("messageDragDrop").style.position = "absolute";
}

function dropChat(chat) {
    chat.preventDefault();
    document.getElementById("chatContainer").classList.remove('inDragAndDrop');
    document.getElementById("messageDragDrop").style.display = "none";
    selectChat(chat.dataTransfer.getData("text/plain"));
}

document.getElementById("publicChat-container").addEventListener('dragend', function() {
    document.getElementById("chatContainer").classList.remove('inDragAndDrop');
    document.getElementById("messageDragDrop").style.display = "none";
});

document.getElementById("messageInputChat").addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        document.getElementById("sendMessageBtn").click();
    }
})

document.getElementById("messageInputDm").addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        document.getElementById("sendDmBtn").click();
    }
})

function selectChat(chatName) {
    localStorage.setItem('selectedChat', chatName);
    localStorage.setItem('chatType', "webchat");
    document.getElementById("chatContainer").classList.remove('inDragAndDrop');
    document.getElementById("messageDragDrop").style.display = "none";
    document.getElementById("webChatName").innerHTML = chatName;
    document.getElementById("formInputChat").style.display = "flex";
    document.getElementById("messageSelectChat").style.display = "none";
    fetchChat(chatName);
}

function menuMessages() {
    let messagesBox = document.querySelectorAll(".message-box");
    messagesBox.forEach(message => {
        message.addEventListener('contextmenu', function(event) {
            event.preventDefault();
            document.querySelectorAll('.message-menu-popup').forEach(element => element.remove());
        
            const child = event.target;
            const isChild = child.closest('.message-box') === message;
        
            let menu = document.createElement("div");
            menu.classList.add("message-menu-popup");
            if (isChild) {
                menu.style.display = "block";
                let p = document.createElement("p");
                let reportBtn = document.createElement("a");
                let messageId = message.id.split("-").pop();
                reportBtn.href = `./new-ticket?obj=reportMessage&id=${messageId}`;
                p.innerHTML = `ID du message : ${messageId}`;
                reportBtn.innerHTML = "Signaler";
                menu.appendChild(p);
                menu.appendChild(reportBtn);

                if (event.target.tagName === "IMG") {
                    let imgLink = document.createElement("a");
                    imgLink.href = event.target.src;
                    imgLink.id = "imageLinkPopup";
                    imgLink.innerHTML = "Voir l'image";
                    imgLink.setAttribute('target', '_blank');
                    menu.appendChild(imgLink);
                }

                menu.style.left = `${event.clientX+10}px`;
                menu.style.top = `${event.clientY-55}px`;
                document.body.appendChild(menu);
            }

            document.addEventListener('click', function() {
                menu.remove();
            })
        });
    })
}

function menuAuthorMessages() {
    let messagesBoxAuthor = document.querySelectorAll(".message-box-author");
    messagesBoxAuthor.forEach(messageAuthor => {
        messageAuthor.addEventListener('contextmenu', function(event) {
            event.preventDefault();
            document.querySelectorAll('.message-author-menu-popup').forEach(element => element.remove());
        
            const child = event.target;
            const isChild = child.closest('.message-box-author') === messageAuthor;
        
            let menu = document.createElement("div");
            menu.classList.add("message-author-menu-popup");
            if (isChild) {
                menu.style.display = "block";
                let p = document.createElement("p");
                let reportBtn = document.createElement("button");
                let messageId = messageAuthor.id.split("-").pop();
                reportBtn.setAttribute('onclick',`deleteMessage(${messageId})`);
                p.innerHTML = `ID du message : ${messageId}`;
                reportBtn.innerHTML = "Supprimer";
                menu.appendChild(p);
                menu.appendChild(reportBtn);

                if (event.target.tagName === "IMG") {
                    let imgLink = document.createElement("a");
                    imgLink.href = event.target.src;
                    imgLink.id = "imageLinkPopup";
                    imgLink.innerHTML = "Voir l'image";
                    imgLink.setAttribute('target', '_blank');
                    menu.appendChild(imgLink);
                }

                menu.style.left = `${event.clientX+10}px`;
                menu.style.top = `${event.clientY-55}px`;
                document.body.appendChild(menu);
            }

            document.addEventListener('click', function() {
                menu.remove();
            })
        });
    })
}

async function deleteMessage(messageId) {
    try {
        let request = await fetch('../includes/webchats-mod.php', {
            method: "POST",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://larche.ovh/webchat.php",
            body: `requestType=deleteMsg&messageId=${messageId}`
        });
        let response = await request.text();
        if (response == "Pas connecté") location.href = "https://larche.ovh/login";
        if (response === "ok") {
            fetchChat(document.getElementById("webChatName").innerHTML);
        } else {
            alert("Erreur dans la suppresion");
        }
    } catch (error) {
        alert("Erreur, Veuillez retenter");
    }
}

async function fetchChat(chatName) {
    try {
        let request = await fetch(`../includes/webchats-mod.php?requestType=getConv&chatName=${chatName}`, {
            method: "GET",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://larche.ovh/webchat.php",
        });
        let response = await request.text();
        if (response == "Pas connecté") location.href = "https://larche.ovh/login";
        if (response === "Chat non trouvé") document.getElementById("webChatName").innerHTML = "Chat non trouvé";
        if (response.length !== oldRes) {
            document.getElementById("webChat").innerHTML = response;
            oldRes = response.length;
        }
        menuMessages();
        menuAuthorMessages();
        scrollBottom();
    } catch (error) {
        alert("Erreur, Veuillez retenter");
    }
}

async function fetchDm(receiver) {
    localStorage.setItem('selectedDm', receiver);
    try {
        let request = await fetch(`../includes/webchats-mod.php?requestType=getDm&userConv=${receiver}`, {
            method: "GET",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://larche.ovh/webchat.php",
            //body: `requestType=getDm&receiver=${receiver}`
        });
        let response = await request.text();
        if (response == "Pas connecté") location.href = "https://larche.ovh/login";
        document.getElementById("dmChat").innerHTML = response;
        document.getElementById("formDmChat").style.display = "flex";
        document.getElementById("dmChatUser").innerHTML = receiver;
        //scrollBottom();
    } catch (error) {
        alert("Erreur, Veuillez retenter");
    }
}

// Envoi fichier 
function addFileMessage(inputFile) {
    document.getElementById(inputFile).click();
}

document.querySelectorAll(".formInputWebchat input[type='file']").forEach(function(inputFile) {
    inputFile.addEventListener('change', () => {
        let idBox = (inputFile.id === "imageToSendChat") ? "fileUploadedWebchat" : "fileUploadedDm";
        document.querySelector(`.formInputWebchat #${idBox}`).style.display = "inline-flex";
        document.querySelector(`.formInputWebchat #${idBox} p`).innerHTML = inputFile.files[0].name;
    })
})

function deleteFileUploaded(inputFile) {
    document.getElementById(inputFile).value = "";
    let idBox = (inputFile === "imageToSendChat") ? "fileUploadedWebchat" : "fileUploadedDm";
    document.querySelector(`.formInputWebchat #${idBox}`).style.display = "none";
    document.querySelector(`.formInputWebchat #${idBox} p`).innerHTML = "";
}

// Envoi message 
async function sendMessage(chatType, message, imageInput, receiver) {
    if (message.length === 0 && imageInput.files.length === 0) {
        alert("Message vide");
        return;
    } 

    let bodyForm = new FormData(), chatName = (chatType === "webchat") ? document.getElementById('webChatName').innerHTML : document.getElementById('dmChatUser').innerHTML;
    if (chatType === "webchat") {
        bodyForm.append('requestType', 'sendMessage');
        bodyForm.append('chatName', chatName);
    } else if (chatType === "dm") {
        bodyForm.append('requestType', 'sendDm');
        bodyForm.append('userConv', chatName);
    }
    if (imageInput !== undefined && imageInput.files.length === 1) {
        bodyForm.append('image', imageInput.files[0]);
        imageInput.value = "";
        deleteFileUploaded(imageInput.id);
    }
    bodyForm.append('message', message);
    try {
        let request = await fetch("../includes/webchats-mod.php", {
            method: "POST",
            mode: "no-cors",
            credentials: "same-origin",
            referrer: "https://larche.ovh/webchat.php",
            body: bodyForm
        });
        let response = await request.text();
        if (chatType === "webchat") {
            document.getElementById("messageInputChat").value = "";
            if (response === "ok") fetchChat(document.getElementById('webChatName').innerHTML);
        } else if (chatType === "dm") {
            document.getElementById("messageInputDm").value = "";
            if (response === "ok") fetchDm(document.getElementById("dmChatUser").innerHTML);
        }
    } catch (error) {
        alert("Erreur veuillez retenter");
    }
}

// Scroll bottom
let hasScrolled = false, webChat = document.getElementById("webChat");
function scrollBottom() {
    if (webChat.scrollTop == webChat.scrollHeight || (webChat.scrollHeight - webChat.scrollTop) < 475) hasScrolled = false;
    if (!hasScrolled) webChat.scrollTop = webChat.scrollHeight;
}

webChat.addEventListener("scroll", (event) => {
    hasScrolled = true;
})

let fetchConvChat = window.setInterval(function() {
    if (document.getElementById("webChatName").innerHTML !== "") {
        fetchChat(document.getElementById("webChatName").innerHTML);
        if (webChat.scrollTop != webChat.scrollHeight) scrollBottom();
    }
}, 4500);

let fetchConvDm = window.setInterval(function() {
    if (document.getElementById("dmChatUser").innerHTML !== "") {
        fetchDm(document.getElementById("dmChatUser").innerHTML);
        if (webChat.scrollTop != webChat.scrollHeight) scrollBottom();
    }
}, 4500);

// DM 
async function searchUserToDm(input) {
    try {
        let request = await fetch(`../includes/webchats-mod.php?requestType=searchUserToDm&userName=${input}`, {
            method: "GET",
            mode: "no-cors",
            credentials: "same-origin",
            headers: {
                "Content-type": "application/x-www-form-urlencoded",
            },
            referrer: "https://larche.ovh/webchat.php",
        });
        let response = await request.text();
        if (response == "Pas connecté") location.href = "https://larche.ovh/login";
        if (response === "already") {
            fetchDm(input);
        /*} else if (response === input) {
           await createDm(response);*/
        } else {
            alert("Erreur, veuillez retenter");
        }
    } catch (error) {
        alert("Erreur, Veuillez retenter");
    }
}

async function createDm(userName) {
    if (userName.length > 0) {
        const dms = document.querySelectorAll("#dmConvsNames li");
        let checkDm = true;
        dms.forEach(function (element) {
            if (userName === element.id) {
                fetchDm(userName);
                checkDm = false;
            }
        })
        if (checkDm === true) {
            try {
                let request = await fetch(`../includes/webchats-mod.php`, {
                    method: "POST",
                    mode: "no-cors",
                    credentials: "same-origin",
                    headers: {
                        "Content-type": "application/x-www-form-urlencoded",
                    },
                    referrer: "https://larche.ovh/webchat.php",
                    body: `requestType=createDm&userName=${userName}`
                });
                let response = await request.text();
                if (response == "Pas connecté") location.href = "https://larche.ovh/login";
                if (response === userName) {
                    sendMessage('dm', `Bonjour ${response}`, null, response);
                    fetchDm(`${response}`);
                    document.getElementById('newUserConvInput').value = "";
                    let newConv = document.createElement("li");
                    newConv.setAttribute("draggable", true);
                    newConv.id = userName;
                    newConv.innerHTML = userName;
                    newConv.setAttribute("ondragstart", 'dragChat(event)');
                    newConv.setAttribute("onclick", 'fetchDm(this.innerHTML)');
                    newConv.classList.add("channel-name");
                    document.getElementById("dmConvsNames").appendChild(newConv);
                } else {
                    alert("L'utilisateur indiqué ne peut pas être contacté");
                }
            } catch (error) {
                alert("Erreur durant l'execution, veuillez retenter");
            }
        }
    }
}