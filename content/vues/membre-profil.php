<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!empty($_SESSION['auth_error'])) {
   $erreur_auth=$_SESSION['auth_error'];
   unset($_SESSION['auth_error']);
}else $erreur_auth='';

(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!empty($_SESSION['user'])) {
    $user_session=$_SESSION['user'];
    $erreur_auth='';
} 
else {
    $user_session='';
    header('Location: /');
    exit();
    
}
include("../classes/pub.php");
include("../classes/user.php");
include("../classes/championAdminExterneClass.php");
include("../classes/pays.php");
include("../classes/champion.php");

include("../classes/champion_admin_externe_journal.php");

include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/partenaire.php");

include("../classes/commentaire.php");


$pub=new pub();

$pub728x90=$pub->getBanniere728_90("accueil")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];


$evCategorieEvenement=new evCategorieEvenement();




$evenement=new evenement();





$champion_admin_externe_journal=new champion_admin_externe_journal();
$journal=$champion_admin_externe_journal->getJournalByUser($user_id)['donnees'];

$user=new user();
$profil =$user->getUserById($user_id)['donnees'];
$added_res = $user->getAddedResults($profil->getUsername())['donnees'];



$champion=new champion();
$user_champ=$champion->getUserChampion($user_id)['donnees'];
$pays=new pays();
$liste_pays=$pays->getAll()['donnees'];



$champAdminExterne=new championAdminExterne();
$athlete_adminitres=$champAdminExterne->getChampionsByUser($user_id)['donnees'];
$athlete_adminitre=$champion->getChampionByUserId($user_id)['donnees'];
$comments = new commentaire();
$commentsByUser=$comments->getCommentairesByUser($user_id)['donnees'];





function slugify($text)
{
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}

function age($date_naissance) {
    $timestamp = time()-strtotime($date_naissance);
    $age_calcule=date('Y',$timestamp)-1970;
    $res= ($age_calcule < 150) ? $age_calcule.' ans' : "";
    // return (date('Y',$timestamp)-1970).' ans';
    return $res;
}

function changeDate($date) {
    //$timestamp = mktime(substr($date, 11, 2),substr($date, 14, 2),0,substr($date, 5, 2),substr($date, 8, 2),substr($date, 0, 4));
    $timestamp = strtotime($date);
    return date("d/m/Y",$timestamp);
}

function depuis($date) {
    $timestamp = mktime(substr($date, 11, 2),substr($date, 14, 2),0,substr($date, 5, 2),substr($date, 8, 2),substr($date, 0, 4));
    $time = time() - $timestamp;

    if($time < 60)
        return "moins d'une minute";
    if($time < 3600)
        return "il y a ".(int)($time/60)." minutes";
    if($time < 7200)
        return "il y a ".(int)($time/3600)." heure";
    if($time < 3600*24)
        return "il y a ".(int)($time/3600)." heures";
    return (int)($time/(3600*24))." jours";
    return round((date("n",$timestamp)-1)/3,0);
}

function dateFinParis($date) {
    $day    = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
    $month  = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
    $timestamp = mktime(0,0,0,substr($date, 5, 2),substr($date, 8, 2)-1,substr($date, 0, 4));
    return $day[date('N',$timestamp)].' '.date('d',$timestamp).' '.$month[date("n",$timestamp)-1];
}


require('./config.php');
# the createAuthUrl() method generates the login URL.
$login_url = $client->createAuthUrl();
?>

<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Allmarathon.net : Membre Détails</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="../../css/responsive.css">
    <style>
    .google .btn{
      display: flex;
      justify-content: center;
      padding: 50px;
    }
    .google a{
      all: unset;
      cursor: pointer;
      padding: 10px;
      display: flex;
      width: 250px;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      background-color: #f9f9f9;
      border: 1px solid rgba(0, 0, 0, .2);
      border-radius: 3px;
    }
    .google a:hover{
      background-color: #ffffff;
    }
    .google img{
      width: 50px;
      margin-right: 5px;
    
    }
    #google-connected{
        width: 20px;
        height: 20px;
        float:right;
        border-radius: 100px;
        background-color: #1ecf1e;
    }
    #google-not-connected{
        width: 20px;
        height: 20px;
        float:right;
        border-radius: 100px;
        background-color: #fd1f1f;
    }
  </style>
    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->


    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlete-detail membre-profil">
     
        <div class="row banniere1 bureau ban ban_728x90">
             <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
             <div  class="col-sm-12 ads-contain">
            <?php
                if($pub728x90 !="") {
                echo '<a target="_blank" href="'.$pub728x90["url"].'" class="col-sm-12">';
                    echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
                    echo '</a>';
                }else if($getMobileAds !="") {
                echo $getMobileAds["code"] ? $getMobileAds["code"] :  "<a href=".$getMobileAds["url"]." target='_blank'><img src=".'../images/pubs/'.$getMobileAds['image'] . " alt='' style=\"width: 100%;\" /></a>";
                }
                ?></div>
        </div>
        <div class="row">
            <div class="col-sm-12 left-side resultat-detail">

                <div class="row">
                    <div class="col-sm-12">
                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#tab1" role="tab" data-toggle="tab">Profil</a></li>
                            <li><a href="#tab2" role="tab" data-toggle="tab">Vos actions</a></li>
                            <?php if($user_champ){?>
                                <li><a href="#tab7" role="tab" data-toggle="tab">Résultats</a></li>
                            <? }?>
                            <? if(sizeof($athlete_adminitres)!=0 && 0) {?>
                            <li><a href="#tab8" role="tab" data-toggle="tab">Fiche revendiquée</a></li>
                            <? }?>
                            <li><a href="#tab4" role="tab" data-toggle="tab">Fiche athlète</a></li>
                            <li><a href="#tab5" role="tab" data-toggle="tab">Mot de passe</a></li>
                            <li><a href="#tab6" role="tab" data-toggle="tab">Google</a></li>
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content technique-detail">
                            <div class="active tab-pane fade in" id="tab1">
                           
                        <?php
                        if($user_champ){
                            echo '<br/><strong>User: </strong>'.$user_champ->getNom();
                        }
                        if(age($profil->getDate_naissance())!=""){
                            echo "<br><strong>Né le :</strong> ".changeDate($profil->getDate_naissance()).' ('.age($profil->getDate_naissance()).')';
                        }
                        
                        if($profil->getVille() != ""){
                            echo '<br/><strong>Ville : </strong>'.$profil->getVille();
                        }
                        if($user_champ){
                            $flag=$pays->getFlagByAbreviation($user_champ->getPaysID())['donnees']['Flag'];
                            $pays_flag= ($flag!='') ? '<span><img  class="flag_membre" src="/images/flags/'.$flag.'" alt=""/></span>':"";
                            echo '<br/> <strong>Pays : '.$pays_flag.' </strong>'.$pays->getFlagByAbreviation($user_champ->getPaysID())['donnees']['NomPays'];
                        
                        }
                        else{
                            $flag=$pays->getFlagByAbreviation($profil->getPays())['donnees']['Flag'];
                            $pays_flag= ($flag!='') ? '<span><img  class="flag_membre" src="/images/flags/'.$flag.'" alt=""/></span>':"";
                            echo '<br/> <strong>Pays : '.$pays_flag.' </strong>'.$pays->getFlagByAbreviation($profil->getPays())['donnees']['NomPays'];
                        }
                        if(sizeof($athlete_adminitres)!=0) {

                            echo '<br/> <strong>Administre les athlètes : </strong>';
                            $athletes_adminitres="";
                            foreach($athlete_adminitres as $athlete){
                                $champ=$champion->getChampionById($athlete->getChampion_id())['donnees'];
                                $athletes_adminitres.= ' <a href="/athlete-'.$champ->getId().'-'.slugify($champ->getNom()).'.html" class="link_customized">'.$champ->getNom().'</a> -';
                            }
                            echo substr($athletes_adminitres, 0, -1);;
                        }

                        
                        
                        echo "<br/><br/><br/><a href='/contact.html' type='button' class='contact_admin'>Envoyer un message à l'administrateur</a>";
                        ?>

                            </div>
                            <div class="tab-pane fade" id="tab2">
                                <br>
                                <ul class="videos-tab">
                                    <?php 
                                    // echo "<pre>";
                                    // var_dump($commentsByUser);
                
                                    // echo "</pre>";

                                    foreach ($commentsByUser as $comment) {
                                        
                                        echo stripslashes('<li><img src="/images/CSS/commentaire.png" alt="" style="margin-left:3px;margin-right:3px;" align="left" /> '.$comment->getCommentaire().'</li>');
                                    }


                         if(sizeof($journal)!=0)
                            foreach ($journal as $j) {

                                $champ=$champion->getChampionById($j->getChampion_id())['donnees'];
                              
                                switch ($j->getType()){
                                    case 'new_admin':
                                    if($champ)
                                    {
                                        echo '
                                        <li><img src="/images/CSS/administrateur.png" alt="" style="margin-left:3px;margin-right:3px" align="left" /> '.$profil->getUsername().' administre la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" class="link_customized">'.$champ->getNom().'</a>
                                           ( '.depuis($j->getDate()).' ) </li>';
                                       }
                                       break;
                                       case 'modification':
                                       if($champ)
                                         { echo '<li><img src="/images/CSS/fiche.png" alt="" style="margin-left:3px;margin-right:3px" align="left" /> La fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" class="link_customized">'.$champ->getNom().'</a> a été mise à jour ( '.depuis($j->getDate()).' ) </li> ';}
                                     break;
                                     case 'photo' :
                                     if($champ)
                                         { echo '<li><img src="/images/CSS/photo.png" alt="" style="margin-left:3px;margin-right:3px;" align="left" /> Une photo a été ajoutée dans la galerie de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" class="link_customized" >'.$champ->getNom().'</a>  ( '.depuis($j->getDate()).' ) </li>';}
                                     break;
                                     case 'video' :
                                        if($champ)
                                            {echo '<li><img src="/images/CSS/Video.png" alt="" style="margin-left:3px;margin-right:3px;" align="left" /> Une vidéo a été ajoutée dans la galerie de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" class="link_customized">'.$champ->getNom().'</a> ( '.depuis($j->getDate()).' )</li> ';}
                                        break;
                                    case 'modification' :
                                        echo '<li><img src="/images/CSS/administrateur.png" alt="" style="margin-left:3px;margin-right:3px;" align="left" /> '.$profil->getUsername().' a modifié la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" class="link_customized">'.$champ->getNom().'</a> ( '.depuis($j->getDate()).' )  </li>';
                                        break;
                                    case 'new_admin_club' :
                                        echo '<li><img src="/images/CSS/administrateur.png" alt="" style="margin-left:3px;margin-right:3px;" align="left" /> '.$profil->getUsername().' administre la fiche du club : <a href="/club-marathon-'.$club_ad->getID().'.html" class="link_customized">'.$club_ad->getClub().'</a> ( '.depuis($j->getDate()).' )  </li>';
                                        break;
                                    case 'club' :
                                        echo '<li><img src="/images/CSS/fiche.png" alt="" style="margin-left:3px;margin-right:3px;" align="left" /> La fiche du club <a href="/club-marathon-'.$club_ad->getID().'.html" class="link_customized">'.$club_ad->getClub().' a été mise à jour</a> ( '.depuis($j->getDate()).' ) </li> ';
                                        break;
                                    default:
                                        continue 2;
                                }

                            }
                          else {
                            echo $profil->getUsername()." n'a fait aucune Action.";
                          }  
                            ?>
                                </ul>
                            </div>
                            
                            <div class="tab-pane fade" id="tab4">

                            
                                <br>
                                <!--  -->
                                <?php if(!$user_champ){?>
                                    <a  id="creer_fiche" href="#"  class="call-to-action mx-auto">
                                        Je souhaite créer une fiche athlète
                                    </a>
                                    <form id="creer_fiche_form_part1">

                                                <table style="font-size: 0.9em;">
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="nom_form_part1">Nom * : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" align="left" class="update_athlète_input" required
                                                                name="nom_form_part1" id="nom_form_part1" value="" />
                                                        </td>
                                                        <td class="col-md-2"></td>
                                                    </tr>
                                                

                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="prenom_form_part1">Prénom * : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"
                                                                class="update_athlète_input" required name="prenom_form_part1" id="prenom_form_part1"
                                                                value="" />
                                                        </td>
                                                    </tr>
                                                

                                                    <tr class="row">
                                                        <td class="col-md-3" align="left"></td>
                                                        <td class="pull-right">
                                                            <!-- <input type="submit" class="btn_custom" name="sub" value ="Modifier"/> -->
                                                            <button type="submit" name="sub"
                                                                class="btn_custom">Chercher</button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>
                                            <div id="result-suggestion"></div>
                                <?php }?>
                                    <ul>
                                        <?php 
                                            if(isset($_SESSION['update_profile_msg'])){
                                                echo $_SESSION['update_profile_msg'];
                                                unset($_SESSION['update_profile_msg']);
                                            }
                                        
                                        ?>
                                        

                                        <form id="creer_fiche_form_part2"
                                            action="/content/modules/update_profile.php?profilID=<?php echo $profil->getId(); ?>"
                                            method="post">

                                            <table style="font-size: 0.9em;">
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="nom_form_part2">Nom * : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" align="left" class="update_athlète_input" required
                                                            name="nom_form_part2" id="nom_form_part2" value="<?php echo $profil->getNom(); ?>" />
                                                    </td>
                                                    <td class="col-md-2"></td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="Sexe_form_part2">Sexe : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="radio" name="Sexe_form_part2" value="M" <?php if($user_champ && $user_champ->getSexe()=="M") echo 'checked="checked"';?> />
                                                        <span>homme</span>
                                                        <input type="radio" name="Sexe_form_part2" value="F" <?php if($user_champ &&  $user_champ->getSexe()=="F") echo 'checked="checked"';?> />
                                                        <span >femme</span>
                                                    </td>
                                                </tr>

                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="prenom_form_part2">Prénom * : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input"
                                                            class="update_athlète_input" required name="prenom_form_part2" id="prenom_form_part2"
                                                            value="<?php echo $profil->getPrenom(); ?>" />
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="email_form_part2">E-mail * : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input" name="email_form_part2"
                                                            id="email_form_part2" required
                                                            value="<?php echo $profil->getEmail(); ?>" />
                                                    </td>
                                                </tr>
                                                <input type="hidden" name="c_id_form_part2" id="c_id_form_part2" value="<?php if($user_champ){echo $user_champ->getID();} ?>" />

                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="date_de_naissance_form_part2">Date de naissance : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input"
                                                            id="date_de_naissance_form_part2" name="date_de_naissance_form_part2"
                                                            value="<?php echo $profil->getDate_naissance(); ?>" />
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="pays_form_part2">Pays : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <select name="pays_form_part2" class="update_athlète_input" id="pays_form_part2">
                                                            <?php
                                                                foreach ($liste_pays as $p) {
                                                                    $selected="";
                                                                    if($user_champ){
                                                                        $selected=($p->getAbreviation()==$user_champ->getPaysID()) ? "selected":"";
                                                                    }
                                                                    
                                                                    echo '<option value="' .$p->getAbreviation(). '"'.$selected.'>' .$p->getNomPays(). '</option>';
                                                                }
                                                                ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="LieuNaissance_form_part2">Lieu de Naissance : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input"  name="LieuNaissance_form_part2" id="LieuNaissance_form_part2" value="<?php if($user_champ){echo $user_champ->getLieuNaissance();} ?>" />
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="Equipementier_form_part2">Equipementier : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input"  name="Equipementier_form_part2" id="Equipementier_form_part2" value="<?php if($user_champ){echo $user_champ->getEquipementier();} ?>" />
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="lien_equip_form_part2">Lien site équipementier : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input"  name="lien_equip_form_part2" id="lien_equip_form_part2" value="<?php if($user_champ){echo $user_champ->getLien_site_équipementier();} ?>" />
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="Instagram_form_part2">Instagram : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input"  name="Instagram_form_part2" id="Instagram_form_part2" value="<?php if($user_champ){ echo $user_champ->getInstagram();} ?>" />
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="poids_form_part2">poids : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input"  name="poids_form_part2" id="poids_form_part2" value="<?php if($user_champ){ echo $user_champ->getPoids();} ?>" />
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="taille_form_part2">taille : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input"  name="taille_form_part2" id="taille_form_part2" value="<?php if($user_champ){ echo $user_champ->getTaille();} ?>" />
                                                    </td>
                                                </tr>

                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="Facebook_form_part2">Facebook : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input type="text" class="update_athlète_input"  name="Facebook_form_part2" id="Facebook_form_part2" value="<?php if($user_champ){ echo $user_champ->getFacebook();} ?>" />
                                                    </td>
                                                </tr>
                                                <tr class="row">
                                                    <td class="col-md-3" align="left">
                                                        <label for="Bio_form_part2">Bio : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <textarea name="Bio_form_part2" id="Bio_form_part2" cols="50" rows="20"><?php if($user_champ){ echo $user_champ->getBio(); }?></textarea>
                                                    </td>
                                                </tr>
                                                
                                                <tr class="row">
                                                    <td class="col-md-3" align="right">
                                                        <input type="checkbox" name="newsletter_form_part2" value="1"
                                                            <?php if ($profil->getNewsletter()==1): ?>checked<?php endif ?>>
                                                    </td>
                                                    <td class="col-md-7">j'accepte de recevoir la newsletter hebdomadaire de
                                                        allmarathon</td>
                                                </tr>

                                                <tr class="row">
                                                    <td class="col-md-3" align="right">
                                                        <input type="checkbox" name="offres_form_part2" value="1"
                                                            <?php if ($profil->getOffres()==1): ?>checked<?php endif ?>>
                                                    </td>
                                                    <td class="col-md-7">j'accepte de recevoir des offres commerciales des
                                                        partenaires de allmarathon</td>
                                                </tr>

                                                <tr class="row">
                                                    <td class="col-md-3" align="left"></td>
                                                    <td class="pull-right">
                                                        <!-- <input type="submit" class="btn_custom" name="sub" value ="Modifier"/> -->
                                                        <button type="submit" name="sub"
                                                            class="btn_custom">Modifier</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>

                                    </ul>
                                
                            </div>
                           
                            <? if(sizeof($athlete_adminitres)!=0 && 0) {?>
                            <div class="tab-pane fade pt-10" id="tab8">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="active"><a href="#tab8-1" role="tab" data-toggle="tab">Infos</a></li>
                                    <li><a href="#tab8-2" role="tab" data-toggle="tab">Photos</a></li>
                                    <li><a href="#tab8-3" role="tab" data-toggle="tab">Vidéos</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="active tab-pane fade in pt-10" id="tab8-1">
                                        <ul>
                                            <?php 
                                                if(isset($_SESSION['update_profile_msg'])){
                                                    echo $_SESSION['update_profile_msg'];
                                                    unset($_SESSION['update_profile_msg']);
                                                }
                                                
                                                ?>

                                            <form   
                                                action="/content/modules/update_fiche_athlete.php?championID=<?php echo $athlete_adminitre->getId(); ?>"
                                                method="post">

                                                <table style="font-size: 0.9em;">
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="nom">Nom * : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" align="left" class="update_athlète_input" required
                                                                name="Nom" id="nom" value="<?php echo $athlete_adminitre->getNom(); ?>" />
                                                        </td>
                                                        <td class="col-md-2"></td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="Sexe">Sexe : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="radio" name="Sexe" value="M" <?php if($athlete_adminitre->getSexe()=="M") echo 'checked="checked"';?> />
                                                            <span>homme</span>
                                                            <input type="radio" name="Sexe" value="F" <?php if($athlete_adminitre->getSexe()=="F") echo 'checked="checked"';?> />
                                                            <span >femme</span>
                                                        </td>
                                                    </tr>

                                                    
                                                

                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="date_de_naissance">Date de naissance : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"
                                                                id="date_de_naissance" name="DateNaissance"
                                                                value="<?php echo $athlete_adminitre->getDateNaissance(); ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="DateChangementNat">Date de changement de nationalité: </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"
                                                                id="DateChangementNat" name="DateChangementNat"
                                                                value="<?php echo $athlete_adminitre->getDateChangementNat(); ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="pays">Pays : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <select name="PaysID" class="update_athlète_input" id="pays">
                                                                <?php
                                                        foreach ($liste_pays as $p) {
                                                            $selected=($p->getAbreviation()==$athlete_adminitre->getPaysID()) ? "selected":"";
                                                            echo '<option value="' .$p->getAbreviation(). '"'.$selected.'>' .$p->getNomPays(). '</option>';
                                                        }
                                                        ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="NvPaysID">Nouveau pays : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <select name="NvPaysID" class="update_athlète_input" id="NvPaysID">
                                                                <?php
                                                        foreach ($liste_pays as $p) {
                                                            $selected=($p->getAbreviation()==$athlete_adminitre->getNvPaysID()) ? "selected":"";
                                                            echo '<option value="' .$p->getAbreviation(). '"'.$selected.'>' .$p->getNomPays(). '</option>';
                                                        }
                                                        ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="LieuNaissance">Lieu de Naissance : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"  name="LieuNaissance" id="LieuNaissance" value="<?php echo $athlete_adminitre->getLieuNaissance(); ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="Equipementier">Equipementier : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"  name="Equipementier" id="Equipementier" value="<?php echo $athlete_adminitre->getEquipementier(); ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="lien_equip">Lien site équipementier : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"  name="lien_equip" id="lien_equip" value="<?php echo $athlete_adminitre->getLien_site_équipementier(); ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="Instagram">Instagram : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"  name="Instagram" id="Instagram" value="<?php echo $athlete_adminitre->getInstagram(); ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="poids">poids : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"  name="Poids" id="poids" value="<?php echo $athlete_adminitre->getPoids(); ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="taille">taille : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"  name="Taille" id="taille" value="<?php echo $athlete_adminitre->getTaille(); ?>" />
                                                        </td>
                                                    </tr>

                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="Facebook">Facebook : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <input type="text" class="update_athlète_input"  name="Facebook" id="Facebook" value="<?php echo $athlete_adminitre->getFacebook(); ?>" />
                                                        </td>
                                                    </tr>
                                                    <tr class="row">
                                                        <td class="col-md-3" align="left">
                                                            <label for="Bio">Bio : </label>
                                                        </td>
                                                        <td class="col-md-7" align="left">
                                                            <textarea name="Bio" id="Bio" cols="50" rows="20"><?php echo $athlete_adminitre->getBio(); ?></textarea>
                                                        </td>
                                                    </tr>
                                                    
                                                

                                                    <tr class="row">
                                                        <td class="col-md-3" align="left"></td>
                                                        <td class="pull-right">
                                                            <!-- <input type="submit" class="btn_custom" name="sub" value ="Modifier"/> -->
                                                            <button type="submit" name="sub"
                                                                class="btn_custom">Modifier</button>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </form>

                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" id="tab8-2">
                                        <h2>Upload an Image</h2>
                                        <form action="/content/modules/upload-athlete-image.php?championID=<?php echo $athlete_adminitre->getId(); ?>" method="post" enctype="multipart/form-data">
                                            <label for="file">Ajoutez une image:</label>
                                            <input type="file" name="file" id="file" accept="image/*" required>
                                            <br><br>
                                            <input type="submit" name="submit" value="Uploader">
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="tab8-3">
                                        <h2>Upload Video</h2>
                                        <!-- Dans votre template Angular -->
                                        <form id="video-form" action="/content/modules/upload-athlete-video.php?championID=<?php echo $athlete_adminitre->getId(); ?>" method="post" enctype="multipart/form-data">
                                            <div>
                                                <label for="video-url">Entrez l'URL de la vidéo :</label>
                                                <input type="text" id="video-url" name="video-url" placeholder="Entrez l'URL de la vidéo">
                                                <button type="button" id="preview-button">Aperçu</button>
                                            </div>
                                            <div id="preview-container" style="display: none;">
                                                <h3>Aperçu de la vidéo :</h3>
                                                <div id="video-preview"></div>
                                            </div>
                                            <div>
                                                <button type="submit">Uploader la vidéo</button>
                                            </div>
                                        </form>

                                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                        <script>
                                            $(document).ready(function() {
                                                $('#preview-button').on('click', function() {
                                                    var videoUrl = $('#video-url').val();
                                                    if (videoUrl) {
                                                        // Extraire l'ID de la vidéo YouTube à partir de l'URL
                                                        var videoId = videoUrl.split('v=')[1];
                                                        // Vérifier si l'URL a des paramètres supplémentaires
                                                        var ampersandPosition = videoId.indexOf('&');
                                                        if (ampersandPosition != -1) {
                                                            videoId = videoId.substring(0, ampersandPosition);
                                                        }
                                                        // Construire l'URL de l'iframe avec l'ID de la vidéo YouTube
                                                        var iframeUrl = 'https://www.youtube.com/embed/' + videoId;
                                                        // Afficher l'iframe dans la zone d'aperçu
                                                        $('#video-preview').html('<iframe width="560" height="315" src="' + iframeUrl + '" frameborder="0" allowfullscreen></iframe>');
                                                        $('#preview-container').show();
                                                    } else {
                                                        alert('Veuillez entrer une URL de vidéo.');
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                <br>
                                <!--  -->
                                </div>
                            </div>
                            <? }?>
                            <div class="tab-pane fade" id="tab5">
                                <br>
                                <?php 
                            if(isset($_SESSION['update_mdp_msg'])){
                                echo $_SESSION['update_mdp_msg'];
                                unset($_SESSION['update_mdp_msg']);
                            }
                            ?>
                                <form
                                    action="/content/modules/update_profile.php?profilID=<?php echo $profil->getId(); ?>"
                                    id="form_password" method="post">

                                    <table style="font-size: 0.9em;">
                                        <tr class="row">
                                            <td class="col-md-5" align="left">
                                                <label for="password">Mot de passe * : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="password" align="left" class="update_athlète_input"
                                                    data-rule-required="true" required name="password" id="password" />
                                            </td>
                                            <td class="col-md-2"></td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-5" align="left">
                                                <label for="conf_password">Confirmation de mot de passe * : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="password" class="update_athlète_input"
                                                    data-rule-required="true" data-rule-equalto="#password"
                                                    data-msg-equalto="Les mots de passe doivent se correspondre !"
                                                    class="update_athlète_input" required name="conf_password"
                                                    id="conf_password" />
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left"></td>
                                            <td class="pull-right">
                                                <!-- <input type="submit" class="btn_custom" name="sub" value ="Modifier"/> -->
                                                <button type="submit" name="edit_password"
                                                    class="btn_custom">Modifier</button>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                            <div class="tab-pane fade google" id="tab6"><br><br>
                                
                                <?php 
                                    require('../database/connexion.php');
                                    $google_user_request = $bdd->prepare("SELECT * FROM `users` WHERE id = :id");
                                    $google_user_request->bindValue("id", $user_id, PDO::PARAM_INT);

                                    $google_user_request->execute();
                                    $google_user=$google_user_request->fetch(PDO::FETCH_ASSOC);
                                    if($google_user){
                                        $status=($google_user['is_connected']==1)?'<div id="google-connected"></div>':'<div id="google-not-connected"></div>';
                                                echo '<h1 style="text-transform:uppercase">état de votre compte Google'.$status.'</h1>';
                                                echo '<h5><strong>Votre compte google est associé !</strong></h5>
                                                    
                                                    <ul><li><strong>Infos : </strong></li>
                                                    <li><img src="'.$google_user['profile_pic'].'"></li>
                                                    <li><strong>ID:</strong> '.$google_user['id'].'</li>
                                                    <li><strong>Full Name:</strong> '.$google_user['nom'].' '.$google_user['prenom'].'</li>
                                                    <li><strong>Email:</strong> '.$google_user['email'].'</li>
                                                </ul>';
                                            if($google_user['is_connected']==1){
                                                echo '<a href="./logout-google.php"><img src="https://tinyurl.com/46bvrw4s" alt="Google Logo"> Logout</a>';
                                            }else{
                                                echo '
                                                <div class="btn">
                                                    <a href="'.$login_url.'"><img src="https://tinyurl.com/46bvrw4s" alt="Google Logo"> Login with Google</a>
                                                </div>';
                                            }
                                    }else{
                                        echo '<h1 style="text-transform:uppercase">état de votre compte Google</h1>';

                                        echo '<h5><strong>Votre compte google n\'est pas associé !</strong></h5>';
                                        echo '
                                                <div class="btn">
                                                    <a href="'.$login_url.'"><img src="https://tinyurl.com/46bvrw4s" alt="Google Logo"> Associer google</a>
                                                </div>';
                                    }
                                ?>
                                
                            </div>
                            <?php if($user_champ){?>
                                <div class="tab-pane fade" id="tab7">
                                    <br>
                                    <?php if($added_res){?>
                                        <ul>
                                        <?php foreach ($added_res as $key) { 
                                            $status=(!$key["Status"])?"En attente de validation":"Validé";
                                            $rang_termi=($key["Rang"]>1)?" ème" :" er";
                                            echo "<li>".$key["Rang"].$rang_termi." en ".$key["Temps"]." au marathon ".$key["prefixe"]." ".str_replace('\\','',$key["Nom"])." ".strftime("%Y",strtotime($key["DateDebut"]))." (".$key["Sexe"].")."." ".$status." </li>";
                                        }
                                        ?>
                                        </ul>
                                    <?php }?>
                                    <div class="">
                        <?php echo '<h1>Ajouter un résultat</h1>';?>
                        <form action="/content/modules/ajouter-resultat.php" enctype="multipart/form-data" method="post" class="form-horizontal"
                            id="target">
                            <label for="search_event">Rechercher un évenement :</label>
                            <input name="e" id="search_event" type="text">
                            <input type="hidden" name="c" id="c_id" value="<?php echo $user_champ->getID(); ?>" />
                           
                            <input type="hidden" name="u" id="u_id" value="<?php echo  $profil->getUsername(); ?>" />
                            <?php if(!$user_champ->getSexe()){?>
                                <div class="form-group">
                                    <label for="naissance" class="col-sm-5">Sexe * </label>
                                    <div class="col-sm-7">
                                        <input type="radio" name="s"  required  value="M" class="mr-5"/><span class="mr-10">homme</span><input class="mr-5" type="radio" name="s" value="F"  /><span class="mr-10">femme</span>
                                    </div>
                                </div>
                            <?php }else{?>
                                <input type="hidden" name="s"  value="<?php echo $user_champ->getSexe();?>" />
                            <?php }?>
                            <?php if(!$user_champ->getPaysID()){?>
                                <div class="form-group">
                                    <label for="pays" class="col-sm-5">Nationalité *</label>
                                    <div class="col-sm-7">
                                        <select name="p" id="pays" class="form-control"  required>
                                            <?php
                                            foreach ($liste_pays as $p) {
                                                $selected = ($p->getAbreviation()=='FRA') ? "selected" : "";
                                                echo '<option value="'.$p->getAbreviation().'"'.$selected.'>'.$p->getNomPays().'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?php }else{?>
                                <input type="hidden" name="p"  value="<?php echo $user_champ->getPaysID();?>" />
                            <?php } ?>
                           
                            <div class="form-group">
                                <label for="marathon" class="col-sm-5">Rang *</label>
                                <div class="col-sm-7">
                                    <input id="rang" type="number" name="r" value=""  required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="marathon" class="col-sm-5">Temps *</label>
                                <div class="col-sm-7">
                                    <input id="temps" type="time" name="t" step="1"  required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="marathon" class="col-sm-5">Justificatif</label>
                                <div class="col-sm-7">
                                    <input type="file" id="justificatif" name="j" accept="image/png, image/jpeg, application/pdf," required/>
                                </div>
                            </div>
                            <div class="form-group">
                            <div class="col-sm-5">
                                </div>
                                <div class="col-sm-7">
                                <input id="ajouter-resultat"  type="submit" class="call-to-action" value="envoyer">
                                </div>
                            </div>

                        </form>
                    </div>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>

            </div> <!-- End left-side -->

            
        </div>
        <div class="row banniere1 ban ban_768x90 last-ad ">
                    <div class="placeholder-content">
                           <div class="placeholder-title"> Allmarathon </div> 
                           <div class="placeholder-subtitle">publicité</div>
                    </div>
                    <div  class="col-sm-12 ads-contain">
                <?php
                    if($pub768x90 !="") {
                    echo '<a target="_blank" href="'.$pub768x90["url"].'" class="col-sm-12">';
                        echo $pub768x90["code"] ? $pub768x90["code"] :  "<img src=".'../images/pubs/'.$pub768x90['image'] . " alt='' style=\"width: 100%;\" />";
                        echo '</a>';
                    }
                ?>
            </div>
        </div>
    </div> <!-- End container page-content -->

    <?php include_once('footer.inc.php'); ?>

    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/plugins.js"></script>
    <script src="../../js/jquery.jcarousel.min.js"></script>
    <script src="../../js/jquery.sliderPro.min.js"></script>
    <script src="../../js/easing.js"></script>
    <script src="../../js/jquery.ui.totop.min.js"></script>
    <script src="../../js/herbyCookie.min.js"></script>
    <script src="../../js/main.js"></script>
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js">
    </script>



    <script type="text/javascript">
        function slugify(text) {
            // Remplacer les caractères spécifiques
            text = text.replace(/é/g, 'e');
            text = text.replace(/û/g, 'u');

            // Remplacer tous les caractères non alphabétiques ou numériques par des tirets
            text = text.replace(/[^\p{L}\d]+/gu, '-');

            // Supprimer les tirets en début et fin de chaîne
            text = text.trim('-');

            // Convertir en minuscules
            text = text.toLowerCase();

            return text;
        }
        $('#form_password').validate();
        $(document).ready(function() {
            $('#creer_fiche_form_part1').hide();
            var user_champ = <?php echo ($user_champ ? 'true' : 'false'); ?>;
    
            // Check if user_champ is false
            if (!user_champ) {
                console.log("Utilisateur sans fiche", user_champ);
                $('#creer_fiche_form_part2').hide();
            } else {
                console.log("Utilisateur avec fiche", user_champ);
            }
            $('#creer_fiche').on('click',function(e){
                $('#creer_fiche_form_part1').show();
                    
            });
            $('#creer_fiche_form_part1').on('submit', function(event){
                event.preventDefault(); // Empêche l'envoi du formulaire classique

                // Récupérer les valeurs du formulaire
                var nom = $('#nom_form_part1').val();
                var prenom = $('#prenom_form_part1').val();

                // Envoi des données en AJAX
                $.ajax({
                    url: '/content/modules/update_profile_ajax.php',
                    type: 'POST',
                    data: {
                        function: "check_if_exist",
                        nom: nom,
                        prenom: prenom
                    },
                    success: function(response){
                        // Gestion de la réponse du serveur
                        console.log('Résultat: ' + response);
                        // Transformer la réponse JSON en objet
                        var athletes = JSON.parse(response);

                        // Initialiser le formulaire de suggestion
                        var formHtml = (athletes.length>0)?"<h3>Êtes-vous un des athlètes suivants ? </h3><br>":"<h3>Aucun athlète ne correspond </h3><br>";
                        formHtml += "<form id='athleteSelectionForm'>";
                        // Construire les options du formulaire avec les résultats
                        athletes.forEach(function(athlete) {
                            formHtml += "<label>";
                            formHtml += "<input type='radio' name='selectedAthlete' value='" + athlete.ID + "'>";
                            formHtml += athlete.Nom+" (<a href='athlete-"+athlete.ID+"-"+slugify(athlete.Nom)+".html' target='_blank'><b>voir la fiche</b></a>)";
                            formHtml += "</label><br>";
                        });
                        
                        // Ajouter l'option pour ne pas être parmi eux
                        formHtml += "<label>";
                        formHtml += "<input type='radio' name='selectedAthlete' value='none'>";
                        formHtml += "Je ne suis pas référencé sur allmarathon";
                        formHtml += "</label><br>";

                        // Ajouter un bouton de soumission
                        formHtml += "<button type='submit'>Éditer la fiche</button>";
                        formHtml += "</form>";

                        // Insérer le formulaire dans une div (par exemple)
                        $('#result-suggestion').html(formHtml);

                        // Ajouter un gestionnaire d'événements pour le formulaire de sélection
                        $('#athleteSelectionForm').on('submit', function(event){
                            event.preventDefault();

                            // Récupérer les athlètes sélectionnés
                            var selectedAthlete = $("input[name='selectedAthlete']:checked").val();

                            // Traiter la sélection (redirection, affichage de message, etc.)
                            if (selectedAthlete === "none") {
                                console.log("Vous avez indiqué que vous n'êtes pas parmi les athlètes proposés.");
                            } else {
                                console.log("Vous avez sélectionné l'athlète avec l'ID: " + selectedAthlete);
                            }

                            // Ici, vous pouvez également effectuer d'autres actions comme rediriger l'utilisateur
                            // ou envoyer les données au serveur pour un traitement supplémentaire.
                            
                            $.ajax({
                                url: '/content/modules/update_profile_ajax.php',
                                type: 'POST',
                                data: {
                                    function: "get_athlete_datas",
                                    selected_id: selectedAthlete,
                                    user_id:<?php echo $user_id;?>
                                },
                                success: function(response){
                                    // Gestion de la réponse du serveur
                                    console.log('Résultat: ' + response);
                                    //$('#creer_fiche_form_part').hide();
                                    //$('#result-suggestion').hide();
                                    $('#creer_fiche_form_part2').show();
                                    if (selectedAthlete === "none") {
                                        var athlete = JSON.parse(response);
                                        $('#nom_form_part2').val(athlete.Nom)
                                        $('#prenom_form_part2').val(athlete.Nom)
                                        $('#date_de_naissance_form_part2').val(athlete.DateNaissance)
                                        $('#LieuNaissance_form_part2').val(athlete.LieuNaissance)
                                        $('#Equipementier_form_part2').val(athlete.DateNaissance)
                                        $('#pays_form_part2').val(athlete.PaysID);
                                        $('#lien_equip_form_part2').val(athlete["Lien_site_\u00e9quipementier"])
                                        $('#Instagram_form_part2').val(athlete.Instagram)
                                        $('#poids_form_part2').val(athlete.Poids)
                                        $('#taille_form_part2').val(athlete.Taille)
                                        $('#Facebook_form_part2').val(athlete.Facebook)
                                        $('#Bio_form_part2').val(athlete.Bio)
                                    } else {
                                        var athlete = JSON.parse(response);
                                        $('#nom_form_part2').val(athlete.Nom)
                                        $('#prenom_form_part2').val(athlete.Nom)
                                        $('#date_de_naissance_form_part2').val(athlete.DateNaissance)
                                        $('#LieuNaissance_form_part2').val(athlete.LieuNaissance)
                                        $('#Equipementier_form_part2').val(athlete.DateNaissance)
                                        $('#pays_form_part2').val(athlete.PaysID);
                                        $('#lien_equip_form_part2').val(athlete["Lien_site_\u00e9quipementier"])
                                        $('#Instagram_form_part2').val(athlete.Instagram)
                                        $('#poids_form_part2').val(athlete.Poids)
                                        $('#taille_form_part2').val(athlete.Taille)
                                        $('#Facebook_form_part2').val(athlete.Facebook)
                                        $('#Bio_form_part2').val(athlete.Bio)
                                    }

                                },
                                error: function(xhr, status, error){
                                    // Gestion des erreurs
                                    console.log('Statut de la requête:', status);
                                    console.log('Erreur:', error);
                                    console.log('Code de statut HTTP:', xhr.status);
                                    console.log('Texte du statut:', xhr.statusText);
                                    console.log('Réponse du serveur:', xhr.responseText);
                                }
                            });
                        });

                    },
                    error: function(xhr, status, error){
                        // Gestion des erreurs
                        console.log('Erreur: ' + error);
                    }
                });
            });

            
            $("#search_event").autocomplete({
    source: function(request, response) {
        if(request.term.length >= 5) { // Se déclenche après 5 caractères
            $.ajax({
                url: "/content/modules/update_profile_ajax.php", // URL de votre script PHP
                type: "GET",
                dataType: "json",
                data: {
                    search_event: request.term
                },
                success: function(data) {

                    console.log(data)
                    response($.map(data, function(item) {
                        return {
                            label: "marathon "+item.prefixe+" "+item.Nom+" "+item.annee, // Assurez-vous que la clé 'Nom' correspond bien à la réponse du PHP
                            value: item.ID, // Rempli dans le champ de saisie
                            id: item.ID // Optionnel, pour stocker des valeurs supplémentaires
                        };
                    }));
                },
                error: function(xhr, status, error) {
                    console.log('Statut de la requête:', status);
                                    console.log('Erreur:', error);
                                    console.log('Code de statut HTTP:', xhr.status);
                                    console.log('Texte du statut:', xhr.statusText);
                                    console.log('Réponse du serveur:', xhr.responseText);
                }
            });
        }
    },
    minLength: 5 // Nombre minimum de caractères avant de déclencher l'auto-complétion
});


                console.log("script creer fiche chargé")

        })
       
    </script>

</body>

</html>
<style type="text/css">
label.error {
    color: red;
}
</style>