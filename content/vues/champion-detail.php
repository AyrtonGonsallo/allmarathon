<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
setlocale(LC_TIME, "fr_FR","French");

// (!empty($_SESSION['auth_error'])) ? $erreur_auth=$_SESSION['auth_error'] : $erreur_auth='';
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

include("../classes/pub.php");
include("../classes/champion.php");
include("../classes/pays.php");
include("../classes/evresultat.php");
include("../classes/video.php");
include("../classes/commentaire.php");
include("../classes/user.php");
include("../classes/evenement.php");
include("../classes/championPopularite.php");
include("../classes/abonnement.php");
include("../classes/evCategorieAge.php");
include("../classes/championAdminExterneClass.php");
include("../classes/champion_admin_externe_palmares.php");
include("../classes/evCategorieEvenement.php");

$champion_admin_externe_palmares=new champion_admin_externe_palmares();
$resultsPerso=$champion_admin_externe_palmares->getResultsPerso($id)['donnees'];

$champAdmin=new championAdminExterne();
$isAdmin=$champAdmin->isAdmin($user_id,$id)['donnees'];

$ev_cat_age=new evcategorieage();
$ev_cat_event=new evCategorieEvenement();
$champ_pop=new championPopularite();
$event=new evenement();
$user=new user();

$admins=$champAdmin->getAdminExterneByChampion($id)['donnees'];
$liste_admin="";
if(sizeof($admins)!=0) {
    $liste_admin ="Fiche administrée par : ";
    foreach ($admins as $ad) {
        $profil_admin=$user->getUserById($ad->getUser_id())['donnees'];
        if($profil_admin)
            $liste_admin .=" ".$profil_admin->getUsername().",";
    }
}

if (isset($_SESSION['user_id'])) {
    $user_auth=$user->getUserById($user_id)['donnees'];
}

$champ_abonnement=new abonnement();
$champion=new champion();
$resultats_champ=$champion->getChampionResults($id)['donnees'];

$champ=$champion->getChampionById($id)['donnees'];
$photos=$champion->getChampionsPhotos($id)['donnees'];

$tab_med=$champ->getTabMedailleByChampion($id)['donnees'];

$page=0;

$commentaire=new commentaire();
$coms=$commentaire->getCommentaires(0,0,$id)['donnees'];
// $coms=$commentaire->getCommentairesChampion($id,$page)['donnees'];

$ev_res=new evresultat();


$video=new video();
$videos=$video->getVideosByChamp($id)['donnees'];

$pays=new pays();
$pays_intitule=('9999-12-31'!=$champ->getDateChangementNat())?$pays->getFlagByAbreviation($champ->getNvPaysID())['donnees']['NomPays']:$pays->getFlagByAbreviation($champ->getPaysID())['donnees']['NomPays'];

if($champ->getSexe()=="F") {$sexe="Femme"; $ne="Née";} else{ $sexe="Homme"; $ne="Né";}

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];

$getMobileAds=$pub->getMobileAds("resultats")['donnees'];

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

if(!empty($_SESSION['commentaire_error'])){
    $msg_com=" <span style='color:red' > ".$_SESSION['commentaire_error']."</span>";
    unset($_SESSION['commentaire_error']);}else{$msg_com="";}

if(!empty($_SESSION['commentaire_success'])){
    $msg_com=" <span style='color:green' > ".$_SESSION['commentaire_success']."</span>";
    unset($_SESSION['commentaire_success']);}else{$msg_com="";}

    function slugify($text)
{
    $text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}
$afficher_tab_medaille=false;
   foreach ($tab_med as $key => $value) {
            
    $equipe = false;
    $key  = $value['Intitule'];
    $key .= ($equipe)?" par equipes ":"";
    $key .= ' '.$value['Nom'].' '.substr($value['DateDebut'], 0,4);

    $tabResult[$key] = $value['ID'].'$'.$value['Rang'].'$'.$value['DateDebut'].'$';
    if($value['Rang'] < 4)
    {
        $afficher_tab_medaille=true;
        if(empty($tabMedal[$value['CategorieageID']][$value['tri']][$value['Intitule']][''][$value['Rang']]))
            $tabMedal[$value['CategorieageID']][$value['tri']][$value['Intitule']][''][$value['Rang']] = 1;
        else
            $tabMedal[$value['CategorieageID']][$value['tri']][$value['Intitule']][''][$value['Rang']]++;
    }
        

} 


?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title><?php echo $champ->getNom();?>, coureur de marathon. Résultats, vidéos, photos, record.</title>
    <meta name="Description" lang="fr" content="<?php echo $champ->getNom();?> est athlète, marathonien. Pays: <?php echo $pays_intitule;?>. Record de <?php echo $champ->getNom();?> sur marathon, résultats, photos, vidéos.">
    <meta property="og:type" content="siteweb" />
    <meta property="og:title" content="<?php echo $champ->getNom();?>, coureur de marathon. Résultats, vidéos, photos, record." />
    <meta property="og:image" content="https://allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="<?php echo 'https://allmarathon.fr/athlete-'.$champ->getId().'-'.slugify($champ->getNom()).'.html';?>" />
    <meta property="og:description" content="<?php echo $champ->getNom();?> est athlète, marathonien. Pays: <?php echo $pays_intitule;?>. Record de <?php echo $champ->getNom();?> sur marathon, résultats, photos, vidéos." />


    <link rel="apple-touch-icon" href="../../images/favicon.ico">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <?php echo '<link rel="canonical" href="https://allmarathon.fr/athlete-'.$champ->getId().'-'.slugify($champ->getNom()).'.html" />';?>

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"> -->
    <link href="http://cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/responsive.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->



    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlete-detail champion-detail">
        <div class="row banniere1">
            <div  class="col-sm-12"><?php
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
            <div class="col-sm-8 left-side">

                <div class="row">
                    <div class="col-sm-12">
                        
                            <?php 
                            $pays_datas=('9999-12-31'!=$champ->getDateChangementNat())?$pays->getFlagByAbreviation($champ->getNvPaysID())['donnees']:$pays->getFlagByAbreviation($champ->getPaysID())['donnees'];
                            if($pays_datas){
                                $flag=$pays_datas['Flag'];  
                            }
                            ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                            
                            echo '<div class="row">
                                <div class="col-sm-7 athlete-detail-no-padding-left">
                                <h1>'.$champ->getNom()." ".$pays_flag.'</h1>
                                </div>
                                <div class="col-sm-5 athlete-detail-no-padding-left no-padding-right">'; ?> 
                                <span class="cd-span athlete-boutons-droits" >
                                    <?php //echo '<a class="btn-no-padd info-bulle" href="/formulaire-administration-athlète.php?championID='.$id.'"><img src="/images/pictos/admin.png" title="Devenir administrateur"  /></a>'; ?>
                                    <?php if($user_id!=''){
                                        echo (!$isAdmin) ? '<a class="btn-no-padd info-bulle" href="/formulaire-administration-athlète.html?championID='.$id.'"><img src="/images/pictos/admin.png" title="Devenir administrateur"  /></a>': '<a href="/champion-detail-admin-'.$id.'.html" id="fiche_admin" type="button"><i class="fa fa-edit"></i><span>Modifier la fiche</span></a>  <a class="btn-no-padd info-bulle" href="mailto:lmathieu@allmarathon.net?subject='.$user_auth->getUsername().' ne souhaite plus administrer la fiche de '.$champ->getNom().'"><img src="/images/pictos/admin_1.png" title="Ne plus administrer cette fiche."  /></a>';
                                    }else{
                                        echo '<a id="gerer-cette" class="btn-no-padd info-bulle" href="#">Gérer cette fiche</a>';
                                    }?>
                                    <?php echo '<a class="btn-no-padd info-bulle" href="#">Recevoir des alertes</a>'; ?>
                                </span>
                                </div>
                            </div>
                        <?php echo rtrim($liste_admin, ","); ?>
                        <?php echo ($liste_admin!='') ? '<hr>' : ''; ?>
                        <div>
                            <!-- TAB NAVIGATION -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="active"><a href="#tab1" role="tab" data-toggle="tab">CV</a></li>
                                <li><a href="#tab2" role="tab"
                                        data-toggle="tab">Résultats (<?php echo sizeof($resultats_champ)+sizeof($resultsPerso); ?>)</a>
                                </li>
                                <li><a href="#tab3" role="tab"
                                        data-toggle="tab">PHOTOS (<?php echo sizeof($photos); ?>)</a></li>
                                <li><a href="#tab4" role="tab"
                                        data-toggle="tab">VIDEOS (<?php echo sizeof($videos); ?>)</a></li>
                                
                            </ul>
                        </div> <br /> <br />
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in" id="tab1">
                                <br />
                                <?php ($champ->getTaille()!="" && $champ->getTaille()!=0 ) ? $taille="<li><strong>Taille : </strong>".$champ->getTaille()." cm</li>" : $taille="";
                            ($champ->getDateNaissance()!="0000-00-00" && $champ->getDateNaissance()!="" ) ? $date_naissance="<li><strong>".$ne." le : </strong>".$champ->getDateNaissance()."</li>" : $date_naissance="";
                            $champ_facebook=($champ->getFacebook())?'<li><a href="'.$champ->getFacebook().'" target="_blank">Visitez le facebook de <strong>'.$champ->getNom().'</strong></a></li>':'';
                            $champ_instagram=($champ->getInstagram())?'<li><a href="'.$champ->getInstagram().'" target="_blank">Visitez le instagram de <strong>'.$champ->getNom().'</strong></a></li>':'';
                            $champ_ee=($champ->getEquipementier())?' <li><strong>Equipementier :  </strong>'.$champ->getEquipementier().'</li>':'';
                            $champ_lse=($champ->getLien_site_équipementier())?'<li><strong>Lien site équipementier :  </strong>'.$champ->getLien_site_équipementier().'</li>':'';
                            $champ_bio=($champ->getBio())?'<li><strong>Bio : </strong>'.$champ->getBio().'</li>':'';
                            $champ_taille=($champ->getTaille())?' <li><strong>Taille : </strong>'.$champ->getTaille().'</li>':'';
                            $champ_poids=($champ->getPoids())?'<li><strong>Poids : </strong>'.$champ->getPoids().'</li>':'';
                            /*$champ_=($champ->get())?'':'';
                            $champ_=($champ->get())?'':'';*/
                            echo '<ul>
                                    <li><strong>Sexe : </strong>'.$sexe.'</li>
                                    <li><strong>Pays : </strong>'.$pays_intitule.'</li>
                                    '.$date_naissance.'
                                    '.$champ_taille.'
                                    '.$champ_poids.'
                                    '.$champ_facebook.'
                                    '.$champ_instagram.'
                                    '.$champ_ee.'
                                    '.$champ_lse.'
                                    '.$champ_bio.'
                                </ul>

                            <br/>

                            '; ?>

                                <?php if($afficher_tab_medaille){  ?>
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Les médailles de <?php echo $champ->getNom(); ?></th>
                                            <th><img src="/images/pictos/or.png" alt="" /></th>
                                            <th><img src="/images/pictos/argent.png" alt="" /></th>
                                            <th><img src="/images/pictos/bronze.png" alt="" /></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php krsort($tabMedal, SORT_NUMERIC);
                               foreach($tabMedal as $CategorieageID => $tab){
                               // print_r($CategorieageID);echo '<hr>';
                               ?> <tr style="background: #eee">
                                            
                                        </tr>
                                        <?php   
                                foreach ($tab as $cat_age_ligne) {
                                     foreach($cat_age_ligne as $event_name=>$event_line){
                                        
                                         foreach ($event_line as $type => $rangs) {
                                            $type=($type=="Equipe") ? " par équipe " : "";
                                            ?>
                                        <tr>
                                            <td><?php echo $event_name.$type?></td>
                                            <td><?php echo (empty ($rangs[1]))?0:' '.$rangs[1]; ?></td>
                                            <td><?php echo (empty ($rangs[2]))?0:' '.$rangs[2]; ?></td>
                                            <td><?php echo (empty ($rangs[3]))?0:' '.$rangs[3]; ?></td>
                                        </tr>
                                        <?php }

                                                
                                    }
                                }
                                
                                 }
                                 ?>
                                    </tbody>
                                </table>
                                <?php }?>
                            </div>
                            <div class="tab-pane fade" id="tab2">

                                <table id="classement_resultats" class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th class=" headerSortDown">Année</th>
                                            <th class="">Clt</th>
                                            <th class="">Evenement</th>
                                            <th class="">Temps</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                	foreach ($resultats_champ as $key => $value) {
                                        $cat_age=$ev_cat_age->getEventCatAgeByID($value['CategorieageID'])['donnees']->getIntitule();
                                        $cat_event=$ev_cat_event->getEventCatEventByID($value['CategorieID'])['donnees']->getIntitule();
                                        
                                        $nom_res=slugify($cat_event.' - '.$value['Nom'].' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($value['DateDebut']))));
                                		echo '<tr>
			                                    <td>'.$value['DateDebut'].'</td>
			                                    <td align="left">'.$value['Rang'].'</td>
			                                    <td><a href="/resultats-marathon-'.$value['ID'].'-'.$nom_res.'.html">'.$value['Intitule'].' - '.$value['Nom'].'</a></td>
			                                    <td>'.$value['Temps'].'</td>
			                                </tr>';
                                	}
                                    if(sizeof($resultsPerso)!=0){
                                        foreach ($resultsPerso as $resultPerso) {
                                            
                                            if($resultPerso['CompetitionFr'] != ""){
                                                 echo '<tr>
                                                <td>'.$resultPerso['Date'].'</td>
                                                <td align="center">'.$resultPerso['Rang'].'</td>
                                                <td class="capitalize">'.$resultPerso['CompetitionType']. ' '. " de France (" . $resultPerso['CompetitionFr'] . ") " . $resultPerso['Intitule'].' '.$resultPerso['CompetitionLieu']. " " .'</td>
                                                <td align="center">'.$resultPerso['Temps'].'</td>
                                            </tr>';
                                            }
                                           
                                            else{ 
                                                echo '<tr>
                                                <td>'.$resultPerso['Date'].'</td>
                                                <td align="center">'.$resultPerso['Rang'].'</td>
                                                <td class="capitalize">'.$resultPerso['CompetitionType'].' '. $resultPerso['Intitule'] .' '. $resultPerso['CompetitionLieu'] . " " .'</td>
                                                <td align="center">'.$resultPerso['Temps'].'</td>
                                            </tr>';}
                                           
                                        }
                                        }  
                                	 ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="tab3">
                                <ul class="photos-tab">
                                    <?php 
								if(sizeof($photos)!=0){
									foreach ($photos as $key => $photo) {
                            			echo '<li><a href="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" class="fancybox" rel="group" ><img  src="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" width="116" height="auto" alt=""/></a></li>';
                            		}} ?>

                                </ul>
                            </div>

                            <div class="tab-pane fade" id="tab4">
                                <ul class="videos-tab">
                                    <?php
                            	foreach ($videos as $key => $vd) {
                            		
                            		$event_intitule="";
	                                if($vd->getEvenement_id()!=0){
	                                $annee_event=substr($event->getEvenementByID($vd->getEvenement_id())['donnees']->getDateDebut(),0,4);
                                    $video_intitule=$event->getEvenementByID($vd->getEvenement_id())['donnees']->getNom()." ".$annee_event;
	                                $event_intitule="<li><a href='/resultats-marathon-".$vd->getEvenement_id()."-".slugify($video_intitule).".html' class='video_event'>".$video_intitule."</a></li>";
	                                }
                            		$duree="<li style='list-style-type: none;'></li>";
	                                if($vd->getDuree()!='')
	                                $duree="<li>durée : ".$vd->getDuree()."</li>";

                            		echo '<li class="row">
                                    <ul class="video-align-top">
                                        <li class="col-sm-8">
                                            <ul>
                                                <li><a href="video-de-marathon-'.$vd->getId().'.html" class="video_titre">'.$vd->getTitre().'</a></li>'.$event_intitule.$duree.'
                                            </ul>
                                        </li>
                                        <li class="col-sm-4"><a href="video-de-marathon-'.$vd->getId().'.html"><img src="'.$vd->getVignette().'" width="120" heigh="90" alt="" class="pull-right img-responsive"/></a></li>
                                    </ul>
                                </li>';
                            	}
                            	 ?>
                                </ul>
                            </div>
                            
                        </div>
                        <span style="height:100px;width: 20px;opacity:0;">span</span>

                        <!--h2 class="bordered" style="margin-top:95px;">laissez un message à
                            <?php //echo $champ->getNom(); ?></h2-->
                        <?php
                       
                    ?>

                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                
                <!-- <p class="ban"><a href=""><?php //echo $pub300x60; ?></a></p>
            <p class="ban"><a href=""><?php //echo $pub300x250; ?></a></p> -->
                <p class="ban ban_160-600"><?php
if($pub160x600 !="") {
    //var_dump($pub160x600["url"]); exit;
    if($pub160x600["code"]==""){
        echo "<a href=".'http://allmarathon.net/'.$pub160x600["url"]." target='_blank'><img src=".'../images/news/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";
    }
    else{
        echo $pub160x600["code"];
    }
/*echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";*/
}
?></a></p>
                <div class="marg_bot"></div>
                
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
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" src="/js/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox -->
    <script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <script type="text/javascript" src="/js/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/jquery.fancybox-media.js?v=1.0.6"></script>

    <script type="text/javascript" src="/js/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <script type="text/javascript">
    $(document).ready(function() {
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


    <script type="text/javascript">
    $(document).ready(function() {
        $('#classement_resultats').DataTable({
            "paging": false,
            "bFilter": false,
            "info": false,
            "order": [
                [0, "desc"]
            ]
        });
    });
    </script>

    <?php
	if($user_id!=""){

        if($champ_pop->isUserFan($id,$user_id)['donnees']){
            $path_fan="champion_pop-moins";
            }else{
               $path_fan="champion_pop";
            }  
            // || (empty($_SESSION['plus_abonnee'])) 
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
            $('#gerer-cette').on('click',function(e){
                    $('#SigninModal').modal('show');});
                    
			</script>";
	}
?>
    <script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-1833149-1', 'auto');
    ga('send', 'pageview');
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