<?php
require_once '../database/connexion.php';

$id=$_GET['id'];
$chk=$_GET['chkYesNo'];
 try {
                 $req = $bdd->prepare("UPDATE news SET aLaUne=:chk WHERE id=:id");

                 $req->bindValue('id',$id, PDO::PARAM_INT);
                 $req->bindValue('chk',$chk, PDO::PARAM_INT);
                 $req->execute();
            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
?>