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



$news=new news();
$bref_news=$news->getBrefNews();


$sort = "";
if(isset($_GET['sort']) && $_GET['sort']!="") $sort =$_GET['sort'];

$page = 0;
if(isset($_GET['page']) && is_numeric($_GET['page'])) $page = intval($_GET['page']);

$key_word="";
if(isset($_GET['search']) && $_GET['search']) $key_word= $_GET['search'];

if($key_word!=""){
    $articles=$news->getArticlesViaSearch($key_word,$page);
}
else{
    $articles=$news->getArticlesPerPage($page,$sort);
}
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

?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Marathon : les actualit&eacute;s du marathon en France et dans le monde</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
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
                        <?php $cat_selected=($sort!=0 && $sort!="") ? $nc->getNewsCategoryByID($sort)['donnees']->getIntitule() : ""; ?>
                        <h1>Marathon : les actualités du marathon en France et dans le monde -
                            <strong><?php echo switch_cat($cat_selected); ?></strong>
                        </h1>
                        <form method="post" style="width:45%; display: inline-block;" class="form-inline" role="form">
                            <div class="form-group" style="width:98%; white-space: nowrap; margin-bottom: 1px;">
                                <select onchange="sortArticles();" id="reroutage" name="annee" style="width: 100%; "
                                    class="form-control">
                                    <option value="">Catégories d'actualités</option>
                                    <?php
                                    foreach ($news['donnees'] as $news_cat) {
                                        // echo '<li><a href="#">'. $news_cat->getIntitule().'</a></li>';
                                        $sel="";
                                        if($sort==$news_cat->getId()) $sel=" selected ";
                                        echo '<option value="'.$news_cat->getId().'"'.$sel.">". switch_cat($news_cat->getIntitule()).'</option>';
                                    }
                                ?>
                                </select>

                                <!-- <input type="search" placeholder="Recherche" class="form-control" style="width: 45%" /> -->
                                <!-- <a href="http://localhost/allmarathon_nv/www/content/vues/news.php?search=Française"><button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button></a> -->
                            </div>
                        </form>

                        <input type="search" placeholder="Recherche" class="form-control"
                            style="width: 45%; margin-bottom: 5px; display: inline-block;" id="search_val"></input>
                        <button type="submit" class="btn btn-primary" style="margin-bottom: 1px; display: inline-block;"
                            onclick="goToSearch();"><i class="fa fa-search"></i></button>
                        <br>
                        <br>
                    </div>

                </div>

                <?php
            foreach($articles['donnees'] as $index => $article){
                    $cat=$nc->getNewsCategoryByID($article->getCategorieID())['donnees']->getIntitule();
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
                                if($index==0 && $page==0 && $sort=='' && $key_word=='')
                                {
                                    $url_text=slugify($article->getTitre());
                                    echo '<h2><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html" style="color: #222;" >'.$article->getTitre().'</a></h2>
                    <a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html"><img src="../../images/news/'.substr($article->getDate(), 0,4).'/'.$article->getPhoto().'" class="img-responsive" alt=""/></a>
                    <p style="margin-top: 20px;">'.$article->getChapo().'</p>
                    <a href="'.$lien_1.'" class="link-all"> '.$text_lien_1.'</a><br>
                    <a href="'.$lien_2.'" class="link-all"> '.$text_lien_2.'</a><br>
                    <a href="'.$lien_3.'" class="link-all"> '.$text_lien_3.'</a><br>
                    ';
                                }
                                else{
                                    $url_text=slugify($article->getTitre());
                                    echo '<article class="row">
                                <div class="col-sm-5">
                                    <div class="article-img"><a href="/actualite-marathon-'.$article->getId().'-'.$url_text.'.html"><img src="../../images/news/'.substr($article->getDate(), 0,4).'/'.$article->getPhoto().'" alt="" class="img-responsive"><strong>'.$cat.'</strong><img class="media" src="'.$img_src.'" alt=""></a></div>
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


                <ul class="pager">
                    <?php 
                            if($next==$articles_par_page) $style_suivant='style="pointer-events: none;cursor: default;"';
                            else $style_suivant='';
                            if(intval($next)<2) $style_precedent='style="pointer-events: none;cursor: default;"';
                            else $style_precedent='';
                            if($sort!='') {

                        ?>
                    <li id="precedent_btn">
                        <?php echo '<a href="/actualites-marathon-'.$sort.'-'.$previous.'-'.$key_word.'.html" '.$style_precedent.'> Précédent</a>'; ?>
                    </li>
                    <li><?php echo $next; ?> / <?php echo $articles_par_page; ?></li>
                    <li id="suivant_btn">
                        <?php echo '<a href="/actualites-marathon-'.$sort.'-'.$next.'-'.$key_word.'.html" '.$style_suivant.'> Suivant</a>'; ?>
                    </li>
                    <?php } else {
                         echo '<li id="precedent_btn"><a href="/actualites-marathon--'.$previous.'-'.$key_word.'.html" '.$style_precedent.'> Précédent</a></li>
                          <li>'.$next.' / '.$articles_par_page.'</li>
                        <li id="suivant_btn"><a href="/actualites-marathon--'.$next.'-'.$key_word.'.html" '.$style_suivant.'> Suivant</a> </li>';
                     }?>
                </ul>


            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"><a href=""><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <dt class="bref">EN BREF</dt>
                <dd class="bref">
                    <ul class="clearfix">
                        <?php
                    foreach ($bref_news['donnees'] as $article_bref) {
                        $url_text=slugify($article_bref->getTitre());
                        echo '<li><a href="/actualite-marathon-'.$article_bref->getId().'-'.$url_text.'.html"><span>'.date("d-m", strtotime($article_bref->getDate())).'</span>&nbsp;'.$article_bref->getTitre().'</a></li>';
                        }
                    ?>
                        <li class="last"><a href="actualites-marathon-11--.html">[+] de brèves</a></li>
                    </ul>
                </dd>
                <p class="ban"><a href=""><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <dt class="bref">commentaires</dt>
                <dd class="bref">
                    <ul class="clearfix">
                        <?php
                    foreach ($news_commentaires['donnees'] as $com) {
                        $auteur_com=$user->getUserById($com->getUser_id())['donnees']->getUsername();
                        if(strlen($com->getCommentaire()) < 85){
                        echo '<li><a href="">&laquo;'.$com->getCommentaire().'&raquo;<br/><span>'.$auteur_com.'</span></a></li>';
                        }
                        else{
                        echo '<li><a href="">&laquo;'.substr($com->getCommentaire(), 0, 85).'...&raquo;<br/><span>'.$auteur_com.'</span></a></li>';

                        }
                    }
                    ?>
                    </ul>
                </dd>

                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>


                <br />

                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
            <dd class="facebook">
                <div class="fb-page"
                     data-href="https://www.facebook.com/allmarathonnet-108914759155897/"
                     data-width="310"
                     data-hide-cover="false"
                     data-show-facepile="true">
                </div>
            </dd> -->
                <br>

            </aside>
        </div>

    </div> <!-- End container page-content -->

    <?php include_once('footer.inc.php'); ?>



    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/plugins.js"></script>
    <script src="../../js/jquery.jcarousel.min.js"></script>
    <script src="../../js/jquery.sliderPro.min.js"></script>
    <script src="../../js/main.js"></script>




    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->

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