<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : managementWorksForm.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains form for add/modifiate work
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "000506";

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
} elseif ($_SESSION['user']['grade'] == 0) {
    header('location: home.php ');
    exit;
}


$tokenClass = false;
$tokenTitle = false;
$tokenBranch = false;
$tokenDate = false;
$info = array('title' => '',
    'date' => '',
    'branch' => 0,
    'class' => 0);

if (isset($_GET['id'])) {
    if (!GetIfExistWorkById($_GET['id'])) {
        header('location: managementWorks.php');
        exit;
    }
    $_SESSION['tmpId'] = $_GET['id'];
    $info = GetWorkById($_SESSION['tmpId']);
}

if (isset($_REQUEST['send'])) {

    $class = strip_tags(trim($_REQUEST['class']));
    $branch = strip_tags(trim($_REQUEST['branch']));
    $date = strip_tags(trim($_REQUEST['date']));
    $title = strip_tags(trim($_REQUEST['title']));

    if ($class != "" && is_numeric($class)) {
        if (!GetIfExistClassById($class)) {
            $tokenClass = true;
        }
    } else {
        $tokenClass = true;
    }
    if ($title == '') {
        $tokenTitle = true;
    }
    if ($branch != "" && is_numeric($branch)) {
        if (!GetIfExistBranchById($branch)) {
            $tokenBranch = true;
        }
    } else {
        $tokenBranch = true;
    }

    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
        $tokenDate = true;
    }
    if ($tokenClass == false && $tokenTitle == false && $tokenBranch == false && $tokenDate == false) {
        if ($_SESSION['tmpId'] != 0)
            UpdateWork($_SESSION['tmpId'], $_SESSION['user']['id'], $class, $title, $branch, $date);
        else
            AddWork($_SESSION['user']['id'], $class, $title, $branch, $date);
        header('location: managementWorks.php');
        exit;
    }
    else {
        $info['title'] = $title;
        $info['date'] = $date;
        $info['branch'] = $branch;
        $info['class'] = $class;
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
            <h1><?php echo($_SESSION['tmpId'] != 0 ? 'Modification' : 'Ajout') ?> d'un travail:</h1>
            <form class="form-signin" role="form">
                <label for="class">Classe:</label>
                <select class="form-control text-center" name="class" id="class" autofocus required>
                    <?php echo GetAllClassHTMLOption($info['class']); ?>
                </select>
                <?php
                echo($tokenClass ? '<div class="alert alert-danger">Votre classe n\'existe pas</div>' : '')
                ?>
                <label for="title">Intitulé:</label>
                <input type="text" name="title" value="<?php echo $info['title']; ?>" class="form-control" id="title" placeholder="Titre du travail" required>
                <?php
                echo($tokenTitle ? '<div class="alert alert-danger">L\'intitulé est vide</div>' : '')
                ?>
                <label for="branch">Branche:</label>
                <select class="form-control text-center" name="branch" id="branch" required>
                    <?php echo GetAllBranchHTMLOption($info['branch']); ?>
                </select>
                <?php
                echo($tokenBranch ? '<div class="alert alert-danger">Votre branche n\'existe pas</div>' : '')
                ?>
                <label for="date">Date:</label>
                <input type="date" name="date" value="<?php echo $info['date']; ?>" class="form-control" id="date" required>
                <?php
                echo($tokenDate ? '<div class="alert alert-danger">La date n\'est pas correcte</div>' : '')
                ?>
                <input type="submit" value="Valider" name="send" class="btn btn-lg btn-primary btn-block">
            </form>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>
