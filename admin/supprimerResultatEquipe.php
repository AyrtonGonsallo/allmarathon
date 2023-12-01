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

if($_GET['equipeID']!="" && $_GET['evenementID']!=""){
    require_once '../database/connexion.php';
    $idEquipe    = $_GET['equipeID'];
    $idEvenement = $_GET['evenementID'];
    try {
                 $req = $bdd->prepare("DELETE FROM evequipe WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$idEquipe, PDO::PARAM_INT);
                 $req->execute();
                 $req1 = $bdd->prepare("DELETE FROM evresultats WHERE equipeID=:equipeID LIMIT 1");

                 $req1->bindValue('equipeID',$idEvenement, PDO::PARAM_INT);
                 $req1->execute();
                 header('Location: evenementResultatEquipe.php?evenementID='.$idEvenement);
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
    // $query2 = sprintf("DELETE FROM evequipe WHERE ID=%s",$idEquipe);
    // mysql_query($query2) or die(mysql_error());
    // $query3 = sprintf("DELETE FROM evresultats WHERE equipeID=%s",$idEquipe);
    // mysql_query($query3) or die(mysql_error());
    // header('Location: evenementResultatEquipe.php?evenementID='.$idEvenement);
}else{
    echo "erreur";
}
?>
