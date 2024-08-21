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
}  else {
    $user_session='';
}

include("../classes/news.php");
include("../classes/evenement.php");
include("../classes/champion.php");
include("../classes/video.php");
include("../classes/pays.php");
include("../classes/pub.php");
include("../classes/evCategorieEvenement.php");
include("../classes/evCategorieAge.php");
require_once '../database/connexion.php';

$ev_cat_event=new evCategorieEvenement();
$ev_cat_age=new evCategorieAge();
$pays=new pays();
$pub=new pub();
$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];

$page=0;

$key_search = $_POST['recherche_glob'];

if(isset($_GET['key_search']) && $_GET['key_search']!="") $key_search =$_GET['key_search'];

$vd=new video();
$videos=$vd->getVideosViaSearch($key_search,"page_resultat")['donnees'];

$champion=new champion();
$champions=$champion->getListChampionsByInitial($key_search,"page_resultat")['donnees'];

$event=new evenement();
$results=$event->getResultsViaSearch($key_search,"page_resultat")['donnees'];

try{
    $req = $bdd->prepare("SELECT * FROM marathons where UPPER(nom) like :search");
    $req->bindValue('search', '%'.mb_strtoupper( $key_search).'%', PDO::PARAM_STR);
    $req->execute();
    $marathons= array();
    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
      array_push($marathons, $row);
    }
}
catch(Exception $e){
    die('Erreur : ' . $e->getMessage());
}

$news=new news();
$articles=$news->getArticlesViaSearch($key_search,"page_resultat")['donnees'];

$vd_txt=(sizeof($videos)!=0) ? " - <a href='#videos' class='anchor_link'>vidéos (".sizeof($videos).")</a>":"";
$champions_txt=(sizeof($champions)!=0) ? " - <a href='#athlètes' class='anchor_link'>athlètes (".sizeof($champions).")</a>":"";
$results_txt=(sizeof($results)!=0) ? " - <a href='#resultats'  class='anchor_link'>résultats (".sizeof($results).")</a>":"";
$articles_txt=(sizeof($articles)!=0) ? " <a href='#' class='anchor_link'>actus (".sizeof($articles).")</a>":"";
$marathons_txt=(sizeof($marathons)!=0) ? " <a href='#' class='anchor_link'>marathons (".sizeof($marathons).")</a>":"";

function slugify($text)
{
    $text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}

$nombre_res=sizeof($videos)+sizeof($champions)+sizeof($results)+sizeof($articles)+sizeof($marathons);
setlocale(LC_TIME, "fr_FR","French");
?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Marathon - résultats de la recherche " <?php echo $key_search; ?> "</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../css/responsive.css">


</head>

<body>

    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlete-detail">
        <div class="row banniere1">
            <a href="" class="col-sm-12"><?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side resultat-detail recherche-blocs">
            <div class="row banniere1">

            <div class="col-sm-12"></div>

            </div>
                <div class="row">
                    <div class="col-sm-12">
                        <h2>Nous avons trouvé <?php echo $nombre_res; ?> résultats sur votre recherche "<?php echo $key_search; ?>"</h2>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">

                            <ul class="row">
                                <li class="col-sm-12">
                                    <?php if(sizeof($articles)!=0){ ?>
                                    <dt id="actus">Actus</dt>
                                    <dd>
                                        <ul>
                                            <?php
                                			foreach ($articles as $article) {
                                				echo '<li>"<a href="/actualite-marathon-'.$article->getId().'-'.slugify($article->getTitre()).'.html">'.$article->getTitre().'</a>" ('.date("d/m/Y",strtotime($article->getDate())).')</li>';
                                			}
                                			?>
                                        </ul>
                                    </dd>
                                    <?php }
                                    if (sizeof($results)!=0){?>

                                    <dt class='anchor' id="resultats">Résultats</dt>
                                    <dd>
                                        <ul>
                                            <?php
                                				foreach ($results as $resultat) {
                                					$cat_event=$ev_cat_event->getEventCatEventByID($resultat['CategorieID'])['donnees']->getIntitule();
                                					$type_event="";
													$cat_age=$ev_cat_age->getEventCatAgeByID($resultat['CategorieageID'])['donnees']->getIntitule();
                            						$nom_res=$cat_event.' '.$type_event.' '.$cat_age.' ('.$resultat['Sexe'].') - '.$resultat['Nom'].' - '.substr($resultat['DateDebut'],0,4);
													$nom_res_lien=$cat_event.' - '.$resultat['Nom'].' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut'])));
                                                    echo '<li><a href="/resultats-marathon-'.$resultat['ID'].'-'.slugify($nom_res_lien).'.html">'.$nom_res.'</a> </li>';
                                				}
                                			?>

                                        </ul>
                                    </dd>
                                    <?php }
                                    if (sizeof($videos)!=0){?>

                                    <dt class='anchor' id="videos">Vidéos</dt>
                                    <dd>
                                        <ul>
                                            <?php
                                                foreach ($videos as $video) {
                                                    $duree=($video['Duree']!='') ? " (".$video['Duree'].")" : "";
                                                    echo '<li> <a href="video-de-marathon-'.$video['ID'].'.html"> '.$video['Titre'].$duree.' </a> </li>';
                                                }
                                            ?>

                                        </ul>
                                    </dd>
                                    <?php }
                                    if (sizeof($champions)!=0){?>

                                    <dt class='anchor' id="athlètes">athlètes</dt>
                                    <dd>
                                        <ul>
                                            <?php 
                                			foreach ($champions as $chmp) {
                                				echo '<li> <a href="athlete-'.$chmp->getId().'-'.slugify($chmp->getNom()).'.html">'.$chmp->getNom().' ('.$pays->getFlagByAbreviation($chmp->getPaysID())['donnees']['Abreviation'].') </a> </li>';
                                			}
                                			?>
                                        </ul>
                                    </dd>
                                    <?php }?>
                                    <?php 
                                    if (sizeof($marathons)!=0){?>

                                    <dt class='anchor' id="marathons">marathons</dt>
                                    <dd>
                                        <ul>
                                            <?php 
                                			foreach ($marathons as $resultat) {
                                                $nom_res= $resultat['nom'];
                                                echo '<li><a href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">'.$nom_res.' ('.$resultat['PaysID'].') </a></li>';
                                            }
                                			?>
                                        </ul>
                                    </dd>
                                    <?php }?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x60['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
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
        echo "<a href=".'https://dev.allmarathon.fr/'.$pub160x600["url"]." target='_blank'><img src=".'../images/news/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";
    }
    else{
        echo $pub160x600["code"];
    }
/*echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";*/
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
                <br>


            </aside>
        </div>

    </div> <!-- End container page-content -->


    <?php include_once('footer.inc.php'); ?>

    <style type="text/css">

    </style>

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

    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
    $('a[href^="#"]').click(function() {
        var the_id = $(this).attr("href");

        $('html, body').animate({
            scrollTop: $(the_id).offset().top
        }, 1000);
        return false;
    });
    </script>

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