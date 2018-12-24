<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : managementGrades.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains form for marke student in a work
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "000507";

$error = array();
$gradeMax = 6;
$gradeMin = 0;

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
} elseif ($_SESSION['user']['grade'] == 0) {
    header('location: home.php ');
    exit;
}

if (isset($_GET['id'])) {
    if (!GetIfExistWorkById($_GET['id'])) {
        header('location: managementWorks.php');
        exit;
    }
    $_SESSION['tmpId'] = $_GET['id'];
} else {
    header('location: managementWorks.php');
    exit;
}

if (isset($_REQUEST['send'])) {

    foreach (GetGradeByWork($_SESSION['tmpId']) as $value) {
        $grade = strip_tags(trim($_REQUEST['grade' . $value['idEleve']]));
        if (is_numeric($grade) && $grade != "" && $grade <= $gradeMax && $grade >= $gradeMin) {
            AddGrade($value['idEleve'], $_SESSION['tmpId'], $grade);
        } else {
            $error['grade' . $value['idEleve']] = true;
        }
    }
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
            <h1>Note(s):</h1>
            <?php echo ShowGradeByWork($_SESSION['tmpId'], $error); ?>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>
