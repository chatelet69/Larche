<div class="staff-create-box">
    <button class="open-button" onclick="openForm()"><strong>Créer un staff</strong></button>
</div>
<div class="form-popup" id="popupForm">
    <form action="./includes/create-staff.php" class="form-container" method="post">
        <h2>Création d'un membre staff</h2>
        <label for="username">
            <strong>Nom d'utilisateur</strong>
        </label>
        <input type="text" placeholder="username" name="username" required />
        <label for="email">
            <strong>E-mail</strong>
        </label>
        <input type="text" placeholder="Email" name="email" required />
        <label for="psw">
            <strong>Mot de passe</strong>
        </label>
        <input type="password" placeholder="mot de passe" name="password" required />
        <label for="lvl_perms">
            <strong>Level de perms</strong>
        </label>
        <input type="number" placeholder="perms" min="1" max="4" name="lvlperms" required />

        <input type="submit" class="btn" value="Créer le compte" />
        <button type="submit" class="btn cancel" onclick="closeForm()">Annuler</button>
    </form>
</div>
<script>
    function openForm() {
        document.getElementById("popupForm").style.display = "block";
    }
    function closeForm() {
        document.getElementById("popupForm").style.display = "none";
    }
</script>

<?php
// Récupération des données de la requête POST
if (!empty($_POST)) {
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];
        if (strlen($email) <= 70) {
            $email = strip_tags($email);
            $email = htmlspecialchars($email, ENT_QUOTES);
        } else {
            header("Location: ../index.php?msg=email_problem");
            exit;
        }
    }

    if (isset($_POST['password']) && !empty($_POST['password'])) {
        $password = $_POST['password'];
        if (strlen($password) <= 30) {
            $password = strip_tags($password);
            $password = htmlspecialchars($password, ENT_QUOTES);
        } else {
            header("Location: ../index.php?msg=mdp_problem");
            exit;
        }
    }
    $username = $_POST['username'];
    $lvl_perms = $_POST['lvlperms'];
}

try {
    $date = date("Y-m-d");
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=db_name', 'db_host', 'mdp');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $hash = md5($password);
    $sqlCheck = $bdd->prepare("SELECT username,email FROM staff WHERE email=? AND password=?");
    $sqlCheck->execute([$email, $hash]);
    $check = $sqlCheck->fetch();

    var_dump($check);

    /*if ($check !== true) {
        $sqlCreateStaff = $bdd->prepare("INSERT INTO staff (username, email, password, status, date_creation) VALUES (?,?,?,?,?)");
        $sqlCreateStaff->execute([$username, $email, $hash, $lvl_perms, $date]);
        //header("Location: ../index.php?msg=user_created");
        //exit;
    } else {
        header("Location: ../index.php?msg=user_already_exist");
        exit;
    }*/
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}
?>