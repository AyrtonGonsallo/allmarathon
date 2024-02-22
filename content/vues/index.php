<!DOCTYPE html>
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
include("../classes/marathon.php");
include("../classes/resultat.php");
include("../classes/video.php");
include("../classes/evenement.php");
include("../classes/newscategorie.php");
include("../classes/evCategorieAge.php");
include("../classes/champion.php");
include("../classes/pub.php");
include("../classes/commentaire.php");

$commentaire=new commentaire();

$news=new news();
$champion=new champion();

$last_news=$news->getLastNews();
$pays=new pays();
$bref_news=$news->getBrefNews();

$last_articles_part1=$news->news_home(0);

$last_articles_part2=$news->news_home(1);

$both_news=$news->getBootNews();

$resultat=new resultat();

$last_results=$resultat->getLastResults();

$vd=new video();

$last_videos=$vd->getLastVideos();

$ev_cat_age=new evCategorieAge();

$ev_cat_event=new evCategorieEvenement();

$event=new evenement();

$home_events=getThisMonthEvents();


$nc=new newscategorie();
setlocale(LC_TIME, "fr_FR","French");


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

<html class="no-js" lang="fr">
<head>
    
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Suivez l’actualité du marathon : calendrier, résultats, vidéos | allmarathon.fr</title>
    <meta name="description" content="allmarathon, toutes les informations concernant les marathons en France et dans le monde. News, calendrier, résultats, vidéos. Pour les passionnés des 42 kms.">
    
    <link rel="canonical" href="https://allmarathon.fr" />
    <meta property="og:title" content=" Suivez l’actualité du marathon : calendrier, résultats, vidéos | allmarathon.fr " />
    <meta property="og:description" content="allmarathon, toutes les informations concernant les marathons en France et dans le monde. News, calendrier, résultats, vidéos. Pour les passionnés des 42 kms." />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:image" content="https://allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="https://allmarathon.fr" />
    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/main.css?ver=<?php echo rand(111,999)?>">
    <link rel="stylesheet" href="../../css/responsive.css">
    <?php require_once("../scripts/header_script.php") ?>
    <style>
    #liste {
        min-height: 100vh
    }
    #liste .text-gray {
        color: #aaa
    }
    #liste img {
        height: 170px;
        width: 140px
    }
    </style>
</head>
<body>
    <?php include_once('nv_header-integrer.php'); ?>
    <?php $i=0;
        $article=$last_news['donnees'][0];
        $tab = explode('-',$article->getDate());
        $yearNews  = $tab[0]; ?>
        <div class="news_alune mt-77">
            <div class="image_news">
                <?php $alt = ($article->getLegende())?'alt="'.$article->getLegende().'"':'alt="allmarathon news image"';
                    echo '<a href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html">
                    <img class="img-responsive mobile"'.$alt.'src="../../images/news/'.$yearNews.'/'.$article->getPhoto().'" /></a>' ?>
            </div>            
        </div>

                    
    <div class="container page-content homepage">
        <div class="row banniere1 bureau">
        </div>
        <div class="row">

            <div class="col-sm-8 left-side">
            
                <section class="articles_alune">

                    <?php
                            $i=0;
                            foreach($last_news['donnees'] as $article)

                                {
                                $subheader="auteur : ".$article->getAuteur()." / ".utf8_encode(strftime("%A %d %B %Y",strtotime($article->getDate())))." / source : ".$article->getSource();
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

                                    ?>

                    <div class="news_alune">
                    <?php //echo $subheader; ?>
                        <div class="image_news">

                            <?php $alt = ($article->getLegende())?'alt="'.$article->getLegende().'"':'alt="allmarathon news image"';
                            if($i==0){
                                $display="bureau";
                            }else{
                                $display="";
                            }
                            echo '<a

                                                    href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html"><img

                                                        class="img-responsive '.$display.'"'.$alt.'

                                                        src="../../images/news/'.$yearNews.'/'.$article->getPhoto().'" /></a>' ?>

                        </div>            

                        <div class="title_news <?php echo ($i==0)?'mb-70':'';?>  <?php echo ($i>0)?'mt-20':'';?>">

                            <?php echo '<a href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html">'.$article->getTitre().'</a><br>'; 

                echo '<p class="style-p bureau">'.$article->getChapo().'</p>

               '

                ;
                if($article->getVideoID()){
                    $vid=$vd->getVideoById($article->getVideoID())["donnees"];
                    echo "<a href='video-de-marathon-".$vid->getId().".html'  class='home-link mr-5'>Vidéo : ". $vid->getTitre()."</a>";
                }
                            ?>

                        </div>

                    </div>

                    <?php  
                        $i++;
                } ?>

                </section>

                <section class="last_articles_part1 to_hide_mobile ">

                    <?php

                            foreach ($last_articles_part1['donnees'] as $article_home) {

                                $cat='';

                                if($cat!="Videos" && $cat!="Photos" ) {$img_src="";}

                                if($cat=="Photos") $img_src="../../images/pictos/photo-icon.png";

                                if($cat=="Videos") $img_src="../../images/pictos/video-icon.png";

                                $tab = explode('-',$article_home->getDate());

                                $yearNews  = $tab[0];

                                $lien_1="";$lien_2="";$lien_3="";

                            if($article_home->getLien1()!=""){

                                $lien_1="<a href='".$article_home->getLien1()."' class='home-link'> > ".$article_home->getTextlien1()."</a><br>";

                            }

                            if($article_home->getLien2()!=""){

                                $lien_2="<a href='".$article_home->getLien2()."' class='home-link'> > ".$article_home->getTextlien2()."</a><br>";

                            }

                            if($article_home->getLien3()!=""){

                                $lien_3="<a href='".$article_home->getLien3()."' class='home-link'> > ".$article_home->getTextlien3()."</a>";

                            }

                            $nb=sizeof($commentaire->getCommentaires($article_home->getId(),0,0)['donnees']);

                            $nb=($nb!=0)? $nb: '';
                            $alt = ($article_home->getLegende())?'alt="'.$article_home->getLegende().'"':'alt="allmarathon news image"';

                            $coms=' <a href="/actualite-marathon-'.$article_home->getId().'-'.slugify($article_home->getTitre()).'.html#commentaires" title="Ajouter un commentaire" class="com_news"><i class="fa fa-comment-o com_news" aria-hidden="true"></i> '.$nb.'</a>';

                            echo '<article class="row" >

                                <div class="article-img"><a href="/actualite-marathon-'.$article_home->getId().'-'.slugify($article_home->getTitre()).'.html">

                                <img src="../../images/news/'.$yearNews.'/thumb_'.strtolower($article_home->getPhoto()).'"  '.$alt.' class="img-responsive">

                                </a></div>

                                <div class=" desc-img">

                                    <h2> <a href="/actualite-marathon-'.$article_home->getId().'-'.slugify($article_home->getTitre()).'.html" style="color: #000;">'.$article_home->getTitre().'</a> </h2>';
                                if($article_home->getChampionID()){
                                    $chmp=$champion->getChampionById($article_home->getChampionID())["donnees"];
                                    echo "<a href='athlete-".$chmp->getId()."-".$chmp->getNom().".html' class='home-link mr-5 '>Le palmarès de ". $chmp->getNom()."</a>";
                                }
                                if($article_home->getEvenementID()){
                                    $evenement=$event->getEvenementByID($article_home->getEvenementID())["donnees"];
                                    $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                                    $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                    $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                    $marathon= $event->getMarathon($evenement->getmarathon_id())['donnees'];
                                    echo "<a href='/resultats-marathon-".$evenement->getId()."-".slugify($nom_res_lien).".html' class='home-link mr-5 '>Résultats du marathon ".$marathon["prefixe"]." ".$marathon["nom"]." ".strftime("%Y",strtotime($evenement->getDateDebut()))."</a>";
                                }
                                echo '</div>
                            </article>';

                    }?>

                </section>

                <section class="last_articles_part1 to_show_mobile ">

                <?php

                    foreach ($last_articles_part1['donnees'] as $article_home) {

                        $cat='';

                        if($cat!="Videos" && $cat!="Photos" ) {$img_src="";}

                        if($cat=="Photos") $img_src="../../images/pictos/photo-icon.png";

                        if($cat=="Videos") $img_src="../../images/pictos/video-icon.png";

                        $tab = explode('-',$article_home->getDate());

                        $yearNews  = $tab[0];

                        $lien_1="";$lien_2="";$lien_3="";

                    if($article_home->getLien1()!=""){

                        $lien_1="<a href='".$article_home->getLien1()."' class='home-link'> > ".$article_home->getTextlien1()."</a><br>";

                    }

                    if($article_home->getLien2()!=""){

                        $lien_2="<a href='".$article_home->getLien2()."' class='home-link'> > ".$article_home->getTextlien2()."</a><br>";

                    }

                    if($article_home->getLien3()!=""){

                        $lien_3="<a href='".$article_home->getLien3()."' class='home-link'> > ".$article_home->getTextlien3()."</a>";

                    }

                    $nb=sizeof($commentaire->getCommentaires($article_home->getId(),0,0)['donnees']);
                    $alt = ($article_home->getLegende())?'alt="'.$article_home->getLegende().'"':'alt="allmarathon news image"';

                    $nb=($nb!=0)? $nb: '';

                    $coms=' <a href="/actualite-marathon-'.$article_home->getId().'-'.slugify($article_home->getTitre()).'.html#commentaires" title="Ajouter un commentaire" class="com_news"><i class="fa fa-comment-o com_news" aria-hidden="true"></i> '.$nb.'</a>';

                    echo '<article class="row" >

                        <div class="article-img"><a href="/actualite-marathon-'.$article_home->getId().'-'.slugify($article_home->getTitre()).'.html">

                        <img src="../../images/news/'.$yearNews.'/thumb_'.strtolower($article_home->getPhoto()).'"  '.$alt.' class="img-responsive">

                        </a></div>

                    <div class=" desc-img">

                    <h2> <a href="/actualite-marathon-'.$article_home->getId().'-'.slugify($article_home->getTitre()).'.html" style="color: #000;">'.$article_home->getTitre().'</a> </h2>';
                    if($article_home->getChampionID()){
                        $chmp=$champion->getChampionById($article_home->getChampionID())["donnees"];
                        echo "<a href='athlete-".$chmp->getId()."-".$chmp->getNom().".html' class='home-link mr-5 '>Le palmarès de ". $chmp->getNom()."</a>";
                    }
                    if($article_home->getEvenementID()){
                        $evenement=$event->getEvenementByID($article_home->getEvenementID())["donnees"];
                        $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                        $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                        $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                        $marathon= $event->getMarathon($evenement->getmarathon_id())['donnees'];
                        echo "<a href='/resultats-marathon-".$evenement->getId()."-".slugify($nom_res_lien).".html' class='home-link mr-5 '>Résultats du marathon ".$marathon["prefixe"]." ".$marathon["nom"]." ".strftime("%Y",strtotime($evenement->getDateDebut()))."</a>";
                    }
                    echo '</div>
                </article>';

                    }?>

                </section>
               
                <a href="/actualites-marathon.html" class="mx-auto w-fc mobile bouton-mobile  blue-btn">Plus d'actus</a>

            </div> 

            <aside class="col-sm-4">


                <dt class="bref to_hide_mobile">
                    <h2 class="h2-aside">
                        <span class="material-symbols-outlined ">rocket_launch</span>
                        Vite lu
                    </h2>
                </dt>
         
                <dd class="bref to_hide_mobile ">

                    <ul class="clearfix">

                        <?php

                    foreach ($bref_news['donnees'] as $article_bref) {
                        $tab = explode('-',$article_bref->getDate());
                        $yearNews  = $tab[0];
                        echo '<li><a href="/actualite-marathon-'.$article_bref->getId().'-'.slugify($article_bref->getTitre()).'.html">
                            <div class="row">
                                <div class="vite-lu-image col-sm-6" style="background-image:url(../../images/news/'.$yearNews.'/'.$article_bref->getPhoto().')"></div>
                                <div class="col-sm-6 pr-0 vite-lu-title">'.$article_bref->getTitre().'</div>
                            </div>
                        </a></li>';

                

                    }

                    ?>
                    <!--
                        <li class="last"><a href="/actualites-marathon-11--.html">[+] de brèves</a></li>
                    -->
                    </ul>

                </dd>

                <dt class="calendar to_hide_mobile marg_top">
                    <h2 class="h2-aside">
                        <span class="material-symbols-outlined  ">alarm_on</span>
                        Coming soon
                    </h2>
                </dt>

                <dd class="calendar to_hide_mobile ">

                    <div class="clearfix marathons-home">

                        

                        <?php

                

                    echo $home_events;

                    

                    ?>
                        <?php $today = date("Y/m/d");?>
                        <div class="mx-auto"><a href="<?php echo 'calendrier-marathons-'.utf8_encode(strftime("%B",strtotime($today))).'-'.intval((date("m"))).'-'.strftime("%Y",strtotime($today)).'.html'; ?>" class="mx-auto w-fc blue-btn blue-btn">Tous les marathons de <?php echo utf8_encode(strftime("%B",strtotime($today)));?></a></div>

                    <div>

                </dd>

                

                

            </aside>

        </div>

    </div> 
    </div>


    <section class="dernieres-video bureau">
        <?php include 'dernieres-video.php';?>
    </section>
    <section class="dernieres-video mobile">
        <?php include 'dernieres-video-mobile.php';?>
    </section>



    <div class="container page-content2 homepage mb-80">
        <div class="row banniere1 bureau">
        </div>
        <div class="row">

            <div class="col-sm-8 left-side">
            
                
                <section class="last_articles_part2 bureau">

                    <?php

                        foreach($last_articles_part2['donnees'] as $article){

                                $cat='';

                                if($cat!="Videos" && $cat!="Photos" ) {$img_src="";}

                                if($cat=="Photos") $img_src="../../images/pictos/photo-icon.png";

                                if($cat=="Videos") $img_src="../../images/pictos/video-icon.png";

                                $tab = explode('-',$article->getDate());

                                $yearNews  = $tab[0];

                                $lien_1="";$lien_2="";$lien_3="";

                                if($article->getLien1()!=""){

                                    $lien_1="<a href='".$article->getLien1()."' class='home-link'> > ".$article->getTextlien1()."</a><br>";

                                }

                                if($article->getLien2()!=""){

                                    $lien_2="<a href='".$article->getLien2()."' class='home-link'> > ".$article->getTextlien2()."</a><br>";

                                }

                                if($article->getLien3()!=""){

                                    $lien_3="<a href='".$article->getLien3()."' class='home-link'> > ".$article->getTextlien3()."</a>";

                                }

                                $nb=sizeof($commentaire->getCommentaires($article->getId(),0,0)['donnees']);
                                $alt = ($article->getLegende())?'alt="'.$article->getLegende().'"':'alt="allmarathon news image"';

                                $nb=($nb!=0)? $nb: '';

                                $coms=' <a href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html#commentaires" title="Ajouter un commentaire" class="com_news"><i class="fa fa-comment-o com_news" aria-hidden="true"></i> '.$nb.'</a>';

                                echo '<article class="row">

                                    <div class="article-img" >

                                    <a href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html">

                                    <img src="../../images/news/'.$yearNews.'/thumb_'.strtolower($article->getPhoto()).'" '.$alt.' class="img-responsive" >

                                    </a></div>

                                <div class=" desc-img">

                                <h2> <a href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html" style="color: #000;">'.$article->getTitre().'</a> </h2>';
                                if($article->getChampionID()){
                                    $chmp=$champion->getChampionById($article->getChampionID())["donnees"];
                                    echo "<a href='athlete-".$chmp->getId()."-".$chmp->getNom().".html' class='home-link mr-5 '>Le palmarès de ". $chmp->getNom()."</a>";
                                }
                                if($article->getEvenementID()){
                                    $evenement=$event->getEvenementByID($article->getEvenementID())["donnees"];
                                    $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                                    $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                    $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                    $marathon= $event->getMarathon($evenement->getmarathon_id())['donnees'];
                                    echo "<a href='/resultats-marathon-".$evenement->getId()."-".slugify($nom_res_lien).".html' class='home-link mr-5 '>Résultats du marathon ".$marathon["prefixe"]." ".$marathon["nom"]." ".strftime("%Y",strtotime($evenement->getDateDebut()))."</a>";
                                }
                                echo '</div>
                            </article>';

                }

                ?>

                </section>
                <aside>
                    <dt class="bref mobile">
                        <h2 class="h2-aside">
                            <span class="material-symbols-outlined ">
                                directions_run
                            </span>
                            Résultats récents
                        </h2>
                    </dt>

                    <dd class="result mobile ">

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
                    <dt class="calendar mobile marg_top">
                    <h2 class="h2-aside">
                        <span class="material-symbols-outlined  ">alarm_on</span>
                        Coming soon
                    </h2>
                </dt>

                <dd class="calendar mobile ">

                    <ul class="clearfix marathons-home">

                        

                        <?php

                

                    echo $home_events;

                    

                    ?>
                        <?php $today = date("Y/m/d");?>
                        <li class="last mx-auto"><a href="<?php echo 'calendrier-marathons-'.utf8_encode(strftime("%B",strtotime($today))).'-'.intval((date("m"))).'-'.strftime("%Y",strtotime($today)).'.html'; ?>" class="mx-auto w-fc blue-btn bouton-mobile">Tous les marathons de <?php echo utf8_encode(strftime("%B",strtotime($today)));?></a></li>

                    </ul>

                </dd>
                </aside>
            </div> 

            <aside class="col-sm-4">



            <dt class="bref to_hide_mobile">
                    <h2 class="h2-aside">
                        <span class="material-symbols-outlined ">
                            directions_run
                        </span>
                        Résultats récents
                    </h2>
                </dt>

                <dd class="result to_hide_mobile ">

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
                        echo '<a href="/resultats-marathon-'.$result->getID().'-'.slugify($nom_res_lien_archive).'.html"><div class="res-recents row '.$class.'"> <div class="col-lg-3 pt-10"><span class="res-recents-date"><b>'.date("d/m",strtotime($result->getDateDebut())).'</b></span></div><div class="col-lg-9"><strong>Marathon '.$marathon_prefixe.' '.$marathon_nom.'</strong><br><span>'. $pays_nom.'</span></div></div></a>';
                        $i++;
                    }

                    ?>

                        <li class="last mx-auto"><a href="/resultats-marathon.html" class="mx-auto w-fc mt-20 blue-btn">Voir tous les résultats</a></li>

                    </ul>

                </dd>

                

            </aside>

        </div>

    </div> 
    </div>

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
    <script src="../../js/main.js"></script>

</body>

</html>