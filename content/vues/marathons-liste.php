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


include("../classes/marathon.php");
$par_pages=24;
require_once '../database/connexion.php';
if(isset($_GET['page']) ){
    $page=$_GET['page'];
}else{
    $page=0;
}



if(isset($_GET['search']) ){
    
}else{
    
  try {
    require('../database/connexion.php');
    $req = $bdd->prepare("SELECT count(*) AS nbr FROM marathons");
    $req->execute();
    $evenements= $req->fetch(PDO::FETCH_ASSOC);  
}
catch(Exception $e)
{
   die('Erreur : ' . $e->getMessage());
}

  $nb_pages=intval($evenements['nbr']/$par_pages)+1;
  $next=$page+1;
  $previous=$page-1;
}


  $pays=new pays();
  $ev_cat_event=new evCategorieEvenement();
 
  $home_events=getHomeEvents();




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

try{
    $req_pays = $bdd->prepare("SELECT * FROM pays ORDER BY NomPays");
    $req_pays->execute();
    $liste_pays= array();
    while ( $pays_ind  = $req_pays->fetch(PDO::FETCH_ASSOC)) {  
        array_push($liste_pays, $pays_ind);
    }
}
catch(Exception $e){
    die('Erreur : ' . $e->getMessage());
}


try{
    include("../database/connexion.php");

    $req = $bdd->prepare("SELECT * FROM marathons");
    $req->execute();
    $results= array();
    //$first_events= array();
    $last_linked_events= array();
    while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
        $req2 = $bdd->prepare("SELECT * FROM evenements where marathon_id=:mar_id and Valider=1  AND (DateDebut > :today) ORDER BY DateDebut limit 1");
        $req2->bindValue('mar_id', $row["id"], PDO::PARAM_INT);
        
        $req2->bindValue('today', date('Y-m-d'), PDO::PARAM_STR); 
        $req2->execute();
        if($req2->rowCount()>0){
            while ( $row2  = $req2->fetch(PDO::FETCH_ASSOC)) {
                //var_dump($row2);exit();  
                //array_push($first_events, $row2);
                $row['date_prochain_evenement']=$row2['DateDebut'];
                $row['date_prochain_evenement_nom']=$row2['Nom'];
                $row['date_prochain_evenement_id']=$row2['ID'];
                $row['last_linked_events_cat_id']=$row2['CategorieID'];

            }
        }else {
            //array_push($first_events, NULL);
            $row['date_prochain_evenement']='NULL';
            $row['last_linked_events_cat_id']=NULL;

        }

        
        
      array_push($results, $row);
  }}
  catch(Exception $e)
  {
      die('Erreur : ' . $e->getMessage());
  }
  $results_sorted_by_next_event=array_slice(array_msort($results, array('date_prochain_evenement'=>SORT_ASC,'nom'=>SORT_ASC)),0,400);

?>

<!doctype html>

<html class="no-js" lang="fr">



<head>

    <meta charset="utf-8">

    <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">

    <?php require_once("../scripts/header_script.php") ?>

    <title>Agenda - Calendrier des marathons en France et dans le monde | allmarathon.fr</title>

    <meta name="Description" content="Retrouvez tous les marathons en France et dans le monde : agenda, dates, résultats, records, infos." lang="fr" xml:lang="fr">

    <meta property="og:title" content="Agenda - Calendrier des marathons en France et dans le monde." />
    <meta property="og:description" content="Retrouvez tous les marathons en France et dans le monde : agenda, dates, résultats, records, infos." />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:type" content="siteweb" />
    <meta property="og:image" content="https://allmarathon.fr/images/allmarathon.png" />
    <meta property="og:url" content="https://allmarathon.fr/calendrier-agenda-marathons.html" />

    <link rel="canonical" href="https://allmarathon.fr/calendrier-agenda-marathons.html" />

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

    <!--<script data-type='lazy' ddata-src="js/vendor/modernizr-2.8.3.min.js"></script>-->

</head>



<body>





    <?php include_once('nv_header-integrer.php'); ?>





    <div class="container page-content athlètes marathons">

     

        <div class="row banniere1">

            <div  class="col-sm-12"><?php

               
                ?></div>

        </div>



        <div class="row">

            <div class="col-sm-8 left-side">



                <div class="row">



                    <div class="col-sm-12 no-padding-left">


                        <h1>Calendrier des marathons en France et dans le monde</h1>
                        <h2>Histoire, palmarès, résultats, agenda des marathons les plus célèbres : Boston, Chicago, New-York, Londres, Berlin, Tokyo, Paris ...</h2>

                        <form action="" method="post" class="form-inline" role="form">

                            <div class="form-group" style="width:100%; white-space: nowrap; margin-bottom: 5px;">

                                <input type="search" id="search_val_res" placeholder="Recherche" class="form-control"

                                    style="width:93%" />

                                <button type="button" id="goToSearch_Result" class="btn results-search"><i

                                        class="fa fa-search"></i></button>

                            </div>

                        </form>

                        <div>
                        <div class="row">
                            <div class="col-sm-6">
                                <a href="/agenda-marathons-par-pays.html" class="liens-noirs-soulignes-hover-jaune">Liste des marathons par pays</a>
                            </div>
                            <div class="col-sm-6">
                                <a href="/agenda-marathons-par-mois.html" class="liens-noirs-soulignes-hover-jaune">Liste des marathons par mois</a>
                            </div>
                        </div>
                            
                            
                            <script>
                                $(function() {
                                    $( "#date_fin" ).datepicker({
                                        dateFormat: 'yy-mm-dd',
                                        defaultDate:"2024-05-12"
                                    });
                                });
                            </script>                     
                        </div>



                    </div>





                    <ul class="col-sm-12 resultats">
                <div class="row" id="liste-marathons">
                        <?php 
                        $i=0;                             
                        setlocale(LC_TIME, "fr_FR","French");
                        $res="";
                        foreach ($results_sorted_by_next_event as $resultat) {
                
                            $pays_flag=$pays->getFlagByAbreviation($resultat['PaysID'])['donnees']['Flag'];
                           $nom_res= $resultat['nom'];
                
                            $res.= '<div class="col-sm-6 marathon-grid">
                                <a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">
                                    <h4 class="page-marathon-title">'.$nom_res.'<img class="marathon-title-flag" style="float:right" src="../../images/flags/'.$pays_flag.'" alt=""/></h4></a> ';
                                     
                                    $img_src='/images/marathons/thumb_'.$resultat['image'];
                                    $full_image_path="http://" . $_SERVER['HTTP_HOST'] .$img_src;
                                    //$res.= $full_image_path;
                                    
                                    if ($img_src)
                                        {
                                            $res.= '<div class="marathon-liste-image" style="background-image:url('.$img_src.')"></div>';
                                        }else{
                                            $res.= '<div class="marathon-liste-image" style="background-color:#000"></div>';
                                        }
                                     if($resultat['last_linked_events_cat_id']){
                                        $res.= '<div><b>'.$ev_cat_event->getEventCatEventByID($resultat['last_linked_events_cat_id'])['donnees']->getIntitule().'</b></div>';
                
                                     }else{
                                        $res.= '<div><b>Pas d\'évenement</b></div>';
                
                                     }
                                    if($resultat["date_prochain_evenement"]!='NULL'){
                                        $nom_premier_even= $resultat["date_prochain_evenement_nom"];
                                        $id= $resultat["date_prochain_evenement_id"];
                                        $date_premier_even=strftime("%A %d %B %Y",strtotime($resultat["date_prochain_evenement"]));
                                                
                                        $res.= '<div>'.utf8_encode($date_premier_even).'</div>';
                                    }else{
                                        $res.= '<div>Prochaine date à venir</div>';
                                    }
                                    $res.= '<span class="tt-les-infos"><a class="page-marathon-link" href="/marathons-'.$resultat['id'].'-'.slugify($nom_res).'.html">Toutes les infos</a></span>';
                
                                
                            $res.= '</div>';
                            $i++;
                        }
                        echo $res;
                        ?>
                </div>
                    </ul>



                    <div class="clearfix"></div>







                </div>















            </div> <!-- End left-side -->



            <aside class="col-sm-4">
                <dt class="archive">les 10 prochains marathons</dt>
                <dd class="archive next-marathons">
                <ul class="clearfix marathons-home">
                    <?php
                        echo $home_events;
                    ?>
                    <li class="last more-dates"><a href="/calendrier-agenda-marathons.html">[+] de dates</a></li>
                </ul>
                </dd>
            </aside>

        </div>



    </div> <!-- End container page-content -->

<?php 

            //echo $pub768x90; 

            //include("produits_boutique.php");  

            ?>

    <?php include('footer.inc.php'); ?>



    <link href=
'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css'
          rel='stylesheet'>
<script data-type='lazy' ddata-src=
"https://ajax.googleapis.com/ajax/libs/jquery/1.12.1/jquery.min.js" >
    </script>
      
    <script data-type='lazy' ddata-src=
"https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" >
    </script>

   

    <script data-type='lazy' ddata-src="../../js/bootstrap.min.js"></script>

    <script data-type='lazy' ddata-src="../../js/plugins.js"></script>

    <script data-type='lazy' ddata-src="../../js/jquery.jcarousel.min.js"></script>

    <script data-type='lazy' ddata-src="../../js/jquery.sliderPro.min.js"></script>

    <script data-type='lazy' ddata-src="../../js/easing.js"></script>

    <script data-type='lazy' ddata-src="../../js/jquery.ui.totop.min.js"></script>

    <script data-type='lazy' ddata-src="../../js/herbyCookie.min.js"></script>

    <script data-type='lazy' ddata-src="../../js/main.js"></script>







    <script type="text/javascript">

    

    $(document).ready(function() {
        
    var type_req_courrante="getMarathonsbyNextEventDate";
    var ordre_courrant="ASC";
    var par_pages=<?php echo $par_pages;?>;
    var nb_pages=<?php echo $nb_pages;?>;
    var page=<?php echo $page;?>;
    var next=page+1;
    var previous=page-1;
    if(page==(nb_pages-1)){
        style_suivant={'pointer-events': 'none' ,"background-color": "#cccccc22",  'cursor' : 'default'}
    } else{
        style_suivant={'pointer-events': 'all' ,"background-color": "#fbff0b",  'cursor' : 'pointer'}
    }
    if(page==0){
        style_precedent={'pointer-events': 'none' ,"background-color": "#cccccc22", 'cursor' : 'default'}
    } else{
        style_precedent={'pointer-events': 'all' ,"background-color": "#fbff0b",  'cursor' : 'pointer'}
        
    }
    $("#current").text(next);
    $("#total").text(nb_pages);
    $("#next-link").css(style_suivant)
    $("#back-link").css(style_precedent)

    function remise_a_zero() {
        //remise a zero
        page=0;
        next=page+1;
        if(page==(nb_pages-1)){
            style_suivant={'pointer-events': 'none' ,"background-color": "#cccccc22",  'cursor' : 'default'}
        } else{
            style_suivant={'pointer-events': 'all' ,"background-color": "#fbff0b",  'cursor' : 'pointer'}
        }
        if(page==0){
            style_precedent={'pointer-events': 'none' ,"background-color": "#cccccc22", 'cursor' : 'default'}
        } else{
            style_precedent={'pointer-events': 'all' ,"background-color": "#fbff0b",  'cursor' : 'pointer'}
            
        }
        $("#current").text(next);
        $("#next-link").css(style_suivant)
        $("#back-link").css(style_precedent)
        //fin remise a zero
    }

    //données initiales
   /* $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               data: {
                   function: "getMarathonsbyNextEventDate",
                   par_pages:par_pages,
                   offset:page*par_pages,
               },
               success: function(html) {
                   $("#liste-marathons").html(html).show();
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
           })*/
           // -----------
        //requette ajax
        $("#AtoZ").click(function() {
            remise_a_zero()
            type_req_courrante="getMarathonsbyName";
            ordre_courrant="ASC";
           $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               data: {
                   function: "getMarathonsbyName",
                   order:"ASC",
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
               },
               success: function(html) {
                   $("#liste-marathons").html(html).show();
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
        //requette ajax
        $("#ZtoA").click(function() {
            remise_a_zero()
            type_req_courrante="getMarathonsbyName"; 
            ordre_courrant="DESC";

           $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               data: {
                   function: "getMarathonsbyName",
                   order:"DESC",
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
               },
               success: function(html) {
                   $("#liste-marathons").html(html).show();
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
            //requette ajax
           $('#selected_pays').change( function() {
            remise_a_zero()
             type_req_courrante="getMarathonsbyPays";

            var sel_pays_id=$(this).val();
            console.log("recherche sur ",sel_pays_id)
            $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               data: {
                   function: "getMarathonsbyPays",
                   pays_id:sel_pays_id,
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
               },
               success: function(html) {
                   $("#liste-marathons").html(html).show();
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
           })
        });
         //requette ajax
        $("#date_debut").change(function(){
            console.log("date_deb",$("#date_debut").val())
            console.log("date_fin",$("#date_fin").val())
            console.log("page",page)
            console.log("pays",$("#selected_pays").val())
            remise_a_zero()
            type_req_courrante="getMarathonsbyDate";
            var sel_date=$(this).val();
            console.log(sel_date)
            $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               data: {
                   function: "getMarathonsbyDate",
                   debut:sel_date,
                   pays_id:$('#selected_pays').val(),
                   fin:$("#date_fin").val(),
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
               },
               success: function(html) {
                   $("#liste-marathons").html(html).show();
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
           })
        });
         //requette ajax
        $("#date_fin").change(function(){
            console.log("date_deb",$("#date_debut").val())
            console.log("date_fin",$("#date_fin").val())
            console.log("page",page)
            console.log("pays",$("#selected_pays").val())
            remise_a_zero()
            type_req_courrante="getMarathonsbyDate";
            var sel_date=$(this).val();
            //console.log(sel_date)
            $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               data: {
                   function: "getMarathonsbyDate",
                   debut:$("#date_debut").val(),
                   pays_id:$('#selected_pays').val(),
                   fin:sel_date,
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
               },
               success: function(html) {
                   $("#liste-marathons").html(html).show();
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
           })
        });
        //navigation
        $("#next-link").click(function() {
            page+=1; 
            next=page+1;
            if(page==(nb_pages-1)){
                style_suivant={'pointer-events': 'none' ,"background-color": "#cccccc22",  'cursor' : 'default'}
            } else{
                style_suivant={'pointer-events': 'all' ,"background-color": "#fbff0b",  'cursor' : 'pointer'}
            }
            if(page==0){
                style_precedent={'pointer-events': 'none' ,"background-color": "#cccccc22", 'cursor' : 'default'}
            } else{
               style_precedent={'pointer-events': 'all' ,"background-color": "#fbff0b",  'cursor' : 'pointer'}
               
            }
            $(this).css(style_suivant)
            $("#back-link").css(style_precedent)
            $("#current").text(next);
            $("#total").text(nb_pages);
            console.log("page suivante de ",type_req_courrante)
            console.log("date_deb",$("#date_debut").val())
            console.log("date_fin",$("#date_fin").val())
            console.log("ordre",ordre_courrant)
            console.log("offset",page*par_pages)
            console.log("page",page)
            console.log("nbr page",nb_pages)
            console.log("pays",$("#selected_pays").val())
           $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               data: {
                   function:type_req_courrante,
                   order:ordre_courrant,
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
                   debut:$("#date_debut").val(),
                   fin:$("#date_fin").val(),
                   pays_id:$("#selected_pays").val(),
               },
               success: function(html) {
                   $("#liste-marathons").html(html).show();
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
            next=page+1;
            if(page<(nb_pages-1)){
                style_suivant={'pointer-events': 'all' ,"background-color": "#fbff0b", 'cursor' : 'pointer'}
            } else{
                style_suivant={'pointer-events': 'none' ,"background-color": "#cccccc22", 'cursor' : 'default'}

            }
            if(page==0){
                style_precedent={'pointer-events': 'none' ,"background-color": "#cccccc22", 'cursor' : 'default'}
            } else{
               style_precedent={'pointer-events': 'all' ,"background-color": "#fbff0b",  'cursor' : 'pointer'}
               
            }
            $("#next-link").css(style_suivant)
            $(this).css(style_precedent)
            $("#current").text(next);
            $("#total").text(nb_pages);
            console.log("page précedente de ",type_req_courrante)
            console.log("date_deb",$("#date_debut").val())
            console.log("date_fin",$("#date_fin").val())
            console.log("ordre",ordre_courrant)
            console.log("offset",page*par_pages)
            console.log("nbr page",nb_pages)
            console.log("page",page)
            console.log("pays",$("#selected_pays").val())
           $.ajax({
               type: "POST",
               url: "content/classes/marathon.php",
               data: {
                   function:type_req_courrante,
                   order:ordre_courrant,
                   offset:page*par_pages,
                   par_pages:par_pages,
                   page:page,
                   debut:$("#date_debut").val(),
                   fin:$("#date_fin").val(),
                   pays_id:$("#selected_pays").val(),
               },
               success: function(html) {
                   $("#liste-marathons").html(html).show();
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

           
           $("#goToSearch_Result").click(function() {
                search=$("#search_val_res").val()
                console.log("recherche de ",search)
                    console.log("date_deb",$("#date_debut").val())
                    console.log("date_fin",$("#date_fin").val())
                    console.log("ordre",ordre_courrant)
                    console.log("offset",page*par_pages)
                    console.log("page",page)
                    console.log("nbr page",nb_pages)
                    console.log("pays",$("#selected_pays").val())
                $.ajax({
                    type: "POST",
                    url: "content/classes/marathon.php",
                    data: {
                        function:"search",
                        order:ordre_courrant,
                        search:search,
                        offset:page*par_pages,
                        par_pages:par_pages,
                        page:page,
                        debut:$("#date_debut").val(),
                        fin:$("#date_fin").val(),
                        pays_id:$("#selected_pays").val(),
                    },
                    success: function(html) {
                        $("#liste-marathons").html(html).show();
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
                
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if (keycode == 13) {
                    event.preventDefault();
                    search=$("#search_val_res").val()
                    console.log("recherche de ",search)
                        console.log("date_deb",$("#date_debut").val())
                        console.log("date_fin",$("#date_fin").val())
                        console.log("ordre",ordre_courrant)
                        console.log("offset",page*par_pages)
                        console.log("page",page)
                        console.log("nbr page",nb_pages)
                        console.log("pays",$("#selected_pays").val())
                    $.ajax({
                        type: "POST",
                        url: "content/classes/marathon.php",
                        data: {
                            function:"search",
                            order:ordre_courrant,
                            search:search,
                            offset:page*par_pages,
                            par_pages:par_pages,
                            page:page,
                            debut:$("#date_debut").val(),
                            fin:$("#date_fin").val(),
                            pays_id:$("#selected_pays").val(),
                        },
                        success: function(html) {
                            $("#liste-marathons").html(html).show();
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
    
    
    }
)
    </script>




   

    <!--Google+-->

    <script data-type='lazy' ddata-src="https://apis.google.com/js/platform.js" async defer></script>

</body>



</html>