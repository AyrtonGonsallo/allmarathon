<?php
require_once '../database/connexion.php';
$nom=$_GET["nom"];
$pays=$_GET["pays"];
$competition=$_GET["competition"];
//echo $nom.' '.$pays.' '.$competition;
try {
    $sql="SELECT e.ID,ecev.Intitule as competition,e.Nom,e.paysID as pays,e.sexe,e.Type,ecat.Intitule as 'categorie age' FROM `evenements_fils` e,`evcategorieage` ecat,evcategorieevenement ecev WHERE CategorieageID=ecat.ID and ecev.ID=e.CategorieID and ecat.Intitule like '%eteran%' and ecat.Intitule like '%M%' and e.Nom like :nom  and ecev.Intitule like :competition  and e.paysID like :pays order by e.ID desc;";
    $req = $bdd->prepare($sql);
    $req->bindValue('nom','%'.$nom.'%', PDO::PARAM_STR);
    $req->bindValue('competition','%'.$competition.'%', PDO::PARAM_STR);
    $req->bindValue('pays','%'.$pays.'%', PDO::PARAM_STR);
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
        <html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
        <head>
        <meta charset="utf-8">
            <meta http-equiv="Content-Type" content="text/html;" />
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
            <link rel="stylesheet" href="../css/evenement-hierarchique-style.css" type="text/css"> 
            <!-- InstanceBeginEditable name="doctitle" -->
            <title>allmarathon admin</title>
            </head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <fieldset style="width:70%;float:left;">
    <legend>Liste des sous-&eacute;v&egrave;nements à régrouper </legend>
        <div >
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

            <table class="tablesorter">
                <thead>
                    <tr><th>ID</th><th>Competition</th><th>Nom</th><th>Pays</th><th>sexe</th><th>type</th><th>Cat age</th></tr>
                </thead>
                <tbody>
                <?php //while($evenement = mysql_fetch_array($result1)){
                    foreach ($result1 as $evenement) {
                        echo "<tr align=\"center\" ><td>".$evenement['ID']."</td><td>".$evenement['competition']."</td><td>".$evenement['Nom']."</td><td>".$evenement['pays']."</td><td>".$evenement['sexe']."</td><td>".$evenement['Type']."</td><td>".$evenement['categorie age']."</td>
                        </tr>";
                        } ?>

                </tbody>
            </table>
        </div>
        <div>
        <?php echo "<button type=\"button\" class=\"boutton\" onclick=\"location.href='groupement2.php?id=".$result1[0]["ID"]."'\">Créer évènement parent</button>";?>
        </div>
    </fieldset>
</body>