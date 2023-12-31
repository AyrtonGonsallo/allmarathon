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

include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/evCategorieAge.php");
include("../classes/video.php");
include("../classes/championAdminExterneClass.php");
include("../classes/resultat.php");
include("../classes/news.php");
include("../classes/pub.php");
include("../classes/pays.php");
include("../classes/user.php");
include("../classes/evresultat.php");
include("../classes/champion.php");
include("../classes/evequipe.php");
include("../modules/functions.php");
$user=new user();
$evequipe=new evequipe();

$ch=new champion();

$pays=new pays();

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

$club =$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getClub();
($club) ? $classement="clubs" : $classement="pays";

$type = $evById->getSexe();
$active_tab1="active";
        $active_tab2="";

$destination_path  = "/uploadDocument/";
$ev_cat_event_int_titre=$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule();
$ev_cat_age_int_titre=$ev_cat_age->getEventCatAgeByID($evById->getCategorieageID())['donnees']->getIntitule();
$annee_titre=substr($evById->getDateDebut(), 0, 4);

function slugify($text)
{
    $text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
$text = preg_replace('/[^\pL\d]+/u', '-', $text); 
$text = trim($text, '-');
$text = strtolower($text);
return $text;
}
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
    <title>Résultats du marathon<?php echo $evById->getPrefixe();?> <?php echo str_replace('\\','',$evById->getNom());?> - <?php echo $pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];?> - <?php echo $ev_cat_event_int_titre;?> <?php echo $annee_titre;?></title>
    <meta name="Description" content="Le <?php echo $ev_cat_event_int_titre;?> <?php echo $annee_titre;?> de <?php echo $evById->getNom();?> (<?php echo $pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];?>) a eu lieu le <?php echo changeDate($evById->getDateDebut());?>. Les vainqueurs sont <?php echo  $evresultat->getResultBySexe($id,"M")['donnees'][0]['Nom'];?> (hommes) et <?php echo  $evresultat->getResultBySexe($id,"F")['donnees'][0]['Nom'];?> (femmes). Résultats complets, classements et temps." lang="fr" xml:lang="fr" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:title" content="Résultats du marathon<?php echo $evById->getPrefixe();?> <?php echo str_replace('\\','',$evById->getNom());?> - <?php echo $pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];?> - <?php echo $ev_cat_event_int_titre;?> <?php echo $annee_titre;?>" />
    <meta property="og:description" content="Le <?php echo $ev_cat_event_int_titre;?> <?php echo $annee_titre;?> de <?php echo $evById->getNom();?> (<?php echo $pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];?>) a eu lieu le <?php echo changeDate($evById->getDateDebut());?>. Les vainqueurs sont <?php echo  $evresultat->getResultBySexe($id,"M")['donnees'][0]['Nom'];?> (hommes) et <?php echo  $evresultat->getResultBySexe($id,"F")['donnees'][0]['Nom'];?> (femmes). Résultats complets, classements et temps." />
    <meta property="og:image" content="<?php echo 'https://allmarathon.fr/images/marathons/'.$next_date['image'];?>" />
    <meta property="og:url" content="<?php echo 'https://allmarathon.fr/resultats-marathon-'.$evById->getId().'-'.slugify($ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule()).'-'.slugify($evById->getNom()).'-'.slugify(changeDate($evById->getDateDebut())).'.html';?>" />


    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
  
    <?php echo '<link rel="canonical" href="https://allmarathon.fr/resultats-marathon-'.$evById->getId().'-'.slugify($ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule()).'-'.slugify($evById->getNom()).'-'.slugify(changeDate($evById->getDateDebut())).'.html" />';?>

  
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    


    <link rel="stylesheet" href="../../css/responsive.css">

</head>

<body>



    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlete-detail">
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
            <div class="col-sm-8 left-side resultat-detail">


                <div class="row">
                    <div class="col-sm-12">
                        <?php 
                            $pays_intitule=$pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];
                            $ev_cat_event_int=$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule();
                            $ev_cat_age_int=$ev_cat_age->getEventCatAgeByID($evById->getCategorieageID())['donnees']->getIntitule();
                            $annee=substr($evById->getDateDebut(), 0, 4);
                            $date_debut=changeDate($evById->getDateDebut());
                            $pays_datas=$pays->getFlagByAbreviation($evById->getPaysId())['donnees'];
                            if($pays_datas){
                                $flag=$pays_datas['Flag'];  
                            }
                            ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                            
                            echo '<h1>RÉSULTATS DU '.strtoupper($ev_cat_event_int).' '.mb_strtoupper($evById->getNom()).' '.$annee.' ('.strtoupper($pays_intitule).') <span class="date">'.$date_debut.'</span></h1>';
                            if($next_date){
                                echo '<a class="page-event-marathon-link" href="/marathons-'.$next_date['id'].'-'.slugify($next_date["nom"]).'.html"> Toutes les informations : prochaine édition, palmarès, record du Marathon '.$next_date["nom"].'</a>';
                            }
                            ?>
                    <div class="mb-50"></div>


                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <?php echo '<li class="'.$active_tab1.'"><a href="#tab1" role="tab" data-toggle="tab">Résultats</a></li>'; ?>
                            <?php if($event_news){?>
                                <li><a href="#tab4" role="tab" data-toggle="tab">ACTUS
                                        (<?php echo sizeof($event_news) ; ?>)</a></li>
                            <?php }
                             if(!($evById->getType()=="Parent")){?>
                                <li><a href="#tab2" role="tab" data-toggle="tab">VIDEOS
                                        (<?php echo sizeof($videos) ; ?>)</a></li>
                                <li><a href="#tab3" role="tab" data-toggle="tab">PHOTOS
                                        (<?php echo sizeof($photos) ; ?>)</a></li>
                                
                            <?php }?>
                            
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">

                            <?php ($active_tab1!="") ? $cl_fd_tab1="active fade in" : $cl_fd_tab1="fade";
                        echo '<div class="'.$cl_fd_tab1.' tab-pane" id="tab1">';
                        
                            ?>
                            <ul class="row">
                                <!--<div class="col-12 resultat_shared">
                                    <?php //include_once("shareButtons.php"); ?>
                                </div>-->

                                
                                <li class="col-sm-12"><?php echo '<div id="genre">'.$type.'</div>';?>
                                    <br />
                                    <?php if($type=="MF"){ ?>
                                        <select id="categorie" style="height:34px">
                                            <option value="-h-">Hommes</option>
                                            <option value="-f-">Femmes</option>
                                        </select>
                                    <?php } ?>
                                    
                                    <?php if($evById->getDocument1()!='') echo '<a class="btn results-buttons" href="PDF_frame-'.rawurlencode($evById->getDocument1()).'" target="_blank" class="btn btn-default"><i class="fa-file-pdf-o fa"></i>&nbspPDF</a>';  ?>
                                    <?php if($evById->getlien_resultats_complet()) echo '<a class="btn results-buttons" href="'.$evById->getlien_resultats_complet().'" target="_blank" class="btn btn-default"><img width="16px" src="../../images/redirect.png" alt="tout voir">&nbspRésultats complets</a>';  ?>
                                    <?php if($isAdmin) echo '<a class="btn results-buttons" href="evenement-detail-admin-'.$id.'.html" ><img width="13px" src="../../images/pictos/finisher.png" alt="finisher"> Je suis finisher</a>';  ?>
                                    <?php if(!$isAdmin) echo '<a class="btn results-buttons" href="#" id="finisher"><img width="13px" src="../../images/pictos/finisher.png" alt="finisher"> Je suis finisher</a>';  ?>

                                   
                                    <?php if(($type=="MF") || ($type=="M")){ ?>
                                        <table id="tableauHommes" data-page-length='25' class="display">
                                            <thead>
                                                <tr>
                                                    <th style="text-transform: capitalize;">clt</th>
                                                    <th style="text-transform: capitalize;">pays</th>
                                                    <th style="text-transform: capitalize;">coureur</th>
                                                    <th style="text-transform: capitalize;">temps</th>
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
                                                    }
                                                    ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span><br>':$pays_flag="";
                                                    echo '<tr>';
                                                        echo '<td>'.$value['Rang'].'</td>';
                                                        echo '<td>'.$pays_flag.' '.$pays_datas['NomPays'].'</td>';
                                                        echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].'</a></td>';
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
                                                    <th style="text-transform: capitalize;">clt</th>
                                                    <th style="text-transform: capitalize;">pays</th>
                                                    <th style="text-transform: capitalize;">coureur</th>
                                                    <th style="text-transform: capitalize;">temps</th>
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
                                                    }
                                                    ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                                                    echo '<tr>';
                                                        echo '<td>'.$value['Rang'].'</td>';
                                                        echo '<td>'.$pays_flag.' '.$pays_datas['NomPays'].'</td>';
                                                        echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].'</a></td>';
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
            ($vd->getDuree()!='') ?  $duree="<li>durée : ".$vd->getDuree()."</li>" : $duree="<li style='list-style-type: none;'></li>";
        ($vd->getTop_ippon()) ? $img_top ='<img src="../../images/pictos/badge.png" style="right: 19px;top: 3px;position: absolute;"alt=""/>' : $img_top="";
            echo '<li class="row">
            <ul>
                <li class="col-sm-6">
                    <ul>
                        <li><a href="video-de-marathon-'.$vd->getId().'.html">'.$vd->getTitre().'</a></li>
                        '.$duree.'
                        <li>publiée le '.date("d/m/y", strtotime($vd->getDate())).'</li>
                    </ul>
                </li>
                <li class="col-sm-6"><a href="video-de-marathon-'.$vd->getId().'.html"><img src="'.$vd->getVignette().'" alt="" style="width: 173px;"class="pull-right img-responsive"/>'.$img_top.'</a></li>

            </ul>
        </li>';
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

                                foreach($event_news as $index => $article){

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

                                                        $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'" width="173px"/>';

                                                        echo '<article class="row event-news">
                                                        <div class="col-sm-7 ">

                                                            <a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" class="event-news-title">'.$article->getTitre().' </a>

                                                            <p>'.$article->getChapo().'</p>

                                                            <a href="'.$lien_1.'" class="link-all"> '.$text_lien_1.'</a><br>

                                                            <a href="'.$lien_2.'" class="link-all"> '.$text_lien_2.'</a><br>

                                                            <a href="'.$lien_3.'" class="link-all"> '.$text_lien_3.'</a>

                                                        </div>
                                                        <div class="col-sm-5">

                                                            <div class="article-img" style="display: grid;justify-content: end;"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'<strong>'.$cat.'</strong></a></div>

                                                        </div>

                                                        

                                                        </article>';

                                                    

                                                    

                                    }

                                ?>
                            </div>
                            
                    
                </div>
            </div>
        </div>

    </div> <!-- End left-side -->

    <aside class="col-sm-4">
        <p class="ban"><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x60['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
        <dt class="archive" style="background-color: #fbff0b;">Derniers résultats enregistrés</dt>
        <dd class="archive">
            <ul class="clearfix">
                <?php
                        foreach ($archives['donnees'] as $key => $ev_archive) {
                            $cat_event=$ev_cat_event->getEventCatEventByID($ev_archive->getCategorieId())['donnees']->getIntitule();
                            if($ev_archive->getType()=="Equipe"){
                                $type_event= " par équipes";
                            }
                            else{
                                $type_event=""; 
                            }
                            $nom_res=$cat_event." ".$type_event." (".$ev_archive->getSexe().") ".$ev_archive->getNom()." ".substr($ev_archive->getDateDebut(),0,4);
                            $nom_res_lien_archive=$cat_event.' - '.$ev_archive->getNom().' - '.strftime("%A %d %B %Y",strtotime($ev_archive->getDateDebut()));

                            echo '<li><a href="/resultats-marathon-'.$ev_archive->getId().'-'.slugify($nom_res_lien_archive).'.html">'.$nom_res.'</a></li>';
                        }
                    ?>
            </ul>
        </dd>
        <div class="marg_bot"></div>
        <p class="ban"><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
        <div class="marg_bot"></div>
        <p class="ban ban_160-600"><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<a href=". $pub160x600['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
        <div class="marg_bot"></div>
        


    </aside>
    </div>

    </div> <!-- End container page-content -->


    <?php include_once('footer.inc.php'); ?>

    <style type="text/css">

    </style>

    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script data-type="lazy" ddata-src="../../js/bootstrap.min.js"></script>
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
    $(document).ready(function() {
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
    });
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        
            $('#tableauHommes').DataTable( {
                language: {
                    processing:     "Traitement en cours...",
                    search:         "",
                    lengthMenu: '<select>'+
                    '<option value="10">10 lignes</option>'+
                    '<option value="25" >25 lignes</option>'+
                    '<option value="50">50 lignes</option>'+
                    '<option value="100">100 lignes</option>'+
                    '</select>',
                    info:           "Affichage des &eacute;lements _START_ &agrave; _END_",
                    infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 lignes",
                    infoFiltered:   "(filtr&eacute; de _MAX_ lignes au total)",
                    infoPostFix:    "",
                    loadingRecords: "Chargement en cours...",
                    zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                    emptyTable:     "Aucune donnée disponible dans le tableau",
                    paginate: {
                        first:      "Premier",
                        previous:   "Pr&eacute;c&eacute;dent",
                        next:       "Suivant",
                        last:       "Dernier"
                    },
                    aria: {
                        sortAscending:  ": activer pour trier la colonne par ordre croissant",
                        sortDescending: ": activer pour trier la colonne par ordre décroissant"
                    }
                }
        } );
        $('#tableauFemmes').DataTable( {
            language: {
                processing:     "Traitement en cours...",
                search:         "",
                lengthMenu: '<select>'+
                '<option value="10">10 lignes</option>'+
                '<option value="25">25 lignes</option>'+
                '<option value="50">50 lignes</option>'+
                '<option value="100">100 lignes</option>'+
                '</select>',
                info:           "Affichage des &eacute;lements _START_ &agrave; _END_",
                infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 lignes",
                infoFiltered:   "(filtr&eacute; de _MAX_ lignes au total)",
                infoPostFix:    "",
                loadingRecords: "Chargement en cours...",
                zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                emptyTable:     "Aucune donnée disponible dans le tableau",
                paginate: {
                    first:      "Premier",
                    previous:   "Pr&eacute;c&eacute;dent",
                    next:       "Suivant",
                    last:       "Dernier"
                },
                aria: {
                    sortAscending:  ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            }
        } );
        
        $('#genre').hide()
        //console.log("genre: ",$('#genre').text())
        if($('#genre').text()=="MF"){
            $('#tableauFemmes_wrapper').hide();
            $('#tableauHommes_wrapper').show();
        }
        $('#categorie').change( function() {
            console.log($(this).val())
            //location.href = window.location.pathname.replace('-mf-',$(this).val());
            if($(this).val()=="-m-"){
                $('#tableauFemmes_wrapper').toggle();
                $('#tableauHommes_wrapper').toggle();
            }else{
                $('#tableauHommes_wrapper').toggle();
                $('#tableauFemmes_wrapper').toggle();
            }
        });
        $('.dataTables_filter input[type="search"]').attr('placeholder', 'Trouver un coureur');
        /*$('#tableauHommes').DataTable({
        lengthMenu: [
            [10, 25, 50, 100],
            ['10 lignes', '25 lignes', '50 lignes', '100 lignes'],
        ],
    });*/
    });
    </script>


    
</body>

</html>