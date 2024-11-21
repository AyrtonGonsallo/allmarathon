<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';

include("../classes/champion.php");
include("../classes/user.php");
include("../classes/pays.php");
include 'functions.php';
$champion=new champion();
$p=new pays();

$user=new user();
$profil=$user->getUserById($user_id);

if(isset( $_GET['task']) && $_GET['task']=="add"){
	
    $nom = $_POST['prenom_form_part1']." ".$_POST['nom_form_part1'];;
    $sexe = $_POST['sexe_form_part1'];
    $pays = $_POST['pays_form_part1'];
    $dateNaissance = $_POST['dateNaissance_form_part1'];
    $lieuNaissance = $_POST['lieuNaissance_form_part1'];
    $taille = $_POST['taille_form_part1'];
    $poids = $_POST['poids_form_part1'];
    $site = $_POST['site_form_part1'];
    $nvpays = "";
    $dateChangementNat = "9999-12-31";
    $lien_equip = "";
    $facebook = $_POST['facebook_form_part1'];
    $equipementier = "";
    $instagram = $_POST['instagram_form_part1'];
    $bio = "";

    // Call the function to add a champion, passing the retrieved values
    $added_champ = $champion->addchampion(
        $user_id,
        $nom, 
        $sexe, 
        $pays, 
        $dateNaissance, 
        $lieuNaissance, 
        $taille, 
        $poids, 
        $site, 
        $nvpays, 
        $dateChangementNat, 
        $lien_equip, 
        $facebook, 
        $equipementier, 
        $instagram, 
        $bio
    );

// &&($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) ($tab_champ['validation']==true)
    if( $added_champ['validation']==true) {
        
        $_SESSION['add_champ_msg']= '<br><span style="color:green; font-size:0.8em">Votre champion a bien été ajouté. Il va être validé<br/></span>';
        header('Location: /membre-profil.php?tab=adm_fiche');} 
    
    else {
    	
        $_SESSION['add_champ_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer'.$added_champ['message'].' !<br/></span>';
        header('Location: /membre-profil.php?tab=adm_fiche');
    };
 }


 if(isset( $_GET['task']) && $_GET['task']=="update"){
	$cid = $_GET['cid'];
   //faire pareil
   $nom = $_POST['nom_form_part1'];
    $sexe = $_POST['sexe_form_part1'];
    $pays = $_POST['pays_form_part1'];
    $dateNaissance = $_POST['dateNaissance_form_part1'];
    $lieuNaissance = $_POST['lieuNaissance_form_part1'];
    $taille = $_POST['taille_form_part1'];
    $poids = $_POST['poids_form_part1'];
    $site = $_POST['site_form_part1'];
    $nvpays = $_POST['nvpays_form_part1'];
    $dateChangementNat = $_POST['dateChangementNat_form_part1'];
    $lien_equip = $_POST['lien_site_équipementier_form_part1'];
    $facebook = $_POST['facebook_form_part1'];
    $equipementier = $_POST['equipementier_form_part1'];
    $instagram = $_POST['instagram_form_part1'];
    $bio = $_POST['bio_form_part1'];

    // Call the function to add a champion, passing the retrieved values
    $updated_champ = $champion->updatechampion(
        $cid,
        $user_id,
        $nom, 
        $sexe, 
        $pays, 
        $dateNaissance, 
        $lieuNaissance, 
        $taille, 
        $poids, 
        $site, 
        $nvpays, 
        $dateChangementNat, 
        $lien_equip, 
        $facebook, 
        $equipementier, 
        $instagram, 
        $bio
    );

// &&($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) ($tab_champ['validation']==true)
    if( $updated_champ['validation']==true) {
        
        $_SESSION['update_adm_champ_msg']= '<br><span style="color:green; font-size:0.8em">Votre champion a bien été mis a jour. Il va être validé<br/></span>';
        header('Location: /membre-profil.php?tab=adm_fiche');} 
    
    else {
    	
        $_SESSION['update_adm_champ_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer'.$updated_champ['message'].' !<br/></span>';
        header('Location: /membre-profil.php?tab=adm_fiche');
    };
 }



?>