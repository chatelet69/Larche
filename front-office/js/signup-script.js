const nom = document.querySelector("#nom");
const prenom = document.querySelector("#prenom");
const age = document.querySelector("#age");
const ville = document.querySelector("#nom");
const email = document.querySelector("#Email");
const desc = document.querySelector("#description1");

nom.addEventListener("keydown", function (event) {
  // crée un évènement sur le input quand il y a un clic sur le /!\clavier/!\

  if (/[^-éèüûàùçôöïäâa-zA-Z]/.test(event.key)) {
    // le / c'est une délimitation et le [^] c'est une négation

    event.preventDefault(); // ça annule l'évènement d'avant si la condition n'est pas respectééééééé
  }
});
prenom.addEventListener("keydown", function (event) {
  // crée un évènement sur le input quand il y a un clic sur le /!\clavier/!\

  if (/[^-éèüûàùçôöïäâa-zA-Z]/.test(event.key)) {
    // le / c'est une délimitation et le [^] c'est une négation

    event.preventDefault(); // ça annule l'évènement d'avant si la condition n'est pas respectééééééé
  }
});
age.addEventListener("keydown", function (event) {
  // crée un évènement sur le input quand il y a un clic sur le /!\clavier/!\

  if (/[^0-9]/.test(event.key)&& event.key !== 'Backspace' && event.key !== 'Delete'){ // il y avait un bug de delete donc j'ai réglé en mettant 
  //ds la condtion qu'il acceptait la touche delete et backspace afin de supprimer une valeur mise
    // le / c'est une délimitation et le [^]d c'est une négation

    event.preventDefault(); // ça annule l'évènement d'avant si la condition n'est pas respectééééééé
  }
});
ville.addEventListener("keydown", function (event) {
  // crée un évènement sur le input quand il y a un clic sur le /!\clavier/!\

  if (/[^-éèüûàùçôöïäâa-zA-Z]/.test(event.key)) {
    // le / c'est une délimitation et le ^ c'est une négation

    event.preventDefault(); // ça annule l'évènement d'avant si la condition n'est pas respectééééééé
  }
});
email.addEventListener("keydown", function (event) {
  // crée un évènement sur le input quand il y a un clic sur le /!\clavier/!\

  if (/[^0-9a-zA-Z@.]/.test(event.key)) {
    // le / c'est une délimitation et le ^ c'est une négation

    event.preventDefault(); // ça annule l'évènement d'avant si la condition n'est pas respectééééééé
  }
});
/*desc.addEventListener("keydown", function (event) {
  // crée un évènement sur le input quand il y a un clic sur le /!\clavier/!\

  if (/[^-_&%:.!?éèüûàùçôöïäâa-zA-Z0-9]/.test(event.key)&& event.key !== 'Backspace' && event.key !== 'Delete' && event.key !== 'SpaceBar'){
    // le / c'est une délimitation et le ^ c'est une négation

    event.preventDefault(); // ça annule l'évènement d'avant si la condition n'est pas respectééééééé
  }
});*/
function changePage() {
  let nom = document.getElementById("nom").value;
  let prenom = document.getElementById("prenom").value;
  let age = document.getElementById("age").value;
  let ville = document.getElementById("ville").value;

  let date = new Date(age);
  let min = new Date();
  min.setFullYear(1910, 0, 1);
  let auj = new Date();
  auj.setHours(0,0,0,0);




  if (
    nom.length === 0 ||
    prenom.length === 0 ||
    age.length === 0 ||
    ville.length === 0
  ) {
    const message = document.getElementById("message1");
    message.style.color = "red";
    message.innerHTML = "Il faut remplir tous les champs.";
  } else if(date > auj){ 
  
    const message = document.getElementById("message1");
    message.style.color = "red";
    message.innerHTML = "Vous ne pouvez pas mettre cette date.";

  } else if (date<min){
    const message = document.getElementById("message1");
    message.style.color = "red";
    message.innerHTML = "Vous n'avez pas l'âge de Monsieur Sananes.";

  }
  else if (
    nom.length !== 0 &&
    prenom.length !== 0 &&
    age.length !== 0 &&
    /*Number.isInteger(age)===true &&*/ ville.length !== 0  
  ) {
    document.getElementById("signup").classList.add("page");
    document.getElementById("signup").classList.remove("appear");
    document.getElementById("signup2").classList.add("appear");
    document.getElementById("signup2").classList.remove("page");
  }

  
}

function changePage2() {
    let email = document.getElementById("Email").value;
    let pwd = document.getElementById("pwd").value;
    let pwdconfirm = document.getElementById("pwdconfirm").value;

    if (email.length === 0 || pwd.length === 0 || pwdconfirm.length === 0) {
        const message1 = document.getElementById("message2");
        message1.style.color = "red"; 
        message1.innerHTML = 'Il faut remplir tous les champs.';
    } else if (pwd !== pwdconfirm) {
        const message2 = document.getElementById("message2");
        message2.style.color = "red";
        message2.innerHTML = 'Il faut que ça soit le même mot de passe.';
    }else if(pwd.length<=6){
        const message3 = document.getElementById("message2");
        message3.style.color = "red";
        message3.innerHTML = 'Votre mot de passe doit être plus long.';
    } else if(pwd.length > 32){
        const message4 = document.getElementById("message2");
        message4.style.color = "red";
        message4.innerHTML = 'Votre mot de passe doit être plus court.'; 
    }
    else if (email.length !== 0 && pwd.length !== 0 && pwdconfirm !== 0 && pwdconfirm === pwd) {
        document.getElementById("signup3").classList.add("appear");
        document.getElementById("signup3").classList.remove("page");
        document.getElementById("signup2").classList.remove("appear");
        document.getElementById("signup2").classList.add("page");
    }

}

function changeType1() {
    const balise = document.getElementById("pwd");
    const eyesclose = document.getElementById("eyeclose");
    const eyes = document.getElementById("eyeopen");    



    if (balise.type === "password") {
        balise.type = "text";
        eyesclose.classList.add("eyesclose");
        eyesclose.classList.remove("eyeschange");
        eyes.classList.add("eyeschange");
        eyes.classList.remove("eyesclose");

    } else {
        balise.type = "password";
        eyesclose.classList.remove("eyesclose");
        eyesclose.classList.add("eyeschange");
        eyes.classList.remove("eyeschange");
        eyes.classList.add("eyesclose");

    }
}

function changeType2() {
  const balise1 = document.getElementById("pwdconfirm");
  const eyesclose2 = document.getElementById("eyeclose2");
  const eyeopen = document.getElementById("eyeopen2");

  if (balise1.type === "password") {
    balise1.type = "text";
    eyesclose2.classList.add("eyesclose");
    eyesclose2.classList.remove("eyeschange");
    eyeopen.classList.add("eyeschange");
    eyeopen.classList.remove("eyesclose");
  } else {
    balise1.type = "password";
    eyesclose2.classList.remove("eyesclose");
    eyesclose2.classList.add("eyeschange");
    eyeopen.classList.remove("eyeschange");
    eyeopen.classList.add("eyesclose");
  }
}
const input = document.querySelector("#pseudo");
input.addEventListener("keydown", function (event) {
  // crée un évènement sur le input quand il y a un clic sur le /!\clavier/!\
  if (/[^-_éèüûàùçôöïäâa-zA-Z0-9]/.test(event.key)) {
    // le / c'est une délimitation et le ^ c'est une négation

    event.preventDefault(); // ça annule l'évènement d'avant si la condition n'est pas respectééééééé
  }
});

function changePage3() {
  let pseudo = document.getElementById("pseudo").value;
  let submit3 = document.getElementById("lastsubmit"); 
  const message3 = document.getElementById("message3");
  if (pseudo.length === 0) {

    message3.style.color = "red";
    message3.innerHTML = "Il faut remplir le champs pseudo.";
  } else if(pseudo.length<4) {
    
    message3.style.color = "red";
    message3.innerHTML = "Le pseudo est trop court.";
  }
  else{
    submit3.type = "submit";  
  }
}

function checkboxActive() {
  const btn = document.querySelector("#checkboxtest");
  const test = document.getElementById("active");

  if (btn === document.querySelector("#checkboxtest")) {
    if (test.checked === true) {
      test.checked = false;
    } else {
      test.checked = true;
    }
  }
}

function changeBack() {
  document.getElementById("signup2").classList.add("page");
  document.getElementById("signup2").classList.remove("appear");
  document.getElementById("signup").classList.add("appear");
  document.getElementById("signup").classList.remove("page");
}

function changeBack2() {
  document.getElementById("signup2").classList.add("appear");
  document.getElementById("signup2").classList.remove("page");
  document.getElementById("signup3").classList.remove("appear");
  document.getElementById("signup3").classList.add("page");
}

//captcha
window.onload = function(){


  const container = document.getElementById("image-container");

  const image = container.querySelector("img"); 
  if(image.width > 600 || image.height > 600){
    image.width = image.width - 300 ;
    image.height = image.height - 300 ;
  }
  if(image.width > 400 || image.height> 400) {
    image.width = image.width - 200 ;
    image.height = image.height - 200 ;
  } else {
  image.width = image.width - 40 ;
  image.height = image.height - 40 ;
  }
  
  // Calcul de la largeur et de la hauteur des parties
  const partWidth = image.width / 3 ; 
  const partHeight = image.height / 3;
  // Création du carré contenant les parties découpées de l'image
  const square = document.createElement("div");
  
  square.classList.add("captcha-container");

  // créer une div
  square.style.width = image.width + "px"; 
  square.style.height = image.height + "px"; 
  square.style.display = "grid"; 
  square.style.gridTemplateColumns = `repeat(3, ${partWidth}px)`;
  square.style.gridTemplateRows = `repeat(3, ${partHeight}px)`; 
  square.style.marginBottom = "30px";

  const parts = [];

  for (let i = 0; i < 9; i++) {

    const part = document.createElement("div");

    part.setAttribute("draggable", true);
    part.setAttribute("data-index", i);
    part.style.width = partWidth + "px"; 
    part.style.height = partHeight + "px"; 
    part.style.backgroundImage = `url(${image.src})`; 
    part.style.backgroundSize = `${image.width}px ${image.height}px`; 
    part.style.backgroundPosition = `-${(i % 3) * partWidth}px -${
    Math.floor(i / 3) * partHeight
}px`;   

  part.addEventListener("dragstart", dragStart); 
  part.addEventListener("touchstart", dragStart); 
  part.addEventListener("dragover", dragOver);
  part.addEventListener("touchmove", dragOver); 
  part.addEventListener("dragenter", dragEnter);
  part.addEventListener("drop", dragDrop); 
  part.addEventListener("dragend", dragEnd); 
  parts.push(part);
  }


  for (let i = 0; i < 9; i++) {
    const j = Math.floor(Math.random() * (i + 1));
    [parts[i], parts[j]] = [parts[j], parts[i]];

    square.appendChild(parts[i]); 
    square.prepend(parts[j]); 

  }

  // Ajout du carré dans le conteneur de l'image
  container.appendChild(square);


  // Variables globales pour le glisser-déposer
  let currPart;
  let otherPart;

  //Drag function
  function dragStart() {
    currPart = this; 
  }

  function dragOver(e) {
    e.preventDefault();
  }

  function dragEnter(e) {
    
  }

  function dragDrop() {
    otherPart = this; 


    let currIndex = parts.indexOf(currPart);
    let otherIndex = parts.indexOf(otherPart);

    if (currIndex !== otherIndex) {

      parts.splice(currIndex, 1, otherPart); 
      parts.splice(otherIndex, 1, currPart); 

      for (let i = 0; i < parts.length; i++) {
        //boucle qui permet de remettre après chaque carré
        square.appendChild(parts[i]);
          

      }
      // Vérification de l'ordre des images
      let isCorrectOrder = true; 
      for (let i = 0; i < parts.length; i++) {
        if (parseInt(parts[i].getAttribute("data-index")) !== i) {
  
          isCorrectOrder = false; 
          break;
        }
      }
      if (isCorrectOrder) {
        // Toutes les images sont dans le bon ordre
        document.getElementById("lastsubmit").disabled; 
        document.getElementById("lastsubmit").disabled = false; 
        const captchaAlert = document.getElementById("message3");
        captchaAlert.style.color = "green";
        captchaAlert.fontWeight = "bold";
        captchaAlert.innerHTML = "Vous avez réussi le captcha !";
      } 
    }
  }

  function dragEnd() {
    let currImg = currPart.style.backgroundImage;
    let otherImg = otherPart.style.backgroundImage;

    currPart.style.backgroundImage = otherImg;
    otherPart.style.backgroundImage = currImg;
  }


};

