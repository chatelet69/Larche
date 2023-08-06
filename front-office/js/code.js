function Swap(){
    let button = document.getElementsByClassName("change");
    if(button.style.display=="None"){
        button.style.display="block";
    }else if(button.style.display=="block"){
        button.style.display="None";
    }
}
/*
function PopUpDisplay(event_id){
    console.log("ok1")
    let popup = document.getElementById("popup")
    document.getElementById("event-id").value = event_id
    if(popup.style.display=="block"){
        popup.style.display = "none"
    }else{
        popup.style.display = "block"
        event =
    }
    
}
*/
function listFollow(){
    follows = document.getElementById("abonnements")
    followers = document.getElementById("abonnés")
    if(follows.style.display=="none"){
        follows.style.display="block"
        followers.style.display="none"
    }else{
        follows.style.display="block"
        followers.style.display="none"
    }
}
function listFollowers(){
    followers = document.getElementById("abonnés")
    follows = document.getElementById("abonnements")
    if(followers.style.display=="none"){
        followers.style.display="block"
        follows.style.display="none"
    }
}

function infoEvent(event_id){
    event_id = document.getElementById(event_id);
    if(event_id.style.display=="none"){
        event_id.style.display="flex"
    }else{
        event_id.style.display="none"
    }
}

function closeDiv(){
    followers = document.getElementById("abonnés")
    follows = document.getElementById("abonnements")
    if(followers.style.display=="block" || follows.style.display=="block" ){
        followers.style.display="none"
        follows.style.display="none"
    }
}

async function followUser(id) {
    const res = await fetch('../includes/followuser.php', {
        method: 'POST',
        mode: 'no-cors', // pour firefox, éviter que ça bloque la requete
        credentials: "same-origin",
        headers: {
            "Content-type": "application/x-www-form-urlencoded",
        },
        referrer: "https://larche.ovh/user.php",
        body: `id=${id}` // les acolades indique qu'on concatene une variable, ici id se recuperera avec post 
    });
    const str = await res.text();
    let followButton = document.getElementById("follow"+id);
    if (followButton.classList.contains("sub")) {
        followButton.classList.remove("sub");
        followButton.classList.add("unsub");
        followButton.innerHTML = "S'abonner";

    }else if(followButton.classList.contains("unsub")){
        followButton.classList.remove("unsub");
        followButton.classList.add("sub");
        followButton.innerHTML = "Se desabonner";
    }
    const abos = document.getElementById("abos"+id);
    abos.innerHTML = str;
}

function menuderoulant() {
    const div = document.getElementById("menuderoulant");
    if (div.style.display === "block") {
        div.style.display = "none";
    } else {
        div.style.display = "block";
    }

}
function searchOut(){
    let links = document.getElementsByClassName("headerlinks") // liens navbar
    let search = document.getElementById("recherche") // barre moteur de recherhce
    let divSearch =document.getElementById("search") // barre recherche + loupe
    let header =document.getElementById("header") // header
    for (let i = 0; i < links.length; i++) {
        links[i].style.display="inline-block"; // re affiche les liens
    }
    header.style.position = ""; // on repasse le header en display flex
    divSearch.style.position=""; // on repasse la div en position relative
    search.style.width = ""; // repasse la barre de recherche à 40vw
    const affichage= document.getElementById("research-box") // affichage des resultats
    affichage.style.display="none"; // cache les resultats
}
async function research() {
    let links = document.getElementsByClassName("headerlinks") // liens de la navbar
    let barresearch = document.getElementById("recherche") // barre de recherche sans la loupe
    let divSearch =document.getElementById("search") // barre de recherche avec la loupe
    let header =document.getElementById("header") // header
    const fenetre= document.getElementById("research-box") // résultats
    for (let i = 0; i < links.length; i++) {
        links[i].style.display="none"; // fait disparaitre les liens
    }
    if (window.innerWidth>428) {
        header.style.position = "relative"; // met le header en relative pour bien placer la barre
        divSearch.style.position="absolute"; // met la barre en absolute pour bien la mettre au milieu
        barresearch.style.width = "40vw"; // augmente la largeur de la barre sans affecter la loupe
    }
    fenetre.style.display="flex"; // si les résultats
    fenetre.style.position="absolute";
    fenetre.style.zIndex="2";
    fenetre.style.borderRadius="0% 0% 10px 10px";
    fenetre.style.boxShadow="0px 4px 4px var(--color-opacity-black25)";
    const input = document.getElementById("recherche")
    const search= input.value
    const res = await fetch('./includes/research.php?recherche=' + search)
    const str = await res.text()
    const affichage= document.getElementById("research-box")
    affichage.innerHTML=str
    if (input.value =='') {
        searchOut();
    }
}

document.addEventListener("mouseup", function(event) {
var obj = document.getElementById("research-box");
if (!obj.contains(event.target)) {
    searchOut();
}
});

async function filterSearch(cat, rech){
    let content = document.getElementById("schr-filter-content");
    let filtre = await fetch("./includes/filter-search.php?rech=" + rech + "&cat=" + cat);
    let s = await filtre.text();
    content.innerHTML= s;
}

async function filterEventProfil(type){
    let content = document.getElementById("list-articles");
    let filtre = await fetch("./includes/filter-event.php?type=" + type);
    let s = await filtre.text();
    content.innerHTML= s;
}


function burgerMenu(){
    const burger = document.querySelector('.burger')
    burger.classList.toggle('active');
    let nav = document.getElementsByClassName('navchoix')
    nav[0].classList.toggle('active');
}