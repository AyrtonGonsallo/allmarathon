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

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link href="../styles/admin2009.css?ver=<?php echo rand(111,999)?>" rel="stylesheet" type="text/css" />

<link href="../styles/youtube-data-api.css" rel="stylesheet" type="text/css" />

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="../js/youtube-data-api-chaines.js?ver=<?php echo rand(111,999)?>" rel="stylesheet"></script>
<!--
<script src="../js/evenement-hierarchique-multi-input.js "></script>
  <script src="../js/evenement-hierarchique-script.js"></script>-->
<link rel="icon" type="image/x-icon" href="../images/favicon.ico">

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

<?php require_once "menuAdmin.php"; ?>

<div class="container">







<div id="tabs">

  <ul>

    <li><a href="#tabs-1">Liste des chaines</a></li>

    <li><a href="#tabs-2">Ajouter une chaine</a></li>

  </ul>

  <div id="tabs-1">

        <h4 class="mt-2">liste</h4>

        <p>afficher la liste.</p>

        <ul class="list-group">

            

        <?php 

        try{

              $req = $bdd->prepare("SELECT * FROM `youtube_data_api_chaines2`");

              $req->execute();

              $chaines= array();

              while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  

                array_push($chaines, $row);

                }

        }

        catch(Exception $e)

        {

            die('Erreur : ' . $e->getMessage());

        }

        foreach ($chaines as $chaine){

                echo '<li class="list-group-item" >

                        <div class="row justify-content-center align-items-center">
                            <div class="col-lg-1 col-2">

                                <img src="'.$chaine['image'].'" class="img-fluid" alt="serveur youtube surchargé">

                            </div>
                            <div class="col-lg-8 col-6">

                                <h4 class="mt-2 ">'.$chaine['titre'].'</h4>

                                '.$chaine['description'].'

                            </div>
                            <div class="col-lg-3 col-4">
                                <div class="row justify-content-center align-items-center">
                                    <div class="col-lg-6 col-12">
                                        <a href="'.$chaine['url'].'" target="_blank" class="btn btn-outline-success">Voir</a>
                                    </div>
                                    

                                    <div class="col-lg-6 col-12">

                                        <button onclick="deleteChannel(this,&#34;'.$chaine['idchaine'].'&#34;)"  type="button" class="btn btn-outline-danger">Supprimer</button>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </li>';

            }

            ?>

            

        </ul>

</div>

  <div id="tabs-2">

  <h4 class="mt-2">Rechercher et ajouter des chaines</h4>

                    <p>Commencez par remplir le formulaire -></p>

                    <form class="g-3 was-validated needs-validation" novalidate>

                        <div class="row">

                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" id="byIds" name="mode-recherche" required>
                                <label class="form-check-label" for="byIds">Chercher par liste d'ids</label>
                            </div>
                            <div class="form-check col-6">
                                <input type="radio" class="form-check-input" id="byKeyword" name="mode-recherche" required>
                                <label class="form-check-label" for="byKeyword">Chercher par mot clé ou titre</label>
                                <div class="invalid-feedback">Choisissez un mode de recherche</div>
                            </div>
                        </div>
                        <div class="row" id="motcle">
                            <div class="col-6">

                                <label for="keyword" class="form-label">Mot clé</label>

                            </div>

                            <div class="col-6">

                                <input type="text" class="form-control youtube" id="keyword" required>

                                <div class="invalid-feedback">

                                    Vous devez ajouter un mot clé

                                </div>

                            </div>
                        </div>
                        <div class="row" id="ids">
                            <div class="col-6">

                                <label for="keyword" class="form-label">Liste des Ids</label>

                            </div>

                            <div class="col-6 ">

                                <input type="text" class="form-control youtube" id="listeIDs" required>

                                <div class="invalid-feedback">

                                    Entrez les ids séparés par une virgule

                                </div>

                            </div>

                        </div>

                        <div class="row" id="nombredechaines">

                            <div class="col-6">

                                <label for="total" class="form-label">Nombre de chaines</label>

                            </div>

                            <div class="col-6">

                                <input type="number" class="form-control" id="total" required>

                                <div class="invalid-feedback">

                                    Mentionnez le nombre de chaines a recevoir

                                </div>

                            </div>

                            

                        </div>

                        <div class="col-12">

                            <button class="btn btn-danger" type="submit">Rechercher</button>

                        </div>

                    </form>







                    

                    <div class="row shadow-lg p-3 mb-5 mt-6 bg-body rounded">
                        <ul class="list-group" id="listeChaines">
                        </ul>
                        
                    </div>

                </div>

                <!--fin deuxieme onglet-->

</div>

</div>



    

</div>







<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@3.6.12/dist/js/splide.min.js"></script>     

</body>

<!-- InstanceEnd --></html>









