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
}  else {
    $user_session='';
}

$id=$_GET['championID'];

if (!isset($_SESSION['user_id'])) {
    header('Location: /athlète-'.$id.'.html');
}

include("../classes/pub.php");
include("../classes/champion.php");
include("../classes/pays.php");
include("../classes/evresultat.php");
include("../classes/video.php");
include("../classes/user.php");
include("../classes/evenement.php");
include("../classes/championPopularite.php");
include("../classes/abonnement.php");
include("../classes/evCategorieAge.php");
include("../classes/championAdminExterneClass.php");
include("../classes/departement.php");
include("../classes/region.php");
include("../classes/champion_admin_externe_palmares.php");
include("../classes/image.php");

$image=new image();

$user=new user();
$user_auth=$user->getUserById($user_id)['donnees'];

$admin_palmares=new champion_admin_externe_palmares();
$results=$admin_palmares->getAdminResults($id);

$region=new region();
$regions=$region->getAllRegions()['donnees'];

$departement=new departement();
$departements=$departement->getAllDepartements()['donnees'];

$champAdmin=new championAdminExterne();
$admins=$champAdmin->getAdminExterneByChampion($id)['donnees'];

$liste_admin="";
if(sizeof($admins)!=0){
    $liste_admin ="Fiche administrée par : ";
    foreach ($admins as $ad) {
        $liste_admin .=" ".$user->getUserById($ad->getUser_id())['donnees']->getUsername().",";
    }
}
$isAdmin=$champAdmin->isAdmin($user_id,$id)['donnees'];


$ev_cat_age=new evcategorieage();

$champ_pop=new championPopularite();
$event=new evenement();

function slugify($text)
{
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}

$champ_abonnement=new abonnement();
$champion=new champion();

$champ=$champion->getChampionById($id)['donnees'];
$photos=$champion->getChampionsPhotos($id)['donnees'];
$resultats_champ=$champion->getChampionResults($id)['donnees'];

$champion_name=slugify($champ->getNom());

if(!$isAdmin){
    header('Location: /athlète-'.$id.'-'.$champion_name.'.html');
}

$page=0;


$ev_res=new evresultat();
$poids="";


$video=new video();
$videos=$video->getVideosByChamp($id)['donnees'];

$pays=new pays();
$pays_intitule=$pays->getFlagByAbreviation($champ->getPaysID())['donnees']['NomPays'];

if($champ->getSexe()=="F") {$sexe="Femme"; $ne="Née";} else{ $sexe="Homme"; $ne="Né";}

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];


try{
    include("../database/connexion.php");
    $req4 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
    $req4->execute();
    $result4= array();
    while ( $row  = $req4->fetch(PDO::FETCH_ASSOC)) {  
        array_push($result4, $row);
    }
}catch(Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

// if((!empty($_SESSION['user']))&& (($champ_pop->isUserFan($id,$user_id)['donnees']) || (empty($_SESSION['plus_fan'])))){
if((!empty($_SESSION['user']))&& ($champ_pop->isUserFan($id,$user_id)['donnees'])){
    $img_fan_src="/images/pictos/fan_1.png";
    $img_fan_alt="Ne plus être fan";
}else{
   $img_fan_src="/images/pictos/fan.png";
   $img_fan_alt="Devenir Fan";
}  


// if((!empty($_SESSION['user'])) && (($champ_abonnement->isUserAbonne($id,$user_id)['donnees'])|| (empty($_SESSION['plus_abonnee'])))){
if((!empty($_SESSION['user'])) && ($champ_abonnement->isUserAbonne($id,$user_id)['donnees'])){
    $img_abonnement_src="/images/pictos/abonnement_1.png";
    $img_abonnement_alt="Ne plus s'abonner";
}else{
   $img_abonnement_src="/images/pictos/abonnement.png";
   $img_abonnement_alt="S'abonner";
}  

if(!empty($_SESSION['fan_error'])){
	$erreur=" <span style='color:red' > ".$_SESSION['fan_error']."</span>";
	unset($_SESSION['fan_error']);}else{$erreur="";}

    if(!empty($_SESSION['abonnement_error'])){
        $erreur=" <span style='color:red' > ".$_SESSION['abonnement_error']."</span>";
        unset($_SESSION['abonnement_error']);}else{$erreur="";}

        if(!empty($_SESSION['abonnement_error'])){
            $erreur=" <span style='color:red' > ".$_SESSION['abonnement_error']."</span>";
            unset($_SESSION['abonnement_error']);}else{$erreur="";}

            if(!empty($_SESSION['abonnement_error'])){
                $erreur=" <span style='color:red' > ".$_SESSION['abonnement_error']."</span>";
                unset($_SESSION['abonnement_error']);}else{$erreur="";}

                if(!empty($_SESSION['fiche_error'])){
                    $erreur=" <span style='color:red' > ".$_SESSION['fiche_error']."</span>";
                    unset($_SESSION['fiche_error']);}else{$erreur="";}

                    ?>
<script type="text/javascript">
// ***************************
// Script combobox chamionnat*
// ***************************
function autoComp(index){
        $.get('../admin/resultatAutoCompletion.php',{id: index, even_key: $('#temp'+index).val()},function(data){
            if(data != 0){
                $('#autocomp'+index).css('display', '');
                $('#autocomp'+index).html(data);
            }else{
                $('#autocomp'+index).css('display', 'none');
            }
        });
    }
    function addCompletion(str,index){
        tab     = str.split(':');
        idEV = tab[0];
        name    = tab[1];
        document.getElementById("autocomp"+index).style.display = "none";
        document.getElementById("evenement").value = name;
        document.getElementById("temp"+index).value = name;
        document.getElementById("evID").value = idEV;
    }
</script>

<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Le palmarès de l'athlète <?php  echo $champ->getNom().' ';?> : résultats, vidéos, photos</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="../../images/favicon.ico">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />

    <link href="../../css/jquery.filer.css" type="text/css" rel="stylesheet" />
    <link href="../../css/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="../../css/responsive.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->
    <style>
    a#search_2 {
        margin-top: 26px !important;
    }
    .autocomp {
        position: absolute;
        width: 100%;
        background-color: white;
        border: solid 1px black;
        padding: 2px;
        text-align: center;
        z-index: 100;
    }
    .autocomp a {
        color: black;
        display: block;
        cursor: pointer;
    }
    </style>
    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlète-detail champion-admin">
        <div class="row banniere1">
            <a href="" class="col-sm-12"><?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side">

                <div class="row">
                    <div class="col-sm-12">
                        <h1 style="border-bottom: 2px solid #cccccc;padding-bottom: 10px;">
                            <?php echo $champ->getNom()." ".$erreur ?> <span style="float: right;margin-top: -12px;">
                                <?php echo (!$isAdmin) ? '<a class="btn info-bulle" href="/formulaire-administration-athlète.php?championID='.$id.'"><img src="/images/pictos/admin.png" title="Devenir administrateur"  /></a>': '<a href="/athlète-'.$id.'-'.$champion_name.'.html" id="fiche_admin" type="button"><i class="fa fa-eye"></i><span>Voir la fiche</span></a> <a class="btn info-bulle" href="mailto:lmathieu@alljudo.net?subject='.$user_auth->getUsername().' ne souhaite plus administrer la fiche de '.$champ->getNom().'"><img src="/images/pictos/admin_1.png" title="Ne plus administrer cette fiche."  /></a>'; ?>
                                <?php echo '<a class="btn info-bulle" href="#"><img src="'.$img_abonnement_src.'" id="abonnement_id"title="'.$img_abonnement_alt.'"   /></a>'; ?>
                                <?php echo '<a class="btn info-bulle" href="#"><img src="'.$img_fan_src.'" id="fan_id" title="'.$img_fan_alt.'"  /></a>'; ?>
                        </h1>
                        <?php echo rtrim($liste_admin, ","); ?>
                        <?php echo ($liste_admin!='') ? '<hr>' : ''; ?>

                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#tab1" role="tab" data-toggle="tab">Admin<br>CV</a></li>
                            <li><a href="#tab2" role="tab" data-toggle="tab">Admin<br>Résultats</a></li>
                            <li><a href="#tab3" role="tab" data-toggle="tab">Admin<br>PHOTOS</a></li>
                            <li><a href="#tab4" role="tab" data-toggle="tab">Admin<br>VIDEOS</a></li>
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in" id="tab1">
                                <br><br>
                                <form action="/content/modules/update_fiche_athlète.php?championID=<?php echo $id;?>"
                                    method="post">

                                    <table style="font-size: 0.9em;">
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="Nom">Nom : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" name="Nom" class="update_athlète_input" value="<?php echo str_replace('\\', '',$champ->getNom());?>" />
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td  class="col-md-3" align="left">
                                                <label for="Sexe">Sexe : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="radio" name="Sexe"  value="M" <?php if($champ->getSexe()=="M") echo 'checked="checked"';?> /><span>homme</span><input type="radio" name="Sexe" value="F" <?php if($champ->getSexe()=="F") echo 'checked="checked"';?> /><span >femme</span>
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="DateNaissance">Date Naissance : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" align="left" class="update_athlète_input"
                                                    name="DateNaissance" id="datepicker"
                                                    value="<?php echo $champ->getDateNaissance(); ?>" />
                                            </td>
                                            <td class="col-md-2"></td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="LieuNaissance">Lieu de Naissance : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" class="update_athlète_input"
                                                    class="update_athlète_input" name="LieuNaissance" id="LieuNaissance"
                                                    value="<?php echo $champ->getLieuNaissance(); ?>" />
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="DateChangementNat">Date Changement Nat : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" align="left" class="update_athlète_input"
                                                    name="DateChangementNat" id="DateChangementNat"
                                                    value="<?php echo $champ->getDateChangementNat(); ?>" />
                                            </td>
                                            <td class="col-md-2"></td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="right">
                                                <label for="PaysID">Pays : </label>
                                            </td>
                                            <td class="col-md-7">
                                                <select name="PaysID" >
                                                    <?php //while($pays = mysql_fetch_array($result4)){
                                                    foreach ($result4 as $pays) {
                                                        if($champ->getPaysID()==$pays['Abreviation'] || $champ->getPaysID()==$pays['Abreviation_2'] || $champ->getPaysID()==$pays['Abreviation_3'] ||$champ->getPaysID()==$pays['Abreviation_4'] ||$champ->getPaysID()==$pays['Abreviation_5'])
                                                            echo '<option value="'.$pays['Abreviation'].'" selected>'.$pays['NomPays'].'</option>';
                                                        else
                                                            echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';
                                                    } ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="right">
                                                <label for="NvPaysID">Nouvelle nationalité : </label>
                                            </td>
                                            <td class="col-md-7">
                                            <select name="NvPaysID" >
                                                <?php //while($pays = mysql_fetch_array($result4)){
                                                foreach ($result4 as $pays) {
                                                    if($champ->getNvPaysID()==$pays['Abreviation'])
                                                        echo '<option value="'.$pays['Abreviation'].'" selected>'.$pays['NomPays'].'</option>';
                                                    else
                                                        echo '<option value="'.$pays['Abreviation'].'">'.$pays['NomPays'].'</option>';
                                                } ?>
                                            </select>
                                        </td>
                                    </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="Taille">Taille (en cm) : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" class="update_athlète_input" name="taille" id="Taille"
                                                    value="<?php echo $champ->getTaille(); ?>" />
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="Poids">Poids : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" class="update_athlète_input" name="poids" id="Poids"
                                                    value="<?php echo $champ->getPoids(); ?>" />
                                            </td>
                                        </tr>
                                        
                                        
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="Equipementier">Equipementier : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" class="update_athlète_input" name="Equipementier"
                                                    id="Equipementier" value="<?php echo $champ->getEquipementier(); ?>" />
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="Lien_site_équipementier">Lien_site_équipementier: </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" class="update_athlète_input" name="lien_equip" id="Lien_site_équipementier"
                                                    value="<?php echo $champ->getLien_site_équipementier(); ?>" />
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="Facebook">Facebook : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" class="update_athlète_input" name="Facebook" id="Facebook"
                                                    value="<?php echo $champ->getFacebook(); ?>" />
                                            </td>
                                        </tr>

                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label
                                                    for="Instagram">Instagram</label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" class="update_athlète_input" name="Instagram" id="Instagram"
                                                    value="<?php echo $champ->getInstagram(); ?>" />
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="Bio">Bio : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <textarea name="Bio" id="Bio" class="update_athlète_input"
                                                    cols="50" rows="4"><?php echo $champ->getBio(); ?></textarea>
                                            </td>
                                        </tr>
                                        

                                        <tr class="row">
                                            <td class="col-md-3" align="left">
                                                <label for="Site">Url site perso : </label>
                                            </td>
                                            <td class="col-md-7" align="left">
                                                <input type="text" class="update_athlète_input" name="Site" id="Site"
                                                    placeholder="ex : https://www.site.com"
                                                    value="<?php echo $champ->getSite(); ?>" />
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-md-3" align="left"></td>
                                            <td class="pull-right no-flot">
                                                <input type="submit" class="btn_custom" name="sub" value="modifier" />
                                            </td>
                                        </tr>
                                    </table>
                                </form>

                            </div>
                            <div class="tab-pane fade" id="tab2">
                                <br><br>
                                <p style="font-size: 1em;">Vous pouvez ajouter des résultats au palmarès. Cela concerne
                                    les Jeux olympiques, championnats du monde, championnats d'Europe, championnats de France, World Majors Marathon. Les résultats que vous insérez sont contrôlés et ils peuvent être supprimés
                                    par l'administrateur du site s'ils sont inexacts.
                                </p>

                                <br />
                                <form action="/content/modules/update_fiche_athlète.php?championID=<?php echo $id;?>"
                                    method="post" name="formResult" id="formResult"
                                    style="padding:20px; background-color: #e8e8e8; margin-bottom: 10px;">
                                    <div id="divRang" class="row">
                                        <div class="col-md-3">Rang: </div>
                                        <div class="col-md-5">
                                            <input type="number" name="rang" id="rang" class="update_athlète_input" required
                                                 size="15" />
                                            
                                        </div>
                                    </div>
                                    
                                    <br />
                                    <div id="divDate" class="row">
                                        <div class="col-md-3">Date: </div>
                                        <div class="col-md-5"><input type="text" class="update_athlète_input" required
                                                name="date_comp" id="datepicker_comp" size="15" /></div>
                                    </div>
                                    
                                    <br />
                                    <div id="divtemps" class="row">
                                        <div class="col-md-3">Temps: </div>
                                        <div class="col-md-5"><input type="text" placeholder="XX:XX:XX" class="update_athlète_input" required
                                                name="temps" id="temps" size="15" /></div>
                                    </div>
                                    <br />
                                    <input style="display:none;" id="USER" name="USER" type="text" value="<?php echo $user_auth->getUsername();?>" />

                                    
                                    <div id="divCompDep" class="row">
                                        <div class="col-md-3">Evenement: </div>
                                        <div class="col-md-5">
                                            <div id="autoCompChamp1">
                                                <input autocomplete="off" type="text" class="update_athlète_input"  id="temp1" onkeyup="autoComp(1);" value="" />
                                                <div id="autocomp1" style="display:none;" class="autocomp"></div>
                                                <input style="display:none;" id="evenement" name="evenement" type="text" value="" />
                                            </div>
                                        </div>
                                    </div>

                                    <input style="display:none;" id="evID" name="evID" type="text" value="" />
                                   
                                    <br />

                                    <input type="hidden" name="championID" id="championID" value="<?php echo $id; ?>" />
                                    <input type="hidden" name="userResult" id="userResult"
                                        value="<?php echo $_SESSION['user_id'] ?>" />
                                    <div class="row">
                                        <div class="col-md-4" align="left"></div>
                                        <input type="submit" name="submitForm" class="btn_custom" value="Envoyer" />
                                    </div>
                                    <br>
                                </form>
                                <?php if(sizeof($results['donnees']) != 0){ ?>
                                <p class="liste_res">Liste des résultats ajoutés</p>
                                <table style="font-size: 0.9em;">
                                    <thead style="background-color: #e8e8e8">
                                        <tr>
                                            <th class="col-md-2 update_athlète_cell">Date</th>
                                            <th class="col-md-1 update_athlète_cell">Rang</th>
                                            <th class="col-md-5 update_athlète_cell">Evenement</th>
                                            <th class="col-md-2 update_athlète_cell">Temps</th>
                                            <th class="col-md-1 update_athlète_cell">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody style="border-bottom: 1px">
                                        <form
                                            action="/content/modules/update_fiche_athlète.php?championID=<?php echo $id;?>"
                                            method="post"
                                            onsubmit="if(window.confirm('Supprimer le resultat?'))return true; else return false;">
                                            <?php foreach ($results['donnees'] as $resultPerso) {
                                        
                                        
                                            echo '<tr clas="row"><td class="col-md-2 update_athlète_cell">'.$resultPerso['Date'].'</td><td class="col-md-1 update_athlète_cell" align="center">'.$resultPerso['Rang'].'</td><td class="col-md-5 update_athlète_cell">'.$resultPerso['CompetitionFr'].'</td><td class="col-md-2 update_athlète_cell" align="center">'.$resultPerso['Temps'].'</td><td class="col-md-1 update_athlète_cell"><input name="supprimer" type="image" src="images/supprimer.png" style="width: 20px" title="supprimer le resultat" /><input type="hidden" name="extern_supp_id" value="' . $resultPerso['ID'] . '"/></td></tr><tr><td colspan="5"><img src="images/CSS/border-tableau.gif"/></td></tr>';
                                        
                                    } ?>
                                        </form>
                                    </tbody>
                                </table>
                                <br /><br />
                                <?php } ?>
                            </div>

                            <div class="tab-pane fade" id="tab3">
                                <ul class="photos-tab">
                                    <br>
                                    <p style="font-size: 1em;">Vous pouvez proposer de nouvelles photos.</p>
                                    <br>
                                    <!-- /content/modules/update_fiche_athlète.php?championID=<?php echo $id;?> -->
                                    <form action="" method="post" enctype="multipart/form-data">
                                    <input type="file"  accept="image/png, image/jpeg" name="files[]" id="filer_input2" multiple="multiple">
                                        <input type="submit" value="Envoyer" class="btn_custom pull-right"
                                            name="photo_sub"><br>
                                    </form>
                                </ul>

                                <h4
                                    style="display: block; border-bottom: dotted 1px silver; padding-bottom: 4px;margin-bottom: 2px;">
                                    Modification de Photos</h4>
                                <div id="photos">

                                    <?php foreach ($image->getImagesByChampion($id)['donnees'] as $photo) { ?>
                                    <form
                                        action="/content/modules/update_fiche_athlète.php?imageID=<?php echo $photo['imageID']; ?>"
                                        method="post">
                                        <table class="edit_image_table">
                                            <tr class="row">
                                                <!--  -->
                                                <td class="col-md-3 pull-left">
                                                    <div class="TabbedPanelsContent">


                                                        <a class="nyroModal"
                                                            title="<?php echo $photo['Description']; ?>" rel="gal"
                                                            href="images/galeries/<?php echo $photo['Galerie_id'].'/'.$photo['Nom']; ?>"><img
                                                                class="thumb"
                                                                style="cursor:pointer;float:left;margin:5px;border:0;"
                                                                src="images/galeries/<?php echo $photo['Galerie_id'].'/'.$photo['Nom']; ?>"
                                                                alt="" height="77" /></a>

                                                        <div style="clear:both;height:0px;">&nbsp;</div>
                                                    </div>
                                                </td>
                                                <td class="col-md-7">

                                                    <input type="hidden" name="actif" value="oui" checked />
                                                           
                                                    
                                                    <div class="row">
                                                        <div class="col-md-6 lab_class">Légende : </div>
                                                        <div class="col-md-6 content_class"><textarea cols="30" rows="4"
                                                                id="legende"
                                                                name="legende"><?php echo $photo['Description']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-2 col-md-offset-10">
                                                            <input type="hidden" name="championID"
                                                                value="<?php echo $id; ?>" /><input type="submit"
                                                                class="btn_custom" name="modifPhoto" value="Valider" />
                                                        </div>
                                                    </div>

                                                </td>

                                            </tr>

                                            <!-- <tr>
                                            <td><input type="hidden" name="championID" value="<?php echo $id; ?>"/><input type="submit" name="modifPhoto" value="Valider"/></td>
                                        </tr> -->
                                            <br>
                                            <!-- <div class="col-md-12" style="margin-top: 10px;">
                                            <div class="pull-right">
                                                <input type="hidden" name="championID" value="<?php echo $id; ?>"/><input type="submit" class="btn_custom" name="modifPhoto" value="Valider"/></td>
                                            </div>
                                        </div> -->
                                        </table>
                                    </form>
                                    <form
                                        action="/content/modules/ajax_remove_file.php"
                                        method="post">
                                        <input type="hidden" name="file" value="<?php echo $photo['Nom']; ?>" />
                                        <input type="hidden" name="championID"
                                                                value="<?php echo $id; ?>" />
                                        <input type="submit" class="btn_custom"  value="Supprimer" />
                                                        
                                    </form>
                                    <?php }?>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="tab4">
                                
                                    <br>
                                    <p style="font-size: 1em;">Vous pouvez proposer des vidéos en ligne ( youtube,
                                        dailymotion ... ) en envoyant ici l'url des pages (ex :
                                        https://www.youtube.com/watch?v=XXXXXXX )</p>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            	<input type="text" id="video_url" placeholder="https://www.youtube.com/watch?v=XXXXXXX" class="update_athlète_input"/>
                                        </div>
                                        <br>
                                        <div class="col-md-12" style="margin-top: 10px;">
                                            <div class="pull-right no-flot">
                                                <button  id="aperçu" class="btn_custom"> Aperçu</button>
                                                <button  id="Proposer" class="btn_custom"> Envoyer</button>
                                                
                                                    
                                            </div>
                                            <div id="apercu-video" class="records-nationaux">
                                                
                                                    
                                            </div>
                                        </div>
                                    </div>


                                

                            </div>

                        </div>
                        <span style="height:100px;width: 20px;opacity:0;">span</span>

                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <!-- <p class="ban"><a href=""><?php //echo $pub300x60; ?></a></p>
            <p class="ban"><a href=""><?php //echo $pub300x250; ?></a></p> -->
                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <div class="marg_bot"></div>
                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
            <dd class="facebook">
                <div class="fb-page"
                data-href="https://www.facebook.com/alljudonet-108914759155897/"
                data-width=""
                data-adapt-container-width="true"
                data-hide-cover="false"
                data-show-facepile="true">
            </div>
        </dd>
        <div class="marg_bot"></div> -->
            </aside>
        </div>

    </div> <!-- End container page-content -->


    <?php include_once('footer.inc.php'); ?>



    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins.js"></script>
    <script src="/js/jquery.jcarousel.min.js"></script>
    <script src="/js/jquery.sliderPro.min.js"></script>
    <script src="/js/easing.js"></script>
    <script src="/js/jquery.ui.totop.min.js"></script>
    <script src="/js/herbyCookie.min.js"></script>
    <script src="/js/main.js"></script>
    

    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" src="/js/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox -->
    <script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <script type="text/javascript" src="/js/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/jquery.fancybox-media.js?v=1.0.6"></script>

    <script type="text/javascript" src="/js/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

    <script src="/js/jquery.filer.min.js"></script>
    <script src="/js/custom_filer.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {
        $("#aperçu").click(function() {
            url=$("#video_url").val()
            video_id=url.split("?v=")[1]
            console.log(url)
            iframe='<iframe width="640" height="345" src="https://www.youtube.com/embed/'+video_id+'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>'
            $("#apercu-video").html(iframe).show();
        })
        $("#Proposer").click(function() {
            url=$("#video_url").val()
            $.ajax({
                    type: "POST",
                    url: "content/modules/update_fiche_athlète.php?championID=<?php echo $id;?>",
                    data: {
                        championID:<?php echo $id;?>,
                        video_sub:"oui",
                        video:url,
                    },
                    success: function(html) {
                        $("#apercu-video").html("Merci, votre vidéo a été soumise à notre équipe, elle sera en ligne après validation.").show();
                        //console.log("success",html)
                    },
                    error: function (jqXHR, exception) {
                            var msg = '';
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status == 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status == 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            console.log("error",msg)
                        },
                });
        })
        $("#datepicker,#datepicker_comp").datepicker();
        $('#datepicker,#datepicker_comp').datepicker('option', {
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août',
                'Sept.', 'Oct.', 'Nov.', 'Déc.'
            ],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            dateFormat: 'yy-mm-dd'
        });

        $("#datepicker").datepicker("setDate", "<?php echo $champ->getDateNaissance(); ?>");
        
        $("#DateChangementNat").datepicker("setDate", "<?php echo $champ->getDateChangementNat(); ?>");

        $(".fancybox").fancybox({
            helpers: {
                overlay: {
                    css: {
                        'background': 'rgba(0, 0, 0, 0.4)'
                    }
                }
            },
            margin: [110, 60, 30, 60]
        });
    });
    </script>
    <script type="text/javascript">
    pageSize = 5;

    $(function() {
        var pageCount = Math.ceil($(".line-content").size() / pageSize);

        for (var i = 0; i < pageCount; i++) {
            if (i == 0)
                $("#pagin_com").append('<li><a class="curent_page_com" href="#">' + (i + 1) + '</a></li>');
            else
                $("#pagin_com").append('<li><a href="#">' + (i + 1) + '</a></li>');
        }


        showPage(1);

        $("#pagin_com li a").click(function() {
            $("#pagin_com li a").removeClass("curent_page_com");
            $(this).addClass("curent_page_com");
            showPage(parseInt($(this).text()))
        });

    })

    showPage = function(page) {
        $(".line-content").hide();

        $(".line-content").each(function(n) {
            if (n >= pageSize * (page - 1) && n < pageSize * page)
                $(this).show();
        });
    }
    </script>


    


    <?php
    if($user_id!=""){

        if($champ_pop->isUserFan($id,$user_id)['donnees']){
            $path_fan="champion_pop-moins";
        }else{
           $path_fan="champion_pop";
       }
            // || (!empty($_SESSION['plus_abonnee']))  
       if($champ_abonnement->isUserAbonne($id,$user_id)['donnees']){
        $path_abonnement="champion_desabonnement";
    }else{
       $path_abonnement="champion_abonnement";
   } 

   echo "<script type='text/javascript'>
   $('#fan_id').on('click',function(e){
					// console.log('user id : '+".$user_id.");
    document.location.href='/content/modules/".$path_fan.".php?champ_id=".$id."';

});
$('#abonnement_id').on('click',function(e){
    document.location.href='/content/modules/".$path_abonnement.".php?champ_id=".$id."';
});
$('#com_but').on('click',function(e){
    document.location.href='/content/modules/add_commentaire.php?champ_id=".$id."&commentaire='+$('#message_champion').val();
});


</script>";
}else{
   echo "<script type='text/javascript'>
   $('#fan_id').on('click',function(e){
     $('#SigninModal').modal('show');});
     $('#abonnement_id').on('click',function(e){
        $('#SigninModal').modal('show');});
        $('#com_but').on('click',function(e){
            $('#SigninModal').modal('show');});
        </script>";
    }
    ?>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->

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