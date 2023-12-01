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

//supprimer les r�sultat en rapporte avec l'�v�nement ?
if(isset($_GET['evenementID'])){
    $eventID=$_GET['evenementID'];
    $table="evenements";
    $redirect="evenement.php";
}else if(isset($_GET['evenement_filsID'])){
    $eventID=$_GET['evenement_filsID'];
    $table="evenements_fils";
    $redirect="evenement-fils.php";
}
if($eventID && $_GET['evenementNom']){
    require_once '../database/connexion.php';
    try {
                 $req = $bdd->prepare("DELETE FROM ".$table." WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$eventID, PDO::PARAM_INT);
                 $req->execute();
                  header('Location: '.$redirect);
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
    // $query2 = sprintf("DELETE FROM evenements WHERE ID=%s",mysql_real_escape_string($_GET['evenementID']));
    // mysql_query($query2) or die(mysql_error());
    // header('Location: evenement.php');
}else{
    echo "erreur de variable";
}
?>
