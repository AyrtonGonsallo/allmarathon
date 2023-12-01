<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

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
}  else $user_session='';

require("../classes/news.php");
include("../classes/annonce.php");
include("../classes/resultat.php");
include("../classes/video.php");
include("../classes/evenement.php");
include("../classes/newscategorie.php");
include("../classes/evCategorieAge.php");
include("../classes/evCategorieEvenement.php");
include("../classes/pub.php");

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("accueil")['donnees'];
$pub300x60=$pub->getBanniere300_60("accueil")['donnees'];
$pub300x250=$pub->getBanniere300_250("accueil")['donnees'];
$pub160x600=$pub->getBanniere160_600("accueil")['donnees'];


$news=new news();
$last_news=$news->getLastNews();
$bref_news=$news->getBrefNews();
$last_articles_part1=$news->news_home(0);
$last_articles_part2=$news->news_home(2);



$annonce=new annonce();
$last_annonces=$annonce->get_last_annonces();

$resultat=new resultat();
$last_results=$resultat->getLastResults();

$vd=new video();
$last_videos=$vd->getLastVideos();

$ev_cat_age=new evCategorieAge();

$ev_cat_event=new evCategorieEvenement();

$event=new evenement();
$home_events=$event->getHomeEvents();
$home_events_pack=$event->getHomeEventsPack();

$nc=new newscategorie();

function slugify($text)
{
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
?>
<style type="text/css">
a#search_2 {
    margin-top: 26px !important;
}

input.form-control.search_header {
    margin-bottom: 17px !important;
}
</style>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Marathon, l'actualité sur ALL MARATHON : résultats, vidéos, photos, athlètes</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content">
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
                        <div class="slider-pro" id="my-slider">
                            <div class="sp-slides">

                                <?php
                            foreach($last_news['donnees'] as $article)
                                {
                                    $tab = explode('-',$article->getDate());
                                    $yearNews  = $tab[0];
                                    $lien_1="";$lien_2="";$lien_3="";
                                    if($article->getLien1()!=""){
                                        $lien_1="<br><a href='".$article->getLien1()."' style='color: #fee616;font-size:13px;font-weight:normal;display: block;padding-top: 9px;'> > ".$article->getTextlien1()."</a>";
                                    }
                                    if($article->getLien2()!=""){
                                         $lien_2="<a href='".$article->getLien2()."' style='color: #fee616;font-size:13px;font-weight:normal;'> > ".$article->getTextlien2()."</a>";
                                    }
                                    if($article->getLien3()!=""){
                                        $lien_3="<br><a href='".$article->getLien3()."' style='color: #fee616;font-size:13px;font-weight:normal;'> > ".$article->getTextlien3()."</a>";
                                    }
                                   echo  '<div class="sp-slide">
                                            <img class="sp-image" src="../../images/news/'.$yearNews.'/'.$article->getPhoto().'"/>
                                            <h1 class="sp-layer sp-black sp-padding"
                                                data-position="bottomLeft" data-width="100%" data-vertical="0"
                                                data-show-transition="bottom" data-show-delay="300" data-hide-transition="right" style="padding-bottom:19px;">

                                                <a href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html">'.$article->getTitre().'</a><br>
                                                <span>'.$article->getChapo().'</span>
                                                '.$lien_1.$lien_2.$lien_3.'
                                            </h1>

                                            <img class="sp-thumbnail" src="../../images/news/'.$yearNews.'/'.$article->getPhoto().'"/>
                                        </div>';
                                }
                            ?>

                            </div>
                        </div>
                        <br />
                    </div>

                </div>
                <br>
                <?php
                foreach ($last_articles_part1['donnees'] as $article_home) {
                $cat=$nc->getNewsCategoryByID($article_home->getCategorieID())['donnees']->getIntitule();
                if($cat!="Videos" && $cat!="Photos" ) {$img_src="";}
                if($cat=="Photos") $img_src="../../images/pictos/photo-icon.png";
                if($cat=="Videos") $img_src="../../images/pictos/video-icon.png";
                $tab = explode('-',$article_home->getDate());
                $yearNews  = $tab[0];
                $lien_1="";$lien_2="";$lien_3="";
                if($article_home->getLien1()!=""){
                    $lien_1="<a href='".$article_home->getLien1()."'style='color: #fcb614;'> > ".$article_home->getTextlien1()."</a><br>";
                }
                if($article_home->getLien2()!=""){
                     $lien_2="<a href='".$article_home->getLien2()."'style='color: #fcb614;'> > ".$article_home->getTextlien2()."</a><br>";
                }
                if($article_home->getLien3()!=""){
                    $lien_3="<a href='".$article_home->getLien3()."'style='color: #fcb614;'> > ".$article_home->getTextlien3()."</a>";
                }
                echo '<article class="row"style="margin-bottom: 25px;">
                <div class="col-sm-5">
                    <div class="article-img"><a href="/actualite-marathon-'.$article_home->getId().'-'.slugify($article_home->getTitre()).'.html">
                    <img src="../../images/news/'.$yearNews.'/'.$article_home->getPhoto().'" style="margin-bottom: -25px;" alt="" class="img-responsive">
                    <div class="col-sm-12" style="padding:0;">
                    <strong style="background:red;">'.switch_cat($cat).'</strong>
                    </div>
                    <img class="media_index" src="'.$img_src.'" alt=""></a></div>
                </div>
                <div class="col-sm-7 desc-img">
                    <h2> <a href="/actualite-marathon-'.$article_home->getId().'-'.slugify($article_home->getTitre()).'.html" style="color: #222;">'.$article_home->getTitre().'</a> </h2>
                    <p>'.$article_home->getChapo().'</p>
                    '.$lien_1.$lien_2.$lien_3.'
                </div>
            </article>';
                }
            ?>

                <dt class="actu-video">L’ACTUALITE DU JUDO EN VIDEO</dt>
                <dd class="actu-video">

                    <a href="#" class="navig prev"><i class="fa fa-chevron-left"></i></a>
                    <div class="jcarousel">
                        <ul class="clearfix">

                            <?php
                        foreach ($last_videos['donnees'] as $video) {
                        $eventByID=$event->getEvenementByID($video->getEvenement_id())['donnees'];
                         echo '<li><a href="/video-de-marathon-'.$video->getId().'.html"><img src="'.$video->getVignette().'" class="img-responsive" style ="width: 181px;height: 122px;" alt=""><img class="media" src="../../images/pictos/actu-video-icon.png" alt=""><strong>'.$video->getTitre().'<br>
                             '.$eventByID->getNom().'  '.substr($eventByID->getDateDebut(), 0, 4).'</strong></a></li>';
                        }
                        ?>
                        </ul>
                    </div>
                    <a href="#" class="navig next"><i class="fa fa-chevron-right"></i></a>

                </dd>

                <br><br>


                <?php
foreach($last_articles_part2['donnees'] as $article){
    $cat=$nc->getNewsCategoryByID($article->getCategorieID())['donnees']->getIntitule();
    if($cat!="Videos" && $cat!="Photos" ) {$img_src="";}
                if($cat=="Photos") $img_src="../../images/pictos/photo-icon.png";
                if($cat=="Videos") $img_src="../../images/pictos/video-icon.png";
                $tab = explode('-',$article->getDate());
                $yearNews  = $tab[0];
                $lien_1="";$lien_2="";$lien_3="";
                if($article->getLien1()!=""){
                    $lien_1="<a href='".$article->getLien1()."'style='color: #fcb614;'> > ".$article->getTextlien1()."</a><br>";
                }
                if($article->getLien2()!=""){
                     $lien_2="<a href='".$article->getLien2()."'style='color: #fcb614;'> > ".$article->getTextlien2()."</a><br>";
                }
                if($article->getLien3()!=""){
                    $lien_3="<a href='".$article->getLien3()."'style='color: #fcb614;'> > ".$article->getTextlien3()."</a>";
                }
                echo '<article class="row">
                <div class="col-sm-5">
                    <div class="article-img" style="margin-bottom: 25px;">
                    <a href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html">
                    <img src="../../images/news/'.$yearNews.'/'.$article->getPhoto().'" alt="" class="img-responsive" style="margin-bottom: -25px;">
                    <div class="col-sm-12" style="padding:0;">
                    <strong style="background:red;">'.switch_cat($cat).'</strong>
                    </div>
                    <img class="media_index" src="'.$img_src.'" alt=""></a></div>
                </div>
                <div class="col-sm-7 desc-img">
                    <h2><a href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html" style="color: #222;">'.$article->getTitre().'</a></h2>
                    <p>'.$article->getChapo().'</p>
                    '.$lien_1.$lien_2.$lien_3.'
                </div>
            </article>';
}
?>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <!--   <p class="ban"><a href=""><?php //echo $pub300x60; ?></a></p>
 <br> -->
                <dt class="bref">EN BREF</dt>
                <dd class="bref marg_bot">
                    <ul class="clearfix">
                        <?php
                    foreach ($bref_news['donnees'] as $article_bref) {
                    echo '<li><a href="/actualite-marathon-'.$article_bref->getId().'-'.slugify($article_bref->getTitre()).'.html"><span>'.date("d-m", strtotime($article_bref->getDate())).'</span>&nbsp;
'.$article_bref->getTitre().'</a></li>';
                    }
                    ?>
                        <li class="last"><a href="/actualites-marathon-11--.html">[+] de brèves</a></li>
                    </ul>
                </dd>

                <p class="ban"><a href=""><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <dt class="result marg_top">Les derniers résultats</dt>
                <dd class="result marg_bot">
                    <ul class="clearfix">
                        <?php
                    // <img src="img/logo-champ1.png" alt="">
                    foreach ($last_results['donnees'] as $result) {
                     $ev_cat_age_intitule=$ev_cat_age->getEventCatAgeByID($result->getCategorieAgeID())['donnees']->getIntitule();
                     $nom_res=$result->getCategorie().' '.$ev_cat_age_intitule.' ('.$result->getSexe().') - '.$result->getNom().' - '.substr($result->getDateDebut(),0,4);
                     echo '<li><a href="/resultats-marathon-'.$result->getID().'-'.slugify($nom_res).'.html"> <strong>'.$result->getCategorie().' - '.$result->getNom().' <small>'.$ev_cat_age_intitule.' - le '.date("d/m/Y",strtotime($result->getDateDebut())).' - '.$result->getType().'</small></strong></a></li>';
                    }
                    
                    ?>
                        <li class="last"><a href="/resultats-marathon.html">[+] de résultats</a></li>
                    </ul>
                </dd>

                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <dt class="calendar marg_top">CALENDRIER</dt>
                <dd class="calendar marg_bot">
                    <ul class="clearfix">
                        <?php
                    $tab_pack_a_afficher = array();
                    $indice=0;
                    foreach ($home_events_pack['donnees'] as $ev) {
                        $ev_intitule=$ev_cat_event->getEventCatEventByID($ev->getCategorieId())['donnees']->getIntitule();
                        $ev_cat_age_intitule=$ev_cat_age->getEventCatAgeByID($ev->getCategorieageID())['donnees']->getIntitule();
                        $tab_indice=array('id'=>$ev->getId(),'intitule'=>$ev_intitule,'cat_age'=>$ev_cat_age_intitule,'ville'=>$ev->getNom(),'date'=>$ev->getDateDebut(),'type'=>$ev->getType(),'compteur'=>$ev->getCompteur());
                        $tab_pack_a_afficher=$event->search_array($tab_pack_a_afficher,$tab_indice);
                    }
                
                     foreach ($tab_pack_a_afficher as $ev) {
                        $timestamp=strtotime($ev['date']);
                        echo '<li class="active"><a href="/dossier_presentation-'.$ev['id'].'.html" target="_blank"><span class="calendar"><i>'.date("M",$timestamp ).'</i>'. date("d",$timestamp ).'</span> <strong>'.$ev['intitule'].' - '.$ev['ville'].' <small>'.$ev['cat_age'].' - le '. date("d/m/Y",$timestamp ).' - '.$ev['type'].'</small></strong></a></li>';

                    }
                    ?>

                        <?php
                    $tab_a_afficher=array();
                    foreach ($home_events['donnees'] as $ev) {
                        $ev_intitule=$ev_cat_event->getEventCatEventByID($ev->getCategorieId())['donnees']->getIntitule();
                        $ev_cat_age_intitule=$ev_cat_age->getEventCatAgeByID($ev->getCategorieageID())['donnees']->getIntitule();
                        $tab_indice=array('id'=>$ev->getId(),'intitule'=>$ev_intitule,'cat_age'=>$ev_cat_age_intitule,'ville'=>$ev->getNom(),'date'=>$ev->getDateDebut(),'type'=>$ev->getType(),'compteur'=>$ev->getCompteur());
                        $tab_a_afficher=$event->search_array($tab_a_afficher,$tab_indice);
                    }
                    ?>
                        <?php
                    foreach ($tab_a_afficher as $ev) {
                        $timestamp=strtotime($ev['date']);
                        echo '<li ><a  href="/calendrier-marathon.html"><span class="calendar"><i>'.date("M",$timestamp ).'</i>'. date("d",$timestamp ).'</span> <strong>'.$ev['intitule'].' - '.$ev['ville'].' <small>'.$ev['cat_age'].' - le '. date("d/m/Y",$timestamp ).' - '.$ev['type'].'</small></strong></a></li>';

                    }
                    ?>
                        <li class="last"><a href="/calendrier-marathon.html">[+] de dates</a></li>
                    </ul>
                </dd>

                <dt class="calendar marg_top">PETITES ANNONCES</dt>
                <dd class="calendar marg_bot">
                    <ul class="clearfix">
                        <?php
                    foreach ($last_annonces['donnees'] as $annonce_details) {
                        echo '<li><a href=""><strong>'.$annonce_details->getTitre().' <small>publiée le '. date("d/m/Y", strtotime($annonce_details->getDate_publication())).'</small></strong></a></li>';
                    }

                    ?>
                        <li class="last"><a href="/petites-annonces-marathon-gratuites.html">[+] d’annonces</a></li>
                        <li class="last"><a href="/ajouterannonce.php">[+] publier une annonce</a></li>
                    </ul>
                </dd>
                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
                <dd class="facebook">
                    <div class="fb-page" data-href="https://www.facebook.com/allmarathonnet-108914759155897/"
                        data-width="310" data-hide-cover="false" data-show-facepile="true">
                    </div>
                </dd> -->
                <br>

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
    <script src="../../js/main.js"></script>




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