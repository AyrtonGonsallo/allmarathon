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


$id_news_cat=$_GET['newscatID'];
$int_news_cat=$_GET['intcat'];


include("../classes/news.php");
include("../classes/champion.php");
include("../classes/user.php");
include("../classes/pub.php");
include("../classes/newscategorie.php");
include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");

$nc=new newscategorie();
$champion=new champion();

$pub=new pub();
$event=new evenement();
$ev_cat_event=new evCategorieEvenement();
$pub728x90=$pub->getBanniere728_90("actualite")['donnees'];
$pub300x60=$pub->getBanniere300_60("actualite")['donnees'];
$pub300x250=$pub->getBanniere300_250("actualite")['donnees'];
$pub160x600=$pub->getBanniere160_600("actualite")['donnees'];
$pub768x90=$pub->getBanniere768_90("actualite")['donnees'];
$getMobileAds=$pub->getMobileAds("actualite")['donnees'];
$user=new user();


$news=new news();
$page = 0;
if(isset($_GET['page']) && is_numeric($_GET['page'])) $page = intval($_GET['page']);


$articles=$news->getArticlesByCategoriePerPage1($page,$id_news_cat,);
$cat_news_details=$nc->getNewsCategoryByID($id_news_cat)['donnees'];


function changeDate($date) {
    $day    = array("dimanche","lundi","mardi","mercredi","jeudi","vendredi","samedi");
    $month  = array("janvier","fevrier","mars","avril","mai","juin","juillet","aout","septembre","octobre","novembre","decembre");
    $timestamp = mktime(0,0,0,substr($date, 5, 2),substr($date, 8, 2),substr($date, 0, 4));
    return $day[date("w",$timestamp)].date(" j ",$timestamp).$month[date("n",$timestamp)-1].date(" Y",$timestamp);
}
 function switch_cat($cat)
{
    switch ($cat) {
        case "Breve":
            return "En bref";
            break;
        case "Videos":
            return "Vidéos";
            break;
        case "Equipe de France":
            return "Équipe de France";
            break;
        default:
            return $cat;
                                }
}
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
    <title><?php echo str_replace('\\', '', str_replace('"', '\'', $cat_news_details->getIntitule()));?> | allmarathon.fr </title>
    <meta name="Description" content="<?php echo str_replace('\\', '', str_replace('"', '\'', $cat_news_details->getIntitule()));?>  " lang="fr" xml:lang="fr" />
    <?php echo '<link rel="canonical" href="https://dev.allmarathon.fr/categorie-actualites-marathon-'.$cat_news_details->getId().'-'.slugify($cat_news_details->getIntitule()).'.html" />';?>
    <meta name="robots" content="max-image-preview:large" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?php echo str_replace('\\', '', str_replace('"', '\'', $cat_news_details->getIntitule()));?>" />
    <meta property="og:description" content="<?php echo str_replace('\\', '', str_replace('"', '\'', $cat_news_details->getIntitule()));?>  " />
    <meta property="og:image" content="<?php echo 'https://dev.allmarathon.fr'.$img_src; ?>" />
    <meta property="og:url" content="<?php echo 'https://dev.allmarathon.fr/categorie-actualites-marathon-'.$cat_news_details->getId().'-'.slugify($cat_news_details->getIntitule()).'.html';?>" />

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">
    
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->
    <div id="fb-root"></div>
    

    <?php include_once('nv_header-integrer.php'); ?>

   

    <div class="container page-content news-detail">
        <div class="row banniere1 bureau">
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
                    <h1 class="news-detail "> <?php echo $cat_news_details->getIntitule();?> </h1>
                    <div><?php echo $cat_news_details->getDescription(); ?></div>
                    <section class="last_articles_part1 mt-0 bureau lazyblock">


                    <?php
                    $i==0;
                    foreach($articles['donnees'] as $index => $article){
                    $subheader="auteur : ".$article->getAuteur()." / ".utf8_encode(strftime("%A %d %B %Y",strtotime($article->getDate())))." / source : ".$article->getSource();
                        $cat="";

                        $lien_1="";

                        $text_lien_1="";

                        $lien_2="";

                        $text_lien_2="";

                        $lien_3="";

                        $text_lien_3="";

                        if($cat!="Videos" && $cat!="Photos" ) {$img_src="";}

                                    if($cat=="Photos") $img_src="../../images/pictos/photo-icon.png";

                                    if($cat=="Videos") $img_src="../../images/pictos/video-icon.png";

                                    $cat=switch_cat($cat);

                                    if($article->getLien1()!=""){

                                        $lien_1=$article->getLien1();

                                        $text_lien_1="> ".$article->getTextlien1();

                                    }

                                    if($article->getLien2()!=""){

                                        $lien_2=$article->getLien2();

                                        $text_lien_2="> ".$article->getTextlien2();

                                    }

                                    if($article->getLien3()!=""){

                                        $lien_3=$article->getLien3();

                                        $text_lien_3="> ".$article->getTextlien3();

                                    }

                                    $url_text=slugify($article->getTitre());

                                    

                                        $image_src='/images/news/'.substr($article->getDate(), 0,4).'/thumb_'.strtolower($article->getPhoto());

                                        $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';

                                        $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                        if($i==0){echo '<article class="row pt-10">';}else{echo '<article class="row">';}?>
                                        <?

                                

                                        echo '<div class="article-img"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'</a></div>

                                

                                    <div class="desc-img">

                                        <h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #000;" >'.$article->getTitre().' </a></h2>';
                                        
                                        
                                        if($article->getChampionID()){
                                            $chmp=$champion->getChampionById($article->getChampionID())["donnees"];
                                            echo "<a href='athlete-".$chmp->getId()."-".$chmp->getNom().".html' class='home-link mb-5 mr-5 '>Le palmarès de ". $chmp->getNom()."</a>";
                                        }
                                        if($article->getLien1()  ){
                                            if( $article->getEvenementID()>0 ){
                                                $evenement=$event->getEvenementByID($article->getEvenementID())["donnees"];
                                                $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();
                                                $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                                $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                                                $marathon= $event->getMarathon($evenement->getmarathon_id())['donnees'];
                                            }
                                            $lien_perso=$article->getLien1();
                                            $texte_perso=$article->getTextlien1();
                                            
                                            echo "<a href='".$lien_perso."' class='home-link mb-5 mr-5 '>".$texte_perso."</a>";
                                        }
                                        echo '</div>
                                    </article>';

                                    $i+=1;

                                    

                    }

                    ?>
                    <?if(sizeof($articles['donnees'])>0){?>
                        <div class="pager">
                        <ul>
                            <li><a id="back-link" class="<?php $class_none=($page>0)?"":"none"; echo $class_none;?>" href="categorie-actualites-marathon-<?php echo $id_news_cat?>-<?php $backpage=($page>0)?($page-1):0; echo $backpage;?>-<?php echo $int_news_cat?>.html">page précédente</a></li>
                            <li><a id="next-link" href="categorie-actualites-marathon-<?php echo $id_news_cat?>-<?php $nextpage=$page+1; echo $nextpage;?>-<?php echo $int_news_cat?>.html">page suivante</a></li>
                        </ul>
                        </div>
                   <?}?>
                    </section>
                        
                    </div>

                </div>
            </div>
            <aside class="col-sm-4">
                <p class="ban"><?php
                /*
                if($pub300x60 !="") {
                echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x60['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
                }*/
                ?></a>
                </p>

                <dt class="bref to_hide_mobile">
                    
                </dt>
         
                <dd class="bref to_hide_mobile marg_bot">

                    <ul class="clearfix">

                        
                <p class="ban"><?php
                    if($pub300x250 !="") {
                    echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
                    }
                    ?></a>
                </p>
                
               
                <div class="marg_bot"></div>
                <p class="ban ban_160-600">
                    <a href="">
                        <?php
                        if($pub160x600 !="") {
                            //var_dump($pub160x600["url"]); exit;
                            if($pub160x600["code"]==""){
                                echo "<a href=".'http://dev.allmarathon.fr/'.$pub160x600["url"]." target='_blank'><img src=".'../images/news/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";
                            }
                            else{
                                echo $pub160x600["code"];
                            }
                        /*echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";*/
                        }
                        ?>
                    </a>
                </p>
                <div class="marg_bot"></div>

            </aside>
        </div>

    </div> <!-- End container page-content -->

    <?php include_once('footer.inc.php'); ?>



    <script data-type='lazy' ddata-src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script data-type="lazy" ddata-src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script data-type="lazy" ddata-src="../../js/bootstrap.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/plugins.js"></script>
    <script data-type="lazy" ddata-src="../../js/jquery.jcarousel.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/jquery.sliderPro.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/easing.js"></script>
    <script data-type="lazy" ddata-src="../../js/jquery.ui.totop.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/herbyCookie.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/main.js"></script>
    <style>
        .text-act-marathon,. {
            display: none; /* Cacher initialement */
            opacity: 0;
            transition: opacity 1s ease-in-out; /* Transition en douceur */
        }
    </style>
    

    <?php
    if($user_id!=""){

         echo "<script type='text/javascript'>
        $('#com_but').on('click',function(e){
            document.location.href='/content/modules/add_commentaire.php?id_news=".$id_news_cat."&commentaire='+$('#message_champion').val();
       });
       </script>";
    }else{
         echo "<script type='text/javascript'>
            $('#com_but').on('click',function(e){
                    $('#SigninModal').modal('show');});
            </script>";
    }
?>
    <script type="text/javascript">
    pageSize = 5;

    $(function() {
        var pageCount = Math.ceil($(".line-content").size() / pageSize);

        for (var i = 0; i < pageCount; i++) {
            if (i == 0)
                $("#pagin_com").append('<li><a class="curent_page_com" href="#">' + (i + 1) + '</a></li>');
            else
                $("#pagin_com").append('<li><a href="#">' + (i + 1) + '</a></li>');
        }


        showPage(1);

        $("#pagin_com li a").click(function() {
            $("#pagin_com li a").removeClass("curent_page_com");
            $(this).addClass("curent_page_com");
            showPage(parseInt($(this).text()))
        });

    })

    showPage = function(page) {
        $(".line-content").hide();

        $(".line-content").each(function(n) {
            if (n >= pageSize * (page - 1) && n < pageSize * page)
                $(this).show();
        });
    }
    </script>

    <!--FaceBook-->
    <script>
        setTimeout(() => {
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.5";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        }, "10000");
    
    </script>
    
   
       

</body>

</html>