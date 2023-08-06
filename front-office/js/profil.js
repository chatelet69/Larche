function profilModif() {
  const info = document.querySelectorAll(".ptoform");
  const desc = document.querySelectorAll(".ptoarea");
  const inputModif = document.getElementsByClassName("modif");
  const inputRegister = document.getElementsByClassName("display-profil");
  const mdp = document.getElementById("changemdp");

  const arrayinput = ["email", "username", "ville", "nom", "prenom"];

  inputModif[0].style.display = "none";

  for (let i = 0; i < inputRegister.length; i++) {
    inputRegister[i].style.display = "flex";

    inputRegister[i].type = "submit";
  }
  descinfo = desc[0].textContent;


  

  for (let i = 0; i < info.length; i++) {
    const input = document.createElement("input"); 
    input.setAttribute("type", "text"); 
    input.setAttribute("name", arrayinput[i]);
    input.setAttribute("value", info[i].textContent); 

    input.className = "input-profil";
    info[i].parentNode.replaceChild(input, info[i]); 
  }

  for (let i = 0; i < desc.length; i++) {
    const descInput = document.createElement("textarea"); //crée un élèment input
    descInput.setAttribute("name", "description");
    descInput.value = descinfo;
    // permet de garder la valeur de la balise <p>
    //pck si on ne met pas alorsl'input aura aucune valeur, l'input sera vide
    descInput.className = "desc-textarea";
    descInput.style.minHeight = "80px";
    descInput.style.maxHeight = "80px";
    descInput.style.minWidth = "420px";
    descInput.style.maxWidth = "420px";
    descInput.style.width = "100%";
    desc[i].parentNode.replaceChild(descInput, desc[i]); // pour accèder à l'élèment parent d'info[i]
    // puis prendre l'enfant du parent et le remplacer par newInputs
  }

  mdp.style.display="flex";
  mdp.style.justifyContent ="center";
}

const nav = document.getElementsByClassName("alldiv");
const textbutton = document.getElementsByClassName("nav-settings");
const text = document.getElementsByClassName("text-settings");
const profil = document.getElementById("profil");
const cookies = document.getElementById("cookies");
const apparence = document.getElementById("apparence");



text[0].innerHTML = "INFORMATIONS DU COMPTE";



profil.style.display = "flex";
cookies.style.display = "none";
apparence.style.display = "none";

localStorage.setItem("profilDisplay", "flex");
localStorage.setItem("cookiesDisplay", "none");
localStorage.setItem("apparenceDisplay", "none");

nav[0].addEventListener("click", function (event) {
  nav[1].classList.remove("div-color");
  nav[0].classList.add("div-color");
  nav[2].classList.remove("div-color");

  textbutton[0].style.color = "white";
  textbutton[1].style.color = "black";
  textbutton[2].style.color = "black";

  text[0].innerHTML = "INFORMATIONS DU COMPTE";

  profil.style.display = "flex";
  cookies.style.display = "none";
  apparence.style.display = "none";

  localStorage.removeItem("profilDisplay");
  localStorage.setItem("profilDisplay", "flex");

  localStorage.removeItem("cookiesDisplay");
  localStorage.setItem("cookiesDisplay", "none");

  localStorage.removeItem("apparenceDisplay");
  localStorage.setItem("apparenceDisplay", "none");

});
nav[1].addEventListener("click", function (event) {
  nav[0].classList.remove("div-color");
  nav[1].classList.add("div-color");
  nav[2].classList.remove("div-color");

  textbutton[1].style.color = "white";
  textbutton[0].style.color = "black";
  textbutton[2].style.color = "black";

  text[0].innerHTML = "DONNÉES";

  profil.style.display = "none";
  cookies.style.display = "flex";
  apparence.style.display = "none";

  localStorage.removeItem("profilDisplay");
  localStorage.setItem("profilDisplay", "none");

  localStorage.removeItem("cookiesDisplay");
  localStorage.setItem("cookiesDisplay", "flex");

  localStorage.removeItem("apparenceDisplay");
  localStorage.setItem("apparenceDisplay", "none");


});

nav[2].addEventListener("click", function (event) {
  nav[0].classList.remove("div-color");
  nav[2].classList.add("div-color");
  nav[1].classList.remove("div-color");

  textbutton[2].style.color = "white";
  textbutton[0].style.color = "black";
  textbutton[1].style.color = "black";

  text[0].innerHTML = "APPARENCE";

  profil.style.display = "none";
  cookies.style.display = "none";
  apparence.style.display = "flex";

  localStorage.removeItem("profilDisplay");
  localStorage.setItem("profilDisplay", "none");

  localStorage.removeItem("cookiesDisplay");
  localStorage.setItem("cookiesDisplay", "none");

  localStorage.removeItem("apparenceDisplay");
  localStorage.setItem("apparenceDisplay", "flex");


});

function changeSettings() {
  const btn = document.querySelector(".delete-confirm");
  let input = document.getElementsByClassName("input-delete");
  let input2 = input[0].value;

  if (input2.length === 0) {
    const message = document.getElementById("msg-delete");
    message.style.color = "red";
    message.innerHTML = "Il faut remplir le champs suivant :";
  } else if (input2.length > 0) {

    btn.removeAttribute("type");
    btn.setAttribute("type", "submit");
  }
}

function showPopupMdp(){
  document.querySelector(".popup-bg2").style.display = "block";
  document.querySelector(".popupmdp").style.display = "block";

}

function hidePopupMdp() {
  const hide = document.querySelector(".popup-bg2");
  const mdp = document.querySelector(".popupmdp");
  hide.style.display = "none";
  mdp.style.display = "none";
}


function mdpconfirm() {
  const btn2 = document.querySelector(".delete-confirm2");
  let inputmdp = document.getElementsByClassName("input-delete2");
  let inputmdp2 = inputmdp[0].value;
  let inputconfirm = inputmdp[1].value;
  const message2 = document.getElementById("msg-delete2");
  message2.style.color = "red";
  if (inputmdp2.length === 0 || inputconfirm.length === 0) {

    message2.innerHTML = "Il faut remplir tous les champs";
  } else if(inputmdp2.value != inputconfirm.value){
    message2.innerHTML= "Il faut que ça soit le même mot de passe !";
  } else if (inputmdp2.length < 6){ 
    message2.innerHTML= "Il est trop court !";

  } else if(inputmdp2.length > 32){
    message2.innerHTML= "Il est trop long !";
  }
  else {

    btn2.removeAttribute("type");
    btn2.setAttribute("type", "submit");
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