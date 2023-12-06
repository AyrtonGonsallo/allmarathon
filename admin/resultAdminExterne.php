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

if($_SESSION['admin'] == false){
    header('Location: login.php');
    exit();
}

require_once '../database/connexion.php';



if(isset ($_POST['extern_supp_id'])):
    try {
       $req_refus = $bdd->prepare("DELETE FROM champion_admin_externe_palmares WHERE ID = :id LIMIT 1");
       $req_refus->bindValue('id',$_POST['extern_supp_id'], PDO::PARAM_INT);
       $req_refus->execute();
   }
   catch(Exception $e)
   {
    die('Erreur : ' . $e->getMessage());
}
    // $queryDelete = sprintf("DELETE FROM champion_admin_externe_palmares WHERE ID = '%s' LIMIT 1", (int)$_POST['extern_supp_id']);
    // mysql_query($queryDelete) or die(mysql_error());
endif;


try{
  $req_select = $bdd->prepare("SELECT caep.*, c.Nom, e.Intitule FROM champion_admin_externe_palmares caep"
    ." LEFT JOIN champions c ON c.ID = caep.ChampionID"
    ." LEFT JOIN evcategorieage e ON caep.CategorieAge = e.ID"
    ." ORDER BY caep.ID DESC");
  $req_select->execute();
  $resultResult= array();
  while ( $row  = $req_select->fetch(PDO::FETCH_ASSOC)) {  
    array_push($resultResult, $row);
}
}

catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

// $queryResult = "SELECT caep.*, c.Nom, d.NomDepartement, d.CP, r.NomRegion, r.ID AS RegionID, e.Intitule FROM champion_admin_externe_palmares caep"
//                 ." LEFT JOIN champions c ON c.ID = caep.ChampionID"
//                 ." LEFT JOIN departements d ON caep.CompetitionDepID = d.CP"
//                 ." LEFT JOIN regions r ON caep.CompetitionRegID = r.ID"
//                 ." LEFT JOIN evcategorieage e ON caep.CategorieAge = e.ID"
//                 ." ORDER BY caep.ID DESC";
// $resultResult = mysql_query($queryResult) or die(mysql_error());

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

    <title>allmarathon admin externe resultats</title>

</head>

<body>
    <?php require_once "menuAdmin.php"; ?>

    <fieldset style="float:left;">
        <legend>Liste des resultats administr&eacute;s</legend>
        <div align="center">
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
            <br />

            <table class="tablesorter" id="tbl1">
                <thead>
                    <tr><th>Champion</th><th>Rang</th><th>Evenement</th><th>Temps</th><th>Utilisateur</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php //while($result = mysql_fetch_array($resultResult)){ 
                    foreach ($resultResult as $result) {?>
                    <tr>
                        <td><a href="../athlete-<?php echo $result['ChampionID'] ?>.html"><?php echo $result['Nom'] ?></a></td>
                        <td><?php echo $result['Rang'] ?></td>
                        <td><?php echo $result['CompetitionFr'] ?></td>
                        <td><?php echo $result['Temps'] ?></td>
                        <td><?php echo $result['utilisateur'] ?></td>
                        <td>
                            <form method="post" action="resultAdminExterneValid.php" style="float: left">
                                <input type="hidden" name="rang" value="<?php echo $result['Rang'] ?>" />
                                <input type="hidden" name="ID" value="<?php echo $result['ID'] ?>" />
                                <input type="hidden" name="CompetitionFr" value="<?php echo $result['CompetitionFr'] ?>" />
                                <input type="hidden" name="Temps" value="<?php echo $result['Temps'] ?>" />
                                <input type="hidden" name="utilisateur" value="<?php echo $result['utilisateur'] ?>" />
                                <input type="hidden" name="EvenementID" value="<?php echo $result['EvenementID'] ?>" />
                                <input type="hidden" name="ChampionID" value="<?php echo $result['ChampionID'] ?>" />
                                <input type="hidden" name="date" value="<?php echo $result['Date'] ?>" />
                                <input type="hidden" name="extern_edit_id" value="<?php echo $result['ID']?>"/>
                                <input name="modifier" type="image" src="../images/valid.gif" style="width: 20px" title="valider" />
                            </form>
                            <form method="post" action="resultAdminExterneModif.php" style="float: left">
                                <input type="hidden" name="rang" value="<?php echo $result['Rang'] ?>" />
                                <input type="hidden" name="CompetitionFr" value="<?php echo $result['CompetitionFr'] ?>" />
                                <input type="hidden" name="Temps" value="<?php echo $result['Temps'] ?>" />
                                <input type="hidden" name="utilisateur" value="<?php echo $result['utilisateur'] ?>" />

                                <input type="hidden" name="date" value="<?php echo $result['Date'] ?>" />
                                <input type="hidden" name="extern_edit_id" value="<?php echo $result['ID']?>"/>
                                <input name="modifier" type="image" src="../images/edit.png" style="width: 20px" title="modifier le resultat" />
                            </form>

                            <form method="post" action="" style="float: right" onsubmit="if(window.confirm('Supprimer le resultat?'))return true; else return false;">
                                <input type="hidden" name="extern_supp_id" value="<?php echo $result['ID']?>"/>
                                <input name="supprimer" type="image" src="../images/supprimer.png" style="width: 20px" title="supprimer le resultat" />
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </fieldset>
</body>
</html>
