<?php

ini_set('display_errors', 1);

ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {

    session_start();

}

    //verif de validiter session

    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))

	{

		header('Location: login.php');

                exit();

    }



    if($_SESSION['admin'] == false){

        header('Location: login.php');

        exit();

    }



    require_once '../database/connexion_youtube.php';



   

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../styles/admin2009.css?ver=<?php echo rand(111,999)?>" rel="stylesheet" type="text/css" />

<link href="../styles/youtube-data-api.css" rel="stylesheet" type="text/css" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<script src="../js/youtube-data-api-videos.js?ver=<?php echo rand(111,999)?>" rel="stylesheet"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/css/splide.min.css">

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

  <script>

  $( function() {

    $( "#tabs" ).tabs();

  } );

  </script>

<title>allmarathon admin</title>









</head>



<body onload="loadvalidator()">

<?php require_once "menuAdmin.php";

global $totaldernieres;

$req2 = $bdd->prepare("SELECT count(*) FROM `youtube_data_api_last_videos2`");
$req2->execute();

$number_of_rows_last = $req2->fetchColumn(); 
 
$totaldernieres=rand(6,15); 
$totaldernieres=($totaldernieres<$number_of_rows_last)?$totaldernieres:$number_of_rows_last;
?>

<div class="container">







    <div id="tabs">

        <ul>

            
            <li><a href="#tabs-2">Videos Récentes</a></li>
            <li><a href="#tabs-3">Rechercher une vidéo</a></li>

            

        </ul>

        <div id="tabs-1">
        </div>
        <!--fin 1er onglet-->
        <div id="tabs-2">
            <h4 class="mt-2">Rechercher plus de vidéos récentes</h4>
            <form id="form-videos-recentes" class="g-3 needs-validation" novalidate>
                <div class="row justify-content-center">
                    <div class="col-6">
                        <label for="chaine" class="form-label">Chaine</label>
                    </div>
                    <div class="col-6">
                        <select class="form-select" id="chaine" required>
                            <option selected value="Toutes">All</option>
                            <?php 
                                try{

                                    $req4 = $bdd->prepare("SELECT * FROM `youtube_data_api_chaines2` order by titre asc ");
                                    $req4->execute();
                                    $chaines= array();
                                    while ( $row  = $req4->fetch(PDO::FETCH_ASSOC)) {  
                                        array_push($chaines, $row);
                                    }
                                }
                                catch(Exception $e){
                                    die('Erreur : ' . $e->getMessage());
                                }
                                foreach ($chaines as $chaine){
                                        echo '<option value="'.$chaine["idchaine"].'">'.$chaine["titre"].'</option>';
                                    }
                            ?>
                        </select>
                        <div class="invalid-feedback">
                            Choisir une chaine
                        </div>
                    </div>
                    <div class="col-6">

                        <label for="totalvr" class="form-label">Nombre de vidéos</label>

                    </div>

                    <div class="col-6">

                        <input type="number" class="form-control" id="totalvr" required>

                        <div class="invalid-feedback">

                            Mentionnez le nombre de vidéos récentes a recevoir

                        </div>

                    </div>
                    <div class="col-4">

                        <button class="btn btn-danger" type="submit">Plus de vidéos récentes</button>

                    </div>
                </div>

                

            </form>
            <h4 class="mt-2">Vidéos récentes</h4>
            <p id="texte"></p>
            
            <div class="row shadow-lg p-3 mb-5 mt-6 bg-body rounded">
                <ul class="list-group" id="listeVR">

                    
                    </ul>

            </div>
            
        </div>

        <!--fin deuxieme onglet-->


        <div id="tabs-3">

                <h4 class="mt-2">Recherche globale</h4>

                <p>Commencez par remplir le formulaire -></p>

                <form class="g-3 needs-validation" novalidate>

                    <div class="row">

                        <div class="col-6">

                            <label for="keyword" class="form-label">Mot clé</label>

                        </div>

                        <div class="col-6 ">

                            <input type="text" class="form-control youtube" id="keyword" required>

                            <div class="invalid-feedback">

                                Vous devez ajouter un mot clé

                            </div>

                        </div>

                        

                    </div>

                    <div class="row">

                        <div class="col-6">

                            <label for="total" class="form-label">Nombre de vidéos</label>

                        </div>

                        <div class="col-6">

                            <input type="number" class="form-control" id="total" required>

                            <div class="invalid-feedback">

                                Mentionnez le nombre de vidéos a recevoir

                            </div>

                        </div>

                        

                    </div>

                    <div class="col-12">

                        <button class="btn btn-danger" type="submit">Rechercher</button>

                    </div>

                </form>
                <div class="row shadow-lg p-3 mb-5 mt-6 bg-body rounded">
                    <ul class="list-group" id="listeResultats">
                    </ul>

                </div>

        </div>

                <!--fin dernier onglet-->

    </div>

</div>



    









<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/js/splide.min.js"></script>     

</body>

<!-- InstanceEnd --></html>



