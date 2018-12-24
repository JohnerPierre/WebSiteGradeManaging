<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : display.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains grade of a student
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "0010";

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
} elseif ($_SESSION['user']['grade'] == 1) {
    header('location: home.php ');
    exit;
}

if (isset($_GET['branch'])) {
    if (!GetIfExistBranchById($_GET['branch'])) {
        header('location: home.php ');
        exit;
    }
    $_SESSION['tmpBranchId'] = $_GET['branch'];
} else {
    header('location: index.php ');
    exit;
}
?>
<!doctype html>
<html lang="fr">
    <head>
        <?php echo HeaderDisplay($_SERVER['SCRIPT_FILENAME']); ?>
    </head>
    <body>
        <?php
        echo NavBar($_SERVER['SCRIPT_FILENAME']);
        echo fileTree(idTree);
        ?>      
        Bonjour, <?php echo $_SESSION['user']['lastname']; ?>
        <a href="./logout.php"> Déconnexion</a>
        <div class="container">
            <?php echo GradeByBranchAndId($_SESSION['tmpBranchId'], $_SESSION['user']['id']); ?>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>
