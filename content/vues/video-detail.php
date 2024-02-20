<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// (!empty($_SESSION['auth_error'])) ? $erreur_auth=$_SESSION['auth_error'] : $erreur_auth='';
if(!empty($_SESSION['auth_error'])) {
   $erreur_auth=$_SESSION['auth_error'];
   unset($_SESSION['auth_error']);
}else $erreur_auth='';
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
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!empty($_SESSION['user'])) {
$user_session=$_SESSION['user'];
$erreur_auth='';
}  else $user_session='';

$id=$_GET['videoID'];

include("../classes/pub.php");
include("../classes/video.php");
include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/technique.php");
include("../classes/champion.php");
include("../classes/commentaire.php");
include("../classes/user.php");

$user=new user();

$commentaire=new commentaire();
$coms=$commentaire->getCommentaires(0,$id,0)['donnees'];

$vd=new video();
$video=$vd->getVideoById($id)['donnees'];

$champion=new champion();
if($video->getChampion_id()!=0){
   $champ=$champion->getChampionById($video->getChampion_id())['donnees'];
    $champ_name=$champ->getNom(); 
    $videos_champ=$vd->getVideosByChampSuggest($champ->getId(),$id)['donnees'];
}else{
    $champ_name=''; 

}
$last_videos=$vd->getLastNVideos(5);
$ev_cat_event=new evCategorieEvenement();

$evenement=new evenement();
$event_name="";
$annee="";
$date="";
$id=0;
$ev_cat_ev="";
if($video->getEvenement_id()!=0) {
    $event=$evenement->getEvenementByID($video->getEvenement_id())['donnees'];
    $date=$event->getDateDebut();
    $event_name=$event->getNom();
    $id=$event->getId();
    $annee=substr($event->getDateDebut(),0,4);
    $ev_cat_ev=$ev_cat_event->getEventCatEventByID($event->getCategorieId())['donnees']->getIntitule();
    }

// print_r(expression)

$technique=new technique();
if($video->getTechnique_id()!=0)
    {
        $tech1= $technique->getTechniqueById($video->getTechnique_id())['donnees']->getNom()." / ";
        $videos_tech1=$vd->getVideoByTechnique($tech1,$id)['donnees'];
    }
 else{
        $tech1="";
      }
if($video->getTechnique2_id()!=0)
    {
        $tech2= $technique->getTechniqueById($video->getTechnique2_id())['donnees']->getNom()." / ";
        $videos_tech2=$vd->getVideoByTechnique($tech2,$id)['donnees'];
    }
 else{
        $tech2="";
      }
$pub=new pub();

$pub728x90=$pub->getBanniere728_90("videos")['donnees'];
$pub300x60=$pub->getBanniere300_60("videos")['donnees'];
$pub300x250=$pub->getBanniere300_250("videos")['donnees'];
$pub160x600=$pub->getBanniere160_600("videos")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];

?>
<!doctype html>
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title><?php echo $video->getTitre();?> | allmarathon</title>
    <meta name="Description" lang="fr" content="Vidéo : <?php echo $video->getTitre();?>. Durée : <?php echo $video->getDuree();?>. Thème : <?php echo $event_name;?> - <?php echo $champ_name;?>">
    <meta property="og:type" content="video" />
    <meta property="og:title" content="<?php echo $video->getTitre();?>" />
    <meta property="og:image" content="https://allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="<?php echo 'https://allmarathon.fr/video-de-marathon-'.$video->getId().'.html';?>" />
    <meta property="og:description" content="Vidéo : <?php echo $video->getTitre();?>. Durée : <?php echo $video->getDuree();?>. Thème : <?php echo $event_name;?> - <?php echo $champ_name;?>" />

    <?php echo '<link rel="canonical" href="https://allmarathon.fr/video-de-marathon-'.$video->getId().'.html" />';?>


    <link rel="apple-touch-icon" href="apple-favicon.png">
   <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css"/>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">
    
</head>
<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/fr_FR/sdk.js#xfbml=1&version=v2.6";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>



<?php include_once('nv_header-integrer.php'); ?>

<div class="container page-content video-details mt-77">
    
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
                setlocale(LC_TIME, "fr_FR","French");
                if($video->getEvenement_id()){
                    $evenement=$event->getEvenementByID($video->getEvenement_id())["donnees"];
                    $cat_event=$ev_cat_event->getEventCatEventByID($evenement->getCategorieId())['donnees']->getIntitule();

                    $nom_res='<strong>'.$cat_event.' - '.$evenement->getNom().'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));
                    $nom_res_lien=$cat_event.' - '.$evenement->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($evenement->getDateDebut())));

                    $res_event= "<a href='/resultats-marathon-".$evenement->getId()."-".slugify($nom_res_lien).".html' class='home-link mr-5 '><span class='material-symbols-outlined'>trophy</span> Résultats </a>";
                }
                $nom_res='<strong>'.$ev_cat_ev.' - '.$event_name.'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($date)));
                $nom_res_lien=$ev_cat_ev.' - '.$event_name.' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($date)));

                     echo '<h1 class="video_title">Vidéo : '.$video->getTitre().'</h1>';
                     echo  $res_event;?>
                     <span class="comment-count-container">
                        <a style="font-weight: bold;" href="<?php echo 'https://allmarathon.fr/video-de-marathon-'.$video->getId().'.html';?>#disqus_thread"># </a>
                        <span class="material-symbols-outlined">chat_bubble</span>
                    </span>
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
                    <?
                     //echo '<h2 style="font-size: 15px;"><a href="/resultats-marathon-'.$id.'-'.slugify($nom_res_lien).'.html">Voir les résultats du '.$ev_cat_ev.' '.$event_name.' '.$annee.'</a></h2>';
                     echo $video->getObjet();?>
                     <div id="video-desc">
                         <? echo $video->getDescription();?>
                    </div>
                     <!--<div class="row" style="margin: 10px 0;">

                            <?php //include_once("shareButtons.php"); ?>
                        </div>-->
                        <div class="row" style="margin: 10px 0;">
                            <?php include_once("shareButtons.php"); ?>
                        </div>
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
                            (function() { // DON'T EDIT BELOW THIS LINE
                            var d = document, s = d.createElement('script');
                            s.src = 'https://allmarathon.disqus.com/embed.js';
                            s.setAttribute('data-timestamp', +new Date());
                            (d.head || d.body).appendChild(s);
                            })();
                        </script>
                        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                    
                </div>
            </div>


        </div> <!-- End left-side -->

        <aside class="col-sm-4 bureau">
            <h3 class="h2-aside"><span class="material-symbols-outlined">play_circle</span>Les dernières vidéos</h3>
                <?php
                    foreach ($last_videos['donnees'] as $vd) {
                        $event_intitule="";
                            
                            //$duree="<li style='list-style-type: none;'></li>";
                            
                            
                            $url_img1=str_replace("hqdefault","0",$vd->getVignette());
                            $url_img=str_replace("default","0",$url_img1);
                            
                            echo '<ul class="video-align-top last-video-grid-tab">
                                
                                <li class="mr-5">
                                    <a href="video-de-marathon-'.$vd->getId().'.html">
                                    <div class="last-video-thumbnail" style="background-image: url('.$url_img.')"></div>
                                    </a>
                                </li>
                                <li class="video-t-d-res">
                                    <ul>
                                        <li><a href="video-de-marathon-'.$vd->getId().'.html" class="vite-lu-title">'.$vd->getTitre().'</a></li>
                                    </ul>
                                    
                                </li>
                            </ul>';
                    }
                ?>
            <p class="ban">
                <?php
                            /*
                if($pub300x250 !="") {
                echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
                }*/
                ?>
            </p>

             <div class="marg_bot"></div>
        </aside>
    </div>

</div> <!-- End container page-content -->

<?php include_once('footer.inc.php'); ?>


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
}</script>

<?php
    if($user_id!=""){

         echo "<script type='text/javascript'>
        $('#com_but').on('click',function(e){
            document.location.href='/content/modules/add_commentaire.php?video_id=".$id."&commentaire='+$('#message_champion').val();
       });
       </script>";
    }else{
         echo "<script type='text/javascript'>
            $('#com_but').on('click',function(e){
                    $('#SigninModal').modal('show');});
            </script>";
    }
?>

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
</body>
</html>
