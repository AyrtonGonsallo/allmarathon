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

//supprimer les résultat en rapporte avec l'événement ?

if($_GET['annonceID']!=""){
    require_once '../database/connexion.php';
    try {
                 $req = $bdd->prepare("DELETE FROM annonce WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$_GET['annonceID'], PDO::PARAM_INT);
                 $req->execute();
                  header('Location: annonces.php');
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
   
}else{
    echo "erreur de variable";
}
?>
