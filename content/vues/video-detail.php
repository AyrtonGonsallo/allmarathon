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

<div class="container page-content athlete-detail video-details">
    
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
                $nom_res='<strong>'.$ev_cat_ev.' - '.$event_name.'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($date)));
                $nom_res_lien=$ev_cat_ev.' - '.$event_name.' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($date)));

                     echo '<h1 class="video_title">'.$video->getTitre().' - vidéo </h1>';
                     echo '<h2 style="font-size: 15px;"><a href="/resultats-marathon-'.$id.'-'.slugify($nom_res_lien).'.html">Voir les résultats du '.$ev_cat_ev.' '.$event_name.' '.$annee.'</a></h2>';
                     echo $video->getObjet();
                     echo $video->getDescription();?>
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

        <aside class="col-sm-4">

            <p class="ban"><?php
            /*
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}*/
?></a></p>

            <div class="marg_bot"></div>
            <?php if($video->getChampion_id()!=0){ ?>
            <dt class="suggestions">Vidéos de <?php echo $champ_name; ?></dt>
            <dd class="suggestions">
                <ul class="clearfix">
                    <?php
                    foreach ($videos_champ as $key => $value) {
                    echo '<li><a href="video-de-marathon-'.$value->getId().'.html"><span style="min-width: 90px;"><img src="'.$value->getVignette().'" class="img-responsive" alt="" style="height: 50px;width: 70px;border: 1px solid #666666;"></span> <strong>'.$value->getTitre().'</strong></a></li>';
                    }
                     ?>
                     <li></li>
                </ul>
            </dd>
            <div class="marg_bot"></div>
            <?php } ?>
            <?php if($tech1!=''){
                echo '<dt class="suggestions">'.substr($tech1,0,strlen($tech1)-3).' en vidéos</dt>
            <dd class="suggestions">
                <ul class="clearfix">';
                foreach ($videos_tech1 as $key => $value) {
                    echo '<li><a href="video-de-marathon-'.$value->getId().'.html"><span style="min-width: 90px;"><img src="'.$value->getVignette().'" class="img-responsive" alt="" style="height: 50px;width: 70px;border: 1px solid #666666;"></span> <strong>'.$value->getTitre().'</strong></a></li>';
                }
                echo '<li></li></ul>
            </dd> 
            <div class="marg_bot"></div>';
            }?>
            
            <?php if($tech2!=''){
                echo '<dt class="suggestions">'.substr($tech2,0,strlen($tech2)-3).' en vidéos</dt>
            <dd class="suggestions">
                <ul class="clearfix">';
                foreach ($videos_tech2 as $key => $value) {
                    echo '<li><a href="video-de-marathon-'.$value->getId().'.html"><span style="min-width: 90px;"><img src="'.$value->getVignette().'" class="img-responsive" alt="" style="height: 50px;width: 70px;border: 1px solid #666666;"></span> <strong>'.$value->getTitre().'</strong></a></li>';
                    
                }
                    echo '<li></li></ul>
            </dd>
            <div class="marg_bot"></div>';
            }?>

            <!-- <p class="ban"><a href=""><?php //echo $pub160x600; ?></a></p> -->
            
            <div class="dailymotion-widget" data-placement="58dcd1d2a716ff001755f5f9"></div><script>(function(w,d,s,u,n,e,c){w.PXLObject = n; w[n] = w[n] || function(){(w[n].q = w[n].q || []).push(arguments);};w[n].l = 1 * new Date();e = d.createElement(s); e.async = 1; e.src = u;c = d.getElementsByTagName(s)[0]; c.parentNode.insertBefore(e,c);})(window, document, "script", "//api.dmcdn.net/pxl/client.js", "pxl");</script>
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
