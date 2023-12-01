<?php 
require_once '../database/connexion.php';
$intitule=2;
$pays="";
$nom="";
$condition="";
if(isset($_GET['nom'])){
    $nom=$_GET['nom'];
}
if(isset($_GET['paysID'])){
    $pays=$_GET['paysID'];
}
if(isset($_GET['intitule'])){
    $intitule=intval($_GET['intitule']);
}
$condition.="and E.Nom like '%".$nom."%'";
$condition.="and paysID like '%".$pays."%'";
$condition.="and C.ID =".$intitule;
try{
    $sql="SELECT E.*,A.Intitule,C.Intitule AS typeEvenement FROM evenements_fils E LEFT OUTER JOIN evcategorieage A ON E.CategorieageID=A.ID INNER JOIN evcategorieevenement C ON E.CategorieID=C.ID where A.Intitule like '%eteran%' ".$condition." ORDER BY E.ID DESC";
    $req = $bdd->prepare($sql);
    $req->execute();
    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
      echo '<option value="'.$row['ID'].'">'.$row['typeEvenement'].' '.$row['Nom'].' '.$row['PaysID'].' '.$row['Intitule'].'</option>';
  }
}
catch(Exception $e)
{
  die('Erreur<br>Cause: ' . $e->getMessage().'<br>Requette: '.$sql);
}
?>