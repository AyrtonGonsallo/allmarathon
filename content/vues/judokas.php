<?php

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


include("/var/www/clients/client1/web5/web/content/classes/champion.php");
include("/var/www/clients/client1/web5/web/content/classes/pays.php");
include("/var/www/clients/client1/web5/web/content/classes/pub.php");

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("athlètes")['donnees'];
$pub300x60=$pub->getBanniere300_60("athlètes")['donnees'];
$pub300x250=$pub->getBanniere300_250("athlètes")['donnees'];
$pub160x600=$pub->getBanniere160_600("athlètes")['donnees'];


$champion=new champion();

$pays=new pays();

$order = 'a';
if(isset($_GET['order']))  $order = $_GET['order'];

if(isset($_POST['search']))
        $order =trim($_POST['search']);
$page=0;
if(isset($_GET['p']) && is_numeric($_GET['p'])) $page = intval($_GET['p']);

$nb_pages=intval($champion->getNumberPage($order)['donnees']['COUNT(*)']/80)+1;
$next=$page+1;
$previous=$page-1;

?>


<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("/var/www/clients/client1/web5/web/content/scripts/header_script.php") ?>
    <title></title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
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


    <div class="container page-content athlètes">
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

                        <h1>Champions de marathon, athlètes français célèbres : palmarès, photos et vidéos</h1>

                        <form action="" method="post" class="form-inline" role="form">
                            <div class="form-group" style="width:100%;">
                                <input type="search" id="search_athlètes" placeholder="Recherche" class="form-control"
                                    style="width: 93%" />
                                <button type="button" onclick="goSearch_athlètes();" id="button_search_athlètet"
                                    class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-12">
                        <ul class="search-alpha list-inline list-unstyled well clearfix">
                            <li class="title">Recherche alphabétique :</li>
                            <li class="list-alpha">
                                <ul>

                                    <?php for($i='A';$i!='AA';$i++)
                                    echo ($i==strtoupper($order))?'<li class="active"><a href="http://localhost/allmarathon_nv/www/content/vues/athlètes.php?order='.$i.'">'.$i.'</a></li>':'<li><a href="http://localhost/allmarathon_nv/www/content/vues/athlètes.php?order='.$i.'">'.$i.'</a></li>';
                                ?>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <?php
                foreach ($champion->getListChampionsByInitial($order,$page)['donnees'] as $key => $chmp) {
                    if ($key % 2 == 0) {
                        if($chmp->getPaysID()){
                           $flag=$pays->getFlagByAbreviation($chmp->getPaysID())['donnees']['Flag'];
                           ($flag!='') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                            $pays_ab=" (".$chmp->getPaysID().")";
                        }
                        $nb_com=$champion->getNumberCom($chmp->getId())['donnees'];
                        ($nb_com['COUNT(*)']!=0) ? $nombre_com='<span><img src="../../img/buble.png" alt=""/></span>' : $nombre_com='';
                        
                        $nb_videos=$champion->getNumberVideos($chmp->getId())['donnees'];
                        ($nb_videos!=0) ? $nombre_videos='<span><img src="../../img/tv.png" alt=""/></span>' : $nombre_videos='';
                        
                        $nb_images=$champion->getNumberImages($chmp->getId())['donnees'];
                        ($nb_images!=0) ? $nombre_images='<span style="margin-right: 6px;"><img src="../../img/cam.png" alt=""/></span>' : $nombre_images='';
                        echo '<ul class="col-sm-6 list">
                    <li><a href="http://localhost/allmarathon_nv/www/content/vues/champion-detail.php?id='.$chmp->getId().'">'.$chmp->getNom().$pays_ab.'</a>
                        <ul class="list-inline">'.
                            $nombre_images.'    '.$nombre_videos.' '.$nombre_com.' '.$pays_flag.'
                            
                        </ul>
                    </li>
                </ul>';
                    }
                    else{
                        if($chmp->getPaysID()){
                           $flag=$pays->getFlagByAbreviation($chmp->getPaysID())['donnees']['Flag'];
                           ($flag!='') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                            $pays_ab=" (".$chmp->getPaysID().") ";
                        }
                        $nb_com=$champion->getNumberCom($chmp->getId())['donnees'];
                        ($nb_com['COUNT(*)']!=0) ? $nombre_com='<span><img src="../../img/buble.png" alt=""/></span>' : $nombre_com='';
                        
                        $nb_videos=$champion->getNumberVideos($chmp->getId())['donnees'];
                        ($nb_videos!=0) ? $nombre_videos='<span><img src="../../img/tv.png" alt=""/></span>' : $nombre_videos='';
                        
                        $nb_images=$champion->getNumberImages($chmp->getId())['donnees'];
                        ($nb_images!=0) ? $nombre_images='<span><img src="../../img/cam.png" alt=""/></span>' : $nombre_images='';
                        echo '<ul class="col-sm-6 list">
                    <li><a href="http://localhost/allmarathon_nv/www/content/vues/champion-detail.php?id='.$chmp->getId().'">'.$chmp->getNom().$pays_ab.'</a>
                        <ul class="list-inline">'.
                            $nombre_images.$nombre_videos.$nombre_com.$pays_flag.'
                            
                        </ul>
                    </li>
                </ul>';
                    }
                }
                ?>
                </div>

                <div class="clearfix"></div>

                <ul class="pager">
                    <?php 
                            if($next==$nb_pages) $style_suivant='style="pointer-events: none;cursor: default;"';
                            else $style_suivant='';
                            if(intval($next)<2) $style_precedent='style="pointer-events: none;cursor: default;"';
                            else $style_precedent='';
                            $sort='';
                            if($sort!='') { 
                        ?>
                    <?php echo ' <li><a href="http://localhost/allmarathon_nv/www/content/vues/athlètes.php?sort='.$sort.'&p='.$previous.'&order='.$order.'"'.$style_precedent.'> Précédent</a></li>
                    <li>'.$next.' / '.$nb_pages.'</li>
                    <li><a href="http://localhost/allmarathon_nv/www/content/vues/athlètes.php?sort='.$sort.'&p='.$next.'&order='.$order.'"'.$style_suivant.'> Suivant</a></li>';
                     } else {
                         echo '<li><a href="http://localhost/allmarathon_nv/www/content/vues/athlètes.php?p='.$previous.'&order='.$order.'"'.$style_precedent.'> Précédent</a></li>
                          <li>'.$next.' / '.$nb_pages.'</li>
                        <li><a href="http://localhost/allmarathon_nv/www/content/vues/athlètes.php?p='.$next.'&order='.$order.'"'.$style_suivant.'> Suivant</a> </li>';
                     }?>
                </ul>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x60['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <dt class="anniversaires">ANNIVERSaires</dt>
                <dd class="anniversaires">
                    <ul class="clearfix">
                        <?php
                    foreach ($champion->getChampionBirthday()['donnees'] as $key => $ch) {
                        $age=$ch->ageBirthday($ch->getDateNaissance());
                        $flag=$pays->getFlagByAbreviation($ch->getPaysID())['donnees']['Flag'];
                        ($flag!='') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                        echo '<li><a href="">'.$ch->getNom().' ('.$age.' ans)</a></li>';
                    }
                    ?>

                    </ul>
                </dd>
<div class="newsletter">
                    <div class="title-newsletter">NEWSLETTER</div>
                    <p class="p-newsletter">Restez informés chaque semaine et profitez d'offres exclusives sur <a href="http://www.alljudo.shop/">allmarathon shop</a>.</p>
                    <div class="center"><a href="https://dev.allmarathon.fr/formulaire-inscription.php" class="abon-nwl">Je m'abonne</a></div>
                </div>
                
                <div class="newsletter">
                    <div class="title-newsletter">AJOUTER UN TOURNOI</div>
                    <p class="p-newsletter">Vous êtes organisateur d'un tournoi ou d'un stage, vous pouvez annoncer gratuitement votre
                        manifestation et mettre en ligne le dossier de présentation au fomat PDF.</p>
                        <p class="p-newsletter"><strong>Remplissez votre formulaire pour référencer votre manifestation en moins de deux minutes,
                            alors n'attendez-pas...</strong></p>
                    <div class="center">
                        <?php if($user_session!=''){?>
                        <a href="https://dev.allmarathon.fr/formulaire-calendrier.php" class="abon-nwl">J'ajoute mon tournoi</a>
                        <?php 
                            }
                            else{ ?>
                                <a href="#" data-toggle="modal" data-target="#SigninModal" class="abon-nwl">J'ajoute mon tournoi</a>
                        <?php   }
                        ?>
                        
                        </div>
                </div>
                <p class="ban"><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>



                <dt class="anniversaires">Derniers athlètes référencés</dt>
                <dd class="anniversaires">
                    <ul class="clearfix">
                        <?php
                       foreach ($champion->getLastChampions()['donnees'] as $key => $ch) {
                        $flag=$pays->getFlagByAbreviation($ch->getPaysID())['donnees']['Flag'];
                        ($flag!='') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                        echo '<li><a href="">'.$ch->getNom().' ('.$ch->getPaysID().') '.$pays_flag.'</a></li>';
                        }
                    ?>
                    </ul>
                </dd>

                <p class="ban"><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<a href=". $pub160x600['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
            <dd class="facebook">
                <div class="fb-page"
                     data-href="https://www.facebook.com/allmarathonnet-108914759155897/"
                     data-width=""
                     data-adapt-container-width="true"
                     data-hide-cover="false"
                     data-show-facepile="true">
                </div>
            </dd> -->
                <br>

                <div class="g-page" data-href="https://plus.google.com/104135352479039309038" data-width=""
                    data-adapt-container-width="true" data-layout="portrait">
                </div>

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
    <script src="../../js/easing.js"></script>
    <script src="../../js/jquery.ui.totop.min.js"></script>
    <script src="../../js/main.js"></script>


    <script type="text/javascript">
    function goSearch_athlètes() {
        var key = document.getElementById('search_athlètes').value;
        window.location = "http://localhost/allmarathon_nv/www/content/vues/athlètes.php?order=" + key;
    }

    document.getElementById('search_athlètes').onkeypress = function(e) {
        if (!e) e = window.event;
        var keyCode = e.keyCode || e.which;
        if (keyCode == '13') {
            var key = document.getElementById('search_athlètes').value;
            window.location = "http://localhost/allmarathon_nv/www/content/vues/athlètes.php?order=" + key;
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