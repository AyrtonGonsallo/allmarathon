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

if($_GET['remplacant']!="" && $_GET['remplacer']!=""){
    // on attribut tout les résultat du remplacé au remplacant
    require_once '../database/connexion.php';
     try {
             $req = $bdd->prepare("UPDATE evresultats SET ChampionID=:id_remplacant WHERE ChampionID=:id_remplacer");

             $req->bindValue('id_remplacant',$_GET['remplacant'], PDO::PARAM_INT);
             $req->bindValue('id_remplacer',$_GET['remplacer'], PDO::PARAM_INT);
             $result=$req->execute();
             
             $req2 = $bdd->prepare("DELETE FROM champions WHERE ID=:idchamp");

             $req2->bindValue('idchamp',$_GET['remplacer'], PDO::PARAM_INT);
             $result2=$req2->execute();
             echo  $_GET['remplacer']." remplacé par ".$_GET['remplacant'];
             if(isset($_GET['modifier'] ))
            {
                header('Location:championDetail2.php?championID='.$_GET['remplacant']);
            }
             
             
         }
         catch(Exception $e)
         {
            die('Erreur : ' . $e->getMessage());
        }
}else{
    echo "erreur de variable";
}

?>