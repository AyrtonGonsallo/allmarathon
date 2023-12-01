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

if($_GET['videoID']!=""){
    require_once '../database/connexion.php';
    try {
                 $req = $bdd->prepare("DELETE FROM videos WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$_GET['videoID'], PDO::PARAM_INT);
                 $req->execute();
                 header('Location: video.php');
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
    // $query2 = sprintf("DELETE FROM videos WHERE ID=%s",mysql_real_escape_string($_GET['videoID']));
    // mysql_query($query2) or die(mysql_error());
    // header('Location: video.php');
}else{
    echo "erreur de variable";
}
?>