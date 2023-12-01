<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

if(isset($_GET['clubID']) &&  $user_id!='') {$clubID = $_GET['clubID'];}
else{
    header('Location: /contact-clubs-de-marathon.html');
    exit();
}

include("../classes/pub.php");
include("../classes/club.php");

$cl=new club();

$club=$cl->getClubById($clubID)['donnees'];



$pub=new pub();

$pub728x90=$pub->getBanniere728_90("calendrier")['donnees'];
$pub300x60=$pub->getBanniere300_60("calendrier")['donnees'];
$pub300x250=$pub->getBanniere300_250("calendrier")['donnees'];
$pub160x600=$pub->getBanniere160_600("calendrier")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];

?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Administrer le club <?php echo $club->getClub(); ?></title>
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <meta name="description" content="">
    
    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <style type="text/css">
    label.error {
        color: red;
    }

    input.update_athlète_input.error {
        border: 1px solid red !important;
    }
    </style>


</head>

<body>


    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlètes">
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

                        <h1>ADMINISTREZ LE CLUB <?php echo $club->getClub();?> </h1>

                        <hr class="hr_customized">
                        <?php 
                            if(isset($_SESSION['update_club_msg'])){
                                echo $_SESSION['update_club_msg'];
                                unset($_SESSION['update_club_msg']);
                            }?>

                        <form action="/content/modules/update_club_admin.php?clubID=<?php echo $club->getId(); ?>"
                            method="post" id="form_admin">

                            <table style="font-size: 0.9em;">
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="nom">Nom : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" align="left" class="update_athlète_input"
                                            data-rule-required="true" data-msg-required="Ce champ est obligatoire"
                                            name="nom" id="nom" />
                                    </td>
                                    <td class="col-md-2"></td>
                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="prenom">Prénom : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" class="update_athlète_input" class="update_athlète_input"
                                            data-rule-required="true" data-msg-required="Ce champ est obligatoire"
                                            name="prenom" id="prenom" />
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="telephone">Téléphone : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" class="update_athlète_input" class="update_athlète_input"
                                            data-rule-required="true" data-msg-required="Ce champ est obligatoire"
                                            name="telephone" id="telephone" />
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left">
                                        <label for="fonction">Fonction au sein du club : </label>
                                    </td>
                                    <td class="col-md-7" align="left">
                                        <input type="text" class="update_athlète_input" name="fonction" id="fonction"
                                            required />
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left"></td>
                                    <td class="col-md-7 col-offset-1">
                                        <div class="g-recaptcha"
                                            data-sitekey="6Lf4UxIUAAAAAA7o7Fi_cSY8O4jX4yCcr3y-Cl8p"></div>
                                    </td>
                                </tr>
                                <tr class="row">
                                    <td class="col-md-3" align="left"></td>
                                    <td class="pull-right">
                                        <br><button type="submit" name="add_club_admin"
                                            class="btn_custom">Ajouter</button>
                                    </td>
                                </tr>
                            </table>
                        </form>


                    </div>


                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
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

    <script>
    window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="../../js/plugins.js"></script>
    <script src="../../js/jquery.jcarousel.min.js"></script>
    <script src="../../js/jquery.sliderPro.min.js"></script>
    <script src="../../js/herbyCookie.min.js"></script>
    <script src="../../js/main.js"></script>
    <script src="../../tab/jquery.dataTables.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js">
    </script>
    <script type="text/javascript">
    $('#form_admin').validate();
    </script>



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