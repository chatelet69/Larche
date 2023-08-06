function showPopupBanniere() {
    document.querySelector(".popup-bg").style.display = "block";
    document.querySelector(".popupbanniere").style.display = "block";
  }
  
  function hidePopupBanniere() {
    const hide = document.querySelector(".popup-bg");
    const message = document.querySelector(".popupbanniere");
    hide.style.display = "none";
    message.style.display = "none";
  }
  
  function changePDP() {
    const pdp = document.getElementById("pdp-settings");
    const msg = document.getElementById("msg-delete");
    const button = document.getElementById("confirm-settings-pdp");
  
    const filepdp = pdp.value;
  
    if (pdp.files.length === 0) {
      msg.style.color = "red";
      msg.innerHTML = "Il faut ajouter un fichier.";
    } else if (filepdp.indexOf(".png") === -1) {
      msg.style.color = "red";
      msg.innerHTML = "Il faut un fichier png.";
    } else {
      button.type = "submit";
    }
  }
  
  function changeBanniere() {
    const banniere = document.getElementById("banniere-settings");
    const msg2 = document.getElementById("msg-delete2");
    const button2 = document.getElementById("confirm-settings-banniere");
  
    const filebanniere = banniere.value ;
  
    if (banniere.files.length === 0) {
      msg2.style.color = "red";
      msg2.innerHTML = "Il faut ajouter un fichier.";
    } else if (filebanniere.indexOf(".jpg") === -1 && filebanniere.indexOf(".png") === -1 ) {
      msg2.style.color = "red";
      msg2.innerHTML = "Il faut un fichier jpg ou png.";
    }else if (filebanniere.indexOf(".jpg") === -1 && filebanniere.indexOf(".png") !== -1 ) {
      button2.type = "submit";
    } else if (filebanniere.indexOf(".jpg") !== -1 && filebanniere.indexOf(".png") === -1 ) {
      button2.type = "submit";
    } else {
      button2.type = "submit";
    }
  }

  function showPopup() {
    document.querySelector(".popup-bg").style.display = "block";
    document.querySelector(".popup").style.display = "block";
  }
  
  function hidePopup() {
    const hide = document.querySelector(".popup-bg");
    const message = document.querySelector(".popup");
    hide.style.display = "none";
    message.style.display = "none";
  }
  