<?php 
require_once '../database/connexion.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
try {
    $page=$_COOKIE["page_when_logging_to_rev_fiche"];
    $fileName = $_FILES['j']['name'];
    $destination_path = "../../uploadDocument/";
    
                
    $fileinfo = $_FILES['j'];
    $fichierSource = $fileinfo['tmp_name'];
    $fichierName   = $fileinfo['name'];
    if ( $fileinfo['error']) {
        switch ( $fileinfo['error']){
            case 1: // UPLOAD_ERR_INI_SIZE
            echo "'Le fichier ".$fichierName." d&eacute;passe la limite autoris&eacute;e par le serveur (fichier php.ini) !'<br />";
            break;
            case 2: // UPLOAD_ERR_FORM_SIZE
            echo  "'Le fichier ".$fichierName." d&eacute;passe la limite autoris&eacute;e dans le formulaire HTML !'<br />";
            break;
            case 3: // UPLOAD_ERR_PARTIAL
            echo"'L'envoi du fichier ".$fichierName." a &eacute;t&eacute; interrompu pendant le transfert !'<br />";
            break;
            case 4: // UPLOAD_ERR_NO_FILE
            echo "'Le fichier ".$fichierName." que vous avez envoy&eacute; a une taille nulle !'<br />";
            break;
        }
    }
    else{
          
              
                if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
                    $i=2;
                    //echo "Fichier ".$fichierName." corectement envoy&eacute; !";
                }else{
                    //echo "Erreur phase finale fichier ".$fichierName."<br />";
                    $i=2;
                }
    }

            $req3 = $bdd->prepare("INSERT INTO `champion_admin_externe`(`Justificatif`, `Message`, `user_id`, `champion_id`, `date_creation`, `actif`) 
            VALUES (:j, :m, :u, :c,  :d, 0 )");

            $req3->bindValue('m',$_POST["message"], PDO::PARAM_STR);
            $req3->bindValue('c',$_POST["c"], PDO::PARAM_INT);
            $req3->bindValue('u',$_POST["u"], PDO::PARAM_INT);
            $req3->bindValue('d',date("Y-m-d"), PDO::PARAM_STR);
           
            $req3->bindValue('j', $fichierName , PDO::PARAM_STR);
            $req3->execute();

           
            $_SESSION['msg_adm_fiche']="Votre demande est en cours de validation, vous receverez un message lorsque celle-ci aura été validée.<br>";
            if($user["newsletter"]==0){
                $_SESSION['msg_adm_fiche'].=" Pour ne rien manquer de l'actualité du marathon abonnez-vous à notre newsletter<br><a href='/content/modules/update_profile.php?subscribe_to_newsletter=1' class='call-to-action mx-auto'>Oui, je m'abonne</a>";
            }
            header('Location:  '.$page); 
}
catch(Exception $e)
{
   echo  'erreur SQL insertion résultat : '. $e->getMessage();
}