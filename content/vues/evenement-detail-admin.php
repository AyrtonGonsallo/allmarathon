<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!empty($_SESSION['auth_error'])) {
   $erreur_auth=$_SESSION['auth_error'];
   unset($_SESSION['auth_error']);
}else $erreur_auth='';

(!empty($_SESSION['user_id'])) ? $user_id=$_SESSION['user_id'] : $user_id='';
if(!empty($_SESSION['user'])) {
    $user_session=$_SESSION['user'];
    $erreur_auth='';
}  else {
    $user_session='';
}

$id=$_GET['evenementID'];



include("../classes/pub.php");
include("../classes/champion.php");
include("../classes/pays.php");
include("../classes/evresultat.php");
include("../classes/video.php");
include("../classes/user.php");
include("../classes/evenement.php");
include("../classes/evCategorieEvenement.php");
include("../classes/evCategorieAge.php");
include("../classes/championPopularite.php");
include("../classes/abonnement.php");
include("../modules/functions.php");
include("../classes/championAdminExterneClass.php");
include("../classes/departement.php");
include("../classes/region.php");
include("../classes/champion_admin_externe_palmares.php");
include("../classes/image.php");

$image=new image();
$user=new user();
$user_auth=$user->getUserById($user_id)['donnees'];
$admin_palmares=new champion_admin_externe_palmares();
$results=$admin_palmares->getAdminResults($id);
$champAdmin=new championAdminExterne();
$admins=$champAdmin->getAdminExterneByChampion($id)['donnees'];
$pays=new pays();
$event=new evenement();
$evById=$event->getEvenementByID($id)['donnees'];
$ev_cat_event=new evCategorieEvenement();
$ev_cat_age=new evCategorieAge();

function slugify($text)
{
    $text = preg_replace('/[^\pL\d]+/u', '-', $text); 
    $text = trim($text, '-');
    $text = strtolower($text);
    return $text;
}


$champion=new champion();
$tab_user_champ=$champion->getUserChampion($user_auth->getNom(),$user_auth->getPrenom(),$user_auth->getDate_naissance(),$user_auth->getPays());
$user_champ=($tab_user_champ)?$tab_user_champ['donnees']:NULL;


$active_tab1="active";


$page=0;


$ev_res=new evresultat();


$pub=new pub();

$pub728x90=$pub->getBanniere728_90("resultats")['donnees'];
$pub300x60=$pub->getBanniere300_60("resultats")['donnees'];
$pub300x250=$pub->getBanniere300_250("resultats")['donnees'];
$pub160x600=$pub->getBanniere160_600("resultats")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];


try{
    include("../database/connexion.php");
    $req4 = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
    $req4->execute();
    $result4= array();
    while ( $row  = $req4->fetch(PDO::FETCH_ASSOC)) {  
        array_push($result4, $row);
    }
}catch(Exception $e)
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
    <title>Le palmarès de l'athlète  résultats, vidéos, photos</title>
    <meta name="description" content="">
    

    <link rel="apple-touch-icon" href="../../images/favicon.ico">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/font-awesome.min.css">
    <link rel="stylesheet" href="../../css/fonts.css">
    <link rel="stylesheet" href="../../css/slider-pro.min.css" />
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="../../css/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
    <link rel="stylesheet" href="../../css/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />

    <link href="../../css/jquery.filer.css" type="text/css" rel="stylesheet" />
    <link href="../../css/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="../../css/responsive.css">

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

    <!-- Add your site or application content here -->

    <?php include_once('nv_header-integrer.php'); ?>

    <div class="container page-content athlete-detail champion-admin">
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
                            
                            echo '<h1><a class="record-link" href="/resultats-marathon-'.$evById->getID().'.html">'.strtoupper($ev_cat_event_int).' - '.mb_strtoupper($evById->getNom()).'</a> - '.strtoupper($pays_intitule).' '.$pays_flag.' - '.$annee.' <span class="date">'.$date_debut.'</span></h1>'; ?>



                        <!-- TAB NAVIGATION -->
                        <ul class="nav nav-tabs" role="tablist">
                            <?php echo '<li class="'.$active_tab1.'"><a href="#tab1" role="tab" data-toggle="tab">Résultats</a></li>'; ?>
                        </ul>

                        <!-- TAB CONTENT -->
                        
                            <div class="tab-pane">
                                <br><br>
                                <p style="font-size: 1em;">Vous pouvez ajouter des résultats au palmarès. Cela concerne
                                    les Jeux olympiques, championnats du monde, championnats d'Europe, championnats de France, World Majors Marathon. Les résultats que vous insérez sont contrôlés et ils peuvent être supprimés
                                    par l'administrateur du site s'ils sont inexacts.
                                </p>

                                <br />
                                <form action="/content/modules/update_fiche_athlète.php?evenementID=<?php echo $id;?>"
                                    method="post" name="formResult" id="formResult"
                                    style="padding:20px; background-color: #e8e8e8; margin-bottom: 10px;">
                                    <div id="divRang" class="row">
                                        <div class="col-md-3">Rang: </div>
                                        <div class="col-md-5">
                                            <input type="number" name="rang" id="rang" class="update_athlète_input" required
                                                 size="15" />
                                            
                                        </div>
                                    </div>
                                    
                                    <br />
                                    <div id="divDate" class="row">
                                        <div class="col-md-3">Date: </div>
                                        <div class="col-md-5"><input type="text" class="update_athlète_input" required
                                                name="date_comp" id="datepicker_comp" size="15" /></div>
                                    </div>
                                    
                                    <br />
                                    <div id="divtemps" class="row">
                                        <div class="col-md-3">Temps: </div>
                                        <div class="col-md-5"><input type="text" placeholder="XX:XX:XX" class="update_athlète_input" required
                                                name="temps" id="temps" size="15" /></div>
                                    </div>
                                    <br />
                                    <input style="display:none;" id="USER" name="USER" type="text" value="<?php echo $user_auth->getUsername();?>" />
                                    <input style="display:none;" id="champion" name="championID" type="text" value="<?php echo $user_champ->getID();?>" />      
                                    <input style="display:none;" id="evID" name="evID" type="text" value="<?php echo $id; ?>" />

                                   
                                   
                                    <br />
            <?php $nom_res_lien=$ev_cat_event_int.' '. $evById->getNom().' '.strftime("%A %d %B %Y",strtotime( $evById->getDateDebut()));?>
                                    <input type="hidden" name="evenement" id="evenement" value="<?php echo $nom_res_lien; ?>" />
                                    <input type="hidden" name="userResult" id="userResult"
                                        value="<?php echo $_SESSION['user_id'] ?>" />
                                    <div class="row">
                                        <div class="col-md-4" align="left"></div>
                                        <input type="submit" name="submitForm_evenement" class="btn_custom" value="Envoyer" />
                                    </div>
                                    <br>
                                </form>
                                
                            </div>

                            

                        </div>
                        <span style="height:100px;width: 20px;opacity:0;">span</span>

                    </div>
                </div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4">
                <!-- <p class="ban"><a href=""><?php //echo $pub300x60; ?></a></p>
            <p class="ban"><a href=""><?php //echo $pub300x250; ?></a></p> -->
                <p class="ban ban_160-600"><a href=""><?php
if($pub160x600 !="") {
echo $pub160x600["code"] ? $pub160x600["code"] :  "<img src=".'../images/pubs/'.$pub160x600['image'] . " alt='' style=\"width: 100%;\" />";
}
?></a></p>
                <div class="marg_bot"></div>
                <!-- <dt class="facebook">rejoignez-nous sur facebook !</dt>
            <dd class="facebook">
                <div class="fb-page"
                data-href="https://www.facebook.com/alljudonet-108914759155897/"
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



    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins.js"></script>
    <script src="/js/jquery.jcarousel.min.js"></script>
    <script src="/js/jquery.sliderPro.min.js"></script>
    <script src="/js/easing.js"></script>
    <script src="/js/jquery.ui.totop.min.js"></script>
    <script src="/js/herbyCookie.min.js"></script>
    <script src="/js/main.js"></script>
    

    <!-- Add mousewheel plugin (this is optional) -->
    <script type="text/javascript" src="/js/jquery.mousewheel-3.0.6.pack.js"></script>

    <!-- Add fancyBox -->
    <script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>

    <!-- Optionally add helpers - button, thumbnail and/or media -->
    <script type="text/javascript" src="/js/jquery.fancybox-buttons.js?v=1.0.5"></script>
    <script type="text/javascript" src="/js/jquery.fancybox-media.js?v=1.0.6"></script>

    <script type="text/javascript" src="/js/jquery.fancybox-thumbs.js?v=1.0.7"></script>
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

    <script src="/js/jquery.filer.min.js"></script>
    <script src="/js/custom_filer.js"></script>

    <script type="text/javascript">
    $(document).ready(function() {

        $("#datepicker,#datepicker_comp").datepicker();
        $('#datepicker,#datepicker_comp').datepicker('option', {
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                'Septembre', 'Octobre', 'Novembre', 'Décembre'
            ],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août',
                'Sept.', 'Oct.', 'Nov.', 'Déc.'
            ],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            dateFormat: 'yy-mm-dd'
        });

        
      
        
    });
    </script>
    


    


    

    <script src="https://apis.google.com/js/platform.js" async defer></script>
</body>

</html>