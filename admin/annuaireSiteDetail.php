<?php
session_start();
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
		{
		header('Location: login.php');
                exit();
    }

    require_once '../database/connection.php';
$erreur="";

if(isset($_GET['siteID'])) {
    $id = mysql_real_escape_string($_GET['siteID']);


    if( isset($_POST['sub']) ){
        if($_POST['Site']=="")
            $erreur .= "Erreur site.<br />";
        if($erreur == ""){
            $query2    = sprintf("UPDATE liens SET Valide='%s',Site='%s',Pr�sentation='%s',Asavoir='%s',categorieID='%s',PaysID='%s',Departement='%s',email='%s' WHERE ID=%s LIMIT 1"
                ,mysql_real_escape_string($_POST['Valide'])
                ,mysql_real_escape_string($_POST['Site'])
                ,mysql_real_escape_string($_POST['Pr�sentation'])
                ,mysql_real_escape_string($_POST['Asavoir'])
                ,mysql_real_escape_string($_POST['categorieID'])
                ,mysql_real_escape_string($_POST['PaysID'])
                ,mysql_real_escape_string($_POST['Departement'])
                ,mysql_real_escape_string($_POST['email'])
                ,$id);
            $result2   = mysql_query($query2) or die(mysql_error());
            header("Location: annuaireSite.php");
        }
    }


    $query1    = sprintf('SELECT * FROM liens WHERE ID=%s',$id);
    $result1   = mysql_query($query1) or die(mysql_error());
    $lien      = mysql_fetch_array($result1);

    $queryCat    = sprintf('SELECT * FROM lienscategorie WHERE categorie!=2 ORDER BY categorie');
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

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">

<title>allmarathon admin</title>
</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Modifier site</legend>
    <form action="annuaireSiteDetail.php?siteID=<?php echo $id; ?>" method="post">
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>
        <tr><td  align="right"><label for="Valide">Valide : </label></td><td><input type="radio" name="Valide" value="1" <?php if($lien['Valide']) echo 'checked="checked"';?> /><span>oui</span><input type="radio" name="Valide" value="0" <?php if(!$lien['Valide']) echo 'checked="checked"';?> /><span >non</span></td></tr>
        <tr><td align="right"><label for="Site">Site : </label></td><td><input type="text" name="Site" value="<?php echo $lien['Site']; ?>" /></td></tr>
        <tr><td  align="right"><label for="email">Email de contact : </label></td><td><input type="text" name="email" value="<?php echo $lien['email']; ?>"/><?php if($lien['email'] != "") { ?> <a href="mailto:<?php echo $lien['email']; ?>?subject=<?php echo 'R�f�rencement sur alljudo.net'; ?>&body=<?php echo  str_replace('
',"%0A",'Vous venez d��tre r�f�renc� sur alljudo.net, et je serais � mon tour ravi de figurer sur votre site.
Je vous invite �galement � d�couvrir notre offre de partenariat gratdo.net/sites-partenaires.php

Merci d�avance, cordialement

Laurent MATHIEU 
shin-ji communication 
04.74.21.63.26 
06.82.94.74.12 
http://www.shin-ji.com 
 '); ?>">envoyer un mail � <?php echo $lien['email']; ?></a><?php } ?></td></tr>
        <tr><td  align="right"><label for="Pr�sentation">Pr�sentation : </label></td><td><textarea name="Pr�sentation" cols="40" rows="5"><?php echo str_replace('\\', '', $lien['Pr�sentation']); ?></textarea></td></tr>
        <tr><td  align="right"><label for="Asavoir">A savoir : </label></td><td><textarea name="Asavoir" cols="40" rows="5"><?php echo str_replace('\\', '', $lien['Asavoir']); ?></textarea></td></tr>
        <tr><td align="right"><label for="categorieID">Categorie : </label></td><td>
        <select name="categorieID">
      <?php foreach($categories as $id => $nom)
            echo ($id==$lien['categorieID'])?'<option value="'.$id.'" selected="selected">'.$nom.'</option>':'<option value="'.$id.'">'.$nom.'</option>';
      ?>
      </select>
        </td></tr>

        <tr><td align="right"><label for="PaysID">Pays : </label></td><td>
        <select name="PaysID">
      <?php foreach($paysNomTab as $id => $nom)
            echo ($id==$lien['PaysID'])?'<option value="'.$id.'" selected="selected">'.$nom.'</option>':'<option value="'.$id.'">'.$nom.'</option>';
      ?>
      </select>
        </td></tr>
        <tr><td align="right"><label for="Departement">Departement : </label></td><td>
        <select name="Departement">
          <option value="">aucun</option>
      <?php foreach($departements as $id => $nom)
            echo ($id==$lien['Departement'])?'<option value="'.$id.'" selected="selected">'.$nom.'</option>':'<option value="'.$id.'">'.$nom.'</option>';
      ?>
      </select>
        </td></tr>
                <tr align="center"><td colspan="2"><input type="submit" name="sub" value="modifier" /></td></tr>
       </table>
    </form>
</fieldset>
</body>
</html>
