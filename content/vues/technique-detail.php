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
}

$techniqueId=$_GET['techniqueID'];
$ong=(isset($_GET['ong'])) ? $_GET['ong'] : "1";
$curent_page=(isset($_GET['curent_page'])) ?  substr($_GET['curent_page'], 1) : 0;


include("../classes/pub.php");
include("../classes/technique.php");
include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/video.php");
include("../classes/image.php");

$image=new image();
$images=$image->getImagesByTechnique($techniqueId)['donnees'];

$video=new video();
$demos=$video->getTechniqueVideosByCategorie($techniqueId,'technique')['donnees'];
$videos=$video->getTechniqueVideosByCategorie($techniqueId,'combat',$curent_page)['donnees'];

$nb_demos=$video->getTechniqueVideosByCategorie($techniqueId,'technique')['nb_videos'];
$nb_videos=$video->getTechniqueVideosByCategorie($techniqueId,'combat')['nb_videos'];

$nb_pages=ceil($nb_videos/20);
if(($curent_page+1)<$nb_pages){
    $next=($curent_page+1);
    $style_suivant="";
    }
    else{
     $next=$nb_pages;
     $style_suivant='style="pointer-events: none;cursor: default;"';
}
if((($curent_page)!=0) && $curent_page!=$nb_pages){
    $previous=($curent_page-1);
    $style_precedent="";}
    else{
     $previous=0;
      $style_precedent='style="pointer-events: none;cursor: default;"';
}




$ev_cat_event=new evCategorieEvenement();

$event=new evenement();
$archives=$event->getDernierResultatsArchive();

$technique=new technique();
$tech=$technique->getTechniqueById($techniqueId)['donnees'];

$url="/".$tech->getNom();

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

?>

<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>TECHNIQUE DE JUDO : <?php echo $tech->getNom();?></title>
    <meta name="Description" lang="fr" content="ALL MARATHON, toutes les techniques du marathon. Connaître <?php echo $tech->getNom().' ';?> " />
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
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

    <div class="container page-content athlète-detail technique-detail">
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
                        <h1 style="text-transform: uppercase;">TECHNIQUE DE JUDO : <?php echo $tech->getNom(); ?></h1>



                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#tab1" role="tab" data-toggle="tab">Présentation</a></li>
                            <li><a href="#tab2" role="tab" data-toggle="tab">Démo (<?php echo $nb_demos; ?>)</a></li>
                            <li><a href="#tab3" role="tab" data-toggle="tab">Action (<?php echo $nb_videos; ?>)</a></li>
                            <li><a href="#tab4" role="tab" data-toggle="tab">Photos (<?php echo sizeof($images); ?>)</a>
                            </li>
                        </ul>
                        <?php
                        $lien='href="#tab'.$ong.'"';
                         echo "<script type='text/javascript'>
                            $(document).ready(function(){
                                $('.nav-tabs a[".$lien."]').tab('show');
                          
                        });
                        </script>";
                        ?>
                        <!-- TAB CONTENT -->
                        <div class="tab-content technique-detail">
                            <div class="active tab-pane fade in" id="tab1">
                                <?php if($tech->getPresentation()!=""){?>
                                <h2>Généralités</h2>
                                <p><?php echo $tech->getPresentation(); ?></p>
                                <?php } ?>

                                <?php if($tech->getConseil()!=""){?>
                                <h2>Conseil</h2>
                                <p><?php echo $tech->getConseil(); ?></p>
                                <?php } ?>

                            </div>
                            <div class="tab-pane fade" id="tab2">
                                <ul class="videos-tab">
                                    <?php foreach ($demos as $demo) {
                                
                                $duree="<li style='list-style-type: none;'></li>";
                                if($demo->getDuree()!='')
                                    $duree="<li>durée : ".$demo->getDuree()."</li>";

                                echo '<li class="row">
                                    <ul>
                                        <li class="col-sm-6">
                                            <ul>
                                                <li><a href="video-de-marathon-'.$demo->getId().'.html">'.$demo->getTitre().'</a></li>'.$duree.'
                                            </ul>
                                        </li>
                                        <li class="col-sm-6"><a href="video-de-marathon-'.$demo->getId().'.html"><img src="'.$demo->getVignette().'" alt="" class="pull-right img-responsive vignette_technique"/></a></li>
                                    </ul>
                                </li>';
                            }
                            ?>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="tab3">
                                <ul class="videos-tab">
                                    <?php foreach ($videos as $video) {
                                
                                $duree="<li style='list-style-type: none;'></li>";
                                if($video->getDuree()!='')
                                    $duree="<li>durée : ".$video->getDuree()."</li>";

                                echo '<li class="row">
                                    <ul>
                                        <li class="col-sm-6">
                                            <ul>
                                                <li><a href="video-de-marathon-'.$video->getId().'.html">'.$video->getTitre().'</a></li>'.$duree.'
                                            </ul>
                                        </li>
                                        <li class="col-sm-6"><a href="video-de-marathon-'.$video->getId().'.html"><img src="'.$video->getVignette().'" alt="" class="pull-right img-responsive vignette_technique"/></a></li>
                                    </ul>
                                </li>';
                            }
                            ?>
                                </ul>
                                <div class="clearfix"></div>

                                <ul class="pager">
                                    <?php echo '<li><a href="'.$url.'_3_'.($previous).'.html"'.$style_precedent.'>Précédent</a></li>
                                <li>'. $next." / ".$nb_pages.'</li>
                                <li ><a href="'.$url.'_3_'.($next).'.html"'.$style_suivant.'>Suivant</a></li>'; ?>
                                </ul>
                            </div>
                            <div class="tab-pane fade" id="tab4">
                                <ul class="photos-tab">
                                    <?php foreach ($images as $im) {
                                    // echo '<li><a class="fancybox" rel="group" href="images/galeries/'.$im->getGalerie_id().'/'.$im->getNom().' "><img src="images/galeries/'.$im->getGalerie_id().'/'.$im->getNom().'" width="116" height="77" alt=""/></a></li>';
                                       echo '<li><a class="fancybox" rel="group"  href="/images/galeries/'.$im->getGalerie_id().'/'.$im->getNom().'" ><img src="/images/galeries/'.$im->getGalerie_id().'/'.$im->getNom().'" width="116" height="77" alt=""/></a></li>';
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"><a href=""><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <dt class="archive">Résultats anciens</dt>
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
                            $nom_res_archive=$cat_event." ".$type_event." (".$ev_archive->getSexe().") ".$ev_archive->getNom()." ".substr($ev_archive->getDateDebut(),0,4);
                            echo '<li><a href="/resultats-marathon-'.$ev_archive->getId().'-'.slugify($nom_res_archive).'.html">'.$nom_res_archive.'</a></li>';
                        }
                    ?>
                    </ul>
                </dd>
                <div class="marg_bot"></div>
                <p class="ban"><a href=""><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <div class="marg_bot"></div>
                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
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
    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" src="../../js/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox -->
    <script type="text/javascript" src="../../js/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <script type="text/javascript" src="../../js/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="../../js/jquery.fancybox-media.js?v=1.0.6"></script>

    <script type="text/javascript" src="../../js/jquery.fancybox-thumbs.js?v=1.0.7"></script>
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
    <script src="../../js/easing.js"></script>
    <script src="../../js/jquery.ui.totop.min.js"></script>
    <script src="../../js/herbyCookie.min.js"></script>
    <script src="../../js/main.js"></script>




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