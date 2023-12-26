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




$evCategorieEvenement=new evCategorieEvenement();




$evenement=new evenement();





$champion_admin_externe_journal=new champion_admin_externe_journal();
$journal=$champion_admin_externe_journal->getJournalByUser($user_id)['donnees'];

$user=new user();
$profil =$user->getUserById($user_id)['donnees'];



$champion=new champion();
$user_champ=$champion->getUserChampion($profil->getNom(),$profil->getPrenom(),$profil->getDate_naissance(),$profil->getPays())['donnees'];
$pays=new pays();
$liste_pays=$pays->getAll()['donnees'];



$champAdminExterne=new championAdminExterne();
$athlete_adminitres=$champAdminExterne->getChampionsByUser($user_id)['donnees'];

$comments = new commentaire();
$commentsByUser=$comments->getCommentairesByUser($user_id)['donnees'];



$pub=new pub();
$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];

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
        <div class="row banniere1">
            <a href="" class="col-sm-12"><?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side resultat-detail">

                <div class="row">
                    <div class="col-sm-12">
                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#tab1" role="tab" data-toggle="tab">Profil</a></li>
                            <li><a href="#tab2" role="tab" data-toggle="tab">Vos actions</a></li>
                         
                            <li><a href="#tab4" role="tab" data-toggle="tab">Modifier vos infos</a></li>
                            <li><a href="#tab5" role="tab" data-toggle="tab">Modifier votre mot de passe</a></li>
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

                        if($profil->getPays() != ""){
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
                                       case 'champion':
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
                                    case 'commentaire' :
                                        echo '<li><img src="/images/CSS/commentaire.png" alt="" style="margin-left:3px;margin-right:3px;" align="left" /> '.$profil->getUsername().' a commenté la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" class="link_customized">'.$champ->getNom().'</a> ( '.depuis($j->getDate()).' )  </li>';
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
                                <ul>
                                    <?php 
                            if(isset($_SESSION['update_profile_msg'])){
                                echo $_SESSION['update_profile_msg'];
                                unset($_SESSION['update_profile_msg']);
                            }
                            ?>

                                    <form
                                        action="/content/modules/update_profile.php?profilID=<?php echo $profil->getId(); ?>"
                                        method="post">

                                        <table style="font-size: 0.9em;">
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="nom">Nom * : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" align="left" class="update_athlète_input" required
                                                        name="nom" id="nom" value="<?php echo $profil->getNom(); ?>" />
                                                </td>
                                                <td class="col-md-2"></td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="Sexe">Sexe : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="radio" name="Sexe" value="M" <?php if($user_champ->getSexe()=="M") echo 'checked="checked"';?> />
                                                    <span>homme</span>
                                                    <input type="radio" name="Sexe" value="F" <?php if($user_champ->getSexe()=="F") echo 'checked="checked"';?> />
                                                    <span >femme</span>
                                                </td>
                                            </tr>

                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="prenom">Prénom * : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" class="update_athlète_input"
                                                        class="update_athlète_input" required name="prenom" id="prenom"
                                                        value="<?php echo $profil->getPrenom(); ?>" />
                                                </td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="email">E-mail * : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" class="update_athlète_input" name="email"
                                                        id="email" required
                                                        value="<?php echo $profil->getEmail(); ?>" />
                                                </td>
                                            </tr>
                                            <input type="hidden" name="c_id" id="c_id" value="<?php echo $user_champ->getID(); ?>" />

                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="date_de_naissance">Date de naissance : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" class="update_athlète_input"
                                                        id="date_de_naissance" name="date_de_naissance"
                                                        value="<?php echo $profil->getDate_naissance(); ?>" />
                                                </td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="pays">Pays : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <select name="pays" class="update_athlète_input" id="pays">
                                                        <?php
                                                foreach ($liste_pays as $p) {
                                                    $selected=($p->getAbreviation()==$profil->getPays()) ? "selected":"";
                                                    echo '<option value="' .$p->getID(). '"'.$selected.'>' .$p->getNomPays(). '</option>';
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
                                                    <input type="text" class="update_athlète_input"  name="LieuNaissance" id="LieuNaissance" value="<?php echo $user_champ->getLieuNaissance(); ?>" />
                                                </td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="Equipementier">Equipementier : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" class="update_athlète_input"  name="Equipementier" id="Equipementier" value="<?php echo $user_champ->getEquipementier(); ?>" />
                                                </td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="lien_equip">Lien site équipementier : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" class="update_athlète_input"  name="lien_equip" id="lien_equip" value="<?php echo $user_champ->getLien_site_équipementier(); ?>" />
                                                </td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="Instagram">Instagram : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" class="update_athlète_input"  name="Instagram" id="Instagram" value="<?php echo $user_champ->getInstagram(); ?>" />
                                                </td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="poids">poids : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" class="update_athlète_input"  name="poids" id="poids" value="<?php echo $user_champ->getPoids(); ?>" />
                                                </td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="taille">taille : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" class="update_athlète_input"  name="taille" id="taille" value="<?php echo $user_champ->getTaille(); ?>" />
                                                </td>
                                            </tr>

                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="Facebook">Facebook : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <input type="text" class="update_athlète_input"  name="Facebook" id="Facebook" value="<?php echo $user_champ->getFacebook(); ?>" />
                                                </td>
                                            </tr>
                                            <tr class="row">
                                                <td class="col-md-3" align="left">
                                                    <label for="Bio">Bio : </label>
                                                </td>
                                                <td class="col-md-7" align="left">
                                                    <textarea name="Bio" id="Bio" cols="50" rows="20"><?php echo $user_champ->getBio(); ?></textarea>
                                                </td>
                                            </tr>
                                            
                                            <tr class="row">
                                                <td class="col-md-3" align="right">
                                                    <input type="checkbox" name="newsletter" value="1"
                                                        <?php if ($profil->getNewsletter()==1): ?>checked<?php endif ?>>
                                                </td>
                                                <td class="col-md-7">j'accepte de recevoir la newsletter hebdomadaire de
                                                    allmarathon</td>
                                            </tr>

                                            <tr class="row">
                                                <td class="col-md-3" align="right">
                                                    <input type="checkbox" name="offres" value="1"
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
                                    $google_user_request = $bdd->prepare("SELECT * FROM `users_google` WHERE `user_id` = :id");
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
                                                    <li><strong>Full Name:</strong> '.$google_user['first_name'].' '.$google_user['last_name'].'</li>
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
                        </div>
                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <div class="marg_bot"></div>
                <p class="ban"><a href=""><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                
                <?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p> 
                <div class="marg_bot"></div>
               
                </dd>
                <div class="marg_bot"></div>


            </aside>
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
    $.datepicker.setDefaults($.datepicker.regional['fr']);
    $("#date_de_naissance").datepicker();
    $('#date_de_naissance').datepicker('option', {
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
            'Octobre', 'Novembre', 'Décembre'
        ],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.',
            'Nov.', 'Déc.'
        ],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        dateFormat: 'yy-mm-dd'
    });

    $("#date_de_naissance").datepicker("setDate", "<?php echo $profil->getDate_naissance(); ?>");
    </script>

    <script type="text/javascript">
    $('#form_password').validate();
    </script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
    /*
     (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
     function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
     e=o.createElement(i);r=o.getElementsByTagName(i)[0];
     e.src='https://www.google-analytics.com/analytics.js';
     r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
     ga('create','UA-XXXXX-X','auto');ga('send','pageview');
     */
    </script>
    <!--FaceBook-->
    <script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.5";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>
    <!--Google+-->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
</body>

</html>
<style type="text/css">
label.error {
    color: red;
}
</style>