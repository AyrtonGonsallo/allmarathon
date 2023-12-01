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

include("/var/www/clients/client1/web5/web/content/classes/pub.php");
$pub=new pub();

$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="no-js" lang="fr">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("/var/www/clients/client1/web5/web/content/scripts/header_script.php") ?>
    <title>Tableaux de résultats marathon au format PDF</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css"/>
    <link rel="stylesheet" href="../../css/main.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->

    <style type="text/css">
            #result a{color:black;text-decoration: none;}
            #result a:hover{text-decoration: underline}
            a#search_2 {
    margin-top: 26px !important;
}
input.form-control.search_header {
    margin-bottom: 17px !important;
}

        </style>

<script language="JavaScript">
hauteur = screen.height;
H= hauteur*0.6;
function calcHeight()
{
  //récupère la hauteur de la page var the_height=screen.height
  //change la hauteur de l'iframe
  document.getElementById('the_iframe').height=H;
}

</script>
</head>
<body>

    <div class="container page-content">

        <div class="row banniere1">
        <a href="" class="col-sm-12"><?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
    </div>

                <?php include_once('nv_header-integrer.php'); ?>
                <div id="content">
                
   <iframe width="1002" id="the_iframe" onLoad="calcHeight();"  frameborder="0" src="/uploadDocument/<?php echo $_GET['pdf']; ?>" height="1000"  scrolling="auto"></iframe>      
                    </div>
                </div>
               <?php include_once('footer.inc.php'); ?>

 <script>
 (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
 (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
 m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
 })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

 ga('create', 'UA-1833149-1', 'auto');
 ga('send', 'pageview');

</script> 
