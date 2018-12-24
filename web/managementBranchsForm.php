<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : managementBranchsForm.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains form for branch(add/modification)
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "000809";

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
} elseif ($_SESSION['user']['grade'] == 0) {
    header('location: home.php ');
    exit;
}

$tokenError = false;
$nameBranch = '';
if (isset($_GET['id'])) {
    if (!GetIfExistBranchById($_GET['id'])) {
        header('location: managementBranchs.php ');
        exit;
    }

    $_SESSION['tmpId'] = $_GET['id'];
    $nameBranch = GetBranchById($_SESSION['tmpId']);
} else {
    if (!isset($_REQUEST['send']))
        $_SESSION['tmpId'] = 0;
}

if (isset($_REQUEST['send'])) {
    $name = strip_tags(trim($_REQUEST['name']));

    if ($name != '') {
        if ($_SESSION['tmpId'] != 0)
            UpdateBranch($_SESSION['tmpId'], $name);
        else
            AddBranch($name);
        header('location: managementBranchs.php ');
        exit;
    }
    else {
        $tokenError = true;
        $nameBranch = $_REQUEST['name'];
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
            <h1><?php echo($_SESSION['tmpId'] != 0 ? 'Modification' : 'Ajout') ?> Branche:</h1>
            <form class="form-signin" role="form">
                <label for="name">Nom:</label>
                <input type="text" name="name" value="<?php echo($tokenError || $_SESSION['tmpId'] != 0 ? $nameBranch : '') ?>" class="form-control"
                       placeholder="Nom de la branche" id="name" autofocus required>
                       <?php
                       echo($tokenError ? '<div class="alert alert-danger">Le nom de branche est vide</div>' : '')
                       ?>
                <input type="submit" value="Valider" name="send" class="btn btn-lg btn-primary btn-block">
            </form>
        </div>
        <?php include('footer.php'); ?>
    </body>
</html>
