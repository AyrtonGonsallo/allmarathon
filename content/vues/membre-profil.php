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

    $current_tab=isset($_GET["tab"])?$_GET["tab"]:"";


include("../classes/pub.php");
include("../classes/user.php");
include("../classes/championAdminExterneClass.php");
include("../classes/pays.php");
include("../classes/champion.php");
include("../classes/video.php");
include("../classes/champion_admin_externe_journal.php");
include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/partenaire.php");
include("../classes/commentaire.php");

$video=new video();
$pub=new pub();

$pub728x90=$pub->getBanniere728_90("accueil")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$evCategorieEvenement=new evCategorieEvenement();
$evenement=new evenement();
$champion_admin_externe_journal=new champion_admin_externe_journal();
$journal=$champion_admin_externe_journal->getJournalByUser($user_id)['donnees'];
$user=new user();
$profil =$user->getUserById($user_id)['donnees'];
$added_res = $user->getAddedResults($user_id)['donnees'];
$champion=new champion();
$user_champs=$champion->getUserChampions($user_id)['donnees'];
$pays=new pays();
$liste_pays=$pays->getAll()['donnees'];
$champAdminExterne=new championAdminExterne();
$athlete_adminitres=$champion->getUserChampions($user_id)['donnees'];
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
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
     
        
        <div class="row">
            <div class="col-sm-12 left-side resultat-detail">

                <div class="row">
                    <div class="col-sm-12">
                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li <?php if($current_tab!="res" && $current_tab!="adm_fiche"){ echo  "class='active'";} ?>><a href="#tab1" role="tab" data-toggle="tab">Profil</a></li>
                            
                                <li <?php if($current_tab=="adm_fiche"){ echo  "class='active'";} ?>><a href="#tab4" role="tab" data-toggle="tab">Athlètes administrés</a></li>
                           
                            <?php if($user_champs){?>
                                <li <?php if($current_tab=="res"){ echo  "class='active'";} ?>><a href="#tab7"  role="tab" data-toggle="tab">Ajout résultats</a></li>
                            <? }?>
                            <li><a href="#tab2" role="tab" data-toggle="tab">Historique</a></li>
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content technique-detail">
                            <div <?php if($current_tab!="res" && $current_tab!="adm_fiche"){ echo  "class='active tab-pane fade in'";}else{ echo "class='tab-pane fade'";} ?> id="tab1">
                                <div class="row">
                                    <div class="col-sm-8 form-prof-adm">
                                    <br><br>
                                    <?php 
                                        if(isset($_SESSION['update_profile_msg'])){
                                            echo $_SESSION['update_profile_msg'];
                                            unset($_SESSION['update_profile_msg']);
                                        }
                                        ?>
                                    <form  action="/content/modules/update_simple_profile.php?profilID=<?php echo $profil->getId(); ?>"
                                    method="post">
                                        <label for="nom_form_part2">Nom :</label>
                                        <input type="text" name="nom_form_part2" required value="<?php echo $profil->getNom(); ?>"><br>

                                        <label for="prenom_form_part2">Prénom  :</label>
                                        <input type="text" name="prenom_form_part2" required value="<?php echo $profil->getPrenom(); ?>"><br>

                                        <label for="username_form_part2">Nom  d'utilisateur :</label>
                                        <input type="text" name="username_form_part2" required value="<?php echo $profil->getUsername(); ?>"><br>

                                        <label for="email_form_part2">Email  :</label>
                                        <input type="email" name="email_form_part2" required value="<?php echo $profil->getEmail(); ?>"><br>

                                        <label for="date_de_naissance_form_part2">Date de naissance :</label>
                                        <input type="date" name="date_de_naissance_form_part2" value="<?php echo $profil->getDate_naissance(); ?>"><br>

                                        <label for="code_postale_form_part2">Code postal :</label>
                                        <input type="text" name="code_postale_form_part2"value="<?php echo $profil->getCode_postale(); ?>"><br>

                                        <label for="ville_form_part2">Ville  :</label>
                                        <input type="text" name="ville_form_part2" value="<?php echo $profil->getVille(); ?>"><br>

                                        <label for="pays_form_part2">Pays  :</label>
                                        <select name="pays_form_part2" class="update_athlète_input" id="pays_form_part2" >
                                        <option value="">NULL</option>
                                            <?php
                                            foreach ($liste_pays as $p) {
                                                $selected=($p->getAbreviation()==$profil->getPays()) ? "selected":"";
                                                echo '<option value="' . $p->getAbreviation() . '" '.$selected.'>' . $p->getNomPays() . '</option>';
                                            }
                                            ?>
                                        </select><br>

                                        <label for="newsletter_form_part2">S'inscrire à la newsletter :</label>
                                        <input type="checkbox" name="newsletter_form_part2" value="1"
                                                        <?php if ($profil->getNewsletter()==1): ?>checked<?php endif ?>><br>

                                        <label for="offres_form_part2">Recevoir des offres :</label>
                                        <input type="checkbox" name="offres_form_part2" value="1"
                                        <?php if ($profil->getOffres()==1): ?>checked<?php endif ?>><br>

                                        <input type="submit" value="Modifier">
                                    </form>

                                        <?php 
                                            require('../database/connexion.php');
                                            $google_user_request = $bdd->prepare("SELECT * FROM `users` WHERE id = :id");
                                            $google_user_request->bindValue("id", $user_id, PDO::PARAM_INT);

                                            $google_user_request->execute();
                                            $google_user=$google_user_request->fetch(PDO::FETCH_ASSOC);
                                            if($google_user){
                                                echo 'Votre compte est associé au compte google '.$google_user['email'].'<img style="border-radius:20px;margin: 0px 0 0 7px;position: relative;top: 7px;" width="30" src="'.$google_user['profile_pic'].'">'; 
                                            }
                                        ?>
                                        <br>
                                        <br>
                                        <br>
                                        <?php 
                                        if(isset($_SESSION['update_mdp_msg'])){
                                            echo $_SESSION['update_mdp_msg'];
                                            unset($_SESSION['update_mdp_msg']);
                                        }
                                        ?>
                                        <h3>Modifier mes identifiants</h3>
                                        <form class="form-mdp"
                                            action="/content/modules/update_profile.php?profilID=<?php echo $profil->getId(); ?>"
                                            id="form_password" method="post">

                                            <table style="font-size: 0.9em; width: 100% !important; max-width: 100% !important;">
                                                <tr class="row">
                                                    <td class="col-md-5" align="left">
                                                        <label class="max-width: 100%;" for="password">Mot de passe * : </label>
                                                    </td>
                                                    <td class="col-md-7" align="left">
                                                        <input class="max-width: 100%;" type="password" align="left" class="update_athlète_input"
                                                            data-rule-required="true" required name="password" id="password" />
                                                    </td>
                                                    <!--<td class="col-md-2"></td> -->
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
                                                   <!-- <td class="col-md-3" align="left"></td> -->
                                                    <td class="pull-right">
                                                        <!-- <input type="submit" class="btn_custom" name="sub" value ="Modifier"/> -->
                                                        <button type="submit" name="edit_password"
                                                            class="btn_custom"style="width:fit-content;">Modifier</button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab2">
                                <br>
                                <ul class="videos-tab">
                                    <?php 
                                 

                         if(sizeof($journal)!=0)
                            foreach ($journal as $j) {

                                $champ=$champion->getChampionById($j->getChampion_id())['donnees'];
                              
                                switch ($j->getType()){
                                    case 'new_admin':
                                        if($champ)
                                        {
                                            echo '
                                            <li><span class="material-symbols-outlined">admin_panel_settings</span> '.$profil->getUsername().' administre la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" target="_blank" class="link_customized">'.$champ->getNom().'</a>
                                            ( '.depuis($j->getDate()).' ) </li>';
                                        }
                                       break;
                                    case 'ajout':
                                        if($champ)
                                        {
                                            echo '
                                            <li> <span class="material-symbols-outlined">add_circle</span>'.$profil->getUsername().' à créé la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" target="_blank" class="link_customized">'.$champ->getNom().'</a>
                                            ( '.depuis($j->getDate()).' ) </li>';
                                        }
                                       break;
                                    case 'modification':
                                        if($champ){ 
                                            echo '<li><span class="material-symbols-outlined">edit_note</span> La fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" target="_blank" class="link_customized">'.$champ->getNom().'</a> a été mise à jour ( '.depuis($j->getDate()).' ) </li> ';
                                        }
                                        break;
                                    case 'revendication':
                                        if($champ){ 
                                            echo '<li><span class="material-symbols-outlined">draw</span> Vous avez revendiqué la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" target="_blank" class="link_customized">'.$champ->getNom().'</a>  ( '.depuis($j->getDate()).' ) </li> ';
                                        }
                                        break;
                                    case 'revendication-ok':
                                        if($champ){ 
                                            echo '<li><span class="material-symbols-outlined">done_outline</span> Votre demande de revendication sur la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" target="_blank" class="link_customized">'.$champ->getNom().'</a> a été acceptée  ( '.depuis($j->getDate()).' ) </li> ';
                                        }
                                        break;
                                
                                    case 'modification' :
                                        echo '<li> '.$profil->getUsername().' a modifié la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" target="_blank" class="link_customized">'.$champ->getNom().'</a> ( '.depuis($j->getDate()).' )  </li>';
                                        break;
                                    case 'upload-image' :
                                        echo '<li> <span class="material-symbols-outlined">image</span>'.$profil->getUsername().' a ajouté une photo à la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" target="_blank" class="link_customized">'.$champ->getNom().'</a> ( '.depuis($j->getDate()).' )  </li>';
                                        break;
                                    case 'upload-video' :
                                        echo '<li><span class="material-symbols-outlined">play_circle</span> '.$profil->getUsername().' a ajouté une vidéo à la fiche de <a href="/athlete-'.$champ->getID().'-'.slugify($champ->getNom()).'.html" target="_blank" class="link_customized">'.$champ->getNom().'</a> ( '.depuis($j->getDate()).' )  </li>';
                                        break;
                                    default:
                                        continue 2;
                                }

                            }
                          else {
                            echo "";
                          }  
                            ?>
                                </ul>
                            </div>
                            
                            <div <?php if($current_tab=="adm_fiche"){ echo  "class='active tab-pane fade in'";}else{echo "class='tab-pane fade'";} ?> id="tab4" >
                                <br>
                                <?php 
                                            if(isset($_SESSION['add_champ_msg'])){
                                                echo $_SESSION['add_champ_msg'];
                                                unset($_SESSION['add_champ_msg']);
                                            }
                                        ?><br>
                                    <a  id="creer_fiche" href="#"  class="call-to-action mx-auto">
                                        Je souhaite créer une fiche athlète
                                    </a>
                                    <div id="creer_fiche_form_part1">
                                        
                                        <form class="form-infos" style="border: 1px solid #ddd;padding: 15px;margin-bottom: 43px;" action="/content/modules/add_or_update_champion.php?task=add" method="post">
                                            <label for="nom_form_part1">Nom </label>
                                            <input type="text" id="nom" name="nom_form_part1" required><br><br>
                                            <label for="prenom_form_part1">Prénom </label>
                                            <input type="text" id="prenom" name="prenom_form_part1" required><br><br>

                                            <label for="sexe_form_part1">Sexe </label>
                                            <select id="sexe" name="sexe_form_part1" required>
                                                <option value="M">Masculin</option>
                                                <option value="F">Féminin</option>
                                            </select><br><br>

                                            <label for="pays_form_part1">Pays</label>
                                            <select name="pays_form_part1" class="update_athlète_input" id="pays_form_part1" required>
                                                <option value="">NULL</option>
                                               <?php
                                                foreach ($liste_pays as $p) {
                                                    echo '<option value="' . $p->getAbreviation() . '">' . $p->getNomPays() . '</option>';
                                                }
                                                ?><br><br>
                                            </select><br><br>

                                            <label for="dateNaissance_form_part1">Date de Naissance </label>
                                            <input type="date" id="dateNaissance" name="dateNaissance_form_part1" >
                                            <br><br>

                                            <label for="lieuNaissance_form_part1">Lieu de Naissance </label>
                                            <input type="text" id="lieuNaissance" name="lieuNaissance_form_part1" ><br><br>

                                            <label for="taille_form_part1">Taille (cm) </label>
                                            <input type="number" id="taille" name="taille_form_part1" min="1" max="250"><br><br>

                                            <label for="poids_form_part1">Poids (kg) </label>
                                            <input type="number" id="poids" name="poids_form_part1" min="1" max="200"><br><br>

                                            <label for="site_form_part1">Site </label>
                                            <input type="url" id="site" name="site_form_part1" placeholder="http://example.com"><br><br>

                                            <label for="facebook_form_part1">Facebook </label>
                                            <input type="url" id="facebook" name="facebook_form_part1" placeholder="http://facebook.com"><br><br>

                                            <label for="instagram_form_part1">Instagram </label>
                                            <input type="url" id="instagram" name="instagram_form_part1" placeholder="http://instagram.com"><br><br>


                                            <input type="submit" value="Ajouter Champion">
                                            <input id="annuler_creation" class="btn-cancel" type="button" value="Annuler">
                                        </form>
                                    </div>

                                    <?php echo ($athlete_adminitres)?"<h2 class='mt-5'>Liste des athlètes que vous administrez (".sizeof($athlete_adminitres).")</h2>":"<h2>Vous n'administrez aucune fiche</h2>";?>
                                    <br>
                                        <?php 
                                            if(isset($_SESSION['update_adm_champ_msg'])){
                                                echo $_SESSION['update_adm_champ_msg'];
                                                unset($_SESSION['update_adm_champ_msg']);
                                            }
                                        ?>
                                        
                                    <?php if ($athlete_adminitres) { ?>
                                        <ul>
                                            <?php foreach ($athlete_adminitres as $index => $athlete_adminitre) { 
                                                // Create a unique ID for each accordion based on the index
                                                $accordionId = "accordion_" . $index;
                                                $headingId = "heading_" . $index;
                                                $collapseId = "collapse_" . $index;
                                                $videos = $video->getVideosByChamp($athlete_adminitre->getID())['donnees'];
                                                $photos = $champion->getChampionsPhotos($athlete_adminitre->getID())['donnees'];
                                                ?>
                                                
                                                <li>
                                                    
                                                    <div class="panel-group" id="<?php echo $accordionId; ?>" role="tablist" aria-multiselectable="true">
                                                        <div class="panel panel-default">
                                                            <div class="panel-heading" role="tab" id="<?php echo $headingId; ?>">
                                                                <h4 class="panel-title">
                                                                    <?php echo $athlete_adminitre->getNom(); ?>
                                                                    <a role="button" data-toggle="collapse" href="#<?php echo $collapseId; ?>" aria-expanded="false" aria-controls="<?php echo $collapseId; ?>">
                                                                        <span class="material-symbols-outlined accordion-toogle" >
                                                                            expand_all
                                                                        </span>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="<?php echo $collapseId; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?php echo $headingId; ?>">
                                                                <div class="panel-body">

                                                                
                                                                    <!-- Nav tabs -->
                                                                    <ul class="nav nav-tabs" role="tablist">
                                                                        <li role="presentation" class="active"><a href="#infos_<?php echo $collapseId; ?>" aria-controls="infos_<?php echo $collapseId; ?>" role="tab" data-toggle="tab">Infos générales</a></li>
                                                                        <li role="presentation"><a href="#photos_<?php echo $collapseId; ?>" aria-controls="photos_<?php echo $collapseId; ?>" role="tab" data-toggle="tab">Photos <?php echo (sizeof($photos)>0)?(' ('.count($photos).')'):'';?></a></li>
                                                                        <li role="presentation"><a href="#videos_<?php echo $collapseId; ?>" aria-controls="videos_<?php echo $collapseId; ?>" role="tab" data-toggle="tab">Vidéos <?php echo (sizeof($videos)>0)?(' ('.count($videos).')'):'';?></a></li>
                                                                    </ul>

                                                                    <!-- Tab panes -->
                                                                    <div class="tab-content">
                                                                        <div role="tabpanel" class="tab-pane active" id="infos_<?php echo $collapseId; ?>">
                                                                            <div>
                                                                                <h2>Modifier les infos de <a href="athlete-<?= $athlete_adminitre->getID();?>.html" target="_blank"><?php echo htmlspecialchars($athlete_adminitre->getNom()); ?></a></h2><br>
                                                                                <form class="form-infos" action="/content/modules/add_or_update_champion.php?task=update&cid=<?= $athlete_adminitre->getID(); ?>" method="post">
                                                                                    <label for="nom">Nom </label>
                                                                                    <input type="text" id="nom_<?= $athlete_adminitre->getID();?>" name="nom_form_part1" value="<?= htmlspecialchars($athlete_adminitre->getNom()); ?>" required><br><br>

                                                                                    <label for="sexe">Sexe </label>
                                                                                    <select id="sexe_<?= $athlete_adminitre->getID();?>" name="sexe_form_part1" required>
                                                                                        <option value="M" <?= $athlete_adminitre->getSexe() == 'M' ? 'selected' : '' ?>>Masculin</option>
                                                                                        <option value="F" <?= $athlete_adminitre->getSexe() == 'F' ? 'selected' : '' ?>>Féminin</option>
                                                                                    </select><br><br>

                                                                                    <label for="pays_form_part1">Pays</label>
                                                                                    <select name="pays_form_part1" class="update_athlete_input" id="pays_<?= $athlete_adminitre->getID();?>" required>
                                                                                        <?php foreach ($liste_pays as $p): ?>
                                                                                            <option value="<?= htmlspecialchars($p->getAbreviation()); ?>" <?= $athlete_adminitre->getPaysID() == $p->getAbreviation() ? 'selected' : '' ?>>
                                                                                                <?= htmlspecialchars($p->getNomPays()); ?>
                                                                                            </option>
                                                                                        <?php endforeach; ?>
                                                                                    </select><br>

                                                                                    <label for="dateNaissance">Date de Naissance </label>
                                                                                    <input type="date" id="dateNaissance_<?= $athlete_adminitre->getID();?>" name="dateNaissance_form_part1" value="<?= $athlete_adminitre->getDateNaissance(); ?>" ><br><br>

                                                                                    <label for="lieuNaissance">Lieu de Naissance </label>
                                                                                    <input type="text" id="lieuNaissance_<?= $athlete_adminitre->getID();?>" name="lieuNaissance_form_part1" value="<?= htmlspecialchars($athlete_adminitre->getLieuNaissance()); ?>"><br><br>

                                                                                    <label for="taille">Taille (cm) </label>
                                                                                    <input type="number" id="taille_<?= $athlete_adminitre->getID();?>" name="taille_form_part1" value="<?= $athlete_adminitre->getTaille(); ?>" min="0" max="250"><br><br>

                                                                                    <label for="poids">Poids (kg) </label>
                                                                                    <input type="number" id="poids_<?= $athlete_adminitre->getID();?>" name="poids_form_part1" value="<?= $athlete_adminitre->getPoids(); ?>" min="0" max="300"><br><br>

                                                                                    <label for="site">Site </label>
                                                                                    <input type="url" id="site_<?= $athlete_adminitre->getID();?>" name="site_form_part1" value="<?= htmlspecialchars($athlete_adminitre->getSite()); ?>" placeholder="http://example.com"><br><br>

                                                                                    <label for="facebook_form_part1">Facebook </label>
                                                                                    <input type="url" id="facebook_<?= $athlete_adminitre->getID();?>" name="facebook_form_part1" value="<?= htmlspecialchars($athlete_adminitre->getFacebook()); ?>" placeholder="http://facebook.com"><br><br>

                                                                                    <label for="instagram_form_part1">Instagram </label>
                                                                                    <input type="url" id="instagram_<?= $athlete_adminitre->getID();?>" name="instagram_form_part1" value="<?= htmlspecialchars($athlete_adminitre->getInstagram()); ?>" placeholder="http://instagram.com"><br><br>

                                                                                    <!-- Other fields follow the same structure -->

                                                                                    <input type="submit" value="Modifier Champion">
                                                                                </form>
                                                                            </div>
                                                                        </div>

                                                                        <div role="tabpanel" class="tab-pane" id="photos_<?php echo $collapseId; ?>">
                                                                            <div>
                                                                                <h2>Ajouter une photo à <a href="athlete-<?= $athlete_adminitre->getID();?>.html" target="_blank"><?php echo htmlspecialchars($athlete_adminitre->getNom()); ?></a></h2>
                                                                                <form action="/content/modules/upload-athlete-image.php?championID=<?= $athlete_adminitre->getId(); ?>" method="post" enctype="multipart/form-data">
                                                                                    <label for="file">Ajoutez une image :</label><br>
                                                                                    <input type="file" name="file" id="file_<?= $athlete_adminitre->getID();?>" accept="image/*" required><br>
                                                                                    <input type="submit" name="submit" value="Uploader">
                                                                                </form>
                                                                                

                                                                                <?php if (!empty($photos)): ?>
                                                                                    <h2>Toutes les photos</h2>
                                                                                    <ul class="photos-tab">
                                                                                        <?php foreach ($photos as $photo): ?>
                                                                                            <li>
                                                                                                <a href="/images/galeries/<?= $photo['Galerie_id']; ?>/<?= htmlspecialchars($photo['Nom']); ?>" class="fancybox" rel="group">
                                                                                                    <img src="/images/galeries/<?= $photo['Galerie_id']; ?>/<?= htmlspecialchars($photo['Nom']); ?>" width="116" height="auto" alt="">
                                                                                                </a>
                                                                                            </li>
                                                                                        <?php endforeach; ?>
                                                                                    </ul>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>

                                                                        <div role="tabpanel" class="tab-pane" id="videos_<?php echo $collapseId; ?>">
                                                                            <div>
                                                                                <h2>Ajouter une vidéo à <a href="athlete-<?= $athlete_adminitre->getID();?>.html" target="_blank"><?php echo htmlspecialchars($athlete_adminitre->getNom()); ?></a></h2>
                                                                                <form id="video-form_<?= $athlete_adminitre->getID();?>" action="/content/modules/upload-athlete-video.php?championID=<?= $athlete_adminitre->getId(); ?>" method="post" enctype="multipart/form-data">
                                                                                    <label for="video-url">Entrez l'URL de la vidéo :</label>
                                                                                    <input type="text" id="video-url_<?= $athlete_adminitre->getID();?>" name="video-url" placeholder="Entrez l'URL de la vidéo">
                                                         <br>                           <button style="margin-right:10px!important;" type="button" id="preview-button_<?= $athlete_adminitre->getID();?>">Aperçu</button>

                                                                                    <div id="preview-container_<?= $athlete_adminitre->getID();?>" style="display: none;">
                                                                                        <h3>Aperçu de la vidéo :</h3>
                                                                                        <div id="video-preview_<?= $athlete_adminitre->getID();?>"></div>
                                                                                    </div>

                                                                                    <button type="submit">Uploader la vidéo</button>
                                                                                </form>
                                                                                <br><br>
                                                                                <?php if(sizeof($videos)!=0){
                                                                                        echo '<h2>Toutes les vidéos</h2>';
                                                                                        echo '<ul class="profil-videos-tab">';
                                                                                        foreach ($videos as $key => $vd) {
                                                                                            
                                                                                            $event_intitule="";
                                                                                            if($vd->getEvenement_id()!=0){
                                                                                            $annee_event=substr($event->getEvenementByID($vd->getEvenement_id())['donnees']->getDateDebut(),0,4);
                                                                                            $video_intitule=$event->getEvenementByID($vd->getEvenement_id())['donnees']->getNom()." ".$annee_event;
                                                                                            $event_intitule="<li><a href='/resultats-marathon-".$vd->getEvenement_id()."-".slugify($video_intitule).".html' class='video_event'>".$video_intitule."</a></li>";
                                                                                            }
                                                                                            
                                                                                            $res_event="";
                                                                                            if($vd->getEvenement_id()){
                                                                                                $evenement=$event->getEvenementByID($vd->getEvenement_id())["donnees"];
                                                                                                $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                                                                        
                                                                                                $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                                                                                $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                                                        
                                                                                                $res_event= "<a href='/resultats-marathon-".$evenement->getId()."-".slugify($nom_res_lien).".html' class='home-link mr-5 disp-flex'><span class='material-symbols-outlined'>trophy</span> Résultats </a>";
                                                                                                
                                                                                            }
                                                                                            $url_img1=str_replace("hqdefault","0",$vd->getVignette());
                                                                                            $url_img=str_replace("default","0",$url_img1);
                                                                                            
                                                                                            echo '<div class=" profil-video-grid-tab">
                                                                                                <div class="mr-5"><a href="video-de-marathon-'.$vd->getId().'.html"><div class="video-thumbnail" style="background-image: url('.$url_img.'"></div></a></div>
                                                                                                <div >
                                                                                                    <ul class="profil-video-datas">
                                                                                                        <li><a href="video-de-marathon-'.$vd->getId().'.html" >'.$vd->getTitre().'</a></li>
                                                                                                    </ul>
                                                                                                    '.$res_event.'
                                                                                                </div>
                                                                                            </div>';
                                                                                        }
                                                                                    }?>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                                                                    <script>
                                                                        $(document).ready(function() {
                                                                            $('#preview-button_<?= $athlete_adminitre->getID();?>').on('click', function() {
                                                                                var videoUrl = $('#video-url_<?= $athlete_adminitre->getID();?>').val();
                                                                                if (videoUrl) {
                                                                                    var videoId = videoUrl.split('v=')[1];
                                                                                    if (videoId) {
                                                                                        var ampersandPosition = videoId.indexOf('&');
                                                                                        if (ampersandPosition != -1) {
                                                                                            videoId = videoId.substring(0, ampersandPosition);
                                                                                        }
                                                                                        $('#video-preview_<?= $athlete_adminitre->getID();?>').html('<iframe width="560" height="315" src="https://www.youtube.com/embed/' + videoId + '" frameborder="0" allowfullscreen></iframe>');
                                                                                        $('#preview-container_<?= $athlete_adminitre->getID();?>').show();
                                                                                    }
                                                                                } else {
                                                                                    alert('Veuillez entrer une URL de vidéo.');
                                                                                }
                                                                            });
                                                                        });
                                                                    </script>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>

                                
                            </div>
                           
                           
                           
                           
                            <?php if($user_champs){?>
                                <div <?php if($current_tab=="res"){ echo  "class='active tab-pane fade in'";}else{echo "class='tab-pane fade'";} ?> id="tab7">
                                    <br>
                                    
                                    <?php if($added_res){?>
                                        <table id="tableauAddedRes" data-page-length='25' class="display">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-transform: capitalize;">Rang</th>
                                                            <th style="text-transform: capitalize;">Temps</th>
                                                            <th style="text-transform: capitalize;">Évenement</th>
                                                            <th style="text-transform: capitalize;">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        
                                                        foreach ($added_res as $key) {
                                                            $status=(!$key["Status"])?"En attente de validation":"Validé";
                                                            $rang_termi=($key["Rang"]>1)?" ème" :" er";
                                                            echo '<tr>';
                                                                echo '<td>'.$key["Rang"].$rang_termi.'</td>';
                                                                echo '<td>'.$key["Temps"].'</td>';
                                                                echo '<td>marathon '.$key["prefixe"]." ".str_replace('\\','',$key["Nom"])." ".strftime("%Y",strtotime($key["DateDebut"]))." (".$key["Sexe"].')</td>';
                                                                echo '<td>'.$status.'</td>';
                                                                
                                                            echo '</tr>';
                                                        }
                                                            
                                                    ?>
                                                        
                                                    </tbody>
                                                </table>
                                       
                                    <?php }?>
                                    <br><br>
                                    <div class="">
                                        
                                        <?php echo '<h1>Ajouter un résultat</h1>';?>
                                        <form action="/content/modules/ajouter-resultat.php" enctype="multipart/form-data" method="post" class="form-add-res form-horizontal"
                                            id="target">
                                            <div class="form-group">
                                                <label for="search_event" class="col-sm-5">Rechercher un évenement *</label>
                                                <div class="col-sm-7">
                                                    <input  id="search_event" type="text"> 
                                                    <input  type="hidden" name="e" id="search_event_id"> 
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <label class="col-sm-5" for="c_id">Champion:</label>
                                            <div class="col-sm-7">
                                                <select class="update_athlète_input" name="c" id="c_id" >
                                                    <?php foreach ($user_champs as $user_champ): ?>
                                                        <option value="<?php echo $user_champ->getID(); ?>" >
                                                            <?php echo $user_champ->getNom(); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select></div>
                                                </div>
                                            <input type="hidden" name="u" id="u_id" value="<?php echo  $profil->getUsername(); ?>" />
                                            <?php if(!$user_champs[0]->getSexe()){?>
                                                <div class="form-group">
                                                    <label for="naissance" class="col-sm-5">Sexe * </label>
                                                    <div class="col-sm-7">
                                                        <input type="radio" name="s"  required  value="M" class="mr-5"/><span class="mr-10">homme</span><input class="mr-5" type="radio" name="s" value="F"  /><span class="mr-10">femme</span>
                                                    </div>
                                                </div>
                                            <?php }else{?>
                                                <input type="hidden" name="s"  value="<?php echo $user_champs[0]->getSexe();?>" />
                                            <?php }?>
                                            <?php if(!$user_champs[0]->getPaysID()){?>
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
                                                <input type="hidden" name="p"  value="<?php echo $user_champs[0]->getPaysID();?>" />
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
                                                <label for="marathon" class="col-sm-5">Justificatif *</label>
                                                <div class="col-sm-7">
                                                    <input type="file" id="justificatif" name="j" accept="image/png, image/jpeg, application/pdf," required/>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                            <div class="col-sm-5">
                                                </div>
                                                <div class="col-sm-7">
                                                <input id="ajouter-resultat"  type="submit" style="width:100%;" class="call-to-action" value="Envoyer">
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                    <?php 
                                            if(isset($_SESSION['msg_ajout_resultat'])){
                                                echo $_SESSION['msg_ajout_resultat'];
                                               // unset($_SESSION['msg_ajout_resultat']);
                                            }
                                        ?>
                                        <br>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>

            </div> <!-- End left-side -->

            
        </div>
        
    </div> <!-- End container page-content -->

    <?php include_once('footer.inc.php'); ?>

    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/jquery.ui.totop.min.js"></script>
    <script src="../../js/herbyCookie.min.js"></script>
    <script src="../../js/main.js"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
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

            $('#tableauAddedRes').DataTable( {
            paging: false,
                bFilter: false,
                bSort: false,
                searching: true,
                dom: 't'   
        } );
            $('#creer_fiche_form_part1').hide();
            var user_champ = <?php echo ($user_champs ? 'true' : 'false'); ?>;
    
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
           $('#annuler_creation').on('click',function(e){
                $('#creer_fiche_form_part1').hide();
                    
            });
            

            
            $("#search_event").autocomplete({
    source: function(request, response) {
        if(request.term.length >= 3) { // Se déclenche après 5 caractères
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
                            label: "marathon "+(item.prefixe?item.prefixe:' ')+" "+item.Nom+" "+item.annee, // Assurez-vous que la clé 'Nom' correspond bien à la réponse du PHP
                            value: "marathon "+(item.prefixe?item.prefixe:' ')+" "+item.Nom+" "+item.annee, // Rempli dans le champ de saisie
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
    minLength: 3, // Nombre minimum de caractères avant de déclencher l'auto-complétion
    select: function(event, ui) {
        // Remplir un champ caché avec l'ID de l'élément sélectionné
        $("#search_event_id").val(ui.item.id);
        // Optionnel : faire autre chose avec les données sélectionnées
        console.log("ID sélectionné:", ui.item.id);
    }
});


                console.log("script creer fiche chargé")

        })
       
    </script>
 
</body>

</html>
<style type="text/css">
label.error { color: red;}
.form-prof-adm label { margin-bottom: 20px; width: 34%;}
 input[type=submit]{
    font-size: 16px;
    text-align: center;
    font-weight: 700;
    font-family: "Poppins-regular", sans-serif;
    color: #000000;
    background-color: #95d7fe;
    border-radius: 3px;
    border-width: 0px;
    padding: 10px 20px;
    margin-bottom: 20px;
 }
button{
    font-size: 16px;
    text-align: center;
    font-weight: 700;
    font-family: "Poppins-regular", sans-serif;
    color: #000000;
    background-color: #95d7fe;
    border-radius: 3px;
    border-width: 0px;
    padding: 10px 20px;
    margin-top: 5px;
    float:left !important;
 }
  input[type=submit]:hover, button:hover{
    color: #000000;
    background-color: #fbff0b;
 }
input[type="text"], input[type="email"], select, input[type="date"], input[type="url"], input[type="number"], select, input[type="time"]{ width: 50%; padding: 6px;  border: 1px solid #cccccc;}
input[type="email"], .form-mdp input {padding: 6px;}
.form-prof-adm td label{width:100% !important;max-width: 100% !important;text-align: left;}
.form-mdp td{padding-left:0px !important;width: 34% !important;  max-width: 100%!important;}
.form-mdp td input{width: 100% !important; max-width: 100% !important;}
.form-mdp tr.row { display: flex;}
.form-infos label { width: 17%;}
select#pays_40 {margin-bottom: 25px;}
#creer_fiche_form_part1 textarea{border: 1px solid #cccccc !important;}   
@media (max-width:1024px) {
.membre-profil .col-sm-12 {padding-left: 2%; padding-right: 2%;        margin-top: 2% !important;}
.col-sm-8.form-prof-adm { width: 100%;}
}   

@media (max-width:600px) {
.resultat-detail .nav-tabs>li { margin: 0 10px 10px 0px;}
input[type="date"] { width: 64% !important;display: unset;}
input[type="text"], input[type="email"], select, input[type="number"]{ width: 64%;}
.form-mdp td { padding-left: 0px !important;  width: 50% !important;}
.form-infos label { width: 35%;}


}

@media (max-width:497px) {
input[type="text"], input[type="email"], select, input[type="number"], input[type="email"], input[type="date"], input[type="link"], .form-mdp td, input[type="time"]{ width: 100% !important; }
.form-prof-adm label, .form-infos label {margin-top: 10px;margin-bottom: 10px; width: 100%;}
 .col-sm-8.form-prof-adm { margin-bottom: 12%;}   
.form-mdp tr.row { flex-direction: column;}
 #creer_fiche_form_part1 label, #creer_fiche_form_part1 input, #creer_fiche_form_part1 textarea{width: 100%;}   
    
}


</style>