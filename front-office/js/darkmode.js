let darkmode = localStorage.getItem("dark-Mode");

if (darkmode === "true") {
  document.body.classList.add("dark-theme");


}
function darkMode() {
  // let line2 = document.getElementsByClassName("linemode");

  document.body.classList.toggle("dark-theme");
  localStorage.setItem("dark-Mode", document.body.classList.contains("dark-theme"));
  changeDark();
}


let images = document.getElementsByClassName("saturation");
let line = document.querySelectorAll(".linemode");
let mode = document.getElementsByClassName("darkmode");
let mode2 = document.getElementsByClassName("lightmode");
let black = document.getElementById("black");
let white = document.getElementById("white");

function changeModeDark(){
  if (white != null || black != null) {
    white.style.display = "block";
    black.style.display = "none";
  }

  for (let i = 0; i < images.length; i++) {
    let image1 = "/assets/photo de nuit.jfif";
    let image2 = "/assets/Photo nuit.jfif";
    let image3 = "/assets/photo de nuit 3.jfif";
  

    const array = [image1, image2, image3];
    images[i] = images[i].setAttribute("src", array[i]);
  }
  for (let i = 0; i < mode.length; i++) {
    mode[i].style.display = "block";
  }
  for (let i = 0; i < mode2.length; i++) {
    mode2[i].style.display = "none";
  }
  for (let i = 0; i < line.length; i++) {
    line[i].classList.remove("bg-dark", "border-dark");
    line[i].style.backgroundColor = "white";
    line[i].style.border = "white";
  }
  const login = document.querySelector(".login");
  const signup = document.querySelector(".signin");

  if (login !== null) {
    login.style.backgroundImage = 'url("../assets/loup.png")';
  } else if (signup !== null) {
    signup.style.backgroundImage = 'url("../assets/loup.png")';
  }
}
function changeModeLight(){
  if (white != null || black != null) {
    white.style.display = "none";
    black.style.display = "block";
  }

  for (let i = 0; i < images.length; i++) {
    // images[i].style.filter = "saturate(100%)";
    let image1 = "/assets/Champs.webp";
    let image2 = "https://wallpapercave.com/wp/ixmt8JQ.jpg";
    let image3 = "./assets/automne.webp";

    const array = [image1, image2, image3];

    images[i] = images[i].setAttribute("src", array[i]);
  }
  for (let i = 0; i < mode.length; i++) {
    mode[i].style.display = "none";
  }
  for (let i = 0; i < mode2.length; i++) {
    mode2[i].style.display = "block";
  }
  for (let i = 0; i < line.length; i++) {
    line[i].style.backgroundColor = "black";
    line[i].style.border = "black";
  }
  const login = document.querySelector(".login");
  const signup = document.querySelector(".signin");
  if (login !== null) {
    login.style.backgroundImage = 'url("../assets/raton.webp")';
  } else if (signup !== null) {
    signup.style.backgroundImage = 'url("../assets/raton.webp")';
  }
}
function changeDark() {

  if (document.body.classList.contains("dark-theme")) {

      localStorage.removeItem("image");
      localStorage.setItem("image","dark");
      changeModeDark();
    
  } else {

      localStorage.removeItem("image");
      localStorage.setItem("image","light");
      changeModeLight();
  }
}


const inputRadio = document.getElementsByClassName("button-radio");
if (inputRadio.length > 0) {
  if (document.body.classList.contains("dark-theme")) {
    inputRadio[1].checked = "true";
  } else {
    inputRadio[0].checked = "true";
  }
}

function changeApparence (){

  if(inputRadio.length > 0){
    if(inputRadio[0].checked && document.body.classList.contains("dark-theme")){
      document.body.classList.toggle("dark-theme");
      localStorage.setItem(
        "dark-Mode",
        document.body.classList.contains("dark-theme")
      );
      localStorage.removeItem("image");
      localStorage.setItem("image","light");
      for (let i = 0; i < mode.length; i++) {
        mode[i].style.display = "none";
      }
      for (let i = 0; i < mode2.length; i++) {
        mode2[i].style.display = "block";
      }
    } else if(inputRadio[1].checked && !document.body.classList.contains("dark-theme")){
      document.body.classList.add("dark-theme");

      localStorage.setItem(
        "dark-Mode",
        document.body.classList.contains("dark-theme")
      );
      localStorage.removeItem("image");
      localStorage.setItem("image","dark");
      for (let i = 0; i < mode.length; i++) {
        mode[i].style.display = "block";
      }
      for (let i = 0; i < mode2.length; i++) {
        mode2[i].style.display = "none";
      }
    }
  }
  
}
if(localStorage.getItem("image")=="dark"){

  changeModeDark();

} else if(localStorage.getItem("image")=="light"){
  
  changeModeLight();

}


