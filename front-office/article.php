<?php
$title = "articles";
    include("./includes/checksessions.php");
    include("./includes/logHistoric.php");
    
    $id = $_GET['id'];

  $articleInfo = $bdd -> prepare('SELECT content, picture, `date`, user_id_author, username_author, name_category, title, teaser FROM articles WHERE id_article = ?');
  $articleInfo -> execute([$id]);
  $articleInfo = $articleInfo -> fetchAll(PDO::FETCH_ASSOC);
  
  if (empty($articleInfo)) {
    header('location: ./error404.html');
    exit;
}

  $nbLike = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ?');
  $nbLike->execute([$id]);
  $nbLike = $nbLike->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>L'Arche | Articles</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/displayarticle.css">
</head>

<body class="body-bg-beige body">
  <?php
    include('./includes/header.php');
  ?>

<main class="bg-article">
  <div class='article-content'>
    <div class="article-container" id='article-container'>
      <?php
        include('./includes/display-article.php');
      ?>
      </div>
    <div class="article-suggest">
      <h2>Articles récents :</h2>
      <?php
        $suggest = $bdd -> prepare('SELECT id_article, picture, title FROM articles WHERE name_category = ? AND id_article != ? ORDER BY `date` LIMIT 3');
        $result = $suggest -> execute([$articleInfo[0]['name_category'], $_GET['id']]);
        $suggest = $suggest -> fetchALL(PDO::FETCH_ASSOC);

        for ($i=0; $i <count($suggest); $i++) { 
        $id = $suggest[$i]['id_article'];
        echo "<div class='article-suggest-box'>";
        foreach ($suggest[$i] as $key => $value) {
          if($key === 'picture') echo "<a href='https://larche.ovh/article?id=" . $id . "'><div class='article-suggest-img-box'><img src='" . $value . "' alt='image article'></div></a>";
          if($key === 'title') echo "<h4>" . $value . "</h4>";
        }
        echo "</div>";
      }
      ?>
    </div>
  </div>
  <div class="comment-content">
    <h2>Commentaire :</h2>
    <div class='send-comment'>
      <input type="text" id='inputComment' placeholder="Écrire un commentaire... (255 caractères maximum)" class="grey-input">
      <img src="./assets/send.png" alt="bouton envoyer" id='btn-send-comment' onclick="sendComment(<?php echo $id; ?>)">
    </div>
    <div class='comment-container' id="comment-container">
        <?php
          include('./includes/display-comments.php');
        ?>
    </div>
  </div>
</main>

  <?php
    include('./includes/footer.php');
  ?>


<script src="./js/darkmode.js"></script>
<script src="./js/editArticle.js"></script>
<script src="./js/code.js"></script>
<script> 
async function LikeArticle(id){
    let img =document.getElementById("like"+id);
    if (img.classList.contains("liked")) {
        console.log('like');
        img.classList.remove("liked");
    }else{
        console.log('liked');
        img.classList.add("liked");
    }
	const res = await fetch('./includes/like-article.php?id=' + id);
	const str = await res.text();
	const likes = document.getElementById(id);
	likes.innerHTML = str;
}
</script>


</body>
</html>