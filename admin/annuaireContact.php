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
     $erreur = "";
    if( isset($_POST['sub']) ){
        if($_POST['club']=="")
            $erreur .= "Erreur club.<br />";
        if($erreur == ""){

             try {
                 $req3 = $bdd->prepare("INSERT INTO clubs (club,responsable,telephone,mel,site,pays,departement,Valide) VALUES (:club,:responsable,:telephone,:mel,:site,:pays,:departement,1)");

                 $req3->bindValue('club',$_POST['club'], PDO::PARAM_STR);
                 $req3->bindValue('responsable',$_POST['responsable'], PDO::PARAM_STR);
                 $req3->bindValue('telephone',$_POST['telephone'], PDO::PARAM_STR);
                 $req3->bindValue('mel',$_POST['mel'], PDO::PARAM_STR);
                 $req3->bindValue('site',$_POST['site'], PDO::PARAM_STR);
                 $req3->bindValue('pays',$_POST['pays'], PDO::PARAM_STR);
                 $req3->bindValue('departement',$_POST['departement'], PDO::PARAM_INT);
                 $statut=$req3->execute();
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
        }
    }
    
    try{
              $req = $bdd->prepare("SELECT * FROM clubs ORDER BY ID DESC");
              $req->execute();
              $result1= array();
              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                array_push($result1, $row);
            }

            $req1 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
            $req1->execute();
            $paysNomTab= array();
            while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
                array_push($paysNomTab, $row);
            }

            $req2 = $bdd->prepare("SELECT * FROM departements ORDER BY NomDepartement");
            $req2->execute();
            $departements= array();
            while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) {  
                array_push($departements, $row);
            }

        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"  xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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


</head>

<body>
<?php require_once "menuAdmin.php"; ?>
<fieldset style="float:left;">
<legend>Ajouter contact</legend>
    <form action="annuaireContact.php" method="post">
    <p id="pErreur" align="center"><?php echo $erreur; ?></p>
    <table>

        <tr><td align="right"><label for="club">Club : </label></td><td><input type="text" name="club" value="" /></td></tr>
        <tr><td align="right"><label for="responsable">Responsable : </label></td><td><input type="text" name="responsable" value="" /></td></tr>
        <tr><td align="right"><label for="telephone">T&eacute;l&eacute;phone : </label></td><td><input type="text" name="telephone" value="" /></td></tr>
        <tr><td align="right"><label for="mel">mail : </label></td><td><input type="text" name="mel" value="" /></td></tr>
        <tr><td align="right"><label for="site">Site : </label></td><td><input type="text" name="site" value="http://" /></td></tr>
        <tr><td align="right"><label for="pays">Pays : </label></td><td>
        <select name="pays">
      <?php foreach($paysNomTab as $pays)
            echo '<option value="'.$pays["Abreviation"].'">'.$pays["NomPays"].'</option>';
      ?>
      </select>
        </td></tr>
        <tr><td align="right"><label for="departement">D&eacute;partement : </label></td><td>
        <select name="departement">
          <option value="">aucun</option>
      <?php foreach($departements as $dept)
            echo '<option value="'.$dept["CP"].'">'.$dept["NomDepartement"].'</option>';
      ?>
      </select>
        </td></tr>
                <tr align="center"><td colspan="2"><input type="submit" name="sub" value="cr&eacute;er" /></td></tr>
       </table>
    </form>
</fieldset>

<fieldset style="float:left;">
<legend>Liste des contacts</legend>
<div >
    <table class="tablesorter">
    <thead>
        <tr><th>ID</th><th>Club</th><th>pays</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($lien = mysql_fetch_array($result1)){
            foreach ($result1 as $lien) {
            echo "<tr align=\"center\" ><td>".$lien['ID']."</td><td>".str_replace("\\", "", $lien['club'])."</td><td>".$lien['pays']."</td>
                <td><img style=\"cursor:pointer;\" src=\"../images/edit.png\" alt=\"edit\" title=\"modifier\" onclick=\"location.href='annuaireContactDetail.php?siteID=".$lien['ID']."'\" />
                <img style=\"cursor:pointer;\" src=\"../images/supprimer.png\" alt=\"supprimer\" title=\"supprimer\"  onclick=\"if(confirm('Voulez vous vraiment supprimer ".$lien['club']." ?')) { location.href='supprimerContact.php?contactID=".$lien['ID']."';} else { return 0;}\" /></td></tr>";

        } ?>
    </tbody>
    </table>
</div>
</fieldset>
</body>
</html>
