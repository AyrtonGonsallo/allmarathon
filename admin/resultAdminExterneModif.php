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

if (isset($_POST['modifier'])) {

    try {
         $req4 = $bdd->prepare("UPDATE champion_admin_externe_palmares SET Rang = :rang, Temps=:Temps, utilisateur=:utilisateur, Date = :date, CompetitionFr = :fr WHERE  ID = :resultID");

         $req4->bindValue('rang',$_POST['rang'], PDO::PARAM_INT);
         $req4->bindValue('Temps',$_POST['Temps'], PDO::PARAM_STR);
         $req4->bindValue('date',$_POST['date'], PDO::PARAM_STR);
         $req4->bindValue('utilisateur',$_POST['utilisateur'], PDO::PARAM_STR);
         $req4->bindValue('fr',$_POST['fr'], PDO::PARAM_STR);
         $req4->bindValue('resultID',$_POST['resultID'], PDO::PARAM_STR);


         $req4->execute();
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
        <legend>Modification des resultats administr&eacute;s</legend>
        
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
                        <td colspan="2"><input type="submit" name="modifier" value="Modifier"/></td>
                    </tr>
                </table>
            </form>
        </fieldset>
    </body>
    </html>