<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : home.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Home page
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "00";

if (!isset($_SESSION['user']['grade'])) {
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
            <?php
            echo($_SESSION['user']['grade'] == 0 ? linkToDisplay($_SESSION['user']['id']) :
                    '<img border="0" src="http://wwwedu.ge.ch/dip/biblioweb/photos/cepta600.jpg" alt="title">');
            ?>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>
