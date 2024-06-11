<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../database/connexion.php';
$id=$_GET["ID"];
try{
        $req = $bdd->prepare("delete FROM champion_admin_externe_journal where ID=:id");
        $req->bindValue('id',$id, PDO::PARAM_INT);
        $req->execute();
}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}
header('Location: validation.php');
?>