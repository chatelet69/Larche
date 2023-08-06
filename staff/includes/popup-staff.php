<div class="staff-create-box col-1">
    <button class="open-button" onclick="openForm()"><strong>Créer un staff</strong></button>
</div>
<div class="form-popup" id="popupForm">
    <form action="./includes/staff-options.php" class="form-container" method="post">
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

        <input type="submit" class="btn mt-1" value="Créer le compte" />
        <button type="submit" class="btn cancel" onclick="closeForm()">Annuler</button>
        <?php $_POST['request-type'] = "create-staff"; ?>
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