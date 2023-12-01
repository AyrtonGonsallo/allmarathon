<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
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

if(!isset($_GET['imageID']))
    header('location:galerie.php');
    require_once '../database/connexion.php';   

    try {
    $req = $bdd->prepare("SELECT I.Galerie_id, I.Nom, G.Titre, G.ID FROM images I, galeries G WHERE I.ID=:ID AND I.Galerie_id=G.ID");
    $req->bindValue('ID',$_GET['imageID'], PDO::PARAM_INT);
    $req->execute();
    $image=$req->fetch(PDO::FETCH_ASSOC);

    $path = "../images/galeries/".$image['ID']."/".$image['Nom'];
    unlink($path);

    $req2 = $bdd->prepare("DELETE FROM images WHERE ID=:ID");
    $req2->bindValue('ID',$_GET['imageID'], PDO::PARAM_INT);
    $req2->execute();
    header('Location: galerieDetail.php?galerieID='.$image['Galerie_id']);

  }
  catch(Exception $e)
  {
    die('Erreur : ' . $e->getMessage());
  }


    // $query  = sprintf("SELECT I.Galerie_id, I.Nom, G.Titre, G.ID FROM images I, galeries G WHERE I.ID=%s AND I.Galerie_id=G.ID ",mysql_real_escape_string($_GET['imageID']));
    // $result = mysql_query($query) or die(mysql_error());
    // $image  = mysql_fetch_array($result);
    
    
    // $path = "../images/galeries/".$image['ID']."/".$image['Nom'];
    // unlink($path);
    // $query2 = sprintf("DELETE FROM images WHERE ID=%s",mysql_real_escape_string($_GET['imageID']));
    // mysql_query($query2) or die(mysql_error());
    // header('Location: galerieDetail.php?galerieID='.$image['Galerie_id']);
    
?>
