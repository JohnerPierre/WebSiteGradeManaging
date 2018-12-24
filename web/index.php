<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : index.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : login page
 * ************************************************************ */
session_start();
require_once './script/scriptPHP/mysql.php';
dbConnect();
require_once './script/scriptPHP/functions.php';

if (isset($_SESSION['user']['grade'])) {
    header('location: home.php ');
    exit;
}

if (isset($_REQUEST['login'])) {
    $idUser = trim($_REQUEST['id']);
    $pw = trim($_REQUEST['pw']);
    if ($idUser != '' && $pw != '')
        login($_REQUEST['id'], $_REQUEST['pw']);

    //here if login fail
    $id = (!empty($_REQUEST['id']) ? $_REQUEST['id'] : '');
}

fileTree("0304");
?>
<!doctype html>
<html lang="fr">
    <head>
        <?php echo HeaderDisplay($_SERVER['SCRIPT_FILENAME']); ?>
        <!-- Bootstrap specialize CSS -->
        <link href="./css/signin.css" rel="stylesheet">            
    </head>
    <body>
        <div class="container">
            <form class="form-signin" method="post" action="index.php">
                <h2 class="form-signin-heading">Connexion</h2>
                <input type="text" class="form-control" placeholder="Identifiant" name="id" required autofocus value="<?php echo(isset($_REQUEST['login']) ? $id : '') ?>">       
                <input type="password" class="form-control" name="pw" placeholder="Mot De Passe" required>        
                <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Connexion</button>
                <?php
                echo(isset($_REQUEST['login']) ? '<div class="alert alert-danger">Combinaison Identifiant/Mot de Passe incorrecte</div>' : '')
                ?>
            </form>	
        </div> <!-- /container -->
        <?php include('footer.php'); ?>
    </body>
</html>
