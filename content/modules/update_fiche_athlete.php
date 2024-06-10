<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../classes/champion.php");
include("../classes/championAdminExterneClass.php");
include("../classes/champion_admin_externe_journal.php");
include("../classes/champion_admin_externe_palmares.php");
include("../classes/image.php");

$image=new image();

$champ=new champion();
$champ_admin_externe=new championAdminExterne();
$champ_admin_externe_journal=new champion_admin_externe_journal();
$champ_admin_externe_palmares=new champion_admin_externe_palmares();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
function slugify($text)
{
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}
$erreur = "";
    if( isset($_POST['sub']) ){
        if($_POST['Nom']=="")
            $erreur .= "Erreur nom.<br />";
        $poid   = ($_POST['Poids']=='')?'NULL': $_POST['Poids'];
        $taille = ($_POST['Taille']=='')?'NULL': $_POST['Taille'];
        $date   = ($_POST['DateNaissance']=='')?'NULL': $_POST['DateNaissance'];
        $datechang   = ($_POST['DateChangementNat']=='')?'NULL': $_POST['DateChangementNat'];
        if($erreur == ""){

         try {
            require_once '../database/connexion.php';
             $req4 = $bdd->prepare("UPDATE champions SET Nom=:Nom ,Poids=:poids ,Equipementier=:Equipementier,Taille=:taille ,Sexe=:Sexe ,PaysID=:PaysID,NvPaysID=:NvPaysID,DateChangementNat=:DateChangementNat ,DateNaissance=:DateNaissance ,LieuNaissance=:LieuNaissance ,Lien_site_équipementier=:lien_equip,Instagram=:Instagram,Facebook=:Facebook, Bio=:Bio,Site=:site WHERE ID=:id");

             $req4->bindValue('Nom',$_POST['Nom'], PDO::PARAM_STR);
             $req4->bindValue('Sexe',$_POST['Sexe'], PDO::PARAM_STR);
             $req4->bindValue('site',$_POST['Site'], PDO::PARAM_STR);
             $req4->bindValue('PaysID',$_POST['PaysID'], PDO::PARAM_STR);
             $req4->bindValue('NvPaysID',$_POST['NvPaysID'], PDO::PARAM_STR);
             $req4->bindValue('DateChangementNat',$datechang, PDO::PARAM_STR);
             $req4->bindValue('DateNaissance',$date, PDO::PARAM_STR);
             $req4->bindValue('LieuNaissance',$_POST['LieuNaissance'], PDO::PARAM_STR);
             $req4->bindValue('lien_equip',$_POST['lien_equip'], PDO::PARAM_STR);
             $req4->bindValue('Equipementier',$_POST['Equipementier'], PDO::PARAM_STR);
             $req4->bindValue('Instagram',$_POST['Instagram'], PDO::PARAM_STR);
             $req4->bindValue('poids',$_POST['poids'], PDO::PARAM_STR);
             $req4->bindValue('taille',$_POST['taille'], PDO::PARAM_STR);
             $req4->bindValue('Facebook',$_POST['Facebook'], PDO::PARAM_STR);
             $req4->bindValue('Bio',$_POST['Bio'], PDO::PARAM_STR);
            
             $req4->bindValue('id',$_GET['championID'], PDO::PARAM_INT);
             $statut=$req4->execute();

             $req45 = $bdd->prepare("INSERT INTO champion_admin_externe_journal (type, user_id, champion_id) VALUES ('modification', :user_id, :champion_id)");
             $req45->bindValue('user_id',$user_id, PDO::PARAM_STR);
             $req45->bindValue('champion_id',$_GET['championID'], PDO::PARAM_INT);
             $req45->execute();

         }
         catch(Exception $e)
         {
            die('Erreur : ' . $e->getMessage());
        }
        if($statut){
            header('Location: /athlete-'.$_GET['championID'].'.html'); 
        }else{
            $_SESSION['fiche_error']="Une erreur est survenue, veuillez réessayer.";
    	    header('Location: /athlete-'.$_GET['championID'].'.html');
        }
    }

 }

if(isset($_POST['submitForm']))
{
    $rang = $_POST['rang'];
   
    $date = $_POST['date_comp'];
    $temps = $_POST['temps'];
    $compFr = $_POST['evenement'];
    $user = $user_id;
    $username = $_POST['USER'];
    $championID = $_POST['championID'];
    $evID = $_POST['evID'];
    if($temps==""||$date==""||$rang==""){
        $_SESSION['fiche_error']="Veuillez renseigner les champs vides.";
        header('Location: /champion-detail-admin-'.$championID.'-'.slugify($_POST['Nom']).'.html');
    }else{
        $admin_externe_journal=$champ_admin_externe_journal->update_fiche_athlète($championID,$user_id,'champion',$evID);
    $admin_externe_palmares=$champ_admin_externe_palmares->addResults($rang,$championID,$evID,$username,$date,$compFr,$temps);
    if(($admin_externe_palmares['validation']==true) && ($admin_externe_journal['validation']==true)){ header('Location: /champion-detail-admin-'.$championID.'.html');}
    else {
        $_SESSION['fiche_error']="Une erreur est survenue, veuillez réessayer.";
        header('Location: /champion-detail-admin-'.$championID.'-'.slugify($_POST['Nom']).'.html');
    }
    }
}

if(isset($_POST['submitForm_evenement']))
{
    $rang = $_POST['rang'];
   
    $date = $_POST['date_comp'];
    $temps = $_POST['temps'];
    $compFr = $_POST['evenement'];
    $user = $user_id;
    $username = $_POST['USER'];
    $championID = $_POST['championID'];
    $evID = $_POST['evID'];

    if($temps==""||$date==""||$rang==""){
        $_SESSION['fiche_error']="Veuillez renseigner les champs vides.";
        header('Location: /champion-detail-admin-'.$championID.'.html');
    }else{
        $admin_externe_journal=$champ_admin_externe_journal->update_fiche_athlète($championID,$user_id,'champion',$evID);
    $admin_externe_palmares=$champ_admin_externe_palmares->addResults($rang,$championID,$evID,$username,$date,$compFr,$temps);

    if(($admin_externe_palmares['validation']==true) && ($admin_externe_journal['validation']==true)){ header('Location: /evenement-detail-admin-'.$evID.'.html');}
    else {
        $_SESSION['fiche_error']="Une erreur est survenue, veuillez réessayer.";
        header('Location: /evenement-detail-admin-'.$evID.'.html');
    }
    }
}
    if( isset($_POST['video_sub']) ) {

        $user = $user_id;
        $video=$_POST['video'];
        $championID = $_GET['championID'];

        $admin_externe_video=$champ_admin_externe->updateVideoAdmin($user,$championID,$video);
        $admin_externe_journal_video=$champ_admin_externe_journal->update_fiche_athlète($championID,$user_id,'video');
        if($admin_externe_video['validation']){ header('Location: /champion-detail-admin-'.$championID.'.html');}
        else {
            $_SESSION['fiche_error']="Une erreur est survenue, veuillez réessayer.";
            header('Location: /champion-detail-admin-'.$championID.'.html');
        }
        
    }
    if(isset ($_POST['extern_supp_id'])){
        $deletResult=$champ_admin_externe_palmares->deleteResultAdmin($_POST['extern_supp_id']);
        if($deletResult['validation']==true) header('Location: /champion-detail-admin-'.$_GET['championID'].'.html');
        else {
            $_SESSION['fiche_error']="Une erreur est survenue, veuillez réessayer.";
            header('Location: /champion-detail-admin-'.$_GET['championID'].'.html');
        }
    }

    if( isset($_POST['modifPhoto']) ) {

        if($_POST['actif'] == "oui"){
        $actif = 1;
        }elseif($_POST['actif'] == "non"){
            $actif = 0;
        }
        
        $description=$_POST['legende'];
        $id_image=$_GET['imageID'];
        $championID=$_POST['championID'];

        $image_modif=$image->modif_image_admin($id_image,$description,$actif);

        if($image_modif['validation']==true){ header('Location: /champion-detail-admin-'.$championID.'.html');}
        else {
            $_SESSION['fiche_error']="Une erreur est survenue, veuillez réessayer.";
            header('Location: /champion-detail-admin-'.$championID.'.html');
        }
        
    }

?>