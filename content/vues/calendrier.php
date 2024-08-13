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

include "../classes/evCategorieAge.php";
include "../classes/evCategorieEvenement.php";
include("../classes/pub.php");
include("../classes/evenement.php");

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("calendrier")['donnees'];
$pub300x60=$pub->getBanniere300_60("calendrier")['donnees'];
$pub300x250=$pub->getBanniere300_250("calendrier")['donnees'];
$pub160x600=$pub->getBanniere160_600("calendrier")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("resultats")['donnees'];

$age=new evCategorieAge();
$ages=$age->getAll();

$event=new evCategorieEvenement();
$events=$event->getAll();

$temp             = explode(".", (date('n')-1)/3);
$trimestre_actuel = $temp[0];

$evenement=new evenement();
$tab=0;

$type_event = "";
if(isset($_GET['type']) && $_GET['type']!="") $type_event =$_GET['type'];

$categorie_age = "";
if(isset($_GET['age']) && $_GET['age']!="") $categorie_age =$_GET['age'];

?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Marathon : calendrier des compétitions, tournois et stages de marathon</title>
    <meta name="Description" content="Allmarathon.net, tout le marathon en France et dans le monde. Calendrier complet des compétitions, tournois, stages, championnats de la saison." lang="fr" xml:lang="fr">
    

    <link rel="apple-touch-icon" href="../../images/favicon.ico">

    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">
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

    <div class="container page-content calendrier-marathon">
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
                        <h1>Marathon : calendrier des courses et des championnats nationaux et internationaux</h1>
                        <br>
                        

                        <br />
                        <br />

                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#tab1" role="tab"
                                    data-toggle="tab"><?php echo $trimestre_actuel+1; ?>e trimestre<br>
                                    <?php echo date("Y"); ?></a></li>
                            <li><a href="#tab2" role="tab" data-toggle="tab"><?php echo ($trimestre_actuel+1)%4+1; ?>e
                                    trimestre
                                    <br><?php echo (($trimestre_actuel+1)/4 >= 1)?date("Y")+1:date("Y"); ?></a></li>
                            <li><a href="#tab3" role="tab" data-toggle="tab"><?php echo ($trimestre_actuel+2)%4+1; ?>e
                                    trimestre
                                    <br><?php echo (($trimestre_actuel+2)/4 >= 1)?date("Y")+1:date("Y"); ?></a></li>
                            <li><a href="#tab4" role="tab" data-toggle="tab"><?php echo ($trimestre_actuel+3)%4+1; ?>e
                                    trimestre<br>
                                    <?php echo (($trimestre_actuel+3)/4 >= 1)?date("Y")+1:date("Y"); ?></a></li>
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <div class="active tab-pane fade in" id="tab1">

                                <?php 
                          include "../modules/showCalendrier.php";
                          // $tab=10; ?>
                            </div>
                            <div class=" tab-pane fade in" id="tab2">
                                <?php 
                            $tab=1;
                            include "../modules/showCalendrier.php"; 
                            // $tab=10;?>
                            </div>
                            <div class="tab-pane fade" id="tab3">
                                <?php  
                             $tab=2;
                             include "../modules/showCalendrier.php";
                             // $tab=10; ?>

                            </div>
                            <div class="tab-pane fade" id="tab4">
                                <?php 
                             $tab=3; 
                             include "../modules/showCalendrier.php";
                             // $tab=10; ?>
                            </div>
                        </div>




                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4 pd-top">
                <p class="ban"><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x60['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                
              
                <div class="marg_bot"></div>
                <p class="ban"><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <div class="marg_bot"></div>
                <p class="ban ban_160-600"><?php
if($pub160x600 !="") {
    //var_dump($pub160x600["url"]); exit;
    if($pub160x600["code"]==""){
        echo "<a href=".'http://dev.allrathon.fr/'.$pub160x600["url"]." target='_blank'><img src=".'../images/news/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";
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
 <?php //include("produits_boutique.php"); ?>
    </div> <!-- End container page-content -->


    <?php include('footer.inc.php'); ?>


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

    <?php
  if($user_id!=""){
    
     echo "<script type='text/javascript'>
    $('#reference_event').on('click',function(e){
            document.location.href='/formulaire-calendrier.php';
      
      });   

        </script>";
  }else{
     echo "<script type='text/javascript'>
      $('#reference_event').on('click',function(e){
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