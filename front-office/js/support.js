
async function delTicket(id){
    let content = document.getElementById("ticket-list");
    let filtre = await fetch("./includes/del-ticket.php?id=" + id);
    let s = await filtre.text();
    content.innerHTML= s;
}


document.getElementById("contentMsgTicket").addEventListener("keypress", function(press) {
    if (press.key === "Enter") {
        press.preventDefault();
        document.getElementById("btn-ticket-msg").click();
    }
})

async function displayConvTicket(id){
    const res = await fetch('../includes/display-conv-ticket.php?id=' + id);
    const s = await res.text();
    const msg_container = document.getElementById('conv-ticket');
    msg_container.innerHTML = s;
}


const form = document.getElementById('formSendMsg');
form.addEventListener("submit", async function sendMsgTicket (e){ // lancer une fonction quand un parametre se declenche
    const file = document.getElementById('ticket-file');
    e.preventDefault(); // évite que le comportement de base s'éxecute
    const formData = new FormData(this);
    formData.append('id', document.getElementById('ticket-id').value);
    formData.append('msg', document.getElementById('contentMsgTicket').value);
    formData.append('file', file.files);
    const res = await fetch('../includes/send-ticket-msg.php', {
        method: 'POST',
        mode: 'no-cors', // pour firefox, éviter que ça bloque la requete
        credentials: "same-origin",
        // headers: { NE PAS METTRE LE HEADER QUAND C UN FORM AVEC DES IMAGES
        //    "Content-type": "application/x-www-form-urlencoded",
        // },
        referrer: "https://larche.ovh/ticket.php",
        body: formData, // les acolades indique qu'on concatene une variable, ici id se recuperera avec post 
    });
    const str = await res.text();
    if (str === 'ok') {
        displayConvTicket(document.getElementById('ticket-id').value);
    }else{
        alert(str);
    }
    this.reset();
    document.getElementById("contentMsgTicket").value='';
    scrollBottom();
});

/*window.setInterval(function(){
    displayConvTicket(document.getElementById('ticket-id').value);
}, 5000);
*/
function scrollBottom() {
    webChat = document.getElementById("conv-ticket");
    webChat.scrollTop = webChat.scrollHeight;
}