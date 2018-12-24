<?php

/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : deleteWork.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains action for delete work
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
    if (GetIfExistWorkById($_GET['id']))
        DeleteWork($_GET['id']);
}
header('location:managementWorks.php ');
exit;
