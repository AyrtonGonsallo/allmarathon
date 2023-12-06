<?php

include("../classes/championPopularite.php");

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

$champ_pop=new championPopularite();

$champ_pop->ne_plus_devenir_fan($champ_id,$user_id);
$_SESSION['plus_fan']=1;
header("Location: /athlete-".$champ_id.".html");

?>
