<?php
include("./db-connect.php");
include("./trads.php");

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['requestType']) && !empty($_POST['requestType'])) {
    $requestType = $_POST["requestType"];
} else if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['requestType']) && !empty($_GET['requestType'])) {
    $requestType = $_GET["requestType"];
}
$lvl_perms = ["Banni", "Membre", "Contributeur", "Modérateur", "Administrateur", "SuperAdmin"];
session_start();

switch ($requestType) {
    case "searchUser": {
        if (isset($_POST['username']))
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES);

        $sqlGetUser = $bdd->prepare("SELECT user_id,username,email,status,name,lastname,DATE_FORMAT(date_created, '%d/%m/%Y') AS date FROM user WHERE (username = ?)");
        $sqlGetUser->execute([$username]); // on l'exec avec comme paramètre le pseudo à rechercher
        $array = $sqlGetUser->fetch(PDO::FETCH_ASSOC); // On récupère les données et on les associes à array

        if (!empty($array)) {
            $sqlGetLastLog = $bdd->prepare("SELECT id_log AS 'ID',DATE_FORMAT(date, '%d/%m/%Y') AS date,ip AS 'Ip' FROM logs WHERE (user_id = ?) ORDER BY id_log DESC LIMIT 1;");
            $sqlGetLastLog->execute([$array['user_id']]); // on exec
            $lastLog = $sqlGetLastLog->fetch(PDO::FETCH_ASSOC);
        }

        if (isset($array['status']))
            $array['status'] = $lvl_perms[$array['status']];

        if (isset($array['username'])) {
            $userId = $array['user_id'];
            $username = $array['username'];
            echo "<div class='searched-user-container list-group rounded-2'>";
            foreach ($array as $key => $value)
                echo "<li class='list-group-item'>$userInfoTrad[$key] : <span class='item-$key'>$value</span></li>";
            echo "</div><div class='searched-user-container list-group rounded-2 p-0'><p class='fs-6 m-0'>Dernière connexion</p>";
            foreach ($lastLog as $key => $value)
                echo "<li class='list-group-item'>$key : <span class='item-$key'>$value</span></li>";
            echo "</div><button type='button' onclick='exportPdf($userId)' class='btn mt-2 btn-info'>Télécharger PDF</button>";
        }
        break;
    }
    case "editAccount": {
        if (isset($_GET['userName']) && !empty($_GET['userName'])) {
            $authorId = $_SESSION['user_id'];
            $searchUsername = htmlspecialchars($_GET['userName'], ENT_QUOTES);

            $sqlFindMember = $bdd->prepare("SELECT user_id,username,email,status,
            description,name,DATE_FORMAT(age, '%d/%m/%Y') AS birth, lastname,ip,city FROM user WHERE username = ?");
            $sqlFindMember->execute([$searchUsername]);
            $array = $sqlFindMember->fetch(PDO::FETCH_ASSOC);

            if (isset($array['status']))
                $array['status'] = $lvl_perms[$array['status']];

            if (isset($array['username'])) {
                $userId = $array['user_id'];
                echo "<div class='list-group rounded-2'>";
                echo "<li class='list-group-item'>" . $userInfoTrad['user_id'] . " : <span class='item-user_id'>" . $array['user_id'] . "</span></li>
                <li class='list-group-item'>" . $userInfoTrad['username'] . " : <span class='item-username'>" . $array['username'] . "</span></li>
                <li class='list-group-item'>" . $userInfoTrad['email'] . " : <span class='item-email'>" . $array['email'] . "</span></li>
                <li class='list-group-item'>" . $userInfoTrad['status'] . " : <span class='item-status'>" . $array['status'] . "</span></li>";
                echo "</div><form></form>";
                if ($authorId != $userId) {
                    echo "<form action='https://staff.larche.ovh/includes/delete-row.php' method='post' class='d-flex justify-content-end align-items-center'>";
                    if ($array['status'] !== "Banni") {
                        echo "<input type='text' name='requestType' value='banMember' hidden>
                        <input type='hidden' name='userIdToBan' value='$userId'>
                        <input type='submit' class='btn btn-warning mt-3' value='Bannir'>";
                    } else {
                        echo "<input type='text' name='requestType' value='unbanMember' hidden>
                        <input type='hidden' name='userIdToUnban' value='$userId'>
                        <input type='submit' class='btn btn-success mt-3' value='Débannir'>";
                    }
                    echo "</form>";
                } else {
                    echo "<p>Pas d'action sur les ban possibles</p>";
                }
                echo "<form action='https://staff.larche.ovh/includes/delete-row.php' method='post' class='d-flex justify-content-end align-items-center'>";
                echo "<input type='text' name='requestType' value='deleteMember' hidden>
                <input type='hidden' name='userIdToDelete' value='$userId'>";
                if ($authorId != $userId) {
                    echo "<input type='submit' class='btn btn-danger me-1' value='Supprimer'>";
                } else {
                    echo "<input type='submit' class='btn btn-danger me-1' value='Supprimer (Impossible)' disabled>";
                }
                echo "<button type='button' class='btn btn-dark ms-1' onclick='editAccountInfosPopup($userId)' id='editAccountBtn'>Editer compte</button>";
                $userName = $array['username'];
                $userStatus = $array['status'];
                $userEmail = $array['email'];
                $userBirth = $array['birth'];
                $userDesc = $array['description'];
                echo "
                <div style='display:none;' class='position-fixed top-50 start-50 translate-middle' id='editAccountInfosPopup'>
                    <h3 class='text-center fs-5'>Edition de $userName</h3>
                    <form class='d-flex flex-column form-container'>
                        <div class='edit-sections-container d-flex flex-row'>
                            <div class='edit-section d-flex flex-column'>
                                <label class='form-label' for='username'>Nom d'utilisateur</label>
                                <input class='form-control' type='text' placeholder='$userName' name='username'>
            
                                <label class='form-label' for='email'>E-mail</label>
                                <input class='form-control' type='text' placeholder='$userEmail' name='email'>
                                <label class='form-label' for='Description'>Description</label>
                                <textarea class='form-control' maxlength='255' type='text' id='userDescArea' rows='3' name='description'>".$userDesc."</textarea>
                            </div>
                            <div class='edit-section d-flex flex-column'>
                                <label class='form-label' for='name'>Prénom</label>
                                <input class='form-control' type='text' placeholder='" . $array['name'] . "' min='1' max='4' name='name'>
                                
                                <label class='form-label' for='lastname'>Nom</label>
                                <input class='form-control' type='text' placeholder='" . $array['lastname'] . "' min='1' max='4' name='lastname'>
                                
                                <label class='form-label' for='status'>Niveau permissions</label>
                                <input class='form-control' type='number' placeholder='$userStatus' min='1' max='4' name='status'>
                                
                                <label class='form-label' for='age'>Date</label>
                                <input class='form-control' type='date' value='$userBirth' name='age'>
                            </div>
                        </div>
                        <button type='button' class='btn btn-success mt-1' onclick='saveEditAccountInfos($userId)'>Enregistrer</button>
                        <button type='button' class='btn btn-danger cancel' onclick='closeEditAccountInfosPopup()'>Annuler</button>
                    </form>
                </div>";
            }
        }
        break;
    }
    case "saveEditAccount": {
        $acceptableValues = array("name", "user_id","username", "email", "lastname", "status", "age", "description");
        $newValues = array();
        $req = "UPDATE user";
        foreach($_POST as $key => $value) {
            if ($key !== "requestType" && in_array($key, $acceptableValues)) {
                if ($key === "user_id") {
                    $newValues[":".$key] = intval($value); 
                } else {
                    $newValues[":".$key] = $value; 
                    $req = $req. " SET ".$key." = :".$key;
                }
            }
        }

        if (!empty($newValues)) {
            $req = $req." WHERE user_id = :user_id";
            $sqlUpdateUser = $bdd->prepare($req);
            $res = $sqlUpdateUser -> execute($newValues);
            echo ($res !== false) ? "ok" : "Problème dans l'edition";
        } else {
            echo "Valeurs envoyées vides";
            exit;
        }
        break;
    }
    default: {
        echo "KO";
        exit;
    }
}
