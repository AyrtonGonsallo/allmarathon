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
    <title>Mentions légales</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <!-- Place favicon.ico in the root directory -->

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

                        <h1>Qu’est-ce qu’un cookie ?</h1>
                        <p>Un cookie est un fichier texte susceptible d’être enregistré, sous réserve de vos choix, dans un espace dédié du disque dur de votre terminal, à l’occasion de la consultation d’un service en ligne grâce à votre logiciel de navigation.

Un fichier cookie permet à son émetteur d’identifier le terminal dans lequel il est enregistré, pendant la durée de validité.
</p>

                        <h1>A quoi servent les cookies émis sur notre site ?</h1>
                        <p>
						Seul l’émetteur d’un cookie est susceptible de lire ou de modifier les informations qui y sont contenues.
Les cookies utilisés sur notre site permettent d’identifier les services et rubriques que l’utilisateur a visités, et plus généralement son comportement en matière de visites. Ces informations sont utiles pour mieux personnaliser les services, contenus, offres promotionnelles et bannières qui apparaissent sur notre site et faciliter votre navigation sur notre site. Des cookies sont également nécessaires au bon fonctionnement de certains services ou encore pour mesurer leur audience.
Des cookies sont susceptibles d’être inclus dans les espaces publicitaires de notre site. Ces espaces contribuent au financement des contenus et services que nous mettons à votre disposition.
						</p>

                        <h2>Les cookies que nous émettons sur notre site</h2>
                        <p>
1. Améliorer nos services :<br/>
Ces cookies permettent d’établir des statistiques et volume de fréquentation et d’utilisation des divers éléments composant notre site (rubriques et contenus visité, parcours) afin d’améliorer l’intérêt et l’ergonomie de nos services.
<br/><br/>
2. Adapter la publicité proposée sur notre site :<br/>
– en comptabilisant le nombre total de publicités affichées par nos soins sur nos espaces publicitaires, d’identifier ces publicités, le nombre d’utilisateurs ayant cliqué sur chaque publicité et d’établir des statistiques,- en adaptant nos espaces publicitaires aux préférences d’affichage de votre terminal (langue utilisée, résolution d’affichage, système d’exploitation utilisé, etc), selon les matériels et les logiciels de visualisation ou de lecture que votre terminal comporte,- en adaptant les contenus publicitaires affichés sur votre terminal par nos espaces publicitaires, selon la navigation de votre terminal sur notre site,- en adaptant le cas échéant les contenus publicitaires affichés sur votre terminal dans nos espaces publicitaires en fonction des données de localisation transmises par votre terminal avec votre accord préalable- en adaptant les contenus publicitaires affichés sur votre terminal dans nos espaces publicitaires en fonction des données personnelles que vous nous avez fournies
<br/><br/></p>
                        

                        <h2>Propriété intellectuelle et contrefaçons</h2>
                        <p>
L’émission et l’utilisation de cookies par des tiers sont soumises aux politiques de protection de la vie privée de ces tiers. Nous vous informons de l’objet des cookies dont nous avons connaissance et des moyens dont vous disposez pour effectuer des choix à l’égard de ces cookies.
<br/><br/>
a) Du fait d’applications tierces intégrées à notre site
Nous sommes susceptibles d’inclure sur notre site/application, des applications informatiques émanant de tiers, qui vous permettent de partager des contenus de notre site avec d’autres personnes ou de faire connaître à ces autres personnes votre consultation ou votre opinion concernant un contenu de notre site/application. Tel est notamment le cas des boutons « Partager », « J’aime », issus de réseaux sociaux tels que Facebook « Twitter », LinkedIn », « Viadeo », etc.
<br/><br/>
Le réseau social fournissant un tel bouton applicatif est susceptible de vous identifier grâce à ce bouton, même si vous n’avez pas utilisé ce bouton lors de votre consultation de notre site/application. En effet, ce type de bouton applicatif peut permettre au réseau social concerné de suivre votre navigation sur notre site, du seul fait que votre compte au réseau social concerné était activé sur votre terminal (session ouverte) durant votre navigation sur notre site.
<br/><br/>

Nous n’avons aucun contrôle sur le processus employé par les réseaux sociaux pour collecter des informations relatives à votre navigation sur notre site et associées aux données personnelles dont ils disposent. Nous vous invitons à consulter les politiques de protection de la vie privée de ces réseaux sociaux afin de prendre connaissance des finalités d’utilisation, notamment publicitaires, des informations de navigation qu’ils peuvent recueillir grâce à ces boutons applicatifs. Ces politiques de protection doivent notamment vous permettre d’exercer vos choix auprès de ces réseaux sociaux, notamment en paramétrant vos comptes d’utilisation de chacun de ces réseaux.
<br/><br/>
b) Via des contenus de tiers diffusés dans nos espaces publicitaires
Les contenus publicitaires sont susceptibles de contenir des cookies émis par des tiers : soit l’annonceur à l’origine du contenu publicitaire concerné, soit une société tierce à l’annonceur (agence conseil en communication, société de mesure d’audience, prestataire de publicité ciblée, etc.), qui a associé un cookie au contenu publicitaire d’un annonceur. Le cas échéant, les cookies émis par ces tiers peuvent leur permettre, pendant la durée de validité de ces cookies :- de comptabiliser le nombre d’affichages des contenus publicitaires diffusés via nos espaces publicitaires, d’identifier les publicités ainsi affichées, le nombre d’utilisateurs ayant cliqué sur chaque publicité, leur permettant de calculer les sommes dues de ce fait et d’établir des statistiques,- de reconnaître votre terminal lors de sa navigation ultérieure sur tout autre site ou service sur lequel ces annonceurs ou ces tiers émettent également des cookies et, le cas échéant, d’adapter ces sites et services tiers ou les publicités qu’ils diffusent, à la navigation de votre terminal dont ils peuvent avoir connaissance.
<br/><br/>
c) Par une régie publicitaire externe exploitant nos espaces publicitaires
Les espaces publicitaires de notre site sont susceptibles d’être exploités par une ou plusieurs régie(s) publicitaire(s) externe(s) et, le cas échéant, de contenir des cookies émis par l’une d’entre elles. Le cas échéant, les cookies émis par ces régies publicitaires externes leur permettent, pendant la durée de validité de ces cookies :- de comptabiliser le nombre total de publicités affichées par leurs soins sur nos espaces publicitaires, d’identifier ces publicités, leur nombre d’affichages respectifs, le nombre d’utilisateurs ayant cliqué sur chaque publicité et, le cas échéant, les actions ultérieures effectuées par ces utilisateurs sur les pages auxquelles mènent ces publicités, afin de calculer les sommes dues aux acteurs de la chaîne de diffusion publicitaire (annonceur, agence de communication, régie publicitaire, site/support de diffusion) et d’établir des statistiques,- d’adapter les espaces publicitaires qu’elles opèrent aux préférences d’affichage de votre terminal (langue utilisée, résolution d’affichage, système d’exploitation utilisé, etc), selon les matériels et les logiciels de visualisation ou de lecture que votre terminal comporte,- d’adapter les contenus publicitaires affichés sur votre terminal via nos espaces publicitaires selon la navigation de votre terminal sur notre site,- d’adapter les contenus publicitaires affichés sur votre terminal via nos espaces publicitaires selon la navigation antérieure ou ultérieure de votre terminal sur des sites de tiers au sein desquels la régie concernée émet également des cookies, sous réserve que ces cookies aient été enregistrés dans votre terminal conformément aux choix que vous avez exercés à l’égard de cette régie,- d’adapter les contenus publicitaires affichés sur votre terminal via nos espaces publicitaires en fonction des données de localisation (longitude et latitude) transmises par votre terminal avec votre accord préalable
– d’adapter les contenus publicitaires affichés sur votre terminal dans nos espaces publicitaires en fonction des données personnelles que vous auriez pu fournir à cette régie publicitaire.
<br/><br/>
</p>
                        

                        <h1>Vos choix concernant les cookies</h1>
                        <p>Plusieurs possibilités vous sont offertes pour gérer les cookies. Tout paramétrage que vous pouvez entreprendre sera susceptible de modifier votre navigation sur Internet et notre site ainsi que vos conditions d’accès à certains services nécessitant l’utilisation de cookies. Vous pouvez faire le choix à tout moment d’exprimer et de modifier vos souhaits en matière de cookies, par les moyens décrits ci-dessous.
<br/><br/>
Les choix qui vous sont offerts par votre logiciel de navigation :<br/>
Vous pouvez configurer votre logiciel de navigation de manière à ce que des cookies soient enregistrés dans votre terminal ou, au contraire, qu’ils soient rejetés, soit systématiquement, soit selon leur émetteur. Vous pouvez également configurer votre logiciel de navigation de manière à ce que l’acceptation ou le refus des cookies vous soient proposés ponctuellement, avant qu’un cookie soit susceptible d’être enregistré dans votre terminal. 
<br/><br/>
(a) L’accord sur les Cookies<br/>
L’enregistrement d’un cookie dans un terminal est subordonné à la volonté de l’utilisateur du terminal, que celui-ci peut exprimer et modifier à tout moment et gratuitement à travers les choix qui lui sont offerts par son logiciel de navigation. Si vous avez accepté dans votre logiciel de navigation l’enregistrement de cookies dans votre Terminal, les cookies intégrés dans les pages et contenus que vous avez consultés pourront être stockés temporairement dans un espace dédié de votre terminal. Ils y seront lisibles uniquement par leur émetteur.
(b) Le refus des Cookies<br/>
Si vous refusez l’enregistrement de cookies dans votre terminal, ou si vous supprimez ceux qui y sont enregistrés, vous ne pourrez plus bénéficier d’un certain nombre de fonctionnalités nécessaires pour naviguer dans certains espaces de notre site. Tel serait le cas si vous tentiez d’accéder à nos contenus ou services qui nécessitent de vous identifier. 
<br/><br/>
Tel serait également le cas lorsque nous -ou nos prestataires- ne pourrions pas reconnaître, à des fins de compatibilité technique, le type de navigateur utilisé par votre terminal, ses paramètres de langue et d’affichage ou le pays depuis lequel votre terminal semble connecté à Internet. Le cas échéant, nous déclinons toute responsabilité pour les conséquences liées au fonctionnement dégradé de nos services résultant de l’impossibilité pour nous d’enregistrer ou de consulter les cookies nécessaires à leur fonctionnement et que vous auriez refusés ou supprimés.
<br/><br/>
</p>
                    </div>
                </div>




            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"></p>
                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
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
    <script src="../../js/easing.js"></script>
    <script src="../../js/jquery.ui.totop.min.js"></script>
    <script src="../../js/main.js"></script>


   
</body>

</html>