<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
setlocale(LC_TIME, "fr_FR","French");
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

include("../classes/evCategorieAge.php");
include("../classes/evenement.php");
include("../classes/resultat.php");
include("../classes/pub.php");
include("../classes/pays.php");
include("../classes/evresultat.php");
include("../modules/functions.php");
require_once '../database/connexion.php';
include("../classes/evCategorieEvenement.php");
include("../classes/video.php");



$ev_cat_age=new evCategorieAge();
$pays=new pays();
$ev_cat_event=new evCategorieEvenement();
$res_image=new resultat();
$video=new video();
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
$id=$_GET['marathonID'];
$evresultat=new evresultat();
// $resultas_par_classement=$evresultat->getResultClassement($id)['donnees'];

$active_tab1="active";
$active_tab2="active";
$active_tab3="active";
$active_tab4="active";
$event=new evenement();
$results=$event->getMarathonEvents($id)['donnees'];
//ce marathon
try{
    $req = $bdd->prepare("SELECT * FROM marathons WHERE id=:id");
                
    $req->bindValue('id',$id, PDO::PARAM_INT);

    $req->execute();

    $marathon= $req->fetch(PDO::FETCH_ASSOC);

}
catch(Exception $e){
    die('Erreur : ' . $e->getMessage());
}
//les 10 prochains marathons
try{
    $req = $bdd->prepare("SELECT * FROM evenements WHERE Valider=1  AND (DateDebut > :today) ORDER BY DateDebut limit 10");
    $req->bindValue('today', date('Y-m-d'), PDO::PARAM_STR);  
   

    $req->execute();
    $autres_marathon= array();
    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
         array_push($autres_marathon, $row);
    }
    

}
catch(Exception $e){
    die('Erreur : ' . $e->getMessage());
}
//next_date
try{
    
        $req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id  and video_teaser!='' order by ID desc limit 1");
        $req2->bindValue('mar_id', $id, PDO::PARAM_INT);
        $req2->execute();
        $next_date_video= $req2->fetch(PDO::FETCH_ASSOC);
        $req222 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id AND (DateDebut > :today) order by ID desc limit 1");
        $req222->bindValue('today', date('Y-m-d'), PDO::PARAM_STR);  
        $req222->bindValue('mar_id', $id, PDO::PARAM_INT);
        $req222->execute();
        $next_date= $req222->fetch(PDO::FETCH_ASSOC);
  
}
catch(Exception $e)
  {
      die('Erreur : ' . $e->getMessage());
  }
  $last_linked_events= array();
  try{  
    $req3 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1 ORDER BY ID desc limit 1");
    $req3->bindValue('mar_id', $id, PDO::PARAM_INT);
    $req3->execute();
    if($req3->rowCount()>0){
        while ( $row3  = $req3->fetch(PDO::FETCH_ASSOC)) {
            //var_dump($row2);exit();  
            array_push($last_linked_events, $row3);
        }
    }else {
        array_push($last_linked_events, NULL);
    }
}
catch(Exception $e){
    die('Erreur : ' . $e->getMessage());
}
if($last_linked_events[0]!=NULL ){
    $categorie=$ev_cat_event->getEventCatEventByID($last_linked_events[0]['CategorieID'])['donnees']->getIntitule();
}
else{
    $categorie="";
}
$best_res_mens = $evresultat->getBestMarathonResultsBySexe($id,'M',8)['donnees'];
$best_res_womens = $evresultat->getBestMarathonResultsBySexe($id,'F',8)['donnees'];
$best_res_mens_byyear = $evresultat->getBestMarathonResultsByYear($id,'M',18)['donnees'];
$best_res_womens_byyear = $evresultat->getBestMarathonResultsByYear($id,'F',18)['donnees'];
$next_event = $event->getNextMarathonEvents($id)['donnees'];
function slugify($text)
{
$text = preg_replace('/[^\pL\d]+/u', '-', $text); 
$text = trim($text, '-');
$text = strtolower($text);
return $text;
}


$pays_datas=$pays->getFlagByAbreviation($marathon['PaysID'])['donnees'];
if($pays_datas){
    $flag=$pays_datas['Flag'];  
}
($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title><?php echo $categorie;?> - <?php echo $marathon['nom'];?> - <?php echo $pays_datas['NomPays'];?> | allmarathon.fr</title>
    <meta name="Description" content="Toutes les informations sur le marathon de <?php echo $marathon['lieu'];?> : prochaine édition, résultats des éditions précédentes, records... " lang="fr" xml:lang="fr" />
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
  
  
    <?php echo '<link rel="canonical" href="https://allmarathon.fr/marathons-'.$marathon['id'].'-'.slugify($marathon['nom']).'.html" />';?>

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/responsive.css">
    <?php if($best_res_mens_byyear || $best_res_womens_byyear){ ?>
    <script>
window.onload = function() {
    console.log("chargement graphiques hommes")
            $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               dataType:"JSON",
               data: {
                   function: "getTopChronosbyYears",
                   sexe:'M',
                   marathon_id:<?php echo $id;?>,
                   limit:18,
               },
               success: function(html) {
                   
                   //console.log("success",html)
                   var données=html;
                   var dataPoints = [];
    
                    var chart = new CanvasJS.Chart("chartContainer-m", {
                        animationEnabled: true,
                        theme: "light2",
                        zoomEnabled: true,
                        title: {
                            text: "Évolution meilleur chrono Hommes"
                        },
                        axisY: {
                            title: "temps",
                            titleFontSize: 24,
                            labelFormatter: function (e) {
                                return CanvasJS.formatDate( e.value, "HH:mm:ss");
                            },
                        },
                        toolTip: {
                            fontStyle: "oblique",
                            contentFormatter: function ( e ) {
                                var timeStamp= e.entries[0].dataPoint.y;
                                var dateFormat= new Date(timeStamp);
                                var ft=dateFormat.getHours()+":"+dateFormat.getMinutes()+":"+dateFormat.getSeconds();
                                return "Année: " +  e.entries[0].dataPoint.x.getFullYear()+" - Temps: " +ft ;  
                            }  
                        },
                        data: [{
                            type: "line",
                            
                           // yValueFormatString: "HH:mm:ss",
                            dataPoints: dataPoints,
                            //yValueType: "dateTime",
                        }]
                    });
                

                    for (var i = 0; i < données.length; i++) {
                        temps=données[i]["Temps"];
                        h= Number(temps.substr(0, 2))
                        m= Number(temps.substr(3, 2))
                        s= Number(temps.substr(6, 2))
                        sec=(3600*h)+(60*m)+s;
                        //console.log(h,m,s,sec)
                        dataPoints.push({
                            x: new Date(données[i]["annee"]),
                            y: new Date(2016, 1, 15,h,m,s).getTime()
                        });
                    }
                    chart.render();

               },
               error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log("error",msg)
                },
           }); 

           //---------


           console.log("chargement graphiques femmes")
            $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               dataType:"JSON",
               data: {
                   function: "getTopChronosbyYears",
                   sexe:'F',
                   marathon_id:<?php echo $id;?>,
                   limit:18,
               },
               success: function(html) {
                   
                   console.log("success",html)
                   var données=html;
                   var dataPoints = [];
    
                    var chart = new CanvasJS.Chart("chartContainer-f", {
                        animationEnabled: true,
                        theme: "light2",
                        zoomEnabled: true,
                        title: {
                            text: "Évolution meilleur chrono Femmes"
                        },
                        axisY: {
                            title: "temps",
                            titleFontSize: 24,
                            labelFormatter: function (e) {
                                return CanvasJS.formatDate( e.value, "HH:mm:ss");
                            },
                        },
                        toolTip: {
                            fontStyle: "oblique",
                            contentFormatter: function ( e ) {
                                var timeStamp= e.entries[0].dataPoint.y;
                                var dateFormat= new Date(timeStamp);
                                var ft=dateFormat.getHours()+":"+dateFormat.getMinutes()+":"+dateFormat.getSeconds();
                                return "Année: " +  e.entries[0].dataPoint.x.getFullYear()+" - Temps: " +ft ;  
                            }  
                        },
                        data: [{
                            type: "line",
                            yValueFormatString: "0.00",
                            dataPoints: dataPoints
                        }]
                    });
                

                    for (var i = 0; i < données.length; i++) {
                        temps=données[i]["Temps"];
                        h= Number(temps.substr(0, 2))
                        m= Number(temps.substr(3, 2))
                        s= Number(temps.substr(6, 2))
                        sec=(3600*h)+(60*m)+s;
                        //console.log(h,m,s,sec)
                        dataPoints.push({
                            x: new Date(données[i]["annee"]),
                            y: new Date(2016, 1, 15,h,m,s).getTime()
                        });
                    }
                    chart.render();

               },
               error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log("error",msg)
                },
           }); 
}
</script>
<?php } ?>
</head>

<body>



    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlète-detail marathon-detail">
        <div class="row banniere1">
            <div  class="col-sm-12">
                <?php
                    if($pub728x90 !="") {
                    echo '<a target="_blank" href="'.$pub728x90["url"].'" class="col-sm-12">';
                        echo $pub728x90["code"] ? $pub728x90["code"] :  "<img src=".'../images/pubs/'.$pub728x90['image'] . " alt='' style=\"width: 100%;\" />";
                        echo '</a>';
                    }else if($getMobileAds !="") {
                    echo $getMobileAds["code"] ? $getMobileAds["code"] :  "<a href=".$getMobileAds["url"]." target='_blank'><img src=".'../images/pubs/'.$getMobileAds['image'] . " alt='' style=\"width: 100%;\" /></a>";
                    }
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side resultat-detail">


                <div class="row">
                    <div class="col-sm-12">
                        <?php 
                            
                           
                            $lien_youtube=($marathon['youtube'])?'<i
                            class="fa fa-youtube"></i><a href="'.$marathon['youtube'].'" target="_blank">Youtube</a><br>':'';
                            $lien_facebook=($marathon['facebook'])?'<i
                            class="fa fa-facebook"></i><a href="'.$marathon['facebook'].'" target="_blank">Facebook</a><br>':'';
                            $lien_insta=($marathon['Instagram'])?'<i
                            class="fa fa-instagram"></i><a href="'.$marathon['Instagram'].'" target="_blank">Instagram</a><br>':'';
                            $lien_site=($marathon['site_web'])?'<img src="../../images/website.png" alt=""/><a href="'.$marathon['site_web'].'" target="_blank">Visitez le site du marathon '.$marathon['nom'].'</a><br>':'';
                            $lieu=($marathon['lieu'])?'<img src="../../images/location.png" alt=""/>'.$marathon['lieu'].' ('.$pays_datas['NomPays'].')<br>':'';
                            $proch_date=($next_date)?'<h2 style="font-size: 15px;"><img src="../../images/calendar.png" alt=""/>Le <strong>Marathon de '.$next_date['Nom'].' '.strftime("%Y",strtotime($next_date['DateDebut'])).'</strong> aura lieu le '.utf8_encode(strftime("%d %B",strtotime($next_date['DateDebut']))).'</h2><br>':'';

                            echo '<h1 style="text-transform:uppercase">'.strtoupper($marathon['nom']).' '.$pays_flag.'</h1>'; ?>
                            
                            <?php 
                            $img_src='/images/marathons/'.$marathon['image'];
                            $full_image_path="https://" . $_SERVER['HTTP_HOST'] .$img_src;
                            //echo $full_image_path;
                            $alt = 'alt="'.$full_image_path.'"';
                            if($next_date_video && $next_date_video['video_teaser']){
                                echo $next_date_video['video_teaser'];
                            }
                            else if ($img_src)
                                {
                                    echo '<img class="sp-image" '.$alt.' style="max-width: 100%;"src="'.$img_src.'"/>';
                                }
                            ?>
                            <?php echo '<div  class="marathon-medias">'.'
                            '.$lieu.'
                            '.$lien_site.'
                            '.$lien_facebook.'
                            '.$lien_insta.'
                            '.$lien_youtube.'
                            '.$proch_date.'</div>';?>
                            <div class="mb-40"></div>
                            <?php if($marathon['description']){ ?>
                            <ul class="nav nav-tabs" style="margin-top: 10px;">
                                <?php echo '<li class="active"><a>Présentation</li></a>'; ?>
                            </ul>
                            <p style="font-size: 16px; margin-top: 10px;"><?php echo $marathon['description']; ?></p>
                            <?php } ?>
                        
                        <!--ffffffffff-->
                        <div class="mb-40"></div>
                        <!-- TAB resultats -->
                        <?php if($results){ ?>
                            <ul class="nav nav-tabs" role="tablist">
                                <?php echo '<li class="'.$active_tab2.'"><a href="#tab2" role="tab" data-toggle="tab">Résultats</a></li>'; ?>
                            </ul>
                            <!-- TAB CONTENT -->
                            <div class="tab-content">

                                <?php ($active_tab2!="") ? $cl_fd_tab2="active fade in" : $cl_fd_tab2="fade";
                                ?>
                                <div class="<?php echo $cl_fd_tab2;?> tab-pane" id="tab2">
                                    <div  class="row marathon-detail-resultats">
                                        <?php
                                            foreach ($results as $key => $resultat) {

                                                $cat_event=$ev_cat_event->getEventCatEventByID($resultat->getCategorieID())['donnees']->getIntitule();
                                                $nb_photos=sizeof($res_image->getPhotos($resultat->getID())['donnees']);
                                                ($nb_photos!=0) ? $image_src='<li style="margin-right: 6px;"><img src="../../images/pictos/cam.png" alt=""/></li>':$image_src="";
                                                $event_video=$video->getEventVideoById($resultat->getCategorieID())['donnees'];
                                                ($event_video)? $video_src='<li><img src="../../images/pictos/tv.png" alt=""/></li>':$video_src="";
                                                $pays_flag=$pays->getFlagByAbreviation($resultat->getPaysId())['donnees']['Flag'];
                                                $cat_age=$ev_cat_age->getEventCatAgeByID($resultat->getCategorieageID())['donnees']->getIntitule();
                                                $nom_res=$cat_event.' '.$cat_age.' ('.$resultat->getSexe().') - '.$resultat->getNom().' - '.substr($resultat->getDateDebut(),0,4);
                                                echo '<div class="col-sm-1 marathon-detail-res-link"><a href="/resultats-marathon-'.$resultat->getID().'-'.slugify($nom_res).'.html">'.substr($resultat->getDateDebut(),0,4).'</a></div>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <!--ffffffffff-->
                        <?php }?>
                        <div class="mb-40"></div>
                        <?php if($best_res_mens || $best_res_womens){ ?>
                            <!-- TAB meilleurs chronos -->
                            <ul class="nav nav-tabs" role="tablist">
                                <?php echo '<li class="'.$active_tab3.'"><a href="#tab3" role="tab" data-toggle="tab">Meilleurs chronos enregistrés</a></li>'; ?>
                            </ul>
                            <!-- TAB CONTENT -->
                            <div class="tab-content marathon-details-last-tab">

                                <?php ($active_tab3!="") ? $cl_fd_tab3="active fade in" : $cl_fd_tab3="fade";
                                ?>
                                <div class="<?php echo $cl_fd_tab3;?> tab-pane tops-cronos-container" id="tab3">
                                    <ul class="col-sm-12 resultats top-chronos-ul">
                                        <div class="col-sm-6 top-chronos top-chronos-left-div">
                                            <div class="top-chronos-title">hommes</div>
                                            <ul>
                                                <?php
                                                $i=1;
                                                foreach ($best_res_mens as $key => $value) {
                                                    $pays_name=$value['PaysID'];
                                                    $comp_pays_flag=$pays->getFlagByAbreviation($value['PaysID'])['donnees']['Flag'];
                                                    //$comp_pays_flag=$pays->getFlagByAbreviation($value['payscomp'])['donnees']['Flag'];
                                                    echo '<li> <span class="me-20">'.substr($value['DateDebut'], 0, 4).'</span><span><a  href="athlète-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].' ('.$pays_name.')</a> <br/>'.$value['Temps'].'</span><span class="bold-link classement-date"><img class="marathon-title-flag" style="float:right" src="../../images/flags/'.$comp_pays_flag.'" alt=""/></span></li>';
                                                    $i++;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6 top-chronos top-chronos-right-div">
                                        <div class="top-chronos-title">Femmes</div>
                                            <ul>
                                            <?php
                                            $i=1;
                                                foreach ($best_res_womens as $key => $value) {
                                                    $pays_name=$value['PaysID'];
                                                    $comp_pays_flag=$pays->getFlagByAbreviation($value['PaysID'])['donnees']['Flag'];
                                                    //$comp_pays_flag=$pays->getFlagByAbreviation($value['payscomp'])['donnees']['Flag'];
                                                    echo '<li> <span class="me-20">'.substr($value['DateDebut'], 0, 4).'</span><span><a  href="athlète-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].' ('.$pays_name.')</a> <br/>'.$value['Temps'].'</span><span class="bold-link classement-date"><img class="marathon-title-flag" style="float:right" src="../../images/flags/'.$comp_pays_flag.'" alt=""/></span></li>';
                                                    $i++;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="mb-40"></div>
                        <?php if($best_res_mens_byyear || $best_res_womens_byyear){ ?>
                            <!-- TAB palmares -->
                            <ul class="nav nav-tabs" role="tablist">
                                <?php echo '<li class="'.$active_tab4.'"><a href="#tab4" role="tab" data-toggle="tab">Palmares</a></li>'; ?>
                            </ul>
                            <!-- TAB CONTENT -->
                            <div class="tab-content marathon-details-last-tab">

                                <?php ($active_tab4!="") ? $cl_fd_tab4="active fade in" : $cl_fd_tab4="fade";
                                ?>
                                <div class="<?php echo $cl_fd_tab4;?> tab-pane" id="tab4">
                                    <ul class="col-sm-12 resultats top-chronos-ul palmares-marathon">
                                        <div class="col-sm-6 top-chronos top-chronos-left-div">
                                            <div class="top-chronos-title">hommes</div>
                                            <ul>
                                                <?php
                                                $i=1;
                                                foreach ($best_res_mens_byyear as $key => $value) {
                                                    $pays_name=$value['PaysID'];
                                                    $comp_pays_flag=$pays->getFlagByAbreviation($value['PaysID'])['donnees']['Flag'];
                                                    //$comp_pays_flag=$pays->getFlagByAbreviation($value['payscomp'])['donnees']['Flag'];
                                                    echo '<li> <span class="me-20">'.substr($value['DateDebut'], 0, 4).'</span><span><a  href="athlète-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].' ('.$pays_name.')</a> <br/>'.$value['Temps'].'</span><span class="bold-link classement-date"><img class="marathon-title-flag" style="float:right" src="../../images/flags/'.$comp_pays_flag.'" alt=""/></span></li>';
                                                $i++;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6 top-chronos top-chronos-right-div">
                                        <div class="top-chronos-title">Femmes</div>
                                            <ul>
                                            <?php
                                            $i=1;
                                                foreach ($best_res_womens_byyear as $key => $value) {
                                                    $pays_name=$value['PaysID'];
                                                    $comp_pays_flag=$pays->getFlagByAbreviation($value['PaysID'])['donnees']['Flag'];
                                                    //$comp_pays_flag=$pays->getFlagByAbreviation($value['payscomp'])['donnees']['Flag'];
                                                    echo '<li> <span class="me-20">'.substr($value['DateDebut'], 0, 4).'</span><span><a  href="athlète-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].' ('.$pays_name.')</a> <br/>'.$value['Temps'].'</span><span class="bold-link classement-date"><img class="marathon-title-flag" style="float:right" src="../../images/flags/'.$comp_pays_flag.'" alt=""/></span></li>';                                                $i++;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                        
                                    </ul>
                                    
                                </div>
                            </div>
                            <div class="mb-40"></div>
                            <div>
                                <ul class="col-sm-12 resultats top-chronos-ul palmares-marathon">
                                        <div class="col-sm-12 top-chronos top-chronos-left-div">
                                            
                                            
                                            <div id="chartContainer-m" style="height: 440px; width: 100%;"></div>
                                            <script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
                                            <script src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
                                        </div>
                                        <div class="col-sm-12 top-chronos">
                                        
                                            
                                            <div id="chartContainer-f" style="height: 440px; width: 100%;"></div>
                                            <script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
                                            <script src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
                                        </div>
                                        
                                </ul>
                            </div>
                        <?php } ?>
                        
                        <div class="mb-40"></div>
                    </div>
                </div> <!-- End container page-content -->
            </div>
            <aside class="col-sm-4">
                <dt class="archive">les 10 prochains marathons</dt>
                <dd class="archive next-marathons">
                    <ul class="clearfix">
                        <?php setlocale(LC_TIME, "fr_FR","French");
                                foreach ($autres_marathon as $autre_marathon) {
                                    $pays_flag=$pays->getFlagByAbreviation($autre_marathon['PaysID'])['donnees']['NomPays'];
                                    $nom_res= $autre_marathon['Nom'];
                                    $date_res=strftime("%A %d %B %Y",strtotime($autre_marathon['DateDebut']));
                                    echo '<li><a href="/resultats-marathon-'.$autre_marathon['ID'].'-'.slugify($nom_res).'.html">'.$nom_res.'  ('.$pays_flag.') <br><span class="marathons-date-prochains">'.utf8_encode($date_res).'</span></a></li>';
                                }
                            ?>
                    </ul>
                </dd>
            </aside>
        </div>
    </div>

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

   <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    

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
    <script type="text/javascript">
    
    </script>


    
</body>

</html>