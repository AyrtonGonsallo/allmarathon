<?php

include("../classes/abonnement.php");
include("../classes/commentaire.php");

$commentaire=new commentaire();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

(!empty($_SESSION['auth_error'])) ? $erreur_auth=$_SESSION['auth_error'] : $erreur_auth='';
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';

if(!empty($_SESSION['user'])) {
    $user=$_SESSION['user'];
    $erreur_auth='';
    }  else $user='';

    if(!empty($_GET['commentaire'])) {
    $com=$_GET['commentaire'];
    $erreur_com='';
    }  else $erreur_com="Commentaire vide ! ";

    $news_id=0;
    $video_id=0;
    $champ_id = (isset($_GET['champ_id']))?(int)$_GET['champ_id']:0;
    $video_id = (isset($_GET['video_id']))?(int)$_GET['video_id']:0;
    $news_id = (isset($_GET['id_news']))?(int)$_GET['id_news']:0;
    $date_com     = date('Y-m-d H:i:s');
    
    if($erreur_com==""){
        $commentaire->addCommentaire($user_id,$com,$date_com,$news_id,$video_id,$champ_id);
        $_SESSION['commentaire_success']="Votre commentaire a bien été ajouté.";
        if($champ_id!=0) 
        header("Location: /athlete-".$champ_id.".html");
        elseif ($video_id!=0) {
        header("Location: /video-de-marathon-".$video_id.".html");
        }
        elseif ($news_id!=0) {
        header("Location: /actualite-marathon-".$news_id.".html");
        }
    }
    else {
        $_SESSION['commentaire_error']=$erreur_com;
        if($champ_id!=0) 
        header("Location: /athlete-".$champ_id.".html");
        elseif ($video_id!=0) {
        header("Location: /video-de-marathon-".$video_id.".html");
        }
        elseif ($news_id!=0) {
        header("Location: /actualite-marathon-".$news_id.".html");
        }
    }
          
?>
