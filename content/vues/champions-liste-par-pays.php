﻿<?php
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

$paysID=$_GET['paysID'];
include("../classes/champion.php");
include("../classes/pays.php");
include("../classes/pub.php");

$pub=new pub();
$pub728x90=$pub->getBanniere728_90("athlètes")['donnees'];
$pub300x60=$pub->getBanniere300_60("athlètes")['donnees'];
$pub300x250=$pub->getBanniere300_250("athlètes")['donnees'];
$pub160x600=$pub->getBanniere160_600("athlètes")['donnees'];
$pub768x90=$pub->getBanniere768_90("accueil")['donnees'];
$getMobileAds=$pub->getMobileAds("accueil")['donnees'];

$champion=new champion();

$pays=new pays();
$pays_element=$pays->getPaysById($paysID)["donnees"];
$results_initial=$champion->getListChampionsParPays($pays_element->getAbreviation(),$pays_element->getAbreviation2(),$pays_element->getAbreviation3(),$pays_element->getAbreviation4(),$pays_element->getAbreviation5())["donnees"];
                                
$order = 'a';
if(isset($_GET['order']))  $order = $_GET['order'];

if(isset($_POST['search']))
        $order =trim($_POST['search']);
$page=0;
if(isset($_GET['page']) && is_numeric($_GET['page'])) $page = intval($_GET['page']);

$nb_pages=intval($champion->getNumberPage($order)['donnees']['COUNT(*)']/80)+1;
$next=$page+1;
$previous=$page-1;
$olympiques = $champion->getListChampionsOlympics()['donnees'];
function slugify($text)
{
// Swap out Non "Letters" with a -
$text = preg_replace('/[^\pL\d]+/u', '-', $text); 

   // Trim out extra -'s
$text = trim($text, '-');
   // Make text lowercase
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
    <title>Champions de marathon, athlètes célèbres : palmarès, photos et vidéos.</title>
    <meta name="Description" lang="fr" content="Retrouvez les palmarès de <?php echo $nb_champs;?> coureurs, ainsi  que les photos et vidéos des athlètes et marathoniens célèbres. ">

    <link rel="apple-touch-icon" href="apple-favicon.png">
    <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <meta property="og:title" content="Champions de marathon, athlètes célèbres : palmarès, photos et vidéos." />
    <meta property="og:description" content="Retrouvez les palmarès de <?php echo $nb_champs;?> coureurs, ainsi  que les photos et vidéos des athlètes et marathoniens célèbres. " />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:image" content="https://dev.allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="https://dev.allmarathon.fr/liste-des-athletes.html" />

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


    <div class="container page-content athlètes athletes-liste mt-77">
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
            <div class="col-sm-12 left-side">
                <div class="row">
                    <div class="col-sm-12">
                        <a href="/liste-des-athletes.html" class="float-l">Liste des athlètes</a> > <a href="/iste-des-athletes-par-pays.html">Athlètes par pays</a> > <?php echo $pays_element->getNomPays();?>
                        <span class="total-marathons bureau"><?php echo count($results_initial)." athlètes";?></span>
                        <h1 class="clear-b">Athlètes, marathoniens célèbres <?php echo $pays_element->getPrefixe()." ".$pays_element->getNomPays();?></h1>
                    </div>
                    <div class="col-sm-12">
                        <div  id="resultats-recherche-athletes">
                            <ul class="athletes-liste-grid test-image">
                                <?php
                                    foreach ($results_initial as  $resultat) {
                                    $pays_flag = $pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
                                    $pays_nom = $pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['NomPays'];
                                    $champion_name = slugify($resultat['Nom']);
                                    $photos_count = $resultat['t_photos']; // Assuming 't_photos' contains the photo count

                                    // Fetch photos for the current 'resultat'
                                    $photos = $champion->getChampionsPhoto($resultat['ID'])["donnees"]; // Replace with your actual function

                                    echo '<div class="athletes-grid-element">';

                                        // Ensure 'photos' is an array
                                        if (!isset($photos) || !is_array($photos)) {
                                        $photos = []; // Initialize as empty array if not set
                                        }

                                        // Conditionally add the photo if there are photos
                                        if ($photos_count > 0 && is_array($photos)) {
                                        foreach ($photos as $photo) {
                                        if (isset($photo['Galerie_id']) && isset($photo['Nom'])) {
                                        echo '<img class="img-test" src="/images/galeries/'.$photo['Galerie_id'].'/'.$photo['Nom'].'" width="116" height="auto" alt=""/>';
                                        } else {
                                            if($resultat['Sexe']=="M"){
                                                echo '<img class="img-test" src="/images/homme.svg" width="116" height="auto" alt=""/>';

                                            }else{
                                                echo '<img class="img-test" src="/images/femme.svg" width="116" height="auto" alt=""/>';

                                            }
                                        }
                                        }
                                        } else {
                                            if($resultat['Sexe']=="M"){
                                                echo '<img class="img-test" src="/images/homme.svg" width="116" height="auto" alt=""/>';

                                            }else{
                                                echo '<img class="img-test" src="/images/femme.svg" width="116" height="auto" alt=""/>';

                                            }
                                        }

                                        echo '<div>
                                            <a href="athlete-'.$resultat['ID'].'-'.$champion_name.'.html"><strong>'.$resultat['Nom'].'</strong></a>
                                            <img src="../../images/flags/'.$pays_flag.'" class="float-r" alt=""/><br>'.$pays_nom.'<br>
                                            <span><i class="fa-solid fa-medal"></i> ('.$resultat['t_res'].')</span>
                                            <span>- <i class="fa-solid fa-newspaper"></i> ('.$resultat['t_news'].')</span>
                                            <span>- <i class="fa-solid fa-camera"></i> ('.$photos_count.')</span>
                                            <span>- <i class="fa-solid fa-video"></i> ('.$resultat['t_videos'].')</span>
                                            </div>
                                    </div>';
                                    }

                                ?>
                            </ul>
                            
                        </div>
                        
                    </div>
                    

                <div class="clearfix"></div>

            </div> <!-- End left-side -->

            <aside class="col-sm-4 pd-top">
                

               
                
             

            </aside>
            
        </div>
    </div>
    <div class="container">
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
   
    var par_pages=39;
    var nb_pages=0;
    var page=0;
    var next=page+1;
    var previous=page-1;
    function load_pager(){
       // $("#back-link").css({'pointer-events': 'none' ,"color": "#000", 'cursor' : 'default'});

       
        $("#next-link").click(function() {
        page+=1; 
        next=page+1;
        if(page==(nb_pages-1)){
            style_suivant={'pointer-events': 'none' ,"color": "#000",  'cursor' : 'default'}
        } else{
            style_suivant={'pointer-events': 'all' ,"color": "#2caffe",  'cursor' : 'pointer'}
        }
        if(page==0){
            style_precedent={'pointer-events': 'none' ,"color": "#000", 'cursor' : 'default'}
        } else{
        style_precedent={'pointer-events': 'all' ,"color": "#2caffe",  'cursor' : 'pointer'}
        
        }
        $(this).css(style_suivant)
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
        url: "content/classes/athletes-liste-ajax.php",
        data: {
            function:"getNextChampions",
            offset:page*par_pages,
            par_pages:par_pages,
            page:page,
            search:search_v,
            pays_id:pays_v,
        },
        success: function(html) {
                $("#resultats-recherche-athletes").html(html).show();
                
                if(!html){
                    $("#next-link").css({'pointer-events': 'none' ,"color": "#000", 'cursor' : 'default'});
                    console.log("plus de suivants")
                }
                //load_pager()
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
        if(page==0){
            style_precedent={'pointer-events': 'none' ,"color": "#000", 'cursor' : 'default'}
        } else{
        style_precedent={'pointer-events': 'all' ,"color": "#2caffe",  'cursor' : 'pointer'}
        
        }
        $(this).css(style_precedent)
        style_suivant={'pointer-events': 'all' ,"color": "#2caffe",  'cursor' : 'pointer'}
        
        
        //$(this).css(style_suivant)
        $("#next-link").css(style_suivant)
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
        url: "content/classes/athletes-liste-ajax.php",
        data: {
            function:"getNextChampions",
            offset:page*par_pages,
            par_pages:par_pages,
            page:page,
            search:search_v,
            pays_id:pays_v,
        },
        success: function(html) {
                $("#resultats-recherche-athletes").html(html).show();
                
                //load_pager()
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
    }
    $(document).ready(function() {



         //requette ajax
         $('#select-pays').change( function() {
            page=0
            var next=page+1;
            var previous=page-1;
            //remise_a_zero()
             type_req_courrante="getChampionsbyPays";

            var sel_pays_id=$(this).val();
            console.log("recherche sur ",sel_pays_id)
            $.ajax({
               type: "POST",
               url: "content/classes/athletes-liste-ajax.php",
               data: {
                   function: "getChampionsbyPays",
                   pays_id:sel_pays_id,
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
               },
               success: function(html) {
                   $("#resultats-recherche-athletes").html(html).show();
                  
                   //load_pager()
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
                    url: "content/classes/athletes-liste-ajax.php",
                    data: {
                        function:"getChampionsbySearch",
                        
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
                        $("#resultats-recherche-athletes").html(html).show();
                        
                        //load_pager()
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
                        url: "content/classes/athletes-liste-ajax.php",
                        data: {
                            function:"getChampionsbySearch",
                           
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
                            $("#resultats-recherche-athletes").html(html).show();
                           
                            //load_pager()
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

            load_pager()
            
    })

    </script>
    <!--Google+-->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
</body>

</html>