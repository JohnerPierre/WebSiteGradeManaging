<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : managementParticipateClass.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains list of a class and form for add a student in class
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "000204";
$token = true;

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
} elseif ($_SESSION['user']['grade'] == 0) {
    header('location: home.php ');
    exit;
}

if (isset($_GET['id'])) {
    if (!GetIfExistClassById($_GET['id'])) {
        header('location: managementClass.php ');
        exit;
    }
    $_SESSION['tmpId'] = $_GET['id'];
}

if (isset($_REQUEST['send'])) {
    $token = false;
    $student = strip_tags(trim($_REQUEST['student']));

    if ($student != "" || is_numeric($student)) {
        foreach (GetAllStudentsExceptClassById($_SESSION['tmpId']) as $value) {
            if ($value['idEleve'] == $student)
                $token = true;
        }
    }
    if ($token)
        AddParticipate($_REQUEST['student'], $_SESSION['tmpId']);
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
            <h1>Participant(s):</h1>
            <?php
            echo ShowParticipateByClass($_SESSION['tmpId'], $token);
            ?>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>
