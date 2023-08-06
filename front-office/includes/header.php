<header id="header" id="header">
    <div class="burger" onclick='burgerMenu()'>
        <span></span>
    </div>
    <nav>
        <ul class="navchoix">
            <li><a href="https://larche.ovh/accueil" class="headerlinks effect <?php echo ($title == "accueil" ? "bold" : "");?>" id="test">Accueil</a></li>
            <li><a href="https://larche.ovh/webchat" class="headerlinks effect <?php echo ($title == "webchat" ? "bold" : "");?>">WebChat</a></li>
            <li><a href="https://larche.ovh/articles" class="headerlinks effect <?php echo ($title == "articles" ? "bold" : "");?>">Articles</a></li>
            <li><a href="https://larche.ovh/events" class="headerlinks effect <?php echo ($title == "event" ? "bold" : "");?>">Évènements</a></li>
        </ul>
    </nav>
    <div id='search' class="search">
        <img src="https://larche.ovh/assets/Loupe.png" alt="loupe" class="loupe">
        <form method="post" action="https://larche.ovh/search"><input type="text" placeholder="Recherche..." name="recherche" id="recherche" class="recherche"  oninput="research()" autocomplete="off"></form>
    </div>

    <div class="profil" onclick="menuderoulant()">

        <img src=" <?php echo $_SESSION['user_pfp']; ?> " alt="Photo de profil." class="PDP">
    </div>

</header>
<div class="test">
    <div id="menuderoulant" style="display:none;">
        <nav>
            <ul>
                <li>
                    <a href="https://larche.ovh/profil">Profil</a>
                </li>
                <li>
                    <a href="https://larche.ovh/settings">Paramètres</a>
                </li>
                <li>
                    <a href="https://larche.ovh/new-ticket?obj=contrib">Devenir Contributeur</a>
                </li>
                <li>
                    <a href="https://larche.ovh/support">Support</a>
                </li>
                <li>
                    <a href="https://larche.ovh/deconnexion">Déconnexion</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<div class='container-schr-box'>
<section id="research-box" style="display: none;">

</section>
</div>