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

if(isset( $_POST['sub'])){
	
	$id=$_GET['championID'];
	$Poids = $_POST['Poids'];
    $Taille =$_POST['Taille'];
    $DateNaissance = $_POST['DateNaissance'];
    $LieuNaissance=$_POST['LieuNaissance'];
    $Grade=$_POST['Grade'];
    $Clubs=$_POST['Clubs'];
    $TokuiWaza=$_POST['TokuiWaza'];
    $MainDirectrice=$_POST['MainDirectrice'];
    $Activite=$_POST['Activite'];

    $Forces=$_POST['Forces'];
    $Idole=$_POST['Idole'];
    $Idole2=$_POST['Idole2'];
    $Idole3=$_POST['Idole3'];
    $Idole4=$_POST['Idole4'];
    $Idole5=$_POST['Idole5'];
    $Idole6=$_POST['Idole6'];
    $Idole7=$_POST['Idole7'];
    
    $Lidole2=$_POST['Lidole2'];
    $Lidole3=$_POST['Lidole3'];
    $Lidole4=$_POST['Lidole4'];
    $Lidole5=$_POST['Lidole5'];
    $Lidole6=$_POST['Lidole6'];
    $Lidole7=$_POST['Lidole7'];

    $Anecdote=$_POST['Anecdote'];
    $Phrase=$_POST['Phrase'];
    $Site=$_POST['Site'];

    $ip_mod=$_SERVER["REMOTE_ADDR"];
    $date_mod=date('Y-m-d H:i:s');

    $tab_champ=$champ->updateChampByAdminExterne($id,$DateNaissance,$LieuNaissance,$Grade,$Clubs,$Taille,$Poids,$TokuiWaza,$MainDirectrice,$Activite,$Forces,$Idole,$Idole2,$Idole3,$Idole4,$Idole5,$Idole6,$Idole7,$Lidole2,$Lidole3,$Lidole4,$Lidole5,$Lidole6,$Lidole7,$Anecdote,$Phrase,$Site);
    
    $admin_externe=$champ_admin_externe->updateChampAdmin($user_id,$id,$ip_mod,$date_mod);
	
	$admin_externe_journal=$champ_admin_externe_journal->update_fiche_athlète($id,$user_id,'champion');
// &&($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) ($tab_champ['validation']==true)
    if(($tab_champ['validation']==true) && ($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) )  header('Location: /champion-detail-admin-'.$id.'.html'); 
    else {
    	$_SESSION['fiche_error']="Une erreur est survenue, veuillez réessayer.";
    	header('Location: /champion-detail-admin-'.$id.'.html');
    };
 }

if(isset($_POST['submitForm']))
{
    $rang = $_POST['rang'];
    $poids =$_POST['poids'];
    $date = $_POST['date_comp'];
    $lieu = $_POST['lieu'];
    $catAge = $_POST['catAge'];
    $compType = $_POST['compType'];
    $compNiv = $_POST['compNiv'];
    $comDep = $_POST['compDep'];
    $compReg = $_POST['compReg'];
    $compFr = $_POST['compFr'];
    $user = $user_id;
    $championID = $_POST['championID'];

    if($poids==""||$date==""||$lieu==""){
        $_SESSION['fiche_error']="Veuillez renseigner les champs vides.";
        header('Location: /champion-detail-admin-'.$championID.'.html');
    }else{
        $admin_externe_journal=$champ_admin_externe_journal->update_fiche_athlète($championID,$user_id,'champion');
    $admin_externe_palmares=$champ_admin_externe_palmares->addResults($rang,$championID,$poids,$date,$catAge,$compType,$lieu,$compNiv,$comDep,$compReg,$compFr);

    if(($admin_externe_palmares['validation']==true) && ($admin_externe_journal['validation']==true)){ header('Location: /champion-detail-admin-'.$championID.'.html');}
    else {
        $_SESSION['fiche_error']="Une erreur est survenue, veuillez réessayer.";
        header('Location: /champion-detail-admin-'.$championID.'.html');
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