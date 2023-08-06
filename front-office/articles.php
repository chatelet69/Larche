<?php
$title = "articles";
    include('./includes/checksessions.php');
    include("./includes/logHistoric.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création Article</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/article.css">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
</head>
<body class="bgArticles body">
    <?php
    include('includes/header.php');
    ?>
    <div class="accueil-title">
      <div class='barres'></div>
      <h2 class="text-index">Articles</h2>
      <div class='barres'></div>
    </div>

<table  class="filter-article">
    <tbody>
    <tr>
        <th class='filtrer-par'>Filtrer par :</th>
        <td><button class='filter filter-follow' onclick="FilterFollowers()">Abonnements</button></td>
        <td><button class='filter animals' onclick="DisplayArticles('Animals')">Animaux</button></td><!-- Fetch, quand on clique ça display actualise ce qu'on cherche-->
        <td><button class='filter jardinerie' onclick="DisplayArticles('Jardinerie')">Jardinerie</button></td>
        <td><button class='filter autre' onclick="DisplayArticles('Autre')">Autre</button></td>
        <td><button class='filter all' onclick="DisplayArticles('')">Supprimer le filtre</button></td>
    </tr>
</tbody>
</table>

<main id="articlesmain"> 
        <?php
        try {
            include("/var/www/confc.php");
            $bdd = new PDO("mysql:host=" . $dbhost . ";dbname=" . $dbname . "", $dbuser, $dbpass); // Requête de connexion à la DB
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
        $selectArticles = $bdd->prepare('SELECT id_article, picture, `date`, user_id_author, username_author, title, teaser FROM articles');
        $selectArticles->execute();
        $selectArticles = $selectArticles->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($selectArticles)) {
        for ($i = 0; $i < count($selectArticles); $i = $i + 1) {
            foreach ($selectArticles[$i] as $key => $value) {
                    if($key === "id_article") echo '<div class="article-box"><a href="https://larche.ovh/article?id=' . $value . '">';
                    if($key === "picture") echo '<div class="container-img"><img src="' . $value .'" alt="image d\'illustration article" class="img-article"></div></a>';
                    if($key === "date"){ 
                        $date = explode('-', $value);
                        $end = end($date);
                        $date[2] = $date[0];
                        $date[0] = $end;
                        $date = implode(' / ', $date);
                        echo '<p class="article-infos"> Le ' . $date . ' par ';
                    }
                    if($key === "user_id_author") echo '<a href="https://larche.ovh/user?id=' . $value . '" class="profil-visit">';
                    if($key === "username_author") echo $value . '</a>.</p>';

                    if($key === "title") echo '<div class="h3-container"><h3>' . $value . '</h3></div>';
                    if($key === "teaser"){ 
                        echo '<div class="content-box"><p class="text-no-change">' . $value . '</p></div>';

                        $checkLike = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ? AND user_id = ?');
                        $checkLike->execute([$selectArticles[$i]['id_article'], $_SESSION['user_id']]);
                        $checkLike = $checkLike->fetchAll(PDO::FETCH_ASSOC);
    
                        $nbLike = $bdd->prepare('SELECT id_article FROM articles_likes WHERE id_article = ?');
                        $nbLike->execute([$selectArticles[$i]['id_article']]);
                        $nbLike = $nbLike->fetchAll(PDO::FETCH_ASSOC);
                        echo '<div class="likes"><p class="likeArticle text-no-change" id="' . $selectArticles[$i]['id_article'] . '">' . count($nbLike) . '</p>';
                        if (!empty($checkLike)) {
                            echo '<button class="button-like" onclick="LikeArticle(' . $selectArticles[$i]['id_article'] . ')"><img src="./assets/like.png" alt="likes" id="like' . $selectArticles[$i]['id_article'] . '" class="liked coeur"></button></div></div>';
                        }else{
                            echo '<button class="button-like" onclick="LikeArticle(' . $selectArticles[$i]['id_article'] . ')"><img src="./assets/like.png" alt="likes" id="like' . $selectArticles[$i]['id_article'] . '" class="coeur"></button></div></div>';
                        }
                    }
            }
        }
    }else{
        echo "<div class='no-article'><img src='./assets/hibou.jpg' alt='hibou triste'</img>";
        echo "<p>Il n'y a aucun article disponible...</p></div>";
    }
        ?>
</main>
<?php
echo "<div class='bouton'>";
    if ($_SESSION['lvl_perms']==1) {
        echo "<a href='https://larche.ovh/new-ticket?obj=contrib' class='colorbutton'>Devenir contributeur</a>";
    }
    if($_SESSION['lvl_perms'] >1){
    echo "<a href='https://larche.ovh/createarticle' class='colorbutton'>Créer un article</a>";
    }
echo "</div>";
?>
    
    <?php
    include('includes/footer.php');
    ?>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
    <script> 
 
    async function DisplayArticles(category){
	const res = await fetch('./includes/filter-article.php?category=' + category);
	const str = await res.text();
	const mainArticle = document.getElementById('articlesmain');
	mainArticle.innerHTML = str;
}

    async function FilterFollowers(){
        const res = await fetch('./includes/filter-followers.php');
        const str = await res.text();
        const mainArticle = document.getElementById('articlesmain');
        mainArticle.innerHTML = str;
    }
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