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
        $req = $bdd->prepare("select champion_id,image FROM champion_admin_externe_journal where ID=:id");
        $req->bindValue('id',$id, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $champion_id = $result['champion_id'];
            $image = $result['image'];
    
            // Deuxième requête pour insérer les données récupérées
            $req2 = $bdd->prepare("INSERT INTO `images`(`Nom`, `Champion_id`, `Champion2_id`, `Evenement_id`, `News_id`, `Galerie_id`, `Technique_id`, `Description`, `actif`) 
                                   VALUES (:n, :cid, 0, 0, 0, 0, 0, '', '1')");
            $req2->bindValue('n', $image, PDO::PARAM_STR);
            $req2->bindValue('cid', $champion_id, PDO::PARAM_INT);
            $req2->execute();

            $req3 = $bdd->prepare("update champion_admin_externe_journal set type='image-valid' where ID=:id");
            $req3->bindValue('id',$id, PDO::PARAM_INT);
            $req3->execute();
        } else {
            throw new Exception('No result found for the provided ID.');
        }

}
catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}
header('Location: validation.php');
?>