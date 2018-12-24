<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : managementAccount.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains form for change PW
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';
        const idTree = "0001";

if (!isset($_SESSION['user']['grade'])) {
    header('location: index.php ');
    exit;
}

$tokenOld = false;
$tokenNew = false;

if (isset($_REQUEST['send'])) {
    $token = true;
    $oldPw = trim($_REQUEST['oldPW']);
    $newPw = trim($_REQUEST['newPW']);
    $newPwV = trim($_REQUEST['newPWV']);

    if (!CheckPW($oldPw)) {
        $token = false;
        $tokenOld = true;
    }

    if ($newPw != $newPwV || $newPw == '') {
        $token = false;
        $tokenNew = true;
    }

    if ($token) {
        UpdatePW($newPw, $_SESSION['user']['grade'], $_SESSION['user']['id']);
        header('location: home.php ');
        exit;
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
            <h1>Modification du Mot de Passe:</h1>
            <form class="form-signin" role="form">
                <label for="oldPW">Ancien Mot de Passe:</label>
                <input type="password" name="oldPW" class="form-control" id="oldPW" placeholder="Mot de Passe" required autofocus>
                <?php
                echo(($tokenOld) ? '<div class="alert alert-danger">Ancien Mot de Passe incorrecte</div>' : '')
                ?>
                <label for="newPW">Nouveau Mot de Passe:</label>
                <input type="password" name="newPW" class="form-control" id="newPW" placeholder="Nouveau Mot de Passe" required>
                <label for="newPWV">Vérification:</label>
                <input type="password" name="newPWV" class="form-control" id="newPWV" placeholder="Nouveau Mot de Passe" required>
                <?php
                echo(($tokenNew) ? '<div class="alert alert-danger">Combinaison des Mots de Passe incorrecte</div>' : '')
                ?>
                <input type="submit" value="Valider" name="send" class="btn btn-lg btn-primary btn-block">
            </form>
        </div>
        <<?php include('footer.php'); ?>
    </body>
</html>
