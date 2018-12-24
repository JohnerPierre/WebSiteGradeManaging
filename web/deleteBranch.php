<?php

/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : deleteBranch.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains action for delete branch 
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
    if (GetIfExistBranchById($_GET['id']))
        DeleteBranch($_GET['id']);
}
header('location: managementBranchs.php ');
exit;