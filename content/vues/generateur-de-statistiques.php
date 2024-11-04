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
}  else $user_session='';

include("../classes/pub.php");
include("../classes/pays.php");
include("../classes/grade.php");
include("../classes/evenement.php");


$pays=new pays();
$liste_pays=$pays->getAll()['donnees'];
$event=new evenement();

$pub=new pub();
$pub728x90=$pub->getBanniere728_90("statistiques")['donnees'];
$pub300x60=$pub->getBanniere300_60("statistiques")['donnees'];
$pub300x250=$pub->getBanniere300_250("statistiques")['donnees'];
$pub160x600=$pub->getBanniere160_600("statistiques")['donnees'];
$pub768x90=$pub->getBanniere768_90("statistiques")['donnees'];
$getMobileAds=$pub->getMobileAds("statistiques")['donnees'];
$years = $event->getAllYears()['donnees'];


?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Générateur de statistiques</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/slider-pro.min.css" />
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="../../js/main.js"></script>

    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <?php include_once('nv_header-integrer.php'); ?>
    <div class="container page-content page-stats-generator">
       
        <section class="section-stats-generator">
        <div class="row banniere1 ban ban_728x90">
             <div class="placeholder-content">
                 <div class="placeholder-title"> Allmarathon </div> 
                 <div class="placeholder-subtitle">publicité</div>
             </div>
            <div  class="col-sm-12 ads-contain"><?php
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
            <div class="gs-presentation">
                <h1 class="sg-h1">Générateur de statistiques</h1>
                <p>
                    Le générateur de statistiques de allmarathon vous permet d'afficher les meilleurs temps réalisés sur marathon selon la période, le pays et le genre. Par exemple vous pouvez obtenir les meilleurs chronos des coureuses françaises pour l'année 2024.
                    <br>
                    Les résultats fournis ne sont pas exhaustifs, ils se basent uniquement sur les résultats enregistrés dans la base de données de allmarathon.
                </p>
            </div>
        <div class="sg-container">

            <div class="sg-col">
                <b class="form-title disp-block">Pays</b>
                <div>
                    <select name="PaysID" id="select-pays">
                        <option value="tous">Tous les pays</option>
                        <?php 
                            foreach ($liste_pays as $pays) {
                                echo '<option value="'.$pays->getAbreviation().'">'.$pays->getNomPays().'</option>';
                            } ?>
                    </select>
                </div>
                <b class="form-title disp-block">Sexe</b>
                <div>
                    <input type="radio" id="homme" name="sexe" value="M" checked/>
                    <label for="homme" class="mr-10">Homme</label>
               
                    <input type="radio" id="femme" name="sexe" value="F" />
                    <label for="femme">Femme</label>
                </div>
                <div class="mb-5">
                    <b class="form-title">Nombre de résultats</b>
                    <input type="number" id="nbr" name="nbr" min="10" max="100" value="10" />
                </div>
            </div>


            <div class="sg-col">
                <b class="form-title disp-block">Période</b>
                <div>
                    <input type="radio" id="mois" name="periode" value="du mois en cours" checked />
                    <label for="mois">Mois en cours</label>
                </div>

                <div>
                    <input type="radio" id="this-year" name="periode" value="de cette année" />
                    <label for="this-year">Année en cours</label>
                </div>

                <div>
                    <input type="radio" id="30dj" name="periode" value="des 30 derniers jours" />
                    <label for="30dj">30 derniers jours</label>
                </div>
                <button id="get_res" class="button-outils bureau get-res">Générer</button>
            </div>
            
            <div class="sg-col pt-d-50">
                <div class="float-r w-fc">
                    <div>
                            <input type="radio" id="specific-year" name="periode" value="specific-year" />
                            <label for="specific-year">Choisir une année</label>
                            <select name="year" id="year-value">
                                <?php
                                foreach ($years as $year ) {
                                    echo '<option value="' . $year['annee'] . '"' . $selected . '>' . $year['annee'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <input type="radio" id="range" name="periode" value="range" />
                            <label for="range">Choisir une période</label>
                        
                            <input id="range-value" type="text" name="daterange" value="01/01/2024 - 01/06/2024" />
                        </div>
                        <button id="get_res-2" class="button-outils mobile get-res">Générer</button>
                    </div>
                </div>

        </div>
    </section>
    <div id="res-box">
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


<script type="text/javascript">
    $(document).ready(function() {
        
        $(".get-res").click(function() {
            var dateRange = $('#range-value').val();
            
            // Séparer la chaîne en deux dates
            var dates = dateRange.split(" - ");
            var date1 = dates[0];
            var date2 = dates[1];
            
            // Fonction pour convertir MM/DD/YYYY en YYYY-MM-DD
            function convertToISO(dateStr) {
                var parts = dateStr.split('/');
                var mm = parts[1];
                var dd = parts[0];
                var yyyy = parts[2];
                return yyyy + '-' + mm + '-' + dd;
            }
            
            // Conversion des deux dates
            var sel_range_deb = convertToISO(date1);
            var sel_range_fin = convertToISO(date2);
            var sel_pays_id=$('#select-pays').val();
            var sel_year=$('#year-value').val();
            //var sel_range=$('#range-value').val();
            var sel_periode=$('input[name="periode"]:checked').val();
            var sel_sexe=$('input[name="sexe"]:checked').val();
            var sel_nbr=$('#nbr').val();
            console.log({
                   function: "getStatistiques",
                   periode:sel_periode,
                   nbr:sel_nbr,
                   sexe:sel_sexe,
                   range_deb:sel_range_deb,
                   range_fin:sel_range_fin,
                   year:sel_year,
                   pays:sel_pays_id,
               })

           $.ajax({
               type: "POST",
               url: "content/classes/statistiques.php",
               data: {
                   function: "getStatistiques",
                   periode:sel_periode,
                   nbr:sel_nbr,
                   sexe:sel_sexe,
                   range_deb:sel_range_deb,
                   range_fin:sel_range_fin,
                   year:sel_year,
                   pays:sel_pays_id,
               },
               success: function(html) {
                   $("#res-box").html(html).show();
                   //console.log("success",html)
                   $('#tableau-stats').DataTable( {
                    paging: false,
                    bFilter: false,
                    bSort: false,
                    info:false,
                    searching: false,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/2.0.8/i18n/fr-FR.json',
                        },
                    } );
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
           )


          

        console.log("all content is ready !!!")
    })
</script>
    
    <?php include_once('footer.inc.php'); ?>

    <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
    <script>
    window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')
    </script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/jquery.jcarousel.min.js"></script>
    <script src="js/jquery.sliderPro.min.js"></script>
    <script src="js/easing.js"></script>
    <script src="js/jquery.ui.totop.min.js"></script>
    <script src="js/herbyCookie.min.js"></script>
    <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js">
    </script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
$(function() {
    // Calculer la date actuelle
  const today = moment();
  // Calculer la date 20 jours dans le passé
  const startDate = today.clone().subtract(20, 'days');
  // Calculer la date 20 jours dans le futur
  const endDate = today;
  $('input[name="daterange"]').daterangepicker({
    "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Appliquer",
        "cancelLabel": "Annuler",
        "fromLabel": "De",
        "toLabel": "À",
        "customRangeLabel": "Personnalisé",
        "weekLabel": "S",
        "daysOfWeek": [
            "Di",
            "Lu",
            "Ma",
            "Me",
            "Je",
            "Ve",
            "Sa"
        ],
        "monthNames": [
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Août",
            "Septembre",
            "Octobre",
            "Novembre",
            "Décembre"
        ],
        "firstDay": 1
    },
    "startDate": startDate,
    "endDate": endDate,
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
   
</body>

</html>
<style type="text/css">
label.error {
    color: red;
}
</style>