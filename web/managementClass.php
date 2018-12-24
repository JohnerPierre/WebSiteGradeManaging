<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : managementClass.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains managment of a class(add/modifiate/delete)
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "0002";

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
} elseif ($_SESSION['user']['grade'] == 0) {
    header('location: home.php ');
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
            <h1>Classe(s):</h1>
            <?php echo ShowClass(); ?>
            <a href="./managementClassForm.php">Ajouter une classe</a>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>
