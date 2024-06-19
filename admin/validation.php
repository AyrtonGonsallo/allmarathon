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

    if($_SESSION['admin'] == false){
        header('Location: login.php');
        exit();
    }

    require_once '../database/connexion.php';

    try{
              $req = $bdd->prepare("SELECT E.*,A.Intitule FROM evenements E LEFT OUTER JOIN evcategorieage A ON E.CategorieageID=A.ID  WHERE Valider=0 ORDER BY E.DateDebut");
              $req->execute();
              $result1= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
            }

           

            $req2 = $bdd->prepare("SELECT * FROM liens WHERE Valide=0 ORDER BY ID DESC");
            $req2->execute();
            $result3= array();
            while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result3, $row);
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
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<!-- InstanceBeginEditable name="doctitle" -->
<title>allmarathon admin</title>

<!-- InstanceEndEditable -->
</head>

<body>
<?php require_once "menuAdmin.php"; ?>

<fieldset style="float:left;">
<legend>Liste des manifestations en attente de validation</legend>
<div align="center">

    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>DateDebut</th><th>Nom</th><th>Sexe</th><th>cat d'age</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($evenement = mysql_fetch_array($result1)){
            foreach ($result1 as $evenement) {
            echo "<tr align=\"center\" ><td>".$evenement['ID']."</td><td>".$evenement['DateDebut']."</td><td>".$evenement['Nom']."</td><td>".$evenement['Sexe']."</td><td>".$evenement['Intitule']."</td>
                <td><img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/dl.png\" alt=\"resultat\" title=\"ajouter r�sultat\" onclick=\"location.href='evenementResultat.php?evenementID=".$evenement['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='evenementDetail.php?evenementID=".$evenement['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$evenement['Nom']." ?')) { location.href='supprimerEvenement.php?evenementID=".$evenement['ID']."&evenementNom=".$evenement['Nom']."';} else { return 0;}\" /></td></tr>";
        } ?>

    </tbody>
    </table>

</div>
</fieldset>
    <fieldset style="float:left;">
<legend>Liste des site en attente de validation</legend>
<div align="center">

    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>Nom</th><th>Type</th><th>doublons</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($evenement = mysql_fetch_array($result3)){
            foreach ($result3 as $evenement) {
        $query4    = sprintf('SELECT ID FROM liens WHERE Site=\'%s\' AND ID != %s',$evenement['Site'],$evenement['ID']);
        $result4   = mysql_query($query4);
        $doublons = "";
        while($doublon  = mysql_fetch_array($result4)){
            $doublons .= '<a href="annuaireSiteDetail.php?siteID='.$doublon['ID'].'" >'.$doublon['ID'].'</a>';
        }
            echo "<tr align=\"center\" ><td>".$evenement['ID']."</td><td>".$evenement['Pr�sentation']."</td><td>".$evenement['Site']."</td><td>".$doublons."</td>
                <td>
                <img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='annuaireSiteDetail.php?siteID=".$evenement['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$evenement['Pr�sentation']." ?')) { location.href='supprimerSite.php?siteID=".$evenement['ID']."';} else { return 0;}\" /></td></tr>";
        } ?>

    </tbody>
    </table>

</div>
</fieldset>
    <fieldset style="float:left;">
<legend>Liste des contact en attente de validation</legend>
<div align="center">

    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>Pays</th><th>doublons</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($evenement = mysql_fetch_array($result2)){
             ?>

    </tbody>
    </table>

</div>
</fieldset>
</body>
<!-- InstanceEnd --></html>