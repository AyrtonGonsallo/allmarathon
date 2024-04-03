<?php

// ini_set('display_errors', 1);

// ini_set('display_startup_errors', 1);

// error_reporting(E_ALL);



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



include("../classes/newscategorie.php");

include("../classes/news.php");

include("../classes/commentaire.php");

include("../classes/user.php");

include("../classes/pub.php");



$pub=new pub();



$pub728x90=$pub->getBanniere728_90("actualite")['donnees'];

$pub300x60=$pub->getBanniere300_60("actualite")['donnees'];

$pub300x250=$pub->getBanniere300_250("actualite")['donnees'];

$pub160x600=$pub->getBanniere160_600("actualite")['donnees'];

$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];






$news=new news();

$bref_news=$news->getBrefNews();





$sort = "";

if(isset($_GET['sort']) && $_GET['sort']!="") $sort =$_GET['sort'];



$page = 0;

if(isset($_GET['page']) && is_numeric($_GET['page'])) $page = intval($_GET['page']);



$key_word="";

if(isset($_GET['search']) && $_GET['search']) $key_word= $_GET['search'];




    $articles=$news->getAllArticlesDesc();



$next=$page+1;

if($page>1){

  $previous=$page-1;  

}

else{

    $previous=0;

}



$nbr_pages=$news->getNumberPages($sort,$key_word)['donnees'];

$articles_par_page=intval($nbr_pages['COUNT(*)']/20)+1;



$nc=new newscategorie();

$news=$nc->getAllNewsCat();



$commentaires=new commentaire();

$news_commentaires=$commentaires->getCommentairesNews();



$user=new user();

function slugify($text)

{

    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 

    $text = trim($text, '-');

    $text = strtolower($text);

    return $text;

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
setlocale(LC_TIME, "fr_FR","French");


?>

<!doctype html>

<html class="no-js" lang="fr">



<head>

    <meta charset="utf-8">

    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">

    <?php require_once("../scripts/header_script.php") ?>

    <title>Marathon : les actualit&eacute;s du marathon en France et dans le monde (rss)</title>

    <meta name="Description" content="Les actualités du marathon en France et dans le monde. News, résultats, interviews, vidéos, comptes-rendus, brèves, sondages. | allmarathon.fr" lang="fr" xml:lang="fr">

    
    <link rel="canonical" href="https://dev.allmarathon.fr/actualites-marathon.html" />


    <link rel="apple-touch-icon" href="apple-favicon.png">

    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />



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

<p class="browserupgrade">You are using an <strong>outdated</strong> rss browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>

<![endif]-->



    <?php 

// $test="TOURNOI DE RÔDAGE À NOISY-LE-GRAND";

// echo trim(preg_replace('~[^\\pL\d]~', '-',strtr(strtolower(str_replace('\\','',$test)), 'àáâãäåòóôõöøèéêëçìíîïùúûüÿñ', 'aaaaaaooooooeeeeciiiiuuuuyn')), '-').'<br>';

// $unwantedChars = array(',', '!', '?',':',' ',')','(');

// echo  trim(preg_replace('~[^\pL\d]~', strtolower(str_replace($unwantedChars,'-',$test)),'-'));

// die; 

include_once('nv_header-integrer.php'); ?>



    <div class="container page-content news">

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

                        <?php $cat_selected=($sort!=0 && $sort!="") ? $nc->getNewsCategoryByID($sort)['donnees']->getIntitule() : ""; ?>

                        <h1>Les actualités du marathon en France et dans le monde</h1>

        

                        <br>

                        <br>

                    </div>



                </div>



                <?php

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

                                $nb=sizeof($commentaires->getCommentaires($article->getId(),0,0)['donnees']);

                                $nb=($nb!=0)? $nb: '';

                                $coms=' <a title="Ajouter un commentaire" href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html#commentaires" class="com_news"><i class="fa fa-comment-o com_news" aria-hidden="true"></i> '.$nb.'</a>';

                                if($index==0 && $page==0 && $sort=='' && $key_word=='')

                                {

                                    $image_src='/images/news/'.substr($article->getDate(), 0,4).'/'.$article->getPhoto();

                                    $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/defaut.jpg';

                                    $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                    echo '<h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #222;" >'.$article->getTitre().'</a></h2>'; ?>
                                    <?php //echo $subheader; ?>
                                    <?php echo '<a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'</a>

                    <p class="detail-actus">'.$article->getChapo().'</p>

                    <a href="'.$lien_1.'" class="link-all"> '.$text_lien_1.'</a><br>

                    <a href="'.$lien_2.'" class="link-all"> '.$text_lien_2.'</a><br>

                    <a href="'.$lien_3.'" class="link-all"> '.$text_lien_3.'</a><br>

                    ';

                                }

                                else{

                                    $image_src='/images/news/'.substr($article->getDate(), 0,4).'/thumb_'.strtolower($article->getPhoto());

                                    $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';

                                    $img_a_afficher= '<img class="img-responsive" alt="" src="'.$src_a_afficher.'"/>';

                                    echo '<article class="row">

                                <div class="col-sm-5">

                                    <div class="article-img"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html">'.$img_a_afficher.'<strong>'.$cat.'</strong><img class="media" src="'.$img_src.'" alt=""></a></div>

                                </div>

                                <div class="col-sm-7 desc-img">

                                    <h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #222;" >'.$article->getTitre().' </a></h2>

                                    <p>'.$article->getChapo().'</p>

                                    <a href="'.$lien_1.'" class="link-all"> '.$text_lien_1.'</a><br>

                                    <a href="'.$lien_2.'" class="link-all"> '.$text_lien_2.'</a><br>

                                    <a href="'.$lien_3.'" class="link-all"> '.$text_lien_3.'</a>

                                </div>

                                </article>';

                                }

                                

                }

            ?>





               





            </div> <!-- End left-side -->



            <aside class="col-sm-4">

                



            </aside>

        </div>



    </div> <!-- End container page-content -->

<?php 

            //echo $pub768x90; 

           // include("produits_boutique.php");  

            ?>

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

    ga('send', 'pageview');*/

    </script>



    <script type='text/javascript'>

    function sortArticles() {

        selected = document.getElementById('reroutage').selectedIndex;

        sort = document.getElementById('reroutage')[selected].value;

        console.log(sort);

        if (sort != '') {

            window.location.href = '/actualites-marathon-' + sort + '--.html';

        } else {

            window.location.href = '/actualites-marathon.html';

        }

    }

    </script>



    <script type="text/javascript">

    function goToSearch() {

        var key = document.getElementById('search_val').value;

        window.location = "/actualites-marathon---" + key + '.html';

    }

    document.getElementById('search_val').onkeypress = function(e) {

        if (!e) e = window.event;

        var keyCode = e.keyCode || e.which;

        if (keyCode == '13') {

            var key = document.getElementById('search_val').value;

            window.location = "/actualites-marathon---" + key + '.html';

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