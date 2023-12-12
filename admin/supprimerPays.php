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


if($_GET['paysID']!="" ){
    require_once '../database/connexion.php';
    try {
                 $req = $bdd->prepare("DELETE FROM pays WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$_GET['paysID'], PDO::PARAM_INT);
                 $req->execute();
                 header('Location: pays.php');
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
    // $query2 = sprintf("DELETE FROM pays WHERE ID='%s' LIMIT 1",mysql_real_escape_string($_GET['paysID']));
    // mysql_query($query2) or die(mysql_error());
    // header('Location: pays.php');
}else{
    echo "erreur de variable";
}

?>
