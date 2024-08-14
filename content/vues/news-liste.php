<?php

// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);



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



include("../classes/newscategorie.php");

include("../classes/news.php");
include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/commentaire.php");
include("../classes/champion.php");
include("../classes/user.php");

include("../classes/pub.php");



$pub=new pub();



$pub728x90=$pub->getBanniere728_90("actualite")['donnees'];

$pub300x60=$pub->getBanniere300_60("actualite")['donnees'];

$pub300x250=$pub->getBanniere300_250("actualite")['donnees'];

$pub160x600=$pub->getBanniere160_600("actualite")['donnees'];

$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];

$champion=new champion();




$news=new news();

$bref_news=$news->getBrefNews();

$ev_cat_event=new evCategorieEvenement();

$event=new evenement();


$sort = "";

if(isset($_GET['sort']) && $_GET['sort']!="") $sort =$_GET['sort'];



$page = 0;

if(isset($_GET['page']) && is_numeric($_GET['page'])) $page = intval($_GET['page']);



$key_word="";

if(isset($_GET['search']) && $_GET['search']) $key_word= $_GET['search'];



if($key_word!=""){

    $articles=$news->getArticlesViaSearch($key_word,$page);

}

else{

    $articles=$news->getArticlesPerPage($page,"");

}

$next=$page+1;

if($page>1){

  $previous=$page-1;  

}

else{

    $previous=0;

}



$nbr_pages=$news->getNumberPages($sort,$key_word)['donnees'];

$articles_par_page=intval($nbr_pages['COUNT(*)']/20)+1;



$newscategorie=new newscategorie();

$newscats=$newscategorie->getAllNewsCat();



$commentaires=new commentaire();

$news_commentaires=$commentaires->getCommentairesNews();



$user=new user();

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
setlocale(LC_TIME, "fr_FR","French");


?>

<!doctype html>

<html class="no-js" lang="fr">



<head>

    <meta charset="utf-8">

    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">

    <?php require_once("../scripts/header_script.php") ?>

    <title>Marathon : les actualités du marathon en France et dans le monde</title>

    <meta name="Description" content="Les actualités du marathon en France et dans le monde. News, résultats, interviews, vidéos, comptes-rendus, brèves, sondages. | allmarathon.fr" lang="fr" xml:lang="fr">

    <meta property="og:title" content="Marathon : les actualités du marathon en France et dans le monde" />
    <meta property="og:description" content="Les actualités du marathon en France et dans le monde. News, résultats, interviews, vidéos, comptes-rendus, brèves, sondages." />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="article" />
    <meta property="og:image" content="https://dev.allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="https://dev.allmarathon.fr/actualites-marathon.html" />

    <link rel="canonical" href="https://dev.allmarathon.fr/actualites-marathon.html" />


    <link rel="apple-touch-icon" href="apple-favicon.png">

    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />



    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <link rel="stylesheet" href="../../css/font-awesome.min.css">

    <link rel="stylesheet" href="../../css/fonts.css">

    <link rel="stylesheet" href="../../css/slider-pro.min.css" />

    <link rel="stylesheet" href="../../css/main.css">

    <link rel="stylesheet" href="../../css/responsive.css">

   



    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->

</head>



<body>

    <!--[if lt IE 8]>

<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>

<![endif]-->



    <?php 

// $test="TOURNOI DE RÔDAGE À NOISY-LE-GRAND";

// echo trim(preg_replace('~[^\\pL\d]~', '-',strtr(strtolower(str_replace('\\','',$test)), 'àáâãäåòóôõöøèéêëçìíîïùúûüÿñ', 'aaaaaaooooooeeeeciiiiuuuuyn')), '-').'<br>';

// $unwantedChars = array(',', '!', '?',':',' ',')','(');

// echo  trim(preg_replace('~[^\pL\d]~', strtolower(str_replace($unwantedChars,'-',$test)),'-'));

// die; 

include_once('nv_header-integrer.php'); ?>



    <div class="container page-content news">

        <div class="row banniere1  ban ban_728x90">
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
<!--<script src="-->
<!--https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js-->
<!--"></script>-->
<!--<link href="-->
<!--https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css-->
<!--" rel="stylesheet">-->
<!--<script>-->
<!--  document.addEventListener('DOMContentLoaded', function() {-->
<!--    var splide = new Splide('.splide', {-->
<!--      type       : 'loop',-->
<!--      perPage    : 6,-->
<!--      perMove    : 1,-->
<!--      gap        : '1rem',-->
<!--      pagination : false,-->
<!--      arrows     : true,-->
<!--      breakpoints: {-->
<!--        640: {-->
<!--          perPage: 1,-->
<!--        },-->
<!--      },-->
<!--    });-->
<!--    splide.mount();-->
<!--  });-->
<!--</script>-->

            <div class="col-sm-8 left-side">
                
        <!--<div class="splide" role="group" aria-label="Splide Basic HTML Example">-->
            
            <div class="splide-col">

               <div class="splide__list">
                    <?php foreach($newscats['donnees'] as $index => $catnews){
                        echo "<li class='splide__slide'><a class='a-cat-fltr' href='categorie-actualites-marathon-".slugify($catnews->getId()."-0-".$catnews->getIntitule()).".html' class='home-link mb-5 mr-5 '>".$catnews->getIntitule()."</a></li>";
                    }?>
                    </div>
                </div>

          <!--</div>-->

<?php

            foreach(array_slice($articles['donnees'],0,1) as $index => $article){
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

                                $nb=sizeof($commentaires->getCommentaires($article->getId(),0,0)['donnees']);

                                $nb=($nb!=0)? $nb: '';

                                $coms=' <a title="Ajouter un commentaire" href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html#commentaires" class="com_news"><i class="fa fa-comment-o com_news" aria-hidden="true"></i> '.$nb.'</a>';

                               

                                    $image_src='/images/news/'.substr($article->getDate(), 0,4).'/thumb_'.strtolower($article->getPhoto());

                                    $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';

                                    $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                    echo '<article class="row news-mobile-box mobile mt-77">

                               

                                    <div class="article-img"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'</a></div>

                               

                               
                                </article>';

                                

                                

                }

            ?>
            <section class="last_articles_part1 mt-0 bureau lazyblock">


                <?php
            $i==0;
            foreach($articles['donnees'] as $index => $article){
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

                                $nb=sizeof($commentaires->getCommentaires($article->getId(),0,0)['donnees']);

                                $nb=($nb!=0)? $nb: '';

                                $coms=' <a title="Ajouter un commentaire" href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html#commentaires" class="com_news"><i class="fa fa-comment-o com_news" aria-hidden="true"></i> '.$nb.'</a>';

                               

                                    $image_src='/images/news/'.substr($article->getDate(), 0,4).'/thumb_'.strtolower($article->getPhoto());

                                    $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';

                                    $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                    if($i==0){echo '<article class="row pt-10">';}else{echo '<article class="row">';}?>
                                    <?

                               

                                    echo '<div class="article-img"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'</a></div>

                               

                                <div class="desc-img">

                                    <h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #000;" >'.$article->getTitre().' </a></h2>';
                                    
                                    
                                    if($article->getChampionID()){
                                        $chmp=$champion->getChampionById($article->getChampionID())["donnees"];
                                        echo "<a href='athlete-".$chmp->getId()."-".$chmp->getNom().".html' class='home-link mb-5 mr-5 '>Le palmarès de ". $chmp->getNom()."</a>";
                                    }
                                    if($article->getLien1()  ){
                                        if( $article->getEvenementID()>0 ){
                                            $evenement=$event->getEvenementByID($article->getEvenementID())["donnees"];
                                            $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                                            $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                            $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                            $marathon= $event->getMarathon($evenement->getmarathon_id())['donnees'];
                                        }
                                        $lien_perso=$article->getLien1();
                                        $texte_perso=$article->getTextlien1();
                                        
                                        echo "<a href='".$lien_perso."' class='home-link mb-5 mr-5 '>".$texte_perso."</a>";
                                    }
                                    echo '</div>
                                </article>';

                                $i+=1;

                                

                }

            ?>
            <div class="pager">
                <ul>
                    <li><a id="back-link" class="<?php $class_none=($page>0)?"":"none"; echo $class_none;?>" href="?page=<?php $backpage=($page>0)?($page-1):0; echo $backpage;?>">page précédente</a></li>
                    <li><a id="next-link" href="?page=<?php $nextpage=$page+1; echo $nextpage;?>">page suivante</a></li>
                </ul>
            </div>
            
            </section>

            <section class="last_articles_part1 mt-0 mobile">


                <?php

            foreach(array_slice($articles['donnees'],0,1) as $index => $article){
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

                                $nb=sizeof($commentaires->getCommentaires($article->getId(),0,0)['donnees']);

                                $nb=($nb!=0)? $nb: '';

                                $coms=' <a title="Ajouter un commentaire" href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html#commentaires" class="com_news"><i class="fa fa-comment-o com_news" aria-hidden="true"></i> '.$nb.'</a>';

                               

                                    $image_src='/images/news/'.substr($article->getDate(), 0,4).'/thumb_'.strtolower($article->getPhoto());

                                    $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';

                                    $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                    echo ' <div class="news_alune">

                               

                                <div class="title_news mb-70">

                                    <h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #000;" >'.$article->getTitre().' </a></h2><br>';
                                    
                                    if($article->getChampionID()){
                                        $chmp=$champion->getChampionById($article->getChampionID())["donnees"];
                                        echo "<a href='athlete-".$chmp->getId()."-".$chmp->getNom().".html' class='home-link mb-5 mr-5 '>Le palmarès de ". $chmp->getNom()."</a>";
                                    }
                                    if($article->getLien1()  ){
                                        if( $article->getEvenementID()>0 ){
                                            $evenement=$event->getEvenementByID($article->getEvenementID())["donnees"];
                                            $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                                            $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                            $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                            $marathon= $event->getMarathon($evenement->getmarathon_id())['donnees'];
                                        }
                                        $lien_perso=$article->getLien1();
                                        $texte_perso=$article->getTextlien1();
                                        
                                        echo "<a href='".$lien_perso."' class='home-link mb-5 mr-5 '>".$texte_perso."</a>";
                                    }
                                    echo '</div>
                                </div>';

                                

                                

                }

            ?>

            </section>

            <section class="last_articles_part1 mt-0 mobile">


                <?php

                foreach(array_slice($articles['donnees'],1,4) as $index => $article){
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

                                $nb=sizeof($commentaires->getCommentaires($article->getId(),0,0)['donnees']);

                                $nb=($nb!=0)? $nb: '';

                                $coms=' <a title="Ajouter un commentaire" href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html#commentaires" class="com_news"><i class="fa fa-comment-o com_news" aria-hidden="true"></i> '.$nb.'</a>';

                            

                                    $image_src='/images/news/'.substr($article->getDate(), 0,4).'/thumb_'.strtolower($article->getPhoto());

                                    $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';

                                    $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                    echo '<article class="row news-mobile-box">

                            

                                    <div class="article-img"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'</a></div>

                            

                                <div class="desc-img">

                                    <h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #000;" >'.$article->getTitre().' </a></h2>';
                                    
                                    if($article->getChampionID()){
                                        $chmp=$champion->getChampionById($article->getChampionID())["donnees"];
                                        echo "<a href='athlete-".$chmp->getId()."-".$chmp->getNom().".html' class='home-link mb-5 mr-5 '>Le palmarès de ". $chmp->getNom()."</a>";
                                    }
                                    if($article->getLien1()  ){
                                        if( $article->getEvenementID()>0 ){
                                            $evenement=$event->getEvenementByID($article->getEvenementID())["donnees"];
                                            $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                                            $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                            $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                            $marathon= $event->getMarathon($evenement->getmarathon_id())['donnees'];
                                        }
                                        $lien_perso=$article->getLien1();
                                        $texte_perso=$article->getTextlien1();
                                        
                                        echo "<a href='".$lien_perso."' class='home-link mb-5 mr-5 '>".$texte_perso."</a>";
                                     }
                                    echo '</div>
                                </article>';

                                

                                

                }

                ?>

            </section>

            
        </div>
        <aside class="col-sm-4">

        <div class="ban ban_300x60 width-60 mb-30">
            <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
             <div  class="col-sm-12 ads-contain">
                <?php
                    if($pub300x60 !="") {
                    echo '<a target="_blank" href="'.$pub300x60["url"].'" >';
                        echo $pub300x60["code"] ? $pub300x60["code"] :  "<img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
                        echo '</a>';
                    }
                ?>
                </div>
                </div>


                <dt class="bref to_hide_mobile">
                    <h2 class="h2-aside">
                        <span class="material-symbols-outlined ic-15">rocket_launch</span>
                        Vite lu
                    </h2>
                </dt>
         
                <dd class="bref to_hide_mobile marg_bot">

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

                <div class="marg_bot"></div>

                <div class="ban ban_300x250">
                     <div class="placeholder-content">
                            <div class="placeholder-title"> Allmarathon </div> 
                            <div class="placeholder-subtitle">publicité</div>
                     </div>
                  <div  class="col-sm-12 ads-contain">
                <?php if($pub300x250 !="") {

                echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";

                    }

            ?></a>
                 </div>
            </div>

                <div class="marg_bot"></div>

                

                

                

                <div class="marg_bot"></div>
                <!--place des commentaires-->
                

                <div class="marg_bot"></div>

                <div class="ban ban_160-600">
                    
                     <div class="placeholder-content">
                         <div class="placeholder-title"> Allmarathon </div> 
                         <div class="placeholder-subtitle">publicité</div>
                     </div>
                    <div  class="col-sm-12 ads-contain">
                    <a href="">
                <?php

if($pub160x600 !="") {

    //var_dump($pub160x600["url"]); exit;

    if($pub160x600["code"]==""){

        echo "<a href=".'http://dev.allrathon.fr/'.$pub160x600["url"]." target='_blank'><img src=".'../images/news/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";

    }

    else{

        echo $pub160x600["code"];

    }

}

?></a></div>
</div>




                <dt>

                <dd>

                    <div id="taboola-right-rail-thumbnails"></div>

                    

                </dd>

                </dt>



                <div class="marg_bot"></div>



                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>

            <dd class="facebook">

                <div class="fb-page"

                     data-href="https://www.facebook.com/allmarathonnet-108914759155897/"

                     data-width=""

                     data-adapt-container-width="true"

                     data-hide-cover="false"

                     data-show-facepile="true">

                </div>

            </dd> -->

                <div class="marg_bot"></div>



            </aside>

            
    </div>
    <div class="row banniere1 ban ban_768x90 ">
        
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
                        ?></div>
                </div>
            </div>
</div>
            <aside class="mobile vite-lu-mobile-box">

                <dt class="bref ">
                    <h2 class="h2-aside">
                        <span class="material-symbols-outlined ic-15">rocket_launch</span>
                        Vite lu
                    </h2>
                </dt>
         
                <dd class="bref  marg_bot">

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
            </aside>
            <div class="container page-content news  no-mh mobile">
            <div class="row">

            <div class="col-sm-8 left-side">
            <section class="last_articles_part1 mt-0 mobile lazyblock-mobile">


                <?php

            foreach(array_slice($articles['donnees'],5,count($articles['donnees'])) as $index => $article){
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

                                $nb=sizeof($commentaires->getCommentaires($article->getId(),0,0)['donnees']);

                                $nb=($nb!=0)? $nb: '';

                                $coms=' <a title="Ajouter un commentaire" href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html#commentaires" class="com_news"><i class="fa fa-comment-o com_news" aria-hidden="true"></i> '.$nb.'</a>';

                               

                                    $image_src='/images/news/'.substr($article->getDate(), 0,4).'/thumb_'.strtolower($article->getPhoto());

                                    $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';

                                    $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                    echo '<article class="row news-mobile-box">

                               

                                    <div class="article-img"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'</a></div>

                               

                                <div class="desc-img">

                                    <h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #000;" >'.$article->getTitre().' </a></h2>';
                                    
                                    if($article->getChampionID()){
                                        $chmp=$champion->getChampionById($article->getChampionID())["donnees"];
                                        echo "<a href='athlete-".$chmp->getId()."-".$chmp->getNom().".html' class='home-link mb-5 mr-5 '>Le palmarès de ". $chmp->getNom()."</a>";
                                    }
                                    if($article->getLien1()  ){
                                        if( $article->getEvenementID()>0 ){
                                            $evenement=$event->getEvenementByID($article->getEvenementID())["donnees"];
                                            $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                                            $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                            $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                            $marathon= $event->getMarathon($evenement->getmarathon_id())['donnees'];
                                        }
                                        $lien_perso=$article->getLien1();
                                        $texte_perso=$article->getTextlien1();
                                        
                                        echo "<a href='".$lien_perso."' class='home-link mb-5 mr-5 '>".$texte_perso."</a>";
                                    }
                                    echo '</div>
                                </article>';

                                

                                

                }

            ?>

            </section>

               





            </div> <!-- End left-side -->



            

        </div>



    </div> <!-- End container page-content -->

<?php 

            //echo $pub768x90; 

           // include("produits_boutique.php");  

            ?>

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









    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->



    <script>
/*
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

    ga('send', 'pageview');*/

    </script>

    <script type='text/javascript'>

    function sortArticles() {

        selected = document.getElementById('reroutage').selectedIndex;

        sort = document.getElementById('reroutage')[selected].value;

        console.log(sort);

        if (sort != '') {

            window.location.href = '/actualites-marathon-' + sort + '--.html';

        } else {

            window.location.href = '/actualites-marathon.html';

        }

    }

    </script>



    <script type="text/javascript">

    function goToSearch() {

        var key = document.getElementById('search_val').value;

        window.location = "/actualites-marathon---" + key + '.html';

    }

    document.getElementById('search_val').onkeypress = function(e) {

        if (!e) e = window.event;

        var keyCode = e.keyCode || e.which;

        if (keyCode == '13') {

            var key = document.getElementById('search_val').value;

            window.location = "/actualites-marathon---" + key + '.html';

            return false;

        }

    }

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