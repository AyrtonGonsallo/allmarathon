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
$ev_cat_event=new evCategorieEvenement();
$paysID=$_GET['paysID'];
$pub=new pub();

$pub728x90=$pub->getBanniere728_90("athlètes")['donnees'];
$pub300x60=$pub->getBanniere300_60("athlètes")['donnees'];
$pub300x250=$pub->getBanniere300_250("athlètes")['donnees'];
$pub160x600=$pub->getBanniere160_600("athlètes")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];

$champion=new champion();

$pays=new pays();
$pays_datas=$pays->getPaysById($paysID)['donnees'];
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
setlocale(LC_TIME, "fr_FR","French");
?>


<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Calendrier des marathons <?php echo $pays_datas->getPrefixe();?> <?php echo $pays_datas->getNomPays();?></title>
    <meta name="Description" lang="fr" content="A vos agendas ! Le calendrier complet des marathons <?php echo $pays_datas->getPrefixe();?> <?php echo $pays_datas->getNomPays();?> ">
    <meta property="og:type" content="siteweb" />
    <meta property="og:title" content="Calendrier des marathons <?php echo $pays_datas->getPrefixe();?> <?php echo $pays_datas->getNomPays();?>" />
    <meta property="og:description" content="A vos agendas ! Le calendrier complet des marathons <?php echo $pays_datas->getPrefixe();?> <?php echo $pays_datas->getNomPays();?> " />
    <meta property="og:image" content="https://allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="<?php echo 'https://allmarathon.fr/calendrier-marathons-'.slugify($pays_datas->getNomPays()).'-'.$pays_datas->getId().'.html';?>" />

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    
    <link rel="canonical" href="<?php echo 'https://allmarathon.fr/calendrier-marathons-'.slugify($pays_datas->getNomPays()).'-'.$pays_datas->getId().'.html';?>" />
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


    <?php include_once('nv_header-integrer.php'); ?>


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
                    <a href="/calendrier-agenda-marathons.html">Marathons</a> > <a href="/agenda-marathons-par-pays.html">Marathons par pays</a> > <?php echo $pays_datas->getNomPays();?>

                        <h1>Calendrier des marathons  <?php echo $pays_datas->getPrefixe();?> <?php echo $pays_datas->getNomPays();?></h1>

                       
                    </div>
                    <div class="col-sm-12">

                        <?php echo $pays_datas->getTexte();?>

                       
                    </div>
                    

                    
                
                </div>




                <ul class="col-sm-12 resultats">
                <div class="row" id="liste-marathons">
                        <?php 
                        $i=0;                             
                        setlocale(LC_TIME, "fr_FR","French");
                        $res="";
                        $part=getMarathonsAgendaByPaysflitered($pays->getPaysById($paysID)['donnees']->getAbreviation(),$pays->getPaysById($paysID)['donnees']->getAbreviation2(),$pays->getPaysById($paysID)['donnees']->getAbreviation3(),$pays->getPaysById($paysID)['donnees']->getAbreviation4())['donnees'];
                        foreach ($part as $key => $resultat) {
                
                            $pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
                           $nom_res= $resultat['nom'];
                
                            $res.= '<div class="col-sm-6 marathon-grid">
                                <a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">
                                    <h4 class="page-marathon-title">'.$nom_res.'<img class="marathon-title-flag" style="float:right" src="../../images/flags/'.$pays_flag.'" alt=""/></h4></a> ';
                                     
                                    $img_src='/images/marathons/thumb_'.$resultat['image'];
                                    $full_image_path="http://" . $_SERVER['HTTP_HOST'] .$img_src;
                                    //$res.= $full_image_path;
                                    
                                    if ($img_src)
                                        {
                                            $res.= '<div class="marathon-liste-image" style="background-image:url('.$img_src.')"></div>';
                                        }else{
                                            $res.= '<div class="marathon-liste-image" style="background-color:#000"></div>';
                                        }
                                     if($resultat['last_linked_events_cat_id']){
                                        $res.= '<div><b>'.$ev_cat_event->getEventCatEventByID($resultat['last_linked_events_cat_id'])['donnees']->getIntitule().'</b></div>';
                
                                     }else{
                                        $res.= '<div><b>Pas d\'évenement</b></div>';
                
                                     }
                                    if($resultat["date_prochain_evenement"]!='NULL'){
                                        $nom_premier_even= $resultat["date_prochain_evenement_nom"];
                                        $id= $resultat["date_prochain_evenement_id"];
                                        $date_premier_even=strftime("%A %d %B %Y",strtotime($resultat["date_prochain_evenement"]));
                                                
                                        $res.= '<div>'.utf8_encode($date_premier_even).'</div>';
                                    }else{
                                        $res.= '<div>Prochaine date à venir</div>';
                                    }
                                    $res.= '<span class="tt-les-infos"><a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">Toutes les infos</a></span>';
                
                                
                            $res.= '</div>';
                            $i++;
                        }
                        echo $res;
                        ?>
                </div>
                    </ul>



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