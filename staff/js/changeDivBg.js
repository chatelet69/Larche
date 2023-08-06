let page = window.location.pathname.split("/").pop();
let div = document.getElementById(`${page}`);
div.classList.add("active");

// On récupère le nom de la page ou on est, et on ajoute à la div avec l'id de la page la classe active
// C'est pour mettre un bg bleu sur le header au bouton de la page ou on est actuellement