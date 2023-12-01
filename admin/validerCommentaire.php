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

if($_GET['commentaireID']!=""){
    require_once '../database/connexion.php';
    try {
       $req = $bdd->prepare("UPDATE commentaires SET valide='1' WHERE ID=:ID");
       $req->bindValue('ID',$_GET['commentaireID'], PDO::PARAM_INT);
       $req->execute();
       header('Location: commentaire.php');

   }
   catch(Exception $e)
   {
    die('Erreur : ' . $e->getMessage());
}
}else{
    echo "erreur de variable";
}

?>
