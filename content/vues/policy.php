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
}  else $user_session='';

include("../classes/pub.php");

$pub=new pub();
$pub728x90=$pub->getBanniere728_90("calendrier")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];

?>

<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Politique de confidentialité</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css?ver=<?php echo rand(111,999)?>">
    <link rel="stylesheet" href="../../css/responsive.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->
    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content mentions">
        <div class="row banniere1">
            <a href="" class="col-sm-12"><?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side ">

                <div class="row">

                    <div class="col-sm-12">

                        <h1>Cookies</h1>
                        <p>
                            Pour assurer le bon fonctionnement de ce site, nous devons parfois enregistrer de petits
                            fichiers de données sur l’équipement de nos utilisateurs. La plupart des grands sites web
                            font de même.
                        </p>

                        <h1>Qu’est-ce qu’un cookie?</h1>
                        <p>Un cookie est un petit fichier texte que les sites web sauvegardent sur votre ordinateur ou
                            appareil mobile lorsque vous les consultez. Il permet à ces sites de mémoriser vos actions
                            et préférences (nom d’utilisateur, langue, taille des caractères et autres paramètres
                            d’affichage) pendant un temps donné, pour que vous n’ayez pas à réindiquer ces informations
                            à chaque fois vous consultez ces sites ou naviguez d’une page à une autre.</p>

                        <h1>Comment utilisons-nous les cookies?</h1>
                        <p>Les cookies sur ce site sont utilises pour :</p>
                        <ul>
                            <li>Générer des statistiques anonymes sur votre utilisation du site (via des outils comme
                                Google Analytics)</li>
                            <li>Stocker d’autres informations par rapport à votre session (par. ex. si vous êtes
                                connecté au site ou non)</li>
                        </ul>

                        <h1>Comment contrôler les cookies</h1>
                        <p>Vous pouvez contrôler et/ou supprimer des cookies comme vous le souhaitez. Pour en savoir
                            plus, consultez <a href="https://www.aboutcookies.org/"
                                target="_blank">aboutcookies.org</a>. Vous avez la possibilité de supprimer tous les
                            cookies déjà stockés sur votre ordinateur et de configurer la plupart des navigateurs pour
                            qu’ils les bloquent. Toutefois, dans ce cas, vous devrez peut-être indiquer vous-mêmes
                            certaines préférences chaque fois que vous vous rendrez sur un site, et certains services et
                            fonctionnalités risquent de ne pas être accessibles.</p>

                    </div>
                </div>




            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <div class="marg_bot"></div>
                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
            <dd class="facebook">
                <div class="fb-page"
                     data-href="https://www.facebook.com/allmarathonnet-108914759155897/"
                     data-width="310"
                     data-hide-cover="false"
                     data-show-facepile="true">
                </div>
            </dd> -->
                <div class="marg_bot"></div>
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
    <script src="../../js/herbyCookie.min.js"></script>
    <script src="../../js/main.js"></script>




    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
    /*
     (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
     function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
     e=o.createElement(i);r=o.getElementsByTagName(i)[0];
     e.src='https://www.google-analytics.com/analytics.js';
     r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
     ga('create','UA-XXXXX-X','auto');ga('send','pageview');
     */
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