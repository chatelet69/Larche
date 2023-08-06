function editArticle() {
  let input = document.getElementsByClassName("modify");
  let buttonModify = document.getElementById("buttonModify");
  let i = 0;
  if (input[i].style.display == "block") {
      buttonModify.innerHTML =
      "Modifier l'article <img src='./assets/edit.png' alt='crayon modif article'>";
      document.getElementById("btnValidBtn").style.display = "none";
      for (i = 0; i < input.length; i++) {
        input[i].style.display = "none";
      document.getElementById(i + "-article").style.display = "block";
    }
  } else {
    for (let i = 0; i < input.length; i++) {
      input[i].style.display = "block";
      input[i].value = document.getElementById(i + "-article").innerHTML;
      document.getElementById(i + "-article").style.display = "none";
    }
    buttonModify.innerHTML = "Annuler les modifications";
    document.getElementById("btnValidBtn").style.display = "block";
  }
}

async function displayArticle(id) {
  const res = await fetch("../includes/display-article.php?id=" + id);
  const s = await res.text();
  let container = document.getElementById("article-container");
  container.innerHTML = s;
}

async function validModif(id) {
  let titre = document.getElementById("titre-article").value;
  let teaser = document.getElementById("teaser-article").value;
  let pArray = document.getElementsByClassName("textarea-modif");
  let Array = [];
  for (let i = 0; i < pArray.length; i++) {
    Array[i] = pArray[i].value;
  }
  let articleContent = Array.join("`n");

  const res = await fetch("../includes/update-article.php", {
    method: "POST",
    mode: "no-cors", // pour firefox, éviter que ça bloque la requete
    credentials: "same-origin",
    headers: {
     "Content-type": "application/x-www-form-urlencoded",
    },
    referrer: "https://larche.ovh/article.php",
    body: `id=${id}&titre=${titre}&teaser=${teaser}&content=${articleContent}`,
  });
  const s = await res.text();
  if (s == "ok") {
    displayArticle(id);
  } else {
    alert(s);
  }
}

document.getElementById("inputComment").addEventListener("keypress", function(press) {
  if (press.key === "Enter") {
      press.preventDefault();
      document.getElementById("btn-send-comment").click();
      document.getElementById('inputComment').value = '';
  }
})

async function displayComment(id) {
  const res = await fetch("../includes/display-comments.php?id=" + id);
  const s = await res.text();
  let container = document.getElementById("comment-container");
  container.innerHTML = s;
}

async function sendComment(id){
  let content = document.getElementById('inputComment').value;
  const res = await fetch("../includes/send-comment.php", {
    method: "POST",
    mode: "no-cors", // pour firefox, éviter que ça bloque la requete
    credentials: "same-origin",
    headers: {
     "Content-type": "application/x-www-form-urlencoded",
    },
    referrer: "https://larche.ovh/article.php",
    body: `id=${id}&content=${content}`,
  });
  const s = await res.text();
  if (s == "ok") {
    displayComment(id);
  } else {
    alert(s);
  }
}

async function delComment(idArticle, idComment){
  const res = await fetch('../includes/del-comment.php', {
    method: "POST",
    mode: "no-cors", // pour firefox, éviter que ça bloque la requete
    credentials: "same-origin",
    headers: {
     "Content-type": "application/x-www-form-urlencoded",
    },
    referrer: "https://larche.ovh/article.php",
    body: `id=${idComment}`,
  })
  const s = await res.text();
  if (s === 'ok') {
    displayComment(idArticle);
  }else{
    alert(s);
  }
}

async function likeComment(id){
  let img =document.getElementById("like-comment-"+id);
  if (img.classList.contains("liked")) {
      img.classList.remove("liked");
  }else{
      img.classList.add("liked");
  }
const res = await fetch('./includes/like-comment.php',{
  method: "POST",
    mode: "no-cors", // pour firefox, éviter que ça bloque la requete
    credentials: "same-origin",
    headers: {
     "Content-type": "application/x-www-form-urlencoded",
    },
    referrer: "https://larche.ovh/article.php",
    body: `id=${id}`,
});
const str = await res.text();
const likes = document.getElementById("nb-like-comment-" + id);
likes.innerHTML = str;
}