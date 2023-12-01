<?php

    session_start();
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
    {
		header('Location: login.php');
                exit();
    }

    if($_SESSION['admin'] == false){
        header('Location: login.php');
        exit();
    }

     require_once '../database/connection.php';
     $erreur = "";
    if( isset($_POST['sub']) ){
        if($_POST['Site']=="")
            $erreur .= "Erreur site.<br />";
        if($erreur == ""){
            $query2    = sprintf("INSERT INTO liens (Site,Pr�sentation,Asavoir,categorieID,PaysID,Departement,Valide,email) VALUES ('%s','%s','%s','%s','%s','%s',1,'%s')"
                ,mysql_real_escape_string($_POST['Site'])
                ,mysql_real_escape_string($_POST['Pr�sentation'])
                ,mysql_real_escape_string($_POST['Asavoir'])
                ,mysql_real_escape_string($_POST['categorieID'])
                ,mysql_real_escape_string($_POST['PaysID'])
                ,mysql_real_escape_string($_POST['Departement'])
                ,mysql_real_escape_string($_POST['email']));
            $result2   = mysql_query($query2) or die(mysql_error());
        }
    }
    
   
    $query1    = sprintf('SELECT L.ID,L.Site,L.Pr�sentation,C.categorie FROM liens L INNER JOIN lienscategorie C ON L.categorieID=C.ID ORDER BY L.ID DESC');
    $result1   = mysql_query($query1) or die(mysql_error());
    
    $queryCat    = sprintf('SELECT * FROM lienscategorie WHERE categorie!=2 ORDER BY ID DESC');
    $resultCat   = mysql_query($queryCat) or die(mysql_error());
    while($c = mysql_fetch_array($resultCat))
        $categories[$c['ID']] = $c['categorie'];
        
    $queryPays  = sprintf("SELECT * FROM pays ORDER BY NomPays");
    $resultPays = mysql_query($queryPays) or die(mysql_error());
    while($p = mysql_fetch_array($resultPays)){
        $paysNomTab[$p['Abreviation']]=$p['NomPays'];
    }

   $result2 = mysql_query('SELECT * FROM departements ORDER BY NomDepartement') or die(mysql_error());
    while($d = mysql_fetch_array($result2))
        $departements[$d['CP']] = $d['NomDepartement'];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<link href="../fonction/tablesorter/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../fonction/tablesorter/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
    $(document).ready(function()
        {
            $("table.tablesorter").tablesorter({sortList:[[0,1]], widgets: ['zebra']});
        }
    );
</script>
<title>allmarathon admin</title>


<!-- InstanceEndEditable -->
</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Ajouter site</legend>
    <form action="annuaireSite.php" method="post">
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>

        <tr><td align="right"><label for="Site">Site : </label></td><td><input type="text" name="Site" value="http://" /></td></tr>
        <tr><td  align="right"><label for="Pr�sentation">Pr�sentation : </label></td><td><input type="text" name="Pr�sentation" /></td></tr>
        <tr><td  align="right"><label for="email">Email de contact : </label></td><td><input type="text" name="email" value=""/></td></tr>
        <tr><td  align="right"><label for="Asavoir">A savoir : </label></td><td><textarea name="Asavoir" cols="40" rows="5"></textarea></td></tr>
        <tr><td align="right"><label for="categorieID">Categorie : </label></td><td>
        <select name="categorieID">
      <?php foreach($categories as $id => $nom)
            echo '<option value="'.$id.'">'.$nom.'</option>';
      ?>
      </select>
        </td></tr>

        <tr><td align="right"><label for="PaysID">Pays : </label></td><td>
        <select name="PaysID">
      <?php foreach($paysNomTab as $id => $nom)
            echo '<option value="'.$id.'">'.$nom.'</option>';
      ?>
      </select>
        </td></tr>
        <tr><td align="right"><label for="Departement">Departement : </label></td><td>
        <select name="Departement">
          <option value="">aucun</option>
      <?php foreach($departements as $id => $nom)
            echo '<option value="'.$id.'">'.$nom.'</option>';
      ?>
      </select>
        </td></tr>
                <tr align="center"><td colspan="2"><input type="submit" name="sub" value="cr�er" /></td></tr>
       </table>
    </form>
</fieldset>

<fieldset style="float:left;">
<legend>Liste des site</legend>
<div >
    <table class="tablesorter">
    <thead>
        <tr><th>ID</th><th>Categorie</th><th>Nom</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php while($lien = mysql_fetch_array($result1)){
            echo "<tr align=\"center\" ><td>".$lien['ID']."</td><td>".$lien['categorie']."</td><td>".str_replace("\\", "", $lien['Pr�sentation'])."</td>
                <td><img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='annuaireSiteDetail.php?siteID=".$lien['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$lien['Pr�sentation']." ?')) { location.href='supprimerSite.php?siteID=".$lien['ID']."';} else { return 0;}\" /></td></tr>";
            
        } ?>
    </tbody>
    </table>
</div>
</fieldset>
</body>
<!-- InstanceEnd --></html>
