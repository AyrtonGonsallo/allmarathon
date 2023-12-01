<?php
session_start();
//verif de validiter session
if(!isset($_SESSION['admin']) || !isset($_SESSION['login'])) {
    header('Location: login.php');
    exit();
}

if(!isset($_GET['imageID']))
    header('location:news.php');

require_once '../database/connexion.php';
$id=$_GET['imageID'];
 try {
                 $req = $bdd->prepare("DELETE FROM news_galerie WHERE ID=:id");
                 $req->bindValue('id',$id, PDO::PARAM_INT);
                 $req->execute();
                 header('Location: newsDetail.php?newsID='.(int)$_GET['newsID']);
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }

?>
