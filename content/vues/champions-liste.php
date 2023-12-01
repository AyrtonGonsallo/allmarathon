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
include("../classes/pays.php");
include("../classes/pub.php");

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("athlètes")['donnees'];
$pub300x60=$pub->getBanniere300_60("athlètes")['donnees'];
$pub300x250=$pub->getBanniere300_250("athlètes")['donnees'];
$pub160x600=$pub->getBanniere160_600("athlètes")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];

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

function slugify($text)
{
// Swap out Non "Letters" with a -
$text = preg_replace('/[^\pL\d]+/u', '-', $text); 

   // Trim out extra -'s
$text = trim($text, '-');
   // Make text lowercase
   $text = strtolower($text);
   return $text;
}

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
    <title>Champions de marathon, athlètes célèbres : palmarès, photos et vidéos.</title>
    <meta name="Description" lang="fr" content="Retrouvez les palmarès de <?php echo $nb_champs;?> coureurs, ainsi  que les photos et vidéos des athlètes et marathoniens célèbres. ">

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

                        <h1>Champions de marathon, athlètes français célèbres : palmarès, photos et vidéos</h1>

                        <form action="" method="post" class="form-inline" role="form">
                            <div class="form-group" style="width:100%;">
                                <input type="search" id="search_athlètes" placeholder="Recherche" class="form-control"
                                    style="width: 93%" />
                                <button type="button" onclick="goSearch_athlètes();" id="button_search_athlètet"
                                    class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-12">
                        <ul class="search-alpha list-inline list-unstyled well clearfix">
                            <li class="title">Recherche alphabétique :</li>
                            <li class="list-alpha">
                                <ul>

                                    <?php for($i='A';$i!='AA';$i++)
                                    echo ($i==strtoupper($order))?'<li class="active"><a href="cv-champions-de-marathon-'.strtolower($i).'.html">'.$i.'</a></li>':'<li><a href="cv-champions-de-marathon-'.strtolower($i).'.html">'.$i.'</a></li>';
                                ?>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <?php
                foreach ($champion->getListChampionsByInitial($order,$page)['donnees'] as $key => $chmp) {
                    if ($key % 2 == 0) {
                        if($chmp->getPaysID()){
                            if($pays->getFlagByAbreviation($chmp->getPaysID())['donnees']){
                                $flag=$pays->getFlagByAbreviation($chmp->getPaysID())['donnees']['Flag'];  
                              }
                              ($flag!='') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                            
                            $pays_ab=" (".$pays->getFlagByAbreviation($chmp->getPaysID())['donnees']['Abreviation'].")";
                        }
                        $nb_com=$champion->getNumberCom($chmp->getId())['donnees'];
                        ($nb_com['COUNT(*)']!=0) ? $nombre_com='<span><img src="../../images/pictos/buble.png" alt=""/></span>' : $nombre_com='';
                        
                        $nb_videos=$champion->getNumberVideos($chmp->getId())['donnees'];
                        ($nb_videos!=0) ? $nombre_videos='<span><img src="../../images/pictos/tv.png" alt=""/></span>' : $nombre_videos='';
                        
                        $nb_images=$champion->getNumberImages($chmp->getId())['donnees'];
                        ($nb_images!=0) ? $nombre_images='<span style="margin-right: 6px;"><img src="../../images/pictos/cam.png" alt=""/></span>' : $nombre_images='';
                        $champion_name=slugify($chmp->getNom());
                        echo '<ul class="col-sm-6 list">
                    <li><a href="athlète-'.$chmp->getId().'-'.$champion_name.'.html">'.$chmp->getNom().$pays_ab.'</a>
                        <ul class="list-inline">'.
                            $nombre_images.'    '.$nombre_videos.' '.$nombre_com.' '.$pays_flag.'
                            
                        </ul>
                    </li>
                </ul>';
                    }
                    else{
                        if($chmp->getPaysID()){
                           if($pays->getFlagByAbreviation($chmp->getPaysID())['donnees']){
                            $flag=$pays->getFlagByAbreviation($chmp->getPaysID())['donnees']['Flag'];  
                          }
                          ($flag!='') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                        
                          $pays_ab=" (".$pays->getFlagByAbreviation($chmp->getPaysID())['donnees']['Abreviation'].")";
                        }
                        $nb_com=$champion->getNumberCom($chmp->getId())['donnees'];
                        ($nb_com['COUNT(*)']!=0) ? $nombre_com='<span><img src="../../images/pictos/buble.png" alt=""/></span>' : $nombre_com='';
                        
                        $nb_videos=$champion->getNumberVideos($chmp->getId())['donnees'];
                        ($nb_videos!=0) ? $nombre_videos='<span><img src="../../images/pictos/tv.png" alt=""/></span>' : $nombre_videos='';
                        
                        $nb_images=$champion->getNumberImages($chmp->getId())['donnees'];
                        ($nb_images!=0) ? $nombre_images='<span><img src="../../images/pictos/cam.png" alt=""/></span>' : $nombre_images='';
                        $champion_name=slugify($chmp->getNom());
                        echo '<ul class="col-sm-6 list">
                    <li><a href="athlète-'.$chmp->getId().'-'.$champion_name.'.html">'.$chmp->getNom().$pays_ab.'</a>
                        <ul class="list-inline">'.
                            $nombre_images.$nombre_videos.$nombre_com.$pays_flag.'
                            
                        </ul>
                    </li>
                </ul>';
                    }
                }
                ?>
                </div>

                <div class="clearfix"></div>

                <ul class="pager">
                    <?php 
                            if($next==$nb_pages) $style_suivant='style="pointer-events: none;cursor: default;"';
                            else $style_suivant='';
                            if(intval($next)<2) $style_precedent='style="pointer-events: none;cursor: default;"';
                            else $style_precedent='';
                            $sort='';
                            echo '<li><a href="cv-champions-de-marathon-'.$previous.'-'.strtolower($order).'.html"'.$style_precedent.'> Précédent</a></li>
                          <li>'.$next.' / '.$nb_pages.'</li>
                        <li><a href="cv-champions-de-marathon-'.$next.'-'.strtolower($order).'.html"'.$style_suivant.'> Suivant</a> </li>';
                     
                     ?>
                </ul>

            </div> <!-- End left-side -->

            <aside class="col-sm-4 pd-top">
                <p class="ban"><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <dt class="anniversaires">ANNIVERSaires</dt>
                <dd class="anniversaires">
                    <ul class="clearfix">
                        <?php
                    foreach ($champion->getChampionBirthday()['donnees'] as $key => $ch) {
                        $age=$ch->ageBirthday($ch->getDateNaissance());
                        $flag=$pays->getFlagByAbreviation($ch->getPaysID())['donnees']['Flag'];
                        ($flag!='') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                        $ch_name=slugify($ch->getNom());
                        echo '<li><a href="athlète-'.$ch->getId().'-'.$ch_name.'.html">'.$ch->getNom().' ('.$age.' ans)</a></li>';
                    }
                    ?>

                    </ul>
                </dd>
                <div class="marg_bot"></div>
                <p class="ban"><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <div class="marg_bot"></div>
                <dt class="anniversaires">Derniers athlètes référencés</dt>
                <dd class="anniversaires">
                    <ul class="clearfix">
                        <?php
                       foreach ($champion->getLastChampions()['donnees'] as $key => $ch) {
                        
                        if($pays->getFlagByAbreviation($ch->getPaysID())['donnees']){
                          $flag=$pays->getFlagByAbreviation($ch->getPaysID())['donnees']['Flag'];  
                        }
                        ($flag!='') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                        $ch_name=slugify($ch->getNom());
                        echo '<li><a href="athlète-'.$ch->getId().'-'.$ch_name.'.html">'.$ch->getNom().' ('.$ch->getPaysID().') '.$pays_flag.'</a></li>';
                        }
                    ?>
                    </ul>
                </dd>
                <div class="marg_bot"></div>
                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
    //var_dump($pub160x600["url"]); exit;
    if($pub160x600["code"]==""){
        echo "<a href=".'http://allmarathon.fr/'.$pub160x600["url"]." target='_blank'><img src=".'../images/news/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";
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