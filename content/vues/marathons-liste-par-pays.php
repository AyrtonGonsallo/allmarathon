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


include("../classes/champion.php");
include("../classes/marathon.php");
include("../classes/pub.php");

$pub=new pub();

$pub728x90=$pub->getBanniere728_90("athlètes")['donnees'];
$pub300x60=$pub->getBanniere300_60("athlètes")['donnees'];
$pub300x250=$pub->getBanniere300_250("athlètes")['donnees'];
$pub160x600=$pub->getBanniere160_600("athlètes")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];
$ev_cat_event=new evCategorieEvenement();
$champion=new champion();

$pays=new pays();

$order = 'a';
if(isset($_GET['order']))  $order = $_GET['order'];

if(isset($_POST['search']))
        $order =trim($_POST['search']);
$page=0;
if(isset($_GET['page']) && is_numeric($_GET['page'])) $page = intval($_GET['page']);

$nb_pages=intval($champion->getNumberPage($order)['donnees']['COUNT(*)']/80)+1;
$next=$page+1;
$previous=$page-1;



include("../database/connexion.php");
$req = $bdd->prepare('SELECT COUNT(*) as total FROM champions');
$req->execute();
$nb_champs=$req->fetch(PDO::FETCH_ASSOC)['total'];


try{
    include("../database/connexion.php");
    $req = $bdd->prepare("SELECT * FROM evenements where a_l_affiche=1  and Valider=1  ORDER BY DateDebut desc");
    $req->execute();
    $results= array();
    //$first_events= array();
    $last_linked_events= array();
    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
        $req2 = $bdd->prepare("SELECT * FROM marathons where id=:mar_id");
        $req2->bindValue('mar_id', $row["marathon_id"], PDO::PARAM_INT);
        $req2->execute();
        if($req2->rowCount()>0){
            while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
                //var_dump($row2);exit();  
                //array_push($first_events, $row2);
                $row2['date_prochain_evenement']=$row['DateDebut'];
                $row2['is_top_prochain_evenement']=$row['a_l_affiche'];
                $row2['type_evenement']="prochain";
                $row2['date_prochain_evenement_nom']=$row['Nom'];
                $row2['date_prochain_evenement_id']=$row['ID'];
                $row2['last_linked_events_cat_id']=$row['CategorieID'];
                array_push($results, $row2);
            }
        }

        
        
      

      
  }}
  catch(Exception $e)
  {
      die('Erreur : ' . $e->getMessage());
  }
 $results_sorted_by_next_event=array_msort($results, array('type_evenement'=>SORT_DESC,'date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC));
//var_dump($results);exit();
?>


<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>agenda - calendrier des marathons par pays</title>
    <meta name="Description" lang="fr" content="A vos agendas, accédez à la liste complète des marathons à travers le monde classés par pays pour <?php echo date("Y");?>/<?php echo date("Y")+1;?>">

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    
    <link rel="canonical" href="https://dev.allmarathon.fr/liste-des-athletes.html" />
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/responsive.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
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
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->


    <?php include_once('nv_header-integrer.php'); ?>


    <div class="container page-content mpp  mt-77">
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
            <div class="col-sm-12 left-side">

                <div class="row">

                    <div class="col-sm-12 align-with-grid-mpp">
                   

                        <h1>Calendrier des marathons <?php echo date("Y");?>/<?php echo date("Y")+1;?> par pays</h1>
                        <div class="marathon-par-pays-sub-menu-grid">
                            <div class="button-agenda">
                                <a href="/calendrier-agenda-marathons.html" class="home-link">Liste complète des marathons</a>
                            </div>
                            <div class="button-agenda">
                                <a href="/agenda-marathons-par-mois.html" class="home-link">Marathons par mois</a>
                            </div>
                            
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mpp-bg-gray">
        <div class="container page-content">
            <div class="col-sm-12 left-side no-padding-left no-padding-right">
                <div class="row">
                    <div class="col-sm-12 no-padding-left no-padding-right">
                        

                        <?php
                        echo '<div class="mpp-grid">';
                        foreach (getMarathonsAgendaByPays()['donnees'] as $key => $chmp) {
                            $pays_flag=$pays->getFlagByName($chmp['NomPays'])['donnees']['Flag'];
                            
                            $total=gettotalMarathonsByPays($chmp["Abreviation"],$chmp["Abreviation_2"],$chmp["Abreviation_3"],$chmp["Abreviation_4"])['donnees'][0]["total"];
                            if($total==0){
                                continue;
                            }
                            if($total==1){
                                $marathon_par_pays_total=$total.' marathon';
                            }else{
                                $marathon_par_pays_total=$total.' marathons';
                            }
                            echo '<div class="mpp-pays-box">
                            <a href="calendrier-marathons-'.slugify($chmp['NomPays']).'-'.$chmp['pays_id'].'.html">
                            <div class="mpp-title-pays">'.$chmp['NomPays'].'</div>
                            <div class="mpp-nbr-mar mx-auto">'.$marathon_par_pays_total.'</div>
                            </a>
                            </div>';
                        }
                        echo '</div>';
                    
                    ?>
                    </div>

                    <div class="clearfix"></div>

                    <ul class="pager">
                    
                    </ul>

                </div> <!-- End left-side -->

                <aside class="col-sm-4 pd-top">
                    
                </aside>
            </div>

            <?php //include("produits_boutique.php"); ?>
        </div> <!-- End container page-content -->
    </div>
    <div class="container page-content">
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
            <div class="col-sm-12 left-side align-with-grid-mpp">

                <div class="row">

                    <div class="col-sm-12">
                   

                        <h1>Notre sélection de marathons</h1>
                        
                        <ul class="col-sm-12 resultats">
                <div class="row lazyblock" id="liste-marathons">
                        <?php 
                        $i=0;                             
                        setlocale(LC_TIME, "fr_FR","French");
                        $res="";
                        foreach ($results_sorted_by_next_event as $resultat) {
                
                            $pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
                           $nom_res= $resultat['nom'];
                
                            $res.= '<div class="col-sm-4 marathon-grid">
                               ';
                                     
                                    $img_src='/images/marathons/thumb_'.$resultat['image'];
                                    $full_image_path="http://" . $_SERVER['HTTP_HOST'] .$img_src;
                                    //$res.= $full_image_path;

                                    if($resultat['is_top_prochain_evenement']){
                                        $top='<span class="mention-top"><span class="material-symbols-outlined">kid_star</span>Top</span>';
                                    }else{
                                        $top="";
                                    }
                                    $res.='<a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">';
                                    if ($img_src)
                                        {
                                            $res.= '<div class="marathon-liste-image" style="background-image:url('.$img_src.')">'.$top.'</div>';
                                        }else{
                                            $res.= '<div class="marathon-liste-image" style="background-color:#000"></div>';
                                        }
                            $res.='</a>';
                                     if($resultat['last_linked_events_cat_id']){
                                        $res.= '<a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">
                                        <h4 class="page-marathon-title">'.$ev_cat_event->getEventCatEventByID($resultat['last_linked_events_cat_id'])['donnees']->getIntitule().' '.$resultat['prefixe'].' '.$nom_res.'<img class="marathon-title-flag" style="float:right" src="../../images/flags/'.$pays_flag.'" alt=""/></h4></a>';
                                        //$res.= '<div><b>'.$ev_cat_event->getEventCatEventByID($resultat['last_linked_events_cat_id'])['donnees']->getIntitule().'</b></div>';
                
                                     }else{
                                        $res.= '<div><b>Marathon</b></div>';
                
                                     }
                                    if($resultat["type_evenement"]=='prochain'){
                                        $nom_premier_even= $resultat["date_prochain_evenement_nom"];
                                        $id= $resultat["date_prochain_evenement_id"];
                                        $date_premier_even=strftime("%A %d %B %Y",strtotime($resultat["date_prochain_evenement"]));
                                                
                                        $res.= '<div class="date-marathon">'.utf8_encode($date_premier_even).'</div>';
                                    }else if($resultat["type_evenement"]=='dernier'){
                                        $nom_premier_even= $resultat["date_prochain_evenement_nom"];
                                        $id= $resultat["date_prochain_evenement_id"];
                                        $date_premier_even=strftime("%B",strtotime($resultat["date_dernier_evenement"]));
                                                
                                        $res.= '<div class="date-marathon">'.utf8_encode($date_premier_even).' - <span class="marathon-to-come">En attente de date</span></div>';
                                    }else if($resultat["type_evenement"]=='aucun'){
                                        $res.= '<div> Prochaine date À venir</div>';
                                    }
                
                                
                            $res.= '</div>';
                            $i++;
                        }
                        echo $res;
                        ?>
                </div>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

    <script type="text/javascript">
    function goSearch_athlètes() {
        var key = (document.getElementById('search_athlètes').value).toLowerCase();
        window.location = "liste-des-athletes-" + key + ".html";
    }

    document.getElementById('search_athlètes').onkeypress = function(e) {
        if (!e) e = window.event;
        var keyCode = e.keyCode || e.which;
        if (keyCode == '13') {
            var key = (document.getElementById('search_athlètes').value).toLowerCase();
            window.location = "liste-des-athletes-" + key + ".html";
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