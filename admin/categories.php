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


$msg="";
if(isset($_POST['ok'])){

   try {
    $req = $bdd->prepare("INSERT INTO categorie (nom_categorie) VALUES (:nom_categorie)");

    $req->bindValue('nom_categorie',$_POST['nom'], PDO::PARAM_STR);
    $req->execute();
    $msg='ajout avec succes';
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

}

try{

    $req1 = $bdd->prepare("SELECT * from categorie");
    $req1->execute();
    $result1= array();
    while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
        array_push($result1, $row);
    }
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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


    <fieldset style="float:left;">
        <legend>Gestion des Categories d'annonces</legend>
        <div align="left">
            <div id="pager" class="pager">
             <form>
              <img src="../fonction/tablesorter/first.png" class="first"/>
              <img src="../fonction/tablesorter/prev.png" class="prev"/>
              <input type="text" class="pagedisplay"/>
              <img src="../fonction/tablesorter/next.png" class="next"/>
              <img src="../fonction/tablesorter/last.png" class="last"/>
              <select class="pagesize">
               <option selected="selected"  value="10">10</option>

               <option value="20">20</option>
               <option value="30">30</option>
               <option  value="40">40</option>
           </select>
       </form>
   </div>
   <table class="tablesorter">
    <thead>
        <tr>
            <th>ID </th>
            <th>nom</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php //while($annonces = mysql_fetch_array($result1)){
            foreach ($result1 as $annonces) {
                echo "<tr align=\"center\" ><td>".$annonces['ID']."</td><td>".stripslashes($annonces['nom_categorie'])."</td>
                <td>
                    <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer cette annonce : ".$annonces['nom_categorie']." ?')) { location.href='supprimercategorie.php?ID="
                    .$annonces['ID']."';} else { return 0;}\" /></td></tr>";
                } ?>

            </tbody>
        </table>
    </div>
</fieldset>

<fieldset style="float: left">
    <legend>Ajouter Categorie</legend>
    <form action="" method="post" name="f">
       <table>
           <td>Nom de la categorie</td><td><input type="text" name="nom" /></td>
       </table>
       <input type="submit" name="ok" value="ajouter" />
       <?php echo $msg; ?>
   </form>
</fieldset>
</body>
<!-- InstanceEnd --></html>

