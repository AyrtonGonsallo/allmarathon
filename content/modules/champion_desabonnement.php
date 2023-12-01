<?php

include("../classes/abonnement.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

(!empty($_SESSION['auth_error'])) ? $erreur_auth=$_SESSION['auth_error'] : $erreur_auth='';
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';

if(!empty($_SESSION['user'])) {
    $user=$_SESSION['user'];
    $erreur_auth='';
    }  else $user='';

    $champ_id = (isset($_GET['champ_id']))?(int)$_GET['champ_id']:0;
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $host     = gethostbyaddr($_SERVER["REMOTE_ADDR"]);
    $ip       = $_SERVER["REMOTE_ADDR"];
    $date_fan     = date('Y-m-d H:i:s');

$champ_abonnement=new abonnement();
$champ_abonnement->desabonnement_champ($user_id,$champ_id);
$_SESSION['plus_abonnee']=1;
header("Location: /athlète-".$champ_id.".html");
           
?>
