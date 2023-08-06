var ticketToSearch,whatToSearch;
function openViewTicketNext() {
    const inputs = document.querySelectorAll(".ticket-user-input");
    if (inputs[0].value.length != 0 || inputs[1].value.length != 0) {
        if (inputs[0].value.length != 0) {
            ticketToSearch = inputs[0].value;
            whatToSearch = "id_ticket";
        } else {
            ticketToSearch = inputs[1].value;
            whatToSearch = "username_author";
        }
        document.getElementById("chooseTicketToGet").style.display = "none";
        document.getElementById("ticketBoxPopup").style.display = "block";
        document.getElementById("ticketChat").style.display = "block";
        document.getElementById("ticketChat").innerHTML = "En attente d'un chat";
        getTicket(whatToSearch ,ticketToSearch);
    } else {
        document.getElementById("alertTicketSearch").style.display = "block";
    }
}

let hasScrolled = false;
function scrollBottom() {
    let ticketChat = document.getElementById("ticketChat");
    if (ticketChat.scrollTop == ticketChat.scrollHeight || (ticketChat.scrollHeight - ticketChat.scrollTop) < 475) hasScrolled = false;
    if (!hasScrolled) ticketChat.scrollTop = ticketChat.scrollHeight;
}

function openViewTicketPopup() {
    document.getElementById("searchTicketPopup").style.display = "block";
}
function closeViewTicketPopup() {
    const inputs = document.querySelectorAll(".ticket-user-input");
    inputs[0].value = "";
    inputs[1].value = "";
    document.getElementById("chooseTicketToGet").style.display = "block";
    document.getElementById("ticketBoxPopup").style.display = "none";
    document.getElementById("alertTicketSearch").style.display = "none";
    document.getElementById("searchTicketPopup").style.display = "none";
}

async function getTicket(whatToSearch, search) {
    if (search.length === 0) {
        document.getElementById("ticketChat").innerHTML = "Chat non trouvé";
    } else {
        try {
            let request = await fetch(`../includes/chats-mod.php`, {
                method: "POST",
                mode: "no-cors",
                credentials: "same-origin",
                headers: {
                    "Content-type": "application/x-www-form-urlencoded",
                },
                referrer: "https://staff.larche.ovh/gestion-tickets.php",
                body: `requestType=getTicket&whatToSearch=${whatToSearch}&search=${search}`
            });
            let response = await request.text();
            if (ticketChat.innerHTML === "Ticket non trouvé") {
                document.getElementById("resolveTicketForm").style.display = "none";
                document.getElementById("resolveTicketForm").classList.remove("d-flex");
                document.getElementById("answerToTicket").disabled = "true";
                document.getElementById("sendAnswerBtn").disabled = "true";
            } else {
                document.getElementById("ticketChat").innerHTML = response;
                scrollBottom();
            }
        } catch (error) {
            console.log(error);
        }
    }
};

let fetchTicketConv = window.setInterval(function() {
    if (document.getElementById("ticketBoxPopup").style.display == "block") getTicket(whatToSearch,ticketToSearch);
}, 8000);

function sendAnswerTicket(message) {
    const ticketId = document.getElementById("ticketId").innerHTML;
    if (message.length != 0) {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("answerToTicket").value = "";
                if (this.responseText === "ok") {
                    getTicket("id_ticket", document.getElementById("ticketId").innerHTML);
                } else {
                    alert("Problème lors de l'envoi");
                }
            }
        };
        xmlhttp.open("POST", "https://staff.larche.ovh/includes/chats-mod.php", true);
        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xmlhttp.send(`requestType=sendAnswerTicket&message=${message}&ticketId=${ticketId}`);
    } else {
        alert("Il faut rentrer un message");
    }
}

function resolveTicket() {
    const ticketId = document.getElementById("ticketId").innerHTML;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            closeViewTicketPopup();
            location.reload();
        }
    };
    xmlhttp.open("POST", "https://staff.larche.ovh/includes/chats-mod.php", true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(`requestType=resolveTicket&ticketId=${ticketId}`);
}

function openTicket(ticketId) {
    ticketToSearch = ticketId;
    whatToSearch = "id_ticket";
    document.getElementById("chooseTicketToGet").style.display = "none";
    document.getElementById("searchTicketPopup").style.display = "block";
    document.getElementById("ticketBoxPopup").style.display = "block";
    getTicket("id_ticket", ticketId);
}

function deleteTicket(ticketId) {
    let deleteTicketBtn = document.querySelector(`#ticket-${ticketId} form input[type='submit']`);
    deleteTicketBtn.click();
}