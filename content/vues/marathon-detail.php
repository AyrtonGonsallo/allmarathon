<?php header("Cache-Control: max-age=2592000");

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
    $req = $bdd->prepare("SELECT * FROM evenements WHERE marathon_id=:mar_id  and (parcours_image!='' or parcours_iframe!='') ORDER BY DateDebut desc limit 1");
    $req->bindValue('mar_id', $id, PDO::PARAM_INT);

    $req->execute();
    $parcours_marathon= array();
    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {    
         array_push($parcours_marathon, $row);
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
    $text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
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
    <title><?php echo $categorie;?> <?php echo $marathon['prefixe'];?> <?php echo $marathon['nom'];?> | allmarathon.fr</title>
    <meta name="Description" content="Toutes les informations sur le marathon <?php echo $marathon['prefixe'];?> <?php echo $marathon['nom'];?> : prochaine édition, résultats des éditions précédentes, records... " lang="fr" xml:lang="fr" />
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:title" content="<?php echo $categorie;?> <?php echo $marathon['prefixe'];?> <?php echo $marathon['nom'];?>  | allmarathon.fr" />
    <meta property="og:description" content="Toutes les informations sur le marathon <?php echo $marathon['prefixe'];?> <?php echo $marathon['nom'];?> : prochaine édition, résultats des éditions précédentes, records... " />
    <meta property="og:image" content="<?php echo 'https://allmarathon.fr/images/marathons/'.$marathon['image'];?>" />
    <meta property="og:url" content="<?php echo 'https://allmarathon.fr/marathons-'.$marathon['id'].'-'.slugify($marathon['nom']).'.html';?>" />

    <?php echo '<link rel="canonical" href="https://allmarathon.fr/marathons-'.$marathon['id'].'-'.slugify($marathon['nom']).'.html" />';?>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  
 
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

<?php } ?>
</head>

<body>



    <?php include_once('nv_header-integrer.php'); ?>
    <div id="parcours-img-viewer">
        <span class="close" onclick="close_model()">&times;</span>
        <img class="parcours-modal-content" id="parcours-full-image" >
    </div>
    <div class="container page-content athlete-detail marathon-detail  mt-77">
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
            <div class="col-sm-12 no-padding-left">
                        <?php 
                            
                           
                            $lien_youtube=($marathon['youtube'])?'<div class="marathon-medias col-xs-3"><img src="../../images/pictos/youtube.svg" alt=""/><a href="'.$marathon['youtube'].'" target="_blank">Youtube</a><br></div>':'';
                            $lien_facebook=($marathon['facebook'])?'<div class="marathon-medias col-xs-3"><img src="../../images/pictos/facebook.svg" alt=""/><a href="'.$marathon['facebook'].'" target="_blank">Facebook</a><br></div>':'';
                            $lien_insta=($marathon['Instagram'])?'<div class="marathon-medias col-xs-3"><img src="../../images/pictos/instagram.svg" alt=""/><a href="'.$marathon['Instagram'].'" target="_blank">Instagram</a><br></div>':'';
                            $lien_site=($marathon['site_web'])?'<div class="marathon-site"><span class="material-symbols-outlined">public</span><a href="'.$marathon['site_web'].'" target="_blank">Visitez le site officiel</a><br></div>':'';
                            $lieu=($marathon['lieu'])?'<span class="material-symbols-outlined">location_on</span>'.$marathon['lieu'].' ('.$pays_datas['NomPays'].')<br>':'';
                            $proch_date=($next_date)?'<div class="next-edition"> Prochaine édition<br>'.utf8_encode(strftime("%A %d %B %Y",strtotime($next_date['DateDebut']))).'<br></div>':'';

                            echo '<h1 style="text-transform:uppercase" class="float-l">'.strtoupper($marathon['nom']).'</h1>'; ?>
                            <span class="marathon-details-breadcumb">
                               <span class="material-symbols-outlined">location_on</span><? echo $pays_datas['continent'];?> > <a href="calendrier-marathons-<?php echo slugify($pays_datas['NomPays']); ?>-<?php echo $pays_datas['ID']; ?>.html"><?php echo $pays_datas['NomPays'];?></a> <?php if($next_date){echo "> <span class='material-symbols-outlined'>event_available</span><span class='capitalize'> <a href='calendrier-marathons-".slugify(utf8_encode(strftime("%B",strtotime($next_date['DateDebut']))))."-".intval(strftime("%m",strtotime($next_date['DateDebut'])))."-".strftime("%Y",strtotime($next_date['DateDebut'])).".html' class='capitalize'>".utf8_encode(strftime("%B",strtotime($next_date['DateDebut'])))."</span>";}?>
                            </span>
                            <?php 
                            $img_src='/images/marathons/'.$marathon['image'];
                            $full_image_path="https://" . $_SERVER['HTTP_HOST'] .$img_src;
                            //echo $full_image_path;
                            $alt = 'alt="'.$full_image_path.'"';
                            
                            if ($img_src)
                                {
                                    echo '<img class="sp-image image-marathon-full-width bureau" '.$alt.' style="max-width: 100%;"src="'.$img_src.'"/>';
                                }
                            ?>
                            <div class="box-next-edition mobile">
                                <?php echo $proch_date; ?>
                                <?php echo $lien_site; ?>
                                <div class="row no-margin-left">
                                    <?php echo $lien_facebook;?>
                                    <?php echo $lien_insta;?>
                                    <?php echo $lien_youtube;?>
                                </div>
                            </div>
                </div>
            </div>
            <div class="col-sm-8 left-side resultat-detail no-padding-left ">
                 <div class="row">
                    <div class="col-sm-12 no-padding-left ">
                            
                            <div class="mb-40"></div>

                            <!-- menu header -->
                            <ul class="nav-category sub-menu">
                                <li> <a data-category="Header_Links" data-action="Sub Menu Click" data-label="Apropos" href="#Apropos" class="sub-menu-link header--nav--link  active" id="Apropos-link">A propos</a></li>
                                <? if ($parcours_marathon){?>
                                <li> <a data-category="Header_Links" data-action="Sub Menu Click" data-label="Parcours" href="#Parcours" class="sub-menu-link header--nav--link " id="Parcours-link">Parcours</a></li>
                                <? }?>
                                <li> <a data-category="Header_Links" data-action="Sub Menu Click" data-label="Résultats" href="#Résultats" class="sub-menu-link header--nav--link " id="Résultats-link">Résultats</a></li>
                                <li> <a data-category="Header_Links" data-action="Sub Menu Click" data-label="Chronos" href="#Chronos" class="sub-menu-link header--nav--link " id="Chronos-link">Chronos</a></li>
                                <li> <a data-category="Header_Links" data-action="Sub Menu Click" data-label="Palmarès" href="#Palmarès" class="sub-menu-link header--nav--link " id="Palmarès-link">Palmarès</a></li>
                            </ul>


                            <?php if($marathon['description']){ ?>
                            
                                <?php echo '<h3 id="Apropos" class="marathon-details-section-title">Présentation</h3>'; ?>
                            
                            <div class="alpine-hide-box" x-data="{ expanded: false }">
                                <div  x-show="expanded" x-collapse.min.150px>
                                 <?php echo $marathon['description']; ?>
                                </div>
                                <div class="alpine-hide-box-gradient"></div>
                                <button class="read-more-button" @click="expanded = ! expanded">+Lire la suite</button>
                            </div>
                            
                            <?php }
                            if($next_date_video && $next_date_video['video_teaser']){
                                echo $next_date_video['video_teaser'];
                            } ?>
                        <div class="mb-40"></div>
                         <!-- TAB parcours -->
                         <?php if($parcours_marathon){ ?>
                            
                                <?php echo '<h3 id="Parcours" class="marathon-details-section-title">Le parcours du marathon '.$marathon['prefixe'].' '.strtoupper($marathon['nom']).' '.strftime("%Y",strtotime($parcours_marathon[0]['DateDebut'])).'</h3>'; ?>
                           
                            <?php if($parcours_marathon[0]["parcours_iframe"]){ 
                                    echo $parcours_marathon[0]["parcours_iframe"];?>

                                <?php }else{
                                    $img_src='/images/events/'.$parcours_marathon[0]["parcours_image"];
                                    echo '<img class="sp-image parcours-img-source" '.$alt.' style="max-width: 100%;"src="'.$img_src.'"/>';
                                    echo '<button class="read-more-button parcours" onclick="full_view(this);">+Voir le parcours en plein écran</button>';
                                }?>
                         <?php }?>
                         
                        <!--ffffffffff-->
                        <div class="mb-40"></div>
                        <!-- TAB resultats -->
                        <?php if($results){ ?>
                            
                                <?php echo '<h3 href="#tab2" role="tab" data-toggle="tab" id="Résultats" class="marathon-details-section-title">Résultats par année</h3>'; ?>
                            
                            <!-- TAB CONTENT -->
                            <div class="tab-content">

                                <?php ($active_tab2!="") ? $cl_fd_tab2="active fade in" : $cl_fd_tab2="fade";
                                ?>
                                
                                    <div  class="row marathon-detail-resultats no-margin-left no-margin-right">
                                        <?php
                                            foreach ($results as $key => $resultat) {

                                                $cat_event=$ev_cat_event->getEventCatEventByID($resultat->getCategorieID())['donnees']->getIntitule();
                                                $nb_photos=sizeof($res_image->getPhotos($resultat->getID())['donnees']);
                                                ($nb_photos!=0) ? $image_src='<li style="margin-right: 6px;"><img src="../../images/pictos/cam.png" alt=""/></li>':$image_src="";
                                                $event_video=$video->getEventVideoById($resultat->getCategorieID())['donnees'];
                                                ($event_video)? $video_src='<li><img src="../../images/pictos/tv.png" alt=""/></li>':$video_src="";
                                                $pays_flag=$pays->getFlagByAbreviation($resultat->getPaysId())['donnees']['Flag'];
                                                $cat_age=$ev_cat_age->getEventCatAgeByID($resultat->getCategorieageID())['donnees']->getIntitule();
                                                $nom_res=$cat_event.' - '.$resultat->getNom().' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat->getDateDebut())));
                                                echo '<div class="col-sm-1 marathon-detail-res-link"><a href="/resultats-marathon-'.$resultat->getID().'-'.slugify($nom_res).'.html">'.substr($resultat->getDateDebut(),0,4).'</a></div>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            
                            <!--ffffffffff-->
                        <?php }?>
                        <div class="mb-40"></div>
                        <?php if($best_res_mens || $best_res_womens){ ?>
                            <!-- TAB meilleurs chronos -->
                           
                                <?php echo '<h3 href="#tab3" id="Chronos" role="tab" data-toggle="tab" class="marathon-details-section-title">Les 10 meilleurs chronos du marathon '.$marathon['prefixe'].' '.strtoupper($marathon['nom']).'</h3>'; ?>
                            
                            <div id="tabs-mc">
                                <ul>
                                    <li><a href="#mc-h">Hommes</a></li>
                                    <li><a href="#mc-f">Femmes</a></li>
                                </ul>
                                <div id="mc-h">
                                    <div class="col-sm-12 top-chronos top-chronos-left-div">
                                        
                                        <table id="tableauHommes-mc" data-page-length='10' class="display">
                                            <thead>
                                                <tr>
                                                    <th style="text-transform: capitalize;">Rang</th>
                                                    <th style="text-transform: capitalize;">athlète</th>
                                                    <th style="text-transform: capitalize;">Pays</th>
                                                    <th style="text-transform: capitalize;">année</th>
                                                    <th style="text-transform: capitalize;">temps</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i=1;
                                                foreach ($best_res_mens as $key => $value) {
                                                    $pays_datas=NULL;
                                                    $pays_display='';
                                                    if($value['DateDebut']>$value['DateChangementNat']){
                                                        $pays_datas=$pays->getFlagByAbreviation($value['NvPaysID'])['donnees'];
                                                        $pays_display=$value['NvPaysID'];
                                                    }else{
                                                        $pays_datas=$pays->getFlagByAbreviation($value['PaysID'])['donnees'];
                                                        $pays_display=$value['PaysID'];
                                                    }
                                                    if($pays_datas){
                                                        $flag=$pays_datas['Flag'];  
                                                    }
                                                    ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                                                    echo '<tr>';
                                                        echo '<td>'.$i.'</td>';
                                                        echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].'</a></td>';
                                                        echo '<td>'.$pays_datas['Abreviation'].'</td>';
                                                        echo '<td>'.$value['annee'].'</td>';
                                                        echo '<td>'.$value['Temps'].'</td>';
                                                    echo '</tr>';
                                                    $i++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="mc-f">
                                    <div class="col-sm-12 top-chronos top-chronos-left-div">
                                        <table id="tableauFemmes-mc" data-page-length='10' class="display">
                                            <thead>
                                                <tr>
                                                <th style="text-transform: capitalize;">Rang</th>
                                                    <th style="text-transform: capitalize;">athlète</th>
                                                    <th style="text-transform: capitalize;">Pays</th>
                                                    <th style="text-transform: capitalize;">année</th>
                                                    <th style="text-transform: capitalize;">temps</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i=1;
                                                foreach ($best_res_womens as $key => $value) {
                                                    $pays_datas=NULL;
                                                    $pays_display='';
                                                    if($value['DateDebut']>$value['DateChangementNat']){
                                                        $pays_datas=$pays->getFlagByAbreviation($value['NvPaysID'])['donnees'];
                                                        $pays_display=$value['NvPaysID'];
                                                    }else{
                                                        $pays_datas=$pays->getFlagByAbreviation($value['PaysID'])['donnees'];
                                                        $pays_display=$value['PaysID'];
                                                    }
                                                    if($pays_datas){
                                                        $flag=$pays_datas['Flag'];  
                                                    }
                                                    ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                                                    echo '<tr>';
                                                        echo '<td>'.$i.'</td>';
                                                        echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].'</a></td>';
                                                        echo '<td>'.$pays_datas['Abreviation'].'</td>';
                                                        echo '<td>'.$value['annee'].'</td>';
                                                        echo '<td>'.$value['Temps'].'</td>';
                                                    echo '</tr>';
                                                    $i++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            
                        <?php } ?>
                       
                        <?php if($best_res_mens_byyear || $best_res_womens_byyear){ ?>
                            
                            <div class="mb-40"></div>
                            <div>
                                <ul class="col-sm-12 resultats top-chronos-ul palmares-marathon">
                                    <div class="col-sm-12 top-chronos top-chronos-left-div">
                                            <script src="https://code.highcharts.com/highcharts.js"></script>
                                            <div id="container-hommes"></div>
                                            <script>
                                                var dataPoints = [];
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
                                                        
                                                        console.log("success",html)
                                                        var données=html;
                                                        
                                            
                                                            for (var i = 0; i < données.length; i++) {
                                                                temps=données[i]["Temps"];
                                                                h= Number(temps.substr(0, 2))
                                                                m= Number(temps.substr(3, 2))
                                                                s= Number(temps.substr(6, 2))
                                                                sec=(3600*h)+(60*m)+s;
                                                                //console.log(h,m,s,sec)
                                                                res=[Number(données[i]["annee"]), Date.UTC(2011, 10, 1,h,m,s)]
                                                                dataPoints.push(res);
                                                            }
                                                            console.log(  dataPoints )
                                                
                                                            Highcharts.chart('container-hommes', {
                                                                    title: {
                                                                    text: 'Évolution du temps du vainqueur - Hommes',
                                                                    align: 'left'
                                                                },

                                                                subtitle: {
                                                                    text: 'Hommes',
                                                                    align: 'left'
                                                                },
                                                                xAxis: {
                                                                    gridLineWidth: 1,
                                                                    
                                                                },
                                                                chart: {
                                                                    
                                                                    style: {
                                                                        fontFamily: 'Poppins-regular'
                                                                    }
                                                                },   
                                                                yAxis: {
                                                                    title: {
                                                                        text: null
                                                                    },
                                                                    type: 'datetime', //y-axis will be in milliseconds
                                                                                dateTimeLabelFormats: { //force all formats to be hour:minute:second
                                                                                    second: '%H:%M:%S',
                                                                                    minute: '%H:%M:%S',
                                                                                    hour: '%H:%M:%S',
                                                                                    day: '%H:%M:%S',
                                                                                    week: '%H:%M:%S',
                                                                                    month: '%H:%M:%S',
                                                                                    year: '%H:%M:%S'
                                                                                },
                                                                },
                                                                tooltip: {
                                                                    shared: true,
                                                                    crosshairs: true,
                                                                    formatter() {
                                                                        return 'Année: '+this.key + '- Temps: <b>' + Highcharts.dateFormat('%H:%M:%S',new Date(this.y)) + '</b>'
                                                                    }
                                                                },
                                                                series: [{
                                                                name: 'Évolution du record des hommes',
                                                                    data: dataPoints
                                                                }]

                                                            });

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
                                            </script>
                                        </div>
                                        <div class="col-sm-12 top-chronos">
                                            <div id="container-femmes"></div>
                                            <script>
                                                var dataPoints2 = [];
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
                                                        
                                            
                                                            for (var i = 0; i < données.length; i++) {
                                                                temps=données[i]["Temps"];
                                                                h= Number(temps.substr(0, 2))
                                                                m= Number(temps.substr(3, 2))
                                                                s= Number(temps.substr(6, 2))
                                                                sec=(3600*h)+(60*m)+s;
                                                                //console.log(h,m,s,sec)
                                                                res=[Number(données[i]["annee"]), Date.UTC(2011, 10, 1,h,m,s)]
                                                                dataPoints2.push(res);
                                                            }
                                                            console.log(  dataPoints2 )
                                                
                                                            Highcharts.chart('container-femmes', {
                                                                    title: {
                                                                    text: 'Évolution du temps du vainqueur - Femmes',
                                                                    align: 'left'
                                                                },

                                                                subtitle: {
                                                                    text: 'Femmes',
                                                                    align: 'left'
                                                                },
                                                                xAxis: {
                                                                    gridLineWidth: 1,
                                                                    
                                                                },
                                                                chart: {
                                                                   
                                                                    style: {
                                                                        fontFamily: 'Poppins-regular'
                                                                    }
                                                                },  
                                                                yAxis: {
                                                                    title: {
                                                                        text: null
                                                                    },
                                                                    type: 'datetime', //y-axis will be in milliseconds
                                                                                dateTimeLabelFormats: { //force all formats to be hour:minute:second
                                                                                    second: '%H:%M:%S',
                                                                                    minute: '%H:%M:%S',
                                                                                    hour: '%H:%M:%S',
                                                                                    day: '%H:%M:%S',
                                                                                    week: '%H:%M:%S',
                                                                                    month: '%H:%M:%S',
                                                                                    year: '%H:%M:%S'
                                                                                },
                                                                },
                                                                tooltip: {
                                                                    shared: true,
                                                                    crosshairs: true,
                                                                    formatter() {
                                                                        return 'Année: '+this.key + '- Temps: <b>' + Highcharts.dateFormat('%H:%M:%S',new Date(this.y)) + '</b>'
                                                                    }
                                                                },
                                                                series: [{
                                                                name: 'Évolution du record des femmes',
                                                                    data: dataPoints2
                                                                }]

                                                            });

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
                                            </script>
                                        </div>
                                        
                                </ul>
                            </div>
                        <?php } ?>
                        
                        <div class="mb-40"></div>
                        <!-- TAB palmares -->
                        
                                <?php echo '<h3 href="#tab4" id="Palmarès"  role="tab" data-toggle="tab" class="marathon-details-section-title">Palmarès</h3>'; ?>
                            
                            <div id="tabs-pal">
                                <ul>
                                    <li><a href="#pal-h">Hommes</a></li>
                                    <li><a href="#pal-f">Femmes</a></li>
                                </ul>
                                <div id="pal-h">
                                    <div class="col-sm-12 top-chronos top-chronos-left-div">
                                        <div class="alpine-hide-box" x-data="{ expanded: false }">
                                            <div x-show="expanded" x-collapse.min.450px>
                                                <table id="tableauHommes-pal" data-page-length='10' class="display">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-transform: capitalize;">année</th>
                                                            <th style="text-transform: capitalize;">athlète</th>
                                                            <th style="text-transform: capitalize;">Pays</th>
                                                            <th style="text-transform: capitalize;">temps</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i=1;
                                                        foreach ($best_res_mens_byyear as $key => $value) {
                                                            $pays_datas=NULL;
                                                            $pays_display='';
                                                            if($value['DateDebut']>$value['DateChangementNat']){
                                                                $pays_datas=$pays->getFlagByAbreviation($value['NvPaysID'])['donnees'];
                                                                $pays_display=$value['NvPaysID'];
                                                            }else{
                                                                $pays_datas=$pays->getFlagByAbreviation($value['PaysID'])['donnees'];
                                                                $pays_display=$value['PaysID'];
                                                            }
                                                            if($pays_datas){
                                                                $flag=$pays_datas['Flag'];  
                                                            }
                                                            ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span><br>':$pays_flag="";
                                                            echo '<tr>';
                                                                echo '<td>'.$value['annee'].'</td>';
                                                                echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].'</a></td>';
                                                                echo '<td>'.$pays_datas['Abreviation'].'</td>';
                                                                echo '<td>'.$value['Temps'].'</td>';
                                                                
                                                            echo '</tr>';
                                                            $i++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="alpine-hide-box-gradient"></div>
                                            <button class="read-more-button" @click="expanded = ! expanded">+Lire la suite</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="pal-f">
                                    <div class="col-sm-12 top-chronos top-chronos-left-div">
                                        <div class="alpine-hide-box" x-data="{ expanded: false }">
                                            <div x-show="expanded" x-collapse.min.450px>
                                                <table id="tableauFemmes-pal" data-page-length='10' class="display">
                                                    <thead>
                                                        <tr>
                                                            <th style="text-transform: capitalize;">année</th>
                                                            <th style="text-transform: capitalize;">athlète</th>
                                                            <th style="text-transform: capitalize;">Pays</th>
                                                        
                                                            <th style="text-transform: capitalize;">temps</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $i=1;
                                                        foreach ($best_res_womens_byyear as $key => $value) {
                                                            $pays_datas=NULL;
                                                            $pays_display='';
                                                            if($value['DateDebut']>$value['DateChangementNat']){
                                                                $pays_datas=$pays->getFlagByAbreviation($value['NvPaysID'])['donnees'];
                                                                $pays_display=$value['NvPaysID'];
                                                            }else{
                                                                $pays_datas=$pays->getFlagByAbreviation($value['PaysID'])['donnees'];
                                                                $pays_display=$value['PaysID'];
                                                            }
                                                            if($pays_datas){
                                                                $flag=$pays_datas['Flag'];  
                                                            }
                                                            ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span><br>':$pays_flag="";
                                                            echo '<tr>';
                                                                echo '<td>'.$value['annee'].'</td>';
                                                                echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['Nom']).'.html">'.$value['Nom'].'</a></td>';
                                                                echo '<td>'.$pays_datas['Abreviation'].'</td>';
                                                                echo '<td>'.$value['Temps'].'</td>';
                                                                
                                                            echo '</tr>';
                                                            $i++;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="alpine-hide-box-gradient"></div>
                                            <button class="read-more-button" @click="expanded = ! expanded">+Lire la suite</button>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <!-- TAB CONTENT -->
                            
                        
                    </div>
                </div> <!-- End container page-content -->
            </div>
            <aside class="col-sm-4 no-padding-right">
                <div class="box-next-edition bureau">
                    <?php echo $proch_date; ?>
                    <?php echo $lien_site; ?>
                    <div class="row no-margin-left">
                        <?php echo $lien_facebook;?>
                        <?php echo $lien_insta;?>
                        <?php echo $lien_youtube;?>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <?php include_once('footer.inc.php'); ?>

    <style type="text/css">

    </style>

    <script data-type="lazy" ddata-src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script data-type="lazy" ddata-src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
     
    <script data-type="lazy" ddata-src="../../js/bootstrap.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/plugins.js"></script>
    <script data-type="lazy" ddata-src="../../js/jquery.jcarousel.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/jquery.sliderPro.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/easing.js"></script>
    <script data-type="lazy" ddata-src="../../js/jquery.ui.totop.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/herbyCookie.min.js"></script>
    <script data-type="lazy" ddata-src="../../js/main.js"></script>

   <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    
  <!-- Alpine Plugins -->
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
 
 <!-- Alpine Core -->
 <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" data-type="lazy" ddata-src="/js/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox -->
    <script type="text/javascript" data-type="lazy" ddata-src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <script type="text/javascript" data-type="lazy" ddata-src="/js/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" data-type="lazy" ddata-src="/js/jquery.fancybox-media.js?v=1.0.6"></script>

    <script type="text/javascript" data-type="lazy" ddata-src="/js/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    
     <script type="text/javascript">
        function full_view(ele){
				let src=ele.parentElement.querySelector(".parcours-img-source").getAttribute("src");
                console.log("srs lightbox",src)
				document.querySelector("#parcours-img-viewer").querySelector("img").setAttribute("src",src);
				document.querySelector("#parcours-img-viewer").style.display="block";
			}
			
			function close_model(){
				document.querySelector("#parcours-img-viewer").style.display="none";
			}
    $(document).ready(function() {
       
        $('#tableauHommes-mc').DataTable( {
                paging: false,
                bFilter: false,
                bSort: false,
                searching: true,
                dom: 't'   
        } );   
        $('#tableauFemmes-mc').DataTable( {
            paging: false,
            bFilter: false,
            bSort: false,
            searching: true,
            dom: 't'   
        } );
        $('#tableauHommes-pal').DataTable( {
            paging: false,
            bFilter: false,
            bSort: false,
            order: [[0, 'desc']],
            searching: true,
            dom: 't'   
        } );   
        $('#tableauFemmes-pal').DataTable( {
            paging: false,
            bFilter: false,
            bSort: false,
            order: [[0, 'desc']],
            searching: true,
            dom: 't'   
        } );
        $("a.sub-menu-link").click(function() {
            var id = $(this).attr('id');
            $("a.sub-menu-link.active").removeClass("active")
            $(this).addClass("active")
            console.log(id)
        });
        $(".read-more-button:not(.parcours)").click(function() {
           
            $(".alpine-hide-box-gradient").hide()
            $(this).hide()
            
        });
       
    });
    </script>
   

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
        
            $( "#tabs-mc" ).tabs();
            $( "#tabs-pal" ).tabs();
    })</script>
</body>

</html>