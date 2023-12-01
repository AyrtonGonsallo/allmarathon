<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (session_status() == PHP_SESSION_NONE) {
session_start();
}
require_once '../database/connexion.php';
//$result3 =  $mysqli->real_escape_string($query3);
//$champion = mysqli_fetch_array($result3);

include("../classes/pub.php");
$pub=new pub();

$pub728x90=$pub->getBanniere728_90("calendrier")['donnees'];
$pub300x60=$pub->getBanniere300_60("calendrier")['donnees'];
$pub300x250=$pub->getBanniere300_250("calendrier")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];

if(!empty($_SESSION['auth_error'])) {
    $erreur_auth=$_SESSION['auth_error'];
    unset($_SESSION['auth_error']);
    }else $erreur_auth='';

(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!empty($_SESSION['user'])) {
$user_session=$_SESSION['user'];
$erreur_auth='';
}  else $user_session='';
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Mot de passe oubli&eacute;</title>
    <link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link rel="stylesheet" href="../../css/main.css">
    <style type="text/css">
    #result a {
        color: black;
        text-decoration: none;
    }

    #result a:hover {
        text-decoration: underline
    }
    </style>

</head>

<body>
    <?php include_once('nv_header-integrer.php'); ?>
    <div class="container page-content">
        <div class="row banniere1">
            <a href="" class="col-sm-12"> <?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side">

                <div class="row">

                    <div class="col-sm-12">
                        <div id="content">
                            <div id="colun">


                                <h1>R&eacute;cup&eacute;ration du mot de passe </h1><br>

                                <?php
                if(isset($mailValide))
                {
                    echo "<br><span style='color:#cc0000; font-size:0.8em'>Entrez une adresse valide<br/><br/></span>";
                    //unset($_SESSION['mail']);

                }?>
                                <?php
                if(isset($capt))
                    {
                        echo "<br><span style='color:#cc0000; font-size:0.8em'>Captcha invalide<br/><br/></span>";
                        //unset($_SESSION['captcha']);

                    }?><br>
                                <?php
              if (isset($msgEnv)) {
                  echo "<br><span style='color:#009966; font-size:0.8em'>Votre demande a bien &eacute;t&eacute; envoy&eacute;e, merci de v&eacute;rifier votre boite mail !<br/><br/></span>";

                 // unset($_SESSION['message']);
              } else {?>
                                <?php// echo var_dump($_SESSION); ?>
                                <form action="/forgotPass.php" method="post">

                                    <div id="formulaireligne" class="texte4 form-group">
                                        <div class="left" style="padding-top: 3px;"><strong>E-mail *</strong></div>
                                        <div class="right"><input type="text" name="mail" required
                                                value="<?php //echo $_POST['mail']; ?>"></div>
                                        <div style="clear:both"></div>
                                    </div>



                                    <div id="formulaireligne" class="texte4 form-group">
                                        <div class="g-recaptcha left"
                                            data-sitekey="6Lf2-bwlAAAAAFZRxwBD28d2vUPvDT-MPfBEgZJx"></div>
                                        <div class="right">
                                            <input type="submit" name="Envoyer" value="Envoyer" />
                                        </div>
                                        <div style="clear:both"></div>
                                    </div>
                                </form>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <aside class="col-sm-4">
                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
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

    <?php include('footer.inc.php'); ?>

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
        if (d.getElementById(id))
            return;
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