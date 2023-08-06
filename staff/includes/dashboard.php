<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<main class="container-fluid">
    <div class="row flex-nowrap">
        <nav id="sidebarMenu"
            class="vh-100 col-md-3 col-lg-2 d-md-block sidebar 
                collapse d-flex flex-column col-auto col-md-3 col-xl-2 px-sm-2 px-0 p-3 text-white bg-dark border-end border-primary border-3">
            <div class="d-flex overflow-hidden justify-content-center rounded-circle">
                <img class="rounded-circle border border-4 border-light" width="180vw" height="180vh" src="<?php echo $_SESSION['user_pfp']; ?>" alt="PDP">
            </div>
            <ul id="navbar-sidebar" class="navbar nav nav-pills text-center flex-column mb-auto mt-2 fs-5">
                <li class="w-100 nav-item mt-1" id="index"><a class="text-decoration-none" href="https://staff.larche.ovh/index">Dashboard principal</a>
                </li>
                <li class="w-100 nav-item mt-1" id="gestion-staff"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-staff">Gestion des
                        staff</a></li>
                <li class="w-100 nav-item mt-1" id="logs-staff">
                    <a class="text-decoration-none" href="https://staff.larche.ovh/module/logs-staff">Logs Staff</a>
                </li>
                <li class="w-100 nav-item mt-1" id="gestion-membres"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-membres">Gestion des membres</a></li>
                <li class="w-100 nav-item mt-1" id="gestion-tickets"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-tickets">Gestion des tickets</a></li>
                <li class="w-100 nav-item mt-1" id="gestion-articles"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-articles">Gestion des articles</a></li>
                <li class="w-100 nav-item mt-1" id="gestion-events"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-events">Gestion des évènements</a></li>
                <li class="w-100 nav-item mt-1" id="gestion-mails"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-mails">Newsletter/mails</a></li>
                <li class="w-100 nav-item mt-1" id="gestion-chat"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-chat">Gestion du chat</a></li>
                <li class="w-100 nav-item mt-1" id="panel-logs"><a class="text-decoration-none" href="https://staff.larche.ovh/module/panel-logs">Panel Logs connexions</a></li>
            </ul>
            <div style="margin-left:4vw;" class="dropup position-absolute bottom-0 mb-2">
                    <button type="button" aria-expanded="false" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">Theme</button>
                <ul class="dropdown-menu">
                    <li><a id="setThemeBtn" onclick="setThemeMode(this)" class="dropdown-item"></a></li>
                </ul>
            </div>
        </nav>
        <section id="main-container" class="main-container col border-0 m-0 p-0">
            <div id="dashboard-header"
                class="container-fluid navbar-expand-lg bg-dark border-bottom border-primary border-3 mt-0">
                <header class="d-flex flex-wrap justify-content-between py-1 mb-1">
                    <a class="font-weight-bold link-light text-decoration-none d-flex align-items-center mb-2 mb-md-0"
                        href="https://staff.larche.ovh/index">
                        <img class="mx-2"
                            src="https://media.discordapp.net/attachments/1067751095855747075/1073609236006850611/logo-transparent-png.png?width=595&height=588"
                            id="svg-logo" width="60px" height="60px" viewBox="0 0 100 100"></img>
                        <span class="fs-6 fw-bold">Larche Admin Panel</span>
                    </a>
                    <div class="dropdown" id="dropMenuHeader">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Menu
                        </button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-item" id="index"><a class="text-decoration-none" href="https://staff.larche.ovh/index">Dashboard principal</a></li>
                            <li class="dropdown-item" id="gestion-staff"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-staff">Gestion des staff</a></li>
                            <li class="dropdown-item" id="logs-staff"><a class="text-decoration-none" href="https://staff.larche.ovh/module/logs-staff">Logs Staff</a></li>
                            <li class="dropdown-item" id="gestion-membres"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-membres">Gestion des membres</a></li>
                            <li class="dropdown-item" id="gestion-tickets"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-tickets">Gestion des tickets</a></li>
                            <li class="dropdown-item" id="gestion-articles"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-articles">Gestion des articles</a></li>
                            <li class="dropdown-item" id="gestion-events"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-events">Gestion des évènements</a></li>
                            <li class="dropdown-item" id="gestion-mails"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-mails">Newsletter/mails</a></li>
                            <li class="dropdown-item" id="gestion-chat"><a class="text-decoration-none" href="https://staff.larche.ovh/module/gestion-chat">Gestion du chat</a></li>
                            <li class="dropdown-item" id="panel-logs"><a class="text-decoration-none" href="https://staff.larche.ovh/module/panel-logs">Panel Logs connexions</a></li>
                            <li class="dropdown-item"><button id="setThemeBtnDropdown" class="text-primary btn" onclick="setThemeMode(this)">Changer Thème</button></li>
                        </ul>
                    </div>
                    <nav class="navbar">
                        <ul class="nav nav-pills">
                            <li class="nav-item text-light m-2 p-1 px-4">
                                <?php echo "Connecté en tant que <span class='text-info'>" . $_SESSION['user'] . "</span> | Rôle : <span class='text-danger'>" . $_SESSION['role'] . "</span>"; ?>
                            </li>
                            <li class="nav-item"><a class="nav-link bg-light border border-light border-1 m-1"
                                    href="https://staff.larche.ovh/my-account">Mon compte</a></li>
                            <li class="nav-item"><a id="logout-btn" class="nav-link border border-1 border-danger m-1"
                                    href="https://staff.larche.ovh/logout">Se déconnecter</a></li>
                        </ul>
                    </nav>
                </header>
            </div>
            <script src="https://staff.larche.ovh/js/setThemeMode.js"></script>