<?php
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


include("../classes/champion.php");
include("../classes/marathon.php");
include("../classes/pub.php");
$mois=$_GET['mois'];
if($mois<10){
    $mois="0".$mois;
}
$annee=$_GET['annee'];
$pub=new pub();
$ev_cat_event=new evCategorieEvenement();
$pub728x90=$pub->getBanniere728_90("athlètes")['donnees'];
$pub300x60=$pub->getBanniere300_60("athlètes")['donnees'];
$pub300x250=$pub->getBanniere300_250("athlètes")['donnees'];
$pub160x600=$pub->getBanniere160_600("athlètes")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];
setlocale(LC_TIME, "fr_FR","French");
$champion=new champion();

$pays=new pays();

$order = 'a';
if(isset($_GET['order']))  $order = $_GET['order'];

if(isset($_POST['search']))
        $order =trim($_POST['search']);
$page=0;
if(isset($_GET['page']) && is_numeric($_GET['page'])) $page = intval($_GET['page']);

$nb_pages=intval($champion->getNumberPage($order)['donnees']['COUNT(*)']/80)+1;
$next=$page+1;
$previous=$page-1;



include("../database/connexion.php");
$req = $bdd->prepare('SELECT COUNT(*) as total FROM champions');
$req->execute();
$nb_champs=$req->fetch(PDO::FETCH_ASSOC)['total'];

?>


<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Calendrier des marathons de <?php echo utf8_encode(strftime("%B %Y",strtotime($annee.'-'.$mois.'-12')));?></title>
    <meta name="Description" lang="fr" content="A vos agendas ! Le calendrier complet des marathons pour le mois de <?php echo utf8_encode(strftime("%B %Y",strtotime($annee.'-'.$mois.'-12')));?>. ">

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    
    <link rel="canonical" href="https://allmarathon.fr/cv-champions-de-marathon.html" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
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
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->


    <?php include_once('nv_header-integrer.php');?>


    <div class="container page-content athlètes">
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
                        <a href="/calendrier-agenda-marathons.html">Marathons</a> > <a href="/agenda-marathons-par-mois.html">Marathons par mois</a> > <?php echo utf8_encode(strftime("%B %Y",strtotime($annee.'-'.$mois.'-12')));?>
                        <h1>Calendrier des marathons <?php echo utf8_encode(strftime("%B %Y",strtotime($annee.'-'.$mois.'-12')));?></h1>
                        <h2>Agenda : liste des marathons sur route de <?php echo utf8_encode(strftime("%B %Y",strtotime($annee.'-'.$mois.'-12')));?></h2>

                       
                    </div>

                    <div class="col-sm-12">
                       
                    </div>

                    <?php
                    $prec_date = null;
                    $part1=getMarathonsAgendaByMoisfiltered($mois,$annee)['donnees_1'];
                    $part2=getMarathonsAgendaByMoisfiltered($mois,$annee)['donnees_2'];
                    echo '<ul class="col-sm-12 resultats">';
                    if($part1){
                        
                        foreach ($part1 as $key => $resultat) {
                            if($prec_date){
                                if($prec_date!=$resultat['DateDebut']){
                                    echo '<li class="date-regroupees">'.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut']))).'</li>';
                                }
                            }else{
                                echo '<li class="date-regroupees">'.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut']))).'</li>';
                            }
                            $pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];

                            $nom_res='<strong>'.$resultat['Nom'].'</strong>';
                            echo '<li><a href="/marathons-'.$resultat['id'].'-'.slugify($resultat['Nom']).'.html">'.$nom_res.'</a><ul class="list-inline"><li><img src="../../images/flags/'.$pays_flag.'" alt=""/></li></ul></li>';
                            
                            $prec_date=$resultat['DateDebut'];
                        }
                        
                    }
                    if($part2){
                        
                        foreach ($part2 as $key => $resultat) {
                            if($prec_date){
                                if($prec_date!=$resultat['DateDebut']){
                                    echo '<li class="date-regroupees">'.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut']))).'</li>';
                                }
                            }else{
                                echo '<li class="date-regroupees">'.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut']))).'</li>';
                            }
                            $pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
                            $nom_res='<strong>'.$resultat['Nom'].'</strong>';
                            echo '<li><a href="/marathons-'.$resultat['id'].'-'.slugify($resultat['Nom']).'.html">'.$nom_res.'</a><ul class="list-inline"><li><img src="../../images/flags/'.$pays_flag.'" alt=""/></li></ul></li>';
                            
                            $prec_date=$resultat['DateDebut'];
                        }
                        
                    }echo '</ul>';
                ?>
                </div>

                <div class="clearfix"></div>

                <ul class="pager">
                   
                </ul>

            </div> <!-- End left-side -->

            <aside class="col-sm-4 pd-top">
                
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

    <script type="text/javascript">
    function goSearch_athlètes() {
        var key = (document.getElementById('search_athlètes').value).toLowerCase();
        window.location = "cv-champions-de-marathon-" + key + ".html";
    }

    document.getElementById('search_athlètes').onkeypress = function(e) {
        if (!e) e = window.event;
        var keyCode = e.keyCode || e.which;
        if (keyCode == '13') {
            var key = (document.getElementById('search_athlètes').value).toLowerCase();
            window.location = "cv-champions-de-marathon-" + key + ".html";
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