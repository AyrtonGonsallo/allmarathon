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

include("../classes/pub.php");
include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/technique.php");

$technique=new technique();
$techniques=$technique->getAll()['donnees'];
    foreach ($techniques as $technique) {
        $res_techniques[$technique->getFamille()][$technique->getNom()] = $technique->getId();
    }
$ev_cat_event=new evCategorieEvenement();

$event=new evenement();
$archives=$event->getDernierResultatsArchive();

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
    <title>Apprendre le marathon. Techniques et prises de marathon debout et au sol.</title>
    <?php require_once("../scripts/header_script.php") ?>
    <meta name="Description" lang="fr" content="ALL MARATHON, toutes les prises de marathon. Connaître les techniques : projections, immobilisations, étranglements, clés de bras " />
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <!-- Place favicon.ico in the root directory -->

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

    <!-- Add your site or application content here -->


    <?php include_once('nv_header-integrer.php'); ?>


    <div class="container page-content technique-list">
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
                        <h1>APPRENDRE LE JUDO. TECHNIQUES ET PRISES DE JUDO DEBOUT ET AU SOL.</h1>


                        <div class="list-tech row">
                            <div class="col-sm-6">
                                <dt>Tachi-waza. Les projections.</dt>
                                <dd>
                                    <ul>
                                        <?php foreach($res_techniques['projection'] as $nom => $id)
                                        echo '<li id="technique"><a href="'.$nom.'.html">'.$nom.'</a></li>';
                                 ?>
                                    </ul>
                                </dd>
                            </div>
                            <div class="col-sm-6">
                                <dt>Osae-komi-waza. Les immobilisations.</dt>
                                <dd>
                                    <ul>
                                        <?php foreach($res_techniques['immobilisation'] as $nom => $id)
                                        echo '<li id="technique"><a href="'.$nom.'.html">'.$nom.'</a></li>';
                                 ?>
                                    </ul>
                                </dd>
                                <dt>Shime-waza. Les étranglements.</dt>
                                <dd>
                                    <ul>
                                        <?php foreach($res_techniques['étranglement'] as $nom => $id)
                                        echo '<li id="technique"><a href="'.$nom.'.html">'.$nom.'</a></li>';
                                 ?>
                                    </ul>
                                </dd>
                                <dt>Kansetsu-waza. Les clés de bras.</dt>
                                <dd>
                                    <ul>
                                        <?php foreach($res_techniques['clé de bras'] as $nom => $id)
                                        echo '<li id="technique"><a href="'.$nom.'.html">'.$nom.'</a></li>';
                                 ?>
                                    </ul>
                                </dd>
                                <dt>Katas</dt>
                                <dd>
                                    <ul>
                                        <?php foreach($res_techniques['kata'] as $nom => $id)
                                        echo '<li id="technique"><a href="'.$nom.'.html">'.$nom.'</a></li>';
                                 ?>
                                    </ul>
                                </dd>
                                <dt>Exercices pour l'entraînement de marathon</dt>
                                <dd>
                                    <ul>
                                        <?php foreach($res_techniques['exercice'] as $nom => $id)
                                        echo '<li id="technique"><a href="'.$nom.'.html">'.$nom.'</a></li>';
                                 ?>
                                    </ul>
                                </dd>
                                <dt>Divers</dt>
                                <dd>
                                    <ul>
                                        <?php foreach($res_techniques['divers'] as $nom => $id)
                                        echo '<li id="technique"><a href="'.$nom.'.html">'.$nom.'</a></li>';
                                 ?>
                                    </ul>
                                </dd>
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