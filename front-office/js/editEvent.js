function editEvent() {
    let input = document.getElementsByClassName("modify");
    let buttonModify = document.getElementById("buttonModify");
    let i = 0;
    if (input[i].style.display == "block") {
        buttonModify.innerHTML =
        "Modifier l'évènement <img src='./assets/edit.png' alt='crayon modif évènement'>";
        document.getElementById("btnValidBtn").style.display = "none";
        for (i = 0; i < input.length; i++) {
          input[i].style.display = "none";
        document.getElementById(i + "-event").style.display = "block";
      }
    } else {
      for (let i = 0; i < input.length; i++) {
        input[i].style.display = "block";
        input[i].value = document.getElementById(i + "-event").innerHTML;
        document.getElementById(i + "-event").style.display = "none";
      }
      buttonModify.innerHTML = "Annuler les modifications";
      document.getElementById("btnValidBtn").style.display = "block";
    }
  }
  
  async function displayEvent(id) {
    const res = await fetch("../includes/displayEvent.php?id=" + id);
    const s = await res.text();
    let container = document.getElementById("event-content");
    container.innerHTML = s;
  }
  
  async function validModifEvent(id) {
    let titre = document.getElementById("titre-event").value;
    let teaser = document.getElementById("teaser-event").value;
    let pArray = document.getElementsByClassName("textarea-modif");
    let Array = [];
    for (let i = 0; i < pArray.length; i++) {
      Array[i] = pArray[i].value;
    }
    let eventContent = Array.join("`n");
  
    const res = await fetch("../includes/update-event.php", {
      method: "POST",
      mode: "no-cors", // pour firefox, éviter que ça bloque la requete
      credentials: "same-origin",
      headers: {
       "Content-type": "application/x-www-form-urlencoded",
      },
      referrer: "https://larche.ovh/event.php",
      body: `id=${id}&titre=${titre}&teaser=${teaser}&content=${eventContent}`,
    });
    const s = await res.text();
    if (s == "ok") {
      displayEvent(id);
    } else {
      alert(s);
    }
  }
  