<?php

/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : deleteClass.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains action for delete class
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
} elseif ($_SESSION['user']['grade'] == 0) {
    header('location: home.php ');
    exit;
}
if ($_GET['id'] != NULL) {
    if (GetIfExistClassById($_GET['id']))
        DeleteClass($_GET['id']);
}
header('location: managementClass.php ');
exit;