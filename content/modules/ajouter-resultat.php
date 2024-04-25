<?php 
require_once '../database/connexion.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
try {
    $page=$_COOKIE["page_when_adding_result"];
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

            $req3 = $bdd->prepare("INSERT INTO champion_admin_externe_palmares (`Rang`, `Temps`, `ChampionID`, `utilisateur`, `Date`, `PaysID`, `EvenementID`, `Sexe`, `Justificatif`) 
            VALUES (:r, :t, :c, :u, :d, :p,  :e, :s, :j)");

            $req3->bindValue('r',$_POST["r"], PDO::PARAM_INT);
            $req3->bindValue('t',$_POST["t"], PDO::PARAM_STR);
            $req3->bindValue('c',$_POST["c"], PDO::PARAM_INT);
            $req3->bindValue('u',$_POST["u"], PDO::PARAM_STR);
            $req3->bindValue('d',date("Y-m-d"), PDO::PARAM_STR);
            $req3->bindValue('p',$_POST["p"], PDO::PARAM_STR);
            $req3->bindValue('e',$_POST["e"], PDO::PARAM_INT);
            $req3->bindValue('s',$_POST["s"], PDO::PARAM_STR);
            $req3->bindValue('j', $fichierName , PDO::PARAM_STR);
            $req3->execute();

            $req4 = $bdd->prepare("update champions set `Sexe`=:s,`PaysID`=:p where ID=:c");
            $req4->bindValue('c',$_POST["c"], PDO::PARAM_INT);
            $req4->bindValue('s',$_POST["s"], PDO::PARAM_STR);
            $req4->bindValue('p',$_POST["p"], PDO::PARAM_STR);
            $req4->execute();

            $req5 = $bdd->prepare("select * from users where id=:c");
            $req5->bindValue('c',$user_id, PDO::PARAM_INT);
            $req5->execute();
            $user= $req5->fetch(PDO::FETCH_ASSOC);

            $_SESSION['msg_ajout_resultat']="Votre résultat est en cours de validation, vous receverez un message lorsque celui-ci aura été validé.<br>";
            if($user["newsletter"]==0){
                $_SESSION['msg_ajout_resultat'].=" Pour ne rien manquer de l'actualité du marathon abonnez-vous à notre newsletter<br><a href='/content/modules/update_profile.php?subscribe_to_newsletter=1' class='call-to-action mx-auto'>Oui, je m'abonne</a>";
            }
            header('Location:  '.$page); 
}
catch(Exception $e)
{
   echo  'erreur SQL insertion résultat : '. $e->getMessage();
}