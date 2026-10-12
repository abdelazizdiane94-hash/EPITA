<?php
/** Termine la session du compte connecté et retourne à l'écran d'accueil. */

session_start();
session_unset();
session_destroy();

header('location:index.php')

?>
