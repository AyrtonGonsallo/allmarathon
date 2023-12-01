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
    $videos=$vd->getVideoPerPage($page,$sort);
    
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
    <title>Marathon : les meilleures videos, les plus beaux ippons de la planète marathon.</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">

</head>

<body>



    <?php include_once('nv_header-integrer.php'); ?>



    <div class="container page-content athlètes">
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

                        <h1>JUDO : LES MEILLEURES VIDEOS, LES PLUS BEAUX IPPONS DE LA PLANÈTE JUDO.</h1>

                        <form action="" method="post" style="width:45%; display: inline-block;" class="form-inline"
                            role="form">
                            <div class="form-group" style="width:98%; white-space: nowrap; margin-bottom: 1px;">
                                <select onchange="sortVideo();" id="reroutage" name="annee" style="width: 100%;"
                                    class="form-control">
                                    <option value="">Catégories de vidéo</option>
                                    <option value="clip" <?php if($sort=="clip") echo "selected";?>>clip</option>
                                    <option value="combat" <?php if($sort=="combat") echo "selected";?>>combat</option>
                                    <option value="competition" <?php if($sort=="competition") echo "selected";?>>
                                        competition</option>
                                    <option value="technique" <?php if($sort=="technique") echo "selected";?>>technique
                                    </option>
                                    <option value="divers" <?php if($sort=="divers") echo "selected";?>>divers</option>
                                    <option value="reaction" <?php if($sort=="reaction") echo "selected";?>>réaction
                                    </option>
                                    <option value="trailer" <?php if($sort=="trailer") echo "selected";?>>trailer
                                    </option>
                                    <option value="interview" <?php if($sort=="interview") echo "selected";?>>interview
                                    </option>
                                </select>
                                <!--   <input type="search" placeholder="Recherche" class="form-control" style="width: 45%" />
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                         -->
                            </div>
                        </form>
                        <input type="search" placeholder="Recherche" class="form-control"
                            style="width: 45%; margin-bottom: 5px; display: inline-block;" id="search_val"></input>
                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1px; display: inline-block;"
                            onclick="goToSearch();"><i class="fa fa-search"></i></button>

                    </div>

                    <div class="col-sm-12">
                        <ul class="videos-marathon">
                            <?php
                            foreach ($videos['donnees'] as $video) {
                                $event_intitule="";
                                if($video['Evenement_id']!=0){
                                    $annee_event=substr($event->getEvenementByID($video['Evenement_id'])['donnees']->getDateDebut(),0,4);
                                    $ev_vd=$event->getEvenementByID($video['Evenement_id'])['donnees'];
                                    $video_intitule=$ev_vd->getNom()." ".$annee_event;
                                $event_intitule="<li><a href='/resultats-marathon-".$ev_vd->getId()."-".slugify($video_intitule).".html'>".$video_intitule."</a></li>";
                                }
                                $duree="<li style='list-style-type: none;'></li>";
                                if($video['Duree']!='')
                                $duree="<li>durée : ".$video['Duree']."</li>";
                                $img_top ='';
                                echo '<li class="row">
                            <ul>
                                <li class="col-sm-6">
                                    <ul>
                                        <li><a href="video-de-marathon-'.$video['ID'].'.html">'.$video['Titre'].'</a></li>'.$event_intitule.$duree.'
                                    </ul>
                                </li> 
                                <li class="col-sm-6"><a href="video-de-marathon-'.$video['ID'].'.html"><img src="'.$video['Vignette'].'" width="120" heigh="90" alt="" class="pull-right img-responsive"/>';
                                if($video['top_ippon']) $img_top ='<img src="../../images/pictos/badge.png" alt=""/>';
                                    echo $img_top.'</a></li>
                            </ul>
                        </li>';
                            }
                        ?>
                        </ul>

                        <div class="clearfix"></div>

                        <ul class="pager">
                            <?php 
                            if($next==$vid_par_page) $style_suivant='style="pointer-events: none;cursor: default;"';
                            else $style_suivant='';
                            if(intval($next)<2) $style_precedent='style="pointer-events: none;cursor: default;"';
                            else $style_precedent='';
                            if($sort!='') { 
                        ?>
                            <li><?php echo '<a href="/videos-de-marathon-'.$sort.'-'.$key_word.'-'.$previous.'.html"'.$style_precedent.'> Précédent</a>'; ?>
                            </li>
                            <li><?php echo $next; ?> / <?php echo $vid_par_page; ?></li>
                            <li><?php echo '<a href="/videos-de-marathon-'.$sort.'-'.$key_word.'-'.$next.'.html"'.$style_suivant.'> Suivant</a>'; ?>
                            </li>
                            <?php } else {
                         echo '<li><a href="/videos-de-marathon--'.$key_word.'-'.$previous.'.html"'.$style_precedent.'> Précédent</a></li>
                          <li>'.$next.' / '.$vid_par_page.'</li>
                        <li><a href="/videos-de-marathon--'.$key_word.'-'.$next.'.html"'.$style_suivant.'> Suivant</a> </li>';
                     }?>
                        </ul>
                    </div>
                </div>
            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"><a href=""><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <dt class="anniversaires">Résultats anciens</dt>
                <dd class="anniversaires">
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
                            echo '<li><a href="/resultats-marathon-'.$ev_archive->getId().'-'.trim(preg_replace('~[^\\pL\d]~', '-',strtr(strtolower(str_replace('  ',' ',str_replace(array('\\','(',')','-'),'',$nom_res_archive))), 'àáâãäåòóôõöøèéêëçìíîïùúûüÿñ', 'aaaaaaooooooeeeeciiiiuuuuyn')), '-').'.html">'.$cat_event." ".$type_event." (".$ev_archive->getSexe().") ".$ev_archive->getNom()." ".substr($ev_archive->getDateDebut(),0,4).'</a></li>';
                        }
                    ?>
                    </ul>
                </dd>

                <p class="ban"><a href=""><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>


                <!-- <dt class="facebook">Rejoignez-nous sur facebook !</dt>
            <dd class="facebook">
                <div class="fb-page"
                     data-href="https://www.facebook.com/allmarathonnet-108914759155897/"
                     data-width="310"
                     data-hide-cover="false"
                     data-show-facepile="true">
                </div>
            </dd> -->

            </aside>
        </div>

    </div> <!-- End container page-content -->

    <?php include('footer.inc.php'); ?>


    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/plugins.js"></script>
    <script src="../../js/jquery.jcarousel.min.js"></script>
    <script src="../../js/jquery.sliderPro.min.js"></script>
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
    function sortVideo() {
        selected = document.getElementById('reroutage').selectedIndex;
        sort = document.getElementById('reroutage')[selected].value;
        if (sort != '') {
            window.location.href = '/videos-de-marathon-' + sort + "--.html";
        } else {
            window.location.href = '/videos-de-marathon.html';
        }
    }
    </script>

    <script type="text/javascript">
    function goToSearch() {
        var key = document.getElementById('search_val').value;
        window.location = "/videos-de-marathon--" + key + "-.html";
    }
    document.getElementById('search_val').onkeypress = function(e) {
        if (!e) e = window.event;
        var keyCode = e.keyCode || e.which;
        if (keyCode == '13') {
            var key = document.getElementById('search_val').value;
            window.location = "/videos-de-marathon--" + key + "-.html";
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