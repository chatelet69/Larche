<?php
if (!isset($bdd)) {
  include("./checksessions.php");
  include("./logHistoric.php");

  $id = $_GET['id'];
  $articleInfo = $bdd->prepare('SELECT content, picture, `date`, user_id_author, username_author, name_category, title, teaser FROM articles WHERE id_article = ?');
  $articleInfo->execute([$id]);
  $articleInfo = $articleInfo->fetchAll(PDO::FETCH_ASSOC);

  if (empty($articleInfo)) {
    echo 'Erreur';
    exit;
  }

  $nbLike = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ?');
  $nbLike->execute([$id]);
  $nbLike = $nbLike->fetchAll(PDO::FETCH_ASSOC);
}

echo "<div class='article-infos'>
      <div class='likes'>";
$checkLike = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ? AND user_id = ?');
$checkLike->execute([$id, $_SESSION['user_id']]);
$checkLike = $checkLike->fetchAll(PDO::FETCH_ASSOC);
if (!empty($checkLike)) {
  echo '<button class="button-like" onclick="LikeArticle(' . $id . ')"><img src="./assets/like.png" alt="likes" id="like' . $id . '" class="liked coeur"></button>';
} else {
  echo '<button class="button-like" onclick="LikeArticle(' . $id . ')"><img src="./assets/like.png" alt="likes" id="like' . $id . '" class="coeur"></button>';
}
echo '<p class="likeArticle text-no-change" id="' . $id . '">' . count($nbLike) . '</p>';
echo "</div>
      <p class='" . ($articleInfo[0]['name_category'] == 'Jardinerie' ? 'jardinerie' : $articleInfo[0]['name_category']) . "'>" . ($articleInfo[0]['name_category'] == 'animals' ? 'Animaux' : $articleInfo[0]['name_category']) . "</p>
      <p class='ticket-report'><a href='https://larche.ovh/new-ticket?obj=reportArticle&id=" . $id . "' alt=''>Signaler l'article</a></p>
    </div>";

if ($articleInfo[0]['user_id_author'] == $_SESSION['user_id']) {
  echo "<div id='buttonModify' class='modifArticle' onclick='editArticle()'>Modifier l'article <img src='./assets/edit.png' alt='crayon modif article'></div>";
}
echo "<h1 id='0-article' class='titre-article'>" . $articleInfo[0]['title'] . "</h1><input id='titre-article' type='text' class='titre-article modify' name='title' style='display: none;'>";
echo "<h2 id='1-article' class='teaser-article'>" . $articleInfo[0]['teaser'] . "</h2><input id='teaser-article' type='text' class='teaser-article modify' name='teaser' style='display: none;'>";
$pfp_author = $bdd->prepare('SELECT pfp FROM user WHERE user_id = ?');
$pfp_author->execute([$articleInfo[0]['user_id_author']]);
$pfp_author = $pfp_author->fetchALL(PDO::FETCH_ASSOC);
echo "<div class='creator'><p>Par <a href='https://larche.ovh/user?id=" . $articleInfo[0]['user_id_author'] . "' class='profil-visit'>" . $articleInfo[0]['username_author'] . "</a></p><a href='https://larche.ovh/user?id=" . $articleInfo[0]['user_id_author'] . "' class='profil-visit'><img src='" . $pfp_author[0]['pfp'] . "' alt='pdp auteur'></div></a>";

$date = explode('-', $articleInfo[0]['date']);
$end = end($date);
$date[2] = $date[0];
$date[0] = $end;
$date = implode(' / ', $date);
echo "<p class='date-publi'>Publié le " . $date . ".</p>";
echo "<div class='article-picture-box'><img src='" . $articleInfo[0]['picture'] . "' alt='image article' class='image-article'></div>";

$contentArticle = explode('`n', $articleInfo[0]['content']);
for ($i = 0; $i < count($contentArticle); $i++) {
  echo "<p id='" . $i + 2 . "-article'>" . $contentArticle[$i] . "</p><textarea type='text' class='modify textarea-modif' name='content' style='display: none;'></textarea><br>";
}

echo "<button type='button' onclick='validModif(" . $id . ")' class='colorbutton btnValidBtn' style='display: none' id='btnValidBtn'>Valider les modifications</button>";