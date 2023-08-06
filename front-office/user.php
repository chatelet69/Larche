<?php
include("./includes/checksessions.php"); // vérifie si la session existe.
include("./includes/logHistoric.php");
$id = $_GET['id'];
if ($id === $_SESSION['user_id']) {
    header('location: https://larche.ovh/profil');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | User</title>
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
        $user = $bdd->prepare("SELECT link_banner, username, email, `name`, lastname, `status`, pfp, city, `description` FROM user WHERE user_id = ?");
        $user->execute([$id]);
        $user = $user->fetch();
        if (empty($user)) {
            header('location: ./error404.html');
            exit;
        }
        $banniere = $user['link_banner'];

        echo "<img src='$banniere' alt='banniere'>";
        ?>
    </div>

    <div class="box-profil">
        <p class='ticket-report' style='align-self: end; position: absolute; right: 10px'><a href='https://larche.ovh/new-ticket?obj=reportProfile&id=<?php echo $id;?>' alt=''>Signaler l'utilisateur</a></p>
        <img src=" <?php echo $user['pfp']; ?> " alt="Photo de profil." class="PDP">
        <p class="pseudo-profil"><?php echo $user['username']; ?></p>
        <p class="name-profil">
            <?php
            echo $user['name'] . " ";
            echo $user['lastname'];
            ?></p>
    </div>

    <div class="badge">
        <p>Membre</p>
        <?php
        switch ($user['status']) {
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
    <div class="follow-button">
    <?php
    if($id !== $_SESSION['user_id']){
         $checkabos = $bdd -> prepare('SELECT user_id_subscription FROM abonnement WHERE user_id_follow = ? AND user_id_subscription = ?');
         $checkabos -> execute([$_SESSION['user_id'], $id]);
         $checkabos = $checkabos -> fetch();
         if(empty($checkabos)){
            echo "<button onclick='followUser(" . $id . ")' class='unsub' id='follow" . $id . "'>S'abonner</button>";
         }else{
            echo "<button onclick='followUser(" . $id . ")' class='sub' id='follow" . $id . "'>Se desabonner</button>";
         }
        }
        ?>
    </div>
    <div class="follow">
        <div class="follow-section" onclick="listFollowers()">
            <?php
            $abonnés = $bdd->prepare("SELECT COUNT(*) FROM abonnement WHERE user_id_subscription = ?");
            $abonnés->execute([$id]);
            $abonnés = $abonnés->fetch(PDO::FETCH_ASSOC);

            echo '<p id="abos' . $id . '" class="nb_foll">' . $abonnés['COUNT(*)'] . '</p>';
            // nb_follows ==> nombre d'abonnements
            ?>
            <div class="barres"></div>
            <h2 class="nb_foll">Abonnés</h2>
        </div>
        
        <div class="follow-section" onclick="listFollow()">
            <?php
                $abonnements = $bdd->prepare("SELECT COUNT(*) FROM abonnement WHERE user_id_follow = ?");
                $abonnements->execute([$id]);
                $abonnements = $abonnements->fetch(PDO::FETCH_ASSOC);
                echo '<p class="nb_foll">' . $abonnements['COUNT(*)'] . '</p>';
            ?>
            <div class="barres"></div>
            <h2 class="nb_foll">Abonnements</h2>
        </div>
    </div>
    <main class="profil-main">
        <h2 class="profil-h2">Description</h2>
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
        <h2 class="profil-h2">Mes derniers articles</h2>
        <section class="list-articles">

            <?php
            $article = $bdd->prepare("SELECT id_article, picture, name_category, title FROM articles WHERE user_id_author = ?");
            $article -> execute([$id]);
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
                        echo "<div class='title-article'><p class='" . ($value == 'Jardinerie' ? 'jardinerie' : $value) . "'>" . ($value == 'animals' ? 'Animaux' : $value) . "</p>";
                        if($key === "title") echo "<p class='article-titre'>" . $value . "</p></div></div></a>";
                    }
                    
                }

            if($list_article==false){
                echo "<img src='./assets/sad-cat.gif' alt='chat triste'</img>";
                echo "<p style='margin-bottom: 1vw'>Cet utilisateur n'a jamais publié d'articles...</p>";
            }
            ?>

        </section>
        <section class='followers-container' id="abonnés" style="display: none">
            <div class='followers-title'>
                <h2>Abonnés</h2>
                <button type="button" onclick="closeDiv()" class="pointeur"><img src="./assets/cross.png" alt="croix" class="croix"></button>
            </div>
            <?php
                $id = $_GET['id'];
                $list_followers = $bdd->prepare('SELECT pfp, date_follow, user.user_id, name, lastname, user.username FROM abonnement INNER JOIN user ON user.user_id = abonnement.user_id_follow WHERE user_id_subscription = ? ORDER BY date_follow');
                $list_followers -> execute([$id]);
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
                        if ($key === "name") echo "<div class='followers-info'><p>" . $value;
                        if ($key === "lastname") echo " " . $value . "</p>";
                        if ($key === "username") echo "<p>@" . $value . "</p></div></div></a>";
                    }
                }
                echo "</div>";
            ?>
        </section>
        <section class='followers-container' id="abonnements" style="display: none">
            <div class='followers-title'>
                <h2>Abonnements</h2>
                <button type="button" onclick="closeDiv()" class="pointeur"><img src="./assets/cross.png" alt="croix" class="croix"></button>
            </div>
            <?php
            $id = $_GET['id'];
                $list_followers = $bdd->prepare('SELECT pfp, date_follow, user_id_subscription, name, lastname, user.username FROM abonnement INNER JOIN user ON user.user_id = abonnement.user_id_subscription WHERE user_id_follow = ? ORDER BY date_follow');
                $list_followers -> execute([$id]);
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
                        if ($key === "name") echo "<div class='followers-info'><p>" . $value;
                        if ($key === "lastname") echo " " . $value . "</p>";
                        if ($key === "username") echo "<p>@" . $value . "</p></div></div></a>";
                    }
                }
                echo "</div>";
            ?>
        </section>
    </main>
    <?php
    include('./includes/footer.php');
    ?>
    <script src="./js/code.js"></script>
    <script src="./js/darkmode.js"></script>

</body>

</html>