<?php

/* * ************************************************************
 * Project: "Site web de saisie des notes "
 * File : functions.php
 * Css source : Bootstrap
 * Auuthor : Pierre Johner
 * Version : 1.0
 * Description : Page that contains functions for the project
 * ************************************************************ */
require_once './script/scriptPHP/mysql.php';

/**
 * Login
 * @global PDO $dbc global var for DB connection
 * @param int $id   identifiant of user
 * @param string $pw   password of user
 */
function login($id, $pw) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    $pw = mysql_real_escape_string($pw);
    $pw = sha1($pw);
    //id of the student and teacher are unique
    global $dbc;
    $req = "SELECT `idEleve`,`eleveNom`,`elevePrenom`,`eleveMotPasse` FROM `televe` WHERE `eleveMotPasse`='$pw' AND `eleveIdentifiant`='$id'";
    $ps = $dbc->query($req);
    $resultStudent = $ps->fetchAll(PDO::FETCH_ASSOC);

    $req = "SELECT `idProf`,`profNom`,`profPrenom`,`profMotPasse` FROM `tprof` WHERE `profMotPasse`='$pw' AND `profIdentifiant`='$id'";
    $ps = $dbc->query($req);
    $resultTeacher = $ps->fetchAll(PDO::FETCH_ASSOC);


    // build array and put log information in the session
    if (!empty($resultStudent)) {
        $user['grade'] = 0;
        $user['id'] = $resultStudent[0]['idEleve'];
        $user['lastname'] = $resultStudent[0]['elevePrenom'];
        $_SESSION['user'] = $user;
        $_SESSION['tmpId'] = 0;
        header('location: home.php ');
        exit;
    } elseif (!empty($resultTeacher)) {
        $user['grade'] = 1;
        $user['id'] = $resultTeacher[0]['idProf'];
        $user['lastname'] = $resultTeacher[0]['profPrenom'];
        $_SESSION['user'] = $user;
        $_SESSION['tmpId'] = 0;
        header('location: home.php ');
        exit;
    }
}

/**
 * Logout
 */
function logout() {
    //destroy information of user
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

/**
 * Display Navigation bar in all the page
 * @param string $pageName current's name page
 * @return string $display
 */
function NavBar($pageName) {
    //table for page permisson
    $arrayCommon = array(
        "Acceuil" => "home.php",
        "Gestion de Compte" => "managementAccount.php"
    );
    $arrayTeacher = array(
        "Gestion de Classes" => "managementClass.php",
        "Gestion de Travaux" => "managementWorks.php",
        "Gestion de Branches" => "managementBranchs.php"
    );

    $display = '<div class="masthead">
                              <h3 class="text-muted">Site web de saisie des notes</h3>        
                              <ul class="nav nav-justified">';

    foreach ($arrayCommon as $key => $value) {
        $display = $display . '<li ' . (strstr($pageName, $value) ? 'class="active"' : '') . '><a class="nav" href="' . $value . '">' . $key . '</a></li>';
    }
    // add managment's pages for teacher
    if ($_SESSION['user']['grade'] == 1) {
        foreach ($arrayTeacher as $key => $value) {
            $display = $display . '<li ' . (strstr($pageName, $value) ? 'class="active"' : '') . '><a class="nav" href="' . $value . '">' . $key . '</a></li>';
        }
    }
    $display = $display . '</ul>
                </div>';

    return $display;
}

/**
 * Display the header commun of all the page
 * @param string $pageName
 * @return string $display
 */
function HeaderDisplay($pageName) {
    $title = '';
    //$arrayName = preg_split('/(?=[A-Z])/', $pageName);
    $arrayName = explode('/', $pageName);
    $arrayName = array_reverse($arrayName);
    $arrayName = explode('.', $arrayName[0]);
    $arrayName = preg_split('/(?=[A-Z])/', $arrayName[0]);
    foreach ($arrayName as $value) {
        $title = $title . $value . '';
    }
    $display = '<title>' . $title . '</title>
               <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
               <!-- Bootstrap core CSS -->
               <link href="./css/bootstrap.css" rel="stylesheet">
               <link href="./css/bootstrap.min.css" rel="stylesheet">
               <link href="./css/bootstrap-theme.css" rel="stylesheet">
               <link href="./css/bootstrap-theme.min.css" rel="stylesheet">
               <link rel="icon" type="image/png" href="./multimedia/work.png" />';
    return $display;
}

/**
 * Display link at home.php for see note
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the student
 * @return string $display
 */
function linkToDisplay($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);

    global $dbc;
    $req = "SELECT `brancheNom`,`idBranche` FROM `tbranche` NATURAL JOIN ttravail NATURAL JOIN tarealise WHERE idEleve = $id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    $display = '<h2>Mes Branches :</h2>
                 <ul class="list-unstyled">';
    // build array for do array_unique
    $x = 0;
    foreach ($result as $value) {
        $link[$x] = "<li><a href=\"display.php?branch=" . $value['idBranche'] . "\" \"> " . $value['brancheNom'] . "</a></li>";
        $x++;
    }
    //delete same link
    $link = array_unique($link);

    foreach ($link as $value) {
        $display = $display . $value;
    }
    $display = $display . '</ul>';
    return $display;
}

/**
 * Return table of grade by branch and id student
 * @global PDO $dbc global var for DB connection
 * @param int $branch id of the branch selected
 * @param int $id id of student
 * @return string $display
 */
function GradeByBranchAndId($branch, $id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    $branch = mysql_real_escape_string($branch);
    //search information
    global $dbc;
    $req = "SELECT `travailIntitule`,`note`,`brancheNom` FROM `ttravail` NATURAL JOIN `tbranche` NATURAL JOIN `tarealise` WHERE idEleve = $id AND idBranche = $branch";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    //do the average of grades
    $average = 0;
    for ($index = 0; $index < count($result); $index++) {
        $average = $average + $result[$index]['note'];
    }
    $average = $average / count($result);

    $display = '<h1>' . $result[0]['brancheNom'] . '</h1>';
    $display = $display . '<table class="table table-bordered table-striped text-center">';
    $display = $display . '<tr>';
    for ($index = 0; $index < count($result); $index++) {
        $display = $display . '<td>' . $result[$index]['travailIntitule'] . '</td>';
    }
    $display = $display . '<td>Moyenne</td>';
    $display = $display . '</tr>';
    $display = $display . '<tr>';
    for ($index = 0; $index < count($result); $index++) {
        $display = $display . '<td>' . $result[$index]['note'] . '</td>';
    }
    $display = $display . "<td>$average</td>";
    $display = $display . '</tr>';
    $display = $display . '</table>';
    return $display;
}

/**
 * Check if Pw is correct (user must be login)
 * @global PDO $dbc global var for DB connection
 * @param int $pw Pw  verification
 * @return boolean
 */
function CheckPW($pw) {
    global $dbc;
    // search Pw in DB
    if ($_SESSION['user']['grade'] == 0) {
        $req = 'SELECT `eleveMotPasse` FROM `televe` WHERE `idEleve` =' . $_SESSION['user']['id'];
        $ps = $dbc->query($req);
        $result = $ps->fetchAll(PDO::FETCH_ASSOC);
        $pwDB = $result[0]['eleveMotPasse'];
    } else {
        $req = 'SELECT `profMotPasse` FROM `tprof` WHERE `idProf` =' . $_SESSION['user']['id'];
        $ps = $dbc->query($req);
        $result = $ps->fetchAll(PDO::FETCH_ASSOC);
        $pwDB = $result[0]['profMotPasse'];
    }
    $pw = sha1($pw);
    //compare information
    if ($pw == $pwDB)
        return true;
    else
        return false;
}

/**
 * Change Pw
 * @global PDO $dbc global var for DB connection
 * @param string $newPw
 * @param int $grade rank of user
 * @param int $id id of user
 */
function UpdatePW($newPw, $grade, $id) {
    //filter for sql injection
    $newPw = mysql_real_escape_string($newPw);
    $newPw = sha1($newPw);
    global $dbc;
    if ($grade == 0) {
        $req = "UPDATE `televe` SET `eleveMotPasse`= '$newPw' WHERE `idEleve`=$id ";
        $ps = $dbc->query($req);
        $result = $ps->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $req = "UPDATE `tprof` SET `profMotPasse`='$newPw' WHERE `idProf`=$id";
        $ps = $dbc->query($req);
        $result = $ps->fetchAll(PDO::FETCH_ASSOC);
    }
}

/**
 * Return table of all the branch
 * @global PDO $dbc global var for DB connection
 * @return string
 */
function ShowBranchs() {
    //search information
    global $dbc;
    $req = "SELECT * FROM `tbranche`";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    $display = '<table class="table table-bordered table-striped text-center">
                <tr>
                    <td class="lead">Nom</td>
                    <td class="lead">Modifier</td>
                    <td class="lead">Suprimmer</td>
                </tr>';
    foreach ($result as $value) {
        $display = $display . '<tr>
                                  <td>' . $value['brancheNom'] . '</td>
                                  <td><a href="./managementBranchsForm.php?id=' . $value['idBranche'] . '"><img src="./multimedia/modi.png" alt="modification"></a></td>		
                                  <td><a href="./deleteBranch.php?id=' . $value['idBranche'] . '"><img src="./multimedia/del.png" alt="delete"></a></td>
                             </tr> ';
    }
    $display = $display . '</table>';

    return $display;
}

/**
 * Add branch
 * @global PDO $dbc global var for DB connection
 * @param string $nameBranch name of the branch
 */
function AddBranch($nameBranch) {
    //filter for sql injection
    $nameBranch = mysql_real_escape_string($nameBranch);
    $token = true;
    //search if branch early exist
    global $dbc;
    $req = "SELECT `brancheNom` FROM `tbranche`";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $value) {
        if ($value['brancheNom'] == $nameBranch)
            $token = false;
    }
    if ($token) {
        $req = "INSERT INTO `tbranche`(`brancheNom`) VALUES ('$nameBranch')";
        $ps = $dbc->query($req);
        $ps->fetchAll(PDO::FETCH_ASSOC);
    }
}

/**
 * Delete branch
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the branch for delete
 */
function DeleteBranch($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;

    $req = "SELECT `idTravail` FROM `ttravail` WHERE `idBranche`=$id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $value) {
        DeleteWork($value['idTravail']);
    }

    $req = "DELETE FROM `tbranche` WHERE `idBranche`=$id";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Upade branch
 * @global PDO $dbc global var for DB connection
 * @param int $id id of branch for modification
 * @param string $nameBranch new name for branch
 */
function UpdateBranch($id, $nameBranch) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    $nameBranch = mysql_real_escape_string($nameBranch);
    global $dbc;
    $req = "UPDATE `tbranche` SET `brancheNom`='$nameBranch' WHERE `idBranche`=$id";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);

    // set to 0 for resat addPage
    $_SESSION['tmpId'] = 0;
}

/**
 * Return name of branch
 * @global PDO $dbc global var for DB connection
 * @param int $id id of branch for name
 * @return string $result
 */
function GetBranchById($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    $req = "SELECT `brancheNom` FROM `tbranche` WHERE `idBranche`=$id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    // return directly string of Branch's name
    return $result[0]['brancheNom'];
}

/**
 * Return table of class(except class 1)
 * @global PDO $dbc global var for DB connection
 * @return string 
 */
function ShowClass() {
    global $dbc;
    //not take the empty class 
    $req = "SELECT * FROM `tclasse` WHERE `idClasse` <> 1";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    $display = '<table class="table table-bordered table-striped text-center">
                <tr>
                    <td class="lead">Nom</td>
                    <td class="lead">Modifier</td>
                    <td class="lead">Suprimmer</td>
                    <td class="lead">Gestion</td>
                </tr>';
    foreach ($result as $value) {
        $display = $display . '<tr>
                                  <td>' . $value['classeNom'] . '(' . $value['classeAnnee'] . ')</td>
                                  <td><a href="./managementClassForm.php?id=' . $value['idClasse'] . '"><img src="./multimedia/modi.png" alt="modification"></a></td>		
                                  <td><a href="./deleteClass.php?id=' . $value['idClasse'] . '"><img src="./multimedia/del.png" alt="delete"></a></td>
                                  <td><a href="./managementParticipateClass.php?id=' . $value['idClasse'] . '"><img src="./multimedia/para.png" alt="delete"></a></td>   
                             </tr> ';
    }
    $display = $display . '</table>';

    return $display;
    ;
}

/**
 * Delete class by id
 * @global PDO $dbc global var for DB connection
 * @param int $id id of class for delete
 */
function DeleteClass($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    // delete all informations in db who refers the class
    $req = "UPDATE `televe` SET `idClasse`= 1 WHERE `idClasse`=$id";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);

    $req = "SELECT `idTravail` FROM `ttravail` WHERE `idClasse`=$id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $value) {
        $req = "DELETE FROM `tarealise` WHERE `idTravail`=" . $value['idTravail'];
        $ps = $dbc->query($req);
        $ps->fetchAll(PDO::FETCH_ASSOC);
    }

    $req = "DELETE FROM `ttravail` WHERE `idClasse`=$id";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);

    //after delete class
    $req = "DELETE FROM `tclasse` WHERE `idClasse`=$id";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Add class
 * @global PDO $dbc global var for DB connection
 * @param string $nameClass name of the class    
 * @param string $yearClass year of the class
 */
function AddClass($nameClass, $yearClass) {
    //filter for sql injection
    $nameClass = mysql_real_escape_string($nameClass);
    $yearClass = mysql_real_escape_string($yearClass);
    $token = true;
    // search class for not insert seconde time the class
    global $dbc;
    $req = "SELECT `classeNom`,`classeAnnee` FROM `tclasse`";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $value) {
        if ($value['classeAnnee'] == $yearClass && $value['classeNom'] == $nameClass)
            $token = false;
    }
    if ($token) {
        $req = "INSERT INTO `tclasse`( `classeNom`, `classeAnnee`) VALUES ('$nameClass',$yearClass)";
        $ps = $dbc->query($req);
        $ps->fetchAll(PDO::FETCH_ASSOC);
    }
}

/**
 * Update of class
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the class for modification
 * @param string $nameClass new class name   
 * @param string $yearClass new year name
 */
function UpdateClass($id, $nameClass, $yearClass) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    $nameClass = mysql_real_escape_string($nameClass);
    $yearClass = mysql_real_escape_string($yearClass);
    global $dbc;
    $req = "UPDATE `tclasse` SET `classeNom`='$nameClass',`classeAnnee`=$yearClass WHERE `idClasse`=$id";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);

    // set to o for add page
    $_SESSION['tmpId'] = 0;
}

/**
 * Return information of class by id
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the class
 * @return array $result[0]
 */
function GetClassById($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    $req = "SELECT `classeNom` as `nameClass`, `classeAnnee` AS `yearClass` FROM `tclasse` WHERE `idClasse`=$id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    return $result[0];
}

/**
 * Return table of student by class
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the class
 * @return string $display
 */
function ShowParticipateByClass($id, $token) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    //search information for table
    global $dbc;
    $req = "SELECT `eleveNom`,`elevePrenom`,`idEleve` FROM `televe` WHERE `idClasse`=$id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    $display = '<form class="form-signin" method="post" action="managementParticipateClass.php?id=' . $id . '">
                 <table class="table table-bordered table-striped text-center">
                  <tr>
                    <td class="lead">Nom</td>
                    <td class="lead">Suprimmer</td>
                  </tr>';
    foreach ($result as $value) {
        $display = $display . '<tr>
                                  <td>' . $value['elevePrenom'] . ' ' . $value['eleveNom'] . '</td>                             	
                                  <td><a href="./deleteParticipate.php?id=' . $value['idEleve'] . '&amp;class=' . $id . '"><img src="./multimedia/del.png" alt="delete"></a></td>
                             </tr> ';
    }
    $students = ' <option value=""> </option>';
    foreach (GetAllStudentsExceptClassById($id) as $value) {
        $students = $students . '<option value="' . $value['idEleve'] . '">' . $value['elevePrenom'] . ' ' . $value['eleveNom'] . '</option>';
    }

    $display = $display . '<tr>
                            <td>                             
                              <select class="form-control text-center" name="student" autofocus required>
                                ' . $students . '
                                </select> 
                             </td>	
                             <td>
                               <input type="submit" value="Valider" name="send" class="btn btn-lg btn-primary btn-block">                               
                             </td>                             
                           </tr> ';
    if (!$token)
        $display = $display . '<tr><td colspan="2"><div class="alert alert-danger">Participant non valide</div></td></tr>';

    $display = $display . '</table></form>';

    return $display;
}

/**
 * Return all student except one class
 * @global PDO $dbc global var for DB connection
 * @param int $id id of excluded class
 * @return array $result
 */
function GetAllStudentsExceptClassById($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    $req = "SELECT `eleveNom`,`elevePrenom`,`idEleve` FROM `televe` WHERE `idClasse` <> $id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

/**
 * Delete Participate of class
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the student
 */
function DeleteParticipate($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    $req = "UPDATE `televe` SET `idClasse`= 1  WHERE `idEleve` = $id";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Add Participate to class
 * @global PDO $dbc global var for DB connection
 * @param int $student id of student for add
 * @param int $class   id of class
 */
function AddParticipate($student, $class) {
    //filter for sql injection
    $class = mysql_real_escape_string($class);
    $student = mysql_real_escape_string($student);
    global $dbc;
    $req = "UPDATE `televe` SET `idClasse`= $class WHERE `idEleve`=$student";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retrn table of work by teacher
 * @global PDO $dbc global var for DB connection
 * @param int $idTeacher id of teacher for search
 * @return string $display
 */
function ShowWorksByTeacher($idTeacher) {
    //filter for sql injection
    $idTeacher = mysql_real_escape_string($idTeacher);
    //search information for the table
    global $dbc;
    $req = "SELECT `idTravail`,`travailIntitule`,`travailDate`,`brancheNom`,`classeNom`,`classeAnnee` FROM `ttravail` NATURAL JOIN `tclasse` NATURAL JOIN `tbranche` WHERE `idProf` = $idTeacher";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    $display = '<table class="table table-bordered table-striped text-center">
                <tr>
                    <td class="lead">Intitulé</td>
                    <td class="lead">Date</td>
                    <td class="lead">Branche</td>
                    <td class="lead">Classe</td>
                    <td class="lead">Modifier</td>
                    <td class="lead">Suprimmer</td>
                    <td class="lead">Gestion</td>
                </tr>';
    foreach ($result as $value) {
        $display = $display . '<tr>
                                  <td>' . $value['travailIntitule'] . '</td>
                                  <td>' . $value['travailDate'] . '</td>
                                  <td>' . $value['brancheNom'] . '</td>
                                  <td>' . $value['classeNom'] . '(' . $value['classeAnnee'] . ')</td>
                                  <td><a href="./managementWorksForm.php?id=' . $value['idTravail'] . '"><img src="./multimedia/modi.png" alt="modification"></a></td>		
                                  <td><a href="./deleteWork.php?id=' . $value['idTravail'] . '"><img src="./multimedia/del.png" alt="delete"></a></td>
                                  <td><a href="./managementGrades.php?id=' . $value['idTravail'] . '"><img src="./multimedia/para.png" alt="delete"></a></td>   
                             </tr> ';
    }
    $display = $display . '</table>';

    return $display;
}

/**
 * Return Option(html) of class in the DB
 * @global PDO $dbc global var for DB connection
 * @param int $idSelected default option selected
 * @return string $options
 */
function GetAllClassHTMLOption($idSelected = 0) {
    global $dbc;
    //search information
    $req = "SELECT * FROM `tclasse` WHERE `idClasse`<>1 ";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    //rebuild a array for use fonction
    $counter = 0;
    $list = array();
    foreach ($result as $value) {
        $list[$counter]['name'] = $value['classeNom'] . '(' . $value['classeAnnee'] . ')';
        $list[$counter]['id'] = $value['idClasse'];
        $counter++;
    }
    return GetListToHTMLOption($list, $idSelected);
}

/**
 * Return Options(html)of branch from DB 
 * @global PDO $dbc global var for DB connection
 * @param int $idSelected default selected option
 * @return string $options
 */
function GetAllBranchHTMLOption($idSelected = 0) {
    global $dbc;
    $req = "SELECT `idBranche` AS 'id' ,`brancheNom` AS 'name' FROM `tbranche`";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    return GetListToHTMLOption($result, $idSelected);
}

/**
 * Return option(html) from array
 * @param array $list array of item for option ('id'->0/'name'=>'')
 * @param int $idSelected default option selected
 * @return string
 */
function GetListToHTMLOption($list, $idSelected) {
    $options = '<option> </option>';
    foreach ($list as $value) {
        $options = $options . '<option value="' . $value['id'] . '"' . ( $value['id'] == $idSelected ? 'selected' : '') . '>' . $value['name'] . '</option>';
    }
    return $options;
}

/**
 * Return bool if exist class
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the class
 * @return boolean
 */
function GetIfExistClassById($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    $req = "SELECT * FROM `tclasse` WHERE `idClasse` = $id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    return !empty($result);
}

/**
 * Return bool if branch exist
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the branch
 * @return boolean
 */
function GetIfExistBranchById($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    $req = "SELECT * FROM `tbranche` WHERE `idBranche` = $id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    return !empty($result);
}

/**
 * Return bool if student exist
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the student
 * @return boolean
 */
function GetIfExistStudentById($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    $req = "SELECT * FROM `televe` WHERE `idEleve` = $id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    return !empty($result);
}

/**
 * Return bool if student exist
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the student
 * @return boolean
 */
function GetIfExistWorkById($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    $req = "SELECT * FROM `ttravail` WHERE `idTravail` = $id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    return !empty($result);
}

/**
 * Add Work
 * @global PDO $dbc global var for DB connection
 * @param int $idTeacher id of the teacher
 * @param int $idClass id of the class
 * @param string $title title of work
 * @param int $idBranch id of the branch
 * @param date $date date of work(YYYY-MM-DD)
 */
function AddWork($idTeacher, $idClass, $title, $idBranch, $date) {
    //filter for sql injection
    $idTeacher = mysql_real_escape_string($idTeacher);
    $idClass = mysql_real_escape_string($idClass);
    $title = mysql_real_escape_string($title);
    $idBranch = mysql_real_escape_string($idBranch);
    $date = mysql_real_escape_string($date);

    global $dbc;
    $req = "INSERT INTO `ttravail`(`travailIntitule`, `travailDate`, `idBranche`, `idClasse`, `idProf`) 
            VALUES ('$title','$date',$idBranch,$idClass,$idTeacher)";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);
    $idWork = $dbc->lastInsertId();

    //add relation for the grades
    AddPerform($idClass, $idWork);
}

/**
 * Update Work
 * @global PDO $dbc global var for DB connection
 * @param int $idWork id of work for modification
 * @param int $idTeacher new id of teacher
 * @param int $idClass new id of class
 * @param string $title new title
 * @param int $idBranch new id of branch
 * @param date $date new date
 */
function UpdateWork($idWork, $idTeacher, $idClass, $title, $idBranch, $date) {
    //filter for sql injection
    $idWork = mysql_real_escape_string($idWork);
    $idTeacher = mysql_real_escape_string($idTeacher);
    $idClass = mysql_real_escape_string($idClass);
    $title = mysql_real_escape_string($title);
    $idBranch = mysql_real_escape_string($idBranch);
    $date = mysql_real_escape_string($date);
    UpdatePerform($idWork, $idClass);
    global $dbc;
    $req = "UPDATE `ttravail` SET `travailIntitule`='$title',
           `travailDate`='$date',`idBranche`=$idBranch,`idClasse`=$idClass,`idProf`=$idTeacher WHERE `idTravail` = $idWork";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);

    // set to 0 for addpage
    $_SESSION['tmpIdTeacher'] = 0;
    $_SESSION['tmpId'] = 0;
}

/**
 * Add performant for a work
 * @global PDO $dbc global var for DB connection
 * @param int $idClass id of class
 * @param int $idWork id of work
 */
function AddPerform($idClass, $idWork) {
    //filter for sql injection
    $idClass = mysql_real_escape_string($idClass);
    $idWork = mysql_real_escape_string($idWork);
    // search information for add relation
    global $dbc;
    $req = "SELECT `idEleve` FROM `televe` WHERE `idClasse`=$idClass";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $value) {
        $req = "INSERT INTO `tarealise`(`idEleve`, `idTravail`, `note`) VALUES (" . $value['idEleve'] . ",$idWork,0)";
        $ps = $dbc->query($req);
        $ps->fetchAll(PDO::FETCH_ASSOC);
    }
}

/**
 * Update performant for a work
 * @param int $idWork
 * @param int $idClass
 */
function UpdatePerform($idWork, $idClass) {
    DeletePerform($idWork);
    AddPerform($idClass, $idWork);
}

/**
 * Delete performe of a work
 * @global PDO $dbc global var for DB connection
 * @param int $idWork id of the work
 */
function DeletePerform($idWork) {
    //filter for sql injection
    $idWork = mysql_real_escape_string($idWork);
    global $dbc;
    $req = "DELETE FROM `tarealise` WHERE `idTravail`=$idWork ";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Return work by id
 * @global PDO $dbc global var for DB connection
 * @param int $id id of the work
 * @return array $result
 */
function GetWorkById($id) {
    //filter for sql injection
    $id = mysql_real_escape_string($id);
    global $dbc;
    $req = "SELECT `travailIntitule` AS 'title',`travailDate`AS 'date',`idBranche`AS 'branch',`idClasse`AS 'class' FROM `ttravail`  WHERE `idTravail`=$id";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);

    return $result[0];
}

/**
 * Delete work
 * @global PDO $dbc global var for DB connection
 * @param int $idWork id of the work for delete
 */
function DeleteWork($idWork) {
    //filter for sql injection
    $idWork = mysql_real_escape_string($idWork);
    DeletePerform($idWork);
    global $dbc;
    $req = "DELETE FROM `ttravail` WHERE `idTravail`=$idWork";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Return table(html) of the grade for a work
 * @param int $idWork id of the work
 * @return string $display
 */
function ShowGradeByWork($idWork, $error) {
    //search information
    $result = GetGradeByWork($idWork);

    $display = '<form class="form-signin" method="post" action="managementGrades.php?id=' . $idWork . '">
                  <table class="table table-bordered table-striped text-center">
                    <tr>
                      <td class="lead">Nom</td>
                      <td class="lead">Note</td>
                    </tr>';
    foreach ($result as $value) {
        $display = $display . '<tr>
                                  <td>' . $value['eleveNom'] . ' ' . $value['elevePrenom'] . '</td>
                                  <td><input type="number" min="0" max="6" name="grade' . $value['idEleve'] . '" value="' . $value['note'] . '" class="form-control" placeholder="4" required>';
        if (isset($error['grade' . $value['idEleve']]))
            $display = $display . '<div class="alert alert-danger">Votre note est incorrecte</div>';

        $display = $display . '</td>		
                             </tr> ';
    }
    $display = $display . ' </table><input type="submit" value="Valider" name="send" class="btn btn-lg btn-primary btn-block"></form>';

    return $display;
}

/**
 * Get grade by work
 * @global PDO $dbc global var for DB connection
 * @param int $idWork id of the work
 * @return array $result
 */
function GetGradeByWork($idWork) {
    //filter for sql injection
    $idWork = mysql_real_escape_string($idWork);
    global $dbc;
    $req = "SELECT `note`,`eleveNom`,`elevePrenom`,`idEleve` FROM `tarealise` NATURAL JOIN `televe` WHERE `idTravail` =$idWork";
    $ps = $dbc->query($req);
    $result = $ps->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

/**
 * Add Grade 
 * @global PDO $dbc global var for DB connection
 * @param int $idStudent id of the student
 * @param int $idWork id of the work
 * @param int $grade grade of the student
 */
function AddGrade($idStudent, $idWork, $grade) {
    //filter for sql injection
    $idStudent = mysql_real_escape_string($idStudent);
    $idWork = mysql_real_escape_string($idWork);
    $grade = mysql_real_escape_string($grade);
    global $dbc;
    $req = "UPDATE `tarealise` SET `note`=$grade WHERE `idEleve`=$idStudent AND `idTravail`= $idWork";
    $ps = $dbc->query($req);
    $ps->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * For display a tree file
 * @param type $idPage id of the page
 * @return string $display
 */
function fileTree($idPage) {
    $display = '<div>';
    $tree = array(
        0 => "home",
        1 => "managementAccount",
        2 => "managementClass",
        3 => "managementClassForm",
        4 => "managementParticipateClass",
        5 => "managementWorks",
        6 => "managementWorksForm",
        7 => "managementGrades",
        8 => "managementBranchs",
        9 => "managementBranchsForm",
        10 => "display"
    );
    $treeDisplay = array(
        0 => "Acceuil",
        1 => "Gestion de Compte",
        2 => "Gestion de Classes",
        3 => "Gestion de Classe Formulaire",
        4 => "Gestion des Participants",
        5 => "Gestion de Travaux",
        6 => "Gestion de Travaux Formulaire",
        7 => "Gestion des Notes",
        8 => "Gestion des Banches",
        9 => "Gestion des Branches Formulaire",
        10 => "Affichage"
    );

    $arrayIdPage = str_split($idPage, 2);
    $lenght = strlen($idPage);
    for ($index = 0; $index < $lenght / 2; $index++) {
        if ($arrayIdPage[$index][0] == 0) {
            $num = substr($arrayIdPage[$index], -1);
        } else {
            $num = $arrayIdPage[$index];
        }
        if ($index != 0)
            $display = $display . ' > ';
        if ($index != $lenght / 2 - 1)
            $display = $display . '<a href="./' . $tree[$num] . '.php">' . $treeDisplay[$num] . '</a>';
        else
            $display = $display . $treeDisplay[$num];
    }

    return $display . '</div>';
}

?>
