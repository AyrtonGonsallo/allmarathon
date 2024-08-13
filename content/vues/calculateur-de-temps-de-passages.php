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



$pays=new pays();
$liste_pays=$pays->getAll()['donnees'];

$pub=new pub();
$pub728x90=$pub->getBanniere728_90("outils")['donnees'];
$pub300x60=$pub->getBanniere300_60("outils")['donnees'];
$pub300x250=$pub->getBanniere300_250("outils")['donnees'];
$pub160x600=$pub->getBanniere160_600("outils")['donnees'];
$pub768x90=$pub->getBanniere768_90("outils")['donnees'];



?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
    <?php require_once("../scripts/header_script.php") ?>
    <title>Calculateur de temps de passages</title>
    <meta name="description" content="Que vous soyez un coureur débutant ou expérimenté, notre calculateur va vous permettre de planifier et d'ajuster vos temps de course pour atteindre vos objectifs. Notre calculateur est conçu pour être simple et intuitif à utiliser. Il vous donne la possibilité de calculer les temps de passage au kilomètre sur une distance donnée, en fonction de l'allure souhaitée ou du temps global souhaité. Vous pouvez ainsi ajuster vos temps de passage en fonction de votre rythme de course et de vos objectifs de performance.">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

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


    <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
</head>

<body>
    <?php include_once('nv_header-integrer.php'); ?>
    <div class="container page-content">
        <div class="row banniere1 ban ban_728x90">
            <div  class="col-sm-12"><?php
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
        <section class="page-outil">
        <h1>Calculateur de temps de passage</h1>
        <p>Que vous soyez un coureur débutant ou expérimenté, notre calculateur va vous permettre de planifier et d'ajuster vos temps de course pour atteindre vos objectifs.
    Notre calculateur est conçu pour être simple et intuitif à utiliser. Il vous donne la possibilité de calculer les temps de passage au kilomètre sur une distance donnée, en fonction de l'allure souhaitée ou du temps global souhaité. Vous pouvez ainsi ajuster vos temps de passage en fonction de votre rythme de course et de vos objectifs de performance.</p>
        <div class="mt-50"><strong>Distance à parcourir</strong></div>
        <div>
            <input type="number" placeholder="Distance en kilomètres" id="premier-input" />
            <button id="button_10" class="button-outils">10 km</button>
            <button id="button_s" class="button-outils">Semi</button>
            <button id="button_m" class="button-outils">Marathon</button>
        </div>
        <div><strong><input type="radio" id="par_allure" name="par" value="par_allure"/>Allure souhaitée (au km)</strong></div>
        <div class="ml-21">    
            <input  id="par_allure_minutes" type="number" placeholder="minutes" />
            <input id="par_allure_sec" type="number" placeholder="secondes" />
        </div>
        <div><strong><input type="radio" id="par_temps" name="par" value="par_temps"/>Temps souhaité</strong></div>
        <div class="ml-21">    
            <input id="timeInput" class="html-duration-picker"/>
            <script src="https://cdn.jsdelivr.net/npm/html-duration-picker@latest/dist/html-duration-picker.min.js"></script>

        </div>
        <div>
            <button id="button_cal" class="button-outils">Calculer</button>            
            <button id='button_effacer' class='button-outils'>Effacer</button>

        <div id="reponse" class="mt-50">
        </div>
        <div  class="mt-20">
            <div class="listes-calendrier">
                <div id="zone-tableau1" class="listes-calendrier-element">
                        
                </div>
                <div id="zone-tableau2" class="listes-calendrier-element">
                    
                </div>
                <div id="zone-tableau3" class="listes-calendrier-element">
                    
                </div>
                                
            </div>
        </div>

    </section>
    <div class="row banniere1 ban ban_768x90 mb-30">
            <div  class="col-sm-12">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js" ></script>
<script type="text/javascript">
    $(document).ready(function() {
        
        $("#button_10").click(function() {
            $("#premier-input").val(10);
        })
        $("#button_s").click(function() {
            $("#premier-input").val(21);
        })
        $("#button_m").click(function() {
            $("#premier-input").val(42.195);
        })
        function make_passage_temps(input3_distance,allure){
            //console.log(allure)
            listData=[]
            for(let i=0;i+1<input3_distance;i++){
                let scaled_allure=allure*(i+1)
                var allure_min=Math.floor(scaled_allure/1)
                var allure_sec=Math.round((scaled_allure%allure_min)*60)
                if(allure_min<60){
                    let curr={"km":i+1,"allure_formattee":allure_min+"'"+allure_sec+"''","allure_en_sec":scaled_allure*60}
                    listData.push(curr)
                }else{
                    var allure_hour=Math.floor(allure_min/60)
                    var new_allure_min=allure_min%60
                    let curr={"km":i+1,"allure_formattee":allure_hour+"h "+new_allure_min+" '"+allure_sec+"''","allure_en_sec":scaled_allure*60}
                    listData.push(curr)
                }
            }
            //one more time
            let scaled_allure=allure*(input3_distance)
            var allure_min=Math.floor(scaled_allure/1)
            var allure_sec=Math.round((scaled_allure%allure_min)*60)
            if(allure_min<60){
                let curr={"km":input3_distance,"allure_formattee":allure_min+"'"+allure_sec+"''","allure_en_sec":scaled_allure*60}
                listData.push(curr)
            }else{
                var allure_hour=Math.floor(allure_min/60)
                var new_allure_min=allure_min%60
                let curr={"km":input3_distance,"allure_formattee":allure_hour+"h "+new_allure_min+" '"+allure_sec+"''","allure_en_sec":scaled_allure*60}
                listData.push(curr)
            }
            return listData
        }
        function display_table(yourArray){
            total=yourArray.length
            repartition=Math.round(total/3)
            console.log(total," lignes")
            tableau1=yourArray.slice(0,repartition*1 )
            tableau2=yourArray.slice(repartition*1 ,(repartition*2))
            tableau3=yourArray.slice((repartition*2),total )
            //console.log(yourArray)

            table1data="<table><thead><tr><th style='text-transform: capitalize;'>km</th><th style='text-transform: capitalize;'>temps</th></tr></thead><tbody>";
                tableau1.forEach(function (arrayItem) {
                    table1data+="<tr><td>"+arrayItem["km"]+"</td><td>"+arrayItem["allure_formattee"]+"</td></tr>";
                });
            table1data+="</tbody></table>";

            table2data="<table><thead><tr><th style='text-transform: capitalize;'>km</th><th style='text-transform: capitalize;'>temps</th></tr></thead><tbody>";
                tableau2.forEach(function (arrayItem) {
                    table2data+="<tr><td>"+arrayItem["km"]+"</td><td>"+arrayItem["allure_formattee"]+"</td></tr>";
                });
            table2data+="</tbody></table>";

            table3data="<table><thead><tr><th style='text-transform: capitalize;'>km</th><th style='text-transform: capitalize;'>temps</th></tr></thead><tbody>";
                tableau3.forEach(function (arrayItem) {
                    table3data+="<tr><td>"+arrayItem["km"]+"</td><td>"+arrayItem["allure_formattee"]+"</td></tr>";
                });
            table3data+="</tbody></table>";
            $('#zone-tableau1').html(table1data)
            $('#zone-tableau2').html(table2data)
            $('#zone-tableau3').html(table3data)
            $("#button_effacer").click(function() {
                $('#zone-tableau1').html("")
                $('#zone-tableau2').html("")
                $('#zone-tableau3').html("")
                $("#reponse").text("")
            })
            
            
        }
        $("#button_cal").click(function() {
            input3_distance=parseFloat($("#premier-input").val())
            par_temps=$('#par_temps').is(":checked")
            par_allure=$('#par_allure').is(":checked")
            
            if(par_temps){
                par_temps_heure=$("#timeInput").val();
                parts=par_temps_heure.split(" ")[0].split(":");
                heures=parseInt(parts[0])
                minutes=parseInt(parts[1])
                secondes=parseInt(parts[2])
                allure=((heures*60)+minutes+(secondes/60))/input3_distance
                vitesse=60/allure
                allure_min=Math.floor(allure/1)
                allure_sec=Math.round((allure%allure_min)*60)    
                resultat3=par_temps_heure+" équivaut à une allure de "+allure_min+"'"+allure_sec+"'' min/km et une vitesse de "+vitesse.toFixed(2)+" km/h"
                $("#reponse").text(resultat3)
                listOfData=make_passage_temps(input3_distance,allure)
                display_table(listOfData)
            }else if(par_allure){
                par_allure_minutes=parseFloat($("#par_allure_minutes").val())
                par_allure_sec=parseFloat($("#par_allure_sec").val())
                allure=par_allure_minutes+(par_allure_sec/60)
                vitesse=1/((par_allure_minutes/60)+par_allure_sec/(60*60)) 
                resultat3="Allure :"+par_allure_minutes+"'"+par_allure_sec+"'' min/km soit "+vitesse.toFixed(2)+" km/h"
                $("#reponse").text(resultat3)
                listOfData=make_passage_temps(input3_distance,allure)
                display_table(listOfData)
            }
        })




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
    <script src="js/main.js"></script>
    <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js">
    </script>
<script type="text/javascript">
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

$(document).ready(function() {
    var page=getCookie("page_when_creating_account")
    console.log("page_when_creating_account : "+page)
    });

</script>
    <script type="text/javascript">
    $('#target').validate();
    </script>
    <script src="https://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script type="text/javascript">
    $.datepicker.setDefaults($.datepicker.regional['fr']);
    $("#date_naissance").datepicker();
    $('#date_naissance').datepicker('option', {
        closeText: 'Fermer',
        prevText: 'Précédent',
        nextText: 'Suivant',
        currentText: 'Aujourd\'hui',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre',
            'Octobre', 'Novembre', 'Décembre'
        ],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.',
            'Nov.', 'Déc.'
        ],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        dateFormat: 'yy-mm-dd'
    });
    </script>

    <!-- <script src="/content/scripts/identification_user.js" type="text/javascript"></script> -->


    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
    <script>
    /*
     (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
     function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
     e=o.createElement(i);r=o.getElementsByTagName(i)[0];
     e.src='https://www.google-analytics.com/analytics.js';
     r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
     ga('create','UA-XXXXX-X','auto');ga('send','pageview');
     */
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
<style type="text/css">
label.error {
    color: red;
}
</style>