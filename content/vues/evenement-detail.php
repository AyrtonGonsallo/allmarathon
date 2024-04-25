<?php  header("Cache-Control: max-age=2592000");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// (!empty($_SESSION['auth_error'])) ? $erreur_auth=$_SESSION['auth_error'] : $erreur_auth='';
if(!empty($_SESSION['auth_error'])) {
   $erreur_auth=$_SESSION['auth_error'];
   unset($_SESSION['auth_error']);
}else $erreur_auth='';

(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!empty($_SESSION['user'])) {
$user_session=$_SESSION['user'];
$erreur_auth='';
}  else $user_session='';
include("../classes/marathon.php");
include("../classes/evenement.php");
include("../classes/evCategorieAge.php");
include("../classes/video.php");
include("../classes/championAdminExterneClass.php");
include("../classes/resultat.php");
include("../classes/news.php");
include("../classes/pub.php");
include("../classes/user.php");
include("../classes/evresultat.php");
include("../classes/champion.php");
include("../classes/evequipe.php");
include("../modules/functions.php");
$user=new user();
$evequipe=new evequipe();

$ch=new champion();

$pays=new pays();
$liste_pays=$pays->getAll()['donnees'];
$ev_cat_age=new evCategorieAge();

$pub=new pub();
//$user_auth=$user->getUserById($user_id)['donnees'];
$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("resultats")['donnees'];
// $id=4951; //video
// $id=4465; //images
// $id=4990; //club
$id=$_GET['evenementID'];
$evresultat=new evresultat();
// $resultas_par_classement=$evresultat->getResultClassement($id)['donnees'];


$video=new video();
$videos=$video->getEventVideoById($id)['donnees'];


$ev_cat_event=new evCategorieEvenement();

$event=new evenement();
$archives=$event->getDernierResultatsArchive();
$evById=$event->getEvenementByID($id)['donnees'];
$champAdmin=new championAdminExterne();
$isAdmin=$user_id?true:false;
$resultat=new resultat();
$photos=$resultat->getPhotos($id)['donnees'];
$last_results=$resultat->getLastResults();
$all_year_results=$resultat->getAllYearsResults($evById->getmarathon_id(),$id);
$club =$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getClub();
($club) ? $classement="clubs" : $classement="pays";

$type = $evById->getSexe();
$active_tab1="active";
        $active_tab2="";

$destination_path  = "/uploadDocument/";
$ev_cat_event_int_titre=$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule();
$ev_cat_age_int_titre=$ev_cat_age->getEventCatAgeByID($evById->getCategorieageID())['donnees']->getIntitule();
$annee_titre=substr($evById->getDateDebut(), 0, 4);





function switch_cat($cat)

{

    switch ($cat) {

        case "Breve":

            return "En bref";

            break;

        case "Videos":

            return "Vidéos";

            break;

        case "Equipe de France":

            return "Équipe de France";

            break;

        default:

            return $cat;

                                }

}
$next_date= $event->getMarathon($evById->getmarathon_id())['donnees'];
if($next_date){
$results_all_years=$event->getOthersMarathonEvents($next_date['id'],$id)['donnees'];
}else{
    $results_all_years=null;
}
$news=new news();
$event_news=$news->getNewsByEventId($id)['donnees'];
setlocale(LC_TIME, "fr_FR","French");
?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Résultats du marathon <?php echo $evById->getPrefixe();?> <?php echo str_replace('\\','',$evById->getNom());?> - <?php echo $pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];?> - <?php echo $ev_cat_event_int_titre;?> <?php echo $annee_titre;?></title>
    <?php
    $winner="";    
    if($evresultat->getResultBySexe($id,"M")['donnees'] && $evresultat->getResultBySexe($id,"F")['donnees']){
        $winner=" Les vainqueurs sont ".$evresultat->getResultBySexe($id,"M")['donnees'][0]['Nom']." (hommes) et ".$evresultat->getResultBySexe($id,"F")['donnees'][0]['Nom']." (femmes).";
    }else if($evresultat->getResultBySexe($id,"M")['donnees'] &&  !$evresultat->getResultBySexe($id,"F")['donnees']){
        $winner=" Le vainqueur est ".$evresultat->getResultBySexe($id,"M")['donnees'][0]['Nom']." (hommes).";
    }else if(!$evresultat->getResultBySexe($id,"M")['donnees'] && $evresultat->getResultBySexe($id,"F")['donnees']){
        $winner=" Le vainqueur est ".$evresultat->getResultBySexe($id,"F")['donnees'][0]['Nom']." (femmes).";
    }else{
        $winner="";
    }
    ?>
    <meta name="Description" content="Le <?php echo $ev_cat_event_int_titre;?> <?php echo $annee_titre;?> de <?php echo $evById->getNom();?> (<?php echo $pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];?>) a eu lieu le <?php echo changeDate($evById->getDateDebut()).'.'.$winner;?> Résultats complets, classements et temps." lang="fr" xml:lang="fr" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:title" content="Résultats du marathon <?php echo $evById->getPrefixe();?> <?php echo str_replace('\\','',$evById->getNom());?> - <?php echo $pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];?> - <?php echo $ev_cat_event_int_titre;?> <?php echo $annee_titre;?>" />
    <meta property="og:description" content="Le <?php echo $ev_cat_event_int_titre;?> <?php echo $annee_titre;?> de <?php echo $evById->getNom();?> (<?php echo $pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];?>) a eu lieu le <?php echo changeDate($evById->getDateDebut()).'.'.$winner;?> Résultats complets, classements et temps." />
    <? if($next_date){?>
        <meta property="og:image" content="<?php echo 'https://dev.allmarathon.fr/images/marathons/'.$next_date['image'];?>" />
    <? }?>
    <meta property="og:url" content="<?php echo 'https://dev.allmarathon.fr/resultats-marathon-'.$evById->getId().'-'.slugify($ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule()).'-'.slugify($evById->getNom()).'-'.slugify(changeDate($evById->getDateDebut())).'.html';?>" />


    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
  
    <?php echo '<link rel="canonical" href="https://dev.allmarathon.fr/resultats-marathon-'.$evById->getId().'-'.slugify($ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule()).'-'.slugify($evById->getNom()).'-'.slugify(changeDate($evById->getDateDebut())).'.html" />';?>

  
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    
 <!-- Alpine Plugins -->
 <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
 
 <!-- Alpine Core -->
 <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="../../css/responsive.css">

</head>

<body>



    <?php include_once('nv_header-integrer.php'); ?>
    <div id="parcours-img-viewer">
        <span class="close" onclick="close_model()">&times;</span>
        <img class="parcours-modal-content" id="parcours-full-image" >
    </div>
    <div class="container page-content resultat-detail mt-77 mb-80">
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
                            $pays_intitule=$pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];
                            $ev_cat_event_int=$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule();
                            $ev_cat_age_int=$ev_cat_age->getEventCatAgeByID($evById->getCategorieageID())['donnees']->getIntitule();
                            $annee=substr($evById->getDateDebut(), 0, 4);
                            $full_text_date=utf8_encode(strftime("%A %d %B %Y",strtotime($evById->getDateDebut())));
                            $date_debut=changeDate($evById->getDateDebut());
                            $pays_datas=$pays->getFlagByAbreviation($evById->getPaysId())['donnees'];
                            if($pays_datas){
                                $flag=$pays_datas['Flag'];  
                            }
                            ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                            if($next_date){
                                echo '<h1>Résultats du '.$ev_cat_event_int.' '.$next_date["prefixe"].' '.$evById->getNom().' '.$annee.'</h1>';
                            }else{
                                echo '<h1>Résultats du '.$ev_cat_event_int.' '.$evById->getNom().' '.$annee.'</h1>';
                            }
                            
                            
                            ?>
                            <span class="athlete-details-breadcumb">
                                <a href="resultats-marathon.html">Résultats</a>
                                &gt;
                                <? if($next_date){?>
                                <?php echo '<a href="/marathons-'.$next_date['id'].'-'.slugify($next_date["nom"]).'.html">Marathon '.$next_date["prefixe"].' '.$next_date["nom"].'</a>';?>
                                &gt; 
                                <?}?>
                                <?php echo $full_text_date;?>                               
                            </span>
                    <div class="mb-50"></div>


                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <?php echo '<li class="'.$active_tab1.'"><a href="#tab1" role="tab" data-toggle="tab">Résultats</a></li>'; ?>
                            
                            <?php
                             if(sizeof($videos)!=0){?>
                                <li><a href="#tab2" role="tab" data-toggle="tab">Vidéos
                                       </a></li>
                            <? }if(sizeof($photos)!=0){?>
                                <li><a href="#tab3" role="tab" data-toggle="tab">Photos
                                       </a></li>
                                
                            <?php }?>
                            <? if($event_news){?>
                                <li><a href="#tab4" role="tab" data-toggle="tab">Actus
                                       </a></li>
                            <?php }?>
                            <? if($evById->getParcours_iframe() || $evById->getParcours_image() ){?>
                                <li><a href="#tab6" role="tab" data-toggle="tab">Parcours
                                       </a></li>
                            <?php }?>
                            
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content marg_top">

                            <?php ($active_tab1!="") ? $cl_fd_tab1="active fade in" : $cl_fd_tab1="fade";
                        echo '<div class="'.$cl_fd_tab1.' tab-pane" id="tab1">';
                        
                            ?>
                            <ul class="row">
                                <!--<div class="col-12 resultat_shared">
                                    <?php //include_once("shareButtons.php"); ?>
                                </div>-->

                                
                                <li class="col-sm-12">
                                <?php echo '<div id="genre">'.$type.'</div>';?>
                                <div class="results-sub-menu">
                                <?php if(($type=="MF") || ($type=="M")){ ?>
                                    <button id="res-hommes" class="res-by-gender"><span class="material-symbols-outlined">male</span>Hommes</button>
                                    <?php }if(($type=="MF") || ($type=="F")){ ?>
                                    <button id="res-femmes" class="res-by-gender"><span class="material-symbols-outlined">female</span>Femmes</button>
                                    
                                    <?php }if($evById->getDocument1()!='') echo '<a class="btn results-buttons" href="PDF_frame-'.rawurlencode($evById->getDocument1()).'" target="_blank" class="btn btn-default"><i class="fa-file-pdf-o fa"></i>&nbspPDF</a>';  ?>
                                    <?php if($evById->getlien_resultats_complet()) echo '<a class=" results-buttons" href="'.$evById->getlien_resultats_complet().'" target="_blank" class="btn btn-default"><img width="16px" src="../../images/redirect.png" alt="tout voir">&nbspRésultats complets</a>';  ?>
                                    <?php //if($isAdmin) echo '<a class="btn results-buttons" href="evenement-detail-admin-'.$id.'.html" ><img width="13px" src="../../images/pictos/finisher.png" alt="finisher"> Je suis finisher</a>';  ?>
                                    <?php //if(!$isAdmin) echo '<a class="btn results-buttons" href="#" id="finisher"><img width="13px" src="../../images/pictos/finisher.png" alt="finisher"> Je suis finisher</a>';  ?>
                                </div>
                                  
                                            <?php if(($type=="MF") || ($type=="M")){ ?>
                                                <table id="tableauHommes" data-page-length='25' class="display">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-transform: capitalize;">Rang</th>
                                                            <th style="text-transform: capitalize;">Athlète</th>
                                                            <th style="text-transform: capitalize;">Pays</th>
                                                            <th style="text-transform: capitalize;">Temps</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $ev_res_sexe=$evresultat->getResultBySexe($id,"M")['donnees'];
                                                        
                                                        
                                                        foreach ($ev_res_sexe as $key => $value) {
                                                            $pays_datas=NULL;
                                                            $pays_display='';
                                                            if($evById->getDateDebut()>$value['DateChangementNat']){
                                                                $pays_datas=$pays->getFlagByAbreviation($value['NvPaysID'])['donnees'];
                                                                $pays_display=$value['NvPaysID'];
                                                            }else{
                                                                $pays_datas=$pays->getFlagByAbreviation($value['PaysID'])['donnees'];
                                                                $pays_display=$value['PaysID'];
                                                            }
                                                            if($pays_datas){
                                                                $flag=$pays_datas['Flag'];  
                                                                $abv=$pays_datas['Abreviation'];
                                                            }else{
                                                                $flag="";  
                                                                $abv="";
                                                            }
                                                            ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span><br>':$pays_flag="";
                                                            echo '<tr>';
                                                                echo '<td>'.$value['Rang'].'</td>';
                                                                echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].'</a></td>';
                                                                echo '<td>'.$abv.'</td>';
                                                                echo '<td>'.$value['Temps'].'</td>';
                                                            echo '</tr>';
                                                        }
                                                            
                                                    ?>
                                                        
                                                    </tbody>
                                                </table>
                                            <?php } ?>
                                            <?php if(($type=="MF") || ($type=="F")){ ?>
                                                <table id="tableauFemmes" data-page-length='25' class="display">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-transform: capitalize;">Rang</th>
                                                            <th style="text-transform: capitalize;">Athlète</th>
                                                            <th style="text-transform: capitalize;">Pays</th>
                                                            <th style="text-transform: capitalize;">Temps</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                        $ev_res_sexe=$evresultat->getResultBySexe($id,"F")['donnees'];
                                                        
                                                        
                                                        foreach ($ev_res_sexe as $key => $value) {
                                                            $pays_datas=NULL;
                                                            $pays_display='';
                                                            if($evById->getDateDebut()>$value['DateChangementNat']){
                                                                $pays_datas=$pays->getFlagByAbreviation($value['NvPaysID'])['donnees'];
                                                                $pays_display=$value['NvPaysID'];
                                                            }else{
                                                                $pays_datas=$pays->getFlagByAbreviation($value['PaysID'])['donnees'];
                                                                $pays_display=$value['PaysID'];
                                                            }
                                                            if($pays_datas){
                                                                $flag=$pays_datas['Flag'];  
                                                                $abv=$pays_datas['Abreviation'];
                                                            }else{
                                                                $flag="";  
                                                                $abv="";
                                                            }
                                                            ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                                                            echo '<tr>';
                                                                echo '<td>'.$value['Rang'].'</td>';
                                                                echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].'</a></td>';
                                                                echo '<td>'.$abv.'</td>';
                                                                echo '<td>'.$value['Temps'].'</td>';
                                                            echo '</tr>';
                                                        }
                                                            
                                                    ?>
                                                        
                                                    </tbody>
                                                </table>
                                            <?php } ?>
                                      
                                </li>
                                
                            </ul>
                           
                                
                        </div>
                        <div class="tab-pane fade" id="tab2">

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
            if($vd->getDuree()!=''){
                $duree="<li><span class='material-symbols-outlined'>timer</span>Durée de la vidéo : ".$vd->getDuree()."</li>";
            }
            $res_event="";
            if($vd->getEvenement_id()){
                $evenement=$event->getEvenementByID($vd->getEvenement_id())["donnees"];
                $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();

                $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));

                $res_event= "<a href='/resultats-marathon-".$evenement->getId()."-".slugify($nom_res_lien).".html' class='disp-flex video-res-link mr-5 disp-flex'><span class='material-symbols-outlined'>trophy</span> Résultats </a>";
                $url_img1=str_replace("hqdefault","0",$vd->getVignette());
                $url_img=str_replace("default","0",$url_img1);
            }
            
        echo '<div class="video-align-top video-grid-tab">
            <div class="mr-5"><a href="video-de-marathon-'.$vd->getId().'.html"><div class="video-thumbnail" style="background-image: url('.$url_img.'"></div></a></div>
            <div class="video-t-d-res">
                <ul>
                    <li><a href="video-de-marathon-'.$vd->getId().'.html" class="video_titre">'.$vd->getTitre().'</a></li>'.$duree.'
                </ul>
               
            </div>
        </div>';
        }
    ?>



</ul>

</div>
                            <div class="tab-pane fade" id="tab3">
                                <ul class="photos-tab">
                                    <?php
                                    if(sizeof($photos)!=0){
                                    foreach ($photos as $key => $photo) {
                                        echo '<li><a href="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" class="fancybox" rel="group"><img src="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" width="116" height="auto" alt=""/></a></li>';
                                    } 
                                    }
                                    
                                    
                                    ?>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="tab4">
                            <?php
                            $i=0;
                            foreach($event_news as $index => $article){
                                $subheader="auteur : ".$article->getAuteur()." / ".utf8_encode(strftime("%A %d %B %Y",strtotime($article->getDate())))." / source : ".$article->getSource();
                                    $cat="";

                                    $lien_1="";

                                    $text_lien_1="";

                                    $lien_2="";

                                    $text_lien_2="";

                                    $lien_3="";

                                    $text_lien_3="";

                                    if($cat!="Videos" && $cat!="Photos" ) {$img_src="";}

                                                if($cat=="Photos") $img_src="../../images/pictos/photo-icon.png";

                                                if($cat=="Videos") $img_src="../../images/pictos/video-icon.png";

                                                $cat=switch_cat($cat);

                                                if($article->getLien1()!=""){

                                                    $lien_1=$article->getLien1();

                                                    $text_lien_1="> ".$article->getTextlien1();

                                                }

                                                if($article->getLien2()!=""){

                                                    $lien_2=$article->getLien2();

                                                    $text_lien_2="> ".$article->getTextlien2();

                                                }

                                                if($article->getLien3()!=""){

                                                    $lien_3=$article->getLien3();

                                                    $text_lien_3="> ".$article->getTextlien3();

                                                }

                                                $url_text=slugify($article->getTitre());

                                              
                                            

                                                    $image_src='/images/news/'.substr($article->getDate(), 0,4).'/thumb_'.strtolower($article->getPhoto());

                                                    $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';

                                                    $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                                   
                                                    if($i==0){echo '<article class="row news-mobile-box pt-0">';}else{echo '<article class="news-mobile-box row">';}
                                            

                                                    echo '<div class="article-img"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'</a></div>

                                            

                                                <div class="desc-img">

                                                    <h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #222;" >'.$article->getTitre().' </a></h2>';

                                                    if($article->getChampionID()){
                                                        $chmp=$ch->getChampionById($article->getChampionID())["donnees"];
                                                        echo "<a href='athlete-".$chmp->getId()."-".$chmp->getNom().".html' class='home-link mr-5 '>Le palmarès de ". $chmp->getNom()."</a>";
                                                    }
                                                    
                                                    echo '</div>
                                                </article>';
                                                $i+=1;

                                }







                               

                                ?>
                            </div>
                            
                            <div class="tab-pane fade" id="tab6">
                                
                                <?php if($evById->getParcours_iframe() || $evById->getParcours_image()){ ?>
                            
                       
                        <?php if($evById->getParcours_iframe()){ 
                                echo $evById->getParcours_iframe();?>

                            <?php }else{
                                $img_src='/images/events/'.$evById->getParcours_image();
                                echo '<img class="sp-image parcours-img-source"  style="max-width: 100%;"src="'.$img_src.'"/>';
                                echo '<button class="read-more-button" onclick="full_view(this);">+Voir le parcours en plein écran</button>';
                            }?>
                     <?php }?>
                            </div>
                            
                    
                </div>
            </div>
        </div>

    </div> <!-- End left-side -->

    <aside class="col-sm-4 no-padding-right">
    <? if($next_date){?>
        <div class="box-next-edition bureau">
            
                <div class="center">Vous avez participé au <strong>marathon <? echo $next_date["prefixe"].' '.$evById->getNom().' '.$annee.'</strong> ?';?> </div>
           
            <div class="center">..</div>
            <div class="center">Vous êtes finisher ?</div>
            <div>
            <?php if(empty($_SESSION['user_id'])){ ?>
                <a id="enregistrer_resulat" href="#"  class="call-to-action mx-auto">
                    Enregistrez votre résultat !
                </a>
            <?php }else{ ?>
                <?php if (isset($_SESSION['msg_ajout_resultat'])){?>
                    
                    <div class="modal fade" id="AddResultResponseModal" tabindex="-1" role="dialog" aria-labelledby="AddResultResponseModal" aria-hidden="true">
                    <div class="add-result-box">
                        <?php echo $_SESSION['msg_ajout_resultat'];?>
                        
                    </div>
                </div>
                <?php }?>
                <div class="modal fade" id="AddResultModal" tabindex="-1" role="dialog" aria-labelledby="AddResultModal" aria-hidden="true">
                    <div class="add-result-box">
                        <?php echo '<h1>Ajouter votre résultat au '.$ev_cat_event_int.' '.$next_date["prefixe"].' '.$evById->getNom().' '.$annee.'</h1>';?>
                        <form action="/content/modules/ajouter-resultat.php" enctype="multipart/form-data" method="post" class="form-horizontal"
                            id="target">
                            <input type="hidden" name="c" id="c_id" value="<?php echo $user_champ->getID(); ?>" />
                            <input type="hidden" name="e" id="e_id" value="<?php echo $id; ?>" />
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
                                    <input type="file" id="justificatif" name="j" accept="image/png, image/jpeg, application/pdf," />
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
                <a id="" href="#" data-toggle="modal" data-target="#AddResultModal" class="call-to-action mx-auto">
                    Enregistrez votre résultat !
                </a>
            <?php }?>
                
                
            </div>
            
        </div>
        <? }?>
        <? if($next_date){?>
            <div class="box-next-edition bureau">
                <div class="center">
                    Vous souhaitez accéder à l’ensemble des informations concernant le 
                    <strong>
                     
                            marathon <? echo $next_date["prefixe"].' '.$evById->getNom();?> : 
                       
                        palmarès, record, histoire, 
                        prochaine édition...
                    </strong>
                </div>
                <div>
                    <a class="call-to-action mx-auto" href="/marathons-<? echo $next_date['id'].'-'.slugify($next_date["nom"]).'.html';?>">
                        <span class="material-symbols-outlined">
                            arrow_circle_right
                        </span>
                        C'est par ici
                    </a>
                </div>
            </div>
        <? }?>
        <?php if($results_all_years){?>
            <div class="box-next-edition bureau">
                <div><h3 class="center">Résultats des autres éditions du marathon <? echo $next_date["prefixe"].' '.$evById->getNom();?></h3></div>
                <div>
                <div  class="marathon-detail-others-results">
                                        <?php
                                            foreach ($results_all_years as $key => $resultat) {

                                                $cat_event=$ev_cat_event->getEventCatEventByID($resultat->getCategorieID())['donnees']->getIntitule();
                                                
                                                $pays_flag=$pays->getFlagByAbreviation($resultat->getPaysId())['donnees']['Flag'];
                                                $nom_res=$cat_event.' - '.$resultat->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat->getDateDebut())));
                                                echo '<div class="marathon-detail-others-results-link"><a href="/resultats-marathon-'.$resultat->getID().'-'.slugify($nom_res).'.html">'.substr($resultat->getDateDebut(),0,4).'</a></div>';
                                            }
                                        ?>
                                    </div>
                </div>
                
            </div>
        <?}?>
        <dt class="bref mt-30">
                <h2 class="h2-aside">
                    <span class="material-symbols-outlined ">
                        directions_run
                    </span>
                    Résultats récents
                </h2>
            </dt>

            <dd class="result">

                <ul class="clearfix">

                <?php
                $i=0;
                foreach ($last_results['donnees'] as $result) {   
                    if($i%2==0){
                        $class="gray-background";
                    }else{
                        $class="";
                    }
                    $ev_cat_age_intitule=$ev_cat_age->getEventCatAgeByID($result->getCategorieAgeID())['donnees']->getIntitule();
                    $marathon = getMarathonsById($result->getmarathon_id())["donnees"][0];
                    $marathon_nom = $marathon["nom"];
                    $marathon_prefixe = $marathon["prefixe"];
                    $pays_nom=$pays->getFlagByAbreviation($result->getPaysID())['donnees']['NomPays'];
                    $nom_res=$result->getCategorie().' '.$ev_cat_age_intitule.' ('.$result->getSexe().') - '.$result->getNom().' - '.substr($result->getDateDebut(),0,4);
                    $nom_res_lien_archive=$result->getCategorie().' - '.$result->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($result->getDateDebut())));
                    echo '<a href="/resultats-marathon-'.$result->getID().'-'.slugify($nom_res_lien_archive).'.html"><div class="res-recents row '.$class.'"> <div class="col-lg-3 col-sm-3 pt-10"><span class="res-recents-date"><b>'.date("d/m",strtotime($result->getDateDebut())).'</b></span></div><div class="col-lg-9 col-sm-9"><strong>Marathon '.$marathon_prefixe.' '.$marathon_nom.'</strong><br><span>'. $pays_nom.'</span></div></div></a>';
                    $i++;
                }

                ?>

                    <li class="last mx-auto"><a href="/resultats-marathon.html" class="mx-auto w-fc  blue-btn">Voir tous les résultats</a></li>

                </ul>

            </dd>
            
        </div>
    </aside>

    

    </div> <!-- End container page-content -->


    <?php include_once('footer.inc.php'); 
        if (isset($_SESSION['msg_ajout_resultat'])) {
            unset($_SESSION['msg_ajout_resultat']);
        }
    ?>

    <style type="text/css">

    </style>

    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script  src="../../js/bootstrap.min.js"></script>
    <script type="text/javascript">
$(window).load(function() {
    setTimeout(function(e){ $('#AddResultResponseModal').modal('show'); }, 2000);

});
</script>
    <script data-type="lazy" ddata-src="../../js/plugins.js"></script>
    <script data-type="lazy" ddata-src="../../js/jquery.jcarousel.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/jquery.sliderPro.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/easing.js"></script>
    <script data-type="lazy" ddata-src="../../js/jquery.ui.totop.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/herbyCookie.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/main.js"></script>

   <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    

    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" src="/js/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox -->
    <script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <script type="text/javascript" src="/js/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/jquery.fancybox-media.js?v=1.0.6"></script>

    <script type="text/javascript" src="/js/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <script type="text/javascript">
        function full_view(ele){
			let src=ele.parentElement.querySelector(".parcours-img-source").getAttribute("src");
            console.log("srs lightbox",src)
			document.querySelector("#parcours-img-viewer").querySelector("img").setAttribute("src",src);
			document.querySelector("#parcours-img-viewer").style.display="block";
		}
			
		function close_model(){
			document.querySelector("#parcours-img-viewer").style.display="none";
		}
      
   
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        function getCookie(name) {
            console.log("coco",document.cookie)
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }

        // Lire le cookie "open_add_resulat_modal"
        var open_add_resulat_modal = getCookie("open_add_resulat_modal");

        // Vérifier si le cookie existe et afficher sa valeur
        if (open_add_resulat_modal !== undefined) {
            console.log("La valeur du open_add_resulat_modal monCookie est : " + open_add_resulat_modal);
            $('#AddResultModal').modal('show');
        } else {
            console.log("Le cookie open_add_resulat_modal n'existe pas.");
        }
       
       
        $('#enregistrer_resulat').on('click',function(e){
                $('input[type="checkbox"].openSidebarMenu').prop( "checked", true );
                cname="page_when_logging_to_add_result"
                cvalue=window.location.href
                exdays=1
                //e.preventDefault();
                const d = new Date();
                d.setTime(d.getTime() + (exdays*24*60*60*1000));
                let expires = "expires="+ d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                console.log("Création du cookie: "+cname + "=" + cvalue + ";" + expires)
            });
        $('#finisher').on('click',function(e){
                $('#SigninModal').modal('show');});
                    
			
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
       
        $('#ajouter-resultat').on('click', function(e) {
            cname="page_when_adding_result"
            cvalue=window.location.href
            exdays=1
            //e.preventDefault();
            const d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            console.log("Création du cookie: "+cname + "=" + cvalue + ";" + expires)

        });
            $('#tableauHommes').DataTable( {
                paging: false,
                bFilter: false,
                bSort: false,
                searching: true,
                dom: 't'   
        } );
        $('#tableauFemmes').DataTable( {
            paging: false,
                bFilter: false,
                bSort: false,
                searching: true,
                dom: 't'   
        } );
        
        $('#genre').hide()
        //console.log("genre: ",$('#genre').text())
        if($('#genre').text()=="MF"){
            $('#tableauFemmes_wrapper').hide();
            $('#tableauHommes_wrapper').show();
        }
        $('#res-hommes').addClass("active");
        $('#res-hommes').click( function() {
           // console.log($(this).val())
            //location.href = window.location.pathname.replace('-mf-',$(this).val());
                $('#tableauHommes_wrapper').toggle();
                $('#tableauFemmes_wrapper').toggle();
                $(this).addClass("active");
                $('#res-femmes').removeClass("active")
        });
        $('#res-femmes').click( function() {
            //console.log($(this).val())
            //location.href = window.location.pathname.replace('-mf-',$(this).val());
                $('#tableauHommes_wrapper').toggle();
                $('#tableauFemmes_wrapper').toggle();
                $(this).addClass("active");
                $('#res-hommes').removeClass("active")
        });
        $('.dataTables_filter input[type="search"]').attr('placeholder', 'Trouver un Athlète');
        
         
        
   
    });
   
    </script>


    
</body>

</html>