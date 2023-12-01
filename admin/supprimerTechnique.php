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

if($_GET['techniqueID']!=""){
    require_once '../database/connexion.php';

     try {
                 $req = $bdd->prepare("DELETE FROM technique WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$_GET['techniqueID'], PDO::PARAM_INT);
                 $req->execute();
                 header('Location: technique.php');
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
    // $query2 = sprintf("DELETE FROM technique WHERE ID=%s LIMIT 1",mysql_real_escape_string($_GET['techniqueID']));
    // mysql_query($query2) or die(mysql_error());
    // header('Location: technique.php');
}else{
    echo "erreur de variable";
}
?>
