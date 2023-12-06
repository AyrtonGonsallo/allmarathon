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


include("../classes/pays.php");
$pays=new pays();


try{
    require('../database/connexion.php');
    $req1 = $bdd->prepare("SELECT * FROM records r WHERE r.valide =1 and r.sexe='F' order by r.Pays ASC");
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
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement, E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='F' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) ORDER BY R.Temps ASC limit 1;");
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
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement, E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='F' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and p.ID=18 ORDER BY R.Temps ASC limit 1;");
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
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement, E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='F' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and ece.ID=1 ORDER BY R.Temps ASC limit 1;");
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
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement, E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='F' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and ece.ID=2 ORDER BY R.Temps ASC limit 1;");
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
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement, E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='F' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and ece.ID=3 ORDER BY R.Temps ASC limit 1;");
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
    <title>Records du marathon féminin : record du monde, record olympique, continentaux et nationaux
    </title>
    <meta name="description" content="Liste des records du marathon féminin. Record du monde, record olympique, meilleurs performance en Europe, et records nationaux.">
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="canonical" href="https://allmarathon.fr/records-marathon-féminins.html" />
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

    <div class="container page-content">
        <div class="row banniere1">
            <div  class="col-sm-12">

            </div>
        </div>

        <div class="row">
            <div class="col-sm-8 left-side resultat-detail page-records">


                <div class="row">
                    <div class="col-sm-12">
                        <h1>Tous les records du marathon femmes</h1>
                        <h2>Record du monde, record d’Europe, record olympique, records des grands 
                        championnats et records nationaux du marathon féminin.</h2>
                        <a href="/records-marathon-masculins.html" class="records-link">
                        Records masculins du marathon
                        </a><br><br>
                        <div class="title-border"><h3 class="record-title">Record du monde</h3><h3 class="record-title-colored"><a href="10-meilleures-performances-mondiales-marathon.html" class="record-link">Meilleures performances - top 10</a></h3></div>
                                    <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($world_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                    $nom=$pays->getFlagByAbreviation($world_best['lieu_evenement'])['donnees']['NomPays'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                
                                                echo $pays_flag.'<a class="record-link" href="athlete-'.$world_best['champ_id'].'-'.slugify($world_best['champion']).'.html">'.$world_best['champion'].'</a>';?>
                                            </td>
                                            <td>
                                                <?php echo $world_best['Temps'];?>
                                                <br>
                                                <?php echo date_format(date_create($world_best['DateDebut']),"d/m/Y").' - '.$world_best['evenement'].' ('.$nom.')';?>
                                            </td>
                                            </tr>
                                    </table>
                                    <div class="title-border"><h3 class="record-title">Record de France</h3><h3 class="record-title-colored"><a href="10-meilleures-performances-françaises-marathon.html" class="record-link">Meilleures performances - top 10</a></h3></div>
                                    <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($france_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                    $nom=$pays->getFlagByAbreviation($france_best['lieu_evenement'])['donnees']['NomPays'];   
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                echo $pays_flag.'<a class="record-link" href="athlete-'.$france_best['champ_id'].'-'.slugify($france_best['champion']).'.html">'.$france_best['champion'].'</a>';?>
                                            </td>
                                            <td>
                                                <?php echo $france_best['Temps'];?>
                                                <br>
                                                <?php echo date_format(date_create($france_best['DateDebut']),"d/m/Y").' - '.$france_best['evenement'].' ('.$nom.')';?>
                                            </td>
                                            </tr>
                                    </table>
                                    <div class="title-border"><h3 class="record-title">Record des Jeux olympiques</h3><h3 class="record-title-colored"><a href="10-meilleures-performances-jeux-olympiques-marathon.html" class="record-link">Meilleures performances - top 10</a></h3></div>
                                    <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($jo_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                    $nom=$pays->getFlagByAbreviation($jo_best['lieu_evenement'])['donnees']['NomPays'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                echo $pays_flag.'<a class="record-link" href="athlete-'.$jo_best['champ_id'].'-'.slugify($jo_best['champion']).'.html">'.$jo_best['champion'].'</a>';?>
                                            </td>
                                            <td>
                                                <?php echo $jo_best['Temps'];?>
                                                <br>
                                                <?php echo date_format(date_create($jo_best['DateDebut']),"d/m/Y").' - '.$jo_best['evenement'].' ('.$nom.')';?>
                                            </td>
                                            </tr>
                                    </table>
                                    <div class="title-border"><h3 class="record-title">Record des championnats du monde</h3><h3 class="record-title-colored"><a href="10-meilleures-performances-championnats-du-monde-marathon.html" class="record-link">Meilleures performances - top 10</a></h3></div>
                                    <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($cm_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                    $nom=$pays->getFlagByAbreviation($cm_best['lieu_evenement'])['donnees']['NomPays'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                echo $pays_flag.'<a class="record-link" href="athlete-'.$cm_best['champ_id'].'-'.slugify($cm_best['champion']).'.html">'.$cm_best['champion'].'</a>';?>
                                            </td>
                                            <td>
                                                <?php echo $cm_best['Temps'];?>
                                                <br>
                                                <?php echo date_format(date_create($cm_best['DateDebut']),"d/m/Y").' - '.$cm_best['evenement'].' ('.$nom.')';?>
                                            </td>
                                            </tr>
                                    </table>
                                    <div class="title-border"><h3 class="record-title">Record des championnats d'Europe</h3><h3 class="record-title-colored"><a href="10-meilleures-performances-championnats-europe-marathon.html" class="record-link">Meilleures performances - top 10</a></h3></div>
                                    <table class="table table-responsive lignes-données">
                                        <tr class="ligne-rec">
                                            <td>
                                                <?php $pays_datas=$pays->getFlagByName($ce_best['pays'])['donnees'];
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                    $nom=$pays->getFlagByAbreviation($ce_best['lieu_evenement'])['donnees']['NomPays'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                                echo $pays_flag.'<a class="record-link" href="athlete-'.$ce_best['champ_id'].'-'.slugify($ce_best['champion']).'.html">'.$ce_best['champion'].'</a>';?>
                                            </td>
                                            <td>
                                                <?php echo $ce_best['Temps'];?>
                                                <br>
                                                <?php echo date_format(date_create($ce_best['DateDebut']),"d/m/Y").' - '.$ce_best['evenement'].' ('.$nom.')';?>
                                            </td>
                                            </tr>
                                    </table>                                
                            
                    </div>
                </div>
                <div class="row records-nationaux">
                    <div class="col-sm-12">
                    <div class="title-border" style="margin-bottom: 20px;"><h2 class="record-title">Records nationaux</h2></div>

                        <table class="table table-responsive lignes-données-nationales">
                            <?php $affichage="";
                            foreach ($result1 as $record) {
                                $pays_datas=$pays->getFlagByName($record['Pays'])['donnees'];
                                if($pays_datas){
                                    $flag=$pays_datas['Flag'];  
                                    $nom_pays_coureur=$pays->getFlagByName($record['Pays'])['donnees']['NomPays'];  
                                }
                                ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                                
                                $affichage.= '<tr class="ligne-rec intro-nat"><td style="font-weight: bolder;font-size: 16px !important;line-height: 20px;" >Record - '.$nom_pays_coureur.'</td><td style="text-align:right">'.$pays_flag.'</td></tr> ';
                                $affichage.='<tr class="ligne-rec">
                                            
                                            <td>
                                                '.'<a class="record-link" href="athlete-'.$record['champ_id'].'-'.slugify($record['champion']).'.html">'.$record['champion'].'</a>'.'
                                                <br>
                                            </td>
                                            <td>
                                                '.$record['Temps'].'
                                                <br>
                                                <small>'.date_format(date_create($record['DateDebut']),"d/m/Y").' - '.$record['evenement'].'</small>
                                            </td>
                                        </tr> '; 
                             } 
                             echo $affichage;
                             ?>
                            </tbody>
                        </table>
                    </div>
                </div>

    </div> <!-- End left-side -->

    <aside class="col-sm-4">
        <p class="ban"></p>
        <dt class="archive"></dt>
        <dd class="archive">
            
        </dd>
        <div class="marg_bot"></div>
        <p class="ban"></p>
        <div class="marg_bot"></div>
        


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



    
</body>

</html>