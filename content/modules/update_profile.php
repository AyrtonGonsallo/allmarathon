<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';


include("../classes/user.php");
include("../classes/pays.php");
include 'functions.php';

function VerifierAdresseMail($adresse)  
{  
   $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';  
   if(preg_match($Syntaxe,$adresse))  
      return true;  
   else  
     return false;  
}

$p=new pays();

$user=new user();
$profil=$user->getUserById($user_id);
if(isset( $_GET['subscribe_to_newsletter'])){
    if($user->subscribe_to_newsletter($user_id)){
        $page=($_COOKIE["page_when_adding_result"])?$_COOKIE["page_when_adding_result"]:$_COOKIE["page_when_logging_to_rev_fiche"];;
        unset($_COOKIE["page_when_adding_result"]); 
        unset($_COOKIE["page_when_logging_to_rev_fiche"]); 
        header('Location:  '.$page);
       
    }

}
if(isset( $_POST['sub'])){
	
    $membre_id=$_GET['profilID'];
	$nom=(isset($_POST['nom']))? $_POST['nom'] : "";
	$prenom = (isset($_POST['prenom']))? $_POST['prenom'] : "";
    $email =(isset($_POST['email']))? $_POST['email'] : "";
    $date_de_naissance = (isset($_POST['date_de_naissance']))? $_POST['date_de_naissance'] : "";
    
    $pays=(isset($_POST['pays']))? $_POST['pays'] : "";
    
    $newsletter=(isset($_POST['newsletter']))? $_POST['newsletter'] : "";
    $offres=(isset($_POST['offres']))? $_POST['offres'] : "";

    $LieuNaissance=$_POST['LieuNaissance'];
    $Equipementier=$_POST['Equipementier'];
    $lien_equip=$_POST['lien_equip'];
   $Instagram=$_POST['Instagram'];
   $poids=$_POST['poids'];
   $taille=$_POST['taille'];
   $Facebook=$_POST['Facebook'];
   $Bio=$_POST['Bio'];
   $sexe=$_POST['Sexe'];
   $c_id=$_POST['c_id'];

    if( $nom=="" || $prenom=="" || $email==""){
        $_SESSION['update_profile_msg']="<br><span style='color:#cc0000; font-size:0.8em'>Merci de remplir les champs obligatoires *<br/></span>";
        header('Location: /membre-profil.php');
    }
    elseif (!VerifierAdresseMail($email)) {
        $_SESSION['update_profile_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Votre adresse e-mail n\'est pas valide.<br/></span>';
        header('Location: /membre-profil.php');
        
    }
    else{
        $pays=$p->getPaysById($pays)['donnees']->getAbreviation();
        
        $user_updated=$user->updateUserById($nom,$prenom,$sexe,$email,$date_de_naissance,$pays,$newsletter,$offres,$membre_id,$LieuNaissance,$Equipementier,$lien_equip,$Instagram,$poids,$taille,$Facebook,$Bio,$c_id);
    }

// &&($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) ($tab_champ['validation']==true)
    if( $user_updated['validation']==true) {
        
        $_SESSION['update_profile_msg']= '<br><span style="color:green; font-size:0.8em">Votre profil a bien été modifié.<br/></span>';
        header('Location: /membre-profil.php');} 
    
    else {
    	
        $_SESSION['update_profile_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer'.$user_updated['validation'].' !<br/></span>';
        header('Location: /membre-profil.php');
    };
 }


 if(isset( $_POST['subig'])){
	
    $membre_id=$_GET['profilID'];
	$nom=(isset($_POST['nom']))? $_POST['nom'] : "";
	$prenom = (isset($_POST['prenom']))? $_POST['prenom'] : "";
    $email =(isset($_POST['email']))? $_POST['email'] : "";
    
    $pays=(isset($_POST['pays']))? $_POST['pays'] : "";
    

   $sexe=$_POST['Sexe'];
   $c_id=$_POST['c_id'];
   $pays=$p->getPaysById($pays)['donnees']->getAbreviation();
    //updateUserIGById($nom,$prenom,$sexe,$email,$pays,$membre_id,$c_id)
        $user_updated=$user->updateUserIGById($nom,$prenom,$sexe,$email,$pays,$membre_id,$c_id);
    

// &&($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) ($tab_champ['validation']==true)
    if( $user_updated['validation']==true) {
        
        $_SESSION['update_profile_msg']= '<br><span style="color:green; font-size:0.8em">Votre profil a bien été modifié.<br/></span>';
        header('Location: /membre-profil.php');} 
    
    else {
    	
        $_SESSION['update_profile_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer'.$user_updated['validation'].' !<br/></span>';
        header('Location: /membre-profil.php');
    };
 }


 if(isset( $_POST['subdc'])){
	
    $membre_id=$_GET['profilID'];
    $date_de_naissance = (isset($_POST['date_de_naissance']))? $_POST['date_de_naissance'] : "";
    
    
    $newsletter=(isset($_POST['newsletter']))? $_POST['newsletter'] : "";
    $offres=(isset($_POST['offres']))? $_POST['offres'] : "";

    $LieuNaissance=$_POST['LieuNaissance'];
    $Equipementier=$_POST['Equipementier'];
    $lien_equip=$_POST['lien_equip'];
   $Instagram=$_POST['Instagram'];
   $poids=$_POST['poids'];
   $taille=$_POST['taille'];
   $Facebook=$_POST['Facebook'];
   $Bio=$_POST['Bio'];
   $c_id=$_POST['c_id'];

    
        $user_updated=$user->updateUserDCById($date_de_naissance,$newsletter,$offres,$membre_id,$LieuNaissance,$Equipementier,$lien_equip,$Instagram,$poids,$taille,$Facebook,$Bio,$c_id);
    

// &&($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) ($tab_champ['validation']==true)
    if( $user_updated['validation']==true) {
        
        $_SESSION['update_profile_msg']= '<br><span style="color:green; font-size:0.8em">Votre profil a bien été modifié.<br/></span>';
        header('Location: /membre-profil.php');} 
    
    else {
    	
        $_SESSION['update_profile_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer'.$user_updated['validation'].' !<br/></span>';
        header('Location: /membre-profil.php');
    };
 }

 if(isset( $_POST['edit_password'])){
    
    $membre_id=$_GET['profilID'];
    $password=(isset($_POST['password']))? $_POST['password'] : "";
    $conf_password = (isset($_POST['conf_password']))? $_POST['conf_password'] : "";

    if( $password=="" || $conf_password=="" || $password!=$conf_password ){
        $_SESSION['update_mdp_msg']="<br><span style='color:#cc0000; font-size:0.8em'>Les mots de passe doivent se correspondre !<br/></span>";
        header('Location: /membre-profil.php');
    }
    else{
        $newpass=encrypt($password);
        $user_updated=$user->updatePassprdByUserId($membre_id, $newpass);
    }

// &&($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) ($tab_champ['validation']==true)
    if( $user_updated['validation']==true) {
        
        $_SESSION['update_mdp_msg']= '<br><span style="color:green; font-size:0.8em">Votre mot de passe a bien été modifié.<br/></span>';
        header('Location: /membre-profil.php');} 
    
    else {
        
        $_SESSION['update_mdp_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer'.$user_updated['validation'].' !<br/></span>';
        header('Location: /membre-profil.php');
    };
 }



?>