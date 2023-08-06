function hidePopup() {
    const hide = document.getElementById("popup");
    hide.style.display = "none";
  }

async function displayEvents(){
  const res = await fetch('../includes/display-event.php');
  const s = await res.text();
  let containerEvents = document.getElementById('list-event');
  containerEvents.innerHTML = s;
}

async function cancelEvent(){
  let eventId = document.getElementById('inp-event-id').value;
  let motif = document.getElementById('inp-event-motif').value;
  const res = await fetch('../includes/cancel-event.php', {
    method: 'POST',
        mode: 'no-cors', // pour firefox, éviter que ça bloque la requete
        credentials: "same-origin",
        headers: {
            "Content-type": "application/x-www-form-urlencoded",
        },
        referrer: "https://larche.ovh/events.php",
        body: `id=${eventId}&motif=${motif}` // les acolades indique qu'on concatene une variable, ici id se recuperera avec post
  });
  const s = await res.text();
  if (s == 'ok') {
    hidePopup();
    displayEvents();
  }else{
    alert(s);
  }
}
function showPopup(id) {
    const hide = document.getElementById("popup");
    hide.style.display = "block";
    let eventId = document.getElementById('inp-event-id');
    eventId.value = id;
  }