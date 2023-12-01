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

                        <h1>Présentation du site</h1>
                        <ul>
                            <li>Nom de la Société : NASH (NORTH AFRICA SHORING) SARL</br>
Adresse : 14 Route d’Amizmiz km3 - Complexe de Chrifia - 40000 Marrakech – Maroc</br>
Tél : +212 6 42 88 78 83</br>
Au Capital de : 10 000 dhs</br>
R.C. : 124 455</br>
<a href="https://www.nash-digital.com" target="_blank">Nash digital, agence web offshore</a></li><br/><br/>
                            <li>Hébergement :OVH<br/>
2 Rue Kellermann<br/>
59100 Roubaix<br/>
</li>
 </ul>

                        <h1>Conditions générales d’utilisation du site et des services proposés</h1>
                        <p>
						L’utilisation du site allmarathon.fr implique l’acceptation pleine et entière des conditions générales d’utilisation décrites ci-après. Ces conditions d’utilisation sont susceptibles d’être modifiées ou complétées à tout moment, sans préavis, aussi les utilisateurs du site allmarathon.fr sont invités à les consulter de manière régulière.
<br/><br/>
allmarathon.fr est par principe accessible aux utilisateurs 24/24h, 7/7j, sauf interruption, programmée ou non, pour les besoins de sa maintenance ou cas de force majeure. En cas d’impossibilité d’accès au service, allmarathon.fr s’engage à faire son maximum afin de rétablir l’accès au service et s’efforcera alors de communiquer préalablement aux utilisateurs les dates et heures de l’intervention. N’étant soumis qu’à une obligation de moyen, allmarathon.fr ne saurait être tenu pour responsable de tout dommage, quelle qu’en soit la nature, résultant d’une indisponibilité du service.
<br/><br/>
Le site allmarathon.fr est mis à jour régulièrement par le propriétaire du site. De la même façon, les mentions légales peuvent être modifiées à tout moment, sans préavis et s’imposent à l’utilisateur sans réserve. L’utilisateur est réputé les accepter sans réserve et s’y référer régulièrement pour prendre connaissance des modifications.
<br/><br/>
Le site allmarathon.fr se réserve aussi le droit de céder, transférer, ce sans préavis les droits et/ou obligations des présentes CGU et mentions légales. En continuant à utiliser les services du site allmarathon.fr , l’utilisateur reconnaît accepter les modifications des conditions générales qui seraient intervenues.
<br/><br/>
						</p>

                        <h1>Limites de responsabilité</h1>
                        <p>
						Le site allmarathon.fr ne saurait être tenu responsable des erreurs typographiques ou inexactitudes apparaissant sur le service, ou de quelque dommage subi résultant de son utilisation. L’utilisateur reste responsable de son équipement et de son utilisation, de même il supporte seul les coûts directs ou indirects suite à sa connexion à Internet.
<br/><br/>
L’utilisateur du site allmarathon.fr s’engage à accéder à celui-ci en utilisant un matériel récent, ne contenant pas de virus et avec un navigateur de dernière génération mise à jour.
<br/><br/>
L’utilisateur dégage la responsabilité de allmarathon.fr pour tout préjudice qu’il pourrait subir ou faire subir, directement ou indirectement, du fait des services proposés. Seule la responsabilité de l’utilisateur est engagée par l’utilisation du service proposé et celui-ci dégage expressément le site allmarathon.fr de toute responsabilité vis à vis de tiers.
<br/><br/>
                        </p>
                        

                        <h1>Propriété intellectuelle et contrefaçons</h1>
                        <p>Le propriétaire du site est propriétaire des droits de propriété intellectuelle ou détient les droits d’usage sur tous les éléments accessibles sur le site, notamment les textes, graphismes, logo, icônes…
<br/><br/>
Les images utilisées proviennent : <br/>
- de la banque d’images despositphotos.com <br/>
- du web sous licence Creative Commons <br/><br/>

Toute reproduction, représentation, modification, publication, adaptation totale ou partielle des éléments du site, quel que soit le moyen ou le procédé utilisé, est interdite, sauf autorisation.
<br/><br/>
Toute exploitation non autorisée du site ou de l’un quelconque de ces éléments qu’il contient sera considérée comme constitutive d’une contrefaçon et poursuivie conformément aux dispositions applicables par la loi.
<br/><br/>
</p>
                        

                        <h1>Liens hypertextes et cookies</h1>
                        <p>Le site allmarathon.fr contient un certain nombre de liens hypertextes vers d’autres sites (partenaires, informations …). Cependant, le propriétaire du site n’a pas la possibilité de vérifier le contenu des sites ainsi visités et décline donc toute responsabilité de ce fait quand aux risques éventuels de contenus illicites.</p>

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