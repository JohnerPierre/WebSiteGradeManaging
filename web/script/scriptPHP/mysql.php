<?php
/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : mysql.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains the connection information to the database
 * ************************************************************ */
DEFINE('DB_USER', 'admin');
DEFINE('DB_PASSWORD', 'admin');
DEFINE('DB_HOST', '127.0.0.1');
DEFINE('DB_NAME', 'mgdb');



//--------------------------------------------------------------------------
function dbConnect() {
    global $dbc;
    try {
        $dbc = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD,
                        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage() . '<br />';
        echo 'N° : ' . $e->getCode();
        die('Could not connect to MySQL');
    }
}
?>
