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

$pub728x90=$pub->getBanniere728_90("records")['donnees'];
$pub300x60=$pub->getBanniere300_60("records")['donnees'];
$pub300x250=$pub->getBanniere300_250("records")['donnees'];
$pub160x600=$pub->getBanniere160_600("records")['donnees'];
$pub768x90=$pub->getBanniere768_90("records")['donnees'];
$getMobileAds=$pub->getMobileAds("records")['donnees'];
// $id=4951; //video
// $id=4465; //images
// $id=4990; //club
$id=33;
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

$type = $evById->getSexe();
$active_tab1="active";
        $active_tab2="";

$destination_path  = "/uploadDocument/";
$ev_cat_event_int_titre=$ev_cat_event->getEventCatEventByID($evById->getCategorieId())['donnees']->getIntitule();
$ev_cat_age_int_titre=$ev_cat_age->getEventCatAgeByID($evById->getCategorieageID())['donnees']->getIntitule();
$annee_titre=substr($evById->getDateDebut(), 0, 4);

function slugify($text)
{
    $text = str_replace('é', 'e', $text); 
    $text = str_replace('û', 'u', $text); 
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}



// --- requettes hommes et femmes
try {
    include("../database/connexion.php");
   $req = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='M' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and ece.ID=2 ORDER BY R.Temps ASC limit 10;");
   $req->execute();
   $world_best_men= array();
   while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {
    //var_dump($row2);exit();  
    array_push($world_best_men, $row);
}
}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}
try {
    include("../database/connexion.php");
   $req2 = $bdd->prepare("SELECT R.ID,C.Nom as champion,C.ID as champ_id,R.Temps,p.NomPays as pays,E.Nom as evenement,E.PaysID as lieu_evenement,E.ID as ev_id,ece.Intitule,E.DateDebut,R.ChampionID FROM evresultats R,evenements E,evcategorieevenement ece, pays p, champions C where C.Sexe='F' and R.EvenementID=E.ID and ece.ID=E.CategorieID and R.ChampionID=C.ID and (p.Abreviation=C.PaysID or p.Abreviation_2=C.PaysID or p.Abreviation_3=C.PaysID or p.Abreviation_4=C.PaysID) and ece.ID=2 ORDER BY R.Temps ASC limit 10;");
   $req2->execute();
   $world_best_women= array();
   while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
    //var_dump($row2);exit();  
    array_push($world_best_women, $row2);
}
}
catch(Exception $e)
{
  die('Erreur : ' . $e->getMessage());
}


?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Championnats du monde : les 10 meilleures performances sur marathon</title>
    <meta name="Description" content="Découvrez les 10 meilleurs performances de tous les temps réalisées sur marathon - Championnats du monde, hommes et femmes." lang="fr" xml:lang="fr" />
    

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
  
    <meta property="og:title" content="Championnats du monde : les 10 meilleures performances sur marathon" />
    <meta property="og:description" content="Découvrez les 10 meilleurs performances de tous les temps réalisées sur marathon - Championnats du monde, hommes et femmes." />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:image" content="https://dev.allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="https://dev.allmarathon.fr/10-meilleures-performances-championnats-du-monde-marathon.html" />
  
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
    


    <link rel="stylesheet" href="../../css/responsive.css">

</head>

<body>



<?php include_once('nv_header-integrer.php'); ?>

<div class="container page-content athlete-detail page-classement-top">
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
                        $pays_datas=$pays->getFlagByAbreviation($evById->getPaysId())['donnees'];
                        if($pays_datas){
                            $flag=$pays_datas['Flag'];  
                        }
                        ($flag!='NULL') ? $pays_flag='<img src="../../images/flags/'.$flag.'" alt=""/>':$pays_flag="";
                        
                        echo '<h1>Championnats du monde : Les 10 meilleures performances sur marathon de tous les temps.</h1>'; 
                        echo '<p>Retrouvez sur cette page les 10 meilleurs chronos réalisés sur marathon.
                        Cette page n\'affiche que les résultats enregistrés sur allmarathon et il peut y avoir des manques.
                        Si vous constatez une erreur vous pouvez nous le signaler en utilisant ce <a href="contact.html" target="_blank">formulaire de contact</a>.
                        </p>'; ?>



                    
                    <!-- TAB CONTENT -->
                    <div class="tab-content">

                        <?php ($active_tab1!="") ? $cl_fd_tab1="active fade in" : $cl_fd_tab1="fade";
                    echo '<div class="'.$cl_fd_tab1.' tab-pane" id="tab1">';
                    
                        ?>
                        <ul class="row">
                            <!--<div class="col-12 resultat_shared">
                                <?php //include_once("shareButtons.php"); ?>
                            </div>-->

                            
                            <li class="col-sm-12"><?php echo '<div id="genre">'.$type.'</div>';?>
                                <br />
                                
                                    <select id="categorie" style="height:34px">
                                        <option value="-h-">Hommes</option>
                                        <option value="-f-">Femmes</option>
                                    </select>
                                
                                
                                
                                
                                    <table id="tableauHommes" data-page-length='25' class="display">
                                        <thead>
                                            <tr>
                                                <th style="text-transform: capitalize;">clt</th>
                                                <th style="text-transform: capitalize;">coureur</th>
                                                <th style="text-transform: capitalize;">pays</th>
                                                <th style="text-transform: capitalize;">temps</th>
                                                <th style="text-transform: capitalize;">course</th>
                                                <th style="text-transform: capitalize;">année</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $ev_res_sexe=$world_best_men;
                                            $i=1;
                                            
                                            foreach ($ev_res_sexe as  $value) {
                                                $pays_datas=NULL;
                                                $pays_display='';
                                                
                                                    $pays_datas=$pays->getFlagByName($value['pays'])['donnees'];
                                                    $pays_display=$value['pays'];
                                                
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                                                echo '<tr>';
                                                    echo '<td>'.$i.'</td>';                                                    
                                                    echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['champion']).'.html">'.$value['champion'].'</a></td>';

                                                    echo '<td>'.$pays_datas['NomPays'].'</td>';
                                                    echo '<td>'.$value['Temps'].'</td>';
                                                    echo '<td>'.$value['evenement'].'</td>';
                                                    echo '<td>'.substr($value['DateDebut'],0,4).'</td>';
                                                echo '</tr>';
                                                $i++;
                                            }
                                                
                                        ?>
                                            
                                        </tbody>
                                    </table>
                                
                                
                                    <table id="tableauFemmes" data-page-length='25' class="display">
                                        <thead>
                                            <tr>
                                                <th style="text-transform: capitalize;">clt</th> 
                                                <th style="text-transform: capitalize;">coureur</th>
                                                <th style="text-transform: capitalize;">pays</th>
                                               
                                                <th style="text-transform: capitalize;">temps</th>
                                                <th style="text-transform: capitalize;">course</th>
                                                <th style="text-transform: capitalize;">année</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            $ev_res_sexe=$world_best_women;
                                            
                                            $i=1;
                                            foreach ($ev_res_sexe as  $value) {
                                                $pays_datas=NULL;
                                                $pays_display='';
                                               
                                                    $pays_datas=$pays->getFlagByName($value['pays'])['donnees'];
                                                    $pays_display=$value['pays'];
                                                
                                                if($pays_datas){
                                                    $flag=$pays_datas['Flag'];  
                                                }
                                                ($flag!='NULL') ? $pays_flag='<span><img src="../../images/flags/'.$flag.'" alt=""/></span>':$pays_flag="";
                                                echo '<tr>';
                                                    echo '<td>'.$i.'</td>';
                                                    echo '<td><a href="athlete-'.$value['ChampionID'].'-'.slugify($value['champion']).'.html">'.$value['champion'].'</a></td>';

                                                    echo '<td>'.$pays_datas['NomPays'].'</td>';
                                                    echo '<td>'.$value['Temps'].'</td>';
                                                    echo '<td>'.$value['evenement'].'</td>';
                                                    echo '<td>'.substr($value['DateDebut'],0,4) .'</td>';
                                                echo '</tr>';
                                                $i++;
                                            }
                                                
                                        ?>
                                            
                                        </tbody>
                                    </table>
                                
                            </li>
                            
                        </ul>
                       
                            
                    </div>
                    
                      
                        
                
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
            </div> </div>
        <dt class="archive">Autres classements</dt>
        <dd class="archive">
            <ul class="clearfix">
            <li>
                <a href="10-meilleures-performances-mondiales-marathon.html" class="record-link">Top 10 du monde</a>
               </li>
               <li>
               <a href="10-meilleures-performances-françaises-marathon.html" class="record-link">Top 10 de France</a>               </li>
               <li>
               <a href="10-meilleures-performances-jeux-olympiques-marathon.html" class="record-link">Top 10 des Jeux olympiques</a>               
                </li>
               
               <li>
               <a href="10-meilleures-performances-championnats-europe-marathon.html" class="record-link">Top 10 des championnats d'Europe</a>               
                </li>
            </ul>
        </dd>
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
        </div></div>
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
        </div></div>
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
$(document).ready(function() {
    
        $('#tableauHommes').DataTable( {
            language: {
                processing:     "Traitement en cours...",
                search:         "",
                lengthMenu: '<select>'+
                '<option value="10">10 lignes</option>'+
                '<option value="25" >25 lignes</option>'+
                '<option value="50">50 lignes</option>'+
                '<option value="100">100 lignes</option>'+
                '</select>',
                info:           "Affichage des &eacute;lements _START_ &agrave; _END_",
                infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 lignes",
                infoFiltered:   "(filtr&eacute; de _MAX_ lignes au total)",
                infoPostFix:    "",
                loadingRecords: "Chargement en cours...",
                zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                emptyTable:     "Aucune donnée disponible dans le tableau",
                paginate: {
                    first:      "Premier",
                    previous:   "Pr&eacute;c&eacute;dent",
                    next:       "Suivant",
                    last:       "Dernier"
                },
                aria: {
                    sortAscending:  ": activer pour trier la colonne par ordre croissant",
                    sortDescending: ": activer pour trier la colonne par ordre décroissant"
                }
            },
            searching: false, paging: false, info: false
    } );
    $('#tableauFemmes').DataTable( {
        language: {
            processing:     "Traitement en cours...",
            search:         "",
            lengthMenu: '<select>'+
            '<option value="10">10 lignes</option>'+
            '<option value="25">25 lignes</option>'+
            '<option value="50">50 lignes</option>'+
            '<option value="100">100 lignes</option>'+
            '</select>',
            info:           "Affichage des &eacute;lements _START_ &agrave; _END_",
            infoEmpty:      "Affichage de l'&eacute;lement 0 &agrave; 0 sur 0 lignes",
            infoFiltered:   "(filtr&eacute; de _MAX_ lignes au total)",
            infoPostFix:    "",
            loadingRecords: "Chargement en cours...",
            zeroRecords:    "Aucun &eacute;l&eacute;ment &agrave; afficher",
            emptyTable:     "Aucune donnée disponible dans le tableau",
            paginate: {
                first:      "Premier",
                previous:   "Pr&eacute;c&eacute;dent",
                next:       "Suivant",
                last:       "Dernier"
            },
            aria: {
                sortAscending:  ": activer pour trier la colonne par ordre croissant",
                sortDescending: ": activer pour trier la colonne par ordre décroissant"
            }
        },
        searching: false, paging: false, info: false
    } );
    
    $('#genre').hide()
    //console.log("genre: ",$('#genre').text())
   
        $('#tableauFemmes_wrapper').hide();
        $('#tableauHommes_wrapper').show();
    
    $('#categorie').change( function() {
        console.log($(this).val())
        //location.href = window.location.pathname.replace('-mf-',$(this).val());
        if($(this).val()=="-m-"){
            $('#tableauFemmes_wrapper').toggle();
            $('#tableauHommes_wrapper').toggle();
        }else{
            $('#tableauHommes_wrapper').toggle();
            $('#tableauFemmes_wrapper').toggle();
        }
    });
    $('.dataTables_filter input[type="search"]').attr('placeholder', 'Trouver un coureur');
    /*$('#tableauHommes').DataTable({
    lengthMenu: [
        [10, 25, 50, 100],
        ['10 lignes', '25 lignes', '50 lignes', '100 lignes'],
    ],
});*/
});
</script>



</body>

</html>