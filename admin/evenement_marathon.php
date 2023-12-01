<?php
require_once '../database/connexion.php';
$eventID=$_GET["evenementID"];
$marID=$_GET['marathonID'];
if($_GET['add']){
    try {
    
        $sql ="UPDATE evenements set marathon_id=:mar_id WHERE id=:event_id";
        $req4 = $bdd->prepare($sql);
        
        $req4->bindValue('mar_id',$marID, PDO::PARAM_INT);
        $req4->bindValue('event_id',$eventID, PDO::PARAM_INT);
        
        $statut=$req4->execute();
        //echo $statut;
        header("Location: marathonDetail.php?marathonID=".$marID);
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
}
if($_GET['remove']){
    try {
    
        $sql ="UPDATE evenements set marathon_id=0 WHERE id=:event_id and marathon_id=:mar_id";
        $req4 = $bdd->prepare($sql);
        
        $req4->bindValue('mar_id',$marID, PDO::PARAM_INT);
        $req4->bindValue('event_id',$eventID, PDO::PARAM_INT);
        
        $statut=$req4->execute();
        //echo $statut;
        header("Location: marathonDetail.php?marathonID=".$marID);
    }
    catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
}

?>