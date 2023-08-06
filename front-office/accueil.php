<?php
$title = "accueil";
include('./includes/checksessions.php');
include("./includes/logHistoric.php");

$eventinfos = $bdd->prepare('SELECT id_event, title, teaser, image, date_event FROM events WHERE date_event >= CURRENT_DATE ORDER BY date_event LIMIT 5');
$eventinfos->execute();
$eventinfos = $eventinfos->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <title>L'Arche | Accueil</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/accueil.css">
</head>

<body class="body-bg-beige body">
  <?php
  include('./includes/header.php');
  ?>
  <main>
    <div id='meteo-box' style="display: none;">
    <?php
      include('./includes/meteo.php');
    ?>
    </div>
    <div class="accueil-title accueil-title-event">
      <div class='barres'></div>
      <h2 class="text-index">Évènements</h2>
      <div class='barres'></div>
    </div>
    <div class="accueil-event">
      <div id="carouselExampleCaptions" class="carousel slide">
        <div class="carousel-indicators">
          <?php
          if (count($eventinfos) >= 1) {
            echo "<button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='0' class='active' aria-current='true' aria-label='Slide 1'></button>";
          }
          if (count($eventinfos) >= 2) {
            echo "<button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='1' aria-label='Slide 2'></button>";
          }
          if (count($eventinfos) >= 3) {
            echo "<button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='2' aria-label='Slide 3'></button>";
          }
          if (count($eventinfos) >= 4) {
            echo "<button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='3' aria-label='Slide 4'></button>";
          }
          if (count($eventinfos) >= 5) {
            echo "<button type='button' data-bs-target='#carouselExampleCaptions' data-bs-slide-to='4' aria-label='Slide 5'></button>";
          }
          ?>
        </div>
        <?php
        if (count($eventinfos) == 0) {
          echo "<div class='carousel-inner accueil-inner'>";
          echo "<div class='carousel-item item-car active'>";
          echo "<img src='./assets/chiensquipleurent.jpg' class='d-block w-100' alt='image event recent'>";
          echo "<div class='carousel-caption d-none d-md-block bg-event-info'>";
          echo "<h5> Il n'y a pas d'évènements de prévu...</h5>";
          echo "<p class='desc-event-accueil'>Devenez contributeur pour pouvoir en créer un.</p>";
          if ($_SESSION['lvl_perms'] > 1) {
            echo "<a href='https://larche.ovh/eventcreation' class='colorbutton'>Créer un évènement</a>";
          } elseif ($_SESSION['lvl_perms'] == 1) {
            echo "<a href='https://larche.ovh/new-ticket?obj=contrib' class='colorbutton'>Devenir contributeur</a>";
          }
          echo "</div>";
          echo "</div>";
        }
        if (count($eventinfos) >= 1) {
          echo "<div class='carousel-inner accueil-inner'>";
          echo "<a href='https://larche.ovh/event?id=" . $eventinfos[0]['id_event'] . "'>";
          echo "<div class='carousel-item item-car active'>";
          echo "<img src='" . $eventinfos[0]['image'] . "' class='d-block w-100' alt='image event recent'>";
          echo "<div class='carousel-caption d-none d-md-block bg-event-info'>";
          echo "<h5>" . $eventinfos[0]['title'] . "</h5>";
          echo "<p>" . $eventinfos[0]['teaser'] . "</p>";
          echo "</div>";
          echo "</a>";
          echo "</div>";
        }
        ?>
        <?php
        if (count($eventinfos) >= 2) {
          echo "<div class='carousel-item item-car'>";
          echo "<a href='https://larche.ovh/event?id=" . $eventinfos[1]['id_event'] . "'>";
          echo "<img src='" . $eventinfos[1]['image'] . "' class='d-block w-100' alt='image event recent'>";
          echo "<div class='carousel-caption d-none d-md-block bg-event-info'>";
          echo "<h5>" . $eventinfos[1]['title'] . "</h5>";
          echo "<p>" . $eventinfos[1]['teaser'] . "</p>";
          echo "</div>";
          echo "</a>";
          echo "</div>";
        }
        ?>

        <?php
        if (count($eventinfos) >= 3) {
          echo "<div class='carousel-item item-car'>";
          echo "<a href='https://larche.ovh/event?id=" . $eventinfos[2]['id_event'] . "'>";
          echo "<img src='" . $eventinfos[2]['image'] . "' class='d-block w-100' alt='image event recent'>";
          echo "<div class='carousel-caption d-none d-md-block bg-event-info'>";
          echo "<h5>" . $eventinfos[2]['title'] . "</h5>";
          echo "<p>" . $eventinfos[2]['teaser'] . "</p>";
          echo "</div>";
          echo "</a>";
          echo "</div>";
        }
        ?>

        <?php
        if (count($eventinfos) >= 4) {
          echo "<div class='carousel-item item-car'>";
          echo "<a href='https://larche.ovh/event?id=" . $eventinfos[3]['id_event'] . "'>";
          echo "<img src='" . $eventinfos[3]['image'] . "' class='d-block w-100' alt='image event recent'>";
          echo "<div class='carousel-caption d-none d-md-block bg-event-info'>";
          echo "<h5>" . $eventinfos[3]['title'] . "</h5>";
          echo "<p>" . $eventinfos[3]['teaser'] . "</p>";
          echo "</div>";
          echo "</a>";
          echo "</div>";
        }
        ?>

        <?php
        if (count($eventinfos) >= 5) {
          echo "<div class='carousel-item item-car'>";
          echo "<a href='https://larche.ovh/event?id=" . $eventinfos[4]['id_event'] . "'>";
          echo "<img src='" . $eventinfos[4]['image'] . "' class='d-block w-100' alt='image event recent'>";
          echo "<div class='carousel-caption d-none d-md-block bg-event-info'>";
          echo "<h5>" . $eventinfos[4]['title'] . "</h5>";
          echo "<p>" . $eventinfos[4]['teaser'] . "</p>";
          echo "</div>";
          echo "</a>";
          echo "</div>";
        }
        ?>
      </div>
    </div>
    </div>
    </div>
    <div class="accueil-title">
      <div class='barres'></div>
      <h2 class="text-index">Articles récents</h2>
      <div class='barres'></div>
    </div>
    <section class="list-articles">

      <?php
      $article = $bdd->prepare("SELECT id_article, picture, name_category, title FROM articles ORDER BY `date` DESC LIMIT 3");
      $article->execute();
      $list_article = array();
      $i = 0;

      while ($row = $article->fetch(PDO::FETCH_ASSOC)) {
        $list_article[$i] = $row;
        $i++;
      }

      for ($i = 0; $i < count($list_article); $i++) {

        foreach ($list_article[$i] as $key => $value) {
          if ($key === "id_article") echo "<a href='https://larche.ovh/article?id=" . $value . "'>";

          if ($key === "picture") echo "<div class='articles'><img src='" . $value . "' class='article-picture'>";
          if ($key === "name_category") echo "<div class='title-article'><p class='" . $value . "'>" . ($value == 'animals' ? 'Animaux' : $value) . "</p>";
          if ($key === "title") echo "<p class='article-titre'>" . $value . "</p></div></div></a>";
        }
      }

      if ($list_article == false) {
        echo "<img src='./assets/sad-cat.gif' alt='chat triste'</img>";
        echo "<p class='text-change'>Aucun article n'a été posté aujourd'hui... </p>";
        if ($_SESSION['lvl_perms'] > 1) {
          echo "<a href='https://larche.ovh/createarticle' class='colorbutton'>Créer un article</a>";
        } elseif ($_SESSION['lvl_perms'] == 1) {
          echo "<a href='https://larche.ovh/new-ticket?obj=contrib' class='colorbutton'>Devenir contributeur</a>";
        }
      }
      ?>

    </section>
    <div class="accueil-title">
      <div class='barres'></div>
      <h2 class="text-index">Une question ?</h2>
      <div class='barres'></div>
    </div>
    <section class="accueil-qst">
      <p class="text-index">Posez la directement aux autres utilisateurs sur notre webtchat.</p>
      <a href="https://larche.ovh/webchat" class="colorbutton">Poser une question</a>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </main>
  <?php
  include('./includes/footer.php');
  ?>
  <script src="./js/darkmode.js"></script>
  <script src="./js/code.js"></script>
  <script src="./js/meteo.js"></script>
</body>

</html>