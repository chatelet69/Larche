<?php
    if($_SESSION['lvl_perms'] < 2){
        header('location:https://larche.ovh/contrib');
    }
?>