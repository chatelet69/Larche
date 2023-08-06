<?php
include("./includes/checksessions.php"); // vérifie si la session existe.
include("./includes/logHistoric.php");
if (isset($_GET['id'])) {
    if (!empty($id)) {
        $id = $_GET['id'];
    }else{
        $id = '[insérer l\'id';
    }
}else{
    $id = '[insérer l\'id';
}

if (isset($_GET['obj'])) {
if (!empty($_GET['obj'])) {
    $obj = $_GET['obj'];
    switch ($obj) {
        case 'createWebchat':
            $obj = 'Création du Webchat [insérer le nom].';
            break;
            case 'reportMessage':
                $obj = "Signalement message #$id Webchat";
                break;
                case 'reportArticle':
                    $obj = "Signalement article #$id";
                    break;
                    case 'reportEvent':
                        $obj = "Signalement évènement #$id";
                        break;
                        case 'contrib':
                            $obj = "Demande contributeur " . $_SESSION['user'];
                            break;
                            case 'reportProfile':
                                $obj = "Signalement utilisateur #$id";
                                break;
                                case 'reportComment':
                                    $obj = "Signalement commentaire #$id";
                                    break;
        default:
            $obj = '';
            break;
    }
}else{
    $obj = '';
}
}else{
    $obj = '';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>L'Arche | Envoyer un ticket</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/support.css">
    <link rel="stylesheet" type="text/css" href="css/new-ticket.css">
    <link rel="shortcut icon" href="./assets/favicon-green.ico" type="image/x-icon">
</head>

<body class="body-bg-beige body">
    <?php
    include('./includes/header.php');
    ?>
    <div class="accueil-title">
      <div class='barres'></div>
      <h2 class="text-index">Envoyer un ticket</h2>
      <div class='barres'></div>
    </div>
    <main class='support-box'>
        <section class='ticket-box'>
            <form action="./includes/verif-ticket.php" method="post" class='form-new-ticket' enctype="multipart/form-data">
                <input type="text" placeholder="Titre du ticket... (40 caractères maximum)" class="grey-input" name='title' value='<?php echo $obj; ?>'>
                
                <textarea placeholder='Description de votre demande...' class="grey-input" name='request'></textarea>
                
                <label for="file" class="label-file grey-option">Pièces jointes<img src="./assets/PJ.png" alt="pièces jointes" class="PJ-article"></label>
                <input id="file" class="input-file grey-option" type="file" accept="image/jpeg , image/png, image/gif" name="image-ticket" name='file'>
                <?php echo "<p class='msg-danger'>" . (isset($_GET['msg']) ? $_GET['msg']: '</p>')?>
                <div class='container-button'><input type="submit" value="Envoyer" class='new-ticket'></div>
            </form>            
        </section>
    </main>

    <?php
    include('./includes/footer.php');
    ?>
    <script src="./js/darkmode.js"></script>
    <script src="./js/code.js"></script>
</body>
</html>