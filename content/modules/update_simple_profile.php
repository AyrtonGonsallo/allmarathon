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

	
    $membre_id=$_GET['profilID'];
	$nom=(isset($_POST['nom_form_part2']))? $_POST['nom_form_part2'] : "";
	$prenom = (isset($_POST['prenom_form_part2']))? $_POST['prenom_form_part2'] : "";
    $email =(isset($_POST['email_form_part2']))? $_POST['email_form_part2'] : "";
    $username =(isset($_POST['username_form_part2']))? $_POST['username_form_part2'] : "";
    $date_de_naissance = (isset($_POST['date_de_naissance_form_part2']))? $_POST['date_de_naissance_form_part2'] : "";
    $pays=(isset($_POST['pays_form_part2']))? $_POST['pays_form_part2'] : "";
    $newsletter=(isset($_POST['newsletter_form_part2']))? $_POST['newsletter_form_part2'] : "";
    $offres=(isset($_POST['offres_form_part2']))? $_POST['offres_form_part2'] : "";
    $code_postale=$_POST['code_postale_form_part2'];
    $ville=$_POST['ville_form_part2'];

    if( $nom=="" || $prenom=="" || $email==""){
        $_SESSION['update_profile_msg']="<br><span style='color:#cc0000; font-size:0.8em'>Merci de remplir les champs obligatoires *<br/></span>";
        header('Location: /membre-profil.php');
    }
    elseif (!VerifierAdresseMail($email)) {
        $_SESSION['update_profile_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Votre adresse e-mail n\'est pas valide.<br/></span>';
        header('Location: /membre-profil.php');
        
    }
    else{
        
        $user_updated=$user->updateSimpleUserById($nom, $prenom,$username, $email, $date_de_naissance, $pays, $newsletter, $offres, $membre_id, $code_postale, $ville);
    }

// &&($admin_externe['validation']==true) && ($admin_externe_journal['validation']==true) ($tab_champ['validation']==true)
    if( $user_updated['validation']==true) {
        
        $_SESSION['update_profile_msg']= '<br><span style="color:green; font-size:0.8em">Votre profil a bien été modifié.<br/></span>';
        header('Location: /membre-profil.php');} 
    
    else {
    	
        $_SESSION['update_profile_msg']= '<br><span style="color:#cc0000; font-size:0.8em">Une erreur est survenue, veuillez réessayer'.$user_updated['validation'].' !<br/></span>';
        header('Location: /membre-profil.php');
    };
 


?>