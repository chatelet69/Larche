<?php
    include("./includes/checksessions.php");
    include("./includes/logHistoric.php");
    if (isset($_GET['q'])) {
      $search = $_GET['q'];
    }elseif (isset($_POST['recherche'])) {
      $search = $_POST['recherche'];
    }

    $srch_user = $bdd -> prepare('SELECT user.user_id, user.pfp, user.username FROM user WHERE user.username LIKE ?');
    $srch_article = $bdd -> prepare('SELECT articles.id_article, articles.picture, articles.title FROM articles WHERE articles.title LIKE ?');
    $srch_event = $bdd -> prepare('SELECT events.id_event, events.image, events.title FROM events WHERE events.title LIKE ?');
  
    $srch_user -> execute(['%' . $search . '%']);
    $srch_article -> execute(['%' . $search . '%']);
    $srch_event -> execute(['%' . $search . '%']);

    $users = $srch_user -> fetchAll(PDO::FETCH_ASSOC);
    $articles = $srch_article -> fetchAll(PDO::FETCH_ASSOC);
    $events = $srch_event -> fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>L'Arche | Recherche</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
  <link rel="stylesheet" type="text/css" href="css/index.css">
</head>

<body class="body-bg-beige body">
  <?php
    include('./includes/header.php');
  ?>
  <main class="bg-search">
    <section class='content-search'>
        <h1 class='title-search'>Résultats pour "<?php echo $search; ?>" : </h1>
        <table class='table-filter'>
          <tr>
            <th>Filtrer par :</th>
            <td onclick="filterSearch('users', '<?php echo $search ?>')">Utilisateurs</td>
            <td onclick="filterSearch('articles', '<?php echo $search ?>')">Articles</td>
            <td onclick="filterSearch('events', '<?php echo $search ?>')">Évènements</td>
            <td onclick="filterSearch('all', '<?php echo $search ?>')">Reset</td>
          </tr>
        </table>
        <div id="schr-filter-content">
<?php
if (!empty($users)) {

echo "<div class='research-box'><h3 class='search-title'>Utilisateurs :</h3>";

for ($i=0; $i < count($users) ; $i++) {
$id = $users[$i]['user_id'];
foreach ($users[$i] as $key => $value) {
    if($key === 'pfp') echo "<div class='user-item'><div class='rchr-pdp'><img src='" . $value . "' alt='pdp profil'></div>";
    if($key === 'username') echo "<p class='search-content'><a href='https://larche.ovh/user?id=" . $id . "'>" . $value . "</a></p></div>";
}
}
echo "</div>";
}
if(!empty($articles)){
echo "<div class='research-box'><h3 class='search-title'>Articles :</h3>";
for ($i=0; $i < count($articles) ; $i++) {
    $id = $articles[$i]['id_article'];
    foreach ($articles[$i] as $key => $value) {
        if($key === 'picture') echo "<div class='user-item'><div class='rchr-img'><img src='" . $value . "' alt='pdp profil'></div>";
        if($key === 'title') echo "<p class='search-content'><a href='https://larche.ovh/article?id=" . $id . "'>" . $value . "</a></p></div>";
    }
}
echo "</div>";
}
if(!empty($events)){
echo "<div class='research-box'><h3 class='search-title'>Evènements :</h3>";
for ($i=0; $i < count($events) ; $i++) {
    $id = $events[$i]['id_event'];
    foreach ($events[$i] as $key => $value) {
        if($key === 'image') echo "<div class='user-item'><div class='rchr-img'><img src='" . $value . "' alt='pdp profil'></div>";
        if($key === 'title') echo "<p class='search-content'><a href='https://larche.ovh/event?id=" . $id . "'>" . $value . "</a></p></div>";
    }
}
echo "</div>";
echo "</div>";
}
if (empty($events) && empty($articles) && empty($users)) {
  echo "<p class='search-content'>Aucun résultat trouvé.</p>";
}
?>
        </div>
    </section>
</main>
<?php
    include('./includes/footer.php');
  ?>
<script src="./js/darkmode.js"></script>
<script src="./js/code.js"></script>

</body>
</html>