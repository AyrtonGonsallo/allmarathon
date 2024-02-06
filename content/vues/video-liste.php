<?php
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

include("../classes/video.php");
include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/pub.php");

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("videos")['donnees'];
$pub300x60=$pub->getBanniere300_60("videos")['donnees'];
$pub300x250=$pub->getBanniere300_250("videos")['donnees'];
$pub160x600=$pub->getBanniere160_600("videos")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];


$ev_cat_event=new evCategorieEvenement();


$vd=new video();
$event=new evenement();
$archives=$event->getDernierResultatsArchive();

$sort = "";
if(isset($_GET['sort']) && $_GET['sort']!="") $sort =$_GET['sort'];

$page = 0;
if(isset($_GET['page']) && is_numeric($_GET['page'])) $page = intval($_GET['page']);

$key_word="";
if(isset($_GET['search']) && $_GET['search']) $key_word= $_GET['search'];

if($key_word!=""){
    $videos=$vd->getVideosViaSearch($key_word,$page);
    
}
else{
    $videos=$vd->getAllVideos($sort);
    
}

$next=$page+1;
$previous=$page-1;

$nbr_pages=$vd->getNumberPages($sort,$key_word)['donnees'];
$vid_par_page=intval($nbr_pages['COUNT(*)']/12)+1;
//$videos=$vd->getVideoPerPage($page,$sort);
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
    <title>Vidéos de marathon. Jeux olympiques, championnats du monde, championnats d'Europe, World Majors Marathon.</title>
    <meta name="Description" lang="fr" content="Allmarathon.fr, les plus belles videos de marathon : Résumé des courses, conseils entrainement marathon, interviews de marathoniens ">
    
    <meta property="og:title" content="Vidéos de marathon. Jeux olympiques, championnats du monde, championnats d'Europe, World Majors Marathon." />
    <meta property="og:description" content="Allmarathon.fr, les plus belles videos de marathon : Résumé des courses, conseils entrainement marathon, interviews de marathoniens." />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:image" content="https://allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="https://allmarathon.fr/videos-de-marathon.html" />

    <link rel="canonical" href="https://allmarathon.fr/videos-de-marathon.html" />
    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css"/>
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



<div class="container page-content athlètes mt-77 videos-liste">
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

                    <h1>Vidéos de marathon. Jeux olympiques, championnats du monde, championnats d'Europe, World Majors Marathon.</h1>
                    <h2>Retransmissions des courses en streaming, résumés, conseils d’entrainement pour le marathon en vidéo, interviews de marathoniens.</h2>

                    
                    
                </div>

                <div class="col-sm-12">
                    <ul class="videos-marathon lazyblock">
                        <?php
                            foreach ($videos['donnees'] as $video) {
                                $event_intitule="";
                                if($video['Evenement_id']!=0){
                                    /*
                                    $annee_event=substr($event->getEvenementByID($video['Evenement_id'])['donnees']->getDateDebut(),0,4);
                                    $ev_vd=$event->getEvenementByID($video['Evenement_id'])['donnees'];
                                    $ev_cat_ev=$ev_cat_event->getEventCatEventByID($ev_vd->getCategorieId())['donnees']->getIntitule();
                                    $video_intitule=$ev_cat_ev." ".$ev_vd->getNom()." ".$annee_event;
                                    $event_intitule="<li><a class='video_event' href='/resultats-marathon-".$ev_vd->getId()."-".slugify($video_intitule).".html'>".$video_intitule."</a></li>";
                                    */

                                    $evenement=$event->getEvenementByID($video['Evenement_id'])["donnees"];
                                    $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
            
                                    $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                    $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                    $res_event="";
                                    $res_event= "<a href='/resultats-marathon-".$evenement->getId()."-".slugify($nom_res_lien).".html' class='home-link mr-5 '><span class='material-symbols-outlined'>trophy</span> Résultats </a>";
                                
                                }
                                $duree="<li style='list-style-type: none;'></li>";
                                if($video['Duree']!=''){
                                    //$duree="<li>durée : ".$video['Duree']."</li>";
                                    $duree="<li><span class='material-symbols-outlined'>timer</span>Durée de la vidéo : ".$video['Duree']."</li>";
                                }
                                
                                $img_top ='';
                                echo '<div class="video-align-top video-grid-tab">
                                        
                                        <div class="mr-5"><a href="video-de-marathon-'.$video['ID'].'.html"><img src="'.$video['Vignette'].'"  alt="" class="video-liste-image img-responsive"/></a></div>
                                        <div class="video-t-d-res">
                                            <ul>
                                                <li><a href="video-de-marathon-'.$video['ID'].'.html" class="video_titre">'.$video['Titre'].'</a></li>'.$duree.'
                                            </ul>
                                            '.$res_event.'
                                        </div>
                                    </div>';
                            }
                        ?>
                    </ul>

                    <div class="clearfix"></div>

                  
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
            <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
    //var_dump($pub160x600["url"]); exit;
    if($pub160x600["code"]==""){
        echo "<a href=".'https://allmarathon.fr/'.$pub160x600["url"]." target='_blank'><img src=".'../images/news/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";
    }
    else{
        echo $pub160x600["code"];
    }
/*echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";*/
}
?></a></p>
            <div class="marg_bot"></div>
            <div class="dailymotion-widget" data-placement="58dcd1d2a716ff001755f5f9"></div><script>(function(w,d,s,u,n,e,c){w.PXLObject = n; w[n] = w[n] || function(){(w[n].q = w[n].q || []).push(arguments);};w[n].l = 1 * new Date();e = d.createElement(s); e.async = 1; e.src = u;c = d.getElementsByTagName(s)[0]; c.parentNode.insertBefore(e,c);})(window, document, "script", "//api.dmcdn.net/pxl/client.js", "pxl");</script>
            <div class="marg_bot"></div>
            
            
        </aside>
    </div>

        <?php //include("produits_boutique.php"); ?>
</div> <!-- End container page-content -->

<?php include('footer.inc.php'); ?>


<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')</script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/plugins.js"></script>
<script src="../../js/jquery.jcarousel.min.js"></script>
<script src="../../js/jquery.sliderPro.min.js"></script>
<script src="../../js/easing.js"></script>  
<script src="../../js/jquery.ui.totop.min.js"></script>
<script src="../../js/herbyCookie.min.js"></script>
<script src="../../js/main.js"></script>

<script>
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

 ga('create', 'UA-1833149-1', 'auto');
 ga('send', 'pageview');

</script> 



<script type="text/javascript" >
     $(document).ready(function() {

        if(window.outerWidth < 740) {
            $(".lazyblock div").slice(12).hide();
           // $(".lazyblock article").slice(6).hide();

                var mincount = 2;
                var maxcount = 12;
                

                $(window).scroll(function () {
                    //console.log("left: ",$(window).scrollTop() + $(window).height())
                    //console.log("right: ",$(document).height() - 20)
                    if ($(window).scrollTop() + $(window).height() >= 2000) {
                        $(".lazyblock div").slice(mincount, maxcount).slideDown(100);
                      //  $(".lazyblock article").slice(mincount, maxcount).slideDown(100);
                        mincount = mincount + 2;
                        maxcount = maxcount + 2

                    }
                });
        }else{
            $(".lazyblock div").slice(12).hide();
            //$(".lazyblock article").slice(6).hide();

                var mincount = 2;
                var maxcount = 12;
                

                $(window).scroll(function () {
                    //console.log("left: ",$(window).scrollTop() + $(window).height())
                    //console.log("right: ",$(document).height() - 20)
                    if ($(window).scrollTop() + $(window).height() >= $(document).height() - 500) {
                        $(".lazyblock div").slice(mincount, maxcount).slideDown(100);
                        //$(".lazyblock article").slice(mincount, maxcount).slideDown(100);
                        mincount = mincount + 2;
                        maxcount = maxcount + 2

                    }
                });
        }

        })
    function sortVideo(){
        selected = document.getElementById('reroutage').selectedIndex;
        sort = document.getElementById('reroutage')[selected].value;
        if(sort!=''){
        window.location.href = '/videos-de-marathon-'+sort+"--.html";  
        }
        else{
        window.location.href = '/videos-de-marathon.html';
        }
    }

</script>

<script type="text/javascript">
    function goToSearch() {
        var key = document.getElementById('search_val').value;
       window.location = "/videos-de-marathon--" + key+"-.html";
    }
    document.getElementById('search_val').onkeypress = function(e){
    if (!e) e = window.event;
    var keyCode = e.keyCode || e.which;
    if (keyCode == '13'){
       var key = document.getElementById('search_val').value;
       window.location = "/videos-de-marathon--" + key+"-.html";
      return false;
    }
  }

</script>
<!--FaceBook-->
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.5";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<!--Google+-->
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script type="text/javascript" id="ean-native-embed-tag" src="//cdn.elasticad.net/native/serve/js/nativeEmbed.gz.js"></script>
</body>
</html>
