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

    // Fonction pour slugifier le nom de fichier
    function slugify($string) {
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9-]/', '_', $string);
        $string = preg_replace('/-+/', "_", $string);
        return $string;
    }

    // Slugifier le nom du fichier
    $fichierName = slugify($fichierName);

    if ($fileinfo['error']) {
        // Gérer les erreurs d'upload
        switch ($fileinfo['error']){
            case 1: // UPLOAD_ERR_INI_SIZE
                echo "'Le fichier ".$fichierName." dépasse la limite autorisée par le serveur (fichier php.ini) !'<br />";
                break;
            case 2: // UPLOAD_ERR_FORM_SIZE
                echo  "'Le fichier ".$fichierName." dépasse la limite autorisée dans le formulaire HTML !'<br />";
                break;
            case 3: // UPLOAD_ERR_PARTIAL
                echo"'L'envoi du fichier ".$fichierName." a été interrompu pendant le transfert !'<br />";
                break;
            case 4: // UPLOAD_ERR_NO_FILE
                echo "'Le fichier ".$fichierName." que vous avez envoyé a une taille nulle !'<br />";
                break;
        }
    } else {
        if(move_uploaded_file($fichierSource,$destination_path.$fichierName)) {
            $i=2;
            // Fichier correctement envoyé
        } else {
            // Erreur lors de l'envoi du fichier
            $i=2;
        }
    }

    // Préparer et exécuter la requête d'insertion
    $req3 = $bdd->prepare("INSERT INTO `champion_admin_externe`(`Justificatif`, `Message`, `user_id`, `champion_id`, `date_creation`, `actif`) 
    VALUES (:j, :m, :u, :c,  :d, 0 )");

    $req3->bindValue('m', $_POST["message"], PDO::PARAM_STR);
    $req3->bindValue('c', $_POST["c"], PDO::PARAM_INT);
    $req3->bindValue('u', $_POST["u"], PDO::PARAM_INT);
    $req3->bindValue('d', date("Y-m-d"), PDO::PARAM_STR);
    $req3->bindValue('j', $fichierName , PDO::PARAM_STR);
    $req3->execute();

    // Récupérer les informations de l'utilisateur
    $req5 = $bdd->prepare("select * from users where id=:c");
    $req5->bindValue('c', $user_id, PDO::PARAM_INT);
    $req5->execute();
    $user= $req5->fetch(PDO::FETCH_ASSOC);

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
?>
