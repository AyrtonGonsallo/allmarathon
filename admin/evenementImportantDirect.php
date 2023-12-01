<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
	{
		header('Location: login.php');
                exit();
    }

    if($_SESSION['admin'] == false && $_SESSION['user'] == false){
        header('Location: login.php');
        exit();
    }

    require_once '../database/connexion.php';

    try{
            $req = $bdd->prepare("SELECT * FROM evenementimportants ORDER BY ID DESC");
            $req->execute();
            $result1= array();
            while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
                }

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
<link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript" src="../Scripts/tiny_mce_init.js"></script>
<title>allmarathon admin</title>
</head>

<body>
<?php require_once "menuAdmin.php"; ?>

<fieldset style="float:left;">
<legend>Liste des evenements importants</legend>
<div>
    <table class="tab1">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php //while($evenement = mysql_fetch_array($result1)){
            foreach ($result1 as $evenement) {
            echo '<tr align="center" ><td><a href="evenementImportantDirectDetail.php?evenement_id='.$evenement['ID'].'">'.$evenement['Nom'].'</a></td>';
            echo '<td>
                <a href="evenementImportantDirectDetail.php?evenement_id='.$evenement['ID'].'"><img title="live" src="../images/live.png" alt="live" width="32" style="border:0;" /></a>
                <a href="evenementImportantDetail.php?evenementID='.$evenement['ID'].'"><img title="modifier les informations" src="../images/edit2.png" alt="edit" width="32" style="border:0;" /></a>
                </td></tr>';
        } ?>
    </tbody>
    </table>

</div>
</fieldset>
</body>
</html>