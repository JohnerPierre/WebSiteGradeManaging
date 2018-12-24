<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : managementClassForm.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains form for add or modifiate class
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "000203";

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
} elseif ($_SESSION['user']['grade'] == 0) {
    header('location: home.php ');
    exit;
}

$info = array();
$tokenYear = false;
$tokenName = false;

if (isset($_GET['id'])) {
    if (!GetIfExistClassById($_GET['id'])) {
        header('location: managementClass.php ');
        exit;
    }

    $_SESSION['tmpId'] = $_GET['id'];
    $info = GetClassById($_SESSION['tmpId']);
} else {
    if (!isset($_REQUEST['send']))
        $_SESSION['tmpId'] = 0;
}
if (isset($_REQUEST['send'])) {
    $name = strip_tags(trim($_REQUEST['name']));
    $year = strip_tags(trim($_REQUEST['year']));

    if ($name == '')
        $tokenName = true;

    if (!preg_match('/^(19[0-9]{1}[1-9]{1}|2[0-9](0[0-9]{1}|[0-9][0-9]))$/', $year))
        $tokenYear = true;

    if ($tokenName == FALSE && $tokenYear == FALSE) {
        if ($_SESSION['tmpId'] != 0)
            UpdateClass($_SESSION['tmpId'], $name, $year);
        else
            AddClass($name, $year);
        header('location: managementClass.php ');
        exit;
    }
    else {
        $info['nameClass'] = $name;
        $info['yearClass'] = $year;
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
            <h1><?php echo($_SESSION['tmpId'] != 0 ? 'Modification' : 'Ajout') ?> Classe:</h1>
            <form class="form-signin" role="form">
                <label for="name">Nom:</label>
                <input type="text" name="name" value="<?php echo($tokenName || $_SESSION['tmpId'] != 0 ? $info['nameClass'] : '') ?>" 
                       class="form-control" id="name" placeholder="Nom de la Classe" autofocus required>
                       <?php
                       echo($tokenName ? '<div class="alert alert-danger">Le nom de classe est vide</div>' : '')
                       ?>
                <label for="year">Année:</label>
                <input type="text" name="year" value="<?php echo($tokenYear || $_SESSION['tmpId'] != 0 ? $info['yearClass'] : '') ?>" 
                       class="form-control" id="year" placeholder="Année de la classe(YYYY)" required>
                       <?php
                       echo($tokenYear ? '<div class="alert alert-danger">L\'année est pas valide</div>' : '')
                       ?>
                <input type="submit" value="Valider" name="send" class="btn btn-lg btn-primary btn-block">
            </form>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>
