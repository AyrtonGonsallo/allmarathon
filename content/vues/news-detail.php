<?php header("Cache-Control: max-age=2592000");
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

if(!empty($_SESSION['commentaire_error'])){
    $msg_com=" <span style='color:red' > ".$_SESSION['commentaire_error']."</span>";
    unset($_SESSION['commentaire_error']);}else{$msg_com="";}

if(!empty($_SESSION['commentaire_success'])){
    $msg_com=" <span style='color:green' > ".$_SESSION['commentaire_success']."</span>";
    unset($_SESSION['commentaire_success']);}else{$msg_com="";}

$id_news=$_GET['newsID'];



include("../classes/news.php");
include("../classes/commentaire.php");
include("../classes/user.php");
include("../classes/pub.php");
include("../classes/newscategorie.php");
include("../classes/newsgalerie.php");

$nc=new newscategorie();
$new_galerie=new newsgalerie();
$galerie=$new_galerie->getGalerieByNewsID($id_news)['donnees'];

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("actualite")['donnees'];
$pub300x60=$pub->getBanniere300_60("actualite")['donnees'];
$pub300x250=$pub->getBanniere300_250("actualite")['donnees'];
$pub160x600=$pub->getBanniere160_600("actualite")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];
$user=new user();

$commentaire=new commentaire();
$coms=$commentaire->getCommentaires($id_news,0,0)['donnees'];

$news=new news();
$bref_news=$news->getBrefNews();
$news_details=$news->getNewsById($id_news)['donnees'];
$news_cat=$news_details->getCategorieID();
$news_suggestion=$news->getSuggestionByCat($news_cat,$id_news)['donnees'];
//$news_cat_intitule=$nc->getNewsCategoryByID($news_cat)['donnees']->getIntitule();

if($news_details->getUrl()!="") header("Location: ".$news_details->getUrl());
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
$tab = explode('-',$news_details->getDate());
                        $yearNews  = $tab[0];
                        $img_src='/images/news/'.$yearNews.'/'.$news_details->getPhoto();
                        $alt = ($news_details->getLegende())?'alt="'.$news_details->getLegende().'"':'alt="allmarathon news image"';
                       
                        
?>

<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title><?php echo str_replace('\\', '', str_replace('"', '\'', $news_details->getTitre()));?> | allmarathon.fr </title>
    <meta name="Description" content="<?php echo str_replace('\\', '', str_replace('"', '\'', $news_details->getTitre()));?>, <?php echo str_replace('\\', '', str_replace('"', '\'', $news_details->getChapo()));?> " lang="fr" xml:lang="fr" />
    <?php echo '<link rel="canonical" href="https://allmarathon.fr/actualite-marathon-'.$news_details->getId().'-'.slugify($news_details->getTitre()).'.html" />';?>
    <meta name="robots" content="max-image-preview:large" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="<?php echo str_replace('\\', '', str_replace('"', '\'', $news_details->getTitre()));?>" />
    <meta property="og:description" content="<?php echo str_replace('\\', '', str_replace('"', '\'', $news_details->getTitre()));?>, <?php echo str_replace('\\', '', str_replace('"', '\'', $news_details->getChapo()));?> " />
    <meta property="og:image" content="<?php echo 'https://allmarathon.fr'.$img_src; ?>" />
    <meta property="og:url" content="<?php echo 'https://allmarathon.fr/actualite-marathon-'.$news_details->getId().'-'.slugify($news_details->getTitre()).'.html';?>" />

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "NewsArticle",
      "headline": "<?php echo str_replace('"',"",$news_details->getTitre()); ?>",
      "image": [
        "<?php echo 'https://allmarathon.fr'.$img_src; ?>"
       ],
      "datePublished": "<?php echo date(DATE_ISO8601, strtotime($news_details->getDate())); ?>",
      "dateModified": "<?php echo date(DATE_ISO8601, strtotime($news_details->getDate())); ?>",
      "author": [{
          "@type": "Person",
          "name": "<?php echo $news_details->getAuteur(); ?>"
      },{
          "@type": "Website",
          "name": "<?php echo $news_details->getSource(); ?>"
      }]
    }
    </script>
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->
    <div id="fb-root"></div>
    <script>
         setTimeout(() => {
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.6";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        }, "10000");
    
    </script>

    <?php include_once('nv_header-integrer.php'); ?>


    <div class="container page-content athlete-detail">
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


                        <?php 
                    $source=($news_details->getSource()) ? "source : ".$news_details->getSource() : "";
                    echo'<h1 class="news-detail">'.$news_details->getTitre().'</h1>'; ?>

                        <h2 class="mini_h2"><?php echo "auteur : ".$news_details->getAuteur()." / "; ?>
                            <?php echo '<span style="margin-top: 7px;font-family: MuseoSans-500;text-transform: none;">'.changeDate($news_details->getDate()).' / '.$source.'</span>' ; ?>
                            <script>
                            setTimeout(() => {
                                (function() { // DON'T EDIT BELOW THIS LINE
                                    var d = document, s = d.createElement('script');
                                    s.src = '//allmarathon.disqus.com/count.js';
                                    s.setAttribute('data-timestamp', +new Date());
                                    s.setAttribute('id', "dsq-count-scr");
                                    (d.head || d.body).appendChild(s);
                                    })();
                            }, "10000");
                            
                        </script>
                            <a style="float: right;font-weight: bold;" href="<?php echo 'https://allmarathon.fr/actualite-marathon-'.$news_details->getId().'-'.slugify($news_details->getTitre()).'.html';?>#disqus_thread"># COMMENTAIRES</a>


                        </h2>

                        <?php if(sizeof($galerie)!=0) {?>
                        <div class="slider-pro" id="my-slider">
                            <div class="sp-slides">
                                <?php
                            foreach ($galerie as $key => $value) {
                               echo '<div class="sp-slide">
                                <img class="sp-image" src="/images/news/'.$value->getPath().'"/>
                                <img class="sp-thumbnail" src="/images/news/'.$value->getPath().'"/>
                            </div>';
                            }
                             
                            ?>
                            </div>
                        </div>
                        <?php }else{
                        $tab = explode('-',$news_details->getDate());
                        $yearNews  = $tab[0];
                        $img_src='/images/news/'.$yearNews.'/'.$news_details->getPhoto();
                        $img_src_mobile='/images/news/'.$yearNews.'/thumb_'.$news_details->getPhoto();
                        $alt = ($news_details->getLegende())?'alt="'.$news_details->getLegende().'"':'alt="allmarathon news image"';
                        if ($img_src)
                            {
                                echo '<img class="sp-image bureau" '.$alt.' style="max-width: 100%;"src="'.$img_src.'"/>';
                                echo '<img class="sp-image mobile" '.$alt.' width="400px" height="auto" src="'.$img_src_mobile.'"/>';
                            }
                        }
                ?>
                        <div class="row" style="margin: 10px 0;">

                            <?php include_once("shareButtons.php"); ?>
                        </div>

                        <br>
                        <p class="title-act-marathon" style="font-size: 16px;font-weight:bold; margin-top: 10px;">
                            <?php echo $news_details->getChapo(); ?></p>
                        <p style="font-size: 16px; margin-top: 10px;"><?php echo $news_details->getTexte(); ?></p>
                        <br />
                        <?php  
                        $lien_1= ($news_details->getLien1()!="") ? '<a href="'.$news_details->getLien1().'" class="link-all"> '.$news_details->getTextlien1().'</a><br>' : "";
                        $lien_2=($news_details->getLien2()!="") ? '<a href="'.$news_details->getLien2().'" class="link-all"> '.$news_details->getTextlien2().'</a><br>': "";
                        $lien_3=($news_details->getLien3()!="") ? '<a href="'.$news_details->getLien3().'" class="link-all"> '.$news_details->getTextlien3().'</a><br>': "";
                        
                        echo $lien_1.$lien_2.$lien_3; ?>
                        <br />

                        <div id="disqus_thread"></div>
                        <script>
                            /**
                            *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                            *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables    */
                            /*
                            var disqus_config = function () {
                            this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                            this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                            };
                            */
                            setTimeout(() => {
                                (function() { // DON'T EDIT BELOW THIS LINE
                                    var d = document, s = d.createElement('script');
                                    s.src = 'https://allmarathon.disqus.com/embed.js';
                                    s.setAttribute('data-timestamp', +new Date());
                                    (d.head || d.body).appendChild(s);
                                    })();
                            }, "10000");
                            
                        </script>
                        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>


                    </div>

                </div>
            </div>
            <aside class="col-sm-4">
                <p class="ban"><?php
                /*
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x60['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}*/
?></a></p>

                <div class="for_mobile">
                    <dt class="bref">EN BREF</dt>
                    <dd class="bref">
                        <ul class="clearfix">
                            <?php
                    foreach ($bref_news['donnees'] as $article_bref) {
                        echo '<li><a href="/actualite-marathon-'.$article_bref->getId().'-'.slugify($article_bref->getTitre()).'.html"><span>'.date("d-m", strtotime($article_bref->getDate())).'</span>&nbsp;'.$article_bref->getTitre().'</a></li>';
                        }
                    ?>
                            <li class="last"><a href="actualites-marathon-11--.html">[+] de brèves</a></li>
                        </ul>
                    </dd>
                    <div class="marg_bot"></div>
                </div>
                <p class="ban"><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                
                <div class="marg_bot"></div>
                <dt class="suggestions">suggestions d’articles</dt>
                <dd class="suggestions">
                    <ul class="clearfix">
                        <?php
                    foreach ($news_suggestion as $key => $value) {
                        $image_src='/images/news/'.substr($value->getDate(), 0,4).'/thumb_'.strtolower($value->getPhoto());
                        $src_a_afficher= ($image_src) ? $image_src : '/images/news/2015/thumb_defaut.jpg';
                        $img_a_afficher= '<img class="img-responsive news_suggestion" alt="" src="'.$src_a_afficher.'"/>';
                        echo '<li><a href="/actualite-marathon-'.$value->getId().'-'.slugify($value->getTitre()).'.html"><span>'.$img_a_afficher.'</span> <strong>'.$value->getTitre().'</strong></a></li>';
                    }

                    ?>
                        <li></li>
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

   

    <?php
    if($user_id!=""){

         echo "<script type='text/javascript'>
        $('#com_but').on('click',function(e){
            document.location.href='/content/modules/add_commentaire.php?id_news=".$id_news."&commentaire='+$('#message_champion').val();
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