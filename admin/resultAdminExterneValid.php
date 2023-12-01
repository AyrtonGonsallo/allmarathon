<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 
//verif de validiter session
if (!isset($_SESSION['admin']) || !isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

require_once '../database/connexion.php';

if (isset($_POST['valider'])) {
    try {
        $req_suppr = $bdd->prepare("DELETE FROM `evresultats` WHERE Rang=:r and EvenementID=:e");

        $req_suppr->bindValue('r',$_POST['rang'], PDO::PARAM_INT);
        $req_suppr->bindValue('e',$_POST['EvenementID'], PDO::PARAM_INT);


        $req_suppr->execute();
    }
    catch(Exception $e)
    {
       die('Erreur : ' . $e->getMessage());
   }
    try {
         $req_add = $bdd->prepare("INSERT INTO `evresultats`( `Rang`,  `Temps`, `EvenementID`, `ChampionID`) VALUES (:rang,:Temps,:ev,:champ)");

         $req_add->bindValue('rang',$_POST['rang'], PDO::PARAM_INT);
         $req_add->bindValue('Temps',$_POST['Temps'], PDO::PARAM_STR);
         $req_add->bindValue('ev',$_POST['EvenementID'], PDO::PARAM_INT);
         $req_add->bindValue('champ',$_POST['ChampionID'], PDO::PARAM_INT);


         $req_add->execute();
     }
     catch(Exception $e)
     {
        die('Erreur : ' . $e->getMessage());
    }
    try {
        $req_del_from_palm = $bdd->prepare("DELETE FROM champion_admin_externe_palmares WHERE ID = :id LIMIT 1");
        $req_del_from_palm->bindValue('id',$_POST['ID'], PDO::PARAM_INT);
        $req_del_from_palm->execute();
    }
    catch(Exception $e)
    {
     die('Erreur : ' . $e->getMessage());
 }

    header('location: resultAdminExterne.php');
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
    <link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.pager.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $("#tbl1")
            .tablesorter({widthFixed: false, widgets: ['zebra']})
            .tablesorterPager({container: $("#pager")});
        }
        );

    </script>
    <script type="text/javascript">
        $(function() {
            $('#datepicker').datepicker({
                changeMonth: true,
                changeYear: true
            });
            $('#datepicker').datepicker('option', {dateFormat: 'yy-mm-dd'});
        });
    </script>
    <title>allmarathon admin externe resultats</title>




</head>

<body>
    <?php require_once "menuAdmin.php"; ?>

    <fieldset style="float:left;">
        <legend>Validation des resultats administr&eacute;s</legend>
        
        <form method="post" action="">
            <table>
                <tr>
                    <td>Rang: </td>
                    <td><input type="text" name="rang" value="<?php echo $_POST['rang'] ?>" /></td>
                </tr>
                <tr>
                    <td>Evenement: </td>
                    <td><input type="text" name="fr" value="<?php echo $_POST['CompetitionFr'] ?>" /></td>
                </tr>
                <tr>
                    <td>utilisateur: </td>
                    <td><input type="text" name="utilisateur" value="<?php echo $_POST['utilisateur'] ?>" /></td>
                </tr>
                <input type="hidden" name="EvenementID" value="<?php echo $_POST['EvenementID'] ?>" />
                <input type="hidden" name="ID" value="<?php echo $_POST['ID'] ?>" />
                <input type="hidden" name="ChampionID" value="<?php echo $_POST['ChampionID'] ?>" />
                <tr>
                    <td>Temps: </td>
                    <td><input type="text" name="Temps" value="<?php echo $_POST['Temps'] ?>" /></td>
                </tr>
                
                        <tr>
                            <td>Date: </td>
                            <td><input type="text" name="date" id="datepicker" value="<?php echo $_POST['date'] ?>" /></td>
                        </tr>
                        
                       
                     <tr>
                        <input type="hidden" name="resultID" value="<?php echo $_POST['extern_edit_id'] ?>" />
                        <td colspan="2"><input type="submit" name="valider" value="Valider"/></td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </body>
    </html>