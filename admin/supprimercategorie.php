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

if($_GET['ID']!=""){
    require_once '../database/connexion.php';
    try {
                 $req = $bdd->prepare("DELETE FROM categorie WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$_GET['ID'], PDO::PARAM_INT);
                 $req->execute();
                  header('Location: categories.php');
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
    
}elseif($_GET['SID']!=""){
    require_once '../database/connexion.php';
    try {
                 $req = $bdd->prepare("DELETE FROM sous_categorie WHERE ID=:ID LIMIT 1");

                 $req->bindValue('ID',$_GET['SID'], PDO::PARAM_INT);
                 $req->execute();
                  header('Location: souscategorie.php');
            }
            catch(Exception $e)
            {
                 die('Erreur : ' . $e->getMessage());
                
            }
    
    // $query2 = sprintf("DELETE FROM sous_categorie WHERE ID=%s",mysql_real_escape_string($_GET['SID']));
    // mysql_query($query2) or die(mysql_error());
    // header('Location:souscategorie.php');
}else{
    echo "erreur de variable";
}
?>
