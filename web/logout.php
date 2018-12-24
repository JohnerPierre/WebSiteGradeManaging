<?php

/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : logout.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains logout
 * ************************************************************ */
session_start();
if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
}

require_once './script/scriptPHP/functions.php';
logout();
header('location: index.php ');
exit;
?>