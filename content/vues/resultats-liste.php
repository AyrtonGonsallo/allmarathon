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

$res_olympiques = $event->getEvenementsByCategorieID(1,"Desc");
//var_dump($res_olympiques["donnees"]);exit(-1);
$nb_pages=intval($event->getNumberPages($annee,$comp,$cat,$page,$key_word)['donnees']['COUNT(*)']/40)+1;


 



$next=$page+1;

$previous=$page-1;
$pays_object=new pays();


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

$liste_pays= $pays_object->getAll()["donnees"];

setlocale(LC_TIME, "fr_FR","French");
    try{
        include("../database/connexion.php");
        $req = $bdd->prepare("SELECT count(*) AS nbr FROM evenements");
        $req->execute();
        $nombre_de_lignes_de_res= $req->fetch(PDO::FETCH_ASSOC);
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

    <title>Résultats de tous les marathons nationaux et internationaux | allmarathon.fr</title>

    <meta name="Description" content="Résultats de tous les marathons nationaux et internationaux :  Championnats de France, Championnats d'Europe, Championnats du Monde, Jeux Olympiques, World Major" lang="fr" xml:lang="fr">

    <meta property="og:title" content="Résultats de tous les marathons nationaux et internationaux." />
    <meta property="og:description" content="Résultats de tous les marathons nationaux et internationaux :  Championnats de France, Championnats d'Europe, Championnats du Monde, Jeux Olympiques, World Major." />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:image" content="https://dev.allrathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="https://dev.allrathon.fr/resultats-marathon.html" />
    
    <link rel="canonical" href="https://dev.allrathon.fr/resultats-marathon.html" />


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





    <div class="container page-content athlètes mt-77 page-resultats">

     

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

                ?></div>

        </div>



        <div class="row resultats-liste">

            <div class="col-sm-12 left-side">



                <div class="row">



                    <div class="col-sm-12">


                        <h1 class="float-l">Résultats des marathons en France et dans le monde</h1>
                        <span class="total-marathons bureau"><?php echo $nombre_de_lignes_de_res["nbr"]." évenements";?></span>
                        <h2 class="clear-b">allmarathon propose des résultats de marathons du monde entier et notamment les résultats de tous les marathons olympiques, championnats du monde, championnats d’Europe et championnats de France.</h2>
                        <div class="input-zone-box mw-600">
                            <select name="PaysID" id="select-pays">
                                <option value="all">Pays</option>
                                <?php 
                                    foreach ($liste_pays as $pays) {
                                        echo '<option value="'.$pays->getAbreviation().'">'.$pays->getNomPays().'</option>';
                                    } ?>
                            </select>
                            <form action="" method="post" class="form-inline" role="form">

                                <div class="form-group" style="width:100%; white-space: nowrap;">

                                    <input type="search" id="search_val_res" placeholder="Recherche" class="form-control"

                                        style="width: 93%" />

                                    <button id="goToSearch_Result" type="button"  class="btn btn-primary"><i

                                            class="fa fa-search"></i></button>

                                </div>

                            </form>
                        </div>
                        

                        



                    </div>





                    <ul class="col-sm-12 resultats resultats-grid">

                        <?php

                		foreach ($results['donnees'] as $key => $resultat) {

                			$cat_event=$ev_cat_event->getEventCatEventByID($resultat['CategorieID'])['donnees']->getIntitule();

                			

                            $nb_photos=sizeof($res_image->getPhotos($resultat['ID'])['donnees']);

                            ($nb_photos!=0) ? $image_src='<li style="margin-right: 6px;"><img src="../../images/pictos/cam.png" alt=""/></li>':$image_src="";



                            $event_video=$video->getEventVideoById($resultat['ID'])['donnees'];

                            ($event_video)? $video_src='<li><img src="../../images/pictos/tv.png" alt=""/></li>':$video_src="";

                            $pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
                            $pays_nom=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['NomPays'];
                            $cat_age=$ev_cat_age->getEventCatAgeByID($resultat['CategorieageID'])['donnees']->getIntitule();
                            $date_res=utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut'])));

                            $nom_res='<strong>'.$cat_event.' '.$resultat['prefixe'].' '.$resultat['Nom'].'</strong>';
                            $nom_res_lien=$cat_event.' - '.$resultat['Nom'].' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut'])));

                            echo '<div class="resultats-grid-element"><a href="/resultats-marathon-'.$resultat['ID'].'-'.slugify($nom_res_lien).'.html">'.$nom_res.'</a>
                            <img src="../../images/flags/'.$pays_flag.'" class="float-r" alt=""/><br>
                                '.$pays_nom.
                            '<br><span>'.
                            $date_res.'</span>
                            </div>';
                		}

                        // die;

                	?>

                    </ul>



                    <div class="clearfix"></div>



                    <ul class="pager">
                        <li class="rl-prec"><a href="#" id="back-link">Résultats précédents</a></li>
                        <li class="rl-suiv"><a href="#" id="next-link">Résultats suivants</a></li>
                    </ul>
                    
                    <div class="section-divider"></div>
                </div>
            </div>
        </div>
    </div>
    <section id="olympics-marathons">
        <div class="container">
            <div class="row  resultats-liste">
                <?php if($res_olympiques["donnees"]){?>
                    
                    <ul class="col-sm-12">
                        <h3 class="mt-60">Résultats des marathons olympiques</h3>
                        <div class="resultats resultats-grid mb-80">
                            <?php

                            foreach ($res_olympiques["donnees"] as $key => $resultat) {

                                $cat_event=$ev_cat_event->getEventCatEventByID($resultat['CategorieID'])['donnees']->getIntitule();
                                $pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
                                $pays_nom=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['NomPays'];
                                $date_res=utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut'])));

                                $nom_res='<strong>'.$cat_event.' '.$resultat['prefixe'].' '.$resultat['Nom'].' - '.$resultat['Sexe'].'</strong>';
                                $nom_res_lien=$cat_event.' - '.$resultat['Nom'].' - '.utf8_encode(strftime("%A %d %B %Y",strtotime($resultat['DateDebut'])));

                                echo '<div class="resultats-grid-element"><a href="/resultats-marathon-'.$resultat['ID'].'-'.slugify($nom_res_lien).'.html">'.$nom_res.'</a>
                                <img src="../../images/flags/'.$pays_flag.'" class="float-r" alt=""/><br>
                                '.$pays_nom.
                            '<br><span>'.
                            $date_res.'</span>
                            </div>';

                            }

                            // die;

                            ?>
                        </div>
                    </ul>
                <?php }?>
            </div>
            <div class="row banniere1 ban ban_768x90 ">
                <div class="placeholder-content">
                    <div class="placeholder-title"> Allmarathon </div> 
                    <div class="placeholder-subtitle">publicité</div>
                </div>
            <div  class="col-sm-12 ads-contain"><?php
                if($pub768x90 !="") {
                echo '<a target="_blank" href="'.$pub768x90["url"].'" class="col-sm-12">';
                    echo $pub768x90["code"] ? $pub768x90["code"] :  "<img src=".'../images/pubs/'.$pub768x90['image'] . " alt='' style=\"width: 100%;\" />";
                    echo '</a>';
                }
                ?></div>
        </div>
    </div>
        </div>
        
    </section> <!-- End left-side -->



            
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

var par_pages=39;
    var nb_pages=0;
    var page=0;
    var next=page+1;
    var previous=page-1;

    $(document).ready(function() {
         //requette ajax
         $('#select-pays').change( function() {
            page=0
            var next=page+1;
            var previous=page-1;
            //remise_a_zero()
             type_req_courrante="getEvenementsbyPays";

            var sel_pays_id=$(this).val();
            console.log("recherche sur ",sel_pays_id)
            $.ajax({
               type: "POST",
               url: "content/classes/resultats-liste-ajax.php",
               data: {
                   function: "getEvenementsbyPays",
                   pays_id:sel_pays_id,
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
               },
               success: function(html) {
                   $(".col-sm-12.resultats.resultats-grid").html(html).show();
                  // console.log("success",html)
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
           })
        });

        $("#goToSearch_Result").click(function() {
            page=0
            var next=page+1;
            var previous=page-1;
                search=$("#search_val_res").val()
                console.log("recherche de ",search)
                    
                $.ajax({
                    type: "POST",
                    url: "content/classes/resultats-liste-ajax.php",
                    data: {
                        function:"getEvenementsbySearch",
                        
                        search:search,
                        offset:page*par_pages,
                        par_pages:par_pages,
                        page:page,
                        
                    },
                    success: function(html) {
                        if(html.includes("Pas de")){
                            $(".pager").hide()
                        }else{
                            $(".pager").show()
                        }
                        $(".col-sm-12.resultats.resultats-grid").html(html).show();
                        //console.log("success",html)
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
            })

            $("#search_val_res").keydown(function(event){ 
                page=0
            var next=page+1;
            var previous=page-1;
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == 13) {
                    event.preventDefault();
                    search=$("#search_val_res").val()
                   
                    $.ajax({
                        type: "POST",
                        url: "content/classes/resultats-liste-ajax.php",
                        data: {
                            function:"getEvenementsbySearch",
                           
                            search:search,
                            offset:page*par_pages,
                            par_pages:par_pages,
                            page:page,
                           
                        },
                        success: function(html) {
                            if(html.includes("Pas de")){
                                $(".pager").hide()
                            }else{
                                $(".pager").show()
                            }
                            $(".col-sm-12.resultats.resultats-grid").html(html).show();
                            //console.log("success",html)
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
            })


            $("#back-link").css({"display":"none",'pointer-events': 'none' ,"color": "#000", 'cursor' : 'default'});


            $("#next-link").click(function() {
            page+=1; 
            next=page+1;
            if(page==0){
                style_precedent={"display":"none",'pointer-events': 'none' ,"background-color": "#cccccc22", 'cursor' : 'default',"color": "#000"}
            } else{
               style_precedent={"display":"inline-block",'pointer-events': 'all' ,"background-color": "#fbff0b",  'cursor' : 'pointer',"color": "#2caffe"}
               
            }
            
            
            $("#back-link").css(style_precedent)
            search_v=$("#search_val_res").val();
            pays_v=$('#select-pays').val();
            console.log("page: ",page)
            console.log("de: ",page*par_pages," a ",(page+1)*par_pages)
            if(search_v){
                if(pays_v=="all"){
                    console.log("suivant par keyword")
                }else{
                    console.log("suivant par keyword et par pays")
                }
            }else{
                if(pays_v=="all"){
                    console.log("suivant sans filtre")
                }else{
                    console.log("suivant par pays")
                    
                }
            }
           $.ajax({
               type: "POST",
               url: "content/classes/resultats-liste-ajax.php",
               data: {
                   function:"getNextEvenements",
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
                   search:search_v,
                   pays_id:pays_v,
               },
               success: function(html) {
                //console.log("longueur page suivante",html.length)
                    if(html.length<1){
                        //console.log("pas de res")
                        style_suivant={'pointer-events': 'none' ,"color": "#000",  'cursor' : 'default'}
                    } else{
                        style_suivant={'pointer-events': 'all' ,"color": "#2caffe",  'cursor' : 'pointer'}
                    }
                    $("#next-link").css(style_suivant)
                    $(".col-sm-12.resultats.resultats-grid").html(html).show();
                   //console.log("success",html)
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
           });}
           )


           $("#back-link").click(function() {
            page-=1; 
            next=page-1;
           
                style_suivant={'pointer-events': 'all' ,"color": "#2caffe",  'cursor' : 'pointer'}
            
            if(page==0){
                style_precedent={"display":"none",'pointer-events': 'none' ,"color": "#000", 'cursor' : 'default'}
            } else{
               style_precedent={"display":"inline-block",'pointer-events': 'all' ,"color": "#2caffe",  'cursor' : 'pointer'}
               
            }
            $("#next-link").css(style_suivant)
            $("#back-link").css(style_precedent)
            search_v=$("#search_val_res").val();
            pays_v=$('#select-pays').val();
            console.log("page: ",page)
            console.log("de: ",page*par_pages," a ",(page+1)*par_pages)
            if(search_v){
                if(pays_v=="all"){
                    console.log("suivant par keyword")
                }else{
                    console.log("suivant par keyword et par pays")
                }
            }else{
                if(pays_v=="all"){
                    console.log("suivant sans filtre")
                }else{
                    console.log("suivant par pays")
                    
                }
            }
           $.ajax({
               type: "POST",
               url: "content/classes/resultats-liste-ajax.php",
               data: {
                   function:"getNextEvenements",
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
                   search:search_v,
                   pays_id:pays_v,
               },
               success: function(html) {
                    $(".col-sm-12.resultats.resultats-grid").html(html).show();
                   //console.log("success",html)
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
           });}
           )
    })

    </script>

    <script src="https://apis.google.com/js/platform.js" async defer></script>

</body>



</html>