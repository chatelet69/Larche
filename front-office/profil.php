<?php
include("./includes/checksessions.php"); // vérifie si la session existe.
include("./includes/logHistoric.php");

$userInfo = $bdd->prepare("SELECT username, email, link_banner, pfp, `name`, lastname, `status`, `description` FROM user WHERE user_id = ?");
$userInfo->execute([$_SESSION['user_id']]);
$userInfo = $userInfo->fetch();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Mon Profil</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/profil.css">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
</head>

<body class="body-bg-beige body">
    <?php
    include('./includes/header.php');
    ?>


    <div class='banniere'>
        <?php
        echo "<img src='" . $userInfo['link_banner'] . "' alt='banniere'>";
        ?>
    </div>

    <div class="box-profil">
        <img src=" <?php echo $userInfo['pfp']; ?> " alt="Photo de profil." class="PDP">
        <p class="pseudo-profil text-index"><?php echo $userInfo['username']; ?></p>
        <p class="text-index">
            <?php
            echo $userInfo['name'] . " ";
            echo $userInfo['lastname'];
            ?></p>
    </div>

    <div class="badge">
        <p>Membre</p>
        <?php
        switch ($userInfo['status']) {
            case 2:
                echo '<p class="badge-contri">Contributeur</p>';
                break;
            case 3:
                echo '<p class="badge-mod">Modérateur</p></div>';
                break;
            case 4:
                echo '<p class="badge-admin">Administrateur</p>';
                break;
            case 5:
                echo '<p class="badge-super-admin">Super-Administrateur</p>';
                break;
            default:
                echo '';
                break;
        }
        ?>
    </div>

    <div class="follow">
        <div class="follow-section" onclick="listFollowers()">
            <?php
            $abonnés = $bdd->prepare("SELECT COUNT(*) FROM abonnement WHERE user_id_subscription = ?");
            $abonnés->execute([$_SESSION['user_id']]);
            $abonnés = $abonnés->fetch(PDO::FETCH_ASSOC);
            echo '<p class="text-index">' . $abonnés['COUNT(*)'] . '</p>';
            // nb_follows ==> nombre d'abonnements
            ?>
            <div class="barres-profil text-index"></div>
            <h2 class="text-index">Abonnés</h2>
        </div>
        <div class="follow-section" onclick="listFollow()">
            <?php
            $abonnements = $bdd->prepare("SELECT COUNT(*) FROM abonnement WHERE user_id_follow = ?");
            $abonnements->execute([$_SESSION['user_id']]);
            $abonnements = $abonnements->fetch(PDO::FETCH_ASSOC);
            echo '<p class="text-index">' . $abonnements['COUNT(*)'] . '</p>';
            ?>
            <div class="barres-profil text-index"></div>
            <h2 class="text-index">Abonnements</h2>
        </div>
    </div>
    <main class="profil-main">
        <h2 class="profil-h2 text-index">Description</h2>
        <?php
        if (!empty($userInfo['description'])) {
        echo "<section class='profil-desc'>
            <p class='text-no-change'>" . $userInfo['description'] . "</p>
            </section>";
        }else{
            echo "<section class='profil-desc'>
            <p class='text-no-change'>Pas de description...</p>
            </section>";
        }
        ?>
        <h2 class="profil-h2">Mes évènements :</h2>
        <table class='filter-event-profile'>
            <tbody>
            <tr>
                <td onclick="filterEventProfil('create')" class="pointeur">Créés</td>
                <td onclick="filterEventProfil('register')" class="pointeur">Inscrits</td>
            </tr>
            </tbody>
        </table>
        <section class='list-articles' id="list-articles">
            <?php
                include('./includes/display-event-profil.php');
            ?>
        </section>
        <h2 class="profil-h2 text-index">Mes derniers articles</h2>
        <section class="list-articles">

            <?php
            $user_id = $_SESSION['user_id'];
            $article = $bdd->prepare("SELECT id_article, picture, name_category, title FROM articles WHERE user_id_author = ?");
            $article -> execute([$user_id]);
            $list_article = array();
            $i = 0;

                while ($row = $article->fetch(PDO::FETCH_ASSOC)) {
                    $list_article[$i] = $row;
                    $i++;
                }

                for ($i=0; $i < count($list_article); $i++) { 
                    $article_id = $list_article[$i]['id_article'];
                    foreach ($list_article[$i] as $key => $value) {
                        if($key === "id_article") echo "<a href='https://larche.ovh/article?id=" . $value . "'>";
                        
                        if($key === "picture") echo "<div class='articles'><img src='" . $value . "' class='article-picture'>";
                        if($key === "name_category") 
                        echo "
                        <form action='./includes/delarticle.php' method='post'><input type='text' value='" . $article_id . "' name='id-article' hidden><button><img src='./assets/delete.png' class='delete-article' id=' $article_id '></button></form><div class='title-article'><p class='" . ($value == 'Jardinerie' ? 'jardinerie' : $value) . "'>" . ($value == 'animals' ? 'Animaux' : $value) . "</p>";
                        if($key === "title") echo "<p class='article-titre'>" . $value . "</p></div></div></a>";
                    }
                    
                }

            if($list_article==false){
                echo "<img src='./assets/sad-cat.gif' alt='chat triste'</img>";
                echo "<p class='text-index'>Vous n'avez encore jamais posté d'articles...</p>";
            }
            if($_SESSION['lvl_perms'] >1){
                echo "<a href='https://larche.ovh/createarticle' class='colorbutton'>Créer un article</a>";
            }elseif ($_SESSION['lvl_perms']==1) {
                echo "<a href='https://larche.ovh/new-ticket?obj=contrib' class='colorbutton'>Devenir contributeur</a>";
            }
            ?>

        </section>
        <section class='followers-container' id="abonnés" style="display: none">
            <div class='followers-title'>
                <h2 class="text-no-change">Abonnés</h2>
                <button type="button" onclick="closeDiv()" class="pointeur"><img src="./assets/cross.png" alt="croix" class="croix"></button>
            </div>
            <?php
                $list_followers = $bdd->prepare('SELECT pfp, date_follow, user.user_id, name, lastname, user.username FROM abonnement INNER JOIN user ON user.user_id = abonnement.user_id_follow WHERE user_id_subscription = ? ORDER BY date_follow');
                $list_followers -> execute([$user_id]);
                $followers = array();
                $i = 0;
                while ($row2 = $list_followers->fetch(PDO::FETCH_ASSOC)) {
                    $followers[$i] = $row2;
                    $i++;
                }
                echo "<div class='followers-list'>";
                for ($i = 0; $i < count($followers); $i++) {
                    $id = $followers[$i]['user_id'];
                    foreach ($followers[$i] as $key => $value) {
                        if ($key === "pfp") {
                            echo "<a href='https://larche.ovh/user?id=" . $id . "' class='link-follow'><div class='followers-items'><img src='" . $value . "' alt='photo de profil' class='PDP'>";
                        }
                        if ($key === "name") echo "<div class='followers-info'><p class='text-no-change'>" . $value;
                        if ($key === "lastname") echo " " . $value . "</p>";
                        if ($key === "username") echo "<p>@" . $value . "</p></div></div></a>";
                    }
                }
                echo "</div>";
            ?>
        </section>
        <section class='followers-container' id="abonnements" style="display: none">
            <div class='followers-title'>
                <h2 class="text-no-change">Abonnements</h2>
                <button type="button" onclick="closeDiv()" class="pointeur"><img src="./assets/cross.png" alt="croix" class="croix"></button>
            </div>
            <?php
                $list_followers = $bdd->prepare('SELECT pfp, date_follow, user_id_subscription, name, lastname, user.username FROM abonnement INNER JOIN user ON user.user_id = abonnement.user_id_subscription WHERE user_id_follow = ? ORDER BY date_follow');
                $list_followers -> execute([$user_id]);
                $followers = array();
                $i = 0;
                while ($row2 = $list_followers->fetch(PDO::FETCH_ASSOC)) {
                    $followers[$i] = $row2;
                    $i++;
                }
                echo "<div class='followers-list'>";
                for ($i = 0; $i < count($followers); $i++) {
                    $id = $followers[$i]['user_id_subscription'];
                    foreach ($followers[$i] as $key => $value) {
                        if ($key === "pfp") {
                            echo "<a href='https://larche.ovh/user?id=" . $id . "' class='link-follow'><div class='followers-items'><img src='" . $value . "' alt='photo de profil' class='PDP'>";
                        }
                        if ($key === "name") echo "<div class='followers-info'><p class='text-no-change'>" . $value;
                        if ($key === "lastname") echo " " . $value . "</p>";
                        if ($key === "username") echo "<p>@" . $value . "</p></div></div></a>";
                    }
                }
                echo "</div>";
            ?>
        </section>
    </main>
    <div class="popup" id='popup' style="display: none;">
    <h2 class='h2-popup'>Annuler l'évènement</h2>
    <p class='paragraph-popup'>Êtes-vous sûr(e) de vouloir annuler l'évènement ?</p><br>
    <p class='paragraph-popup'>Si oui, indiquez le motif :</p>
    <input type="text" name='motif' id='inp-event-motif'>
    <div class='btn-respons'>
    <button type="button" class="input-close" onclick="hidePopup()">Non</button>
    <input type="number" value="" name='event_id' id='inp-event-id' hidden>
    <button type="button" onclick="cancelEvent()" class="delete-confirm">Oui</button>
    </div>
</div>
    <?php
    include('./includes/footer.php');
    ?>
    <script src="./js/cancel-event-profil.js"></script>
    <script src="./js/code.js"></script>
    <script src="./js/darkmode.js"></script>

</body>

</html>