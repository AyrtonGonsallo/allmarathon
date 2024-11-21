<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';

$champion_id=$_GET['championID'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérifier si le fichier a été uploadé sans erreur
   
           try {
            require_once '../database/connexion.php';
             $req4 = $bdd->prepare("INSERT INTO `champion_admin_externe_journal`( `type`, `date`, `user_id`, `champion_id`, `video`) VALUES (:t,:d,:u,:c,:v)");

             $req4->bindValue('t','upload-video', PDO::PARAM_STR);
             $req4->bindValue('d',date("Y-m-d H:i:s"), PDO::PARAM_STR);
             $req4->bindValue('u',$user_id, PDO::PARAM_INT);
             $req4->bindValue('c',$champion_id, PDO::PARAM_INT);
             $req4->bindValue('v',$_POST["video-url"], PDO::PARAM_STR);
            
             $statut=$req4->execute();

            

            }
            catch(Exception $e)
            {
                die('Erreur : ' . $e->getMessage());
            }
            header('Location: /membre-profil.php?tab=adm_fiche'); 
        
} else {
    echo "Erreur : Le formulaire de téléchargement doit être soumis en utilisant la méthode POST.";
}
?>
