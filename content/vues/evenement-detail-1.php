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

include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/evCategorieAge.php");
include("../classes/video.php");
include("../classes/resultat.php");
include("../classes/pub.php");
include("../classes/pays.php");
include("../classes/evresultat.php");
include("../classes/champion.php");
include("../classes/evequipe.php");
include("../modules/functions.php");

$evequipe=new evequipe();

$ch=new champion();

$pays=new pays();

$ev_cat_age=new evCategorieAge();

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("resultats")['donnees'];
// $id=4951; //video
// $id=4465; //images
// $id=4990; //club
$id=$_GET['evenementID'];
$evresultat=new evresultat();
// $resultas_par_classement=$evresultat->getResultClassement($id)['donnees'];


$video=new video();
$videos=$video->getEventVideoById($id)['donnees'];


$ev_cat_event=new evCategorieEvenement();

$event=new evenement();
$archives=$event->getDernierResultatsArchive();
$evById=$event->getEvenementByID($id)['donnees'];


$resultat=new resultat();
$photos=$resultat->getPhotos($id)['donnees'];

$club =$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getClub();
($club) ? $classement="clubs" : $classement="pays";

$type = "";
if(isset($_GET['type']) && $_GET['type']!="") $type =$_GET['type'];
if ($type==""){
        $active_tab1="active";
        $active_tab2="";
    }else{
        $active_tab2="active";
        $active_tab1="";
    }
if ($evById->getType()=="Parent"){
    $evenements_fils=explode("|",$evById->getEvenements_fils());
    if(strpos($evById->getEvenements_fils(),'|') ){
        $fils=$event->getEventsFils($evenements_fils);
    }
}

$destination_path  = "/uploadDocument/";
$ev_cat_event_int_titre=$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule();
$ev_cat_age_int_titre=$ev_cat_age->getEventCatAgeByID($evById->getCategorieageID())['donnees']->getIntitule();
$annee_titre=substr($evById->getDateDebut(), 0, 4);

function slugify($text)
{
$text = preg_replace('/[^\pL\d]+/u', '-', $text); 
$text = trim($text, '-');
$text = strtolower($text);
return $text;
}

?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Marathon - résultats
        -<?php echo $ev_cat_event_int_titre.' ';       if($evById->getCategorieageID()!=6)       echo $ev_cat_age_int_titre.' ';       echo str_replace('\\','',$evById->getNom()).'  '.$annee_titre; ?>
    </title>
    <meta name="Description" content="Allmarathon.net, tout le marathon en France et dans le monde. Résultats de <?php echo $ev_cat_event_int_titre.' ';       if($evById->getCategorieageID()!=6)       echo $ev_cat_age_int_titre.' ';       echo str_replace('\\','',$evById->getNom()).'  '.$annee_titre; ?>" lang="fr" xml:lang="fr" />
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"> -->
    <link href="http://cdn.datatables.net/responsive/1.0.1/css/dataTables.responsive.css" rel="stylesheet">

    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />


    <link rel="stylesheet" href="../../css/responsive.css">

</head>

<body>



    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlète-detail">
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
            <div class="col-sm-8 left-side resultat-detail">


                <div class="row">
                    <div class="col-sm-12">
                        <?php 
                            $pays_intitule=$pays->getFlagByAbreviation($evById->getPaysId())['donnees']['NomPays'];
                            $ev_cat_event_int=$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule();
                            $ev_cat_age_int=$ev_cat_age->getEventCatAgeByID($evById->getCategorieageID())['donnees']->getIntitule();
                            $annee=substr($evById->getDateDebut(), 0, 4);
                            $date_debut=changeDate($evById->getDateDebut());
                            echo '<h1>'.$evById->getNom().' ('.$pays_intitule.') '.$ev_cat_event_int.' '.$ev_cat_age_int.' DE JUDO '.$annee.' <span class="date">'.$date_debut.'</span></h1>'; ?>



                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <?php echo '<li class="'.$active_tab1.'"><a href="#tab1" role="tab" data-toggle="tab">Résultats</a></li>'; ?>
                            <?php if(!($evById->getType()=="Parent")){?>
                                <li><a href="#tab2" role="tab" data-toggle="tab">VIDEOS
                                        (<?php echo sizeof($videos) ; ?>)</a></li>
                                <li><a href="#tab3" role="tab" data-toggle="tab">PHOTOS
                                        (<?php echo sizeof($photos) ; ?>)</a></li>
                                <?php echo ($evById->getType()!="Equipe") ? '<li  class="'.$active_tab2.'" ><a href="#tab4" role="tab" data-toggle="tab">Classement des '.$classement.'</a></li>' : ""; ?>
                            <?php }?>
                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">

                            <?php ($active_tab1!="") ? $cl_fd_tab1="active fade in" : $cl_fd_tab1="fade";
                        echo '<div class="'.$cl_fd_tab1.' tab-pane" id="tab1">';
                        
                            ?>
                            <ul class="row">
                                <!--<div class="col-12 resultat_shared">
                                    <?php //include_once("shareButtons.php"); ?>
                                </div>-->

                                
                                <li class="col-sm-6">
                                    <br />
                                    <?php if($evById->getDocument1()!='') echo '<a href="PDF_frame-'.rawurlencode($evById->getDocument1()).'" target="_blank" class="btn btn-default"><i class="fa-file-pdf-o fa"></i> Tableaux masculins</a>';  ?>

                                    <?php
                                    $ev_res_sexe=$evresultat->getResultBySexe($id,"M")['donnees'];
                                    $strOM="";
                                    $strOpenM="";
                                    $str100="";
                                    
                                    
                                        
                                            echo '<dt>Masculin</dt>';
                                            echo '<dd> <ul>';
                                            foreach ($ev_res_sexe as $key => $value) {
                                                echo '<li>'.$value['Rang'].'. <a href="athlète-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].' ('.$value['PaysID'].') en '.$value['Temps'].'</a></li>';
                                                 }
                                            echo '</ul> </dd>';
                                        
                                        

                                    
                                    echo ($str100!="") ? $str100: "";
                                    echo ($strOM!="") ? $strOM: "";
                                    echo ($strOpenM!="") ? $strOpenM: "";
                                    ?>

                                </li>
                                <li class="col-sm-6">
                                    <br />
                                    <?php if($evById->getDocument1()!='') echo '<a href="PDF_frame-'.rawurlencode($evById->getDocument1()).'" target="_blank" class="btn btn-default"><i class="fa-file-pdf-o fa"></i> Tableaux masculins</a>';  ?>

                                    <?php
                                    $ev_res_sexe=$evresultat->getResultBySexe($id,"F")['donnees'];
                                    $strOM="";
                                    $strOpenM="";
                                    $str100="";
                                    
                                    
                                        
                                            echo '<dt>Feminin</dt>';
                                            echo '<dd> <ul>';
                                            foreach ($ev_res_sexe as $key => $value) {
                                                echo '<li>'.$value['Rang'].'. <a href="athlète-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].' ('.$value['PaysID'].') en '.$value['Temps'].'</a></li>';
                                                 }
                                            echo '</ul> </dd>';
                                        
                                        

                                    
                                    echo ($str100!="") ? $str100: "";
                                    echo ($strOM!="") ? $strOM: "";
                                    echo ($strOpenM!="") ? $strOpenM: "";
                                    ?>

                                </li>
                            </ul>
                           
                                
                        </div>
                        <div class="tab-pane fade" id="tab2">

<ul class="videos-tab">
    <?php
        foreach ($videos as $key => $vd) {
            ($vd->getDuree()!='') ?  $duree="<li>durée : ".$vd->getDuree()."</li>" : $duree="<li style='list-style-type: none;'></li>";
        ($vd->getTop_ippon()) ? $img_top ='<img src="../../images/pictos/badge.png" style="right: 19px;top: 3px;position: absolute;"alt=""/>' : $img_top="";
            echo '<li class="row">
            <ul>
                <li class="col-sm-6">
                    <ul>
                        <li><a href="video-de-judo-'.$vd->getId().'.html">'.$vd->getTitre().'</a></li>
                        '.$duree.'
                        <li>publiée le '.date("d/m/y", strtotime($vd->getDate())).'</li>
                    </ul>
                </li>
                <li class="col-sm-6"><a href="video-de-judo-'.$vd->getId().'.html"><img src="'.$vd->getVignette().'" alt="" style="width: 173px;"class="pull-right img-responsive"/>'.$img_top.'</a></li>

            </ul>
        </li>';
            }
    ?>



</ul>

</div>
                            <div class="tab-pane fade" id="tab3">
                                <ul class="photos-tab">
                                    <?php
                                    if(sizeof($photos)!=0){
                                    foreach ($photos as $key => $photo) {
                                        echo '<li><a href="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" class="fancybox" rel="group"><img src="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" width="116" height="77" alt=""/></a></li>';
                                    } 
                                    }
                                    
                                    
                                    ?>
                                </ul>
                            </div>
                            <?php echo '<div class="tab-pane" id="tab4">' ?>
                            <ul class="list-inline" style="margin:10px 0;">
                                <?php 
                                    // echo '<li><a class="btn btn-default" href="http://localhost/alljudo_nv/www/content/vues/evenement-detail.php?id='.$id.'&type=hf">Afficher tous</a></li>
                                    //  <li><a class="btn btn-default" href="http://localhost/alljudo_nv/www/content/vues/evenement-detail.php?id='.$id.'&type=homme"><i class="fa fa-filter"></i> Hommes</a></li>
                                    // <li><a class="btn btn-default" href="http://localhost/alljudo_nv/www/content/vues/evenement-detail.php?id='.$id.'&type=femme"><i class="fa fa-filter"></i> Femmes</a></li>'; 
                                    echo '<li><a class="btn btn-default" href="/resultats-judo-'.$id.'-hf.html">Afficher tous</a></li>
                                    <li><a class="btn btn-default" href="/resultats-judo-'.$id.'-homme.html"><i class="fa fa-filter"></i> Hommes</a></li>
                                    <li><a class="btn btn-default" href="/resultats-judo-'.$id.'-femme.html"><i class="fa fa-filter"></i> Femmes</a></li>'; 
                                    ?>

                            </ul>
                            <table id="classement_table" class="table table-responsive  table-bordered classement">
                                <thead>
                                    <tr>
                                        <?php ($classement=="pays") ? $th="Pays" : $th="Clubs";
                                        echo '<th class="">'.$th.'</th>' ?>
                                        <th class="">1ere place</th>
                                        <th class="">2e place</th>
                                        <th class="">3e place</th>
                                        <th class="">5e place</th>
                                        <th class="">7e place</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $res_class_pays=$evresultat->getResultClassementByPays($id,$type,$classement)['donnees'];
                                        foreach ($res_class_pays as $key => $value) {
                                            if($classement=="pays") {
                                                $pays_club=$value["PaysID"];
                                                if($pays->getFlagByAbreviation($value["PaysID"])['donnees']){
                                                    $pays_name=$pays->getFlagByAbreviation($value["PaysID"])['donnees']['NomPays'];
                                                    $pays_flag=$pays->getFlagByAbreviation($value["PaysID"])['donnees']['Flag'];
                                                    $td='<img src="../../images/flags/'.$pays_flag.'" alt=""/>'.$pays_name.'';
                                                }
                                                
                                            
                                            }else{
                                                $pays_club=$value["Club"];
                                                $td=$value["Club"];
                                            }
                                                $resultat_details= $evresultat->getResultClassement($id,$pays_club,$type,$classement)['donnees'];
                                            
                                            echo '<tr>

                                                        <td>'.$td.'</td>';
                                                        $p1="-";$p2="-";$p3="-";$p5="-";$p7="-";
                                                        foreach ($resultat_details as $key => $res_det_value) {
                                                            $indice1=0;$indice2=0;$indice3=0;$indice5=0;$indice7=0;
                                                            if($res_det_value['Rang']==1)  $p1=$res_det_value['nb'];
                                                            if ($res_det_value['Rang']==2) $p2=$res_det_value['nb'] ;
                                                            if ($res_det_value['Rang']==3)  $p3=$res_det_value['nb'] ;
                                                            if ($res_det_value['Rang']==5)  $p5=$res_det_value['nb'] ;
                                                            if ($res_det_value['Rang']==7)  $p7=$res_det_value['nb'];
                                                        }
                                            echo'<td>'.$p1.'</td>
                                                                    <td>'.$p2.'</td>
                                                                    <td>'.$p3.'</td>
                                                                    <td>'.$p5.'</td>
                                                                    <td>'.$p7.'</td></tr>';
                                        }
                                        
                                        ?>
                                </tbody>
                            </table>
                        </div>
                    <?php ?>
                </div>
            </div>
        </div>

    </div> <!-- End left-side -->

    <aside class="col-sm-4">
        <p class="ban"><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x60['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
        <dt class="archive">Résultats anciens</dt>
        <dd class="archive">
            <ul class="clearfix">
                <?php
                        foreach ($archives['donnees'] as $key => $ev_archive) {
                            $cat_event=$ev_cat_event->getEventCatEventByID($ev_archive->getCategorieId())['donnees']->getIntitule();
                            if($ev_archive->getType()=="Equipe"){
                                $type_event= " par équipes";
                            }
                            else{
                                $type_event=""; 
                            }
                            $nom_res=$cat_event." ".$type_event." (".$ev_archive->getSexe().") ".$ev_archive->getNom()." ".substr($ev_archive->getDateDebut(),0,4);
                            echo '<li><a href="/resultats-marathon-'.$ev_archive->getId().'-'.slugify($nom_res).'.html">'.$nom_res.'</a></li>';
                        }
                    ?>
            </ul>
        </dd>
        <div class="marg_bot"></div>
        <p class="ban"><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
        <div class="marg_bot"></div>
        <p class="ban ban_160-600"><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<a href=". $pub160x600['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
        <div class="marg_bot"></div>
        <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
            <dd class="facebook">
                <div class="fb-page"
                     data-href="https://www.facebook.com/allmarathonnet-108914759155897/"
                     data-width=""
                     data-adapt-container-width="true"
                     data-hide-cover="false"
                     data-show-facepile="true">
                </div>
            </dd>
            <div class="marg_bot"></div> -->


    </aside>
    </div>

    </div> <!-- End container page-content -->


    <?php include_once('footer.inc.php'); ?>

    <style type="text/css">

    </style>

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
    <script src="../../js/herbyCookie.min.js"></script>
    <script src="../../js/main.js"></script>

    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" src="/js/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox -->
    <script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <script type="text/javascript" src="/js/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/jquery.fancybox-media.js?v=1.0.6"></script>

    <script type="text/javascript" src="/js/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $(".fancybox").fancybox({
            helpers: {
                overlay: {
                    css: {
                        'background': 'rgba(0, 0, 0, 0.4)'
                    }
                }
            },
            margin: [110, 60, 30, 60]
        });
    });
    </script>


    <script>
    (function(i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function() {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
            m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-1833149-1', 'auto');
    ga('send', 'pageview');
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

    <script type="text/javascript">
    $(document).ready(function() {
        $('#classement_table').DataTable({
            "responsive": true,
            "paging": false,
            "bFilter": false,
            "info": false,
            "order": [
                [1, "desc"],
                [2, "desc"],
                [3, "desc"],
                [4, "desc"],
                [5, "desc"]
            ]
        });
    });
    </script>
</body>

</html>