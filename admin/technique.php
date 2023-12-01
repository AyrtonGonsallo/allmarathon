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

if ($_SESSION['admin'] == false) {
    header('Location: login.php');
    exit();
}

require_once '../database/connexion.php';

$erreur = "";
if (isset($_POST['sub'])) {
    if ($_POST['nom'] == "")
        $erreur .= "Erreur nom.<br />";
    if ($erreur == "") {
        try {
            $req4 = $bdd->prepare("INSERT INTO technique (Nom,Famille,Presentation,Conseils) VALUES (:Nom,:Famille,:Presentation,:Conseils)");

            $req4->bindValue('Nom',$_POST['nom'], PDO::PARAM_STR);
            $req4->bindValue('Famille',$_POST['famille'], PDO::PARAM_STR);
            $req4->bindValue('Presentation',$_POST['presentation'], PDO::PARAM_STR);
            $req4->bindValue('Conseils',$_POST['conseil'], PDO::PARAM_STR);
            $req4->execute();

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
    }
}

try{
  $req = $bdd->prepare("SELECT * FROM technique ORDER BY id DESC");
  $req->execute();
  $resultTechnique= array();
  while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
    array_push($resultTechnique, $row);
}
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css"/>
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/jquery-ui-1.7.1.custom.min.js" type="text/javascript"></script>
    <link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css"/>
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
    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin</title>


    <!-- InstanceEndEditable -->
</head>

<body>
    <?php require_once "menuAdmin.php"; ?>

    <div id="pager" class="pager">
        <form>
            <img src="../fonction/tablesorter/first.png" class="first"/>
            <img src="../fonction/tablesorter/prev.png" class="prev"/>
            <input type="text" class="pagedisplay"/>
            <img src="../fonction/tablesorter/next.png" class="next"/>
            <img src="../fonction/tablesorter/last.png" class="last"/>
            <select class="pagesize">
                <option selected="selected"  value="10">10</option>

                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </form>
    </div>
    <br />
    <table  border="0">
        <td style="width:100%;margin:0;padding:0;vertical-align:top;">
            <fieldset style="float:left;">
                <legend>Liste des technique</legend>
                <table  class="tab1 tablesorter">
                    <thead>
                        <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Famille</th>
                <th>Présentation</th>
                <th>Conseils</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php         //while ($j = mysql_fetch_array($resultTechnique)) {
            foreach ($resultTechnique as $j) {
                echo '<tr>';
                echo "<td>".$j['ID']."</td>";
                echo "<td>".$j['Nom']."</td>";
                echo "<td>".$j['Famille']."</td>";
                echo "<td >".$j['Presentation']."</td>";
                    //echo "<td style=\"width:10%\">$j[4]</td>";
                echo "<td >".$j['Conseils']."</td>";
                    
                    echo '<td style=\"width:5%\"><a href="techniqueDetail.php?techniqueID=' . $j['ID'] . '" ><img src="../images/edit.png" title="detail" /></a>
                    <img style="cursor:pointer;" src="../images/supprimer.png" alt="supprimer" title="supprimer"  onclick="if(confirm(\'Voulez vous vraiment supprimer ' . $j['Nom'] . ' ?\')) { location.href=\'supprimerTechnique.php?techniqueID=' . $j['ID'] . '\';} else { return 0;}" /></td>',
                    '</tr>';
                } ?>
            </tbody>
        </table>
    </fieldset>
</td>
<td style="margin:0;padding:0;vertical-align:top;">
    <fieldset>

        <legend>Nouvelle technique</legend>
        <?php if (isset($_GET['retour'])) {
            echo '<span style="color:green;">Modification r&eacute;ussie</span><br /><br />';
        } ?>
        <form action="" method="post">
            <table>
                <?php
               
                echo '  <tr>
                            <td><label for="nom">Nom : </label></td><td><input type="text" id="nom" name="nom" /></td>
                        </tr> 
                        <tr>
                            <td><label for="famille">Famille : </label></td><td><input type="text" id="famille" name="famille" /></td>
                        </tr>
                        <tr>
                            <td><label for="presentation">Présentation :</label></td><td><textarea name="presentation" id="presentation" cols="50" rows="8" > </textarea></td>
                        </tr>
                        <tr>
                            <td><label for="conseil">Conseil : </label></td><td><textarea name="conseil" id="conseil" cols="50" rows="8" ></textarea></td>
                        </tr>';
                ?>
            </table>
            <div><input type="submit" name="sub" value="Cr&eacute;er"/></div>
        </form>
    </fieldset>
</td>
</table>
</body>
</html>