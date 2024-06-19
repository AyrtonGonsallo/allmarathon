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

include("../classes/pub.php");
include("../classes/pays.php");
include("../classes/grade.php");



$pays=new pays();
$liste_pays=$pays->getAll()['donnees'];

$pub=new pub();
$pub728x90=$pub->getBanniere728_90("outils")['donnees'];
$pub300x60=$pub->getBanniere300_60("outils")['donnees'];
$pub300x250=$pub->getBanniere300_250("outils")['donnees'];
$pub160x600=$pub->getBanniere160_600("outils")['donnees'];
$pub768x90=$pub->getBanniere768_90("outils")['donnees'];



?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Convertisseur allure vitesse</title>
    <meta name="description" content="Sur cette page vous allez pouvoir facilement convertir votre vitesse en allure et vice versa. Il vous suffit d'entrer la valeur dans l'unité de mesure appropriée (vitesse ou allure) et le convertisseur effectue la conversion. Que ce soit pour calculer leur rythme pendant un entraînement, établir un plan d'entraînement ou simplement pour connaître vos performances, cet outil de conversion a été conçu pour aider les fans de course à pied.">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/slider-pro.min.css" />
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>


    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <?php include_once('nv_header-integrer.php'); ?>
    <div class="container page-content">
        <div class="row banniere1">
            <div  class="col-sm-12"><?php
                if($pub728x90 !="") {
                echo '<a target="_blank" href="'.$pub728x90["url"].'" class="col-sm-12">';
                    echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
                    echo '</a>';
                }else if($getMobileAds !="") {
                echo $getMobileAds["code"] ? $getMobileAds["code"] :  "<a href=".$getMobileAds["url"]." target='_blank'><img src=".'../images/pubs/'.$getMobileAds['image'] . " alt='' style=\"width: 100%;\" /></a>";
                }
                ?>
            </div>
        </div>
        <section class="page-outil">
        <h1>Convertisseur vitesse / allure</h1>
        <p>
        Vous êtes coureur ? vous êtes au bon endroit ! Sur cette page vous allez pouvoir facilement convertir votre vitesse en allure et vice versa. Il vous suffit d'entrer la valeur dans l'unité de mesure appropriée (vitesse ou allure) et le convertisseur effectue la conversion.
    Que ce soit pour calculer leur rythme pendant un entraînement, établir un plan d'entraînement ou simplement pour connaître vos performances, cet outil de conversion a été conçu pour aider les fans de course à pied.

        </p>
        <div class="mt-50"><h2>Convertissez votre vitesse (km/h) en allure (min/km)</h2></div>
        <div>
            <input type="number" placeholder="vitesse (km/h)" id="premier-input" />
            <button id="button_vtoa" class="button-outils">Convertir</button>
        </div>
        <div id="reponse-vtoa">
            
        </div>
        <div class="mt-50"><h2>Convertissez votre allure (min:sec/km) en vitesse (km/h) </h2></div>
        <div>    
            <input id="allure_min" type="number" placeholder="minutes" />
            <input id="allure_sec" type="number" placeholder="secondes" />
            <button id="button_atov" class="button-outils">Convertir</button>
        </div>
        <div id="reponse-atov">
            
        </div>
        <div class="mt-50"><h3>Tout ce qu’il faut savoir sur la vitesse et sur l’allure en course à pieds</h3></div>
        <h4>Quelle différence entre l’allure et la vitesse ?</h4>
        <p>
        
    Souvent confondus l’allure et la vitesse de course sont deux notions proches mais différentes. 
    La vitesse est la mesure directe du déplacement d'un coureur. Elle est généralement exprimée en unité de distance par unité de temps, comme kilomètres par heure (km/h) ou mètres par seconde (m/s). 
    L'allure fait référence au temps moyen nécessaire pour parcourir une certaine distance. Elle est généralement exprimée en minutes par kilomètre (min/km) ou minutes par mile (min/mi) dans les pays anglophones. 
    L'avantage de l'allure réside dans sa capacité à fournir une indication directe du rythme de course sans être influencée par les variations de distance parcourue. Par conséquent, les coureurs utilisent souvent l'allure pour se fixer des objectifs, suivre leur progression et comparer leurs performances.

        </p>


        </div>
    </section>
</div>   
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js" ></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#button_vtoa").click(function() {
            vitesse=parseFloat($("#premier-input").val())
            allure_en_sec=3600/vitesse
            console.log(allure_en_sec)
            min=Math.floor(allure_en_sec/60)
            sec=Math.round(((allure_en_sec/60)-min)*60)
            reponse="Pour une vitesse de "+vitesse+" km/h, l'allure correspondante est de : "+min+" min "+sec+" sec/km"
            $("#reponse-vtoa").text(reponse)
        })
        
        $("#button_atov").click(function() {
           allure_min=parseFloat($("#allure_min").val())
           allure_sec=parseFloat($("#allure_sec").val())
           console.log(typeof  allure_sec)
            allure_en_sec=allure_min*60+allure_sec
            
            vitesse=3600/allure_en_sec
            reponse="Pour une allure de "+allure_min+" min "+allure_sec+" sec par kilomètre, la vitesse correspondante est de "+vitesse.toFixed(2)+" km/h"
            $("#reponse-atov").text(reponse)
        })



        console.log("all content is ready !!!")
    })
</script>
    
    <?php include_once('footer.inc.php'); ?>


    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/jquery.jcarousel.min.js"></script>
    <script src="js/jquery.sliderPro.min.js"></script>
    <script src="js/easing.js"></script>
    <script src="js/jquery.ui.totop.min.js"></script>
    <script src="js/herbyCookie.min.js"></script>
    <script src="js/main.js"></script>
    <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js">
    </script>
<script type="text/javascript">
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

$(document).ready(function() {
    var page=getCookie("page_when_creating_account")
    console.log("page_when_creating_account : "+page)
    });

</script>
    <script type="text/javascript">
    $('#target').validate();
    </script>
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript">
    $.datepicker.setDefaults($.datepicker.regional['fr']);
    $("#date_naissance").datepicker();
    $('#date_naissance').datepicker('option', {
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
            'Octobre', 'Novembre', 'Décembre'
        ],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.',
            'Nov.', 'Déc.'
        ],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        dateFormat: 'yy-mm-dd'
    });
    </script>

    <!-- <script src="/content/scripts/identification_user.js" type="text/javascript"></script> -->


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
<style type="text/css">
label.error {
    color: red;
}
</style>