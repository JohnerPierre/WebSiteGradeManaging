<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : managementWorks.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains managment for work(add/modifiate/delete)
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "0005";

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
} elseif ($_SESSION['user']['grade'] == 0) {
    header('location: home.php ');
    exit;
}
$_SESSION['tmpId'] = 0;
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
            <h1>Travaux:</h1>
            <?php echo ShowWorksByTeacher($_SESSION['user']['id']) ?>
            <a href="./managementWorksForm.php">Ajouter un travail</a>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>
