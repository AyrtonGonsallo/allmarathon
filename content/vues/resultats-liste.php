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



include("../classes/evenement.php");

include("../classes/evCategorieEvenement.php");

include("../classes/evCategorieAge.php");

include("../classes/pays.php");

include("../classes/video.php");

include("../classes/resultat.php");

include("../classes/pub.php");



$pub=new pub();



$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];

$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];

$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];

$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];

$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("resultats")['donnees'];




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

if(isset($_GET['page']) && is_numeric($_GET['page'])) $page = intval($_GET['page']);





$event=new evenement();

$archives=$event->getDernierResultatsArchive();

if($key_word!=""){

	$results=$event->getResultsViaSearch($key_word,$page);

}else

{

	$results=$event->getResultsPerPage($annee,$comp,$cat,$page);

}


$nb_pages=intval($event->getNumberPages($annee,$comp,$cat,$page,$key_word)['donnees']['COUNT(*)']/40)+1;


 



$next=$page+1;

$previous=$page-1;



$ev_cat_event=new evCategorieEvenement();

$ev_cat_event_list=$ev_cat_event->getAll();



$ev_cat_age=new evCategorieAge();

$ev_cat_age_list=$ev_cat_age->getCatParentes();



function slugify($text)

{
    $text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 

    $text = trim($text, '-');

    $text = strtolower($text);

    return $text;

}


setlocale(LC_TIME, "fr_FR","French");


?>

<!doctype html>

<html class="no-js" lang="fr">



<head>

    <meta charset="utf-8">

    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">

    <?php require_once("../scripts/header_script.php") ?>

    <title>Résultats de tous les marathons nationaux et internationaux | allmarathon.fr</title>

    <meta name="Description" content="Résultats de tous les marathons nationaux et internationaux :  Championnats de France, Championnats d'Europe, Championnats du Monde, Jeux Olympiques, World Major" lang="fr" xml:lang="fr">

    <meta property="og:title" content="Résultats de tous les marathons nationaux et internationaux." />
    <meta property="og:description" content="Résultats de tous les marathons nationaux et internationaux :  Championnats de France, Championnats d'Europe, Championnats du Monde, Jeux Olympiques, World Major." />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:image" content="https://allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="https://allmarathon.fr/resultats-marathon.html" />
    
    <link rel="canonical" href="https://allmarathon.fr/resultats-marathon.html" />


    <link rel="apple-touch-icon" href="apple-favicon.png">

    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />



    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <link rel="stylesheet" href="../../css/font-awesome.min.css">

    <link rel="stylesheet" href="../../css/fonts.css">

    <link rel="stylesheet" href="../../css/slider-pro.min.css" />

    <link rel="stylesheet" href="../../css/main.css">

    <link rel="stylesheet" href="../../css/responsive.css">

    <style>

           #liste {

    

    min-height: 100vh

}



#liste .text-gray {

    color: #aaa

}



#liste img {

    height: 170px;

    width: 140px

}

        </style>

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->

</head>



<body>





    <?php include_once('nv_header-integrer.php'); ?>





    <div class="container page-content athlètes">

     

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

            <div class="col-sm-8 left-side">



                <div class="row">



                    <div class="col-sm-12">


                        <h1>Résultats des marathons en France et dans le monde</h1>
                        <h2>Jeux olympiques, championnats du monde, championnats d'Europe, championnats de France, World Majors Marathon</h2>

                        <form action="" method="post" class="form-inline" role="form">

                            <div class="form-group" style="width:100%; white-space: nowrap; margin-bottom: 5px;">

                                <input type="search" id="search_val_res" placeholder="Recherche" class="form-control"

                                    style="width: 93%" />

                                <button type="button" onclick="goToSearch_Result();" class="btn btn-primary"><i

                                        class="fa fa-search"></i></button>

                            </div>

                        </form>

                        



                    </div>





                    <ul class="col-sm-12 resultats">

                        <?php

                		foreach ($results['donnees'] as $key => $resultat) {

                			$cat_event=$ev_cat_event->getEventCatEventByID($resultat['CategorieID'])['donnees']->getIntitule();

                			

                            $nb_photos=sizeof($res_image->getPhotos($resultat['ID'])['donnees']);

                            ($nb_photos!=0) ? $image_src='<li style="margin-right: 6px;"><img src="../../images/pictos/cam.png" alt=""/></li>':$image_src="";



                            $event_video=$video->getEventVideoById($resultat['ID'])['donnees'];

                            ($event_video)? $video_src='<li><img src="../../images/pictos/tv.png" alt=""/></li>':$video_src="";

                            $pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];

                            $cat_age=$ev_cat_age->getEventCatAgeByID($resultat['CategorieageID'])['donnees']->getIntitule();

                            $nom_res='<strong>'.$cat_event.' - '.$resultat['Nom'].'</strong> - '.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut'])));
                            $nom_res_lien=$cat_event.' - '.$resultat['Nom'].' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut'])));

                            echo '<li><a href="/resultats-marathon-'.$resultat['ID'].'-'.slugify($nom_res_lien).'.html">'.$nom_res.'</a>

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

                            $sort.= ($annee!="") ? "_".$annee : "_";

                            $sort.= ($cat!="") ? "_".$cat : "_";

                            $sort.= ($comp!="") ? "_".$comp : "_";

                            

                            if($sort!='___') { 

                        ?>

                        <?php echo ' <li><a href="/resultats-marathon_'.$previous.$sort.'.html"'.$style_precedent.'> Précédent</a></li>

                    <li>'.$next.' / '.$nb_pages.'</li>

                    <li><a href="/resultats-marathon_'.$next.$sort.'.html"'.$style_suivant.'> Suivant</a></li>';

                     } else {

                         echo '<li><a href="/resultats-marathon_'.$previous.'_'.$key_word.'.html"'.$style_precedent.'> Précédent</a></li>

                          <li>'.$next.' / '.$nb_pages.'</li>

                        <li><a href="/resultats-marathon_'.$next.'_'.$key_word.'.html"'.$style_suivant.'> Suivant</a> </li>';

                     }?>

                    </ul>





                </div>















            </div> <!-- End left-side -->



            <aside class="col-sm-4 pd-top">

                 <p class="ban"><?php

                    if($pub300x60 !="") {

                    echo $pub300x60["code"] ? $pub300x60["code"] :  "<a href=". $pub300x60['url'] ." target='_blank'><img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";

                    }

                    ?></a></p>



                <dt class="anniversaires">Résultats anciens</dt>

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

                            $nom_res_archive=$cat_event." ".$type_event." (".$ev_archive->getSexe().") ".$ev_archive->getNom()." ".substr($ev_archive->getDateDebut(),0,4);
                            $nom_res_lien_archive=$cat_event.' - '.$ev_archive->getNom().' - '.strftime("%A %d %B %Y",strtotime($ev_archive->getDateDebut()));

                            echo '<li><a href="/resultats-marathon-'.$ev_archive->getId().'-'.slugify($nom_res_lien_archive).'.html">'.$nom_res_archive.'</a></li>';

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

                 <p class="ban ban_160-600"><a href=""><?php

if($pub160x600 !="") {

    //var_dump($pub160x600["url"]); exit;

    if($pub160x600["code"]==""){

        echo "<a href=".'http://allmarathon.net/'.$pub160x600["url"]." target='_blank'><img src=".'../images/news/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";

    }

    else{

        echo $pub160x600["code"];

    }

/*echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";*/

}

?></a></p>

                <div class="marg_bot"></div>





            </aside>

        </div>



    </div> <!-- End container page-content -->

<?php 

            //echo $pub768x90; 

            //include("produits_boutique.php");  

            ?>

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

        condition = "__" + annee + "_" + age + "_" + type + ".html";

        if (condition != '') {

            // alert("condition : "+condition);

            window.location.href = '/resultats-marathon' + condition;

        } else {

            // alert("rien");

            window.location.href = '/resultats-marathon.html';

        }

    }

    </script>



    <script type="text/javascript">

    function goToSearch_Result() {

        var key = document.getElementById('search_val_res').value;

        window.location = "resultats-marathon__" + key + ".html";

    }

    document.getElementById('search_val_res').onkeypress = function(e) {

        if (!e) e = window.event;

        var keyCode = e.keyCode || e.which;

        if (keyCode == '13') {

            var key = document.getElementById('search_val_res').value;

            window.location = "resultats-marathon__" + key + ".html";

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