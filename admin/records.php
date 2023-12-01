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
$records_types = array("première place","deuxième place","troisième place");
$erreur = "";
$result= array();
$result1= array();
$result_script= array();
if( isset($_POST['sub'])){
    
    if($_POST['type']=="") { $erreur .= "Erreur type.<br />";}
    
    if($_POST['pays_id']=="") { $erreur .= "Erreur pays.<br />";}
    $type=$_POST['type'];
    try{
        
        if($type==$records_types[0]){
            $sql_req="SELECT R.ID,C.Nom as champion,C.ID as champ_id, C.Sexe as sexe,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe=:sexe and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and p.ID=:pays ORDER BY R.Temps ASC limit 1;";
        }else if($type==$records_types[1]){
            $sql_req="SELECT R.ID,C.Nom as champion,C.ID as champ_id, C.Sexe as sexe,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe=:sexe and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and p.ID=:pays ORDER BY R.Temps ASC limit 1 offset 1;";

        }else if($type==$records_types[2]){
            $sql_req="SELECT R.ID,C.Nom as champion,C.ID as champ_id, C.Sexe as sexe,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe=:sexe and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and p.ID=:pays ORDER BY R.Temps ASC limit 1 offset 2;";

        }      
        $req = $bdd->prepare($sql_req);
        if($type==$records_types[0]||$type==$records_types[1]||$type==$records_types[2]){
            $req->bindValue('pays',$_POST['pays_id'], PDO::PARAM_INT);
            $req->bindValue('sexe',$_POST['sexe'], PDO::PARAM_STR);
        }
        $req->execute();
        //$result= array();
        while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
            array_push($result, $row);
        }
    }
    catch(Exception $e)
    {
        $erreur .=$e->getMessage();
        //die('Erreur : ' . $e->getMessage());
    }
}
if( isset($_GET['goal'])){
    
    if($_GET['goal']=="save") { 
        try{
            $req5 = $bdd->prepare("INSERT INTO `records`( `valide`, `champion`, `Temps`, `Pays`, `evenement`, lieu_evenement,`DateDebut`,champ_id,sexe) VALUES (0,:champion,:temps,:pays,:evenement,:lieu,:datedeb,:champ_id,:sexe)");

            $req5->bindValue('lieu',$_GET['lieu_evenement'], PDO::PARAM_STR);
            $req5->bindValue('datedeb',$_GET['DateDebut'], PDO::PARAM_STR);
            $req5->bindValue('champion',$_GET['champion'], PDO::PARAM_STR);
            $req5->bindValue('sexe',$_GET['sexe'], PDO::PARAM_STR);
            $req5->bindValue('champ_id',$_GET['champ_id'], PDO::PARAM_INT);
            $req5->bindValue('temps',$_GET['Temps'], PDO::PARAM_STR);
            $req5->bindValue('pays',$_GET['Pays'], PDO::PARAM_STR);
            $req5->bindValue('evenement',$_GET['evenement'], PDO::PARAM_STR);
            $req5->execute();
            
        }
        catch(Exception $e)
        {
            $erreur .=$e->getMessage();
            //die('Erreur : ' . $e->getMessage());
        }
        try{
            $req1 = $bdd->prepare("SELECT * FROM records r WHERE r.valide =0");
            $req1->execute();
            
            while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
              array_push($result1, $row);
          }
        
        }
        catch(Exception $e)
        {
          die('Erreur : ' . $e->getMessage());
        }
        header("Location: records.php");
    }
    if($_GET['goal']=="delete") { 
        try{
            $req5 = $bdd->prepare("DELETE FROM `records` WHERE id = :rec_id");

            $req5->bindValue('rec_id',intval($_GET['recID']), PDO::PARAM_INT);

            $req5->execute();
            
        }
        catch(Exception $e)
        {
            $erreur .=$e->getMessage();
            //die('Erreur : ' . $e->getMessage());
        }
        try{
            $req1 = $bdd->prepare("SELECT * FROM records r WHERE r.valide =0");
            $req1->execute();
            
            while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
              array_push($result1, $row);
          }
        
        }
        catch(Exception $e)
        {
          die('Erreur : ' . $e->getMessage());
        }
        header("Location: records.php");
    }
    if($_GET['goal']=="validate") { 
        try{
            $req5 = $bdd->prepare("DELETE FROM `records` WHERE Pays = :pays and sexe=:sexe");
            $req5->bindValue('sexe',$_GET['sexe'], PDO::PARAM_STR);
            $req5->bindValue('pays',$_GET['Pays'], PDO::PARAM_STR);

            $req5->execute();

            $req5 = $bdd->prepare("INSERT INTO `records`( `valide`, `champion`, `Temps`, `Pays`, `evenement`,lieu_evenement, `DateDebut`,champ_id,sexe) VALUES (1,:champion,:temps,:pays,:evenement,:lieu,:datedeb,:champ_id,:sexe)");

            $req5->bindValue('lieu',$_GET['lieu_evenement'], PDO::PARAM_STR);
            $req5->bindValue('datedeb',$_GET['DateDebut'], PDO::PARAM_STR);
            $req5->bindValue('champ_id',$_GET['champ_id'], PDO::PARAM_STR);
            $req5->bindValue('sexe',$_GET['sexe'], PDO::PARAM_STR);
            $req5->bindValue('champion',$_GET['champion'], PDO::PARAM_STR);
            $req5->bindValue('temps',$_GET['Temps'], PDO::PARAM_STR);
            $req5->bindValue('pays',$_GET['Pays'], PDO::PARAM_STR);
            $req5->bindValue('evenement',$_GET['evenement'], PDO::PARAM_STR);
            $req5->execute();
            
        }
        catch(Exception $e)
        {
            $erreur .=$e->getMessage();
            //die('Erreur : ' . $e->getMessage());
        }
        header("Location: records.php");
    } 
    
} else if(isset($_POST['goal'])){
    if($_POST['goal']=="generate_all"){
        try{
                $req2 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
                $req2->execute();
                $result4= array();
                
                while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) { 
                    if($_POST['sexe']=="masculin"){
                        $sql_req3="SELECT R.ID as id,C.Nom as champion,C.ID as champ_id,C.sexe as sexe,R.Temps,p.NomPays as Pays,E.Nom as evenement,E.PaysID as lieu_evenement,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='M' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and p.ID=:pays ORDER BY R.Temps ASC limit 1;";
                    }else{
                        $sql_req3="SELECT R.ID as id,C.Nom as champion,C.ID as champ_id,C.sexe as sexe,R.Temps,p.NomPays as Pays,E.Nom as evenement,E.PaysID as lieu_evenement,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='F' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and p.ID=:pays ORDER BY R.Temps ASC limit 1;";
                    }
                    $req3 = $bdd->prepare($sql_req3);
                    $req3->bindValue('pays',$row['ID'], PDO::PARAM_INT);
                    
                    $req3->execute();
                    //$result= array();
                    while ( $row3  = $req3->fetch(PDO::FETCH_ASSOC)) {  
                        array_push($result_script, $row3);
                    }
                    array_push($result4, $row);
                }
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
        }
}
else {
    try{
        $req1 = $bdd->prepare("SELECT * FROM records r WHERE r.valide =0");
        $req1->execute();
        
        while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
          array_push($result1, $row);
      }
    
    }
    catch(Exception $e)
    {
      die('Erreur : ' . $e->getMessage());
    }
}
//validés
try{
    $req1 = $bdd->prepare("SELECT * FROM records r WHERE r.valide =1");
    $req1->execute();
    $valides= array();
    while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
      array_push($valides, $row);
  }

}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}
//les pays
try{
    $req2 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
    $req2->execute();
    $result4= array();
    while ( $row  = $req2->fetch(PDO::FETCH_ASSOC)) { 
        array_push($result4, $row);
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


</head>

<body>
<?php require_once "menuAdmin.php"; ?>

<fieldset style="float:left;">
<legend>Liste des records validés</legend>
<div align="center">

    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>coureur</th><th>sexe</th><th>Temps</th><th>Pays</th><th>competition</th><th>date competition</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($evenement = mysql_fetch_array($result1)){
            foreach ($valides as $record) {
            echo "<tr align=\"center\" ><td>".$record['id']."</td><td>".$record['champion']."</td><td>".$record['sexe']."</td><td>".$record['Temps']."</td><td>".$record['Pays']."</td><td>".$record['evenement']."</td><td>".$record['DateDebut']."</td>
                    <td>
                        <img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/invalid.png\" alt=\"resultat\" title=\"supprimer\" onclick=\"location.href='records.php?goal=delete&recID=".$record['id']."'\" />

                        </td>
                </tr>";
        } ?>

    </tbody>
    </table>

</div>
</fieldset>
<fieldset style="float:left;">
<legend>Liste des records en attente de validation</legend>
<div align="center">

    <table class="tab1">
    <thead>
        <tr><th>ID</th><th>coureur</th><th>sexe</th><th>Temps</th><th>Pays</th><th>competition</th><th>date competition</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php //while($evenement = mysql_fetch_array($result1)){
            foreach ($result1 as $record) {
            echo "<tr align=\"center\" ><td>".$record['id']."</td><td>".$record['champion']."</td><td>".$record['sexe']."</td><td>".$record['Temps']."</td><td>".$record['Pays']."</td><td>".$record['evenement']."</td><td>".$record['DateDebut']."</td>
                    <td>
                        <img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/invalid.png\" alt=\"resultat\" title=\"supprimer\" onclick=\"location.href='records.php?goal=delete&recID=".$record['id']."'\" />
                        <a  href=\"records.php?goal=validate&champ_id=".$record['champ_id']."&champion=".$record['champion']."&sexe=".$record['sexe']."&Temps=".$record['Temps']."&Pays=".$record['Pays']."&evenement=".$record['evenement']."&lieu_evenement=".$record['lieu_evenement']."&DateDebut=".$record['DateDebut']."\">
                        <img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/valid.gif\" alt=\"resultat\" title=\"valider\"/>
                        <a/>

                        </td>
                </tr>";
        } ?>

    </tbody>
    </table>

</div>
</fieldset>
<fieldset style="float:left;">
<legend>Génération de records nationaux</legend>
    <div align="center">
        <h2>Créer un record (un seul pays)</h2>
        <form action="records.php" method="post" enctype="multipart/form-data">
            <p id="pErreur" ><?php echo $erreur; ?></p>
            <table>        
                <tr>
                    <td><label for="type">Type de record : </label></td><td>
                        <select name="type" id="type">
                            <?php 
                            foreach ($records_types as $type) {
                                echo '<option value="'.$type.'">'.$type.'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="sexe">Sexe : </label></td><td>
                        <select name="sexe" id="sexe">
                            <option value="M">masculin</option>
                            <option value="F">féminin</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="pays_id">Pays : </label></td><td>
                        <select name="pays_id" id="pays_id">
                            <?php //while($pays = mysql_fetch_array($result4)){
                            foreach ($result4 as $pays) {
                                echo '<option value="'.$pays['ID'].'">'.$pays['NomPays'].'</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                
                <tr ><td colspan="2"><input type="submit" name="sub" value="cr&eacute;er" /></td></tr>
            </table>
        </form>
        
    </div>
    <?php if($result){?>
    <div align="center">
        <table class="tab1">
            <thead>
                <tr><th>ID</th><th>coureur</th><th>sexe</th><th>Temps</th><th>Pays</th><th>competition</th><th>type competition</th><th>date competition</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php //while($evenement = mysql_fetch_array($result1)){
                    foreach ($result as $result_search) {
                    echo "<tr align=\"center\" ><td>".$result_search['ID']."</td><td>".$result_search['champion']."</td><td>".$result_search['sexe']."</td><td>".$result_search['Temps']."</td><td>".$result_search['pays']."</td><td>".$result_search['evenement']."</td><td>".$result_search['Intitule']."</td><td>".$result_search['DateDebut']."</td>
                            <td>
                                <a target=\"_blank\" href=\"records.php?goal=save&champ_id=".$result_search['champ_id']."&champion=".$result_search['champion']."&sexe=".$result_search['sexe']."&Temps=".$result_search['Temps']."&Pays=".$result_search['pays']."&evenement=".$result_search['evenement']."&lieu_evenement=".$result_search['lieu_evenement']."&DateDebut=".$result_search['DateDebut']."\">
                                    <img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/save.png\" alt=\"resultat\" title=\"sauvegarder sans valider\"/>
                                <a/>                            
                            </td>
                        </tr>";
                } ?>

            </tbody>
        </table>
    </div>
    <?php }?>
<h2>Lancer un script pour tous les pays ?</h2><br>
<form action="records.php" method="post" enctype="multipart/form-data">
    <table>  
        <tr style="display:none">
            <td><input type="text" name="goal" value="generate_all" /></td>
        </tr>
        <tr>
            <td><label for="sexe">Sexe : </label></td><td>
                <select name="sexe" id="sexe">
                    <option value="masculin">masculin</option>
                    <option value="feminin">féminin</option>
                </select>
            </td>
        </tr>
        <tr ><td colspan="2"><input type="submit" value="lancer le script" /></td></tr>
    </table>  
</form>
<?php if($result_script){?>
   
    <div align="center">

        <table class="tab1">
        <thead>
            <tr><th>ID</th><th>coureur</th><th>sexe</th><th>Temps</th><th>Pays</th><th>competition</th><th>date competition</th><th>Action</th></tr>
        </thead>
        <tbody>
            <?php //while($evenement = mysql_fetch_array($result1)){
                foreach ($result_script as $record) {
                echo "<tr align=\"center\" ><td>".$record['id']."</td><td>".$record['champion']."</td><td>".$record['sexe']."</td><td>".$record['Temps']."</td><td>".$record['Pays']."</td><td>".$record['evenement']."</td><td>".$record['DateDebut']."</td>
                        <td>
                            <img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/invalid.png\" alt=\"resultat\" title=\"supprimer\" onclick=\"location.href='records.php?goal=delete&recID=".$record['id']."'\" />
                            <a target=\"_blank\" href=\"records.php?goal=save&champ_id=".$record['champ_id']."&champion=".$record['champion']."&sexe=".$record['sexe']."&Temps=".$record['Temps']."&Pays=".$record['Pays']."&evenement=".$record['evenement']."&lieu_evenement=".$record['lieu_evenement']."&DateDebut=".$record['DateDebut']."\">
                            <img style=\"cursor:pointer;\" width=\"16px\" src=\"../images/valid.gif\" alt=\"resultat\" title=\"valider\"/>
                            <a/>

                            </td>
                    </tr>";
            } ?>

        </tbody>
        </table>

    </div>
    
<?php }?>
</fieldset>

</body>
<!-- InstanceEnd --></html>



