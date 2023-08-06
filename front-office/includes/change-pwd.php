<?php

include('./db-connect.php');
$pwd = $_POST['newPassword'];
$confirmpwd = $_POST['confirmNewPassword'];

if (isset($pwd) && isset($confirmpwd)) {
    if (!empty($pwd) && !empty($confirmpwd)) {
        if ($pwd == $confirmpwd) {
            if (strlen($pwd) <= 6) {
                $msg = "Ton mot de passe doit faire plus de 6 caractères !";
                header('location: https://larche.ovh/changer-mdp?msg=' . $msg);
                exit;
            }
            if (strlen($pwd) > 32) {
                $msg = "Ton mot de passe doit faire moins de 32 caractères ! ";
                header('location: https://larche.ovh/changer-mdp?msg=' . $msg);
                exit();
            }
            $email = $_POST['emailUser'];
            $email = strip_tags($email);
            $email = htmlspecialchars($email, ENT_QUOTES);

            $pwd = strip_tags($pwd);
            $pwd = htmlspecialchars($pwd, ENT_QUOTES);

            $pass = "x!" . $pwd . ";A4!";
            $pwd = hash("sha512", $pass);

            $changepwd = $bdd -> prepare('UPDATE user SET password = ? WHERE email = ?');
            $changepwd -> execute([$pwd, $email]);

            $msg = "Mot de passe modifié !";
            header('location: https://larche.ovh/login?message=' . $msg);
            exit();

        }else {
            $msg = "Les mots de passes doivent être identiques";
            header('location: https://larche.ovh/changer-mdp?msg=' . $msg);
            exit();
        }
    }else {
        $msg = "Veuillez remplir tous les champs.";
        header('location: https://larche.ovh/changer-mdp?msg=' . $msg);
        exit();
    }
}else {
    $msg = "Erreur lors de la verification du mot de passe.";
    header('location: https://larche.ovh/changer-mdp?msg=' . $msg);
    exit();
}
?>