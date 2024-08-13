<?php

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

include("../classes/pub.php");
include("../classes/video.php");
include("../classes/image.php");
include("../classes/champion.php");
include("../classes/evresultat.php");
include("../classes/evenement.php");
include("../classes/user.php");

include("../classes/champion_admin_externe_palmares.php");
include("../classes/lien.php");

$lien=new lien();
$nb_liens=$lien->getNumberLiens()['donnees']['nbr'];

$admin_externe=new champion_admin_externe_palmares();
$nb_results_admin_externe=$admin_externe->getNumberAdminExternResult()['donnees']['nbr'];


$user=new user();
$nb_users=$user->getNumberUsers()['donnees']['nbr'];

$evenement=new evenement();
$nb_evenements=$evenement->getNumberEvenements()['donnees']['nbr'];

$evresultat=new evresultat();
$nb_evresultats=$evresultat->getNumberEvresultats()['donnees']['nbr'];

$champion=new champion();
$nb_champions=$champion->getNumberChampions()['donnees']['nbr'];

$image=new image();
$nb_images=$image->getNumberImages()['donnees']['nbr'];

$video=new video();
$nb_videos=$video->getNumberVideos()['donnees']['nbr'];

$pub=new pub();
$pub728x90=$pub->getBanniere728_90("calendrier")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];

?>

<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Les statistiques de allmarathon.net</title>
    <meta name="description" content="">
    

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

    <div class="container page-content stats">
        <div class="row banniere1">
             <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
            
            <a href="" class="col-sm-12 ads-contain"><?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side">

                <div class="row">

                    <div class="col-sm-12">

                        <h1>Les statistiques de allmarathon.fr</h1>

                        <table class="table table-responsive table-bordered">
                            <tbody>
                                <tr>
                                    <td>Vidéos</td>
                                    <td><?php echo number_format($nb_videos,0,',',' '); ?></td>
                                </tr>
                                <tr>
                                    <td>Photos</td>
                                    <td><?php echo number_format($nb_images,0,',',' '); ?></td>
                                </tr>
                                <tr>
                                    <td>athlètes référencés</td>
                                    <td><?php echo number_format($nb_champions,0,',',' '); ?></td>
                                </tr>
                                <tr>
                                    <td>Résultats enregistrés par Allmarathon</td>
                                    <td><?php echo number_format($nb_evresultats,0,',',' '); ?></td>
                                </tr>
                                <tr>
                                    <td>Résultats enregistrés par les internautes</td>
                                    <td><?php echo number_format($nb_results_admin_externe,0,',',' '); ?></td>
                                </tr>
                                <tr>
                                    <td>&Eacute;vénements</td>
                                    <td><?php echo number_format($nb_evenements,0,',',' '); ?></td>
                                </tr>
                                
                                <tr>
                                    <td>Membres</td>
                                    <td><?php echo number_format($nb_users,0,',',' '); ?></td>
                                </tr>
                            </tbody>

                        </table>

                        <p>Si vous souhaitez connaître nos chiffres de fréquentation, <a
                                href="partenaires.php">n'hésitez pas à nous contacter !</a></p>

                    </div>
                </div>




            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"></p>
                <div class="ban ban_160-600">
                    
                     <div class="placeholder-content">
                          <div class="placeholder-title"> Allmarathon </div> 
                          <div class="placeholder-subtitle">publicité</div>
                    </div>
                    
                    <div  class="col-sm-12 ads-contain">
                    <a href="">
                <?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></div>

              </div>
              
              
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