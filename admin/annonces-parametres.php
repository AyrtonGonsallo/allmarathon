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

if($_SESSION['admin'] == false && $_SESSION['ev'] == false){
    header('Location: login.php');
    exit();
}

require_once '../database/connexion.php';

$page = 0;
if(isset($_GET['page']) && is_numeric($_GET ['page']))
    $page = $_GET['page'];


try{

//     $req1 = $bdd->prepare("SELECT a.*,u.username from annonce a,phpbb_users u where u.user_id =a.User_ID ORDER BY Date_publication DESC");
//     $req1->execute();
//     $result1= array();
//     while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
//         array_push($result1, $row);
//     }

//      $req2 = $bdd->prepare("SELECT a.*,u.username from annonce a,phpbb_users u where u.user_id =a.User_ID ORDER BY Date_modification DESC");
//     $req2->execute();
//     $result2= array();
//     while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
//         array_push($result2, $row);
//     }
   $req3 = $bdd->prepare("SELECT * FROM options WHERE id=1");
   $req3->execute();
   $normal= $req3->fetch(PDO::FETCH_ASSOC);

   $req4 = $bdd->prepare("SELECT * FROM options WHERE id=2");
   $req4->execute();
   $premium= $req4->fetch(PDO::FETCH_ASSOC);

}

catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
    <link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.pager.js"></script>
    <script type="text/javascript">
        $(document).ready(function()
        {
            $("table.tablesorter")
            .tablesorter({widthFixed: false, widgets: ['zebra']})
            .tablesorterPager({container: $("#pager")});
        }
        );

    </script>
    <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin</title>

    <script type="text/javascript">
        $(document).ready(function()
        {
	// $(function() {
       $('#datepicker').datepicker({
        changeMonth: true,
        changeYear: true
    });
       $('#datepicker').datepicker('option', {dateFormat: 'yy-mm-dd'});
       $('#datepicker2').datepicker({
        changeMonth: true,
        changeYear: true
    });
       $('#datepicker2').datepicker('option', {dateFormat: 'yy-mm-dd'});
   }
   );

</script>

<!-- InstanceEndEditable -->
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <?php

    try {

        if(isset($_POST['p1'])){
            $req = $bdd->prepare("UPDATE options SET valeur=:valeur WHERE id=1");
            $req->bindValue('valeur',$_POST['jvn'], PDO::PARAM_INT);
            $req->execute();
            header('Location: annonces-parametres.php');
        }
        if(isset($_POST['p2'])){
            $reqp = $bdd->prepare("UPDATE options SET valeur=:valeur WHERE id=2");
            $reqp->bindValue('valeur',$_POST['jvp'], PDO::PARAM_INT);
            $reqp->execute();
            header('Location: annonces-parametres.php');
        }

    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }


    ?>
    <fieldset >
        <form name="f" action="" method="post">
           <table>
               <tr>
                   <td>jours validite normal</td>
                   <td><input type="text" name="jvn" value="<?php echo $normal['valeur'] ?>" /></td>
                   <td><input type="submit" name="p1" value="modifier" /></td>
               </tr>
               
               <tr>
                   <td>jours validite premium</td>
                   <td><input type="text" name="jvp" value="<?php echo $premium['valeur'] ?>" /></td>
                   <td><input type="submit" name="p2" value="modifier" /></td>
               </tr>
           </table>
       </form>
   </fieldset>

</body>
<!-- InstanceEnd --></html>

