<?php
require_once '../database/connexion.php';
$eventID=$_GET["id"];
try {
    $req = $bdd->prepare("SELECT * FROM evenements_fils WHERE ID=:id");
    $req->bindValue('id',$eventID, PDO::PARAM_INT);
    $req->execute();
    $event= $req->fetch(PDO::FETCH_ASSOC);
    $sql ="INSERT INTO evenements (Nom,Sexe,DateDebut,DateFin,CategorieageID,CategorieID,Visible,Type, PaysID,hierarchique) VALUES (:nom,:sexe,:dateDebut,:dateFin,:CategorieAgeID,:CategorieID,1,'Parent',:PaysID,1)";
    $req4 = $bdd->prepare($sql);
    
     $req4->bindValue('nom',$event['Nom'], PDO::PARAM_STR);
     $req4->bindValue('sexe',$event['Sexe'], PDO::PARAM_STR);
     $req4->bindValue('dateDebut',$event['DateDebut'], PDO::PARAM_STR);
     $req4->bindValue('dateFin',$event['DateFin'], PDO::PARAM_STR);
     $req4->bindValue('CategorieAgeID',7, PDO::PARAM_INT);
     $req4->bindValue('CategorieID',$event['CategorieID'], PDO::PARAM_STR);
     $req4->bindValue('PaysID',$event['PaysID'], PDO::PARAM_STR);
     $statut=$req4->execute();
    echo $statut;
     
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}
?>