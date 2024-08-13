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

include("/var/www/clients/client1/web5/web/content/classes/evenement.php");
include("/var/www/clients/client1/web5/web/content/classes/evCategorieEvenement.php");
include("/var/www/clients/client1/web5/web/content/classes/evCategorieAge.php");
include("/var/www/clients/client1/web5/web/content/classes/pays.php");
include("/var/www/clients/client1/web5/web/content/classes/video.php");
include("/var/www/clients/client1/web5/web/content/classes/resultat.php");
include("/var/www/clients/client1/web5/web/content/classes/pub.php");

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];


$res_image=new resultat();

$video=new video();

$pays=new pays();

$annee = "";
if(isset($_GET['annee']) && $_GET['annee']!="") $annee =$_GET['annee'];

$comp = "";
if(isset($_GET['type']) && $_GET['type']!="") $comp =$_GET['type'];

$cat = "";
if(isset($_GET['age']) && $_GET['age']!="") $cat =$_GET['age'];

$key_word="";
if(isset($_GET['search']) && $_GET['search']) $key_word= $_GET['search'];

$page=0;
if(isset($_GET['p']) && is_numeric($_GET['p'])) $page = intval($_GET['p']);

$key_word="";
if(isset($_GET['search']) && $_GET['search']) $key_word= $_GET['search'];

$event=new evenement();
$archives=$event->getDernierResultatsArchive();
if($key_word!=""){
	$results=$event->getResultsViaSearch($key_word,$page);
}else{
	$results=$event->getResultsPerPage($annee,$comp,$cat,$page);
}


$nb_pages=intval($event->getNumberPages($annee,$comp,$cat,$page,$key_word)['donnees']['COUNT(*)']/40)+1;

$next=$page+1;
$previous=$page-1;

$ev_cat_event=new evCategorieEvenement();
$ev_cat_event_list=$ev_cat_event->getAll();

$ev_cat_age=new evCategorieAge();
$ev_cat_age_list=$ev_cat_age->getAll();



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

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>


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

                        <h1>JUDO, RÉSULTATS DES TOURNOIS ET CHAMPIONNATS DE JUDO : TOURNOIS LABELISÉS, CHAMPIONNATS DE
                            FRANCE, CHAMPIONNATS D'EUROPE, CHAMPIONNATS DU MONDE, JEUX OLYMPIQUES.</h1>

                        <form action="" method="post" class="form-inline" role="form">
                            <div class="form-group" style="width:100%; white-space: nowrap; margin-bottom: 5px;">
                                <input type="search" id="search_val_res" placeholder="Recherche" class="form-control"
                                    style="width: 93%" />
                                <button type="button" onclick="goToSearch_Result();" class="btn btn-primary"><i
                                        class="fa fa-search"></i></button>
                            </div>
                        </form>
                        <form action="" method="post" class="form-inline" role="form">
                            <div class="form-group" style="width:100%; white-space: nowrap;">
                                <select name="annee" id="annee" style="width: 30%;" class="form-control">
                                    <option value="">Toutes les années</option>
                                    <?php for($i=date('Y');$i>1950;$i--)
		                            echo ($i==$year)?'<option value="'.$i.'" selected="selected" >'.$i.'</option>':'<option value="'.$i.'">'.$i.'</option>';
		                         ?>
                                </select>
                                <select name="type" id="type" style="width: 32%;" class="form-control">
                                    <option value="">Tous les types de compétition</option>
                                    <?php
                                foreach ($ev_cat_event_list['donnees'] as $key => $event) {
                                	echo '<option value="'.$event->getId().'">'.$event->getIntitule().'</option>';
                                }
                                ?>
                                </select>
                                <select name="age" id="age" style="width: 30%;" class="form-control">
                                    <option value="">Tous les âges</option>
                                    <?php
                                foreach ($ev_cat_age_list['donnees'] as $key => $value) {
                                	echo '<option value="'.$value->getId().'">'.$value->getIntitule().'</option>';
                                }
                                ?>
                                </select>
                                <button type="button" onClick="sortResult();" class="btn btn-primary"><i
                                        class="fa fa-search"></i></button>
                            </div>
                        </form>

                    </div>


                    <ul class="col-sm-12 resultats">
                        <?php
                		foreach ($results['donnees'] as $key => $resultat) {
                			$cat_event=$ev_cat_event->getEventCatEventByID($resultat['CategorieID'])['donnees']->getIntitule();
                			if($resultat['Type']=="Equipe"){
                                $type_event= " par équipes";
                            }
                            else{
                                $type_event=""; 
                            }
                            $nb_photos=sizeof($res_image->getPhotos($resultat['ID'])['donnees']);
                            ($nb_photos!=0) ? $image_src='<li style="margin-right: 6px;"><img src="../../img/cam.png" alt=""/></li>':$image_src="";

                            $event_video=$video->getEventVideoById($resultat['ID'])['donnees'];
                            ($event_video)? $video_src='<li><img src="../../img/tv.png" alt=""/></li>':$video_src="";
                            $pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
                            $cat_age=$ev_cat_age->getEventCatAgeByID($resultat['CategorieageID'])['donnees']->getIntitule();
                            echo '<li><a href="http://localhost/allmarathon_nv/www/content/vues/evenement-detail.php?id='.$resultat['ID'].'">'.$cat_event.' '.$type_event.' '.$cat_age.' ('.$resultat['Sexe'].') - '.$resultat['Nom'].' - '.substr($resultat['DateDebut'],0,4).'</a>
                        <ul class="list-inline">
                            '.$video_src.' '.$image_src.' '.'
                            <li><img src="../../images/flags/'.$pays_flag.'" alt=""/></li>

	                        </ul>
	                    </li>';
                		}
                        // die;
                	?>
                    </ul>

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
                        <?php echo ' <li><a href="http://localhost/allmarathon_nv/www/content/vues/resultats.php?sort='.$sort.'&p='.$previous.'&search='.$key_word.'"'.$style_precedent.'> Précédent</a></li>
                    <li>'.$next.' / '.$nb_pages.'</li>
                    <li><a href="http://localhost/allmarathon_nv/www/content/vues/resultats.php?sort='.$sort.'&p='.$next.'&search='.$key_word.'"'.$style_suivant.'> Suivant</a></li>';
                     } else {
                         echo '<li><a href="http://localhost/allmarathon_nv/www/content/vues/resultats.php?p='.$previous.'&search='.$key_word.'"'.$style_precedent.'> Précédent</a></li>
                          <li>'.$next.' / '.$nb_pages.'</li>
                        <li><a href="http://localhost/allmarathon_nv/www/content/vues/resultats.php?p='.$next.'&search='.$key_word.'"'.$style_suivant.'> Suivant</a> </li>';
                     }?>
                    </ul>


                </div>







            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <p class="ban"><?php
if($pub300x60 !="") {
echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x60['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>

                <dt class="anniversaires">Derniers résultats archivés</dt>
                <dd class="anniversaires">
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

                <p class="ban"><?php
if($pub300x250 !="") {
echo $pub300x250["code"] ? $pub300x250["code"] :  "<a href=". $pub300x250['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
<div class="newsletter">
                    <div class="title-newsletter">NEWSLETTER</div>
                    <p class="p-newsletter">Restez informés chaque semaine et profitez d'offres exclusives sur <a href="http://www.alljudo.shop/">allmarathon shop</a>.</p>
                    <div class="center"><a href="https://dev.allrathon.fr/formulaire-inscription.php" class="abon-nwl">Je m'abonne</a></div>
                </div>
                
                <div class="newsletter">
                    <div class="title-newsletter">AJOUTER UN TOURNOI</div>
                    <p class="p-newsletter">Vous êtes organisateur d'un tournoi ou d'un stage, vous pouvez annoncer gratuitement votre
                        manifestation et mettre en ligne le dossier de présentation au fomat PDF.</p>
                        <p class="p-newsletter"><strong>Remplissez votre formulaire pour référencer votre manifestation en moins de deux minutes,
                            alors n'attendez-pas...</strong></p>
                    <div class="center">
                        <?php if($user_session!=''){?>
                        <a href="https://dev.allrathon.fr/formulaire-calendrier.php" class="abon-nwl">J'ajoute mon tournoi</a>
                        <?php 
                            }
                            else{ ?>
                                <a href="#" data-toggle="modal" data-target="#SigninModal" class="abon-nwl">J'ajoute mon tournoi</a>
                        <?php   }
                        ?>
                        
                        </div>
                </div>
                <p class="ban"><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<a href=". $pub160x600['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
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

    <?php include('footer.inc.php'); ?>


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


    <script type="text/javascript">
    function sortResult() {

        selected_annee = document.getElementById('annee').selectedIndex;
        annee = document.getElementById('annee')[selected_annee].value;

        selected_age = document.getElementById('age').selectedIndex;
        age = document.getElementById('age')[selected_age].value;

        selected_type = document.getElementById('type').selectedIndex;
        type = document.getElementById('type')[selected_type].value;

        condition = '';
        if (annee != '') {
            if (condition == '') {
                condition += '?annee=' + annee;
            } else {
                condition += '&annee=' + annee;
            }
        }
        if (type != '') {
            if (condition == '') {
                condition += '?type=' + type;
            } else {
                condition += '&type=' + type;
            }
        }
        if (age != '') {
            if (condition == '') {
                condition += '?age=' + age;
            } else {
                condition += '&age=' + age;
            }
        }

        if (condition != '') {
            // alert("condition : "+condition);
            window.location.href = 'http://localhost/allmarathon_nv/www/content/vues/resultats.php' + condition;
        } else {
            // alert("rien");
            window.location.href = 'http://localhost/allmarathon_nv/www/content/vues/resultats.php';
        }
    }
    </script>

    <script type="text/javascript">
    function goToSearch_Result() {
        var key = document.getElementById('search_val_res').value;
        window.location = "http://localhost/allmarathon_nv/www/content/vues/resultats.php?search=" + key;
    }
    document.getElementById('search_val_res').onkeypress = function(e) {
        if (!e) e = window.event;
        var keyCode = e.keyCode || e.which;
        if (keyCode == '13') {
            var key = document.getElementById('search_val_res').value;
            window.location = "http://localhost/allmarathon_nv/www/content/vues/resultats.php?search=" + key;
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