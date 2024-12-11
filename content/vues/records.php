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

include("../classes/pub.php");
$pub=new pub();
$pub728x90=$pub->getBanniere728_90("records")['donnees'];
$pub300x60=$pub->getBanniere300_60("records")['donnees'];
$pub300x250=$pub->getBanniere300_250("records")['donnees'];
$pub160x600=$pub->getBanniere160_600("records")['donnees'];
$pub768x90=$pub->getBanniere768_90("records")['donnees'];
$getMobileAds=$pub->getMobileAds("records")['donnees'];
include("../classes/pays.php");
$pays=new pays();


try{
    require('../database/connexion.php');
    $req1 = $bdd->prepare("SELECT * FROM records r WHERE r.valide =1 and r.sexe='M' order by r.Pays ASC");
    $req1->execute();
    $result1= array();
    while ( $row  = $req1->fetch(PDO::FETCH_ASSOC)) {  
      array_push($result1, $row);
  }

}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}
// du monde
try {
    include("../database/connexion.php");
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='M' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) ORDER BY R.Temps ASC limit 1;");
   $req->execute();
   $world_best=$req->fetch(PDO::FETCH_ASSOC);
   
}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}

// de france
try {
    include("../database/connexion.php");
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='M' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and p.ID=18 ORDER BY R.Temps ASC limit 1;");
   $req->execute();
   $france_best=$req->fetch(PDO::FETCH_ASSOC);
   
}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}

// des jo
try {
    include("../database/connexion.php");
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='M' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and ece.ID=1 ORDER BY R.Temps ASC limit 1;");
   $req->execute();
   $jo_best=$req->fetch(PDO::FETCH_ASSOC);
   
}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}

// du champ du monde
try {
    include("../database/connexion.php");
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='M' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and ece.ID=2 ORDER BY R.Temps ASC limit 1;");
   $req->execute();
   $cm_best=$req->fetch(PDO::FETCH_ASSOC);
   
}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}

// deurope
try {
    include("../database/connexion.php");
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='M' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and ece.ID=3 ORDER BY R.Temps ASC limit 1;");
   $req->execute();
   $ce_best=$req->fetch(PDO::FETCH_ASSOC);
   
}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}

function slugify($text)
{
    $text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
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
    <title>Records du marathon masculin : record du monde, record olympique, continentaux et nationaux
    </title>
    <meta name="description" content="Liste des records du marathon masculin. Record du monde, record olympique, meilleurs performance en Europe, et records nationaux. ">
    <link rel="canonical" href="https://dev.allmarathon.fr/records-marathon-masculins.html" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:title" content="Records du marathon masculin : record du monde, record olympique, continentaux et nationaux" />
    <meta property="og:image" content="https://dev.allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="https://dev.allmarathon.fr/records-marathon-masculins.html" />
    <meta property="og:description" content="Liste des records du marathon masculin. Record du monde, record olympique, meilleurs performance en Europe, et records nationaux. " />

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

    <div class="container page-content mt-77 page-records">
    <div class="row banniere1 ban ban_728x90">
        
            <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
        
            <div  class="col-sm-12 ads-contain">
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
                        <h1>Records du marathon - hommes</h1>
                        <h2>Record du monde, record d’Europe, record olympique, records des grands 
                        championnats et records nationaux du marathon masculin.</h2>
                        <a href="/records-marathon-feminins.html" class="records-link">
                        Records féminins
                        </a>
                        
                        <h1 class="section-record-title">Records internationaux</h1>
                       
                        <div class="title-border"><h3 class="record-title">Record du monde : <?php echo $world_best['Temps'];?></h3></div>
                        <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($world_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                    $nom_pays_coureur=$pays_datas['NomPays'];  
                                                    $nom=$pays->getFlagByAbreviation($world_best['lieu_evenement'])['donnees']['NomPays'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                
                                                echo 'Détenteur : <a href="athlete-'.$world_best['champ_id'].'-'.slugify($world_best['champion']).'.html">'.$world_best['champion'].'</a>'.' ('.$nom_pays_coureur.')';?>

                                            
                                            
                                                <br>
                                                <?php echo 'Date : '.utf8_encode(strftime("%A %d %B %Y",strtotime($world_best['DateDebut']))).' - Lieu : '.$world_best['evenement'].' ('.$nom.')';?>
                                                <br>
                                                <div class="record-title-colored">
                                                    <a href="10-meilleures-performances-mondiales-marathon.html" class="record-link">Les 10 meilleures performances</a>
                                                </div>
                                            </td>
                                            </tr>
                                    </table>
                                    <div class="title-border"><h3 class="record-title">Record de France : <?php echo $france_best['Temps'];?></h3></div>
                                    <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($france_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag']; 
                                                    $nom_pays_coureur=$pays_datas['NomPays'];   
                                                    $nom=$pays->getFlagByAbreviation($france_best['lieu_evenement'])['donnees']['NomPays'];   
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                echo 'Détenteur : <a href="athlete-'.$france_best['champ_id'].'-'.slugify($france_best['champion']).'.html">'.$france_best['champion'].'</a>'.' ('.$nom_pays_coureur.')';?>
                                            
                                                <br>
                                                <?php echo 'Date : '.utf8_encode(strftime("%A %d %B %Y",strtotime($france_best['DateDebut']))).' - Lieu : '.$france_best['evenement'].' ('.$nom.')';?>
                                                <br>
                                                <div class="record-title-colored">
                                                    <a href="10-meilleures-performances-francaises-marathon.html" class="record-link">Les 10 meilleures performances</a>
                                                </div>
                                            </td>
                                            </tr>
                                    </table>
                                    <div class="title-border"><h3 class="record-title">Record des Jeux olympiques : <?php echo $jo_best['Temps'];?></h3></div>
                                    <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($jo_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                    $nom_pays_coureur=$pays_datas['NomPays'];  
                                                    $nom=$pays->getFlagByAbreviation($jo_best['lieu_evenement'])['donnees']['NomPays'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                echo 'Détenteur : <a href="athlete-'.$jo_best['champ_id'].'-'.slugify($jo_best['champion']).'.html">'.$jo_best['champion'].'</a>'.' ('.$nom_pays_coureur.')';?>
                                            
                                                <br>
                                                <?php echo 'Date : '.utf8_encode(strftime("%A %d %B %Y",strtotime($jo_best['DateDebut']))).' - Lieu : '.$jo_best['evenement'].' ('.$nom.')';?>
                                                <br>
                                                <div class="record-title-colored">
                                                    <a href="10-meilleures-performances-jeux-olympiques-marathon.html" class="record-link">Les 10 meilleures performances</a>
                                                </div>
                                            </td>
                                            </tr>
                                    </table>
                                    <div class="title-border"><h3 class="record-title">Record des championnats du monde : <?php echo $cm_best['Temps'];?></h3></div>
                                    <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($cm_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                    $nom_pays_coureur=$pays_datas['NomPays'];  
                                                    $nom=$pays->getFlagByAbreviation($cm_best['lieu_evenement'])['donnees']['NomPays'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                echo 'Détenteur : <a href="athlete-'.$cm_best['champ_id'].'-'.slugify($cm_best['champion']).'.html">'.$cm_best['champion'].'</a>'.' ('.$nom_pays_coureur.')';?>
                                            
                                                <br>
                                                <?php echo 'Date : '.utf8_encode(strftime("%A %d %B %Y",strtotime($cm_best['DateDebut']))).' - Lieu : '.$cm_best['evenement'].' ('.$nom.')';?>
                                                <br>
                                                <div class="record-title-colored">
                                                    <a href="10-meilleures-performances-championnats-du-monde-marathon.html" class="record-link">Les 10 meilleures performances</a>
                                                </div>
                                            </td>
                                            </tr>
                                    </table>
                                    <div class="title-border"><h3 class="record-title">Record des championnats d'Europe: <?php echo $ce_best['Temps'];?></h3></div>
                                    <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($ce_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                    $nom_pays_coureur=$pays_datas['NomPays'];  
                                                    $nom=$pays->getFlagByAbreviation($ce_best['lieu_evenement'])['donnees']['NomPays'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                echo 'Détenteur : <a href="athlete-'.$ce_best['champ_id'].'-'.slugify($ce_best['champion']).'.html">'.$ce_best['champion'].'</a>'.' ('.$nom_pays_coureur.')';?>
                                            
                                                <br>
                                                <?php echo 'Date : '.utf8_encode(strftime("%A %d %B %Y",strtotime($ce_best['DateDebut']))).' - Lieu : '.$ce_best['evenement'].' ('.$nom.')';?>
                                                <br>
                                                <div class="record-title-colored">
                                                    <a href="10-meilleures-performances-championnats-europe-marathon.html" class="record-link">Les 10 meilleures performances</a>
                                                </div>
                                            </td>
                                            </tr>
                                    </table>                                
                            
                    </div>
                </div>
                <div class="row records-nationaux">
                    <div class="col-sm-12">
                        
                        <h1 class="section-record-title">Records nationaux</h1>
                        

                        <div class="table table-responsive lignes-données-nationales">
                            <?php $affichage="";
                            foreach ($result1 as $record) {
                                $pays_datas=$pays->getFlagByName($record['Pays'])['donnees'];
                                if($pays_datas){
                                    $flag=$pays_datas['Flag'];  
                                    $nom_pays_coureur=$pays->getFlagByName($record['Pays'])['donnees']['NomPays'];  
                                    $prefixe_pays_coureur=$pays->getFlagByName($record['Pays'])['donnees']['prefixe'];  
                                    $nom_lieu=$pays->getFlagByAbreviation($record['lieu_evenement'])['donnees']['NomPays']; 
                                }
                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                
                                $affichage.= '<h3 class="record-title">Record '.$prefixe_pays_coureur.' '.$nom_pays_coureur.' : '.$record['Temps'].''.$pays_flag.'</h3>';
                                $affichage.='<div class="mb-30 national-rec">Détenteur : '.'<a href="athlete-'.$record['champ_id'].'-'.slugify($record['champion']).'.html">'.$record['champion'].'</a>'.'
                                                <br>
                                                Date : 
                                               '.utf8_encode(strftime("%d %B %Y",strtotime($record['DateDebut']))).' - Lieu : '.$record['evenement'].' ('. $nom_lieu.')</div>'; 
                             } 
                             echo $affichage;
                             ?>
                           
                        </div>
                    </div>
                </div>

    </div> <!-- End left-side -->

    <aside class="col-sm-4">
        <div class="ban ban_300x60 width-60 mb-30">
            
             <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
            
            <div  class="col-sm-12 ads-contain">
                <?php
                    if($pub300x60 !="") {
                    echo '<a target="_blank" href="'.$pub300x60["url"].'" >';
                        echo $pub300x60["code"] ? $pub300x60["code"] :  "<img src=".'../images/pubs/'.$pub300x60['image'] . " alt='' style=\"width: 100%;\" />";
                        echo '</a>';
                    }
                ?>
            </div>    
        </div>
        <dt class="archive"></dt>
        <div class="ban ban_300x250 to_hide_mobile">
            
             <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
             
            <div  class="col-sm-12 ads-contain">
            <?php
            if($pub300x250 !="") {
                //var_dump($pub300x250["url"]); exit;
                if($pub300x250["code"]==""){
                    echo "<a href=".''.$pub300x250["url"]." target='_blank'><img src=".'../images/pubs/'.$pub300x250['image'] . " alt='' style=\"width: 100%;\" /></a>";
                }
                else{
                    echo $pub300x250["code"];
                }
            }
            ?>
            </div>
        </div>
        <dd class="archive">
            
        </dd>
        
         <div class="ban ban_160-600">
            
             <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
            
            <div  class="col-sm-12 ads-contain">
            <?php
            if($pub160x600 !="") {
                //var_dump($pub160x600["url"]); exit;
                if($pub160x600["code"]==""){
                    echo "<a href=".'https://allmarathon.net/'.$pub160x600["url"]." target='_blank'><img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" /></a>";
                }
                else{
                    echo $pub160x600["code"];
                }
            }?>
         </div>
        </div>
        
        
        
        
        <div class="marg_bot"></div>
        


    </aside>
    
    </div>
<div class="row banniere1 ban ban_768x90 ">
    
     <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
    
    
            <div  class="col-sm-12 ads-contain">
                <?php
                    if($pub768x90 !="") {
                    echo '<a target="_blank" href="'.$pub768x90["url"].'" class="col-sm-12">';
                        echo $pub768x90["code"] ? $pub768x90["code"] :  "<img src=".'../images/pubs/'.$pub768x90['image'] . " alt='' style=\"width: 100%;\" />";
                        echo '</a>';
                    }
                ?>
            </div>
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

    <script src="../../js/main.js"></script>
    
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



    
</body>

</html>