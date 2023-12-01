<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

(!empty($_SESSION['auth_error'])) ? $erreur_auth=$_SESSION['auth_error'] : $erreur_auth='';
(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!empty($_SESSION['user'])) {
$user=$_SESSION['user'];
$erreur_auth='';
}  else $user='';

include("/../classes/evenement.php");
include("/../classes/evCategorieEvenement.php");
include("/../classes/evCategorieAge.php");
include("/../classes/video.php");
include("/../classes/resultat.php");
include("/../classes/pub.php");
include("/../classes/pays.php");
include("/../classes/evresultat.php");
include("/../classes/champion.php");
include("/../modules/functions.php");

$ch=new champion();

$pays=new pays();

$ev_cat_age=new evCategorieAge();

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];

$id=4951; //video
// $id=4465; //images
// $id=4990; //club

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

?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("/../scripts/header_script.php") ?>
    <title></title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <!-- Place favicon.ico in the root directory -->

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">

</head>

<body>



    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlète-detail">
        <div class="row banniere1">
            <a href="" class="col-sm-12"><?php
if($pub728x90 !="") {
echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a>
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
                    $date_fin=changeDate($evById->getDateFin());
                    echo '<h1>'.$evById->getNom().' ('.$pays_intitule.') '.$ev_cat_event_int.' '.$ev_cat_age_int.' DE JUDO '.$annee.' <span class="date">'.$date_debut.' - '.$date_fin.'</span></h1>'; ?>


                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <!-- <li class="active"><a href="#tab1" role="tab" data-toggle="tab">Résultats</a></li> -->
                            <?php echo '<li class="'.$active_tab1.'"><a href="#tab1" role="tab" data-toggle="tab">Résultats</a></li>'; ?>

                            <li><a href="#tab2" role="tab" data-toggle="tab">VIDEOS
                                    (<?php echo sizeof($videos) ; ?>)</a></li>
                            <li><a href="#tab3" role="tab" data-toggle="tab">PHOTOS
                                    (<?php echo sizeof($photos) ; ?>)</a></li>
                            <!-- <li><a href="#tab4" role="tab" data-toggle="tab">Classement des <?php echo $classement; ?></a></li> -->
                            <?php echo '<li  class="'.$active_tab2.'" ><a href="#tab4" role="tab" data-toggle="tab">Classement des '.$classement.'</a></li>'; ?>

                        </ul>
                        <!-- TAB CONTENT -->
                        <div class="tab-content">
                            <!-- <div class="active tab-pane fade in" id="tab1"> -->
                            <?php ($active_tab1!="") ? $cl_fd_tab1="active fade in" : $cl_fd_tab1="fade";
                        echo '<div class="'.$cl_fd_tab1.' tab-pane" id="tab1">'?>
                            <ul class="row">
                                <li class="col-sm-6">
                                    <br />
                                    <a href="" class="btn btn-default"><i class="fa-file-pdf-o fa"></i> Tableaux
                                        féminins</a>
                                    <?php
                                    $ev_res_sexe=$evresultat->getResultBySexe($id,"F")['donnees'];
                                    foreach ($ev_res_sexe as $key => $value) {
                                        $ev_res=$evresultat->getEvResultByEventID($id,"F",$value['poidId'])['donnees'];
                                        echo '<dt>'.$value['poidId'].'</dt>';
                                        echo '<dd> <ul>';
                                        foreach ($ev_res as $key => $res) {
                                            $champion_name=$ch->getChampionById($res['ChampionID'])['donnees']->getNom();
                                            $champion_pays=$ch->getChampionById($res['ChampionID'])['donnees']->getPaysID();
                                            $rang= $res['Rang'];
                                            echo '<li>'.$rang.'. <a href="">'.$champion_name.' ('.$champion_pays.')</a></li>';
                                        }
                                        echo '</ul> </dd>';
                                    }
                                    ?>


                                </li>
                                <li class="col-sm-6">
                                    <br />
                                    <a href="" class="btn btn-default"><i class="fa-file-pdf-o fa"></i> Tableaux
                                        masculins</a>
                                    <?php
                                    $ev_res_sexe=$evresultat->getResultBySexe($id,"M")['donnees'];
                                    foreach ($ev_res_sexe as $key => $value) {
                                        $ev_res=$evresultat->getEvResultByEventID($id,"M",$value['poidId'])['donnees'];
                                        echo '<dt>'.$value['poidId'].'</dt>';
                                        echo '<dd> <ul>';
                                        foreach ($ev_res as $key => $res) {
                                            $champion_name=$ch->getChampionById($res['ChampionID'])['donnees']->getNom();
                                            $champion_pays=$ch->getChampionById($res['ChampionID'])['donnees']->getPaysID();
                                            $rang= $res['Rang'];
                                            echo '<li>'.$rang.'. <a href="">'.$champion_name.' ('.$champion_pays.')</a></li>';
                                        }
                                        echo '</ul> </dd>';
                                    }
                                    ?>

                                </li>
                            </ul>

                        </div>
                        <div class="tab-pane fade" id="tab2">
                            <form class="form-inline" style="margin: 10px 0; ">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="cat" value="masculin"> Catégories masculines
                                    </label><br>
                                    <label>
                                        <input type="checkbox" name="cat" value="feminin"> Catégories féminine
                                    </label>

                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-filter"></i> Par
                                        catégories de poids</button>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">Afficher toutes les vidéos</button>
                                </div>

                                <!--<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>-->
                            </form>

                            <ul class="videos-tab">
                                <?php
                                    foreach ($videos as $key => $vd) {
                                        ($vd->getDuree()!='') ?  $duree="<li>durée : ".$vd->getDuree()."</li>" : $duree="<li style='list-style-type: none;'></li>";
                                       ($vd->getTop_ippon()) ? $img_top ='<img src="../../img/badge.png" style="right: 19px;top: 3px;position: absolute;"alt=""/>' : $img_top="";
                                        echo '<li class="row">
                                        <ul>
                                            <li class="col-sm-6">
                                                <ul>
                                                    <li><a href="">'.$vd->getTitre().'</a></li>
                                                    '.$duree.'
                                                    <li>publiée le '.date("d/m/y", strtotime($vd->getDate())).'</li>
                                                </ul>
                                            </li>
                                            <li class="col-sm-6"><a href=""><img src="'.$vd->getVignette().'" alt="" class="pull-right img-responsive"/>'.$img_top.'</a></li>

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
                                    echo '<li><a href=""><img src="/allmarathon_nv/www/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" width="116" height="77" alt=""/></a></li>';
                                } 
                                }
                                
                                
                                ?>
                            </ul>
                        </div>
                        <!-- <div class="tab-pane fade" id="tab4"> -->
                        <?php ($active_tab2!="") ? $cl_fd="active fade in" : $cl_fd="fade";
                        echo '<div class="tab-pane '.$cl_fd.'" id="tab4">' ?>
                        <ul class="list-inline" style="margin:10px 0;">
                            <li><a class="btn btn-default" href="">Afficher tous</a></li>
                            <li><a class="btn btn-default" href=""><i class="fa fa-filter"></i> Hommes</a></li>
                            <li><a class="btn btn-default" href=""><i class="fa fa-filter"></i> Femmes</a></li>
                        </ul>
                        <table id="classement_tabl" class="table table-responsive table-bordered classement">
                            <thead>
                                <tr>
                                    <th class="">Pays</th>
                                    <th class="">1ere place</th>
                                    <th class="">2e place</th>
                                    <th class="">3e place</th>
                                    <th class="">5e place</th>
                                    <th class="">7e place</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $res_class_pays=$evresultat->getResultClassementByPays($id)['donnees'];
                                    foreach ($res_class_pays as $key => $value) {
                                        // 
                                        $resultat_details= $evresultat->getResultClassement($id,$value["PaysID"])['donnees'];
                                        $pays_name=$pays->getFlagByAbreviation($value["PaysID"])['donnees']['NomPays'];
                                        $pays_flag=$pays->getFlagByAbreviation($value["PaysID"])['donnees']['Flag'];
                                        echo '<tr>

                                                    <td><img src="../../images/flags/'.$pays_flag.'" alt=""/> '.$pays_name.'</td>';
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
                </div>
            </div>
        </div>

    </div> <!-- End left-side -->

    <aside class="col-sm-4">
        <p class="ban"><a href=""><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
        <dt class="archive">Derniers résultats archivés</dt>
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
                            echo '<li><a href="">'.$cat_event." ".$type_event." (".$ev_archive->getSexe().") ".$ev_archive->getNom()." ".substr($ev_archive->getDateDebut(),0,4).'</a></li>';
                        }
                    ?>
            </ul>
        </dd>
        <p class="ban"><a href=""><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
        <p class="ban"><a href=""><?php
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

        <div class="g-page" data-href="https://plus.google.com/104135352479039309038" data-width="310"
            data-layout="portrait">
        </div>

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
    <script src="../../js/main.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>




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
            "paging": false,
            "bFilter": false,
            "info": false,
            "order": [
                [1, "desc"]
            ]
        });
    });
    </script>
</body>

</html>