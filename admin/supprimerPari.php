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

if($_GET['pariID']!=""){
    require_once '../database/connexion.php';
    try {
                 $req = $bdd->prepare("DELETE FROM pari WHERE id=:ID LIMIT 1");

                 $req->bindValue('ID',$_GET['pariID'], PDO::PARAM_INT);
                 $req->execute();
                 header('Location: pari.php');
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
    // $query2 = sprintf("DELETE FROM pari WHERE id=%s",mysql_real_escape_string($_GET['pariID']));
    // mysql_query($query2) or die(mysql_error());
    // header('Location: pari.php');
}else{
    echo "erreur de variable";
}
?>
