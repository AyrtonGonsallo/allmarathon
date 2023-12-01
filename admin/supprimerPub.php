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

$pubID  = (isset($_GET['pubID']))?$_GET['pubID']:exit("error ID");
$adType = (isset($_GET['type']))?addslashes($_GET['type']):exit("error type");

require_once '../database/connexion.php';
try {
                 $req = $bdd->prepare("DELETE FROM $adType WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$_GET['pubID'], PDO::PARAM_INT);
                 $req->execute();
                 header('Location: pub.php?type='.$adType);
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
            


?>